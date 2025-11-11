<?php require_once __DIR__ . '/partials/header.php'; ?>

<!-- Mensagens de Sucesso/Erro -->
<?php if (isset($data['success'])): ?>
    <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; color: #155724; display: flex; align-items: center; gap: 0.75rem;">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </svg>
        <strong><?php echo htmlspecialchars($data['success']); ?></strong>
    </div>
<?php endif; ?>

<?php if (isset($data['error'])): ?>
    <div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; color: #721c24; display: flex; align-items: center; gap: 0.75rem;">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
        </svg>
        <strong><?php echo htmlspecialchars($data['error']); ?></strong>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
    
    <!-- Card de Informações do Perfil -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="user-avatar" style="width: 64px; height: 64px; font-size: 1.5rem;">
                    <?php 
                    $iniciais = '';
                    $nome_partes = explode(' ', $data['user']['nome']);
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
                    <h2 style="margin: 0; font-size: 1.5rem; color: var(--text-primary);">
                        <?php echo htmlspecialchars($data['user']['nome']); ?>
                    </h2>
                    <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem;">
                        Membro desde <?php echo date('d/m/Y', strtotime($data['user']['created_at'])); ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <h3 style="font-size: 1.1rem; margin-bottom: 1.5rem; color: var(--text-primary);">
                📝 Informações Pessoais
            </h3>
            
            <form action="<?php echo BASE_URL; ?>/update-profile" method="POST">
                <div class="form-group">
                    <label for="nome" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="nome" name="nome" 
                           value="<?php echo htmlspecialchars($data['user']['nome']); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($data['user']['email']); ?>" 
                           required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                    </svg>
                    Salvar Alterações
                </button>
            </form>
        </div>
    </div>
    
    <!-- Card de Alteração de Senha -->
    <div class="card">
        <div class="card-header">
            <h3 style="margin: 0; font-size: 1.1rem; color: var(--text-primary);">
                🔒 Segurança da Conta
            </h3>
        </div>
        
        <div class="card-body">
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                Altere sua senha para manter sua conta segura. Use uma senha forte com letras, números e caracteres especiais.
            </p>
            
            <form action="<?php echo BASE_URL; ?>/change-password" method="POST">
                <div class="form-group">
                    <label for="senha_atual" class="form-label">Senha Atual</label>
                    <input type="password" class="form-control" id="senha_atual" name="senha_atual" 
                           placeholder="Digite sua senha atual" required>
                </div>
                
                <div class="form-group">
                    <label for="nova_senha" class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" id="nova_senha" name="nova_senha" 
                           placeholder="Digite a nova senha" required>
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
                           placeholder="Digite a nova senha novamente" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                    </svg>
                    Alterar Senha
                </button>
            </form>
        </div>
    </div>
    
</div>

<script>
// Validação visual da senha
const novaSenhaInput = document.getElementById('nova_senha');
const rules = {
    length: document.getElementById('rule-length'),
    uppercase: document.getElementById('rule-uppercase'),
    lowercase: document.getElementById('rule-lowercase'),
    special: document.getElementById('rule-special')
};

novaSenhaInput.addEventListener('input', () => {
    const senha = novaSenhaInput.value;
    
    if (senha.length >= 8) {
        rules.length.style.color = 'var(--success-color)';
        rules.length.style.textDecoration = 'line-through';
    } else {
        rules.length.style.color = 'var(--text-secondary)';
        rules.length.style.textDecoration = 'none';
    }
    
    if (/[A-Z]/.test(senha)) {
        rules.uppercase.style.color = 'var(--success-color)';
        rules.uppercase.style.textDecoration = 'line-through';
    } else {
        rules.uppercase.style.color = 'var(--text-secondary)';
        rules.uppercase.style.textDecoration = 'none';
    }
    
    if (/[a-z]/.test(senha)) {
        rules.lowercase.style.color = 'var(--success-color)';
        rules.lowercase.style.textDecoration = 'line-through';
    } else {
        rules.lowercase.style.color = 'var(--text-secondary)';
        rules.lowercase.style.textDecoration = 'none';
    }
    
    if (/[^A-Za-z0-9]/.test(senha)) {
        rules.special.style.color = 'var(--success-color)';
        rules.special.style.textDecoration = 'line-through';
    } else {
        rules.special.style.color = 'var(--text-secondary)';
        rules.special.style.textDecoration = 'none';
    }
});

// Remove mensagens após 5 segundos
setTimeout(() => {
    const alerts = document.querySelectorAll('[style*="background: #d4edda"], [style*="background: #f8d7da"]');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
