<?php require_once __DIR__ . '/partials/header.php'; ?>

<!-- Navegação de Abas -->
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

<?php if (isset($_SESSION['success'])): ?>
	<div class="alert alert-success">
		<?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
	</div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
	<div class="alert alert-error">
		<?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
	</div>
<?php endif; ?>

<!-- Cabeçalho da Página -->
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

<!-- Metas em Andamento -->
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
						<span class="progress-label"><?php echo number_format($meta['progresso']['percentual'], 1); ?>%</span>
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
						<?php if ($meta['meses_estimados'] && $meta['meses_estimados'] > 0): ?>
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

<!-- Metas Concluídas -->
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
						<strong class="value"><?php echo date('F/y', strtotime($meta['data_conclusao'])); ?></strong>
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

<!-- Modal de Meta -->
<div id="goalModal" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<h2 id="modalTitle">Criar Meta</h2>
			<button class="modal-close" onclick="closeGoalModal()">&times;</button>
		</div>
		<form id="goalForm" method="POST" action="<?php echo BASE_URL; ?>/metas/create">
			<input type="hidden" id="goalId" name="goal_id">
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
				<label for="variacao_anual">Estimativa de variação anual</label>
				<div class="input-group">
					<input type="text" id="variacao_anual" name="variacao_anual" class="form-control percent-input" placeholder="0,00">
					<span class="input-suffix">%</span>
				</div>
			</div>
			<input type="hidden" name="tipo" value="patrimonio">
			<div class="modal-actions">
				<button type="button" class="btn btn-secondary" onclick="closeGoalModal()">Cancelar</button>
				<button type="button" id="deleteBtn" class="btn btn-danger" onclick="deleteGoal()" style="display: none;">Deletar meta</button>
				<button type="submit" class="btn btn-primary" id="submitBtn">Criar meta</button>
			</div>
		</form>
	</div>
</div>

