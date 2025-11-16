<?php
require_once __DIR__ . '/../core/Database.php';

class Goal {
	// Cria uma nova meta
	public function create($data) {
		$this->db->query('INSERT INTO goals (usuario_id, nome, valor_objetivo, aporte_mensal, variacao_anual, tipo, status, created_at) VALUES (:usuario_id, :nome, :valor_objetivo, :aporte_mensal, :variacao_anual, :tipo, :status, NOW())');
		$this->db->bind(':usuario_id', $data['usuario_id']);
		$this->db->bind(':nome', $data['nome']);
		$this->db->bind(':valor_objetivo', $data['valor_objetivo']);
		$this->db->bind(':aporte_mensal', $data['aporte_mensal']);
		$this->db->bind(':variacao_anual', $data['variacao_anual']);
		$this->db->bind(':tipo', $data['tipo']);
		$this->db->bind(':status', 'andamento');
		return $this->db->execute();
	}
	private $db;

	public function __construct() {
		$this->db = new Database();
	}

	// Busca todas as metas do usuário
	public function findAllByUserId($usuario_id) {
		$this->db->query('SELECT * FROM goals WHERE usuario_id = :usuario_id ORDER BY created_at DESC');
		$this->db->bind(':usuario_id', $usuario_id);
		return $this->db->resultSet();
	}
}