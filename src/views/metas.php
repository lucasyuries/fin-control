<?php 
// Arquivo: src/views/metas.php
require_once __DIR__ . '/partials/header.php'; 
?>

<script>
    // Define a variável BASE_URL globalmente para uso no JS
    window.BASE_URL = '<?php echo BASE_URL; ?>'; 
</script>

<nav class="nav-tabs">
	<a class="nav-link" href="<?php echo BASE_URL; ?>/dashboard">
		<svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
			<path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
			<path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z"/>
		</svg>
		Resumo
	</a>
	<a class="nav-link" href="<?php echo BASE_URL; ?>/lancamentos">
		<svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
			<path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2z"/>
		</svg>
		Lançamentos
	</a>
	<a class="nav-link active" href="<?php echo BASE_URL; ?>/metas">
		<svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
			<path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
			<path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
		</svg>
		Metas
	</a>
</nav>

<div class="page-header">
	<div>
		<h1 class="page-title">Metas Financeiras</h1>
		<p class="page-subtitle">Defina e acompanhe suas metas de patrimônio, ativos e proventos</p>
	</div>
	<button class="btn btn-primary" onclick="openGoalModal('create')">
		<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
			<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
		</svg>
		Criar nova meta
	</button>
</div>

<section class="goals-section">
	<h2 class="section-title">Metas em andamento</h2>
	<?php if (empty($data['metas_em_andamento'])): ?>
		<div class="empty-state">
			<svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="opacity: 0.2;">
				<path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
			</svg>
			<p style="margin-top: 1rem; color: var(--text-secondary);">Não há metas em andamento</p>
		</div>
	<?php else: ?>
		<div class="goals-grid">
			<?php foreach ($data['metas_em_andamento'] as $meta): ?>
				<div class="goal-card" onclick="openGoalModal('edit', <?php echo htmlspecialchars(json_encode($meta)); ?>)">
					<div class="goal-icon">💰</div>
					<h3 class="goal-name"><?php echo htmlspecialchars($meta['nome']); ?></h3>
					<div class="goal-progress">
						<div class="progress-bar">
							<div class="progress-fill" style="width: <?php echo min(100, $meta['progresso']['percentual']); ?>%"></div>
						</div>
						<span class="progress-label"><?php echo number_format($meta['progresso']['percentual'], 1, ',', '.'); ?>%</span>
					</div>
					<div class="goal-details">
						<div class="goal-detail-row">
							<span class="label">Atual</span>
							<strong class="value">R$ <?php echo number_format($meta['progresso']['atual'], 2, ',', '.'); ?></strong>
						</div>
						<div class="goal-detail-row">
							<span class="label">Faltam</span>
							<strong class="value">R$ <?php echo number_format($meta['progresso']['falta'], 2, ',', '.'); ?></strong>
						</div>
						<?php if ($meta['meses_estimados'] !== null && $meta['meses_estimados'] > 0): ?>
							<div class="goal-detail-row">
								<span class="label">Estimativa</span>
								<strong class="value">
									<?php 
										$anos = floor($meta['meses_estimados'] / 12);
										$meses = $meta['meses_estimados'] % 12;
										if ($anos > 0) {
											echo $anos . ' ano' . ($anos > 1 ? 's' : '');
											if ($meses > 0) echo ' e ' . $meses . ' mês' . ($meses > 1 ? 'es' : '');
										} else {
											echo $meses . ' mês' . ($meses > 1 ? 'es' : '');
										}
									?>
								</strong>
							</div>
						<?php endif; ?>
						<div class="goal-detail-row">
							<span class="label">Objetivo</span>
							<strong class="value primary">R$ <?php echo number_format($meta['valor_objetivo'], 2, ',', '.'); ?></strong>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>

<?php if (!empty($data['metas_concluidas'])): ?>
<section class="goals-section">
	<h2 class="section-title">Metas concluídas</h2>
	<div class="goals-grid">
		<?php foreach ($data['metas_concluidas'] as $meta): ?>
			<div class="goal-card completed">
				<div class="goal-icon">✅</div>
				<h3 class="goal-name"><?php echo htmlspecialchars($meta['nome']); ?></h3>
				<div class="goal-progress">
					<div class="progress-bar">
						<div class="progress-fill completed" style="width: 100%"></div>
					</div>
					<span class="progress-label">100%</span>
				</div>
				<div class="goal-details">
					<div class="goal-detail-row">
						<span class="label">Atual</span>
						<strong class="value">R$ <?php echo number_format($meta['progresso']['atual'], 2, ',', '.'); ?></strong>
					</div>
					<div class="goal-detail-row">
						<span class="label">Faltam</span>
						<strong class="value">R$ 0,00</strong>
					</div>
					<div class="goal-detail-row">
						<span class="label">Concluído em</span>
						<strong class="value"><?php echo date('m/Y', strtotime($meta['data_conclusao'] ?? date('Y-m-d'))); ?></strong>
					</div>
					<div class="goal-detail-row">
						<span class="label">Objetivo</span>
						<strong class="value primary">R$ <?php echo number_format($meta['valor_objetivo'], 2, ',', '.'); ?></strong>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<div class="modal-overlay" id="goalModal" onclick="closeGoalModal()">
	<div class="modal-container" onclick="event.stopPropagation()">
		<div class="modal-header">
			<h2 id="modalTitle">Criar Meta</h2>
			<button class="modal-close" onclick="closeGoalModal()">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                </svg>
            </button>
		</div>
        
		<form id="goalForm" method="POST" action="<?php echo BASE_URL; ?>/metas/create">
			<input type="hidden" id="goalId" name="goal_id">
            
            <div class="modal-form">
                <div class="form-group">
                    <label>Meta de Patrimônio</label>
                    <p class="form-help">Defina uma meta de patrimônio que melhor se alinhe com seu perfil de investimento e seus objetivos pessoais.</p>
                </div>
                <div class="form-group">
                    <label for="nome">Escolha uma categoria para sua meta</label>
                    <input type="text" id="nome" name="nome" class="form-control" placeholder="Ex: Aposentadoria, Viagem, Casa própria" required>
                </div>
                <div class="form-group">
                    <label for="valor_objetivo">Valor total</label>
                    <div class="input-group">
                        <span class="input-prefix">R$</span>
                        <input type="text" id="valor_objetivo" name="valor_objetivo" class="form-control money-input" placeholder="0,00" required>
                    </div>
                </div>
                <div class="form-divider">
                    <span>Como você planeja alcançar essa meta?</span>
                </div>
                <div class="form-group">
                    <label for="aporte_mensal">Aporte mensal</label>
                    <div class="input-group">
                        <span class="input-prefix">R$</span>
                        <input type="text" id="aporte_mensal" name="aporte_mensal" class="form-control money-input" placeholder="0,00">
                    </div>
                </div>
                <div class="form-group">
                    <label for="variacao_anual">Estimativa de variação anual (%)</label>
                    <div class="input-group">
                        <input type="text" id="variacao_anual" name="variacao_anual" class="form-control percent-input" placeholder="0,00">
                        <span class="input-suffix">%</span>
                    </div>
                    <p class="form-help">Informe a rentabilidade média anual esperada (sem considerar a inflação).</p>
                </div>
                <input type="hidden" name="tipo" value="patrimonio">
            </div>
            
            <div class="modal-footer">
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeGoalModal()">Cancelar</button>
                    <button type="button" id="deleteBtn" class="btn btn-danger" onclick="deleteGoal()" style="display: none;">Deletar meta</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Criar meta</button>
                </div>
            </div>
		</form>
	</div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>