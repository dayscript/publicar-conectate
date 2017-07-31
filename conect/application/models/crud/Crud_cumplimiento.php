<?php

if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
class Crud_cumplimiento extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct();
        
    }

    public function datosPendientecarga($whereArray = null,$select = '*',$group = null)
    {
        $joins[0]  = array('tabla' => 'produccion_venta v ','tipo_join' =>'inner', 'conect'=>'v.usuario_id = p.usuario_id AND p.metaventa_mes = v.venta_mes');
        $joins[1]  = array('tabla' => 'produccion_usuario u ','tipo_join' =>'inner', 'conect'=>'u.usuario_id =  p.usuario_id');
        $joins[2]  = array('tabla' => 'parametria_incentive pi ','tipo_join' =>'left', 'conect'=>'u.cargo_id =  pi.cargo_id');

        if (is_null($whereArray)) {
            $where = array('u.estado_id' => 1);
        }
        else
        {
            $where=$whereArray;
        }
        return $this->Crud_model->obtenerRegistros('produccion_metaventa',$where,$select, NULL,'p.metaventa_id desc', $joins,$group);
    }
    public function datosPendientecargaVisitas($whereArray = null,$select = '*',$group = null)
    {
        $joins[0]  = array('tabla' => 'produccion_visita v ','tipo_join' =>'inner', 'conect'=>'v.usuario_id = p.usuario_id AND p.metavisita_mes = v.visita_mes');
        $joins[1]  = array('tabla' => 'produccion_usuario u ','tipo_join' =>'inner', 'conect'=>'u.usuario_id =  p.usuario_id');
        $joins[2]  = array('tabla' => 'parametria_incentive pi ','tipo_join' =>'left', 'conect'=>'u.cargo_id =  pi.cargo_id');

        if (is_null($whereArray)) {
            $where = array('u.estado_id' => 1);
        }
        else
        {
            $where=$whereArray;
        }
        return $this->Crud_model->obtenerRegistros('produccion_metavisita',$where,$select, NULL,NULL, $joins,$group);
    }
    public function GetDatosTotal(){
        return $this->Crud_model->obtenerRegistros('produccion_cumplimiento',null,'*');
    }
    public function GetDatosCumplimiento($where)
    {
        return $this->Crud_model->obtenerRegistros('produccion_cumplimiento',$where);
    }
    public function Insertar($arrayInsertar)
    {
        return $this->Crud_model->agregarRegistro('produccion_cumplimiento',$arrayInsertar);
    }
    public function editar($pArrayActualizar,$id)
    {
        return $this->Crud_model->actualizarRegistro('produccion_cumplimiento',$pArrayActualizar,$id);
    }
   
}

?>
