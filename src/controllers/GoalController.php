<?php
// Arquivo: src/controllers/GoalController.php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Goal.php';
require_once __DIR__ . '/../models/Transaction.php'; 
require_once __DIR__ . '/../core/Session.php'; // Novo CORE

class GoalController {
    private $goalModel;
    private $transactionModel; 

    public function __construct() {
        Session::requireLogin(); // Exige que o usuário esteja logado
        $this->goalModel = new Goal();
        $this->transactionModel = new Transaction();
    }

    public function index() {
        $usuario_id = $_SESSION['user_id'];
        $metas = $this->goalModel->findAllByUserId($usuario_id);

        // Busca o patrimônio total do usuário para o cálculo do progresso da meta
        $patrimonio_atual = $this->transactionModel->getPatrimonioTotal($usuario_id);

        // Calcula progresso e separa metas em andamento/concluídas
        $metas_em_andamento = [];
        $metas_concluidas = [];
        
        foreach ($metas as $meta) {
            // O cálculo da meta de patrimônio usa o patrimônio atual como 'atual'
            $atual = $patrimonio_atual; 

            // Cálculo do progresso
            $valor_objetivo = floatval($meta['valor_objetivo']);
            $aporte_mensal = floatval($meta['aporte_mensal']);

            $percentual = $valor_objetivo > 0 ? min(100, ($atual / $valor_objetivo) * 100) : 0;
            $falta = max(0, $valor_objetivo - $atual);
            
            // Simulação simples de estimativa de tempo (sem juros compostos)
            $meses_estimados = null;
            if ($falta > 0 && $aporte_mensal > 0) {
                // Simplificando o cálculo, sem considerar a variacao_anual no Controller
                $meses_estimados = ceil($falta / $aporte_mensal);
            }
            
            $meta['progresso'] = [
                'atual' => $atual,
                'percentual' => round($percentual, 1),
                'falta' => $falta
            ];
            $meta['meses_estimados'] = $meses_estimados; 

            if ($meta['status'] === 'concluida') {
                $metas_concluidas[] = $meta;
            } else {
                $metas_em_andamento[] = $meta;
            }
        }
        
        $data = [
            'metas_em_andamento' => $metas_em_andamento,
            'metas_concluidas' => $metas_concluidas,
            'patrimonio_atual' => $patrimonio_atual,
            'success' => Session::getFlash('success'),
            'error' => Session::getFlash('error')
        ];
        
        $pageTitle = 'Metas Financeiras';
        require_once __DIR__ . '/../views/metas.php';
    }

    // Função auxiliar para sanitizar valores monetários
    private function sanitizeMoneyValue($value) {
        $value = str_replace('.', '', $value); 
        $value = str_replace(',', '.', $value); 
        return floatval($value);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/metas');
            exit();
        }
        
        $usuario_id = $_SESSION['user_id'];
        
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) ?? '';
        $valor_objetivo = $this->sanitizeMoneyValue($_POST['valor_objetivo'] ?? '0');
        $aporte_mensal = $this->sanitizeMoneyValue($_POST['aporte_mensal'] ?? '0');
        $variacao_anual = $this->sanitizeMoneyValue($_POST['variacao_anual'] ?? '0');
        $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING) ?? 'patrimonio';
        
        if (empty($nome) || $valor_objetivo <= 0) {
            Session::setFlash('error', 'Nome da meta e Valor objetivo são obrigatórios.');
            header('Location: ' . BASE_URL . '/metas');
            exit();
        }

        if ($this->goalModel->create([
            'usuario_id' => $usuario_id,
            'nome' => $nome,
            'valor_objetivo' => $valor_objetivo,
            'aporte_mensal' => $aporte_mensal,
            'variacao_anual' => $variacao_anual,
            'tipo' => $tipo,
        ])) {
            Session::setFlash('success', 'Meta criada com sucesso!');
        } else {
            Session::setFlash('error', 'Erro ao criar meta.');
        }

        header('Location: ' . BASE_URL . '/metas');
        exit();
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/metas');
            exit();
        }

        $usuario_id = $_SESSION['user_id'];
        
        // Verifica se a meta existe e pertence ao usuário
        if (!$this->goalModel->findById($id, $usuario_id)) {
            Session::setFlash('error', 'Meta não encontrada.');
            header('Location: ' . BASE_URL . '/metas');
            exit();
        }

        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) ?? '';
        $valor_objetivo = $this->sanitizeMoneyValue($_POST['valor_objetivo'] ?? '0');
        $aporte_mensal = $this->sanitizeMoneyValue($_POST['aporte_mensal'] ?? '0');
        $variacao_anual = $this->sanitizeMoneyValue($_POST['variacao_anual'] ?? '0');

        if (empty($nome) || $valor_objetivo <= 0) {
            Session::setFlash('error', 'Nome da meta e Valor objetivo são obrigatórios.');
            header('Location: ' . BASE_URL . '/metas');
            exit();
        }
        
        if ($this->goalModel->update($id, [
            'nome' => $nome,
            'valor_objetivo' => $valor_objetivo,
            'aporte_mensal' => $aporte_mensal,
            'variacao_anual' => $variacao_anual,
        ], $usuario_id)) {
            Session::setFlash('success', 'Meta atualizada com sucesso!');
        } else {
            Session::setFlash('error', 'Erro ao atualizar meta.');
        }

        header('Location: ' . BASE_URL . '/metas');
        exit();
    }

    public function delete($id) {
        $usuario_id = $_SESSION['user_id'];
        
        // Verifica se a meta existe e pertence ao usuário
        if (!$this->goalModel->findById($id, $usuario_id)) {
            Session::setFlash('error', 'Meta não encontrada.');
            header('Location: ' . BASE_URL . '/metas');
            exit();
        }

        if ($this->goalModel->delete($id, $usuario_id)) {
            Session::setFlash('success', 'Meta excluída com sucesso!');
        } else {
            Session::setFlash('error', 'Erro ao excluir meta.');
        }

        header('Location: ' . BASE_URL . '/metas');
        exit();
    }
}