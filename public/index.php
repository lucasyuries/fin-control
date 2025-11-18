<?php
// Arquivo: public/index.php
// Ponto de entrada do sistema FinControl

// Carrega as configurações (BASE_URL e DB)
require_once __DIR__ . '/../config/config.php';

// Carrega classes de Core (Sessão, DB)
require_once __DIR__ . '/../src/core/Session.php'; 

// Carrega os Controllers
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/DashboardController.php';
require_once __DIR__ . '/../src/controllers/TransactionController.php';
require_once __DIR__ . '/../src/controllers/ProfileController.php';
require_once __DIR__ . '/../src/controllers/GoalController.php';

// INICIALIZA A SESSÃO NO PONTO DE ENTRADA DO PROJETO
Session::init();

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);

switch ($url) {
    case 'register':
        $authController = new AuthController();
        $_SERVER['REQUEST_METHOD'] == 'POST' ? $authController->register() : $authController->showRegisterForm();
        break;
    case 'login':
        $authController = new AuthController();
        $_SERVER['REQUEST_METHOD'] == 'POST' ? $authController->login() : $authController->showLoginForm();
        break;
    case 'forgot-password':
        $authController = new AuthController();
        $_SERVER['REQUEST_METHOD'] == 'POST' ? $authController->forgotPassword() : $authController->showForgotPasswordForm();
        break;
    case 'reset-password':
        $authController = new AuthController();
        $_SERVER['REQUEST_METHOD'] == 'POST' ? $authController->resetPassword() : $authController->showResetPasswordForm();
        break;
    case 'logout':
        Session::destroy(); // Usa o Session Core
        header('Location: ' . BASE_URL . '/login');
        exit();
    case 'dashboard':
        $dashboardController = new DashboardController();
        $dashboardController->index();
        break;
    case 'profile':
        $profileController = new ProfileController();
        $profileController->index();
        break;
    case 'update-profile':
        $profileController = new ProfileController();
        $profileController->updateProfile();
        break;
    case 'change-password':
        $profileController = new ProfileController();
        $profileController->changePassword();
        break;
    case 'lancamentos':
        $transactionController = new TransactionController();
        $transactionController->index();
        break;
    case 'lancamentos/criar':
        $transactionController = new TransactionController();
        $transactionController->criar();
        break;
    case 'lancamentos/compensar':
        $transactionController = new TransactionController();
        $transactionController->compensar();
        break;
    case 'lancamentos/editar':
        $transactionController = new TransactionController();
        $transactionController->editar();
        break;
    case 'lancamentos/excluir':
        $transactionController = new TransactionController();
        $transactionController->excluir();
        break;
    case 'metas':
        $goalController = new GoalController();
        $goalController->index();
        break;
    case 'metas/create':
        $goalController = new GoalController();
        $goalController->create();
        break;
    // Rotas de edição e exclusão com ID na URL
    case (preg_match('/^metas\\/update\\/(\\d+)$/', $url, $matches) ? true : false):
        $goalController = new GoalController();
        $goalController->update($matches[1]);
        break;
    case (preg_match('/^metas\\/delete\\/(\\d+)$/', $url, $matches) ? true : false):
        $goalController = new GoalController();
        $goalController->delete($matches[1]);
        break;
    default:
        // Rota padrão (geralmente redireciona para login ou dashboard)
        $authController = new AuthController();
        $authController->login(); 
        break;
}