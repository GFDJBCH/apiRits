<?php
class Connection {
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $connection;
    public function __construct()
    {
        $dataList = $this->connectionData();
        foreach ($dataList as $key => $value):
            $this->server = $value["server"];
            $this->user = $value["user"];
            $this->password = $value["password"];
            $this->database = $value["database"];
            $this->port = $value["port"];
        endforeach;
        $this->connection = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
        if ($this->connection->connect_errno):
            echo 'Conexión incorrecta';
            die();
        endif;
    }
#Obtener datos de configuración.
    private function connectionData() {
        $path = dirname(__FILE__);
        $jsonData = file_get_contents($path. "/" . "config");
        return json_decode($jsonData, true);
    }
#Convertir a UTF8.
    private function utf8Convert($array) {
        array_walk_recursive($array, function (&$item, $key){
           if (!mb_detect_encoding($item, 'utf8', true)):
               $item = utf8_encode($item);
           endif;
        });
        return $array;
    }
#Obtener información de una tabla (SELECT).
    public function getData($query) {
        $results = $this->connection->query($query);
        $arrayResult = array();
        foreach ($results as $key):
            $arrayResult[] = $key;
        endforeach;
        return $this->utf8Convert($arrayResult);
    }
#Obtener información de registro (INSERT) de una tabla.
    public function nonQuery($query) {
        $results = $this->connection->query($query);
        return $this->connection->affected_rows;
    }
#Obtener ultimo ID insertado (INSERT) de una tabla.
    public function nonQueryId($query) {
        $results = $this->connection->query($query);
        $rows = $this->connection->affected_rows;
        if ($rows >= 1):
            return $this->connection->insert_id;
        else:
            return 0;
        endif;
    }
#Encriptar contraseña.
    protected function encrypt($string) {
        return md5($string);
    }
}
