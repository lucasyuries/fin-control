// Arquivo: public/js/app.js

// Variáveis globais definidas no PHP e anexadas ao objeto window
// Ex: window.BASE_URL, window.allTransactions, window.allCategories

// =========================================================
// === CHART.JS E DASHBOARD LOGIC (ROBUSTO) ===
// =========================================================
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js não foi carregado!');
    }

    // Dados vêm de variáveis globais definidas no PHP
    const chartData = window.dashboardChartData || [];
    const categoryData = window.dashboardCategoryData || [];

    const colors = {
        receita: '#34a853',
        despesa: '#ea4335',
        gradient: {
            receita: 'rgba(52, 168, 83, 0.1)',
            despesa: 'rgba(234, 67, 53, 0.1)'
        }
    };

    // GRÁFICO 1: Evolução Mensal (Linha)
    const patrimonioCanvas = document.getElementById('patrimonioChart');
    if (patrimonioCanvas && chartData) { 
        const ctx = patrimonioCanvas.getContext('2d');
        
        // --- CÁLCULO ROBUSTO: GARANTIR OS ÚLTIMOS 12 MESES CALENDÁRIOS ---
        const monthlyDataMap = new Map();
        const today = new Date();
        // Formato para label (Ex: "Nov/25")
        const monthFormatter = new Intl.DateTimeFormat('pt-BR', { month: 'short', year: '2-digit' });

        // 1. Criar o mapa com os últimos 12 meses, garantindo labels e zeros
        for (let i = 11; i >= 0; i--) {
            const date = new Date(today.getFullYear(), today.getMonth() - i, 1);
            const mesKey = date.toISOString().slice(0, 7); // Ex: "2025-01"
            // Garante que o label seja formatado corretamente e sem ponto final
            const label = monthFormatter.format(date).replace('.', ''); 
            
            monthlyDataMap.set(mesKey, {
                label: label,
                receitas: 0,
                despesas: 0
            });
        }

        // 2. Popular o mapa com dados reais do PHP
        chartData.forEach(item => {
            const mesKey = item.mes;
            if (monthlyDataMap.has(mesKey)) {
                const dataEntry = monthlyDataMap.get(mesKey);
                if (item.tipo === 'receita') {
                    dataEntry.receitas = parseFloat(item.total || 0);
                } else if (item.tipo === 'despesa') {
                    dataEntry.despesas = parseFloat(item.total || 0);
                }
            }
        });

        // 3. Converter para arrays ordenados para Chart.js
        const sortedData = Array.from(monthlyDataMap.entries())
            .sort((a, b) => a[0].localeCompare(b[0]))
            .map(entry => entry[1]); // Pega apenas os objetos de dados
            
        const labels = sortedData.map(data => data.label);
        const receitasData = sortedData.map(data => data.receitas);
        const despesasData = sortedData.map(data => data.despesas);
        
        
        if (sortedData.length > 0) { 
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Receitas',
                            data: receitasData,
                            borderColor: colors.receita,
                            backgroundColor: colors.gradient.receita,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4, pointHoverRadius: 6, pointBackgroundColor: '#fff', pointBorderWidth: 2
                        },
                        {
                            label: 'Despesas',
                            data: despesasData,
                            borderColor: colors.despesa,
                            backgroundColor: colors.gradient.despesa,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4, pointHoverRadius: 6, pointBackgroundColor: '#fff', pointBorderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, interaction: { intersect: false, mode: 'index' },
                    plugins: {
                        legend: { display: true, position: 'top', labels: { usePointStyle: true, padding: 15, font: { size: 12, weight: 500 } } },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)', padding: 12, titleFont: { size: 13, weight: 600 }, bodyFont: { size: 12 },
                            callbacks: {
                                label: (context) => {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    label += 'R$ ' + context.parsed.y.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: (value) => 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }), font: { size: 11 } }, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                        x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                    }
                }
            });
        } else if (patrimonioCanvas) {
            patrimonioCanvas.parentElement.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #999;"><p>Adicione lançamentos para visualizar o gráfico</p></div>';
        }
    } else if (patrimonioCanvas) {
        patrimonioCanvas.parentElement.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #999;"><p>Adicione lançamentos para visualizar o gráfico</p></div>';
    }


    // GRÁFICO 2: Categorias (Pizza)
    const categoryCanvas = document.getElementById('categoryChart');
    if (categoryCanvas && categoryData && categoryData.length > 0) {
        const ctx2 = categoryCanvas.getContext('2d');
        const categoryMap = new Map();
        
        categoryData.forEach(item => {
            const categoria = item.categoria || 'Sem categoria';
            const total = parseFloat(item.total || 0);
            
            // FILTRO DE GASTRS (DESPESAS)
            if (item.tipo === 'despesa') {
                categoryMap.set(categoria, (categoryMap.get(categoria) || 0) + total);
            }
        });

        if (categoryMap.size > 0) {
            const sortedCategories = Array.from(categoryMap.entries())
                .sort((a, b) => b[1] - a[1])
                .slice(0, 10);
            
            const categorias = sortedCategories.map(c => c[0]);
            const valores = sortedCategories.map(c => c[1]);
            
            const chartColors = ['#4285f4', '#34a853', '#fbbc04', '#ea4335', '#9b59b6', '#3498db', '#e74c3c', '#f39c12', '#1abc9c', '#95a5a6'];
            
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: categorias,
                    datasets: [{ data: valores, backgroundColor: chartColors.slice(0, categorias.length), borderWidth: 2, borderColor: '#fff', hoverOffset: 8 }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true, position: 'right',
                            labels: {
                                usePointStyle: true, padding: 12, font: { size: 11 },
                                generateLabels: (chart) => {
                                    const data = chart.data;
                                    if (!data.labels || !data.datasets.length) return [];
                                    const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    return data.labels.map((label, i) => ({
                                        text: `${label} (${((data.datasets[0].data[i] / total) * 100).toFixed(1)}%)`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        hidden: false,
                                        index: i
                                    }));
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)', padding: 12, titleFont: { size: 13, weight: 600 }, bodyFont: { size: 12 },
                            callbacks: {
                                label: (context) => {
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return [context.label || '', 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ` (${percentage}%)`];
                                }
                            }
                        }
                    }
                }
            });
        } else if (categoryCanvas) {
             categoryCanvas.parentElement.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #999;"><p>Adicione despesas para visualizar categorias</p></div>';
        }
    } else if (categoryCanvas) {
        categoryCanvas.parentElement.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #999;"><p>Adicione lançamentos para visualizar categorias</p></div>';
    }
});


