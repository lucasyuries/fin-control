<?php
/**
 * Teste direto do banco de dados
 */
require_once __DIR__ . '/../src/core/Database.php';

header('Content-Type: text/html; charset=utf-8');

try {
    $db = new Database();
    
    echo "<h3>✅ Conexão com banco OK!</h3>";
    
    // Testar se tabela existe
    $db->query("SHOW TABLES LIKE 'stock_prices'");
    $table = $db->single();
    
    if ($table) {
        echo "<p>✅ Tabela stock_prices existe!</p>";
        
        // Contar registros
        $db->query("SELECT COUNT(*) as total FROM stock_prices");
        $count = $db->single();
        echo "<p>📊 Total de ações: <strong>{$count['total']}</strong></p>";
        
        // Buscar todas
        $db->query("SELECT ticker, nome, preco_atual FROM stock_prices LIMIT 10");
        $stocks = $db->resultSet();
        
        echo "<h4>Primeiras 10 ações:</h4>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Ticker</th><th>Nome</th><th>Preço</th></tr>";
        
        foreach ($stocks as $stock) {
            echo "<tr>";
            echo "<td><strong>{$stock['ticker']}</strong></td>";
            echo "<td>{$stock['nome']}</td>";
            echo "<td>R$ " . number_format($stock['preco_atual'], 2, ',', '.') . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
    } else {
        echo "<p>❌ Tabela stock_prices NÃO existe!</p>";
        echo "<p>Execute o arquivo: database/instalacao_completa.sql</p>";
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Erro:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
