<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha Senha - FinControl</title>
    <link rel="stylesheet" href="/fin-control/public/css/style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-logo">
            <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="color: var(--primary-color);">
                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
            </svg>
            <h1>Esqueceu sua Senha?</h1>
            <p>Não se preocupe! Digite seu email e enviaremos instruções para redefinir sua senha.</p>
        </div>

        <?php if (isset($success)): ?>
            <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #155724; font-size: 0.9rem; text-align: center;">
                <strong>✅ <?php echo $success; ?></strong>
            </div>
        <?php endif; ?>

        <?php if (isset($erro)): ?>
            <div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #721c24; font-size: 0.9rem; text-align: center;">
                <strong>❌ <?php echo $erro; ?></strong>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>/forgot-password" method="POST">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       placeholder="seu@email.com" required autofocus>
                <small style="color: var(--text-secondary); font-size: 0.85rem; display: block; margin-top: 0.5rem;">
                    Digite o email cadastrado na sua conta
                </small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem; font-size: 1rem; margin-top: 0.5rem;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                </svg>
                Enviar Instruções
            </button>
        </form>

        <div class="auth-footer">
            Lembrou sua senha? <a href="<?php echo BASE_URL; ?>/login">Voltar para o login</a>
        </div>
    </div>
</div>

</body>
</html>
