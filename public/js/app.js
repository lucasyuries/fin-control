// Aguardar o Chart.js carregar
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js não foi carregado!');
        return;
    }

    // Dados vêm de variáveis globais definidas no PHP
    const chartData = window.dashboardChartData || [];
    const categoryData = window.dashboardCategoryData || [];

    console.log('Chart Data:', chartData);
    console.log('Category Data:', categoryData);

    // Configuração de cores
    const colors = {
        receita: '#34a853',
        despesa: '#ea4335',
        ativo: '#1a73e8',
        gradient: {
            receita: 'rgba(52, 168, 83, 0.1)',
            despesa: 'rgba(234, 67, 53, 0.1)'
        }
    };

    // GRÁFICO 1: Evolução Mensal (Linha)
    const patrimonioCanvas = document.getElementById('patrimonioChart');
    if (patrimonioCanvas && chartData && chartData.length > 0) {
        const ctx = patrimonioCanvas.getContext('2d');
        
        // Agrupar dados por mês
        const monthlyData = {};
        
        chartData.forEach(item => {
            const mes = item.mes;
            if (!monthlyData[mes]) {
                monthlyData[mes] = {
                    mes: mes,
                    label: item.label || mes,
                    receitas: 0,
                    despesas: 0
                };
            }
            
            if (item.tipo === 'receita') {
                monthlyData[mes].receitas = parseFloat(item.total || 0);
            } else if (item.tipo === 'despesa') {
                monthlyData[mes].despesas = parseFloat(item.total || 0);
            }
        });
        
        // Converter para arrays
        const sortedMonths = Object.keys(monthlyData).sort();
        const labels = sortedMonths.map(mes => monthlyData[mes].label);
        const receitasData = sortedMonths.map(mes => monthlyData[mes].receitas);
        const despesasData = sortedMonths.map(mes => monthlyData[mes].despesas);
        
        console.log('Labels:', labels);
        console.log('Receitas:', receitasData);
        console.log('Despesas:', despesasData);
        
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
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Despesas',
                        data: despesasData,
                        borderColor: colors.despesa,
                        backgroundColor: colors.gradient.despesa,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 12,
                                weight: 500
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13, weight: 600 },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                            },
                            font: { size: 11 }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    } else if (patrimonioCanvas) {
        console.log('Nenhum dado para o gráfico de evolução');
        patrimonioCanvas.parentElement.innerHTML = 
            '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #999;">' +
            '<p>Adicione lançamentos para visualizar o gráfico</p></div>';
    }

    // GRÁFICO 2: Categorias (Pizza)
    const categoryCanvas = document.getElementById('categoryChart');
    if (categoryCanvas && categoryData && categoryData.length > 0) {
        const ctx2 = categoryCanvas.getContext('2d');
        
        // Processar dados - agrupar por categoria (somar receitas e despesas)
        const categoryMap = new Map();
        
        categoryData.forEach(item => {
            const categoria = item.categoria || 'Sem categoria';
            const total = parseFloat(item.total || 0);
            
            if (categoryMap.has(categoria)) {
                categoryMap.set(categoria, categoryMap.get(categoria) + total);
            } else {
                categoryMap.set(categoria, total);
            }
        });
        
        // Ordenar por valor e pegar top 10
        const sortedCategories = Array.from(categoryMap.entries())
            .sort((a, b) => b[1] - a[1])
            .slice(0, 10);
        
        const categorias = sortedCategories.map(c => c[0]);
        const valores = sortedCategories.map(c => c[1]);
        
        console.log('Categorias:', categorias);
        console.log('Valores:', valores);
        
        // Gerar cores dinâmicas
        const chartColors = [
            '#4285f4', '#34a853', '#fbbc04', '#ea4335', '#9b59b6',
            '#3498db', '#e74c3c', '#f39c12', '#1abc9c', '#95a5a6'
        ];
        
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: categorias,
                datasets: [{
                    data: valores,
                    backgroundColor: chartColors.slice(0, categorias.length),
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 12,
                            font: {
                                size: 11
                            },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        
                                        return {
                                            text: `${label} (${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13, weight: 600 },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                
                                return [
                                    label,
                                    'R$ ' + value.toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }) + ` (${percentage}%)`
                                ];
                            }
                        }
                    }
                }
            }
        });
    } else if (categoryCanvas) {
        console.log('Nenhum dado para o gráfico de categorias');
        categoryCanvas.parentElement.innerHTML = 
            '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #999;">' +
            '<p>Adicione lançamentos para visualizar categorias</p></div>';
    }
});
