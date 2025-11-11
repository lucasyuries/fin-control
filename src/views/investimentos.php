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
    <a class="nav-link active" href="<?php echo BASE_URL; ?>/investimentos">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
        </svg>
        Investimentos
    </a>
    <a class="nav-link" href="<?php echo BASE_URL; ?>/metas">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
            <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
        </svg>
        Metas
    </a>
</nav>

<!-- Cabeçalho da Página -->
<div class="page-header">
    <div>
        <h1 class="page-title">Carteira de Investimentos</h1>
        <p class="page-subtitle">Gerencie seus ativos e investimentos</p>
    </div>
    <button class="btn-new-transaction" onclick="openModal()">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Adicionar Ativo
    </button>
</div>

<!-- Tabela de Investimentos -->
<div class="transactions-card">
    <div class="table-responsive">
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Ativo</th>
                    <th>Categoria</th>
                    <th class="text-end">Valor Investido</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['investments'])): ?>
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 3rem; color: #5f6368;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                                <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="opacity: 0.2;">
                                    <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                </svg>
                                <div>
                                    <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">Nenhum investimento cadastrado</p>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;">Clique em "Adicionar Ativo" para começar sua carteira</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['investments'] as $investment): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($investment['data'])); ?></td>
                            <td><strong><?php echo htmlspecialchars($investment['descricao']); ?></strong></td>
                            <td>
                                <span class="category-tag"><?php echo htmlspecialchars($investment['categoria_nome'] ?? 'Sem categoria'); ?></span>
                            </td>
                            <td class="text-end">
                                <span class="value-ativo">
                                    R$ <?php echo number_format($investment['valor'], 2, ',', '.'); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn-icon" title="Editar">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                    </svg>
                                </button>
                                <button class="btn-icon" title="Excluir">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Novo Investimento -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal()">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2 class="modal-title">Adicionar Ativo</h2>
            <button class="modal-close" onclick="closeModal()">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                </svg>
            </button>
        </div>
        
        <div class="modal-tabs">
            <button class="modal-tab active" data-tab="compra">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                Compra
            </button>
            <button class="modal-tab" data-tab="venda">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                </svg>
                Venda
            </button>
        </div>
        
        <form action="<?php echo BASE_URL; ?>/investimentos/criar" method="POST" class="modal-form">
            <input type="hidden" name="tipo" value="ativo">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="tipo_ativo">Tipo de ativo</label>
                    <select class="form-input" id="tipo_ativo" name="tipo_ativo" required>
                        <option value="">Selecionar</option>
                        <option value="acoes">Ações</option>
                        <option value="fiis">FIIs</option>
                        <option value="bdrs">BDRs</option>
                        <option value="etfs">ETFs</option>
                        <option value="cripto">Criptomoedas</option>
                        <option value="renda_fixa">Renda Fixa</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="categoria_id">Categoria</label>
                    <select class="form-input" id="categoria_id" name="categoria_id">
                        <option value="">Selecionar categoria</option>
                        <?php foreach($data['categorias'] as $categoria): ?>
                            <option value="<?php echo $categoria['id']; ?>">
                                <?php echo htmlspecialchars($categoria['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="stock-search">Buscar Ativo</label>
                <div class="stock-search-wrapper">
                    <input 
                        type="text" 
                        class="form-input" 
                        id="stock-search" 
                        placeholder="Digite o código ou nome (ex: PETR4, Petrobras...)" 
                        autocomplete="off"
                    >
                    <div id="stock-suggestions" class="stock-suggestions"></div>
                </div>
                <input type="hidden" id="descricao" name="descricao" required>
                <input type="hidden" id="ticker" name="ticker">
                <div id="selected-stock" class="selected-stock"></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="data">Data da Compra</label>
                    <input type="date" class="form-input" id="data" name="data" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="quantidade">Quantidade de Ações</label>
                    <input type="number" step="0.0001" class="form-input" id="quantidade" name="quantidade" placeholder="1" value="1" min="0.0001" onchange="calculateTotal()" required>
                </div>
                
                <div class="form-group">
                    <label for="valor">Preço por Ação (R$)</label>
                    <input type="number" step="0.01" class="form-input" id="valor" name="valor" placeholder="0,00" onchange="calculateTotal()" required>
                    <small id="current-price-info" class="form-help"></small>
                </div>
            </div>
            
            <div class="modal-footer">
                <div class="total-value">
                    <span>Valor Total</span>
                    <strong id="valorTotal">R$ 0,00</strong>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn-submit">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Adicionar Ativo
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* Reutiliza os mesmos estilos de lancamentos.php */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 2rem 0;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #202124;
    margin: 0;
}

.page-subtitle {
    color: #5f6368;
    margin: 0.25rem 0 0 0;
}

.btn-new-transaction {
    background: #1a73e8;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-new-transaction:hover {
    background: #1557b0;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
}

.transactions-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.transactions-table {
    width: 100%;
    border-collapse: collapse;
}

.transactions-table thead {
    background: #f8f9fa;
}

.transactions-table th {
    padding: 1rem 1.5rem;
    text-align: left;
    font-weight: 600;
    color: #5f6368;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.transactions-table td {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e8eaed;
    color: #202124;
}

.transactions-table tbody tr {
    transition: background 0.2s;
}

.transactions-table tbody tr:hover {
    background: #f8f9fa;
}

.category-tag {
    color: #5f6368;
    font-size: 0.875rem;
}

.value-ativo {
    color: #1a73e8;
    font-weight: 600;
    font-size: 1rem;
}

.btn-icon {
    background: none;
    border: none;
    padding: 0.375rem;
    cursor: pointer;
    color: #5f6368;
    border-radius: 4px;
    transition: all 0.2s;
}

.btn-icon:hover {
    background: #f1f3f4;
    color: #1a73e8;
}

/* Modal Styles */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-overlay.active {
    display: flex;
}

.modal-container {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 700px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e8eaed;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #202124;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    color: #5f6368;
    border-radius: 50%;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #f1f3f4;
    color: #202124;
}

.modal-tabs {
    display: flex;
    padding: 0 2rem;
    border-bottom: 1px solid #e8eaed;
}

.modal-tab {
    background: none;
    border: none;
    padding: 1rem 1.5rem;
    cursor: pointer;
    color: #5f6368;
    font-weight: 500;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-tab:hover {
    color: #1a73e8;
}

.modal-tab.active {
    color: #1a73e8;
    border-bottom-color: #1a73e8;
}

.modal-form {
    padding: 2rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
}

.form-group label {
    font-weight: 500;
    color: #202124;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-input {
    padding: 0.75rem;
    border: 1px solid #dadce0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #1a73e8;
    box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
}

.modal-footer {
    border-top: 1px solid #e8eaed;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.total-value {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.total-value span {
    font-size: 0.875rem;
    color: #5f6368;
}

.total-value strong {
    font-size: 1.25rem;
    color: #202124;
}

.modal-actions {
    display: flex;
    gap: 1rem;
}

.btn-cancel {
    background: white;
    border: 1px solid #dadce0;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    color: #5f6368;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel:hover {
    background: #f1f3f4;
    border-color: #5f6368;
}

.btn-submit {
    background: #1a73e8;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.btn-submit:hover {
    background: #1557b0;
}
</style>

<script>
function openModal() {
    document.getElementById('modalOverlay').classList.add('active');
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
    document.querySelector('.modal-form').reset();
    document.getElementById('valorTotal').textContent = 'R$ 0,00';
}

function calculateTotal() {
    const valor = parseFloat(document.getElementById('valor').value) || 0;
    const quantidade = parseFloat(document.getElementById('quantidade').value) || 1;
    const total = valor * quantidade;
    document.getElementById('valorTotal').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
    
    // Atualiza o campo valor com o total
    document.getElementById('valor').dataset.total = total;
}

// Sugestões e cotações externas removidas. O usuário digita o ticker/nome e preenche manualmente o preço.
const searchInput = document.getElementById('stock-search');
const suggestionsDiv = document.getElementById('stock-suggestions');
const selectedStockDiv = document.getElementById('selected-stock');
const descricaoInput = document.getElementById('descricao');
const tickerInput = document.getElementById('ticker');
const valorInput = document.getElementById('valor');
const priceInfoSpan = document.getElementById('current-price-info');

if (searchInput) {
    searchInput.addEventListener('input', function() {
        const q = this.value.trim();
        // Preenche campos ocultos com o que o usuário digitou
        descricaoInput.value = q;
        tickerInput.value = q.toUpperCase();
        // Sem cotações: limpa info de preço automático
        priceInfoSpan.textContent = '';
        suggestionsDiv.innerHTML = '';
        suggestionsDiv.style.display = 'none';
        selectedStockDiv.innerHTML = '';
        selectedStockDiv.style.display = 'none';
    });
}

// Antes de enviar o form, atualiza o valor com o total
document.querySelector('.modal-form').addEventListener('submit', function(e) {
    const quantidade = parseFloat(document.getElementById('quantidade').value) || 1;
    const valorUnitario = parseFloat(document.getElementById('valor').value) || 0;
    const total = valorUnitario * quantidade;
    document.getElementById('valor').value = total.toFixed(2);
    // Garante que descrição/ticker estejam definidos a partir do campo de busca
    if (!descricaoInput.value) descricaoInput.value = (searchInput.value || '').trim();
    if (!tickerInput.value) tickerInput.value = (searchInput.value || '').trim().toUpperCase();
});
</script>

<style>
/* Stock Search Autocomplete Styles */
.stock-search-wrapper {
    position: relative;
}

.stock-suggestions {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 300px;
    overflow-y: auto;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    z-index: 1000;
    margin-top: 4px;
}

.stock-suggestion-item {
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid #f5f5f5;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: background 0.2s;
}

.stock-suggestion-item:hover {
    background: #f8f9fa;
}

.stock-suggestion-item:last-child {
    border-bottom: none;
}

.stock-suggestion-item.no-results {
    color: #5f6368;
    cursor: default;
    justify-content: center;
}

.stock-suggestion-item.no-results:hover {
    background: white;
}

.stock-ticker {
    font-weight: 700;
    color: #1a73e8;
    font-size: 14px;
    min-width: 60px;
}

.stock-info {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stock-name {
    font-size: 13px;
    color: #202124;
}

.stock-price {
    font-size: 13px;
    font-weight: 600;
    color: #34a853;
}

.selected-stock {
    display: none;
    margin-top: 8px;
}

.selected-stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #e8f0fe;
    color: #1a73e8;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 14px;
}

.clear-selection {
    background: none;
    border: none;
    color: #1a73e8;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
    padding: 0 4px;
    margin-left: 4px;
}

.clear-selection:hover {
    color: #174ea6;
}

.form-help {
    display: block;
    margin-top: 4px;
    font-size: 12px;
    color: #5f6368;
}
</style>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
