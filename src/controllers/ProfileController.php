<?php
// Arquivo: src/controllers/ProfileController.php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Session.php'; // Novo CORE

class ProfileController {
    private $userModel;
    
    public function __construct() {
        Session::requireLogin(); // Exige que o usuário esteja logado
        $this->userModel = new User();
    }
    
    // Exibe a página de perfil
    public function index() {
        $user = $this->userModel->findUserById($_SESSION['user_id']);
        
        $data = [
            'user' => $user,
            'success' => Session::getFlash('success'), // Mensagem Flash
            'error' => Session::getFlash('error') // Mensagem Flash
        ];
        
        $pageTitle = 'Meu Perfil';
        require_once __DIR__ . '/../views/profile.php';
    }
    
    // Atualiza os dados do perfil
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        $usuario_id = $_SESSION['user_id'];
        
        $data = [
            'nome' => filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) ?? '',
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? ''
        ];
        
        // Validações
        if (empty($data['nome']) || empty($data['email'])) {
            Session::setFlash('error', 'Preencha todos os campos obrigatórios.');
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            Session::setFlash('error', 'Email inválido.');
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        // Verifica se o email já está em uso por outro usuário
        $existing_user = $this->userModel->findUserByEmail($data['email']);
        if ($existing_user && $existing_user['id'] != $usuario_id) {
            Session::setFlash('error', 'Este email já está em uso.');
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        // Atualiza o perfil
        if ($this->userModel->updateProfile($usuario_id, $data)) {
            $_SESSION['user_nome'] = $data['nome']; // Atualiza a sessão
            Session::setFlash('success', 'Perfil atualizado com sucesso!');
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        Session::setFlash('error', 'Erro ao atualizar perfil.');
        header('Location: ' . BASE_URL . '/profile');
        exit();
    }
    
    // Altera a senha
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        $usuario_id = $_SESSION['user_id'];
        
        $senha_atual = $_POST['senha_atual'] ?? '';
        $nova_senha = $_POST['nova_senha'] ?? '';
        $confirma_senha = $_POST['confirma_senha'] ?? '';
        
        // Validações
        if (empty($senha_atual) || empty($nova_senha) || empty($confirma_senha)) {
            Session::setFlash('error', 'Preencha todos os campos de senha.');
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        // Verifica se a senha atual está correta (pega o usuário completo com hash)
        $user = $this->userModel->findUserById($usuario_id);
        $full_user = $this->userModel->findUserByEmail($user['email']);
        
        if (!password_verify($senha_atual, $full_user['senha'])) {
            Session::setFlash('error', 'Senha atual incorreta.');
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        // Valida a nova senha (regras de segurança)
        if (strlen($nova_senha) < 8 || 
            !preg_match('/[A-Z]/', $nova_senha) || 
            !preg_match('/[a-z]/', $nova_senha) || 
            !preg_match('/[^A-Za-z0-9]/', $nova_senha)) {
            Session::setFlash('error', 'A nova senha não atende aos requisitos de segurança.');
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        // Verifica se as novas senhas coincidem
        if ($nova_senha !== $confirma_senha) {
            Session::setFlash('error', 'As novas senhas não coincidem.');
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        // Atualiza a senha
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        if ($this->userModel->changePassword($usuario_id, $nova_senha_hash)) {
            Session::setFlash('success', 'Senha alterada com sucesso!');
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }
        
        Session::setFlash('error', 'Erro ao alterar senha.');
        header('Location: ' . BASE_URL . '/profile');
        exit();
    }
}