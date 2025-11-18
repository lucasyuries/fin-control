<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - FinControl</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-logo">
            <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="color: var(--primary-color);">
                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
            </svg>
            <h1>Criar Nova Senha</h1>
            <p>Digite sua nova senha abaixo. Certifique-se de que seja forte e segura.</p>
        </div>

        <?php if (isset($erro)): ?>
            <div class="alert alert-error" style="text-align: center;">
                <strong>❌ <?php echo htmlspecialchars($erro); ?></strong>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>/reset-password" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
            
            <div class="form-group">
                <label for="nova_senha" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="nova_senha" name="nova_senha" 
                       placeholder="Digite sua nova senha" required autofocus>
            </div>

            <div id="password-rules" style="background: var(--bg-secondary); padding: 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.85rem;">
                <strong style="display: block; margin-bottom: 0.5rem; color: var(--text-primary);">A senha deve conter:</strong>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li id="rule-length" style="padding: 0.25rem 0; color: var(--text-secondary);">✓ Pelo menos 8 caracteres</li>
                    <li id="rule-uppercase" style="padding: 0.25rem 0; color: var(--text-secondary);">✓ Pelo menos uma letra maiúscula (A-Z)</li>
                    <li id="rule-lowercase" style="padding: 0.25rem 0; color: var(--text-secondary);">✓ Pelo menos uma letra minúscula (a-z)</li>
                    <li id="rule-special" style="padding: 0.25rem 0; color: var(--text-secondary);">✓ Pelo menos um caractere especial (!, @, #, etc.)</li>
                </ul>
            </div>

            <div class="form-group">
                <label for="confirma_senha" class="form-label">Confirme a Nova Senha</label>
                <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" 
                       placeholder="Digite a senha novamente" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem; font-size: 1rem; margin-top: 0.5rem;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                </svg>
                Redefinir Senha
            </button>
        </form>

        <div class="auth-footer">
            <a href="<?php echo BASE_URL; ?>/login">Voltar para o login</a>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/public/js/app.js"></script>

</body>
</html>