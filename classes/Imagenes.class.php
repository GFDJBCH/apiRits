<?php
require_once 'connection/connection.php';
require_once 'classes/response.class.php';

class Imagenes extends Connection
{
    private $table = "_EntidadesPartes";
    private $tableView = "vwwms_RevFacturaItemsFiles";
    private $Id = 'null';
    private $Referencia = 'null';
    private $IdFuente = 'null';
    private $Fuente = 'null';
    private $IdBitacoraRegistro = 'null';
    private $IdEstatus = 'null';
    private $currentFile = null;
    private $token = '';

    public function listaImagenes($parte, $page = 1)
    {
        $start = 0;
        $qty = 50;
        if ($page > 1):
            $start = ($qty * ($page - 1)) + 1;
            $qty = $qty * $page;
        endif;
        $query = "SELECT * FROM " . $this->tableView . " WHERE ItemNoParte = '$parte';";
        $data = parent::getData($query);
        return ($data);
    }
    public function post($json)
    {
        $_response = new Response();
        $data = json_decode($json, true);
        if (!isset($data["token"])):
            return $_response->error401();
        else:
            $this->token = $data["token"];
            $arrayToken = $this->searchToken();
            if ($arrayToken):
                if (!isset($data["file"])):
                    return $_response->error400();
                else:
                    $this->currentFile = $data["file"];
                    $partida = $data["partida"];
                    $parte = $data["numparte"];
                    $resp = $this->processImage($this->currentFile);
                    if ($resp):
                        $response = $_response->response;
                        $response["result"] = array(
                            "imageURL" => $resp
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

    public function processImage($img) {
        $path = dirname(__DIR__)."\public\images\\";
        $parts = explode(";base64,", $img);
        $extension = explode("/", mime_content_type($img)[1]);
        $img_base64 = base64_decode($parts[1]);
        $file = $path . uniqid() . "." . $extension;
        file_put_contents($file, $img_base64);
        return str_replace('\\', '/', $file);
    }

    private function insertProduct(): int
    {
        $query = "insert into ".$this->table." (codigo, nombre, descripcion, imagen, departamento, unidad, precioProveedor, precioGanancia, impuesto, precioVenta, promocionPrecio, mayoreoPrecio, mayoreoMinimo, mayoreoMaximo, stock, stockmin, paquete, tipoProducto) values ('" . $this->codigo . "', '" . $this->nombre . "', " . $this->descripcion . ", '".$this->imagen."', " . $this->departamento . ", " . $this->unidad . ", " . $this->precioProveedor . ", " . $this->precioGanancia . ", " . $this->impuesto . ", " . $this->precioVenta . ", " . $this->promocionPrecio . ", " . $this->mayoreoPrecio . ", " . $this->mayoreoMinimo . ", " . $this->mayoreoMaximo . ", " . $this->stock . ", " . $this->stockmin . ", " . $this->paquete . ", " . $this->tipoProducto . ");";
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
                    $this->id = $data["id"];
                    if (isset($data["codigo"])): $this->codigo = "'" . $data["codigo"] . "'"; endif;
                    if (isset($data["nombre"])): $this->nombre = "'" . $data["nombre"] . "'"; endif;
                    if (isset($data["descripcion"])): $this->descripcion = "'" . $data["descripcion"] . "'"; endif;
                    if (isset($data["departamento"])): $this->departamento = $data["departamento"]; endif;
                    if (isset($data["unidad"])): $this->unidad = $data["unidad"]; endif;
                    if (isset($data["precioProveedor"])): $this->precioProveedor = $data["precioProveedor"]; endif;
                    if (isset($data["precioGanancia"])): $this->precioGanancia = $data["precioGanancia"]; endif;
                    if (isset($data["impuesto"])): $this->impuesto = $data["impuesto"]; endif;
                    if (isset($data["precioVenta"])): $this->precioVenta = $data["precioVenta"]; endif;
                    if (isset($data["promocionPrecio"])): $this->promocionPrecio = $data["promocionPrecio"]; endif;
                    if (isset($data["mayoreoPrecio"])): $this->mayoreoPrecio = $data["mayoreoPrecio"]; endif;
                    if (isset($data["mayoreoMinimo"])): $this->mayoreoMinimo = $data["mayoreoMinimo"]; endif;
                    if (isset($data["mayoreoMaximo"])): $this->mayoreoMaximo = $data["mayoreoMaximo"]; endif;
                    if (isset($data["stock"])): $this->stock = $data["stock"]; endif;
                    if (isset($data["stockmin"])): $this->stockmin = $data["stockmin"]; endif;
                    if (isset($data["paquete"])): $this->paquete = $data["paquete"]; endif;
                    $this->tipoProducto = $data["tipoProducto"];
                    $resp = $this->updateProduct();
                    if ($resp):
                        $response = $_response->response;
                        $response["result"] = array(
                            "productoId" => $this->id
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

    private function updateProduct(): int
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
                            "productoId" => $this->id
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
