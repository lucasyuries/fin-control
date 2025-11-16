<?php
require_once __DIR__ . '/../models/Goal.php';

class GoalController {
    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        $goalModel = new Goal();
        $usuario_id = $_SESSION['user_id'];
        $metas = $goalModel->findAllByUserId($usuario_id);

        // Calcula progresso e separa metas em andamento/concluídas
        $metas_em_andamento = [];
        $metas_concluidas = [];
        foreach ($metas as $meta) {
            $atual = 0.0; // Aqui você pode buscar o patrimônio atual do usuário, se desejar
            $percentual = $meta['valor_objetivo'] > 0 ? min(100, ($atual / $meta['valor_objetivo']) * 100) : 0;
            $falta = max(0, $meta['valor_objetivo'] - $atual);
            $meses_estimados = ($meta['aporte_mensal'] > 0) ? ceil($falta / $meta['aporte_mensal']) : null;
            $meta['progresso'] = [
                'atual' => $atual,
                'percentual' => $percentual,
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
            'metas_concluidas' => $metas_concluidas
        ];
        require __DIR__ . '/../views/metas.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/metas');
            exit();
        }
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        $usuario_id = $_SESSION['user_id'];
        $nome = $_POST['nome'] ?? '';
        $valor_objetivo = str_replace([',','.'], ['','.'], $_POST['valor_objetivo'] ?? '0');
        $valor_objetivo = floatval(str_replace(',', '.', $valor_objetivo));
        $aporte_mensal = str_replace([',','.'], ['','.'], $_POST['aporte_mensal'] ?? '0');
        $aporte_mensal = floatval(str_replace(',', '.', $aporte_mensal));
        $variacao_anual = str_replace([',','.'], ['','.'], $_POST['variacao_anual'] ?? '0');
        $variacao_anual = floatval(str_replace(',', '.', $variacao_anual));
        $tipo = $_POST['tipo'] ?? 'patrimonio';
        $goalModel = new Goal();
        $goalModel->create([
            'usuario_id' => $usuario_id,
            'nome' => $nome,
            'valor_objetivo' => $valor_objetivo,
            'aporte_mensal' => $aporte_mensal,
            'variacao_anual' => $variacao_anual,
            'tipo' => $tipo,
        ]);
        header('Location: ' . BASE_URL . '/metas');
        exit();
    }

    public function update($id) {
        // Lógica para atualizar uma meta (placeholder)
        header('Location: ' . BASE_URL . '/metas');
        exit();
    }

    public function delete($id) {
        // Lógica para deletar uma meta (placeholder)
        header('Location: ' . BASE_URL . '/metas');
        exit();
    }
}