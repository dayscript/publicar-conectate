<?php
if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
/**
 * Description of Crud_model
 *
 * @author YARA WEB Developer
 */
class Crud_campo extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct(); 
    }

    public function GetDatosMetaGrupo($where)
    {
        $select = '*';
        $joins[0]  = array('tabla' => 'basica_campo c ','tipo_join' =>'inner', 'conect'=>'c.tabla_id = p.tabla_id');
        $joins[1]  = array('tabla' => 'basica_tipocampo tc ','tipo_join' =>'inner', 'conect'=>'tc.tipocampo_id =  c.tipocampo_id');
        return $this->Crud_model->obtenerRegistros('basica_tabla',$where,$select, NULL,'c.campo_ordena', $joins);
    }
}
?>