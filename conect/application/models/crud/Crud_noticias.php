<?php

if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
class Crud_noticias extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct();
        
    }

    public function GetDatosTotales($limit = null){
    	$where = array('noticia_vista' => 0,'usuario_id' => $this->session->userdata('id'));
        return $this->Crud_model->obtenerRegistros('produccion_noticia',$where,'*',$limit);
    }
    
}

?>
