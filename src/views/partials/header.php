<?php 
// Arquivo: src/views/partials/header.php
// A sessão é iniciada e autenticada no Controller/Router, então a variável $_SESSION já está disponível.

// Define a função de exibição de Flash Messages para ser usada em todas as views autenticadas
function display_flash_messages() {
    // Para usar a função Session::getFlash, precisamos incluir a classe Session.
    // O caminho foi ajustado para subir dois níveis de pasta.
    require_once __DIR__ . '/../../core/Session.php'; // <--- CAMINHO CORRIGIDO!
    
    $success = \Session::getFlash('success');
    $error = \Session::getFlash('error');
    $warning = \Session::getFlash('warning'); 
    
    if ($success) {
        echo '<div class="alert alert-success">';
        echo '<svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>';
        echo '<strong>Sucesso:</strong> ' . htmlspecialchars($success) . '</div>';
    }
    if ($error) {
        echo '<div class="alert alert-error">';
        echo '<svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg>';
        echo '<strong>Erro:</strong> ' . htmlspecialchars($error) . '</div>';
    }
    if ($warning) {
        echo '<div class="alert alert-warning">';
        echo '<svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.91.956L8.1 10.5h-.2l-.8-4.544c-.044-.494.375-.956.91-.956zM8 12.5c.732 0 1.002-.572 1.002-.95S8.732 10.5 8 10.5s-1.002.572-1.002.95S7.268 12.5 8 12.5z"/></svg>';
        echo '<strong>Aviso:</strong> ' . htmlspecialchars($warning) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'FinControl'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css"> 
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
<header class="main-header">
    <div class="header-container">
        <a href="<?php echo BASE_URL; ?>/dashboard" class="header-logo">
            <svg width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z"/>
            </svg>
            FinControl
        </a>
        <div class="header-actions">
            <div class="user-info">
                <a href="<?php echo BASE_URL; ?>/profile" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: white;">
                    <div class="user-avatar">
                        <?php 
                        $iniciais = '';
                        // Usamos $_SESSION['user_nome'] que é garantido pelo Controller no login
                        $nome_partes = explode(' ', $_SESSION['user_nome']); 
                        foreach ($nome_partes as $parte) {
                            if (!empty($parte)) {
                                $iniciais .= strtoupper(substr($parte, 0, 1));
                                if (strlen($iniciais) >= 2) break;
                            }
                        }
                        echo $iniciais;
                        ?>
                    </div>
                    <div>
                        <div style="font-weight: 500; font-size: 0.9rem;">
                            <?php echo htmlspecialchars($_SESSION['user_nome']); ?>
                        </div>
                        <div style="color: #9aa0a6; font-size: 0.8rem;">
                            Ver perfil
                        </div>
                    </div>
                </a>
                <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-outline" style="margin-left: 1rem;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                    </svg>
                    Sair
                </a>
            </div>
        </div>
    </div>
</header>
<?php endif; ?>

<main class="main-container">
    <?php 
    // Exibe as mensagens flash no topo da área de conteúdo (apenas para views autenticadas)
    if (isset($_SESSION['user_id'])) {
        display_flash_messages(); 
    }
    ?>