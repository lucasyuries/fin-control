<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Category.php';

class TransactionController {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    // Exibe a página com o formulário e a lista de transações
    public function index() {
        $usuario_id = $_SESSION['user_id'];
        
        $categoryModel = new Category();
        $categories = $categoryModel->findAllByUserId($usuario_id);

        $transactionModel = new Transaction();
        $transactions = $transactionModel->findAllByUserId($usuario_id);

        $data = [
            'categorias' => $categories,
            'transacoes' => $transactions
        ];
        
        $pageTitle = 'Lançamentos';
        require_once __DIR__ . '/../views/lancamentos.php';
    }

    // Exibe a página de investimentos
    public function investments() {
        $usuario_id = $_SESSION['user_id'];
        
        $categoryModel = new Category();
        $categories = $categoryModel->findAllByUserId($usuario_id);

        $transactionModel = new Transaction();
        $investments = $transactionModel->findByType($usuario_id, 'ativo');

        $data = [
            'categorias' => $categories,
            'investments' => $investments
        ];
        
        $pageTitle = 'Investimentos';
        require_once __DIR__ . '/../views/investimentos.php';
    }

    // Salva um novo lançamento vindo do formulário
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'usuario_id' => $_SESSION['user_id'],
                'descricao' => trim($_POST['descricao']),
                'valor' => trim($_POST['valor']),
                'data' => trim($_POST['data']),
                'categoria_id' => trim($_POST['categoria_id']),
                'tipo' => trim($_POST['tipo']),
                'ticker' => isset($_POST['ticker']) ? strtoupper(trim($_POST['ticker'])) : null,
                'quantidade' => isset($_POST['quantidade']) ? trim($_POST['quantidade']) : null
            ];
            
            // Validação simples (pode ser melhorada)
            if (!empty($data['descricao']) && !empty($data['valor'])) {
                $transactionModel = new Transaction();
                if ($transactionModel->create($data)) {
                    // Redireciona baseado no tipo
                    $redirect = ($data['tipo'] === 'ativo') ? '/investimentos' : '/lancamentos';
                    header('Location: ' . BASE_URL . $redirect);
                    exit();
                }
            }
        }
        // Em caso de falha, redireciona para lançamentos
        header('Location: ' . BASE_URL . '/lancamentos');
        exit();
    }
}