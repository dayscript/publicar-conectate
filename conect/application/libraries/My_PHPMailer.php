<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//clase para tomar como libreria
class My_PHPMailer {

    public function __construct() {
        require_once('PHPMailer/class.phpmailer.php');
        //obtengo lo principal de php mailer. 
    }

    /*
     * -------------------------------------------------------------------------
     * Metodo para enviar mensajes por SMTP
     * -------------------------------------------------------------------------
     */

    public function enviarCorreo($pData, $dataCC = FALSE,$dominio =  1) {
        //variable de retorno
        $valorRetorno = FALSE;
        //se crea el objeto de tipo mailer
        $mail = new PHPMailer(TRUE);
        //inicializo los valores que se vana anecesitar
        //$pData['dataUsuario'] = "info@enmarcha.com.co";
        //$pData['dataContraseña'] = "netfor!a";
        $pData['dataUsuario'] = "LaEstacion_PlazaCentral__JG5Z";
        $pData['dataContraseña'] = '(qU-0Tf%z@';

        $mail->IsSMTP(); // establecemos que utilizaremos SMTP
        $mail->SMTPAuth = true; // habilitamos la autenticación SMTP
        $mail->Host = "smtp.masivapp.com";      // establecemos GMail como nuestro servidor SMTP
        $mail->Port = 587;                   // establecemos el puerto SMTP en el servidor de GMail
        $mail->Username = $pData['dataUsuario'];  // la cuenta de correo GMail
        $mail->Password = $pData['dataContraseña'];            // password de la cuenta GMail
        /*
        $mail->Host = 'localhost';
        $mail->SMTPAuth = false;
        */
        //Codificacion
        $mail->CharSet = 'utf-8';
        //datos del mensaje
        if ($dominio == 1) {
            $mail->SetFrom("info@conectatepublicar.com", 'Conectate Publicar');  //Quien envía el correo
        }
        else
        {  
            $mail->SetFrom("info@sumatepublicar.com", 'Sumate Publicar');  //Quien envía el correo
        }
        $mail->Subject = $pData['dataAsunto'];  //Asunto del mensaje
        $mail->Body = $pData['dataMensaje']; //cuerpo del mensaje
        $mail->IsHTML(true); //le especifico que puedo enviar html
        if ($pData['dataCorreo'] != NULL && $pData['dataNombre'] != NULL):
            $cadena = explode(";",$pData['dataCorreo']);   
            for ($i=0; $i < count($cadena); $i++) { 
                $mail->AddAddress($cadena[$i], $pData['dataNombre']);
            }
        endif;
        //Obtener concesionario
        if ($dataCC and isset($pData['dataConcesionario'])):
            //Obtener los correos
            if ($pData['dataConcesionario'][0]->con_correos_copia != NULL):

                $dataCorreos = explode(",", $pData['dataConcesionario'][0]->con_correos_copia);
                //Iterar los correos
                foreach ($dataCorreos as $correo):
                    $mail->addCC($correo);
                endforeach;
            endif;

        endif;

        //verifico si el mensaje se envio
        if ($mail->Send()) :
            $valorRetorno = TRUE;
        else:
            echo $mail->ErrorInfo;
        endif;

        //retorno la variable
        return $valorRetorno;
    }

}
