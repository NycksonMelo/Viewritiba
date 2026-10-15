<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

if (isset($_SESSION['organizador_id'])) {
    echo json_encode([
        'status' => 'ok',
        'mensagem' => '',
        'data' => []
    ]);
    exit;
}

http_response_code(401);
echo json_encode([
    'status' => 'nok',
    'mensagem' => 'Faça login como organizador para continuar.',
    'data' => []
]);
=======
    session_start();
    if(isset($_SESSION['usuario']) || isset($_SESSION['organizador_id'])){
        $retorno = [
            'status'    => 'ok', 
            'mensagem'  => '', 
            'data'      => []
        ];
    }else{
        $retorno = [
            'status'    => 'nok', 
            'mensagem'  => '', 
            'data'      => []
        ];
    }
    header("Content-type:application/json;charset:utf-8;");
    echo json_encode($retorno);

