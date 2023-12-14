<?php
require_once 'connection/connection.php';
class Token extends Connection {
    public function updateToken($date) {
        $query = "update User_Tokens set estado = 'Inactivo' where date(createdAt) < '".$date."' and estado = 'Activo';";
        $verify = parent::nonQuery($query);
        if ($verify > 0):
            return 1;
        else:
            return 0;
        endif;
    }
}