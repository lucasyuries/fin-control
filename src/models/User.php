<?php
require_once __DIR__ . '/../core/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Encontra um usuário pelo email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    // Registra um novo usuário e retorna o ID
    public function register($data) {
        $this->db->query('INSERT INTO users (nome, email, senha) VALUES (:nome, :email, :senha_hash)');
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':senha_hash', $data['senha_hash']);
        
        if ($this->db->execute()) {
            // Retorna o ID do usuário recém criado
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    // Tenta autenticar um usuário
    public function login($email, $senha) {
        $user = $this->findUserByEmail($email);
        if (!$user) return false;
        return password_verify($senha, $user['senha']) ? $user : false;
    }
    
    // Atualiza os dados do perfil do usuário
    public function updateProfile($user_id, $data) {
        $this->db->query('UPDATE users SET nome = :nome, email = :email WHERE id = :id');
        $this->db->bind(':id', $user_id);
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':email', $data['email']);
        return $this->db->execute();
    }
    
    // Altera a senha do usuário
    public function changePassword($user_id, $nova_senha_hash) {
        $this->db->query('UPDATE users SET senha = :senha WHERE id = :id');
        $this->db->bind(':id', $user_id);
        $this->db->bind(':senha', $nova_senha_hash);
        return $this->db->execute();
    }
    
    // Busca usuário por ID
    public function findUserById($user_id) {
        $this->db->query('SELECT id, nome, email, created_at FROM users WHERE id = :id');
        $this->db->bind(':id', $user_id);
        $row = $this->db->single();
        return $row ? $row : false;
    }
    
    // Cria um token de recuperação de senha
    public function createPasswordResetToken($email) {
        // Verifica se o email existe
        $user = $this->findUserByEmail($email);
        if (!$user) return false;
        
        // Gera um token único
        $token = bin2hex(random_bytes(32));
        
        // Define validade para 1 hora
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Salva o token no banco
        $this->db->query('INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)');
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);
        $this->db->bind(':expires_at', $expires_at);
        
        if ($this->db->execute()) {
            return $token;
        }
        return false;
    }
    
    // Valida um token de recuperação de senha
    public function validateResetToken($token) {
        $this->db->query('
            SELECT * FROM password_resets 
            WHERE token = :token 
            AND used = 0 
            AND expires_at > NOW()
        ');
        $this->db->bind(':token', $token);
        $row = $this->db->single();
        return $row ? $row : false;
    }
    
    // Marca um token como usado
    public function markTokenAsUsed($token) {
        $this->db->query('UPDATE password_resets SET used = 1 WHERE token = :token');
        $this->db->bind(':token', $token);
        return $this->db->execute();
    }
    
    // Reseta a senha usando um token válido
    public function resetPassword($token, $nova_senha_hash) {
        $reset_data = $this->validateResetToken($token);
        if (!$reset_data) return false;
        
        // Atualiza a senha
        $user = $this->findUserByEmail($reset_data['email']);
        if (!$user) return false;
        
        $this->changePassword($user['id'], $nova_senha_hash);
        
        // Marca o token como usado
        $this->markTokenAsUsed($token);
        
        return true;
    }
}