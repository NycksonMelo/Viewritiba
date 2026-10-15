<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION['organizador_id'])) {
    http_response_code(401);
    echo json_encode([
        'status' => 'nok',
        'mensagem' => 'Faça login como organizador para excluir eventos.',
        'data' => []
    ]);
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) {
    http_response_code(400);
    echo json_encode([
        'status' => 'nok',
        'mensagem' => 'É necessário informar um ID válido para exclusão.',
        'data' => []
    ]);
    exit;
}

include_once('conexao.php');

$id_organizador = (int) $_SESSION['organizador_id'];
$stmt = $conexao->prepare("DELETE FROM evento WHERE id = ? AND id_organizador = ?");
$stmt->bind_param("ii", $id, $id_organizador);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $retorno = [
        'status' => 'ok',
        'mensagem' => 'Evento excluído.',
        'data' => []
    ];
} else {
    $retorno = [
        'status' => 'nok',
        'mensagem' => 'Evento não encontrado ou sem permissão para excluí-lo.',
        'data' => []
    ];
}

$stmt->close();
$conexao->close();
echo json_encode($retorno);