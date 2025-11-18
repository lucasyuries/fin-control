<?php
// Arquivo: src/controllers/DashboardController.php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../core/Session.php'; // Novo CORE

class DashboardController {

    public function __construct() {
        Session::requireLogin(); // Exige que o usuário esteja logado
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
            'recent_transactions' => array_slice($recentTransactions, 0, 10), // Últimas 10
            'flash_success' => Session::getFlash('success'),
            'flash_error' => Session::getFlash('error')
        ];
        
        $pageTitle = 'Dashboard';
        require_once __DIR__ . '/../views/dashboard.php';
    }
}