<?php
if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
/**
 * Description of Crud_model
 *
 * @author YARA WEB Developer
 */
class Crud_log extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct(); 
    }
    public function Insertar($tabla=  null,$id=  null,$contenido = null)
    {
        $insertar = array('tabla' => $tabla,'id'=> $id,'mensaje'=>$contenido);
        return $this->Crud_model->agregarRegistro('produccion_log',$insertar);
    }
}
?>