<style>
/* Metas - Visual moderno e alinhado ao sistema */
.page-header {
	display: flex;
	justify-content: space-between;
	align-items: flex-start;
	margin-bottom: 2rem;
	gap: 2rem;
}
.page-title {
	font-size: 1.75rem;
	font-weight: 700;
	color: var(--text-primary, #202124);
	margin: 0;
}
.page-subtitle {
	color: var(--text-secondary, #5f6368);
	margin: 0.5rem 0 0 0;
	font-size: 0.95rem;
}
.btn.btn-primary {
	background: #1a73e8;
	color: #fff;
	border: none;
	padding: 0.75rem 1.5rem;
	border-radius: 8px;
	font-weight: 500;
	display: flex;
	align-items: center;
	gap: 0.5rem;
	cursor: pointer;
	transition: all 0.2s;
	box-shadow: 0 2px 8px rgba(26, 115, 232, 0.08);
}
.btn.btn-primary:hover {
	background: #1557b0;
	transform: translateY(-1px);
}
.goals-section {
	margin-bottom: 3rem;
}
.section-title {
	font-size: 1.25rem;
	font-weight: 600;
	color: var(--text-primary, #202124);
	margin-bottom: 1.5rem;
}
.goals-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
	gap: 1.5rem;
}
.goal-card {
	background: #fff;
	border: 1px solid #e8eaed;
	border-radius: 12px;
	padding: 1.5rem;
	cursor: pointer;
	transition: box-shadow 0.2s, transform 0.2s;
	box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.goal-card:hover {
	box-shadow: 0 4px 16px rgba(26, 115, 232, 0.10);
	transform: translateY(-2px);
}
.goal-card.completed {
	background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);
	border-color: #34a853;
}
.goal-icon {
	font-size: 2rem;
	margin-bottom: 1rem;
}
.goal-name {
	font-size: 1.1rem;
	font-weight: 600;
	color: var(--text-primary, #202124);
	margin-bottom: 1rem;
}
.goal-progress {
	margin-bottom: 1.5rem;
}
.progress-bar {
	height: 8px;
	background: #f1f3f4;
	border-radius: 4px;
	overflow: hidden;
	margin-bottom: 0.5rem;
}
.progress-fill {
	height: 100%;
	background: linear-gradient(90deg, #1a73e8 0%, #4285f4 100%);
	border-radius: 4px;
	transition: width 0.3s;
}
.progress-fill.completed {
	background: linear-gradient(90deg, #34a853 0%, #2e7d32 100%);
}
.progress-label {
	font-size: 0.85rem;
	font-weight: 600;
	color: #5f6368;
}
.goal-details {
	display: flex;
	flex-direction: column;
	gap: 0.75rem;
}
.goal-detail-row {
	display: flex;
	justify-content: space-between;
	align-items: center;
}
.goal-detail-row .label {
	color: #5f6368;
	font-size: 0.9rem;
}
.goal-detail-row .value {
	color: #202124;
	font-size: 0.95rem;
}
.goal-detail-row .value.primary {
	color: #1a73e8;
	font-weight: 700;
}
.empty-state {
	text-align: center;
	padding: 4rem 2rem;
	background: #fff;
	border: 2px dashed #e8eaed;
	border-radius: 12px;
}
/* Modal metas */
.modal {
	display: none;
	position: fixed;
	z-index: 1000;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0.5);
	animation: fadeIn 0.2s ease;
}
.modal.active {
	display: flex;
	align-items: center;
	justify-content: center;
}
.modal-content {
	background: #fff;
	border-radius: 16px;
	max-width: 500px;
	width: 90%;
	max-height: 90vh;
	overflow-y: auto;
	animation: slideUp 0.3s ease;
}
.modal-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 1.5rem;
	border-bottom: 1px solid #e8eaed;
}
.modal-header h2 {
	font-size: 1.25rem;
	font-weight: 600;
	margin: 0;
}
.modal-close {
	background: none;
	border: none;
	font-size: 1.5rem;
	cursor: pointer;
	color: #5f6368;
	width: 32px;
	height: 32px;
	display: flex;
	align-items: center;
	justify-content: center;
	border-radius: 50%;
	transition: all 0.2s;
}
.modal-close:hover {
	background: #f1f3f4;
	color: #202124;
}
.modal form {
	padding: 1.5rem;
}
.form-divider {
	margin: 1.5rem 0;
	text-align: center;
	position: relative;
}
.form-divider::before {
	content: '';
	position: absolute;
	left: 0;
	top: 50%;
	width: 100%;
	height: 1px;
	background: #e8eaed;
}
.form-divider span {
	background: #fff;
	padding: 0 1rem;
	position: relative;
	color: #5f6368;
	font-size: 0.9rem;
}
.modal-actions {
	display: flex;
	gap: 0.75rem;
	justify-content: flex-end;
	margin-top: 2rem;
}
@keyframes fadeIn {
	from { opacity: 0; }
	to { opacity: 1; }
}
@keyframes slideUp {
	from {
		opacity: 0;
		transform: translateY(20px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}
@media (max-width: 768px) {
	.page-header {
		flex-direction: column;
	}
	.goals-grid {
		grid-template-columns: 1fr;
	}
}
</style>

<script>
let editingGoalId = null;
function openGoalModal(mode, goalData = null) {
	const modal = document.getElementById('goalModal');
	const form = document.getElementById('goalForm');
	const modalTitle = document.getElementById('modalTitle');
	const submitBtn = document.getElementById('submitBtn');
	const deleteBtn = document.getElementById('deleteBtn');
	if (mode === 'create') {
		modalTitle.textContent = 'Criar Meta';
		submitBtn.textContent = 'Criar meta';
		form.action = '<?php echo BASE_URL; ?>/metas/create';
		form.reset();
		deleteBtn.style.display = 'none';
		editingGoalId = null;
	} else {
		modalTitle.textContent = 'Editar Meta';
		submitBtn.textContent = 'Salvar alterações';
		form.action = '<?php echo BASE_URL; ?>/metas/update/' + goalData.id;
		deleteBtn.style.display = 'block';
		editingGoalId = goalData.id;
		document.getElementById('nome').value = goalData.nome;
		document.getElementById('valor_objetivo').value = parseFloat(goalData.valor_objetivo).toFixed(2).replace('.', ',');
		document.getElementById('aporte_mensal').value = parseFloat(goalData.aporte_mensal).toFixed(2).replace('.', ',');
		document.getElementById('variacao_anual').value = parseFloat(goalData.variacao_anual).toFixed(2).replace('.', ',');
	}
	modal.classList.add('active');
}
function closeGoalModal() {
	const modal = document.getElementById('goalModal');
	modal.classList.remove('active');
}
function deleteGoal() {
	if (!editingGoalId) return;
	if (confirm('Tem certeza que deseja excluir esta meta?')) {
		window.location.href = '<?php echo BASE_URL; ?>/metas/delete/' + editingGoalId;
	}
}
document.getElementById('goalModal').addEventListener('click', function(e) {
	if (e.target === this) {
		closeGoalModal();
	}
});
document.querySelectorAll('.money-input').forEach(input => {
	input.addEventListener('input', function(e) {
		let value = e.target.value.replace(/\D/g, '');
		value = (value / 100).toFixed(2);
		e.target.value = value.replace('.', ',');
	});
});
document.querySelectorAll('.percent-input').forEach(input => {
	input.addEventListener('input', function(e) {
		let value = e.target.value.replace(/[^\d,]/g, '');
		e.target.value = value;
	});
});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>