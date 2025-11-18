<?php
// Arquivo: src/controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../core/Session.php'; // Novo CORE

class AuthController {
    private $userModel;
    private $categoryModel;

    public function __construct() {
        $this->userModel = new User();
        $this->categoryModel = new Category();
        Session::init(); // Garante que a sessão esteja ativa
    }

    // Mostra o formulário de registro (GET)
    public function showRegisterForm() {
        // Pega erros de validação anteriores (se houver)
        $data['erros'] = Session::getFlash('erros') ?? [];
        require_once __DIR__ . '/../views/register.php';
    }

    // Processa o registro (POST)
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/register');
            exit();
        }

        // Sanitização e Preparação de Dados
        $data = [
            'nome' => filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) ?? '',
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '',
            'senha' => $_POST['senha'] ?? '', // A senha é validada antes de ser hash
            'confirma_senha' => $_POST['confirma_senha'] ?? '',
            'erros' => []
        ];

        // Validação
        if (empty($data['nome']) || empty($data['email']) || empty($data['senha']) || empty($data['confirma_senha'])) {
            $data['erros']['geral'] = 'Preencha todos os campos.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['erros']['email_err'] = 'Email inválido.';
        }
        if ($this->userModel->findUserByEmail($data['email'])) {
            $data['erros']['email_err'] = 'Este e-mail já está em uso.';
        }
        // Requisitos de Segurança da Senha (Mínimo 8, Maiúscula, Minúscula, Especial)
        if (strlen($data['senha']) < 8 || !preg_match('/[A-Z]/', $data['senha']) || !preg_match('/[a-z]/', $data['senha']) || !preg_match('/[^A-Za-z0-9]/', $data['senha'])) {
            $data['erros']['senha_err'] = 'A senha não atende aos requisitos de segurança.';
        }
        if ($data['senha'] !== $data['confirma_senha']) {
            $data['erros']['confirma_senha_err'] = 'As senhas não coincidem.';
        }
        
        if (empty($data['erros'])) {
            $data['senha_hash'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            $user_id = $this->userModel->register($data);
            
            if ($user_id) {
                // Cria categorias padrão para o novo usuário
                $this->categoryModel->createDefaultCategories($user_id);
                
                Session::setFlash('success', 'Cadastro realizado com sucesso! Faça login.');
                header('Location: ' . BASE_URL . '/login');
                exit();
            }
            Session::setFlash('error', 'Erro ao processar o cadastro. Tente novamente.');
            header('Location: ' . BASE_URL . '/register');
            exit();
        } else {
            // Se houver erros, envia para a view mostrar
            Session::setFlash('erros', $data['erros']);
            $this->showRegisterForm($data);
        }
    }

    // Mostra o formulário de login (GET)
    public function showLoginForm() {
        $erro = Session::getFlash('error');
        $success = Session::getFlash('success');
        require_once __DIR__ . '/../views/login.php';
    }

    // Processa o login (POST)
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
        $senha = $_POST['senha'] ?? ''; 

        if (empty($email) || empty($senha)) {
            Session::setFlash('error', 'Preencha todos os campos.');
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $loggedInUser = $this->userModel->login($email, $senha);

        if ($loggedInUser) {
            $this->createUserSession($loggedInUser);
        } else {
            Session::setFlash('error', 'Email ou senha inválidos. Tente novamente.');
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];
        header('Location: ' . BASE_URL . '/dashboard');
        exit();
    }
    
    // Exibe o formulário de esqueci minha senha (GET)
    public function showForgotPasswordForm() {
        $success = Session::getFlash('success');
        $erro = Session::getFlash('error');
        require_once __DIR__ . '/../views/forgot-password.php';
    }
    
    // Processa a solicitação de recuperação de senha (POST)
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/forgot-password');
            exit();
        }
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::setFlash('error', 'Email inválido.');
            header('Location: ' . BASE_URL . '/forgot-password');
            exit();
        }

        $token = $this->userModel->createPasswordResetToken($email);
        
        if ($token) {
            // Em um ambiente de produção, o token seria enviado por e-mail.
            Session::setFlash('success', "Um link de recuperação foi 'enviado' para seu email! (Token: {$token})");
            header('Location: ' . BASE_URL . '/forgot-password');
            exit();
        } else {
            Session::setFlash('error', "Email não encontrado em nossa base de dados.");
            header('Location: ' . BASE_URL . '/forgot-password');
            exit();
        }
    }
    
    // Exibe o formulário de reset de senha (GET)
    public function showResetPasswordForm() {
        $erro = Session::getFlash('error');
        require_once __DIR__ . '/../views/reset-password.php';
    }
    
    // Processa o reset de senha (POST)
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/forgot-password');
            exit();
        }
        
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING) ?? '';
        $nova_senha = $_POST['nova_senha'] ?? '';
        $confirma_senha = $_POST['confirma_senha'] ?? '';
        
        // Validações
        if (empty($token) || empty($nova_senha) || empty($confirma_senha)) {
            Session::setFlash('error', "Preencha todos os campos e verifique o token.");
            header('Location: ' . BASE_URL . '/reset-password?token=' . $token);
            exit();
        }
        if ($nova_senha !== $confirma_senha) {
            Session::setFlash('error', "As senhas não coincidem.");
            header('Location: ' . BASE_URL . '/reset-password?token=' . $token);
            exit();
        }
        // Validação da força da senha
        if (strlen($nova_senha) < 8 || 
            !preg_match('/[A-Z]/', $nova_senha) || 
            !preg_match('/[a-z]/', $nova_senha) || 
            !preg_match('/[^A-Za-z0-9]/', $nova_senha)) {
            Session::setFlash('error', "A senha não atende aos requisitos de segurança.");
            header('Location: ' . BASE_URL . '/reset-password?token=' . $token);
            exit();
        }
        
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        if ($this->userModel->resetPassword($token, $nova_senha_hash)) {
            Session::setFlash('success', 'Senha alterada com sucesso! Faça login com sua nova senha.');
            header('Location: ' . BASE_URL . '/login');
            exit();
        } else {
            Session::setFlash('error', "Token inválido ou expirado. Solicite uma nova recuperação de senha.");
            header('Location: ' . BASE_URL . '/reset-password?token=' . $token);
            exit();
        }
    }
}