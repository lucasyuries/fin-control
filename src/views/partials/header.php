<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'FinControl'; ?></title>
    <link rel="stylesheet" href="/fin-control/public/css/style.css">
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