// =========================================================
// === VIEW LANCAMENTOS JS LOGIC ===
// =========================================================

// Acesso às variáveis globais (definidas no lancamentos.php)
// window.allTransactions
// window.allCategories

function openModal() {
    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay) modalOverlay.classList.add('active');
    updateCategories('tipo', 'categoria_id'); 
}

function closeModal() {
    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay) modalOverlay.classList.remove('active');
    const form = document.querySelector('#modalOverlay .modal-form');
    if (form) form.reset();
}

/**
 * Atualiza o campo de seleção de categoria baseado no tipo de transação.
 */
function updateCategories(selectTipoId, selectCategoriaId, selectedCategoryId = null) {
    const tipoSelect = document.getElementById(selectTipoId);
    if (!tipoSelect) return;
    const tipo = tipoSelect.value;
    const selectCategoria = document.getElementById(selectCategoriaId);
    
    // Limpa opções
    selectCategoria.innerHTML = '<option value="">Selecionar categoria</option>';

    if (tipo && window.allCategories && window.allCategories.length) {
        window.allCategories.forEach(categoria => {
            if (categoria.tipo === tipo) {
                const opt = document.createElement('option');
                opt.value = categoria.id;
                opt.textContent = categoria.nome;
                if (categoria.id == selectedCategoryId) {
                    opt.selected = true;
                }
                selectCategoria.appendChild(opt);
            }
        });
    }
}


function openDeleteModal(id) {
    const deleteModalOverlay = document.getElementById('deleteModalOverlay');
    const deleteIdInput = document.getElementById('deleteId');
    if (deleteModalOverlay) deleteModalOverlay.classList.add('active');
    if (deleteIdInput) deleteIdInput.value = id;
}

function closeDeleteModal() {
    const deleteModalOverlay = document.getElementById('deleteModalOverlay');
    const deleteForm = document.getElementById('deleteForm');
    if (deleteModalOverlay) deleteModalOverlay.classList.remove('active');
    if (deleteForm) deleteForm.reset();
}


