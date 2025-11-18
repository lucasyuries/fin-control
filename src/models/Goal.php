<?php
// Arquivo: src/models/Goal.php
require_once __DIR__ . '/../core/Database.php';

class Goal {
	private $db;

	public function __construct() {
		$this->db = new Database();
	}

    /**
     * Cria uma nova meta no banco.
     * @param array $data Dados da meta.
     * @return bool Sucesso ou falha.
     */
	public function create($data) {
		$this->db->query('INSERT INTO goals (usuario_id, nome, valor_objetivo, aporte_mensal, variacao_anual, tipo, status, data_inicio) 
                          VALUES (:usuario_id, :nome, :valor_objetivo, :aporte_mensal, :variacao_anual, :tipo, :status, CURDATE())');
		$this->db->bind(':usuario_id', $data['usuario_id']);
		$this->db->bind(':nome', $data['nome']);
		$this->db->bind(':valor_objetivo', $data['valor_objetivo']);
		$this->db->bind(':aporte_mensal', $data['aporte_mensal']);
		$this->db->bind(':variacao_anual', $data['variacao_anual']);
		$this->db->bind(':tipo', $data['tipo']);
		$this->db->bind(':status', 'em_andamento');
		return $this->db->execute();
	}

	// Busca todas as metas do usuário
	public function findAllByUserId($usuario_id) {
		$this->db->query('SELECT * FROM goals WHERE usuario_id = :usuario_id ORDER BY created_at DESC');
		$this->db->bind(':usuario_id', $usuario_id);
		return $this->db->resultSet();
	}

    // Busca uma única meta pelo ID
    public function findById($id, $usuario_id) {
        $this->db->query('SELECT * FROM goals WHERE id = :id AND usuario_id = :usuario_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->single() ?: false;
    }

    // Atualiza uma meta existente
    public function update($id, $data, $usuario_id) {
        $this->db->query('
            UPDATE goals 
            SET nome = :nome, valor_objetivo = :valor_objetivo, aporte_mensal = :aporte_mensal, variacao_anual = :variacao_anual, updated_at = NOW() 
            WHERE id = :id AND usuario_id = :usuario_id
        ');
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $usuario_id);
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':valor_objetivo', $data['valor_objetivo']);
        $this->db->bind(':aporte_mensal', $data['aporte_mensal']);
        $this->db->bind(':variacao_anual', $data['variacao_anual']);
        return $this->db->execute();
    }

    // Exclui uma meta
    public function delete($id, $usuario_id) {
        $this->db->query("DELETE FROM goals WHERE id = :id AND usuario_id = :usuario_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->execute();
    }
}