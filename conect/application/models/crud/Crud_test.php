<?php
if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
/**
 * Description of Crud_model
 *
 * @author YARA WEB Developer
 */
class Crud_test extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct(); 
    }

    public function GetDatos($where)
    {
        $joins[0]  = array('tabla' => 'produccion_usuario u ','tipo_join' =>'inner', 'conect'=>'p.usuario_id = u.usuario_id');
        return $this->Crud_model->obtenerRegistros('produccion_test',$where,'*', NULL,NULL, $joins);
    }
    public function editar($pArrayActualizar,$id)
    {
        return $this->Crud_model->actualizarRegistro('produccion_test',$pArrayActualizar,$id);
    }
}
?>