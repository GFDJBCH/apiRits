<?php
require_once 'classes/response.class.php';
require_once 'classes/clasificando.class.php';

$_response = new Response();
$_clasificando = new Clasificando();

if ($_SERVER["REQUEST_METHOD"] === "GET"):
    if (isset($_GET["page"])):
        $page = $_GET["page"];
        $productList = $_clasificando->listaClasificando($page);
        header('Content-Type: application/json');
        echo json_encode($productList);
        http_response_code(200);
    elseif (isset($_GET["id"])):
        $revisionId = $_GET["id"];
        $revisionData = $_clasificando->obtenerClasificacion($revisionId);
        header('Content-Type: application/json');
        echo json_encode($revisionData);
        http_response_code(200);
    endif;
elseif ($_SERVER["REQUEST_METHOD"] === "POST"):
    $postBody = file_get_contents('php://input');
    $dataArray = $_clasificando->post($postBody);
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
    $dataArray = $_clasificando->put($postBody);
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

    $dataArray = $_clasificando->delete($postBody);
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