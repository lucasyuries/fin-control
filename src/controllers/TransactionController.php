
	<?php
	require_once __DIR__ . '/../models/Transaction.php';
	require_once __DIR__ . '/../../config/config.php';

	class TransactionController {

	// Editar lançamento
	public function editar() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			header('Location: ' . BASE_URL . '/lancamentos');
			exit();
		}
		if (!isset($_SESSION['user_id'])) {
			header('Location: ' . BASE_URL . '/login');
			exit();
		}
		$usuario_id = $_SESSION['user_id'];
		$id = $_POST['id'] ?? null;
		if (!$id) {
			header('Location: ' . BASE_URL . '/lancamentos?erro=editar');
			exit();
		}
		$data = [
			'descricao' => $_POST['descricao'] ?? '',
			'valor' => $_POST['valor'] ?? 0,
			'data' => $_POST['data'] ?? date('Y-m-d'),
			'tipo' => $_POST['tipo'] ?? '',
			'categoria_id' => $_POST['categoria_id'] ?? null
		];
		$transactionModel = new Transaction();
		$transactionModel->update($id, $data, $usuario_id);
		header('Location: ' . BASE_URL . '/lancamentos');
		exit();
	}
		public function __construct() {
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			if (!isset($_SESSION['user_id'])) {
				header('Location: ' . BASE_URL . '/login');
				exit();
			}
		}

		public function index() {
			$transactionModel = new Transaction();
			$usuario_id = $_SESSION['user_id'];
			$transacoes = $transactionModel->findAllByUserId($usuario_id);
			$data = [
				'transacoes' => $transacoes
			];
			$pageTitle = 'Lançamentos';
			require_once __DIR__ . '/../views/lancamentos.php';
		}

		// Compensar lançamento
		public function compensar() {
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				header('Location: ' . BASE_URL . '/lancamentos');
				exit();
			}
			if (!isset($_SESSION['user_id'])) {
				header('Location: ' . BASE_URL . '/login');
				exit();
			}
			$usuario_id = $_SESSION['user_id'];
			$id = $_POST['id'] ?? null;
			$compensada_por = $_POST['compensada_por'] ?? null;
			if (!$id || !$compensada_por) {
				header('Location: ' . BASE_URL . '/lancamentos?erro=compensar');
				exit();
			}
			$transactionModel = new Transaction();
			$transactionModel->compensar($id, $compensada_por, $usuario_id);
			// Também marca o outro lançamento como compensado, se necessário
			$transactionModel->compensar($compensada_por, $id, $usuario_id);
			header('Location: ' . BASE_URL . '/lancamentos');
			exit();
		}

		// Excluir lançamento
		public function excluir() {
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				header('Location: ' . BASE_URL . '/lancamentos');
				exit();
			}
			if (!isset($_SESSION['user_id'])) {
				header('Location: ' . BASE_URL . '/login');
				exit();
			}
			$usuario_id = $_SESSION['user_id'];
			$id = $_POST['id'] ?? null;
			if (!$id) {
				header('Location: ' . BASE_URL . '/lancamentos?erro=excluir');
				exit();
			}
			$transactionModel = new Transaction();
			$transactionModel->delete($id, $usuario_id);
			header('Location: ' . BASE_URL . '/lancamentos');
			exit();
		}
	}
