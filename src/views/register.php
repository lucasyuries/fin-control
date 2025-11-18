<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - FinControl</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-logo">
            <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="color: var(--primary-color);">
                <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z"/>
            </svg>
            <h1>FinControl</h1>
            <p>Crie sua conta e comece a organizar suas finanças</p>
        </div>
        
        <?php if (isset($data['erros']['geral'])): ?>
             <div class="alert alert-error" style="text-align: center; margin-bottom: 1.5rem; color: var(--danger-color); background: rgba(234, 67, 53, 0.1); border-color: var(--danger-color);">
                <strong>Erro:</strong> <?php echo htmlspecialchars($data['erros']['geral']); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>/register" method="POST">
            <div class="form-group">
                <label for="nome" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($data['nome'] ?? ''); ?>" placeholder="João Silva" required autofocus>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" placeholder="seu@email.com" required>
                <?php if (isset($data['erros']['email_err'])): ?>
                    <div class="invalid-feedback d-block"><?php echo $data['erros']['email_err']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="••••••••" required>
                <?php if (isset($data['erros']['senha_err'])): ?>
                    <div class="invalid-feedback d-block"><?php echo $data['erros']['senha_err']; ?></div>
                <?php endif; ?>
            </div>

            <div id="password-rules" style="background: var(--bg-secondary); padding: 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.85rem;">
                <strong style="display: block; margin-bottom: 0.5rem; color: var(--text-primary);">A senha deve conter:</strong>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li id="rule-length" style="padding: 0.25rem 0;">✓ Pelo menos 8 caracteres</li>
                    <li id="rule-uppercase" style="padding: 0.25rem 0;">✓ Pelo menos uma letra maiúscula (A-Z)</li>
                    <li id="rule-lowercase" style="padding: 0.25rem 0;">✓ Pelo menos uma letra minúscula (a-z)</li>
                    <li id="rule-special" style="padding: 0.25rem 0;">✓ Pelo menos um caractere especial (!, @, #, etc.)</li>
                </ul>
            </div>

            <div class="form-group">
                <label for="confirma_senha" class="form-label">Confirme a Senha</label>
                <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" placeholder="••••••••" required>
                <?php if (isset($data['erros']['confirma_senha_err'])): ?>
                    <div class="invalid-feedback d-block"><?php echo $data['erros']['confirma_senha_err']; ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem; font-size: 1rem; margin-top: 0.5rem;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                </svg>
                Criar Conta
            </button>
        </form>

        <div class="auth-footer">
            Já tem uma conta? <a href="<?php echo BASE_URL; ?>/login">Faça login</a>
        </div>
    </div>
</div>


</body>
</html>