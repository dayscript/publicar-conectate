<?php
if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
/**
 * Description of Crud_model
 *
 * @author YARA WEB Developer
 */
class Crud_liquidacion extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct(); 
    }

    public function GetDatosliquidacion($where)
    {
        $select = 'SUM(p.liquidacion_suma) liquidacion_suma,u.*,g.grupo_nombre';
        $joins[0]  = array('tabla' => 'produccion_usuario u','tipo_join' =>'inner', 'conect'=>'u.usuario_id =  p.usuario_id');
        $joins[1]  = array('tabla' => 'basica_grupo g','tipo_join' =>'inner', 'conect'=>'u.grupo_id =  g.grupo_id');
        return $this->Crud_model->obtenerRegistros('produccion_liquidacion',$where,$select, NULL,'liquidacion_suma desc', $joins,'u.usuario_id');
    }
}
?>