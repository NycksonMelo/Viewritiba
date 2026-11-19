<?php
$servidor = "127.0.0.1";
$porta = 3310;
$usuario = "root";
$senha_banco = "";
$nome_banco = "viewritiba";
$conexao = new mysqli($servidor, $usuario, $senha_banco, $nome_banco, $porta);
$conexao->set_charset("utf8mb4");
