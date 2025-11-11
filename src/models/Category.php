<?php
require_once __DIR__ . '/../core/Database.php';

class Category {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Busca todas as categorias de um usuário específico
    public function findAllByUserId($usuario_id) {
        $this->db->query("SELECT * FROM categories WHERE usuario_id = :usuario_id ORDER BY nome ASC");
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->resultSet();
    }
    
    // Cria categorias padrão para um novo usuário
    public function createDefaultCategories($usuario_id) {
        $categorias_padroes = [
            // Receitas
            ['nome' => 'Salário', 'tipo' => 'receita', 'icone' => '💰', 'cor' => '#4caf50'],
            ['nome' => 'Freelance', 'tipo' => 'receita', 'icone' => '💼', 'cor' => '#2196f3'],
            ['nome' => 'Investimentos', 'tipo' => 'receita', 'icone' => '📈', 'cor' => '#9c27b0'],
            ['nome' => 'Outros', 'tipo' => 'receita', 'icone' => '💵', 'cor' => '#00bcd4'],
            
            // Despesas
            ['nome' => 'Alimentação', 'tipo' => 'despesa', 'icone' => '🍔', 'cor' => '#ff9800'],
            ['nome' => 'Transporte', 'tipo' => 'despesa', 'icone' => '🚗', 'cor' => '#f44336'],
            ['nome' => 'Moradia', 'tipo' => 'despesa', 'icone' => '🏠', 'cor' => '#795548'],
            ['nome' => 'Saúde', 'tipo' => 'despesa', 'icone' => '⚕️', 'cor' => '#e91e63'],
            ['nome' => 'Educação', 'tipo' => 'despesa', 'icone' => '📚', 'cor' => '#3f51b5'],
            ['nome' => 'Lazer', 'tipo' => 'despesa', 'icone' => '🎮', 'cor' => '#00bcd4'],
            ['nome' => 'Compras', 'tipo' => 'despesa', 'icone' => '🛒', 'cor' => '#ff5722'],
            ['nome' => 'Outros', 'tipo' => 'despesa', 'icone' => '💸', 'cor' => '#607d8b'],
        ];
        
        foreach ($categorias_padroes as $categoria) {
            $this->db->query("
                INSERT INTO categories (usuario_id, nome, tipo, icone, cor) 
                VALUES (:usuario_id, :nome, :tipo, :icone, :cor)
            ");
            $this->db->bind(':usuario_id', $usuario_id);
            $this->db->bind(':nome', $categoria['nome']);
            $this->db->bind(':tipo', $categoria['tipo']);
            $this->db->bind(':icone', $categoria['icone']);
            $this->db->bind(':cor', $categoria['cor']);
            $this->db->execute();
        }
        
        return true;
    }
}