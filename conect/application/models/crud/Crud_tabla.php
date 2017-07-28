<?php

if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
class Crud_tabla extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct();
        
    }
    public function GetDatos($whereArray = null,$group = null,$sobrecarga = '',$selectcarga = '*'){
        $joins[0]  = array('tabla' => 'basica_columna c','tipo_join' =>'inner','conect'=>'c.tabla_id = p.tabla_id');
        if (is_null($group)) 
        {
            $select = $selectcarga;
            $group = null;
        }else
        {
            $select = $group.' '.$sobrecarga;
        }
        return $this->Crud_model->obtenerRegistros('basica_tabla',$whereArray,$select,null,null,$joins,$group);
    }
    public function getTablas($whereArray = null,$group = null,$sobrecarga = '',$selectcarga = '*')
    {
        if (is_null($group)) 
        {
            $select = $selectcarga;
            $group = null;
        }else
        {
            $select = $group.' '.$sobrecarga;
        }
        return $this->Crud_model->obtenerRegistros('basica_tabla',$whereArray,$select,null,null,null,$group);   
    }
}

?>
