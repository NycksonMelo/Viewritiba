<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION['organizador_id'])) {
    http_response_code(401);
    echo json_encode([
        'status' => 'nok',
        'mensagem' => 'Faça login como organizador para cadastrar eventos.',
        'data' => []
    ]);
    exit;
}

$titulo = trim($_POST['titulo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$data_hora = trim($_POST['data_hora'] ?? '');
$local = trim($_POST['local'] ?? '');

if ($titulo === '' || $descricao === '' || $data_hora === '' || $local === '') {
    http_response_code(400);
    echo json_encode([
        'status' => 'nok',
        'mensagem' => 'Preencha todos os campos do evento.',
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
    INSERT INTO evento (titulo, descricao, data_hora, local, id_organizador)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("ssssi", $titulo, $descricao, $data_hora, $local, $id_organizador);
$stmt->execute();

echo json_encode([
    'status' => 'ok',
    'mensagem' => 'Evento cadastrado com sucesso.',
    'data' => ['id' => $stmt->insert_id]
]);

$stmt->close();
$conexao->close();