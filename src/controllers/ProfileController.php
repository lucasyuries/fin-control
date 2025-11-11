<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController {
    private $userModel;
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        
        $this->userModel = new User();
    }
    
    // Exibe a página de perfil
    public function index() {
        $user = $this->userModel->findUserById($_SESSION['user_id']);
        
        $data = [
            'user' => $user,
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null
        ];
        
        $pageTitle = 'Meu Perfil';
        require_once __DIR__ . '/../views/profile.php';
    }
    
    // Atualiza os dados do perfil
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        $data = [
            'nome' => trim($_POST['nome']),
            'email' => trim($_POST['email'])
        ];
        
        // Validações
        if (empty($data['nome']) || empty($data['email'])) {
            header('Location: ' . BASE_URL . '/profile?error=Preencha todos os campos');
            exit();
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            header('Location: ' . BASE_URL . '/profile?error=Email inválido');
            exit();
        }
        
        // Verifica se o email já está em uso por outro usuário
        $existing_user = $this->userModel->findUserByEmail($data['email']);
        if ($existing_user && $existing_user['id'] != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . '/profile?error=Este email já está em uso');
            exit();
        }
        
        // Atualiza o perfil
        if ($this->userModel->updateProfile($_SESSION['user_id'], $data)) {
            $_SESSION['user_nome'] = $data['nome'];
            header('Location: ' . BASE_URL . '/profile?success=Perfil atualizado com sucesso!');
            exit();
        }
        
        header('Location: ' . BASE_URL . '/profile?error=Erro ao atualizar perfil');
        exit();
    }
    
    // Altera a senha
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        $senha_atual = trim($_POST['senha_atual']);
        $nova_senha = trim($_POST['nova_senha']);
        $confirma_senha = trim($_POST['confirma_senha']);
        
        // Validações
        if (empty($senha_atual) || empty($nova_senha) || empty($confirma_senha)) {
            header('Location: ' . BASE_URL . '/profile?error=Preencha todos os campos de senha');
            exit();
        }
        
        // Verifica se a senha atual está correta
        $user = $this->userModel->findUserById($_SESSION['user_id']);
        $full_user = $this->userModel->findUserByEmail($user['email']);
        
        if (!password_verify($senha_atual, $full_user['senha'])) {
            header('Location: ' . BASE_URL . '/profile?error=Senha atual incorreta');
            exit();
        }
        
        // Valida a nova senha
        if (strlen($nova_senha) < 8 || 
            !preg_match('/[A-Z]/', $nova_senha) || 
            !preg_match('/[a-z]/', $nova_senha) || 
            !preg_match('/[^A-Za-z0-9]/', $nova_senha)) {
            header('Location: ' . BASE_URL . '/profile?error=A nova senha não atende aos requisitos de segurança');
            exit();
        }
        
        // Verifica se as senhas coincidem
        if ($nova_senha != $confirma_senha) {
            header('Location: ' . BASE_URL . '/profile?error=As novas senhas não coincidem');
            exit();
        }
        
        // Atualiza a senha
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        if ($this->userModel->changePassword($_SESSION['user_id'], $nova_senha_hash)) {
            header('Location: ' . BASE_URL . '/profile?success=Senha alterada com sucesso!');
            exit();
        }
        
        header('Location: ' . BASE_URL . '/profile?error=Erro ao alterar senha');
        exit();
    }
}
