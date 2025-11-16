<?php
require_once __DIR__ . '/../core/Database.php';

class Transaction {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Atualiza uma transação existente
    public function update($id, $data, $usuario_id) {
        $categoria_id = $data['categoria_id'] ?? null;
        $descricao = $data['descricao'] ?? '';
        $valor = $data['valor'] ?? 0;
        $dataMov = $data['data'] ?? date('Y-m-d');
        $tipo = $data['tipo'] ?? '';
        $observacoes = $data['observacoes'] ?? null;
        $this->db->query("UPDATE transactions SET categoria_id = :categoria_id, descricao = :descricao, valor = :valor, data = :data, tipo = :tipo, observacoes = :observacoes WHERE id = :id AND usuario_id = :usuario_id");
        $this->db->bind(':categoria_id', $categoria_id);
        $this->db->bind(':descricao', $descricao);
        $this->db->bind(':valor', $valor);
        $this->db->bind(':data', $dataMov);
        $this->db->bind(':tipo', $tipo);
        $this->db->bind(':observacoes', $observacoes);
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
    
    // Retorna totais por tipo (receita, despesa)
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
            'despesas' => 0.00
        ];
        
        foreach($results as $row) {
            if ($row['tipo'] == 'receita') {
                $totals['receitas'] = $row['total'];
            } elseif ($row['tipo'] == 'despesa') {
                $totals['despesas'] = $row['total'];
            }
        }
        
        return $totals;
    }

    // Retorna patrimônio total (receitas - despesas)
    public function getPatrimonioTotal($usuario_id) {
        if (!isset($usuario_id)) {
            throw new InvalidArgumentException('usuario_id é obrigatório');
        }
        $totals = $this->getTotalsByType($usuario_id);
        return $totals['receitas'] - $totals['despesas'];
    }
    
    // Retorna dados para o gráfico mensal (últimos 12 meses)
    public function getMonthlyChartData($usuario_id) {
        if (!isset($usuario_id)) {
            throw new InvalidArgumentException('usuario_id é obrigatório');
        }
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
        if (!isset($usuario_id)) {
            throw new InvalidArgumentException('usuario_id é obrigatório');
        }
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
        if (!isset($usuario_id)) {
            throw new InvalidArgumentException('usuario_id é obrigatório');
        }
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
    
    // Busca transações por tipo (receita, despesa)
    public function findByType($usuario_id, $tipo) {
        if (!isset($usuario_id)) {
            throw new InvalidArgumentException('usuario_id é obrigatório');
        }
        if (!isset($tipo)) {
            throw new InvalidArgumentException('tipo é obrigatório');
        }
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
        $observacoes  = $data['observacoes']  ?? $data['notes']        ?? null;
        $compensada_por = $data['compensada_por'] ?? null;

        $this->db->query("\n            INSERT INTO transactions (usuario_id, categoria_id, descricao, valor, data, tipo, observacoes, compensada_por)\n            VALUES (:usuario_id, :categoria_id, :descricao, :valor, :data, :tipo, :observacoes, :compensada_por)\n        ");
        $this->db->bind(':usuario_id', $usuario_id);
        $this->db->bind(':categoria_id', $categoria_id);
        $this->db->bind(':descricao', $descricao);
        $this->db->bind(':valor', $valor);
        $this->db->bind(':data', $dataMov);
        $this->db->bind(':tipo', $tipo);
        $this->db->bind(':observacoes', $observacoes);
        $this->db->bind(':compensada_por', $compensada_por);
        return $this->db->execute();
    }

    // Atualiza o campo compensada_por de uma transação
    public function compensar($id, $compensada_por, $usuario_id) {
        $this->db->query("UPDATE transactions SET compensada_por = :compensada_por WHERE id = :id AND usuario_id = :usuario_id");
        $this->db->bind(':compensada_por', $compensada_por);
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->execute();
    }
}