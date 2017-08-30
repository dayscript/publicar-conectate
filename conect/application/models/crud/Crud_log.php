<?php
if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
/**
 * Description of Crud_model
 *
 * @author YARA WEB Developer
 */
class Crud_log extends CI_Model {

    public $ajusteFechalog;
    //constructor de la clase
    public function __construct() {
        parent::__construct(); 
        $this->ajusteFechalog = strtotime($this->Crud_parametria->obtenerParametria('ajusteFecha'),strtotime(date('Y-m-j H:i:s')));
    }
    public function Insertar($tabla=  null,$id=  null,$contenido = null)
    {
        $insertar = array('tabla' => $tabla,'fecha_carga' => date('Y-m-d',$this->ajusteFechalog),'id'=> $id,'mensaje'=>$contenido);
        return $this->Crud_model->agregarRegistro('produccion_log',$insertar);
    }
    public function GetDatos($where)
    {
        return $this->Crud_model->obtenerRegistros('produccion_log',$where,NULL, NULL,'fecha_carga desc', NULL,NULL);
    }
}
?>