function openCompensarModal(id, tipo) {
    const compensarModalOverlay = document.getElementById('compensarModalOverlay');
    const compensarIdInput = document.getElementById('compensarId');
    const select = document.getElementById('compensadaPorSelect');

    if (compensarModalOverlay) compensarModalOverlay.classList.add('active');
    if (compensarIdInput) compensarIdInput.value = id;
    
    select.innerHTML = '<option value="">Selecione...</option>';
    const oppositeType = tipo === 'receita' ? 'despesa' : 'receita';
    
    window.allTransactions.forEach(function(tx) {
        if (tx.tipo === oppositeType && (tx.compensada_por === null || tx.compensada_por == id) && tx.id != id) {
            const opt = document.createElement('option');
            opt.value = tx.id;
            opt.textContent = `${tx.descricao} (${tx.tipo}, R$ ${parseFloat(tx.valor).toLocaleString('pt-BR', {minimumFractionDigits:2, maximumFractionDigits:2})}, ${tx.data})`;
            select.appendChild(opt);
        }
    });
}
function closeCompensarModal() {
    const compensarModalOverlay = document.getElementById('compensarModalOverlay');
    const compensarForm = document.getElementById('compensarForm');
    if (compensarModalOverlay) compensarModalOverlay.classList.remove('active');
    if (compensarForm) compensarForm.reset();
}


function openEditModal(transacao) {
    if (typeof transacao === 'string') transacao = JSON.parse(transacao);

    const editModalOverlay = document.getElementById('editModalOverlay');
    if (editModalOverlay) editModalOverlay.classList.add('active');
    
    document.getElementById('editId').value = transacao.id;
    document.getElementById('editDescricao').value = transacao.descricao;
    document.getElementById('editValor').value = parseFloat(transacao.valor).toFixed(2); 
    document.getElementById('editData').value = transacao.data;
    document.getElementById('editTipo').value = transacao.tipo;

    // Atualiza as categorias para o tipo selecionado e pré-seleciona a categoria atual
    updateCategories('editTipo', 'editCategoriaId', transacao.categoria_id);
}

function closeEditModal() {
    const editModalOverlay = document.getElementById('editModalOverlay');
    const editForm = document.getElementById('editForm');
    if (editModalOverlay) editModalOverlay.classList.remove('active');
    if (editForm) editForm.reset();
}

// Handler para o onchange do select de tipo na edição
function updateEditCategories() {
    updateCategories('editTipo', 'editCategoriaId');
}

// =========================================================
// === VIEW METAS JS LOGIC ===
// =========================================================

let editingGoalId = null;

function openGoalModal(mode, goalData = null) {
	const modal = document.getElementById('goalModal');
	const form = document.getElementById('goalForm');
	const modalTitle = document.getElementById('modalTitle');
	const submitBtn = document.getElementById('submitBtn');
	const deleteBtn = document.getElementById('deleteBtn');

    // Resetar campos
    const nomeInput = document.getElementById('nome');
    const valorObjetivoInput = document.getElementById('valor_objetivo');
    const aporteMensalInput = document.getElementById('aporte_mensal');
    const variacaoAnualInput = document.getElementById('variacao_anual');
    
    if (nomeInput) nomeInput.value = '';
    if (valorObjetivoInput) valorObjetivoInput.value = '';
    if (aporteMensalInput) aporteMensalInput.value = '';
    if (variacaoAnualInput) variacaoAnualInput.value = '';

	if (mode === 'create') {
		if (modalTitle) modalTitle.textContent = 'Criar Meta';
		if (submitBtn) submitBtn.textContent = 'Criar meta';
		if (form) form.action = window.BASE_URL + '/metas/create';
		if (deleteBtn) deleteBtn.style.display = 'none';
		editingGoalId = null;
	} else {
		if (modalTitle) modalTitle.textContent = 'Editar Meta';
		if (submitBtn) submitBtn.textContent = 'Salvar alterações';
		if (form) form.action = window.BASE_URL + '/metas/update/' + goalData.id;
		if (deleteBtn) deleteBtn.style.display = 'block';
		editingGoalId = goalData.id;
		
        // Preencher dados (garantindo o formato R$,##)
        if (nomeInput) nomeInput.value = goalData.nome;
        if (valorObjetivoInput) valorObjetivoInput.value = parseFloat(goalData.valor_objetivo).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        if (aporteMensalInput) aporteMensalInput.value = parseFloat(goalData.aporte_mensal).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        if (variacaoAnualInput) variacaoAnualInput.value = parseFloat(goalData.variacao_anual).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
	}
	if (modal) modal.classList.add('active');
}

function closeGoalModal() {
	const modal = document.getElementById('goalModal');
    if (modal) modal.classList.remove('active');
}

function deleteGoal() {
	if (!editingGoalId) return;
	if (confirm('Tem certeza que deseja excluir esta meta?')) {
		window.location.href = window.BASE_URL + '/metas/delete/' + editingGoalId;
	}
}

// Adiciona listener para fechar o modal ao clicar fora
document.addEventListener('click', function(e) {
    const goalModal = document.getElementById('goalModal');
    if (goalModal && e.target === goalModal) {
        closeGoalModal();
    }
});

