<?php

if (!defined('BASEPATH'))
    exit('No ingrese directamente es este script');

/**
 * Description of Crud_model
 *
 * @author YARA WEB Developer
 */
class Crud_model extends CI_Model {

    //constructor de la clase
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }


    public function obtenerRegistros($pTabla, $pArrayWhere = NULL, $pSelect = NULL, $pLimit = NULL, $pOrder = NULL,$joins = NULL,$group = NULL) {
        //verifico el parámetro para preparar la consulta SELECT
        if ($joins != NULL) {
            for ($i=0; $i < count($joins); $i++) { 
                //echo $joins[$i]['conect'];
                $this->db->join($joins[$i]['tabla'], $joins[$i]['conect'], $joins[$i]['tipo_join']);
            }
            //var_dump($joins);
        }
        if ($pSelect != NULL) {
            if (is_int(strpos($pSelect, 'distinct'))) {                
                $this->db->distinct();
                $pSelect = substr($pSelect,strpos($pSelect, 'distinct')+8,1000);
                $this->db->select($pSelect);
                $this->db->select($pSelect);
            }
            else
            {
                $this->db->select($pSelect);
            }
            
        }//fin del if
        //verifico el parámetro para preparar la consulta WHERE
        if ($pArrayWhere != NULL) {
            $sqlRegistros = $this->db->where($pArrayWhere);
        }//fin del if
        if (isset($pLimit)):
            if (is_array($pLimit)):
                $this->db->limit($pLimit[0], $pLimit[1]);
            else:
                $this->db->limit($pLimit);
            endif;
        endif;
        //group by
        if (!is_null($group)) 
        {
            $this->db->select($group);
            $this->db->group_by($group); 
        }
        //Order by
        //si hay valor de order
        if ($pOrder):
            $this->db->order_by($pOrder);
        endif;
        //realizo la consulta 
        $sqlRegistros = $this->db->get($pTabla.' p');
        //validamos si existen registros
        if ($sqlRegistros->num_rows() > 0) {
            //iniciamos la iteracion de los datos
            foreach ($sqlRegistros->result() as $filaTabla):
                $dataRegistro[] = $filaTabla;
            endforeach;
        }//fin del if
        else {
            //si no se encuentra ningún resultado en la consulta
            //se envia un arreglo vacio
            $dataRegistro = null;
        }
        //devolvemos la data
        return $dataRegistro;
    }
    public function agregarRegistro($pTabla, $pArrayInsert) {
        //ejecuto la inserción
        return $insertar = $this->db->insert($pTabla, $pArrayInsert);
    }
    public function agregarRegistroRetId($pTabla, $pArrayInsert) {
        //ejecuto la inserción
        $this->db->insert($pTabla, $pArrayInsert);
        return $this->db->insert_id();
    }
    public function agregarRegistroMultiple($pTabla, $pArrayInsert) {
        //creo la variable de retorno
        $valorRetorno = null;
        //ejecuto la inserción
        $valorRetorno = $this->db->insert_batch($pTabla, $pArrayInsert);

        //devolvemos la variable de retorno
        return $valorRetorno;
    }
     public function actualizarRegistro($pTabla, $pArrayActualizar, $pArrayWhere) {

        //hago la actualización
        $actualizar = $this->db->where($pArrayWhere);
        return $actualizar = $this->db->update($pTabla, $pArrayActualizar);
    }

}

?>