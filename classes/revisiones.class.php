<?php
require_once 'connection/connection.php';
require_once 'classes/response.class.php';

class Revisiones extends Connection
{
    private $table = "vw_wms_revision";
    private $tableFactura = "wms_Rev_Factura";
    private $tablePartida = "wms_Rev_Facturaitem";
    private $id = null;
    private $codigo = 'null';
    private $nombre = 'null';
    private $descripcion = 'null';
    private $imagen = 'null';
    private $departamento = 'null';
    private $unidad = 'null';
    private $precioProveedor = 'null';
    private $precioGanancia = 'null';
    private $impuesto = 'null';
    private $precioVenta = 'null';
    private $promocionPrecio = 'null';
    private $mayoreoPrecio = 'null';
    private $mayoreoMinimo = 'null';
    private $mayoreoMaximo = 'null';
    private $stock = 'null';
    private $stockmin = 'null';
    private $paquete = 'null';
    private $tipoProducto = 'null';
    private $token = '';
    #4c92a883ffac02b6275e7ba4076567c6

    public function listaRevisiones($page = 1)
    {
        $page = (int)$page;
        $start = 0;
        $qty = 20;

        $coneccion = array(
            "host" => "ritstest.cnfwdrtgyxew.us-west-2.rds.amazonaws.com",
            "user" => "acromntec",
            "pass" => "actJG2022/*",
            "db" => "rits",
        );

        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array('db' => 'sucursal_id', 'dt' => 'sucursal_id'),
            array('db' => 'sucursal_codigo', 'dt' => 'sucursal_codigo'),
            array('db' => 'sucursal_descripcion', 'dt' => 'sucursal_descripcion'),
            array('db' => 'Folio', 'dt' => 'Folio'),
            array('db' => 'FechaHora', 'dt' => 'FechaHora'),
            array('db' => 'cliente_id', 'dt' => 'cliente_id'),
            array('db' => 'cliente_codigo', 'dt' => 'cliente_codigo'),
            array('db' => 'cliente_id_fiscal', 'dt' => 'cliente_id_fiscal'),
            array('db' => 'cliente_nombre', 'dt' => 'cliente_nombre'),
            array('db' => 'cliente_razon', 'dt' => 'cliente_razon'),
            array('db' => 'cliente_estado', 'dt' => 'cliente_estado'),
            array('db' => 'transportista_id', 'dt' => 'transportista_id'),
            array('db' => 'transportista_codigo', 'dt' => 'transportista_codigo'),
            array('db' => 'transportista_id_fiscal', 'dt' => 'transportista_id_fiscal'),
            array('db' => 'transportista_nombre', 'dt' => 'transportista_nombre'),
            array('db' => 'transportista_razon', 'dt' => 'transportista_razon'),
            array('db' => 'transportista_estado', 'dt' => 'transportista_estado'),
            array('db' => 'CantidadPallet', 'dt' => 'CantidadPallet'),
            array('db' => 'Referencia', 'dt' => 'Referencia'),
            array('db' => 'Urgente', 'dt' => 'Urgente'),
            array('db' => 'Notas', 'dt' => 'Notas'),
            array('db' => 'FechaHoraEnviado', 'dt' => 'FechaHoraEnviado'),
            array('db' => 'Estatus', 'dt' => 'Estatus'),
            array('db' => 'FechaHoraEstatus', 'dt' => 'FechaHoraEstatus'),
            array('db' => 'bitacora_id', 'dt' => 'bitacora_id'),
            array('db' => 'bitacora_creo_nombre', 'dt' => 'bitacora_creo_nombre'),
            array('db' => 'bitacora_actualizo_nombre', 'dt' => 'bitacora_actualizo_nombre'),
            array('db' => 'bitacora_elimino_nombre', 'dt' => 'bitacora_elimino_nombre'),
            array('db' => 'Consecutivo', 'dt' => 'Consecutivo'),
            array('db' => 'Tipo', 'dt' => 'Tipo'),
            array('db' => 'NumeroEntrada', 'dt' => 'NumeroEntrada'),
            array('db' => 'CantidadEmbalaje', 'dt' => 'CantidadEmbalaje'),
            array('db' => 'unidad_bulto_id', 'dt' => 'unidad_bulto_id'),
            array('db' => 'unidad_bulto_tipo', 'dt' => 'unidad_bulto_tipo'),
            array('db' => 'unidad_bulto_unidad', 'dt' => 'unidad_bulto_unidad'),
            array('db' => 'unidad_bulto_descripcion', 'dt' => 'unidad_bulto_descripcion'),
            array('db' => 'unidad_bulto_descripcion_ingles', 'dt' => 'unidad_bulto_descripcion_ingles'),
            array('db' => 'unidad_estado', 'dt' => 'unidad_estado'),
            array('db' => 'recinto_id', 'dt' => 'recinto_id'),
            array('db' => 'recinto_clave', 'dt' => 'recinto_clave'),
            array('db' => 'recinto_nombre', 'dt' => 'recinto_nombre'),
            array('db' => 'unidad_transporte_id', 'dt' => 'unidad_transporte_id'),
            array('db' => 'unidad_transporte_nombre', 'dt' => 'unidad_transporte_nombre'),
            array('db' => 'PesoTotal', 'dt' => 'PesoTotal'),
            array('db' => 'GuiaPrevio', 'dt' => 'GuiaPrevio'),
            array('db' => 'ReferenciaTrafico', 'dt' => 'ReferenciaTrafico'),
        );
        require 'ssp.class.php';

        if ($page > 1):
            $start = ($qty * ($page - 1));
            $qty = $qty * $page;
        endif;
        $queryCount = "SELECT count(id) as conteo FROM " . $this->table . " where Estatus in ('REVISADO');";
        $query = "SELECT * FROM " . $this->table . " where Estatus in ('REVISADO') limit $start,$qty;";
        $data = parent::getData($query);
        $datos = parent::getData($queryCount);
        $resultado = SSP::simple($_GET,$coneccion, $this->table, "id", $columns);
        return $resultado;
    }
    public function listaPartidas($revision)
    {
//        $query = "SELECT * FROM " . $this->tableFactura . " where IDReferencia = $revision;";
        $query = "select * from " . $this->tablePartida . " where IdFactura in (select id from wms_Rev_Factura where IDReferencia = $revision);";
        $data = parent::getData($query);
        return ($data);
    }
    public function obtenerRevision($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = $id";
        return (parent::getData($query));
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
                if (!isset($data["codigo"]) || !isset($data["nombre"]) || !isset($data["tipoProducto"])):
                    return $_response->error400();
                else:
                    $this->codigo = $data["codigo"];
                    $this->nombre = $data["nombre"];
                    if (isset($data["descripcion"])): $this->descripcion = "'" . $data["descripcion"] . "'"; endif;
                    if (isset($data["departamento"])): $this->departamento = $data["departamento"]; endif;
                    if (isset($data["imagen"])):
                        $resp = $this->processImage($data["imagen"]);
                        $this->imagen = $resp;
                    endif;
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
                    $resp = $this->insertProduct();
                    if ($resp):
                        $response = $_response->response;
                        $response["result"] = array(
                            "productoId" => $resp
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

    private function processImage($img) {
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
        $query = "select * from usuario_token where token = '".$this->token."' and estado = 'Activo';";
        $res = parent::getData($query);
        if ($res):
            return $res;
        else:
            return 0;
        endif;
    }

    private function updateToken($tokenId){
        $query = "update usuario_token set createdAt = current_timestamp where id = $tokenId";
        $res = parent::nonQuery($query);
        if ($res >= 1):
            return $res;
        else:
            return 0;
        endif;
    }

}
