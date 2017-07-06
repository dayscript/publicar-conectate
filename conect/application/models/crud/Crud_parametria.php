<?php

if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');

/**
 * Description of Crud_model
 *
 * @author YARA WEB Developer
 */
class Crud_parametria extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct();
        $this->load->database();
        
    }


    public function obtenerParametria($nombre)
    {
        $where = array('parametria_nombre' => $nombre);
        $datos = $this->Crud_model->obtenerRegistros('parametria_aplicacion',$where,'parametria_valor');
        if (!is_null($datos)) {
            return $datos[0]->parametria_valor;
        }
        else
        {
            return 0;
        }
    }
    public function actualizarParametria($nombre,$valor)
    {
        $dataclave = array(
            'parametria_valor' => $valor
        );
        $where = array('parametria_nombre' => $nombre);
        return $this->Crud_model->actualizarRegistro('parametria_aplicacion',$dataclave,$where);
    }

}

?>