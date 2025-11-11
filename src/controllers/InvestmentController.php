<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Category.php';

class InvestmentController {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    // Exibe a página de investimentos
    public function index() {
        $usuario_id = $_SESSION['user_id'];
        
        $categoryModel = new Category();
        $categories = $categoryModel->findAllByUserId($usuario_id);

        $transactionModel = new Transaction();
        // Busca apenas ativos
        $investments = $transactionModel->findByType($usuario_id, 'ativo');

        $data = [
            'categorias' => $categories,
            'investments' => $investments
        ];
        
        $pageTitle = 'Investimentos';
        require_once __DIR__ . '/../views/investimentos.php';
    }

    // Salva um novo investimento
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'description' => trim($_POST['descricao']),
                'amount' => trim($_POST['valor']),
                'date' => trim($_POST['data']),
                'category_id' => trim($_POST['categoria_id']),
                'ticker' => isset($_POST['ticker']) ? strtoupper(trim($_POST['ticker'])) : null,
                'quantity' => isset($_POST['quantidade']) ? trim($_POST['quantidade']) : null,
                'type' => 'asset' // Sempre ativo
            ];
            
            if (!empty($data['description']) && !empty($data['amount'])) {
                $transactionModel = new Transaction();
                if ($transactionModel->create($data)) {
                    header('Location: ' . BASE_URL . '/investimentos');
                    exit();
                }
            }
        }
        header('Location: ' . BASE_URL . '/investimentos');
        exit();
    }
}
