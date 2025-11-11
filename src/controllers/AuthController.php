<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../../config/config.php';

class AuthController {
    private $userModel;
    private $categoryModel;

    public function __construct() {
        $this->userModel = new User();
        $this->categoryModel = new Category();
    }

    // Mostra o formulário de registro, passando dados (e erros) para a view
    public function showRegisterForm($data = []) {
        require_once __DIR__ . '/../views/register.php';
    }

    // Processa o registro, agora com validação completa e exibição de erros
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->showRegisterForm();
            return;
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'nome' => trim($_POST['nome']),
            'email' => trim($_POST['email']),
            'senha' => trim($_POST['senha']),
            'confirma_senha' => trim($_POST['confirma_senha']),
            'erros' => []
        ];

        if ($this->userModel->findUserByEmail($data['email'])) {
            $data['erros']['email_err'] = 'Este e-mail já está em uso.';
        }
        if (strlen($data['senha']) < 8 || !preg_match('/[A-Z]/', $data['senha']) || !preg_match('/[a-z]/', $data['senha']) || !preg_match('/[^A-Za-z0-9]/', $data['senha'])) {
            $data['erros']['senha_err'] = 'A senha não atende a todos os requisitos.';
        }
        if ($data['senha'] != $data['confirma_senha']) {
            $data['erros']['confirma_senha_err'] = 'As senhas não coincidem.';
        }
        
        if (empty($data['erros'])) {
            $data['senha_hash'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            $user_id = $this->userModel->register($data);
            
            if ($user_id) {
                // Cria categorias padrão para o novo usuário
                $this->categoryModel->createDefaultCategories($user_id);
                
                header('Location: ' . BASE_URL . '/login');
                exit();
            }
            die('Algo deu errado ao salvar o usuário.');
        } else {
            // Se houver erros, chama a view de registro passando os dados e erros
            $this->showRegisterForm($data);
        }
    }

    public function showLoginForm($erro = null) {
        require_once __DIR__ . '/../views/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->showLoginForm();
            return;
        }
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = ['email' => trim($_POST['email']),'senha' => trim($_POST['senha'])];
        $loggedInUser = $this->userModel->login($data['email'], $data['senha']);

        if ($loggedInUser) {
            $this->createUserSession($loggedInUser);
        } else {
            $this->showLoginForm('Email ou senha inválidos. Tente novamente.');
        }
    }

    public function createUserSession($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];
        header('Location: ' . BASE_URL . '/dashboard');
        exit();
    }
    
    // Exibe o formulário de esqueci minha senha
    public function showForgotPasswordForm($success = null, $erro = null) {
        require_once __DIR__ . '/../views/forgot-password.php';
    }
    
    // Processa a solicitação de recuperação de senha
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->showForgotPasswordForm();
            return;
        }
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $email = trim($_POST['email']);
        
        // Gera o token
        $token = $this->userModel->createPasswordResetToken($email);
        
        if ($token) {
            // Em produção, aqui você enviaria um email
            // Por enquanto, vamos simular mostrando uma mensagem de sucesso
            $success = "Um link de recuperação foi enviado para seu email! (Token: {$token})";
            
            // Redireciona para a página de reset com o token
            // Em produção, o token iria por email
            header('Location: ' . BASE_URL . '/reset-password?token=' . $token);
            exit();
        } else {
            $erro = "Email não encontrado em nossa base de dados.";
            $this->showForgotPasswordForm(null, $erro);
        }
    }
    
    // Exibe o formulário de reset de senha
    public function showResetPasswordForm($erro = null) {
        require_once __DIR__ . '/../views/reset-password.php';
    }
    
    // Processa o reset de senha
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->showResetPasswordForm();
            return;
        }
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        $token = trim($_POST['token']);
        $nova_senha = trim($_POST['nova_senha']);
        $confirma_senha = trim($_POST['confirma_senha']);
        
        // Validações
        if (empty($nova_senha) || empty($confirma_senha)) {
            $erro = "Preencha todos os campos.";
            $this->showResetPasswordForm($erro);
            return;
        }
        
        if ($nova_senha != $confirma_senha) {
            $erro = "As senhas não coincidem.";
            $this->showResetPasswordForm($erro);
            return;
        }
        
        // Valida a força da senha
        if (strlen($nova_senha) < 8 || 
            !preg_match('/[A-Z]/', $nova_senha) || 
            !preg_match('/[a-z]/', $nova_senha) || 
            !preg_match('/[^A-Za-z0-9]/', $nova_senha)) {
            $erro = "A senha não atende aos requisitos de segurança.";
            $this->showResetPasswordForm($erro);
            return;
        }
        
        // Reseta a senha
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        if ($this->userModel->resetPassword($token, $nova_senha_hash)) {
            header('Location: ' . BASE_URL . '/login?success=Senha alterada com sucesso! Faça login com sua nova senha.');
            exit();
        } else {
            $erro = "Token inválido ou expirado. Solicite uma nova recuperação de senha.";
            $this->showResetPasswordForm($erro);
        }
    }
}