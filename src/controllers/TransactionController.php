<?php
// Arquivo: src/controllers/TransactionController.php
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Category.php'; 
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../core/Session.php'; // Novo CORE

class TransactionController {
    private $transactionModel;
    private $categoryModel;

    public function __construct() {
        Session::requireLogin(); // Exige que o usuário esteja logado
        $this->transactionModel = new Transaction();
        $this->categoryModel = new Category();
    }

    public function index() {
    $usuario_id = $_SESSION['user_id'];
    $categorias = $this->categoryModel->findAllByUserId($usuario_id); 
    
    // Lê parâmetros de filtro da URL
    $tipo = filter_input(INPUT_GET, 'tipo', FILTER_SANITIZE_STRING);
    $data_inicio = filter_input(INPUT_GET, 'data_inicio', FILTER_SANITIZE_STRING);
    $data_fim = filter_input(INPUT_GET, 'data_fim', FILTER_SANITIZE_STRING);

    // Se o filtro de tipo não for definido na URL, define 'todos' como padrão para a view
    if (!$tipo) {
        $tipo = 'todos';
    }

    // Se o tipo for 'todos' na URL, anula o filtro para buscar todos os tipos no Model
    $filtro_para_model = ($tipo === 'todos') ? null : $tipo;
    
    // Chama o Model com os filtros, reduzindo o tráfego de dados!
    $transacoes = $this->transactionModel->findAllByUserId($usuario_id, $filtro_para_model, $data_inicio, $data_fim);
    
    // Prepara os dados de volta para a View
    $data = [
        'transacoes' => $transacoes,
        'categorias' => $categorias,
        'filtro_tipo' => $tipo, // Passa o tipo de volta para manter o botão ativo na view
        'filtro_data_inicio' => $data_inicio,
        'filtro_data_fim' => $data_fim,
        'success' => Session::getFlash('success'),
        'error' => Session::getFlash('error')
    ];
    
    $pageTitle = 'Lançamentos';
    require_once __DIR__ . '/../views/lancamentos.php';
}

    // Cria um novo lançamento
    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/lancamentos');
            exit();
        }
        
        $usuario_id = $_SESSION['user_id'];
        
        // Sanitização e Preparação de Dados
        $data = [
            'usuario_id' => $usuario_id,
            'descricao' => filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING) ?? '',
            'valor' => filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT) ?? 0,
            'data' => filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING) ?? date('Y-m-d'),
            'tipo' => filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING) ?? '',
            'categoria_id' => filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT)
        ];
        
        // Validação
        if (empty($data['descricao']) || empty($data['tipo']) || $data['valor'] <= 0 || !in_array($data['tipo'], ['receita', 'despesa'])) {
            Session::setFlash('error', 'Dados inválidos para o lançamento.');
            header('Location: ' . BASE_URL . '/lancamentos');
            exit();
        }

        if ($this->transactionModel->create($data)) {
            Session::setFlash('success', 'Lançamento criado com sucesso!');
        } else {
            Session::setFlash('error', 'Erro ao criar lançamento.');
        }
        
        header('Location: ' . BASE_URL . '/lancamentos');
        exit();
    }

    // Editar lançamento
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/lancamentos');
            exit();
        }

        $usuario_id = $_SESSION['user_id'];
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        
        // Verifica se a transação existe e pertence ao usuário
        if (!$this->transactionModel->findById($id, $usuario_id)) {
            Session::setFlash('error', 'Lançamento não encontrado ou acesso negado.');
            header('Location: ' . BASE_URL . '/lancamentos');
            exit();
        }

        $data = [
            'descricao' => filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING) ?? '',
            'valor' => filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT) ?? 0,
            'data' => filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING) ?? date('Y-m-d'),
            'tipo' => filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING) ?? '',
            'categoria_id' => filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT)
        ];

        // Validação
        if (empty($data['descricao']) || empty($data['tipo']) || $data['valor'] <= 0 || !in_array($data['tipo'], ['receita', 'despesa'])) {
            Session::setFlash('error', 'Dados inválidos para edição.');
            header('Location: ' . BASE_URL . '/lancamentos');
            exit();
        }

        if ($this->transactionModel->update($id, $data, $usuario_id)) {
            Session::setFlash('success', 'Lançamento editado com sucesso!');
        } else {
            Session::setFlash('error', 'Erro ao editar lançamento.');
        }
        
        header('Location: ' . BASE_URL . '/lancamentos');
        exit();
    }

    // Compensar lançamento
    public function compensar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/lancamentos');
            exit();
        }
        
        $usuario_id = $_SESSION['user_id'];
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $compensada_por = filter_input(INPUT_POST, 'compensada_por', FILTER_VALIDATE_INT);

        if (!$id || !$compensada_por) {
            Session::setFlash('error', 'Selecione dois lançamentos para compensar.');
            header('Location: ' . BASE_URL . '/lancamentos');
            exit();
        }
        
        // 1. Marca o lançamento atual como compensado pelo outro
        $result1 = $this->transactionModel->compensar($id, $compensada_por, $usuario_id);
        
        // 2. Marca o outro lançamento como compensado pelo atual
        $result2 = $this->transactionModel->compensar($compensada_por, $id, $usuario_id);

        if ($result1 && $result2) {
            Session::setFlash('success', 'Lançamentos compensados com sucesso!');
        } else {
            Session::setFlash('error', 'Erro ao compensar lançamentos.');
        }
        
        header('Location: ' . BASE_URL . '/lancamentos');
        exit();
    }

    // Excluir lançamento
    public function excluir() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/lancamentos');
            exit();
        }
        
        $usuario_id = $_SESSION['user_id'];
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        
        // Verifica se a transação pertence ao usuário
        if (!$this->transactionModel->findById($id, $usuario_id)) {
            Session::setFlash('error', 'Lançamento não encontrado ou acesso negado.');
            header('Location: ' . BASE_URL . '/lancamentos');
            exit();
        }
        
        if ($this->transactionModel->delete($id, $usuario_id)) {
            Session::setFlash('success', 'Lançamento excluído com sucesso!');
        } else {
            Session::setFlash('error', 'Erro ao excluir lançamento.');
        }
        
        header('Location: ' . BASE_URL . '/lancamentos');
        exit();
    }
}