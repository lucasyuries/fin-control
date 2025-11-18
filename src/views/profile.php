<?php require_once __DIR__ . '/partials/header.php'; ?>

<div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
    
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

<?php require_once __DIR__ . '/partials/footer.php'; ?>