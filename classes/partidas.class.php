<?php
require_once 'connection/connection.php';
require_once 'classes/response.class.php';

class Partidas extends Connection
{
    private $tableView = "vw_cdc_partidas";
    private $table = "cdc_ClasificacionPartida";
    private $Id = 'null';
    private $IdClasificacion = 'null';
    private $IdFuentePartida = 'null';
    private $Fuente = 'null';
    private $Fraccion = 'null';
    private $UMT = 'null';
    private $IdFraccion = 'null';
    private $Descripcion = 'null';
    private $token = '';
    #4c92a883ffac02b6275e7ba4076567c6
    public function listaPartidas($clasificacion, $page = 1)
    {
        $start = 0;
        $qty = 20;
        if ($page > 1):
            $start = ($qty * ($page - 1)) + 1;
            $qty = $qty * $page;
        endif;
        $query = "SELECT * FROM " . $this->tableView . " WHERE clasificacion_id = $clasificacion limit $start,$qty";
        $data = parent::getData($query);
        return ($data);
    }
    public function obtenerPartida($id)
    {
        $query = "SELECT * FROM " . $this->tableView . " WHERE id = $id";
        return (parent::getData($query));
    }
    public function post($json)
    {
        $_response = new Response();
        $data = json_decode($json, true);
        if (!isset($data["token"])) {
            return $_response->error401();
        }
        $this->token = $data["token"];
        $arrayToken = $this->searchToken();
        if (!$arrayToken) {
            return $_response->error401("El token que envió es inválido o ha caducado.");
        }
        if (!isset($data["IdClasificacion"]) || !isset($data["Descripcion"])) {
            return $_response->error400();
        }
        $this->IdClasificacion = $data["IdClasificacion"];
        $this->IdFuentePartida = (is_null($data["IdFuentePartida"]) || $data["IdFuentePartida"] === '' ? 'null' : "'".$data["IdFuentePartida"]."'") ?? null;
        $this->Fuente = $data["Fuente"] ?? null;
        $this->Fraccion = (is_null($data["Fraccion"]) || $data["Fraccion"] === '' ? 'null' : "'".$data["Fraccion"]."'") ?? null;
        $this->UMT = (is_null($data["UMT"]) || $data["UMT"] === '' ? 'null' : "'".$data["UMT"]."'") ?? null;
        $this->IdFraccion = (is_null($data["IdFraccion"]) || $data["IdFraccion"] === '' ? 'null' : "'".$data["IdFraccion"]."'") ?? null;
        $this->Descripcion = $data["Descripcion"];

        $resp = $this->insertPartida();
        if ($resp) {
            $response = $_response->response;
            $response["result"] = array(
                "partidaId" => $resp
            );
            return $response;
        } else {
            return $_response->error500();
        }
    }
    private function processImage($img) {
        $path = dirname(__DIR__)."\public\images\\";
        $parts = explode(";base64,", $img);
        $extension = explode("/", mime_content_type($img)[1]);
        $img_base64 = base64_decode($parts[1]);
        $file = $path . uniqid() . "." . $extension;
        file_put_contents($file, $img_base64);
        return str_replace('\\', '/', $file);
    }
    private function insertPartida(): int
    {
        $query = "insert into ".$this->table." (IdClasificacion, IdFuentePartida, Fuente, Fraccion, UMT, IdFraccion, Descripcion) values (" . $this->IdClasificacion . ", " . $this->IdFuentePartida . ", '" . $this->Fuente . "', ".$this->Fraccion.", " . $this->UMT . ", " . $this->IdFraccion . ", '" . $this->Descripcion . "');";
        $resp = parent::nonQueryId($query);
        if ($resp):
            return $resp;
        else:
            return 0;
        endif;
    }
    public function put($json)
    {
        $_response = new Response();
        $data = json_decode($json, true);

        if (!isset($data["token"])):
            return $_response->error401();
        else:
            $this->token = $data["token"];
            $arrayToken = $this->searchToken();
            if ($arrayToken):
                if (!isset($data["id"])):
                    return $_response->error400();
                else:
                    $this->Id = $data["id"];
                    $this->IdClasificacion = $data["IdClasificacion"];
                    $this->IdFuentePartida = (is_null($data["IdFuentePartida"]) || $data["IdFuentePartida"] === '' ? 'null' : "'".$data["IdFuentePartida"]."'") ?? null;
                    $this->Fuente = $data["Fuente"] ?? null;
                    $this->Fraccion = (is_null($data["Fraccion"]) || $data["Fraccion"] === '' ? 'null' : "'".$data["Fraccion"]."'") ?? null;
                    $this->UMT = (is_null($data["UMT"]) || $data["UMT"] === '' ? 'null' : "'".$data["UMT"]."'") ?? null;
                    $this->IdFraccion = (is_null($data["IdFraccion"]) || $data["IdFraccion"] === '' ? 'null' : "'".$data["IdFraccion"]."'") ?? null;
                    $this->Descripcion = $data["Descripcion"];
                    $resp = $this->updatePartida();
                    if ($resp):
                        $response = $_response->response;
                        $response["result"] = array(
                            "partidaId" => $this->Id
                        );
                        return $response;
                    else:
                        return $_response->noChangesResponse();
                    endif;
                endif;
            else:
                return $_response->error401("El token que envió es inválido o ha caducado.");
            endif;
        endif;
    }
    private function updatePartida(): int
    {
        $query = "update ".$this->table." set IdClasificacion = ".$this->IdClasificacion.", IdFuentePartida = ".$this->IdFuentePartida.", Fuente = '" . $this->Fuente . "', Fraccion = ".$this->Fraccion.", UMT = ".$this->UMT.", IdFraccion = ".$this->IdFraccion.", Descripcion = '" . $this->Descripcion . "' where Id = ".$this->Id.";";
        $resp = parent::nonQuery($query);
        if ($resp >= 1):
            return $resp;
        else:
            return 0;
        endif;
    }
    public function delete($json)
    {
        $_response = new Response();
        $data = json_decode($json, true);

        if (!isset($data["token"])):
            return $_response->error401();
        else:
            $this->token = $data["token"];
            $arrayToken = $this->searchToken();
            if ($arrayToken):
                if (!isset($data["id"])):
                    return $_response->error400();
                else:
                    $this->Id = $data["id"];
                    $resp = $this->deletePartida();
                    if ($resp):
                        $response = $_response->response;
                        $response["result"] = array(
                            "partidaId" => $this->Id
                        );
                        return $response;
                    else:
                        return $_response->error500();
                    endif;
                endif;
            else:
                return $_response->error401("El token que envió es inválido o ha caducado.");
            endif;
        endif;
    }
    private function deletePartida(): int
    {
        $query = "delete from ".$this->table." where Id = ".$this->Id.";";
        $resp = parent::nonQuery($query);
        if ($resp >= 1):
            return $resp;
        else:
            return 0;
        endif;
    }
    private function searchToken() {
        $query = "select * from User_Tokens where token = '".$this->token."' and estado = 'Activo';";
        $res = parent::getData($query);
        if ($res):
            return $res;
        else:
            return 0;
        endif;
    }
    private function updateToken($tokenId){
        $query = "update User_Tokens set createdAt = current_timestamp where id = $tokenId";
        $res = parent::nonQuery($query);
        if ($res >= 1):
            return $res;
        else:
            return 0;
        endif;
    }

}
