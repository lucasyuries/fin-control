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
    /**
     * Cria uma nova transação na tabela usando o schema atual (pt-BR).
     * Aceita tanto chaves em inglês quanto em português para compatibilidade.
     */
    public function create($data) {
        // Normaliza chaves
        $usuario_id   = $data['usuario_id']   ?? $data['user_id']      ?? null;
        $categoria_id = $data['categoria_id'] ?? $data['category_id']  ?? null;
        $descricao    = $data['descricao']    ?? $data['description']  ?? '';
        $valor        = $data['valor']        ?? $data['amount']       ?? 0;
        $dataMov      = $data['data']         ?? $data['date']         ?? date('Y-m-d');
        $tipo         = $data['tipo']         ?? $data['type']         ?? '';
        $ticker       = $data['ticker']       ?? null;
        $quantidade   = $data['quantidade']   ?? $data['quantity']     ?? null;
        $observacoes  = $data['observacoes']  ?? $data['notes']        ?? null;

        $this->db->query("\n            INSERT INTO transactions (usuario_id, categoria_id, descricao, valor, data, tipo, ticker, quantidade, observacoes)\n            VALUES (:usuario_id, :categoria_id, :descricao, :valor, :data, :tipo, :ticker, :quantidade, :observacoes)\n        ");
        $this->db->bind(':usuario_id', $usuario_id);
        $this->db->bind(':categoria_id', $categoria_id);
        $this->db->bind(':descricao', $descricao);
        $this->db->bind(':valor', $valor);
        $this->db->bind(':data', $dataMov);
        $this->db->bind(':tipo', $tipo);
        $this->db->bind(':ticker', $ticker);
        $this->db->bind(':quantidade', $quantidade);
        $this->db->bind(':observacoes', $observacoes);
        
        return $this->db->execute();
    }
}