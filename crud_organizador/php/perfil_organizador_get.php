<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
include_once("conexao.php");

// Determina qual organizador será consultado
$id_alvo = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_alvo = (int) $_GET['id'];
} elseif (isset($_SESSION['organizador_id'])) {
    $id_alvo = (int) $_SESSION['organizador_id'];
}

if (!$id_alvo) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Acesso restrito. Faça login para visualizar o perfil.']);
    exit;
}

// Verifica se o usuário autenticado na sessão é o dono deste perfil
$is_dono = (isset($_SESSION['organizador_id']) && (int)$_SESSION['organizador_id'] === $id_alvo);

// 1. Busca os dados cadastrais do organizador
$stmtOrg = $conexao->prepare("SELECT id, nome, documento, email, telefone, bio FROM organizador WHERE id = ?");
$stmtOrg->bind_param("i", $id_alvo);
$stmtOrg->execute();
$resOrg = $stmtOrg->get_result();

if (!$organizador = $resOrg->fetch_assoc()) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Organizador não encontrado.']);
    $stmtOrg->close();
    $conexao->close();
    exit;
}
$stmtOrg->close();

// Se não for o dono, esconde dados sensíveis como o documento completo
if (!$is_dono && !empty($organizador['documento'])) {
    // Mascara o documento se for visitante público
    $organizador['documento'] = '***.***.***-**';
}

// 2. Busca os eventos criados por este organizador na tabela evento
$eventos = [];
$stmtEvt = $conexao->prepare("
    SELECT id, titulo, descricao, data_hora, local 
    FROM evento 
    WHERE id_organizador = ? 
    ORDER BY data_hora DESC
");

if ($stmtEvt) {
    $stmtEvt->bind_param("i", $id_alvo);
    $stmtEvt->execute();
    $resEvt = $stmtEvt->get_result();
    while ($linha = $resEvt->fetch_assoc()) {
        $eventos[] = $linha;
    }
    $stmtEvt->close();
}

$conexao->close();

// Retorna os dados completos do perfil e seus eventos
echo json_encode([
    'status' => 'sucesso',
    'is_dono' => $is_dono,
    'organizador' => $organizador,
    'data' => $organizador, // Compatibilidade retroativa
    'total_eventos' => count($eventos),
    'eventos' => $eventos
]);
?>
