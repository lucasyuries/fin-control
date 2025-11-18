<?php 
// Arquivo: src/views/lancamentos.php
require_once __DIR__ . '/partials/header.php'; 
?>

<script>
    // Define a variável BASE_URL globalmente para uso no JS
    window.BASE_URL = '<?php echo BASE_URL; ?>'; 
    // Variável com todas as transações (agora sem tipo 'ativo')
    window.allTransactions = <?php echo json_encode(array_filter($data['transacoes'] ?? [], function($t) { return $t['tipo'] !== 'ativo'; })); ?>;
    // Variável com todas as categorias (enviadas pelo Controller)
    window.allCategories = <?php echo json_encode($data['categorias'] ?? []); ?>;
</script>

<nav class="nav-tabs">
    <a class="nav-link" href="<?php echo BASE_URL; ?>/dashboard">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
            <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z"/>
        </svg>
        Resumo
    </a>
    <a class="nav-link active" href="<?php echo BASE_URL; ?>/lancamentos">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2z"/>
        </svg>
        Lançamentos
    </a>
    <a class="nav-link" href="<?php echo BASE_URL; ?>/metas">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
            <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
        </svg>
        Metas
    </a>
</nav>

<div class="page-header">
    <div>
        <h1 class="page-title">Lançamentos Financeiros</h1>
        <p class="page-subtitle">Controle suas receitas e despesas do dia a dia</p>
    </div>
    <button class="btn-new-transaction" onclick="openModal()">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Novo Lançamento
    </button>
</div>

<div class="transaction-filters">
    <button class="filter-btn active" onclick="filterTransactions('todos')" data-filter="todos">
        Todos
    </button>
    <button class="filter-btn" onclick="filterTransactions('receita')" data-filter="receita">
        <span class="filter-badge receita">Receitas</span>
    </button>
    <button class="filter-btn" onclick="filterTransactions('despesa')" data-filter="despesa">
        <span class="filter-badge despesa">Despesas</span>
    </button>
</div>

