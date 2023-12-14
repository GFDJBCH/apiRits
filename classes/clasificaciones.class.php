<?php
require_once 'connection/connection.php';
require_once 'classes/response.class.php';

class Clasificaciones extends Connection
{
    private $tableView = "vw_cdc_clasificacion";
    private $table = "cdc_Clasificacion";
    private $Id = 'null';
    private $Referencia = 'null';
    private $IdFuente = 'null';
    private $Fuente = 'null';
    private $IdBitacoraRegistro = 'null';
    private $IdEstatus = 'null';
    private $IdTipoOperacion = 'null';
    private $IdClavePedimento = 'null';
    private $IdZona = 'null';
    private $token = '';
    #4c92a883ffac02b6275e7ba4076567c6

    public function listaClasificaciones($estado, $page = 1)
    {
        $start = 0;
        $qty = 20;
        if ($page > 1):
            $start = ($qty * ($page - 1)) + 1;
            $qty = $qty * $page;
        endif;
        $query = "SELECT * FROM " . $this->tableView . " where estatusId = $estado limit $start,$qty";
        $data = parent::getData($query);
        return ($data);
    }
    public function obtenerClasificacion($id)
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

        if (!isset($data["Referencia"])) {
            return $_response->error400();
        }

        $this->Referencia = $data["Referencia"];
        $this->IdFuente = $data["IdFuente"] ?? 'null';
        $this->Fuente = $data["Fuente"] ?? 'null';
        $this->IdBitacoraRegistro = $data["IdBitacoraRegistro"] ?? 'null';
        $this->IdEstatus = $data["IdEstatus"] ?? 'null';
        $this->IdTipoOperacion = $data["IdTipoOperacion"] ?? 'null';
        $this->IdClavePedimento = $data["IdClavePedimento"] ?? 'null';
        $this->IdZona = $data["IdZona"] ?? 'null';

        $resp = $this->insertProduct();
        if ($resp) {
            $response = $_response->response;
            $response["result"] = array(
                "clasificacionId" => $resp
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
    private function changeStatus() {
        $query = "update ".$this->table." set IdEstatus = ".$this->IdEstatus." where id = ".$this->Id.";";
        $resp = parent::nonQuery($query);
        if ($resp >= 1):
            return $resp;
        else:
            return 0;
        endif;
    }
    private function insertProduct(): int
    {
        $query = "insert into cdc_Clasificacion(Referencia, IdFuente, Fuente, IdBitacoraRegistro, IdEstatus, IdTipoOperacion, IdClavePedimento, IdZona) values ('" . $this->Referencia . "', " . $this->IdFuente . ", '" . $this->Fuente . "', ".$this->IdBitacoraRegistro.", " . $this->IdEstatus . ", " . $this->IdTipoOperacion . ", " . $this->IdClavePedimento . ", " . $this->IdZona . ");";
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

                    if ($data["estado"] == 3347) {
                        $this->IdEstatus = 3348;
                    } else {
                        $this->IdEstatus = $data["estado"];
                    }

                    $resp = $this->changeStatus();
                    if ($resp):
                        $response = $_response->response;
                        $response["result"] = array(
                            "clasificacionId" => $this->id
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
    private function updateClasificacion(): int
    {
        $query = "update ".$this->table." set codigo = ".$this->codigo.", nombre = ".$this->nombre.", descripcion = ".$this->descripcion.", departamento = ".$this->departamento.", unidad = ".$this->unidad.", precioProveedor = ".$this->precioProveedor.", precioGanancia = ".$this->precioGanancia.", impuesto = ".$this->impuesto.", precioVenta = ".$this->precioVenta.", promocionPrecio = ".$this->promocionPrecio.", mayoreoPrecio = ".$this->mayoreoPrecio.", mayoreoMinimo = ".$this->mayoreoMinimo.", mayoreoMaximo = ".$this->mayoreoMaximo.", stock = ".$this->stock.", stockmin = ".$this->stockmin.", paquete = ".$this->paquete.", tipoProducto = ".$this->tipoProducto." where id = ".$this->id.";";
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
                    $resp = $this->deleteProduct();
                    if ($resp):
                        $response = $_response->response;
                        $response["result"] = array(
                            "clasificacionId" => $this->id
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
    private function deleteProduct(): int
    {
        $query = "update ".$this->table." set deletedAt = current_timestamp where id = ".$this->id.";";
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
