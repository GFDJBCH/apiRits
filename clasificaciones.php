<?php
require_once 'classes/response.class.php';
require_once 'classes/clasificaciones.class.php';

$_response = new Response();
$_clasificaciones = new Clasificaciones();

if ($_SERVER["REQUEST_METHOD"] === "GET"):
    if (isset($_GET["page"])):
        $page = $_GET["page"];

    if (isset($_GET["tipo"])) {
        if ($_GET["tipo"] === "pendiente") {;
            $productList = $_clasificaciones->listaClasificaciones(3347, $page);
        } elseif ($_GET["tipo"] === "clasificando") {
            $productList = $_clasificaciones->listaClasificaciones(3348, $page);
        } elseif ($_GET["tipo"] === "pausa") {
            $productList = $_clasificaciones->listaClasificaciones(3349, $page);
        } elseif ($_GET["tipo"] === "cancelado") {
            $productList = $_clasificaciones->listaClasificaciones(3350, $page);
        } else {
            $productList = $_clasificaciones->listaClasificaciones(3351, $page);
        }
    }
        header('Content-Type: application/json');
        echo json_encode($productList);
        http_response_code(200);
    elseif (isset($_GET["id"])):
        $revisionId = $_GET["id"];
        $revisionData = $_clasificaciones->obtenerClasificacion($revisionId);
        header('Content-Type: application/json');
        echo json_encode($revisionData);
        http_response_code(200);
    endif;
elseif ($_SERVER["REQUEST_METHOD"] === "POST"):
    $postBody = file_get_contents('php://input');
    $dataArray = $_clasificaciones->post($postBody);
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
    $dataArray = $_clasificaciones->put($postBody);
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

    $dataArray = $_clasificaciones->delete($postBody);
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
