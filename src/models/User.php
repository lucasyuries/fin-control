<?php
// Arquivo: src/models/User.php
require_once __DIR__ . '/../core/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Encontra um usuário pelo email, retornando os dados completos (incluindo hash de senha)
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        return $row ?: false;
    }

    // Registra um novo usuário e retorna o ID
    public function register($data) {
        $this->db->query('INSERT INTO users (nome, email, senha) VALUES (:nome, :email, :senha_hash)');
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':senha_hash', $data['senha_hash']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Tenta autenticar um usuário
    public function login($email, $senha) {
        $user = $this->findUserByEmail($email);
        if (!$user) return false;
        
        // Verifica a senha e retorna o usuário (sem o hash de senha)
        if (password_verify($senha, $user['senha'])) {
            unset($user['senha']); 
            return $user;
        }
        return false;
    }

    // Atualiza os dados do perfil do usuário
    public function updateProfile($user_id, $data) {
        $this->db->query('UPDATE users SET nome = :nome, email = :email, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':id', $user_id);
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':email', $data['email']);
        return $this->db->execute();
    }

    // Altera a senha do usuário
    public function changePassword($user_id, $nova_senha_hash) {
        $this->db->query('UPDATE users SET senha = :senha, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':id', $user_id);
        $this->db->bind(':senha', $nova_senha_hash);
        return $this->db->execute();
    }

    // Busca usuário por ID (sem hash de senha)
    public function findUserById($user_id) {
        $this->db->query('SELECT id, nome, email, created_at FROM users WHERE id = :id');
        $this->db->bind(':id', $user_id);
        return $this->db->single() ?: false;
    }

    // Cria um token de recuperação de senha
    public function createPasswordResetToken($email) {
        $user = $this->findUserByEmail($email);
        if (!$user) return false;
        
        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Apaga tokens expirados para este email (limpeza)
        $this->db->query('DELETE FROM password_resets WHERE email = :email AND used = 0 AND expires_at < NOW()');
        $this->db->bind(':email', $email);
        $this->db->execute();

        // Salva o novo token
        $this->db->query('INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)');
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);
        $this->db->bind(':expires_at', $expires_at);

        return $this->db->execute() ? $token : false;
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
        return $this->db->single() ?: false;
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
        
        $user = $this->findUserByEmail($reset_data['email']);
        if (!$user) return false;
        
        $passwordUpdated = $this->changePassword($user['id'], $nova_senha_hash);
        $tokenMarked = $this->markTokenAsUsed($token);
        
        return $passwordUpdated && $tokenMarked;
    }
}