// Padronização do input money/percent (para lidar com vírgulas)
document.querySelectorAll('.money-input').forEach(input => {
	input.addEventListener('input', function(e) {
		let value = e.target.value.replace(/[^\d,]/g, ''); 
        if (value.length > 2 && value.endsWith(',')) {
            value = value.slice(0, -1);
        }
        let parts = value.split(',');
        if (parts.length > 2) {
            value = parts[0] + ',' + parts.slice(1).join('');
        }
		e.target.value = value;
	});
});
document.querySelectorAll('.percent-input').forEach(input => {
	input.addEventListener('input', function(e) {
		let value = e.target.value.replace(/[^\d,]/g, '');
		e.target.value = value;
	});
});


// =========================================================
// === AUTH & PROFILE PASSWORD VALIDATION LOGIC ===
// =========================================================

function setupPasswordValidation(passwordInputId, rulesConfig) {
    const passwordInput = document.getElementById(passwordInputId);
    if (!passwordInput) return;

    const rules = {};
    for (const key in rulesConfig) {
        rules[key] = document.getElementById(rulesConfig[key]);
    }

    passwordInput.addEventListener('input', () => {
        const senha = passwordInput.value;
        
        function updateRule(element, isValid) {
            if (!element) return;
            // Estilos definidos no CSS global
            const successColor = getComputedStyle(document.documentElement).getPropertyValue('--success-color').trim();
            const textSecondaryColor = getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim();

            if (isValid) {
                element.style.color = successColor;
                element.style.textDecoration = 'line-through';
            } else {
                element.style.color = textSecondaryColor;
                element.style.textDecoration = 'none';
            }
        }

        updateRule(rules.length, senha.length >= 8);
        updateRule(rules.uppercase, /[A-Z]/.test(senha));
        updateRule(rules.lowercase, /[a-z]/.test(senha));
        updateRule(rules.special, /[^A-Za-z0-9]/.test(senha));
    });
}

// Configurações para as views de autenticação e perfil
setupPasswordValidation('senha', { // register.php
    length: 'rule-length', uppercase: 'rule-uppercase', lowercase: 'rule-lowercase', special: 'rule-special'
});

setupPasswordValidation('nova_senha', { // profile.php e reset-password.php
    length: 'rule-length', uppercase: 'rule-uppercase', lowercase: 'rule-lowercase', special: 'rule-special'
});

// File: public/js/app.js (Rewriting data preparation for GRÁFICO 1)
// ... (rest of the file) ...
// GRÁFICO 1: Evolução Mensal (Linha)
const patrimonioCanvas = document.getElementById('patrimonioChart');
if (patrimonioCanvas && chartData) { // Note: Removed chartData.length > 0 check here
    const ctx = patrimonioCanvas.getContext('2d');
    
    // --- NOVO CÁLCULO: GARANTIR OS ÚLTIMOS 12 MESES ---
    const monthlyDataMap = new Map();
    const today = new Date();
    const monthFormatter = new Intl.DateTimeFormat('pt-BR', { month: 'short', year: '2-digit' });

    // 1. Criar o mapa com os últimos 12 meses, garantindo labels e zeros
    for (let i = 11; i >= 0; i--) {
        const date = new Date(today.getFullYear(), today.getMonth() - i, 1);
        const mesKey = date.toISOString().slice(0, 7); // Ex: "2025-01"
        const label = monthFormatter.format(date).replace('.', ''); // Ex: "Jan/25"
        
        monthlyDataMap.set(mesKey, {
            label: label,
            receitas: 0,
            despesas: 0
        });
    }

    // 2. Popular o mapa com dados reais do PHP
    chartData.forEach(item => {
        const mesKey = item.mes;
        if (monthlyDataMap.has(mesKey)) {
            const dataEntry = monthlyDataMap.get(mesKey);
            if (item.tipo === 'receita') {
                dataEntry.receitas = parseFloat(item.total || 0);
            } else if (item.tipo === 'despesa') {
                dataEntry.despesas = parseFloat(item.total || 0);
            }
        }
    });

    // 3. Converter para arrays ordenados
    const sortedData = Array.from(monthlyDataMap.entries())
        .sort((a, b) => a[0].localeCompare(b[0]))
        .map(entry => entry[1]); // Pega apenas os objetos de dados
        
    const labels = sortedData.map(data => data.label);
    const receitasData = sortedData.map(data => data.receitas);
    const despesasData = sortedData.map(data => data.despesas);
    
    // ... (rest of chart rendering logic using labels, receitasData, despesasData) ...
}
// ... (rest of the file, including Pie Chart logic, remains the same) ...