<?php
/**
 * API Endpoint for stock search and quotes
 * Returns JSON data for AJAX requests
 */

require_once __DIR__ . '/../../src/core/Database.php';
require_once __DIR__ . '/../../src/core/StockService.php';

header('Content-Type: application/json');

// Check if user is authenticated
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';
$stockService = new StockService();

switch ($action) {
    case 'search':
        // Search stocks by query
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 1) {
            echo json_encode([]);
            exit;
        }
        
        $results = $stockService->searchStocks($query);
        echo json_encode($results);
        break;
        
    case 'quote':
        // Get quote for specific ticker
        $ticker = $_GET['ticker'] ?? '';
        
        if (empty($ticker)) {
            echo json_encode(['error' => 'Ticker required']);
            exit;
        }
        
        $quote = $stockService->getStockPrice($ticker);
        
        if ($quote) {
            echo json_encode($quote);
        } else {
            echo json_encode(['error' => 'Stock not found']);
        }
        break;
        
    case 'portfolio':
        // Get user's portfolio with current prices
        $portfolio = $stockService->getPortfolioSummary($_SESSION['user_id']);
        echo json_encode($portfolio);
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
