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

    public function datosIncentive($whereArray = null,$select=null){
        $joins[0]  = array('tabla' => 'basica_cargo c','tipo_join' =>'inner','conect'=>'c.cargo_id=p.cargo_id');
        return $this->Crud_model->obtenerRegistros('parametria_incentive',$whereArray,$select,null,null,$joins);
    }
    public function datosMenuIncentive($whereArray = null,$select=null){
        $joins[0]  = array('tabla' => 'basica_cargosubmenu c','tipo_join' =>'inner','conect'=>'p.cargomenu_id =  c.cargomenu_id');
        $group = null;
        return $this->Crud_model->obtenerRegistros('basica_cargomenu',$whereArray,$select,null,null,$joins,$group);
    }
    public function datosWhere($whereArray = null,$select=null){
        return $this->Crud_model->obtenerRegistros('produccion_where',$whereArray,$select);
    }

}

?>