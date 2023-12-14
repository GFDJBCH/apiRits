<?php
require_once 'classes/response.class.php';
require_once 'classes/partidas.class.php';

$_response = new Response();
$_partidas = new Partidas();

if ($_SERVER["REQUEST_METHOD"] === "GET"):
    if (isset($_GET["page"]) && isset($_GET["clasificacion"])):
        $page = $_GET["page"];
        $clasificacion = $_GET["clasificacion"];
        $productList = $_partidas->listaPartidas($clasificacion, $page);
        header('Content-Type: application/json');
        echo json_encode($productList);
        http_response_code(200);
    elseif (isset($_GET["id"])):
        $partidaId = $_GET["id"];
        $partidasData = $_partidas->obtenerPartida($partidaId);
        header('Content-Type: application/json');
        echo json_encode($partidasData);
        http_response_code(200);
    endif;
elseif ($_SERVER["REQUEST_METHOD"] === "POST"):
    $postBody = file_get_contents('php://input');
    $dataArray = $_partidas->post($postBody);
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
    $dataArray = $_partidas->put($postBody);
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

    $dataArray = $_partidas->delete($postBody);
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
