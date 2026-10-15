<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION['organizador_id'])) {
    http_response_code(401);
    echo json_encode([
        'status' => 'nok',
        'mensagem' => 'Faça login como organizador para consultar seus eventos.',
        'data' => []
    ]);
    exit;
}

include_once('conexao.php');
$id_organizador = (int) $_SESSION['organizador_id'];
$retorno = [
    'status' => '',
    'mensagem' => '',
    'data' => []
];
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conexao->prepare(
        "SELECT * FROM evento WHERE id = ? AND id_organizador = ?"
    );
    $stmt->bind_param("ii", $id, $id_organizador);
} elseif (isset($_GET['titulo'])) {
    $titulo = "%" . $_GET['titulo'] . "%";
    $stmt = $conexao->prepare(
        "SELECT * FROM evento WHERE id_organizador = ? AND titulo LIKE ?"
    );
    $stmt->bind_param("is", $id_organizador, $titulo);
} else {
    $stmt = $conexao->prepare(
        "SELECT * FROM evento WHERE id_organizador = ?"
    );
    $stmt->bind_param("i", $id_organizador);
}
$stmt->execute();
$resultado = $stmt->get_result();
$tabela = [];
if ($resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        $tabela[] = $linha;
    }
    $retorno = [
        'status' => 'ok',
        'mensagem' => 'Sucesso, consulta efetuada.',
        'data' => $tabela
    ];
} else {
    $retorno = [
        'status' => 'nok',
        'mensagem' => 'Não há registros',
        'data' => []
    ];
}
$stmt->close();
$conexao->close();
echo json_encode($retorno);