<?php
// Arquivo: src/models/Transaction.php
require_once __DIR__ . '/../core/Database.php';

class Transaction {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Cria uma nova transação.
     * @param array $data Dados da transação.
     * @return bool Sucesso ou falha.
     */
    public function create($data) {
        $this->db->query("
            INSERT INTO transactions 
            (usuario_id, categoria_id, descricao, valor, data, tipo, observacoes, compensada_por)
            VALUES (:usuario_id, :categoria_id, :descricao, :valor, :data, :tipo, :observacoes, :compensada_por)
        ");
        
        $this->db->bind(':usuario_id', $data['usuario_id'] ?? null);
        $this->db->bind(':categoria_id', $data['categoria_id'] ?? null);
        $this->db->bind(':descricao', $data['descricao'] ?? '');
        $this->db->bind(':valor', $data['valor'] ?? 0);
        $this->db->bind(':data', $data['data'] ?? date('Y-m-d'));
        $this->db->bind(':tipo', $data['tipo'] ?? '');
        $this->db->bind(':observacoes', $data['observacoes'] ?? null);
        $this->db->bind(':compensada_por', $data['compensada_por'] ?? null);
        
        return $this->db->execute();
    }

    // Atualiza uma transação existente
    public function update($id, $data, $usuario_id) {
        $this->db->query("
            UPDATE transactions 
            SET categoria_id = :categoria_id, descricao = :descricao, valor = :valor, 
                data = :data, tipo = :tipo, observacoes = :observacoes, updated_at = NOW() 
            WHERE id = :id AND usuario_id = :usuario_id
        ");
        $this->db->bind(':categoria_id', $data['categoria_id'] ?? null);
        $this->db->bind(':descricao', $data['descricao'] ?? '');
        $this->db->bind(':valor', $data['valor'] ?? 0);
        $this->db->bind(':data', $data['data'] ?? date('Y-m-d'));
        $this->db->bind(':tipo', $data['tipo'] ?? '');
        $this->db->bind(':observacoes', $data['observacoes'] ?? null);
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->execute();
    }

    // Exclui uma transação
    public function delete($id, $usuario_id) {
        $this->db->query("DELETE FROM transactions WHERE id = :id AND usuario_id = :usuario_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->execute();
    }

    // Retorna o resumo mensal de receitas e despesas
    public function getMonthlySummary($usuario_id) {
        $this->db->query("
            SELECT 
                COALESCE(SUM(CASE WHEN tipo = 'receita' THEN valor ELSE 0 END), 0) as total_receitas,
                COALESCE(SUM(CASE WHEN tipo = 'despesa' THEN valor ELSE 0 END), 0) as total_despesas
            FROM transactions 
            WHERE usuario_id = :usuario_id 
            AND MONTH(data) = MONTH(CURRENT_DATE())
            AND YEAR(data) = YEAR(CURRENT_DATE())
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        $summary = $this->db->single();

        return [
            'total_receitas' => $summary['total_receitas'] ?? 0.00,
            'total_despesas' => $summary['total_despesas'] ?? 0.00
        ];
    }
    
    public function getTotalsByType($usuario_id) {
        // 1. Total Receitas Acumuladas
        $this->db->query("
            SELECT COALESCE(SUM(valor), 0) as total 
            FROM transactions 
            WHERE usuario_id = :usuario_id AND tipo = 'receita'
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        $receitas = $this->db->single();

        // 2. Total Despesas Acumuladas
        $this->db->query("
            SELECT COALESCE(SUM(valor), 0) as total 
            FROM transactions 
            WHERE usuario_id = :usuario_id AND tipo = 'despesa'
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        $despesas = $this->db->single();
        
        // Garante que o valor é tratado como float
        return [
            'receitas' => (float)($receitas['total'] ?? 0.00),
            'despesas' => (float)($despesas['total'] ?? 0.00)
        ];
    }
    
    // Retorna patrimônio total (receitas - despesas)
    // Este método usa getTotalsByType, e agora funcionará corretamente.
    public function getPatrimonioTotal($usuario_id) {
        if (!isset($usuario_id)) return 0.00;
        $totals = $this->getTotalsByType($usuario_id);
        return $totals['receitas'] - $totals['despesas'];
    }
    // Retorna dados para o gráfico mensal (últimos 12 meses)
    public function getMonthlyChartData($usuario_id) {
        if (!isset($usuario_id)) return [];
        $this->db->query("
            SELECT 
                DATE_FORMAT(data, '%Y-%m') as mes,
                DATE_FORMAT(data, '%b/%y') as label,
                tipo,
                COALESCE(SUM(valor), 0) as total
            FROM transactions 
            WHERE usuario_id = :usuario_id
            AND data >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY mes, label, tipo
            ORDER BY mes ASC
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->resultSet();
    }
    
    // Retorna totais por categoria
    public function getTotalsByCategory($usuario_id) {
        if (!isset($usuario_id)) return [];
        $this->db->query("
            SELECT 
                COALESCE(c.nome, 'Sem categoria') as categoria,
                t.tipo,
                COALESCE(SUM(t.valor), 0) as total,
                COUNT(*) as quantidade
            FROM transactions t
            LEFT JOIN categories c ON t.categoria_id = c.id
            WHERE t.usuario_id = :usuario_id
            GROUP BY categoria, t.tipo
            ORDER BY total DESC
        ");
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->resultSet();
    }
    
    // Busca todas as transações do usuário, aceitando filtros opcionais
    public function findAllByUserId($usuario_id, $tipo = null, $data_inicio = null, $data_fim = null) {
        if (!isset($usuario_id)) return [];

        $sql = "
            SELECT t.*, c.nome as categoria_nome 
            FROM transactions t
            LEFT JOIN categories c ON t.categoria_id = c.id
            WHERE t.usuario_id = :usuario_id 
        ";
        
        // Filtro por tipo
        if ($tipo && in_array($tipo, ['receita', 'despesa'])) {
            $sql .= " AND t.tipo = :tipo";
        }
        
        // Filtro por data de início
        if ($data_inicio) {
            $sql .= " AND t.data >= :data_inicio";
        }
        
        // Filtro por data de fim
        if ($data_fim) {
            $sql .= " AND t.data <= :data_fim";
        }
        
        $sql .= " ORDER BY t.data DESC, t.created_at DESC";
        
        $this->db->query($sql);
        $this->db->bind(':usuario_id', $usuario_id);
        
        // Bind dos parâmetros
        if ($tipo && in_array($tipo, ['receita', 'despesa'])) {
            $this->db->bind(':tipo', $tipo);
        }
        if ($data_inicio) {
            $this->db->bind(':data_inicio', $data_inicio);
        }
        if ($data_fim) {
            $this->db->bind(':data_fim', $data_fim);
        }
        
        return $this->db->resultSet();
    }
        
    // Busca uma única transação pelo ID
    public function findById($id, $usuario_id) {
        $this->db->query("
            SELECT t.*, c.nome as categoria_nome 
            FROM transactions t
            LEFT JOIN categories c ON t.categoria_id = c.id
            WHERE t.id = :id AND t.usuario_id = :usuario_id
        ");
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->single() ?: false;
    }

    // Atualiza o campo compensada_por de uma transação (Usado para 'parear' lançamentos)
    public function compensar($id, $compensada_por, $usuario_id) {
        $this->db->query("UPDATE transactions SET compensada_por = :compensada_por, updated_at = NOW() WHERE id = :id AND usuario_id = :usuario_id");
        $this->db->bind(':compensada_por', $compensada_por);
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->execute();
    }
}