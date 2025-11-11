<?php
/**
 * StockService - Service for fetching Brazilian stock quotes
 * Uses Brapi.dev - Free Brazilian stock market API
 * Documentation: https://brapi.dev/docs
 */

class StockService {
    private $db;
    private $apiUrl = 'https://brapi.dev/api/quote/';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Search stocks by ticker or name
     * @param string $query Search term
     * @return array List of matching stocks
     */
    public function searchStocks($query) {
        $query = strtoupper(trim($query));
        
        // Search in local database first (faster)
        $this->db->query("
            SELECT ticker, nome as name, preco_atual as current_price, 
                   variacao_dia as change_percent, ultima_atualizacao as last_update 
            FROM stock_prices 
            WHERE ticker LIKE :query OR nome LIKE :query
            ORDER BY ticker ASC
            LIMIT 20
        ");
        $this->db->bind(':query', "%{$query}%");
        
        return $this->db->resultSet();
    }
    
    /**
     * Get current price for a specific ticker
     * @param string $ticker Stock ticker (e.g., PETR4)
     * @return array|false Stock data or false if not found
     */
    public function getStockPrice($ticker) {
        $ticker = strtoupper(trim($ticker));
        
        // Check if we have recent data (less than 1 hour old)
        $this->db->query("
            SELECT ticker, nome as name, preco_atual as current_price, 
                   variacao_dia as change_percent, ultima_atualizacao as last_update
            FROM stock_prices 
            WHERE ticker = :ticker 
            AND ultima_atualizacao > DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ");
        $this->db->bind(':ticker', $ticker);
        $cached = $this->db->single();
        
        if ($cached) {
            return $cached;
        }
        
        // Fetch fresh data from API
        return $this->fetchFromAPI($ticker);
    }
    
    /**
     * Fetch stock data from Brapi API
     * @param string $ticker Stock ticker
     * @return array|false Stock data or false if error
     */
    private function fetchFromAPI($ticker) {
        $url = $this->apiUrl . urlencode($ticker);
        
        // Use cURL for API request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For localhost testing
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$response) {
            return false;
        }
        
        $data = json_decode($response, true);
        
        if (!isset($data['results']) || empty($data['results'])) {
            return false;
        }
        
        $stock = $data['results'][0];
        
        // Update database
        $this->updateStockPrice(
            $ticker,
            $stock['longName'] ?? $stock['shortName'] ?? $ticker,
            $stock['regularMarketPrice'] ?? 0,
            $stock['regularMarketChangePercent'] ?? 0
        );
        
        return [
            'ticker' => $ticker,
            'name' => $stock['longName'] ?? $stock['shortName'] ?? $ticker,
            'current_price' => $stock['regularMarketPrice'] ?? 0,
            'change_percent' => $stock['regularMarketChangePercent'] ?? 0,
            'last_update' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Update or insert stock price in database
     */
    private function updateStockPrice($ticker, $name, $price, $changePercent) {
        $this->db->query("
            INSERT INTO stock_prices (ticker, nome, preco_atual, variacao_dia, ultima_atualizacao)
            VALUES (:ticker, :name, :price, :change, NOW())
            ON DUPLICATE KEY UPDATE 
                nome = :name,
                preco_atual = :price,
                variacao_dia = :change,
                ultima_atualizacao = NOW()
        ");
        
        $this->db->bind(':ticker', $ticker);
        $this->db->bind(':name', $name);
        $this->db->bind(':price', $price);
        $this->db->bind(':change', $changePercent);
        
        return $this->db->execute();
    }
    
    /**
     * Get portfolio summary with current prices
     * @param int $userId User ID
     * @return array Portfolio data with current values
     */
    public function getPortfolioSummary($userId) {
        // Get all user's stocks
        $this->db->query("
            SELECT 
                ticker,
                descricao as description,
                SUM(quantidade) as total_quantity,
                SUM(valor) as total_invested,
                SUM(valor) / NULLIF(SUM(quantidade), 0) as average_price,
                MIN(data) as first_purchase
            FROM transactions
            WHERE usuario_id = :user_id 
            AND tipo = 'ativo'
            AND ticker IS NOT NULL
            GROUP BY ticker, descricao
            HAVING total_quantity > 0
        ");
        $this->db->bind(':user_id', $userId);
        $stocks = $this->db->resultSet();
        
        $portfolio = [];
        $totalInvested = 0;
        $totalCurrent = 0;
        
        foreach ($stocks as $stock) {
            // Get current price
            $currentData = $this->getStockPrice($stock['ticker']);
            $currentPrice = $currentData ? $currentData['current_price'] : $stock['average_price'];
            
            $currentValue = $stock['total_quantity'] * $currentPrice;
            $profitLoss = $currentValue - $stock['total_invested'];
            $profitLossPercent = ($stock['total_invested'] > 0) 
                ? (($profitLoss / $stock['total_invested']) * 100) 
                : 0;
            
            $portfolio[] = [
                'ticker' => $stock['ticker'],
                'description' => $stock['description'],
                'quantity' => $stock['total_quantity'],
                'average_price' => $stock['average_price'],
                'current_price' => $currentPrice,
                'invested' => $stock['total_invested'],
                'current_value' => $currentValue,
                'profit_loss' => $profitLoss,
                'profit_loss_percent' => $profitLossPercent,
                'first_purchase' => $stock['first_purchase']
            ];
            
            $totalInvested += $stock['total_invested'];
            $totalCurrent += $currentValue;
        }
        
        return [
            'stocks' => $portfolio,
            'total_invested' => $totalInvested,
            'total_current' => $totalCurrent,
            'total_profit_loss' => $totalCurrent - $totalInvested,
            'total_profit_loss_percent' => ($totalInvested > 0) 
                ? ((($totalCurrent - $totalInvested) / $totalInvested) * 100) 
                : 0
        ];
    }
}
