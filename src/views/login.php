<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FinControl</title>
    <link rel="stylesheet" href="/fin-control/public/css/style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-logo">
            <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="color: var(--primary-color);">
                <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z"/>
            </svg>
            <h1>FinControl</h1>
            <p>Controle suas finanças de forma inteligente</p>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #155724; font-size: 0.9rem;">
                <strong>✅ <?php echo htmlspecialchars($_GET['success']); ?></strong>
            </div>
        <?php endif; ?>

        <?php if (isset($erro)): ?>
            <div style="background: rgba(234, 67, 53, 0.1); border: 1px solid var(--danger-color); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: var(--danger-color); font-size: 0.9rem;">
                <strong>Erro:</strong> <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>/login" method="POST">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="seu@email.com" required autofocus>
            </div>

            <div class="form-group">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="••••••••" required>
                <div style="text-align: right; margin-top: 0.5rem;">
                    <a href="<?php echo BASE_URL; ?>/forgot-password" style="color: var(--primary-color); font-size: 0.85rem; text-decoration: none;">
                        Esqueci minha senha
                    </a>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem; font-size: 1rem; margin-top: 0.5rem;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                </svg>
                Entrar
            </button>
        </form>

        <div class="auth-footer">
            Não tem uma conta? <a href="<?php echo BASE_URL; ?>/register">Cadastre-se gratuitamente</a>
        </div>
    </div>
</div>

</body>
</html>