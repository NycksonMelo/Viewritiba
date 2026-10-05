<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
include_once("conexao.php");

if (!isset($_SESSION['organizador_id'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Não autenticado']);
    exit;
}

$id = $_SESSION['organizador_id'];
$stmt = $conexao->prepare("SELECT nome, documento, email, telefone, bio FROM organizador WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($linha = $resultado->fetch_assoc()) {
    echo json_encode(['status' => 'sucesso', 'data' => $linha]);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Organizador não encontrado']);
}

$stmt->close();
$conexao->close();
?>
