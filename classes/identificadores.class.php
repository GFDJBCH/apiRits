<?php
require_once 'connection/connection.php';
require_once 'classes/response.class.php';

class Identificadores extends Connection
{
    private $table = "cdc_ClasificacionPartidaIdentificadores";
    private $tableView = "vw_cdc_partidas";
    private $id = 'null';
    private $idclasificacionpartida = 'null';
    private $clave = 'null';
    private $complemento1 = 'null';
    private $complemento2 = 'null';
    private $complemento3 = 'null';
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

    public function obtenerIDPartida($clasificacion)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE idclasificacionpartida = $clasificacion";
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
        if (!isset($data["clasificacion"]) || !isset($data["clave"])) {
            return $_response->error400();
        }
        $this->id = $data["id"];
        $this->idclasificacionpartida = $data["clasificacion"];
        $this->clave = $data["clave"];
        $this->complemento1 = ($data["c1"] === "" ? 'null' : "'".$data["c1"]."'") ?? "null";
        $this->complemento2 = ($data["c2"] === "" ? 'null' : "'".$data["c2"]."'") ?? "null";
        $this->complemento3 = ($data["c3"] === "" ? 'null' : "'".$data["c3"]."'") ?? "null";

        if (is_null($data["id"])) {
            $resp = $this->insertComplement();
        } else {
            $resp = $this->updateComplement();
        }

        if ($resp) {
            $response = $_response->response;
            $response["result"] = array(
                "partidaId" => $resp
            );
            return $response;
        } else {
            return $_response->noChangesResponse();
        }
    }
    private function insertComplement(): int
    {
        $query = "insert into ".$this->table." (idclasificacionpartida, clave, complemento1, complemento2, complemento3) values ('" . $this->idclasificacionpartida . "', '" . $this->clave . "', " . $this->complemento1 . ", ".$this->complemento2.", " . $this->complemento3 . ");";
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
                    $resp = $this->updateComplement();
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

    private function updateComplement(): int
    {
        $query = "update ".$this->table." set clave = '".$this->clave."', complemento1 = ".$this->complemento1.", complemento2 = " . $this->complemento2 . ", complemento3 = ".$this->complemento3." where Id = ".$this->id.";";
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
                    $this->id = $data["id"];
                    $resp = $this->deleteComplement();
                    if ($resp):
                        $response = $_response->response;
                        $response["result"] = array(
                            "partidaId" => $this->id
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

    private function deleteComplement(): int
    {
        $query = "delete from ".$this->table." where Id = ".$this->id.";";
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
