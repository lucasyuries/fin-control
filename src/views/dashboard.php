<?php require_once __DIR__ . '/partials/header.php'; ?>

<!-- Navegação de Abas -->
<nav class="nav-tabs">
    <a class="nav-link active" href="<?php echo BASE_URL; ?>/dashboard">
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
    <a class="nav-link" href="<?php echo BASE_URL; ?>/investimentos">
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

<?php
// Cálculos para as métricas
$saldo_mes = $data['total_receitas'] - $data['total_despesas'];
$patrimonio_total = $data['patrimonio_total'] ?? 0;
$valor_investido = $data['total_ativos'] ?? 0;
$lucro_total = $patrimonio_total - $valor_investido;
$receitas_12m = $data['total_receitas_geral'] ?? 0;
?>

<!-- Cards de Métricas -->
<div class="metrics-grid">
    <!-- Patrimônio Total -->
    <div class="metric-card">
        <div class="metric-icon patrimonio">💰</div>
        <div class="metric-label">
            Patrimônio total
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg>
        </div>
        <div class="metric-value">R$ <?php echo number_format($patrimonio_total, 2, ',', '.'); ?></div>
        <span class="metric-variation <?php echo $lucro_total >= 0 ? 'positive' : 'negative'; ?>">
            <?php if ($lucro_total >= 0): ?>
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                    <path d="m7.247 11.14 4.796-5.481c.566-.647 1.766-.133 1.766.753v9.592a1 1 0 0 1-.753.753l-5.48-4.796a1 1 0 0 1 0-1.506z"/>
                </svg>
                +<?php echo $valor_investido > 0 ? number_format(($lucro_total / $valor_investido) * 100, 2) : '0,00'; ?>%
            <?php else: ?>
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                    <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
                </svg>
                <?php echo $valor_investido > 0 ? number_format(($lucro_total / $valor_investido) * 100, 2) : '0,00'; ?>%
            <?php endif; ?>
        </span>
        <div class="metric-subtitle">Valor investido: R$ <?php echo number_format($valor_investido, 2, ',', '.'); ?></div>
    </div>

    <!-- Receitas (12M) -->
    <div class="metric-card">
        <div class="metric-icon receitas">📈</div>
        <div class="metric-label">
            Receitas (Total)
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg>
        </div>
        <div class="metric-value">R$ <?php echo number_format($receitas_12m, 2, ',', '.'); ?></div>
        <span class="metric-variation positive">
            <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                <path d="m7.247 11.14 4.796-5.481c.566-.647 1.766-.133 1.766.753v9.592a1 1 0 0 1-.753.753l-5.48-4.796a1 1 0 0 1 0-1.506z"/>
            </svg>
            Este mês: R$ <?php echo number_format($data['total_receitas'], 2, ',', '.'); ?>
        </span>
        <div class="metric-subtitle">Total acumulado</div>
    </div>

    <!-- Despesas do Mês -->
    <div class="metric-card">
        <div class="metric-icon despesas">📉</div>
        <div class="metric-label">
            Despesas (Total)
        </div>
        <div class="metric-value">R$ <?php echo number_format($data['total_despesas_geral'], 2, ',', '.'); ?></div>
        <span class="metric-variation negative">
            <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
            </svg>
            Este mês: R$ <?php echo number_format($data['total_despesas'], 2, ',', '.'); ?>
        </span>
        <div class="metric-subtitle">Total acumulado</div>
    </div>

    <!-- Saldo do Mês -->
    <div class="metric-card">
        <div class="metric-icon saldo">💹</div>
        <div class="metric-label">
            Saldo do Mês
        </div>
        <div class="metric-value">R$ <?php echo number_format($saldo_mes, 2, ',', '.'); ?></div>
        <span class="metric-variation <?php echo $saldo_mes >= 0 ? 'positive' : 'negative'; ?>">
            <?php if ($saldo_mes >= 0): ?>
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                    <path d="m7.247 11.14 4.796-5.481c.566-.647 1.766-.133 1.766.753v9.592a1 1 0 0 1-.753.753l-5.48-4.796a1 1 0 0 1 0-1.506z"/>
                </svg>
                +<?php echo $data['total_receitas'] > 0 ? number_format(($saldo_mes / $data['total_receitas']) * 100, 2) : '0,00'; ?>%
            <?php else: ?>
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                    <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
                </svg>
                <?php echo $data['total_receitas'] > 0 ? number_format(($saldo_mes / $data['total_receitas']) * 100, 2) : '0,00'; ?>%
            <?php endif; ?>
        </span>
        <div class="metric-subtitle">Receitas - Despesas</div>
    </div>
