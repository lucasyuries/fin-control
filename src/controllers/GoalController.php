<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Goal.php';
require_once __DIR__ . '/../models/Transaction.php';

class GoalController {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    public function index() {
        $goalModel = new Goal();
        $transactionModel = new Transaction();
        $usuario_id = $_SESSION['user_id'];
        
        // Buscar patrimônio atual
        $patrimonioAtual = $transactionModel->getPatrimonioTotal($usuario_id);
        
        // Buscar metas em andamento e concluídas
        $metasEmAndamento = $goalModel->findAllByUserId($usuario_id, 'em_andamento');
        $metasConcluidas = $goalModel->findAllByUserId($usuario_id, 'concluida');
        
        // Calcular progresso de cada meta
        foreach ($metasEmAndamento as &$meta) {
            $meta['progresso'] = $goalModel->calculateProgress($meta, $patrimonioAtual);
            $meta['meses_estimados'] = $goalModel->calculateEstimatedTime($meta, $patrimonioAtual);
        }
        
        foreach ($metasConcluidas as &$meta) {
            $meta['progresso'] = $goalModel->calculateProgress($meta, $patrimonioAtual);
        }
        
        $data = [
            'metas_em_andamento' => $metasEmAndamento,
            'metas_concluidas' => $metasConcluidas,
            'patrimonio_atual' => $patrimonioAtual
        ];
        
        $pageTitle = 'Metas';
        require_once __DIR__ . '/../views/metas.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $goalModel = new Goal();
            $usuario_id = $_SESSION['user_id'];
            
            $data = [
                'usuario_id' => $usuario_id,
                'tipo' => $_POST['tipo'] ?? 'patrimonio',
                'nome' => $_POST['nome'],
                'valor_objetivo' => str_replace(['.', ','], ['', '.'], $_POST['valor_objetivo']),
                'aporte_mensal' => str_replace(['.', ','], ['', '.'], $_POST['aporte_mensal'] ?? 0),
                'variacao_anual' => str_replace(',', '.', $_POST['variacao_anual'] ?? 0),
                'status' => 'em_andamento',
                'data_inicio' => date('Y-m-d')
            ];
            
            if ($goalModel->create($data)) {
                $_SESSION['success'] = 'Meta criada com sucesso!';
            } else {
                $_SESSION['error'] = 'Erro ao criar meta.';
            }
            
            header('Location: ' . BASE_URL . '/metas');
            exit();
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $goalModel = new Goal();
            $transactionModel = new Transaction();
            $usuario_id = $_SESSION['user_id'];
            
            // Verificar se a meta pertence ao usuário
            $meta = $goalModel->findById($id, $usuario_id);
            if (!$meta) {
                $_SESSION['error'] = 'Meta não encontrada.';
                header('Location: ' . BASE_URL . '/metas');
                exit();
            }
            
            $data = [
                'usuario_id' => $usuario_id,
                'nome' => $_POST['nome'],
                'valor_objetivo' => str_replace(['.', ','], ['', '.'], $_POST['valor_objetivo']),
                'aporte_mensal' => str_replace(['.', ','], ['', '.'], $_POST['aporte_mensal'] ?? 0),
                'variacao_anual' => str_replace(',', '.', $_POST['variacao_anual'] ?? 0),
                'status' => $_POST['status'] ?? $meta['status'],
                'data_conclusao' => null
            ];
            
            // Se mudou para concluída, registrar data
            if ($data['status'] === 'concluida' && $meta['status'] !== 'concluida') {
                $data['data_conclusao'] = date('Y-m-d');
            }
            
            if ($goalModel->update($id, $data)) {
                $_SESSION['success'] = 'Meta atualizada com sucesso!';
            } else {
                $_SESSION['error'] = 'Erro ao atualizar meta.';
            }
            
            header('Location: ' . BASE_URL . '/metas');
            exit();
        }
    }

    public function delete($id) {
        $goalModel = new Goal();
        $usuario_id = $_SESSION['user_id'];
        
        if ($goalModel->delete($id, $usuario_id)) {
            $_SESSION['success'] = 'Meta excluída com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir meta.';
        }
        
        header('Location: ' . BASE_URL . '/metas');
        exit();
    }
}
