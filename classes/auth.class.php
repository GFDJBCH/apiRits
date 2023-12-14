<?php
require_once 'connection/connection.php';
require_once 'response.class.php';
class Auth extends Connection {
    public function login($json) {
        $_response = new Response();
        $data = json_decode($json, true);
        if (!isset($data['usuario']) || !isset($data['password'])):
            #Error con los campos.
            return $_response->error400();
            else:
                #Al 100
            $user = $data['usuario'];
            $password = $data['password'];
//            $password = parent::encrypt($password);
            $data = $this->getUserData($user);
            if ($data):
                #Verificar si la contraseña es igual.
                if ($password == $data[0]['PasswordHash']):
                    if (!$data[0]['Disabled']):
                        #Crear token.
                        $verify = $this->createToken($data[0]['Id']);
                        if ($verify):
                            #Token generado y guardado.
                            $result = $_response->response;
                            $result["result"] = array(
                                "resultData" => $data[0],
                                "token" => $verify
                            );
                            return $result;
                        else:
                            #Error al guardar token.
                            return $_response->error500("Error interno, No hemos podido guardar.");
                        endif;
                    else:
                        #Usuario inactivo.
                        return $_response->error200("El usuario $user esta inactivo.");
                    endif;
                else:
                    #La contraseña no es igual.
                    return $_response->error200("La contraseña es invalida.");
                endif;
            else:
                #No existe el usuario.
                return $_response->error200("El usuario $user no existe.");
            endif;
        endif;
    }
    private function getUserData($codigo) {
        $query = "SELECT Id, UserName, PasswordHash, SecurityStamp, Email, EmailConfirmed, PhoneNumber, PhoneNumberConfirmed, TwoFactorEnabled, Name, Disabled FROM Users WHERE UserName = '$codigo';";
        $data = parent::getData($query);
        if (isset($data[0]['Id'])):
            return $data;
        else:
            return 0;
        endif;
    }
    private function createToken($userId) {
        $var = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $var));
        $query = "INSERT INTO User_Tokens (userId, token, estado) VALUES ('$userId', '$token', 'Activo');";
        $verify = parent::nonQuery($query);
        if ($verify):
            return $token;
        else:
            return 0;
        endif;
    }
}

//