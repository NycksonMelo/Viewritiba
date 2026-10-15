<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION['organizador_id'])) {
    http_response_code(401);
    echo json_encode([
        'status' => 'nok',
        'mensagem' => 'Faça login como organizador para alterar eventos.',
        'data' => []
    ]);
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
$titulo = trim($_POST['titulo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$data_hora = trim($_POST['data_hora'] ?? '');
$local = trim($_POST['local'] ?? '');

if ($id < 1 || $titulo === '' || $descricao === '' || $data_hora === '' || $local === '') {
    http_response_code(400);
    echo json_encode([
        'status' => 'nok',
        'mensagem' => 'Informe um evento válido e preencha todos os campos.',
        'data' => []
    ]);
    exit;
}

$data_hora = str_replace('T', ' ', $data_hora);
if (strlen($data_hora) === 16) {
    $data_hora .= ':00';
}

include_once('conexao.php');

$id_organizador = (int) $_SESSION['organizador_id'];
$stmt = $conexao->prepare("
    UPDATE evento
    SET titulo = ?, descricao = ?, data_hora = ?, local = ?
    WHERE id = ? AND id_organizador = ?
");
$stmt->bind_param("ssssii", $titulo, $descricao, $data_hora, $local, $id, $id_organizador);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $retorno = [
        'status' => 'ok',
        'mensagem' => 'Evento alterado com sucesso.',
        'data' => []
    ];
} else {
    $retorno = [
        'status' => 'nok',
        'mensagem' => 'O evento não foi encontrado ou não houve alterações.',
        'data' => []
    ];
}

$stmt->close();
$conexao->close();
echo json_encode($retorno);