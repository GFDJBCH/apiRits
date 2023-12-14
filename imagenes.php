<?php
require_once 'classes/response.class.php';
require_once 'classes/Imagenes.class.php';

$_response = new Response();
$_partidas = new Imagenes();
if ($_SERVER["REQUEST_METHOD"] === "GET"):
    if (isset($_GET["parte"])):
        $parte = $_GET["parte"];
        $productList = $_partidas->listaImagenes($parte);
        header('Content-Type: application/json');
        echo json_encode($productList);
        http_response_code(200);
    endif;
elseif ($_SERVER["REQUEST_METHOD"] === "POST"):
    if (isset($_FILES["archivo"])) {
        $file = $_FILES["archivo"];

        $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);
        $uuid = uniqid();
        $file_name = $uuid . "." . $file_extension;

        $file_tmp = $file["tmp_name"];
        $destination = "public/images/" . $file_name;

        $original_name = $file["name"];
        $extension = $file_extension;
        $file_size_bytes = $file["size"];

        if (move_uploaded_file($file_tmp, $destination)) {
            http_response_code(200);
            echo json_encode([
                "message" => "Carga exitosa",
                "nombre" => $file_name,
                "nombre_original" => $original_name,
                "extension" => $extension,
                "peso_bytes" => $file_size_bytes
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al cargar el archivo"]);
        }
    }
    else {
        http_response_code(400);
        echo json_encode(["error" => "Archivo no encontrado en la solicitud"]);
    }
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
