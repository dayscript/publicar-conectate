<?php

if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');
class Crud_update extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct();
        
    }
    public function GetDatos($whereArray = null)
    {
        $fecha=date('Y-m-d',$this->ajusteFecha);
        $joins[0]  = array('tabla' => 'basica_estado e ','tipo_join' =>'left', 'conect'=>'e.estado_id = p.estado_id');
        $joins[1]  = array('tabla' => 'basica_genero g ','tipo_join' =>'left', 'conect'=>'p.genero_id = g.genero_id');
        $joins[2]  = array('tabla' => 'basica_ciudad c','tipo_join' =>'left', 'conect'=>'c.ciudad_id = p.ciudad_id');
        $joins[3]  = array('tabla' => 'basica_departamento de ','tipo_join' =>'left', 'conect'=>'de.departamento_id =  c.departamento_id');
        $joins[4]  = array('tabla' => 'basica_pais pa ','tipo_join' =>'left', 'conect'=>'pa.pais_id = de.pais_id');
        $joins[5]  = array('tabla' => 'basica_rol r ','tipo_join' =>'left', 'conect'=>'r.rol_id = p.rol_id');
        $joins[6]  = array('tabla' => 'basica_empresalegal be ','tipo_join' =>'left', 'conect'=>'be.empresalegal_id =  p.empresalegal_id');
        $joins[7]  = array('tabla' => 'basica_posicion bp  ','tipo_join' =>'left', 'conect'=>'bp.posicion_id =  p.posicion_id');
        $joins[8]  = array('tabla' => 'basica_regional br ','tipo_join' =>'left', 'conect'=>'br.regional_id =  c.regional_id');
        $joins[9]  = array('tabla' => 'basica_tipocontrato bt ','tipo_join' =>'left', 'conect'=>'bt.tipocontrato_id =  p.tipocontrato_id');
        $joins[10]  = array('tabla' => 'parametria_incentive pri ','tipo_join' =>'left', 'conect'=>"p.cargo_id =  pri.cargo_id and pri.incentive_fechainicio <=  '".$fecha."' and pri.incentive_fechafin >= '".$fecha."'");
        $joins[11]  = array('tabla' => 'basica_grupo bg ','tipo_join' =>'left', 'conect'=>'bg.grupo_id =  p.grupo_id');
        $joins[12]  = array('tabla' => 'basica_cargo bca ','tipo_join' =>'left', 'conect'=>'bca.cargo_id =  p.cargo_id');
        $joins[13]  = array('tabla' => 'produccion_dominio pd ','tipo_join' =>'left', 'conect'=>'pd.empresalegal_id =  be.empresalegal_id');

        if (is_null($whereArray)) {
            $where = array('estado_id' => 1);
        }
        else
        {
            $where=array('estado_id' => 1)+$whereArray;
            $where=$whereArray;
        }
        return $this->Crud_model->obtenerRegistros('produccion_updateusuario',$where,'*', NULL,NULL, $joins);
    }
    public function datosConsulta($whereArray = null,$select = '*',$group = null)
    {
        $joins[0]  = array('tabla' => 'produccion_usuario u ','tipo_join' =>'inner', 'conect'=>'u.usuario_documento =  p.usuario_documento');
        if (is_null($whereArray)) {
            $where = array('p.estado_id' => 1);
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
