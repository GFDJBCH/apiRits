<?php
require_once 'classes/response.class.php';
require_once 'classes/colaboradores.class.php';

$_response = new Response();
$_colaboradores = new Colaboradores();

if ($_SERVER["REQUEST_METHOD"] === "GET"):
    $productList = $_colaboradores->listaColaboradores();
    header('Content-Type: application/json');
    echo json_encode($productList);
    http_response_code(200);
elseif ($_SERVER["REQUEST_METHOD"] === "POST"):
    $postBody = file_get_contents('php://input');
    $dataArray = $_colaboradores->post($postBody);
    header('Content-Type: application/json');
    if (isset($dataArray["result"]["errorId"])):
        $responseCode = $dataArray["result"]["errorId"];
        http_response_code($responseCode);
    else:
        http_response_code(200);
    endif;
    echo json_encode($dataArray);
elseif ($_SERVER["REQUEST_METHOD"] === "PUT"):
    $postBody = file_get_contents('php://input');
    $dataArray = $_colaboradores->put($postBody);
    header('Content-Type: application/json');
    if (isset($dataArray["result"]["errorId"])):
        $responseCode = $dataArray["result"]["errorId"];
        http_response_code($responseCode);
    else:
        http_response_code(200);
    endif;
    echo json_encode($dataArray);
elseif ($_SERVER["REQUEST_METHOD"] === "DELETE"):
    $headers = getallheaders();
    if (isset($headers["id"]) && isset($headers["token"])):
        //Obtener datos del header.
        $send = [
            "id" => $headers["id"],
            "token" => $headers["token"],
        ];
        $postBody = json_encode($send);
    else:
        //Obtener datos del body.
        $postBody = file_get_contents('php://input');
    endif;

    $dataArray = $_colaboradores->delete($postBody);
    header('Content-Type: application/json');
    if (isset($dataArray["result"]["errorId"])):
        $responseCode = $dataArray["result"]["errorId"];
        http_response_code($responseCode);
    else:
        http_response_code(200);
    endif;
    echo json_encode($dataArray);
else:
    header('Content-Type: application/json');
    $dataArray = $_response->error405();
    echo json_encode($dataArray);
endif;
