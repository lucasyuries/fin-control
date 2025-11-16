<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Transaction.php';

class DashboardController {

    public function __construct() {
        // CORREÇÃO: Inicia a sessão APENAS se ela não estiver ativa
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
        
        // Busca todos os dados necessários
        $summary = $transactionModel->getMonthlySummary($usuario_id);
        $totals = $transactionModel->getTotalsByType($usuario_id);
        $patrimonio = $transactionModel->getPatrimonioTotal($usuario_id);
        $chartData = $transactionModel->getMonthlyChartData($usuario_id);
        $categoryData = $transactionModel->getTotalsByCategory($usuario_id);
        $recentTransactions = $transactionModel->findAllByUserId($usuario_id);

        // Prepara os dados para a view
        $data = [
            'total_receitas' => $summary['total_receitas'],
            'total_despesas' => $summary['total_despesas'],
            'total_receitas_geral' => $totals['receitas'],
            'total_despesas_geral' => $totals['despesas'],
            'patrimonio_total' => $patrimonio,
            'chart_data' => $chartData,
            'category_data' => $categoryData,
            'recent_transactions' => array_slice($recentTransactions, 0, 10) // Últimas 10
        ];
        
        $pageTitle = 'Dashboard';
        require_once __DIR__ . '/../views/dashboard.php';
    }
}