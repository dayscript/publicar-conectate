<?php

if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
class Crud_menu extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct();
        
    }
    public function GetDatos($whereArray = null,$group = null,$sobrecarga = ''){
        $joins[0]  = array('tabla' => 'basica_categoria_menu cm','tipo_join' =>'inner','conect'=>'cm.categoria_menu_id = p.categoria_menu');
        $joins[1]  = array('tabla' => 'basica_categoria_submenu cs','tipo_join' =>'inner','conect'=>'cs.categoria_submenu_id = p.categoria_submenu');
        $joins[2]  = array('tabla' => 'basica_link l','tipo_join' =>'inner','conect'=>'l.menu_id = p.menu_id');
        $joins[3]  = array('tabla' => 'produccion_permisos pr','tipo_join' =>'inner','conect'=>'pr.menu_id = p.menu_id');
        if (is_null($whereArray)) {
            $where = array('l.estado_id' => 1);
        }
        else
        {
            $where=$whereArray;  
        }
        if (is_null($group)) 
        {
            $select = '*';
            $group = null;
        }else
        {
            $select = $group.' '.$sobrecarga;
        }
        return $this->Crud_model->obtenerRegistros('basica_menu',$where,$select,null,'cm.menu_ordenar',$joins,$group);
    }
}

?>
