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
require_once __DIR__ . '/../../crud_admin/php/banimentos.php';
exigir_conta_nao_banida($conexao, 'organizador', $id_organizador);
$retorno = [
    'status' => '',
    'mensagem' => '',
    'data' => []
];
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conexao->prepare(

        "SELECT * FROM evento WHERE id = ? AND id_organizador = ?"
=======
        "SELECT * FROM evento WHERE id_evento = ?"

    );
    $stmt->bind_param("ii", $id, $id_organizador);
} elseif (isset($_GET['titulo'])) {
    $titulo = "%" . $_GET['titulo'] . "%";

    $stmt = $conexao->prepare(
        "SELECT * FROM evento WHERE id_organizador = ? AND titulo LIKE ?"
    );
    $stmt->bind_param("is", $id_organizador, $titulo);
=======
     $stmt = $conexao->prepare(
        "SELECT * FROM evento
         WHERE titulo LIKE ?
         OR descricao LIKE ?
         OR local LIKE ?"
    );
    $stmt->bind_param("sss", $titulo, $titulo, $titulo);

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
        'mensagem' => 'Nenhum evento encontrado.',
        'data' => []
    ];
}
$stmt->close();
$conexao->close();
echo json_encode($retorno);