<div class="transactions-card">
    <div class="table-responsive">
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Categoria</th>
                    <th class="text-end">Valor</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody id="transactionsTableBody">
                <?php if (empty($data['transacoes'])): ?>
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 3rem; color: #5f6368;">
                            Nenhum lançamento encontrado. Clique em "Novo Lançamento" para começar.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['transacoes'] as $transacao): ?>
                        <?php if ($transacao['tipo'] != 'ativo'): // Não mostra ativos aqui ?>
                        <tr data-tipo="<?php echo htmlspecialchars($transacao['tipo']); ?>">
                            <td><?php echo date('d/m/Y', strtotime($transacao['data'])); ?></td>
                            <td><strong><?php echo htmlspecialchars($transacao['descricao']); ?></strong></td>
                            <td>
                                <span class="type-badge <?php echo $transacao['tipo']; ?>">
                                    <?php echo ucfirst($transacao['tipo']); ?>
                                </span>
                                <?php if (!empty($transacao['compensada_por'])): ?>
                                    <span class="compensada-indicator" title="Compensada">
                                        <svg width="14" height="14" fill="#34a853" viewBox="0 0 16 16" style="vertical-align:middle;"><path d="M13.485 1.929a1 1 0 0 1 0 1.414l-7.071 7.071-3.182-3.182a1 1 0 1 1 1.414-1.414l1.768 1.768 6.364-6.364a1 1 0 0 1 1.414 0z"/></svg>
                                        <span style="color:#34a853;font-size:0.85em;">Compensada</span>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="category-tag"><?php echo htmlspecialchars($transacao['categoria_nome'] ?? 'Sem categoria'); ?></span>
                            </td>
                            <td class="text-end">
                                <span class="value-<?php echo $transacao['tipo']; ?>">
                                    <?php echo ($transacao['tipo'] == 'receita' ? '+' : '-') . ' R$ ' . number_format($transacao['valor'], 2, ',', '.'); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn-icon" title="Editar" type="button" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($transacao), ENT_QUOTES, 'UTF-8'); ?>)">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                    </svg>
                                </button>
                                <button class="btn-icon" title="Excluir" type="button" onclick="openDeleteModal(<?php echo $transacao['id']; ?>)">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </button>
                                <?php if (empty($transacao['compensada_por'])): ?>
                                    <button class="btn-icon btn-compensar" title="Compensar" type="button" onclick="openCompensarModal(<?php echo $transacao['id']; ?>, '<?php echo $transacao['tipo']; ?>')">
                                        <svg width="16" height="16" fill="#1a73e8" viewBox="0 0 16 16"><path d="M8.5 1a.5.5 0 0 0-1 0v6.5H1a.5.5 0 0 0 0 1h6.5V15a.5.5 0 0 0 1 0V8.5H15a.5.5 0 0 0 0-1H8.5V1z"/></svg>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal-overlay" id="modalOverlay" onclick="closeModal()">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2 class="modal-title">Novo Lançamento</h2>
            <button class="modal-close" onclick="closeModal()">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                </svg>
            </button>
        </div>
        
        <form action="<?php echo BASE_URL; ?>/lancamentos/criar" method="POST" class="modal-form">
            <div class="form-group">
                <label for="tipo">Tipo de Lançamento</label>
                <select class="form-input" id="tipo" name="tipo" required onchange="updateCategories('tipo', 'categoria_id')">
                    <option value="">Selecione o tipo</option>
                    <option value="receita">💰 Receita</option>
                    <option value="despesa">💸 Despesa</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <input type="text" class="form-input" id="descricao" name="descricao" placeholder="Ex: Salário, Aluguel, Supermercado..." required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="categoria_id">Categoria</label>
                    <select class="form-input" id="categoria_id" name="categoria_id">
                        <option value="">Selecionar categoria</option>
                        </select>
                </div>
                
                <div class="form-group">
                    <label for="data">Data</label>
                    <input type="date" class="form-input" id="data" name="data" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="valor">Valor (R$)</label>
                <input type="number" step="0.01" class="form-input" id="valor" name="valor" placeholder="0.00" required>
            </div>
            
            <div class="modal-footer">
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn-submit">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Adicionar Lançamento
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editModalOverlay" onclick="closeEditModal()">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2 class="modal-title">Editar Lançamento</h2>
            <button class="modal-close" onclick="closeEditModal()">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                </svg>
            </button>
        </div>
        <form id="editForm" action="<?php echo BASE_URL; ?>/lancamentos/editar" method="POST" class="modal-form">
            <input type="hidden" name="id" id="editId" value="">
            <div class="form-group">
                <label for="editTipo">Tipo de Lançamento</label>
                <select class="form-input" id="editTipo" name="tipo" required onchange="updateCategories('editTipo', 'editCategoriaId')">
                    <option value="">Selecione o tipo</option>
                    <option value="receita">💰 Receita</option>
                    <option value="despesa">💸 Despesa</option>
                </select>
            </div>
            <div class="form-group">
                <label for="editDescricao">Descrição</label>
                <input type="text" class="form-input" id="editDescricao" name="descricao" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="editCategoriaId">Categoria</label>
                    <select class="form-input" id="editCategoriaId" name="categoria_id">
                        <option value="">Selecionar categoria</option>
                        </select>
                </div>
                <div class="form-group">
                    <label for="editData">Data</label>
                    <input type="date" class="form-input" id="editData" name="data" required>
                </div>
            </div>
            <div class="form-group">
                <label for="editValor">Valor (R$)</label>
                <input type="number" step="0.01" class="form-input" id="editValor" name="valor" required>
            </div>
            <div class="modal-footer">
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" class="btn-submit">Salvar Alterações</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="deleteModalOverlay" onclick="closeDeleteModal()">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2 class="modal-title">Excluir Lançamento</h2>
            <button class="modal-close" onclick="closeDeleteModal()">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                </svg>
            </button>
        </div>
        <form id="deleteForm" action="<?php echo BASE_URL; ?>/lancamentos/excluir" method="POST" class="modal-form">
            <input type="hidden" name="id" id="deleteId" value="">
            <p>Tem certeza que deseja excluir este lançamento?</p>
            <div class="modal-footer">
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancelar</button>
                    <button type="submit" class="btn-submit btn-danger">Excluir</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="compensarModalOverlay" onclick="closeCompensarModal()">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2 class="modal-title">Compensar Lançamento</h2>
            <button class="modal-close" onclick="closeCompensarModal()">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                </svg>
            </button>
        </div>
        <form id="compensarForm" action="<?php echo BASE_URL; ?>/lancamentos/compensar" method="POST" class="modal-form">
            <input type="hidden" name="id" id="compensarId" value="">
            <div class="form-group">
                <label for="compensada_por">Selecione o lançamento para compensar:</label>
                <select class="form-input" id="compensadaPorSelect" name="compensada_por" required>
                    <option value="">Selecione...</option>
                </select>
            </div>
            <div class="modal-footer">
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeCompensarModal()">Cancelar</button>
                    <button type="submit" class="btn-submit">Compensar</button>
                </div>
            </div>
        </form>
    </div>
</div>




<?php require_once __DIR__ . '/partials/footer.php'; ?>