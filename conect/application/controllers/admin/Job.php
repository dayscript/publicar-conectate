<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job extends MY_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->library("Excel/Excel");
        $this->load->library('Array_conevrt');
        $this->load->model('crud/Crud_usuario');
        $this->load->model('crud/Crud_cumplimiento');
    }
    public function index()
    {
        $this->cargaUsuarios();
        $this->cargarCumplimientosVentas();

    }
    public function buscarUsu()
    {
        var_dump($this->buscarUsuarioAgile('idelvalle1@grupo-link.com','email'));
    }
    public function puntosPorUsuario($documento,$fecha = null)
    {
        $docuemnto = $documento;
        if (is_null($fecha)) {
            $fecha = date('m');
            $fecha = 1;
        }
        /*
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('fecha') == 1) {
        */
                $datos = array();
                $datodCarga =  $this->consultaRest('/api/entities/'.$docuemnto);
                //var_dump($datodCarga["goalvalues"]);
                foreach ($datodCarga['entity']["goalvalues"] as $key) {
                    if (date("m", strtotime($key["date"])) == $fecha) {
                        var_dump($key);
                    }
                }
                //echo json_encode($datodCarga);
        /*
            }
        }
        */
    }
    public function cargarCumplimientosVentas()
    {
        $where = array('v.estado_id' => 1 ,'p.estado_id' => 1);
        $datosUsuario = $this->Crud_cumplimiento->datosPendientecarga($where);
        if (!is_null($datosUsuario)) {
            foreach ($datosUsuario as $key) {
                $envioDatos = array(
                    'value' => number_format((float) $key->metaventa_recompra, 0, '.', ''),
                    'real' => number_format((float) $key->venta_recompra, 0, '.', ''),
                    'goal' => $key->incentive_id_renovacion,
                    'date' => '2017-'.$key->metaventa_mes.'-01'
                );
                $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_renovacion, 'cumplimiento_fecha' => '2017-'.$key->metaventa_mes.'-01',);
                $datosCumplimiento =  $this->Crud_cumplimiento->GetDatosCumplimiento($wherebuscar);
                if (is_null($datosCumplimiento)) {
                    $insertar = array(
                        'usuario_id' => $key->usuario_id, 
                        'tipocumplimiento_id' => $key->incentive_id_renovacion,
                        'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                        'cumplimiento_fecha' => '2017-'.$key->metaventa_mes.'-01',
                        'incentive_id' => $datodCarga["value"]["id"],
                        'cumplimiento_modified'=>$datodCarga["value"]["percentage_modified"],
                        'cumplimiento_weighed'=>$datodCarga["value"]["percentage_weighed"]
                    );
                    $this->Crud_cumplimiento->Insertar($insertar);
                }
                else
                {
                    $where = array('cumplimiento_id' => $datosCumplimiento[0]->cumplimiento_id);
                    $edit = array(
                        'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                        'cumplimiento_modified'=>$datodCarga["value"]["percentage_modified"],
                        'cumplimiento_weighed'=>$datodCarga["value"]["percentage_weighed"]
                    );
                    $this->Crud_cumplimiento->editar($edit,$where);
                }
                $envioDatos = array(
                    'value' => number_format((float) $key->metaventa_nuevas, 0, '.', ''),
                    'real' => number_format((float) $key->venta_nuevas, 0, '.', ''),
                    'goal' => $key->incentive_id_nueva,
                    'date' => '2017-'.$key->metaventa_mes.'-01'
                );
                $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_nueva, 'cumplimiento_fecha' => '2017-'.$key->metaventa_mes.'-01',);
                $datosCumplimiento =  $this->Crud_cumplimiento->GetDatosCumplimiento($wherebuscar);
                if (is_null($datosCumplimiento)) {
                    $insertar = array(
                        'usuario_id' => $key->usuario_id, 
                        'tipocumplimiento_id' => $key->incentive_id_nueva,
                        'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                        'cumplimiento_fecha' => '2017-'.$key->metaventa_mes.'-01',
                        'incentive_id' => $datodCarga["value"]["id"],
                        'cumplimiento_modified'=>$datodCarga["value"]["percentage_modified"],
                        'cumplimiento_weighed'=>$datodCarga["value"]["percentage_weighed"]
                    );
                    $this->Crud_cumplimiento->Insertar($insertar);
                }
                else
                {
                    $where = array('cumplimiento_id' => $datosCumplimiento[0]->cumplimiento_id);
                    $edit = array(
                        'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                        'cumplimiento_modified'=>$datodCarga["value"]["percentage_modified"],
                        'cumplimiento_weighed'=>$datodCarga["value"]["percentage_weighed"]
                    );
                    $this->Crud_cumplimiento->editar($edit,$where);
                }
                $actualiza = array('estado_id' => 2);
                $whereMetas = array('metaventa_id' => $key->metaventa_id);
                $whereVenta = array('venta_id' => $key->venta_id);
                $this->Crud_model->actualizarRegistro('produccion_venta',$actualiza,$whereVenta);
                $this->Crud_model->actualizarRegistro('produccion_metaventa',$actualiza,$whereMetas);
            }
        }
        else
        {
            echo "sin datos";
        }
    }
    public function cargaUsuarios()
    {
        $where = array('r.rol_id' => 7 ,'p.agile_estado_id' => 1);
        $datosUsuario = $this->Crud_usuario->GetDatos($where);
        $conteo = 1;
        if (!is_null($datosUsuario)) {
            foreach ($datosUsuario as $key) {
                    //var_dump($key->usuario_nombre);
                    $insertar = array(
                        'name' => $key->usuario_documento,
                        'field_apellido' => array('und' => array('0' => array('value' => $key->usuario_apellido))),
                        'field_nombre' => array('und' => array('0' => array('value' => $key->usuario_nombre))),
                        'mail' => $key->usuario_correo,
                        'pass' => $key->usuario_codigounico
                    );
                    $conteo = 2;
                    $result =  $this->restDrupal(json_encode($insertar),'post');
                    //var_dump($result->form_errors);
                    if (!isset($result->uid)) {
                        $editar = array('drupal_estado_id' => 3,'drupal_id' => null);
                        $busqueda = array('usuario_id' => $key->usuario_id);
                        $this->Crud_usuario->editar($editar,$busqueda);
                    }else
                    {
                        $editar = array('drupal_id' => $result->uid,'drupal_estado_id' => 2);
                        $busqueda = array('usuario_id' => $key->usuario_id);
                        $this->Crud_usuario->editar($editar,$busqueda);
                    }
                    $tempo = $this->crearUsuario($this->crearUsuarioAgile($key,'Archivo Plano',null,"Carga Manual"));
                    if(!$tempo['estado'])
                    {   
                        $editar = array('agile_estado_id' => 4);
                        $busqueda = array('usuario_id' => $key->usuario_id);
                        $this->Crud_usuario->editar($editar,$busqueda);
                    }else
                    {   
                        $editar = array(
                            'agile_estado_id' => 3,
                            'agile_id' => json_decode($tempo['mensaje'], true)["id"],
                            'agile_fecha'=>date($this->formatoFecha)
                        );
                        $busqueda = array('usuario_id' => $key->usuario_id);
                        $this->Crud_usuario->editar($editar,$busqueda);
                    }
            }
        }
    }
    public function habeasData($numeroId = null)
    {

        if (!is_null($numeroId)) {
            $where = array('vin' => $numeroId);
            $conta = $this->Crud_ventas->GetDatos($where);
            if (!is_null($conta)) {
                $contact1 = $this->buscarUsuarioAgile($conta[0]->mail);
                if ($contact1 != '') {
                    $result = json_decode($contact1, false, 512, JSON_BIGINT_AS_STRING);
                    $result1 =$this->editarContacto($result,'off','habeasData');
                    echo "Gracias por informarnos que no quierees recibir mas comunicados";
                    //var_dump($result1);
                }
            }
        }
    }
    public function borrarDatos()
    {
        $docuemnto  =  '79558338';
        $value = 10;
        for ($i=0; $i <60; $i++) { 
            $datos = $this->eliminarPuntosIncentive($docuemnto,$i);
            var_dump($datos);
        }
    }
    public function buscarUsuario($correo=null)
    {
        var_dump($this->buscarUsuarioAgile('daniel.paez@inxaitcorp.com','email'));
    }
    public function archivos()
    {
        echo $this->listar_archivos(getcwd(),"/File/uploader/courier");
    }
}

