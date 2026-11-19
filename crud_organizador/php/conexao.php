<?php

$servidor = '127.0.0.1';
$porta = 3310;
$usuario = 'root';
$senha_banco = '';
$banco = 'viewritiba';

$conexao = new mysqli($servidor, $usuario, $senha_banco, $banco, $porta);

// banco de dados MySQL
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
    
}