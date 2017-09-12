<?php

/**
 * Configuracion inicial
 */
if (php_sapi_name() == 'cli-server') {
    header("Content-Type: application/json");
    die(json_encode(array(
        'SERVER' => $_SERVER,
        'REQUEST' => $_REQUEST,
        'POST' => $_POST,
        'GET' => $_GET,
        'body' => file_get_contents('php://input'),
        'headers' => getallheaders()
    )));
}

/**
 * Usar la libreria de RestClient
 */
require 'RestClient.php';

/**
 *  Clase para enviar mensaje s de 
 * 
 * @package         Hondacrm
 * @subpackage      Client
 * @category        Cliente
 * @author          Yara Web Developer
 * @link            http://netfordigital.com/
 */
class My_restclient {

    /**
     * Url del servidor de la API donde se hacen las solicitudes
     * 
     * @var String
     */
    public $urlServer = 'https://api.masiv.co/SmsHandlers/sendhandler.ashx';

    /**
     *  Función para enviar los mensajes
     *  los parametros son enviados poe GET de la solicitud.
     * 
     * 
     * 
     * -Estos son los parametros aue se deben enviar en la solictud.
     * 
     * dataMenaje: 
     *  -dataNumero:    Numero de destinatario
     *  -dataMensaje:   Mensaje que se va a enviar
     * 
     * 
     * @param       array                   $dataMensaje            Nombre del cliente
     * 
     * @return      array                   $response_json          Una array de respuesta
     */
    public function enviarMensaje($dataMensaje) {
        //Nueva objeto de RestClient
        $api = new RestClient;

        //Parametros a enviar
        $dataParametros = array(
            "recipient" => "57" . $dataMensaje['dataNumero'],
            "messagedata" => $dataMensaje['dataMensaje'],
            "action" => "sendmessage",
            "username" => "L43s1gT45.8",
            "password" => "hT56aQ3g.5",
            "longMessage" => "true"
        );
        //Ejecutar el post de agregar cliente
        $result = $api->get($this->urlServer, $dataParametros);

        //Obtener la respuesta del servidor
        $response_json = $result->decode_response();

        //Retornar la respuesta del servidor
        return $response_json;
    }
    public function crearUsuarioDrupal($conect,$metodo,$datos,$url = null)
    {
        $api = new RestClient([
            'base_url' => $conect['urlServicesDrupal'],
            'headers' => ['Authorization' => 'Basic '.base64_encode($conect['usuarioServicesDrupal'].':'.$conect['claveServicesDrupal']),'Content-Type' => 'application/json']
        ]);
        $api->set_option('format', "json");
        if (is_null($url)) {
            switch ($metodo) {
                case 'post':
                    $result = $api->post("/user",$datos);
                break;
                case 'get':
                    $result = $api->get("/user",$datos);
                break;
            }
        }
        else
        {
            switch ($metodo) {
                case 'post':
                    $result = $api->post($url,$datos);
                break;
                case 'get':
                    $result = $api->get($url,$datos);
                break;
                case 'put':
                    $result = $api->get($url,$datos);
                break;
            }
        }
        $response_json = $result->decode_response();
        return $response_json;
    }

}
