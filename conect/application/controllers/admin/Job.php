<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job extends MY_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->library("Excel/Excel");
        $this->load->library('Array_conevrt');
        $this->load->model('crud/Crud_usuario');
        
    }
    public function index()
    {
        $this->cargaUsuarios();
    }
    public function buscarUsu()
    {
        var_dump($this->buscarUsuarioAgile('idelvalle1@grupo-link.com','email'));
    }
    public function cargaUsuarios()
    {
        $where = array('r.rol_id' => 7 ,'p.agile_estado_id' => 1);
        $datosUsuario = $this->Crud_usuario->GetDatos($where);
        $conteo = 1;
        foreach ($datosUsuario as $key) {
            if ($conteo == 1) {
                //var_dump($key->usuario_nombre);
                $insertar = array(
                    'name' => $key->usuario_documento,
                    'field_apellido' => array('und' => array('0' => array('value' => $key->usuario_apellido))),
                    'field_nombre' => array('und' => array('0' => array('value' => $key->usuario_nombre))),
                    'mail' => $key->usuario_correo,
                    'pass' => $key->usuario_documento
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
    public function cargarUsuariosdrupalBach()
    {

    }
    public function cargarUsuariosAgileBach()
    {/*
        $where = array('rol_id' => 7 ,'agileestado_id' => 1,'usuario_documento'=>1020717492);
        $datosUsuario = $this->Crud_usuario->GetDatos($where);
        if (!is_null($datosUsuario)) {
            
            foreach ($datosUsuario as $key) {
                $tempo = $this->crearUsuario($this->crearUsuarioAgile($key,'Kioskos',null,"Carga Manual"));
                if(!$tempo['estado'])
                {   
                    $editar = array('agileestado_id' => 4);
                    $busqueda = array('usuario_id' => $key->usuario_id);
                    $this->Crud_usuario->editar($editar,$busqueda);
                    $insertar = array('tabla' => 'agileUsuario','id'=> $key->usuario_id,'mensaje'=>$tempo['mensaje']);
                    $return = array('estado' => true,'carga'=>$tempo['mensaje']);
                    echo json_encode($return, JSON_FORCE_OBJECT);
                }else
                {   
                    $editar = array(
                        'agileestado_id' => 3,
                        'usuario_codigoagile' => json_decode($tempo['mensaje'], true)["id"],
                        'usuario_fechacargaAgile'=>date($this->formatoFecha)
                    );
                    $busqueda = array('usuario_id' => $key->usuario_id);
                    $this->Crud_usuario->editar($editar,$busqueda);
                    $insertar = array('tabla' => 'agileUsuario','id'=> $key->usuario_id,'mensaje'=>$tempo['mensaje']);
                    $return = array('estado' => true,'carga'=>'carga exitosa');
                    echo json_encode($return, JSON_FORCE_OBJECT);
                }
            }
            
        }
        */
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

