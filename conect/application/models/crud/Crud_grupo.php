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
    public function GetDatosGrupo($where,$selct = '*')
    {
        return $this->Crud_model->obtenerRegistros('produccion_usuario',$where,$selct, NULL,NULL, NULL,NULL);
    }
    public function GetDatosVentaGrupo($where)
    {
        $group = 'u.grupo_id';
        $select = 'g.grupo_id,g.grupo_nombre,sum(p.venta_recompra) ventasumaRecompra,sum(p.venta_nuevas) ventasumaNuevo';
        $joins[0]  = array('tabla' => 'produccion_usuario u ','tipo_join' =>'inner', 'conect'=>'p.usuario_id = u.usuario_id');
        $joins[1]  = array('tabla' => 'basica_grupo g ','tipo_join' =>'inner', 'conect'=>'g.grupo_id = u.grupo_id');
        return $this->Crud_model->obtenerRegistros('produccion_venta',$where,$select, NULL,NULL, $joins,$group);
    }
    public function GetdatosQuery($stringwhere,$order)
    {
        $string = 'select grupo_id,grupo_nombre,sum(venta_recompra) ventasumaRecompra,sum(venta_nuevas) ventasumaNuevo from (
        SELECT p.usuario_documento, 
            p.usuario_nombre, 
            p.usuario_apellido, 
            p.usuario_codigounico, 
            c.cargo_nombre, 
            g.grupo_nombre, 
            g.grupo_id, 
            v.venta_mes, 
            v.venta_fecha, 
            max(v.venta_recompra) venta_recompra, 
            max(v.venta_nuevas) venta_nuevas, 
            max(v.venta_fechacarga) venta_fechacarga, 
            v.estado_id, 
            v.usuario_id, 
            v.venta_nomina,
            usuario_codigojefe
        FROM produccion_usuario p INNER JOIN basica_cargo c ON c.cargo_id = p.cargo_id
             INNER JOIN basica_grupo g ON g.grupo_id = p.grupo_id
             INNER JOIN produccion_venta v ON v.usuario_id = p.usuario_id
        WHERE '.$stringwhere.'
        GROUP BY p.usuario_id) tempo
        group by '.$order.'';
        return $this->Crud_model->queryConsulta($string);
    }
}
?>