<?php
require_once '../src/controllers/AuthController.php';
require_once '../src/controllers/DashboardController.php';
require_once '../src/controllers/TransactionController.php';
require_once '../src/controllers/ProfileController.php';
require_once '../src/controllers/GoalController.php';

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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
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
        $transactionController->store();
        break;
    
    case 'investimentos':
        $transactionController = new TransactionController();
        $transactionController->investments();
        break;
    
    case 'investimentos/criar':
        $transactionController = new TransactionController();
        $transactionController->store();
        break;
    
    case 'metas':
        $goalController = new GoalController();
        $goalController->index();
        break;
    
    case (preg_match('/^metas\/create$/', $url) ? true : false):
        $goalController = new GoalController();
        $goalController->create();
        break;
    
    case (preg_match('/^metas\/update\/(\d+)$/', $url, $matches) ? true : false):
        $goalController = new GoalController();
        $goalController->update($matches[1]);
        break;
    
    case (preg_match('/^metas\/delete\/(\d+)$/', $url, $matches) ? true : false):
        $goalController = new GoalController();
        $goalController->delete($matches[1]);
        break;
    
    default:
        $authController = new AuthController();
        $authController->login();
        break;
}