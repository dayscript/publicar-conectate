<?php
if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
/**
 * Description of Crud_model
 *
 * @author YARA WEB Developer
 */
class Crud_grupo extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct(); 
    }

    public function GetDatosMetaGrupo($where)
    {
        $group = 'u.grupo_id';
        $select = 'g.grupo_id,g.grupo_nombre,sum(p.metaventa_recompra) metasumaRecompra,sum(p.metaventa_nuevas) metasumaNuevo';
        $joins[0]  = array('tabla' => 'produccion_usuario u ','tipo_join' =>'inner', 'conect'=>'p.usuario_id = u.usuario_id');
        $joins[1]  = array('tabla' => 'basica_grupo g ','tipo_join' =>'inner', 'conect'=>'g.grupo_id = u.grupo_id');
        return $this->Crud_model->obtenerRegistros('produccion_metaventa',$where,$select, NULL,NULL, $joins,$group);
    }
    public function GetDatosMetaGrupoFijo($where)
    {
        return $this->Crud_model->obtenerRegistros('produccion_metagrupo',$where,'*', NULL,NULL, NULL,NULL);
    }
    public function GetDatosVentaGrupo($where)
    {
        $group = 'u.grupo_id';
        $select = 'g.grupo_id,g.grupo_nombre,sum(p.venta_recompra) ventasumaRecompra,sum(p.venta_nuevas) ventasumaNuevo';
        $joins[0]  = array('tabla' => 'produccion_usuario u ','tipo_join' =>'inner', 'conect'=>'p.usuario_id = u.usuario_id');
        $joins[1]  = array('tabla' => 'basica_grupo g ','tipo_join' =>'inner', 'conect'=>'g.grupo_id = u.grupo_id');
        return $this->Crud_model->obtenerRegistros('produccion_venta',$where,$select, NULL,NULL, $joins,$group);
    }
}
?>