<?php
class Response {
    public $response = [
        'status' => 'ok',
        'result' => array()
    ];
    public function error405() {
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            'errorId' => '405',
            'errorMsg' => 'Método no permitido.'
        );
        return $this->response;
    }
    public function error200($message = 'Datos incorrectos.') {
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            'errorId' => '200',
            'errorMsg' => $message
        );
        return $this->response;
    }
    public function error400() {
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            'errorId' => '400',
            'errorMsg' => 'Solicitud incorrecta.'
        );
        return $this->response;
    }
    public function error500($message = 'Error interno del servidor.') {
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            'errorId' => '500',
            'errorMsg' => $message
        );
        return $this->response;
    }

    public function error401($message = 'No autorizado, TOKEN invalido.') {
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            'errorId' => '401',
            'errorMsg' => $message
        );
        return $this->response;
    }
    public function noChangesResponse($message = 'No se realizaron cambios, los datos ya eran los mismos.') {
        $this->response['status'] = 'success';
        $this->response['result'] = array(
            'message' => $message
        );
        return $this->response;
    }

}