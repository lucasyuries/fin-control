<?php
class Session {
    // Inicia a sessão de forma segura se ainda não estiver ativa
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Define uma mensagem flash (ex: "Salvo com sucesso")
    public static function setFlash($key, $message) {
        self::init();
        $_SESSION['flash'][$key] = $message;
    }

    // Recupera e apaga a mensagem (para ela não aparecer de novo ao atualizar a página)
    public static function getFlash($key) {
        self::init();
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    // Verifica se o usuário está logado
    public static function isLoggedIn() {
        self::init();
        return isset($_SESSION['user_id']);
    }

    // Exige login: se não estiver logado, redireciona para login
    public static function requireLogin() {
        self::init();
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    // Destrói a sessão (Logout)
    public static function destroy() {
        self::init();
        session_unset();
        session_destroy();
    }
}