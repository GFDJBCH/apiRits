<?php
require_once 'connection/connection.php';
require_once 'classes/response.class.php';

class Partes extends Connection
{
    private $table = "_EntidadesPartes";
    private $tableView = "vw_num_partes";
    private $Id = 'null';
    private $Referencia = 'null';
    private $IdFuente = 'null';
    private $Fuente = 'null';
    private $IdBitacoraRegistro = 'null';
    private $IdEstatus = 'null';
    private $token = '';

    public function listaPartes($cliente, $page = 1)
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
            array('db' => 'parte_id', 'dt' => 'parte_id'),
            array('db' => 'cliente_id', 'dt' => 'cliente_id'),
            array('db' => 'proveedor_id', 'dt' => 'proveedor_id'),
            array('db' => 'proveedor_codigo', 'dt' => 'proveedor_codigo'),
            array('db' => 'proveedor_nombre', 'dt' => 'proveedor_nombre'),
            array('db' => 'parte_numero', 'dt' => 'parte_numero'),
            array('db' => 'parte_descripcion', 'dt' => 'parte_descripcion'),
            array('db' => 'fraccion_id', 'dt' => 'fraccion_id'),
            array('db' => 'fraccion_numero', 'dt' => 'fraccion_numero'),
            array('db' => 'fraccion_descripcion', 'dt' => 'fraccion_descripcion'),
            array('db' => 'fraccion_pais_id', 'dt' => 'fraccion_pais_id'),
            array('db' => 'fraccion_pais_codigo', 'dt' => 'fraccion_pais_codigo'),
            array('db' => 'fraccion_pais_codigo2', 'dt' => 'fraccion_pais_codigo2'),
            array('db' => 'fraccion_pais_numerico', 'dt' => 'fraccion_pais_numerico'),
            array('db' => 'fraccion_pais_nombre', 'dt' => 'fraccion_pais_nombre'),
            array('db' => 'fraccion_unidad_medida', 'dt' => 'fraccion_unidad_medida'),
            array('db' => 'hts_id', 'dt' => 'hts_id'),
            array('db' => 'hts_numero', 'dt' => 'hts_numero'),
            array('db' => 'hts_descripcion', 'dt' => 'hts_descripcion'),
            array('db' => 'hts_pais_id', 'dt' => 'hts_pais_id'),
            array('db' => 'hts_pais_codigo', 'dt' => 'hts_pais_codigo'),
            array('db' => 'hts_pais_codigo2', 'dt' => 'hts_pais_codigo2'),
            array('db' => 'hts_pais_numerico', 'dt' => 'hts_pais_numerico'),
            array('db' => 'hts_pais_nombre', 'dt' => 'hts_pais_nombre'),
            array('db' => 'hts_unidad_medida', 'dt' => 'hts_unidad_medida'),
            array('db' => 'parte_unidad_medida_id', 'dt' => 'parte_unidad_medida_id'),
            array('db' => 'parte_unidad_medida_tipo', 'dt' => 'parte_unidad_medida_tipo'),
            array('db' => 'parte_unidad_medida_unidad', 'dt' => 'parte_unidad_medida_unidad'),
            array('db' => 'parte_unidad_medida_descripcion', 'dt' => 'parte_unidad_medida_descripcion'),
            array('db' => 'parte_unidad_medida_desc_ingles', 'dt' => 'parte_unidad_medida_desc_ingles'),
            array('db' => 'FactorConversionBulto', 'dt' => 'FactorConversionBulto'),
            array('db' => 'bulto_unidad_medida_id', 'dt' => 'bulto_unidad_medida_id'),
            array('db' => 'bulto_unidad_medida_tipo', 'dt' => 'bulto_unidad_medida_tipo'),
            array('db' => 'bulto_unidad_medida_unidad', 'dt' => 'bulto_unidad_medida_unidad'),
            array('db' => 'bulto_unidad_medida_descripcion', 'dt' => 'bulto_unidad_medida_descripcion'),
            array('db' => 'FactorConversionPallet', 'dt' => 'FactorConversionPallet'),
            array('db' => 'cove_unidad_medida_id', 'dt' => 'cove_unidad_medida_id'),
            array('db' => 'cove_unidad_medida_tipo', 'dt' => 'cove_unidad_medida_tipo'),
            array('db' => 'cove_unidad_medida_unidad', 'dt' => 'cove_unidad_medida_unidad'),
            array('db' => 'cove_unidad_medida_descripcion', 'dt' => 'cove_unidad_medida_descripcion'),
            array('db' => 'FactorConversionCove', 'dt' => 'FactorConversionCove'),
            array('db' => 'PesoBrutoUnitario', 'dt' => 'PesoBrutoUnitario'),
            array('db' => 'PesoNetoUnitario', 'dt' => 'PesoNetoUnitario'),
            array('db' => 'peso_unidad_medida_id', 'dt' => 'peso_unidad_medida_id'),
            array('db' => 'peso_unidad_medida_tipo', 'dt' => 'peso_unidad_medida_tipo'),
            array('db' => 'peso_unidad_medida_unidad', 'dt' => 'peso_unidad_medida_unidad'),
            array('db' => 'peso_unidad_medida_descripcion', 'dt' => 'peso_unidad_medida_descripcion'),
            array('db' => 'ImporteUnitario', 'dt' => 'ImporteUnitario'),
            array('db' => 'Importe', 'dt' => 'Importe'),
            array('db' => 'Notas', 'dt' => 'Notas'),
            array('db' => 'Activo', 'dt' => 'Activo'),
            array('db' => 'IdBitacoraRegistros', 'dt' => 'IdBitacoraRegistros'),
            array('db' => 'pais_origen_id', 'dt' => 'pais_origen_id'),
            array('db' => 'pais_origen_codigo', 'dt' => 'pais_origen_codigo'),
            array('db' => 'pais_origen_codigo2', 'dt' => 'pais_origen_codigo2'),
            array('db' => 'pais_origen_numerico', 'dt' => 'pais_origen_numerico'),
            array('db' => 'pais_origen_nombre', 'dt' => 'pais_origen_nombre'),
            array('db' => 'pais_vendedor_id', 'dt' => 'pais_vendedor_id'),
            array('db' => 'pais_vendedor_codigo', 'dt' => 'pais_vendedor_codigo'),
            array('db' => 'pais_vendedor_codigo2', 'dt' => 'pais_vendedor_codigo2'),
            array('db' => 'pais_vendedor_numerico', 'dt' => 'pais_vendedor_numerico'),
            array('db' => 'pais_vendedor_nombre', 'dt' => 'pais_vendedor_nombre'),
            array('db' => 'Marca', 'dt' => 'Marca'),
            array('db' => 'Modelo', 'dt' => 'Modelo'),
            array('db' => 'Tipo', 'dt' => 'Tipo'),
            array('db' => 'Fraccion', 'dt' => 'Fraccion')
        );
        require 'ssp.class.php';

        if ($page > 1):
            $start = ($qty * ($page - 1));
            $qty = $qty * $page;
        endif;
        $resultado = SSP::complex($_GET,$coneccion, $this->tableView, "parte_id", $columns, "cliente_id = $cliente");
        return $resultado;

       /* $start = 0;
        $qty = 50;
        if ($page > 1):
            $start = ($qty * ($page - 1)) + 1;
            $qty = $qty * $page;
        endif;
        $queryCount = "SELECT count(parte_id) as conteo FROM " . $this->tableView . " WHERE cliente_id = $cliente;";
        $query = "SELECT * FROM " . $this->tableView . " WHERE cliente_id = $cliente;";
        $data = parent::getData($query);
        $datos = parent::getData($queryCount);

        $arregloResultado = [
            "draw" => (int)$page,
            "recordsTotal" => (int)$datos[0]["conteo"],
            "recordsFiltered" => count($data),
            "data" => $data
        ];

        return ($arregloResultado);*/
    }
    public function listaPartesNoCliente($page = 1)
    {
        $start = 0;
        $qty = 50;
        if ($page > 1):
            $start = ($qty * ($page - 1)) + 1;
            $qty = $qty * $page;
        endif;
        $queryCount = "SELECT count(parte_id) as conteo FROM " . $this->tableView . ";";
        $query = "SELECT * FROM " . $this->tableView . ";";
        $data = parent::getData($query);
        $datos = parent::getData($queryCount);

        $arregloResultado = [
            "draw" => (int)$page,
            "recordsTotal" => (int)$datos[0]["conteo"],
            "recordsFiltered" => count($data),
            "data" => $data
        ];

        return ($arregloResultado);
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
                if (!isset($data["Referencia"]) || !isset($data["IdFuente"])):
                    return $_response->error400();
                else:
                    $this->Referencia = $data["Referencia"];
                    $this->IdFuente = $data["IdFuente"];
                    $this->Fuente = $data["Fuente"];
                    if (isset($data["IdBitacoraRegistro"])): $this->IdBitacoraRegistro = "'" . $data["IdBitacoraRegistro"] . "'"; endif;
                    if (isset($data["IdEstatus"])): $this->IdEstatus = "'" . $data["IdEstatus"] . "'"; endif;
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
