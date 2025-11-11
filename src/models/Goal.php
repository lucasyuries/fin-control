<?php
require_once __DIR__ . '/../core/Database.php';

class Goal {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Busca todas as metas do usuário
    public function findAllByUserId($usuario_id, $status = null) {
        $query = "SELECT * FROM goals WHERE usuario_id = :usuario_id";
        
        if ($status) {
            $query .= " AND status = :status";
        }
        
        $query .= " ORDER BY created_at DESC";
        
        $this->db->query($query);
        $this->db->bind(':usuario_id', $usuario_id);
        
        if ($status) {
            $this->db->bind(':status', $status);
        }
        
        return $this->db->resultSet();
    }

    // Busca meta por ID
    public function findById($id, $usuario_id) {
        $this->db->query("SELECT * FROM goals WHERE id = :id AND usuario_id = :usuario_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->single();
    }

    // Cria uma nova meta
    public function create($data) {
        $this->db->query("
            INSERT INTO goals (usuario_id, tipo, nome, valor_objetivo, aporte_mensal, variacao_anual, status, data_inicio) 
            VALUES (:usuario_id, :tipo, :nome, :valor_objetivo, :aporte_mensal, :variacao_anual, :status, :data_inicio)
        ");
        
        $this->db->bind(':usuario_id', $data['usuario_id']);
        $this->db->bind(':tipo', $data['tipo']);
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':valor_objetivo', $data['valor_objetivo']);
        $this->db->bind(':aporte_mensal', $data['aporte_mensal'] ?? 0);
        $this->db->bind(':variacao_anual', $data['variacao_anual'] ?? 0);
        $this->db->bind(':status', $data['status'] ?? 'em_andamento');
        $this->db->bind(':data_inicio', $data['data_inicio']);
        
        return $this->db->execute();
    }

    // Atualiza uma meta
    public function update($id, $data) {
        $this->db->query("
            UPDATE goals 
            SET nome = :nome, 
                valor_objetivo = :valor_objetivo, 
                aporte_mensal = :aporte_mensal, 
                variacao_anual = :variacao_anual,
                status = :status,
                data_conclusao = :data_conclusao
            WHERE id = :id AND usuario_id = :usuario_id
        ");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $data['usuario_id']);
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':valor_objetivo', $data['valor_objetivo']);
        $this->db->bind(':aporte_mensal', $data['aporte_mensal'] ?? 0);
        $this->db->bind(':variacao_anual', $data['variacao_anual'] ?? 0);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':data_conclusao', $data['data_conclusao'] ?? null);
        
        return $this->db->execute();
    }

    // Deleta uma meta
    public function delete($id, $usuario_id) {
        $this->db->query("DELETE FROM goals WHERE id = :id AND usuario_id = :usuario_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->execute();
    }

    // Calcula progresso da meta de patrimônio
    public function calculateProgress($goal, $currentValue) {
        $objetivo = floatval($goal['valor_objetivo']);
        $progresso = ($currentValue / $objetivo) * 100;
        $falta = max(0, $objetivo - $currentValue);
        
        return [
            'percentual' => min(100, $progresso),
            'atual' => $currentValue,
            'objetivo' => $objetivo,
            'falta' => $falta,
            'concluida' => $progresso >= 100
        ];
    }

    // Calcula tempo estimado para conclusão
    public function calculateEstimatedTime($goal, $currentValue) {
        $objetivo = floatval($goal['valor_objetivo']);
        $falta = $objetivo - $currentValue;
        $aporteMensal = floatval($goal['aporte_mensal']);
        $variacaoAnual = floatval($goal['variacao_anual']) / 100;
        
        if ($falta <= 0) {
            return 0; // Já atingiu a meta
        }
        
        if ($aporteMensal <= 0) {
            return null; // Sem aporte não há estimativa
        }
        
        // Cálculo simples (sem considerar juros compostos por enquanto)
        if ($variacaoAnual == 0) {
            return ceil($falta / $aporteMensal);
        }
        
        // Com juros compostos (fórmula de valor futuro)
        $taxaMensal = pow(1 + $variacaoAnual, 1/12) - 1;
        $meses = 0;
        $valorAtual = $currentValue;
        
        while ($valorAtual < $objetivo && $meses < 600) { // limite de 50 anos
            $valorAtual = $valorAtual * (1 + $taxaMensal) + $aporteMensal;
            $meses++;
        }
        
        return $meses;
    }
}
