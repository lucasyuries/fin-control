<?php
require_once __DIR__ . '/../core/Database.php';

class Transaction {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Retorna o resumo mensal de receitas e despesas
    public function getMonthlySummary($usuario_id) {
        // Query para somar as receitas do mês atual
        $this->db->query("
            SELECT SUM(valor) as total 
            FROM transactions 
            WHERE usuario_id = :usuario_id 
            AND tipo = 'receita' 
            AND MONTH(data) = MONTH(CURRENT_DATE())
            AND YEAR(data) = YEAR(CURRENT_DATE())
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        $receitas = $this->db->single();

        // Query para somar as despesas do mês atual
        $this->db->query("
            SELECT SUM(valor) as total 
            FROM transactions 
            WHERE usuario_id = :usuario_id 
            AND tipo = 'despesa'
            AND MONTH(data) = MONTH(CURRENT_DATE())
            AND YEAR(data) = YEAR(CURRENT_DATE())
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        $despesas = $this->db->single();

        // Garante que o valor retornado seja um número (0 se for nulo)
        return [
            'total_receitas' => $receitas['total'] ?? 0.00,
            'total_despesas' => $despesas['total'] ?? 0.00
        ];
    }
    
    // Retorna totais por tipo (receita, despesa, ativo)
    public function getTotalsByType($usuario_id) {
        $this->db->query("
            SELECT 
                tipo,
                SUM(valor) as total
            FROM transactions 
            WHERE usuario_id = :usuario_id
            GROUP BY tipo
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        $results = $this->db->resultSet();
        
        $totals = [
            'receitas' => 0.00,
            'despesas' => 0.00,
            'ativos' => 0.00
        ];
        
        foreach($results as $row) {
            if ($row['tipo'] == 'receita') {
                $totals['receitas'] = $row['total'];
            } elseif ($row['tipo'] == 'despesa') {
                $totals['despesas'] = $row['total'];
            } elseif ($row['tipo'] == 'ativo') {
                $totals['ativos'] = $row['total'];
            }
        }
        
        return $totals;
    }
    
    // Retorna patrimônio total (receitas + ativos - despesas)
    public function getPatrimonioTotal($usuario_id) {
        $totals = $this->getTotalsByType($usuario_id);
        return $totals['receitas'] + $totals['ativos'] - $totals['despesas'];
    }
    
    // Retorna dados para o gráfico mensal (últimos 12 meses)
    public function getMonthlyChartData($usuario_id) {
        $this->db->query("
            SELECT 
                DATE_FORMAT(data, '%Y-%m') as mes,
                DATE_FORMAT(data, '%b/%y') as label,
                tipo,
                SUM(valor) as total
            FROM transactions 
            WHERE usuario_id = :usuario_id
            AND data >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(data, '%Y-%m'), DATE_FORMAT(data, '%b/%y'), tipo
            ORDER BY mes ASC
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->resultSet();
    }
    
    // Retorna totais por categoria
    public function getTotalsByCategory($usuario_id) {
        $this->db->query("
            SELECT 
                c.nome as categoria,
                t.tipo,
                SUM(t.valor) as total,
                COUNT(*) as quantidade
            FROM transactions t
            LEFT JOIN categories c ON t.categoria_id = c.id
            WHERE t.usuario_id = :usuario_id
            GROUP BY c.nome, t.tipo
            ORDER BY total DESC
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->resultSet();
    }
    
    // Busca todas as transações do usuário
    public function findAllByUserId($usuario_id) {
        $this->db->query("
            SELECT t.*, c.nome as categoria_nome 
            FROM transactions t
            LEFT JOIN categories c ON t.categoria_id = c.id
            WHERE t.usuario_id = :usuario_id 
            ORDER BY t.data DESC
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->resultSet();
    }
    
    // Busca transações por tipo (receita, despesa, ativo)
    public function findByType($usuario_id, $tipo) {
        $this->db->query("
            SELECT t.*, c.nome as categoria_nome 
            FROM transactions t
            LEFT JOIN categories c ON t.categoria_id = c.id
            WHERE t.usuario_id = :usuario_id 
            AND t.tipo = :tipo
            ORDER BY t.data DESC
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        $this->db->bind(':tipo', $tipo);
        return $this->db->resultSet();
    }

    // Cria uma nova transação
    public function create($data) {
        $this->db->query("
            INSERT INTO transactions (user_id, category_id, description, amount, date, type, ticker, quantity, notes) 
            VALUES (:user_id, :category_id, :description, :amount, :date, :type, :ticker, :quantity, :notes)
        ");
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':category_id', $data['category_id'] ?? null);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':ticker', $data['ticker'] ?? null);
        $this->db->bind(':quantity', $data['quantity'] ?? null);
        $this->db->bind(':notes', $data['notes'] ?? null);
        
        return $this->db->execute();
    }
}