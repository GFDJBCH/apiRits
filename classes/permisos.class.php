<?php
require_once 'connection/connection.php';
require_once 'classes/response.class.php';

class Permisos extends Connection
{
    private $table = "cdc_ClasificacionPartidaPermisos";
    private $tableView = "vw_cdc_partidas";
    private $Id = null;
    private $IdClasificacionPartida = 'null';
    private $IdPedimento = 'null';
    private $Clave = 'null';
    private $FirmaDescargo = 'null';
    private $NumeroPermiso = 'null';
    private $ValorComercialDolares = 'null';
    private $CantidadMercancia = 'null';
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
        $query = "SELECT * FROM " . $this->table . " WHERE IdClasificacionPartida = $clasificacion";
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
        if (!isset($data["clasificacion"]) || !isset($data["Clave"])) {
            return $_response->error400();
        }
        $this->Id = $data["id"];
        $this->IdClasificacionPartida = $data["clasificacion"];
        $this->IdPedimento = "null";
        $this->Clave = $data["Clave"];
        $this->FirmaDescargo = ($data["FirmaDescargo"] === "" ? 'null' : "'" . $data["FirmaDescargo"] . "'") ?? "null";
        $this->NumeroPermiso = ($data["NumeroPermiso"] === "" ? 'null' : "'" . $data["NumeroPermiso"] . "'") ?? "null";
        $this->ValorComercialDolares = ($data["ValorComercialDolares"] === "" ? 'null' : "'" . $data["ValorComercialDolares"] . "'") ?? "null";
        $this->CantidadMercancia = ($data["CantidadMercancia"] === "" ? 'null' : "'" . $data["CantidadMercancia"] . "'") ?? "null";

        if (is_null($data["id"])) {
            $resp = $this->insertPermiso();
        } else {
            $resp = $this->updatePermiso();
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
    private function insertPermiso(): int
    {
        $query = "insert into ".$this->table." (IdClasificacionPartida, IdPedimento, Clave, FirmaDescargo, NumeroPermiso, ValorComercialDolares, CantidadMercancia) values ('" . $this->IdClasificacionPartida . "', " . $this->IdPedimento . ", '" . $this->Clave . "', ".$this->FirmaDescargo.", " . $this->NumeroPermiso . ", " . $this->ValorComercialDolares . ", " . $this->CantidadMercancia . ");";
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
                    $resp = $this->updatePermiso();
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

    private function updatePermiso(): int
    {
        $query = "update ".$this->table." set IdPedimento = ".$this->IdPedimento.", Clave = '".$this->Clave."', FirmaDescargo = " . $this->FirmaDescargo . ", NumeroPermiso = ".$this->NumeroPermiso.", ValorComercialDolares = ".$this->ValorComercialDolares.", CantidadMercancia = ".$this->CantidadMercancia." where Id = ".$this->Id.";";
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
