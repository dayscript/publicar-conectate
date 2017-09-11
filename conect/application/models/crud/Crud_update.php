<?php

if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
class Crud_update extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct();
        
    }

    public function datosConsulta($whereArray = null,$select = '*',$group = null)
    {
        $joins[0]  = array('tabla' => 'produccion_usuario u ','tipo_join' =>'inner', 'conect'=>'u.usuario_documento =  p.usuario_documento');
        if (is_null($whereArray)) {
            $where = array('p.agile_estado_id' => 1);
        }
        else
        {
            $where=$whereArray;
        }
        return $this->Crud_model->obtenerRegistros('produccion_updateusuario',$where,$select, NULL,null, $joins,$group);
    }
    public function GetDatosTotal(){
        return $this->Crud_model->obtenerRegistros('produccion_updateusuario',null,'*');
    }
    public function Insertar($arrayInsertar)
    {
        return $this->Crud_model->agregarRegistro('produccion_updateusuario',$arrayInsertar);
    }
    public function editar($pArrayActualizar,$id)
    {
        return $this->Crud_model->actualizarRegistro('produccion_updateusuario',$pArrayActualizar,$id);
    }
   
}

?>
