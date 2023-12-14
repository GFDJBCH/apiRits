<?php
require_once 'classes/auth.class.php';
require_once 'classes/response.class.php';

$_auth = new Auth();
$_response = new Response();

if ($_SERVER['REQUEST_METHOD'] == 'POST'):
    #Recibir datos.
    $postBody = file_get_contents('php://input');
    #Enviamos datos al manejador.
    $dataArray = $_auth->login($postBody);
    #Devolvemos respuesta.
    header('Content-Type: application/json');
    if (isset($dataArray["result"]["errorId"])):
        $responseCode = $dataArray["result"]["errorId"];
        http_response_code($responseCode);
    else:
        http_response_code(200);
    endif;
else:
    header('Content-Type: application/json');
    $dataArray =$_response->error405();
endif;

echo json_encode($dataArray);