</div>

<!-- Seção de Gráficos -->
<div class="charts-section">
    <!-- Evolução do Patrimônio -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Evolução Financeira (Últimos 12 Meses)</h3>
            <div class="chart-filter">
                <button class="filter-btn active">Receitas vs Despesas</button>
            </div>
        </div>
        <div style="position: relative; height: 280px; padding: 1rem;">
            <canvas id="patrimonioChart"></canvas>
        </div>
    </div>

    <!-- Distribuição por Categorias -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Top 10 Categorias</h3>
        </div>
        <div style="position: relative; height: 280px; padding: 1rem;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<!-- Tabela de Transações Recentes -->
<div class="transactions-section">
    <div class="section-header">
        <h3 class="section-title">Últimos Lançamentos (<?php echo count($data['recent_transactions']); ?>)</h3>
        <a href="<?php echo BASE_URL; ?>/lancamentos" class="btn btn-primary">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
            Adicionar Lançamento
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Categoria</th>
                    <th class="text-end">Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['recent_transactions'])): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-light); padding: 3rem;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                                <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="opacity: 0.2;">
                                    <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2z"/>
                                </svg>
                                <div>
                                    <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">Nenhum lançamento encontrado</p>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;">Comece adicionando receitas, despesas ou investimentos</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['recent_transactions'] as $transaction): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($transaction['data'])); ?></td>
                            <td>
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($transaction['descricao']); ?></div>
                            </td>
                            <td>
                                <span class="type-badge <?php echo $transaction['tipo']; ?>">
                                    <?php echo ucfirst($transaction['tipo']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($transaction['categoria_nome'] ?? 'Sem categoria'); ?></td>
                            <td class="text-end">
                                <span class="value-<?php echo $transaction['tipo']; ?>">
                                    <?php 
                                        $prefix = $transaction['tipo'] == 'receita' ? '+' : ($transaction['tipo'] == 'ativo' ? '' : '-');
                                        echo $prefix . ' R$ ' . number_format($transaction['valor'], 2, ',', '.'); 
                                    ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (!empty($data['recent_transactions'])): ?>
        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="<?php echo BASE_URL; ?>/lancamentos" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">
                Ver todos os lançamentos →
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
.type-badge {
    display: inline-block;
    padding: 0.375rem 0.875rem;
    border-radius: 16px;
    font-size: 0.8125rem;
    font-weight: 500;
}

.type-badge.receita {
    background: #e8f5e9;
    color: #2e7d32;
}

.type-badge.despesa {
    background: #ffebee;
    color: #c62828;
}

.type-badge.ativo {
    background: #e3f2fd;
    color: #1565c0;
}

.value-receita {
    color: #34a853;
    font-weight: 600;
}

.value-despesa {
    color: #ea4335;
    font-weight: 600;
}

.value-ativo {
    color: #1a73e8;
    font-weight: 600;
}
</style>

<script>
// Dados para os gráficos (carregados pelo dashboard-charts.js)
window.dashboardChartData = <?php echo json_encode($data['chart_data'] ?? []); ?>;
window.dashboardCategoryData = <?php echo json_encode($data['category_data'] ?? []); ?>;
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>