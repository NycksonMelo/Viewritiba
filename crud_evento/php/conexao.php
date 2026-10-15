<?php

$servidor = "localhost:3306";
$usuario = "root";
$senha = "";
$nome_banco = "viewritiba";

$conexao = new mysqli($servidor, $usuario, $senha, $nome_banco);
$conexao->set_charset("utf8mb4");
=======

$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'viewritiba';

$conexao = new mysqli($servidor, $usuario, $senha, $banco);

// banco de dados MySQL
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
    
}

