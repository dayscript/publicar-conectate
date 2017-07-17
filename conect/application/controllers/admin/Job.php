<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job extends MY_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->library("Excel/Excel");
        $this->load->library('Array_conevrt');
        $this->load->model('crud/Crud_usuario');
        $this->load->model('crud/Crud_cumplimiento');
        $this->load->model('crud/Crud_grupo');
    }
    public function index()
    {
        $this->cargaUsuarios();
        $this->cargarCumplimientosVentas();
        $this->cargarCumplimientosVisitas();

    }
    public function buscarUsu()
    {
        var_dump($this->buscarUsuarioAgile('idelvalle1@grupo-link.com','email'));
    }
    public function rankingxgrupo()
    {
        /*
        if ($this->input->is_ajax_request()) {
            $docuemnto = $this->input->post("documento", TRUE);
            $dia = $this->input->post("dia", TRUE);
            $mes = $this->input->post("mes", TRUE);
            $ano = $this->input->post("ano", TRUE);
            $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;
            $dia = (strlen($dia) == 1) ? '0'.$dia : $dia;
            */
            $docuemnto = 1020717492;
            $dia = 01;
            $mes = 07;
            $ano = 2017;
            $fecha = $ano.'-'.$mes.'-'.$dia;
            /*
            var_dump($this->Crud_grupo->GetDatosMetaGrupo(7));
            $datodCarga['goal_values'] =  $this->consultaRest('/api/clients/3/dategoalvalues/2017-01-01','GET');
            //var_dump(json_encode($datodCarga['goal_values']['goal_values']));
            foreach ($datodCarga['goal_values']['goal_values'] as $key) {
                var_dump(($key));
                echo "<br>";
            }
            */
            $suma =0;

            $return = array('estado' => true,'carga'=>$suma);
            echo json_encode($return, JSON_FORCE_OBJECT);
        //}   
    }
    public function puntosPorUsuario()
    {
        if ($this->input->is_ajax_request()) {
            $docuemnto = $this->input->post("documento", TRUE);
            $dia = $this->input->post("dia", TRUE);
            $mes = $this->input->post("mes", TRUE);
            $ano = $this->input->post("ano", TRUE);
            $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;
            $dia = (strlen($dia) == 1) ? '0'.$dia : $dia;
            //$mes =  01;
            $fecha = $ano.'-'.$mes.'-'.$dia;
            $datodCarga =  $this->consultaRest('/api/entities/'.$docuemnto);
            $suma =  0;
            foreach ($datodCarga['entity']["goalvalues"] as $key) 
            {
                if (date("m", strtotime($key["date"])) == date("m", strtotime($fecha))) {
                    $suma = $suma+ $key["percentage_weighed"];
                }
            }
            $return = array('estado' => true,'carga'=>$suma);
            echo json_encode($return, JSON_FORCE_OBJECT);
        }
    }
    public function metasPorUsuario()
    {
        if ($this->input->is_ajax_request()) {
            $docuemnto = $this->input->post("documento", TRUE);
            //$docuemnto = 1024839411;
            $where = array('p.usuario_documento' => $docuemnto);
            $datosUsuario = $this->Crud_usuario->GetDatos($where);
            $dia = $this->input->post("dia", TRUE);
            $mes = $this->input->post("mes", TRUE);
            $ano = $this->input->post("ano", TRUE);
            $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;
            $dia = (strlen($dia) == 1) ? '0'.$dia : $dia;
            //$dia =  01;
            //$mes =  01;
            //$ano =  2017;
            $fecha = $ano.'-'.$mes.'-'.$dia;
            $datodCarga =  $this->consultaRest('/api/entities/'.$docuemnto);
            $enviodatos = array();
            $contador = 0;
            $suma =  0;
            if (!isset($datodCarga["status"])) 
            {
                foreach ($datodCarga['entity']["goalvalues"] as $key) 
                {
                    if (date("m", strtotime($key["date"])) == date("m", strtotime($fecha))) {
                        $datoIcentive = $this->buscarTipoDeCarga($key["goal_id"],$datosUsuario[0]->cargo_id);
                        $arrayName = array(
                            'menu' => $datoIcentive[0]->cargomenu_nombre, 
                            'menuid' => $datoIcentive[0]->cargomenu_id, 
                            'indicarod' => $datoIcentive[0]->cargosubmenu_nombre, 
                            'meta' => $key["value"], 
                            'cumplimiento' => $key["real"], 
                            'puntos' => $key["percentage_weighed"],
                            'date'=> $key["date"]
                        );
                        $enviodatos[$contador] = $arrayName;
                        $contador = $contador + 1;
                        $suma = $suma+$key["percentage_weighed"];
                        
                    }
                }
            }
            $datosIncentive = $this->Crud_parametria->datosMenuIncentive();
            foreach ($datosIncentive as $key1) {
                switch ($key1->cargomenu_id) {
                    case '1':
                        $bandera = true;
                        $conta = 0;
                        while ($bandera and count($enviodatos) > $conta ) {
                            if ($enviodatos[$conta]["menuid"] == $key1->cargomenu_id) {
                                $bandera = false;
                            }
                            $conta = $conta+1;
                        }
                        if ($bandera) {
                            $whereTempo = array('metaventa_mes' => $mes,'usuario_id'=> $datosUsuario[0]->usuario_id);
                            $datosDemetas = $this->Crud_model->obtenerRegistros('produccion_metaventa',$whereTempo);
                            if (!is_null($datosDemetas)) {
                                $merta = $datosDemetas[0]->metaventa_recompra;
                            }
                            else
                            {
                                $merta =0;
                            }
                            $arrayName = array(
                                'menu' => $key1->cargomenu_nombre, 
                                'menuid' => $key1->cargomenu_id, 
                                'indicarod' => $key1->cargosubmenu_nombre, 
                                'meta' => $merta, 
                                'cumplimiento' => 0, 
                                'puntos' => 0,
                                'date'=> ''
                            );
                            $enviodatos[$contador] = $arrayName;
                            $contador = $contador + 1;
                        }
                    break;
                    case '2':
                        $bandera = true;
                        $conta = 0;
                        while ($bandera and count($enviodatos) > $conta ) {
                            if ($enviodatos[$conta]["menuid"] == $key1->cargomenu_id) {
                                $bandera = false;
                            }
                            $conta = $conta+1;
                        }
                        if ($bandera) {
                            $arrayName = array(
                                'menu' => $key1->cargomenu_nombre, 
                                'menuid' => $key1->cargomenu_id, 
                                'indicarod' => $key1->cargosubmenu_nombre, 
                                'meta' => '', 
                                'cumplimiento' => 0, 
                                'puntos' => 0,
                                'date'=> ''
                            );
                            $enviodatos[$contador] = $arrayName;
                            $contador = $contador + 1;
                        }
                    break;
                    case '3':
                        $bandera = true;
                        $conta = 0;
                        while ($bandera and count($enviodatos) > $conta ) {
                            if ($enviodatos[$conta]["menuid"] == $key1->cargomenu_id) {
                                $bandera = false;
                            }
                            $conta = $conta+1;
                        }
                        if ($bandera) {
                            $arrayName = array(
                                'menu' => $key1->cargomenu_nombre, 
                                'menuid' => $key1->cargomenu_id, 
                                'indicarod' => $key1->cargosubmenu_nombre, 
                                'meta' => '', 
                                'cumplimiento' => 0, 
                                'puntos' => 0,
                                'date'=> ''
                            );
                            $enviodatos[$contador] = $arrayName;
                            $contador = $contador + 1;
                        }
                    break;
                    case '4':
                        $bandera = true;
                        $conta = 0;
                        while ($bandera and count($enviodatos) > $conta ) {
                            if ($enviodatos[$conta]["menuid"] == $key1->cargomenu_id) {
                                $bandera = false;
                            }
                            $conta = $conta+1;
                        }
                        if ($bandera) {
                            $arrayName = array(
                                'menu' => $key1->cargomenu_nombre, 
                                'menuid' => $key1->cargomenu_id, 
                                'indicarod' => $key1->cargosubmenu_nombre, 
                                'meta' => '', 
                                'cumplimiento' => 0, 
                                'puntos' => 0,
                                'date'=> ''
                            );
                            $enviodatos[$contador] = $arrayName;
                            $contador = $contador + 1;
                        }
                    break;
                    case '5':
                        $bandera = true;
                        $conta = 0;
                        while ($bandera and count($enviodatos) > $conta ) {
                            if ($enviodatos[$conta]["menuid"] == $key1->cargomenu_id) {
                                $bandera = false;
                            }
                            $conta = $conta+1;
                        }
                        if ($bandera) {
                            $arrayName = array(
                                'menu' => $key1->cargomenu_nombre, 
                                'menuid' => $key1->cargomenu_id, 
                                'indicarod' => $key1->cargosubmenu_nombre, 
                                'meta' => '', 
                                'cumplimiento' => 0, 
                                'puntos' => 0,
                                'date'=> ''
                            );
                            $enviodatos[$contador] = $arrayName;
                            $contador = $contador + 1;
                        }
                    break;
                }
            }
            $htmlText = $this->cargarHtml($enviodatos,$suma);
            $return = array('estado' => true,'carga'=>$htmlText);
            echo json_encode($return, JSON_FORCE_OBJECT);
        }
    }
    public function buscarTipoDeCarga($goal_id,$cargo_id)
    {
        $datoAjustedo = (($goal_id/5)-intval(($goal_id/5)))*100;
        switch (strval($datoAjustedo)) 
        {
            case 20:
                /*
                $where = array('c.cargo_id' => $cargo_id);
                $datosIncentive = $this->Crud_parametria->datosIncentive($where,'incentive_id_renovacion');
                */
                $where = array('c.cargosubmenu_id' => 1);
                $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
            break;
            case 40:

                $where = array('c.cargosubmenu_id' => 2);
                $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
            break;
            case 60:

                $where = array('c.cargosubmenu_id' => 3);
                $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
            break;
            case 80:

                $where = array('c.cargosubmenu_id' => 4);
                $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
            break;
            case 0:
                $where = array('c.cargosubmenu_id' => 5);
                $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
            break;
        }
        return $datosIncentive;
    }
    public function cargarHtml($arrayDatos,$suma)
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        $bandera = true;
        $html = '<table class="rendimiento" id="rendimiento" width="100%">
        <thead>
        <tr><th>indicador</th><th width="15%">meta</th><th width="15%">cumplimiento</th><th width="15%">Puntos</th></tr>
        </thead>
        <tbody>';
        foreach ($arrayDatos as $key) {
            switch ($key['menuid']) {
                case '1':
                    if ($bandera) {
                        $html = $html.'
                    <tr>
                        <td colspan="4" class="group">'.$key['menuid'].'. '.$key['menu'].'</td>
                    </tr>
                    <tr>
                        <td>'.$key['indicarod'].'</td>
                        <td align="">'.money_format('%(#10n',(int) $key['meta']).'</td>
                        <td align="">'.money_format('%(#10n',(int) $key['cumplimiento']).'</td>
                        <td align="">'.$key['puntos'].'</td>
                    </tr>';
                        $bandera= false;
                    }
                    else
                    {
                        $html = $html.'
                    <tr>
                        <td>'.$key['indicarod'].'</td>
                        <td align="">'.money_format('%(#10n',(int) $key['meta']).'</td>
                        <td align="">'.money_format('%(#10n',(int) $key['cumplimiento']).'</td>
                        <td align="">'.$key['puntos'].'</td>
                    </tr>';
                    }
                break;
                case '4':
                    $html = $html.'
                        <tr>
                            <td colspan="4" class="group">'.$key['menuid'].'. '.$key['menu'].'</td>
                        </tr>
                        <tr>
                            <td>'.$key['indicarod'].'</td>
                            <td align="">'.money_format('%(#10n',(int) $key['meta']).'</td>
                            <td align="">'.money_format('%(#10n',(int) $key['cumplimiento']).'</td>
                            <td align="">'.$key['puntos'].'</td>
                        </tr>';
                break;

                default:
                        $html = $html.'
                        <tr>
                            <td colspan="4" class="group">'.$key['menuid'].'. '.$key['menu'].'</td>
                        </tr>
                        <tr>
                            <td>'.$key['indicarod'].'</td>
                            <td align="">'.(int) $key['meta'].'</td>
                            <td align="">'.(int) $key['cumplimiento'].'</td>
                            <td align="">'.$key['puntos'].'</td>
                        </tr>';
                    
                break;
            }
        }
        $html =  $html.'<tr><td class="total"> Total </td><td colspan="4" class="total percent" align="">'.$suma.'</td></tr></tbody></table>';
        return $html;
    }
    public function cargarCumplimientoGrupo()
    {
        var_dump($this->Crud_grupo->GetDatosMetaGrupo(7));
        $datodCarga['goal_values'] =  $this->consultaRest('/api/clients/3/dategoalvalues/2017-01-01','GET');
        //var_dump(json_encode($datodCarga['goal_values']['goal_values']));
        foreach ($datodCarga['goal_values']['goal_values'] as $key) {
            var_dump(($key));
            echo "<br>";
        }
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
                //cargar cumplimiento grupal
                $metas = $this->Crud_grupo->GetDatosMetaGrupo($key->metaventa_mes,$key->grupo_id);
                $venta = $this->Crud_grupo->GetDatosVentaGrupo($key->metaventa_mes,$key->grupo_id);
                $metasTotal = (int) $metas[0]->metasumaRecompra+(int) $metas[0]->metasumaNuevo;
                $ventatotal = (int) $venta[0]->ventasumaRecompra +(int) $venta[0]->ventasumaNuevo;
                $envioDatos = array(
                    'value' => $metasTotal,
                    'real' => $ventatotal,
                    'goal' => $key->incentive_id_grupo,
                    'date' => '2017-'.$key->metaventa_mes.'-01'
                );
                $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_grupo, 'cumplimiento_fecha' => '2017-'.$key->metaventa_mes.'-01',);
                $datosCumplimiento =  $this->Crud_cumplimiento->GetDatosCumplimiento($wherebuscar);
                if (is_null($datosCumplimiento)) {
                    $insertar = array(
                        'usuario_id' => $key->usuario_id, 
                        'tipocumplimiento_id' => $key->incentive_id_grupo,
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
            echo "sin datos Ventas";
        }
    }
    public function cargarCumplimientosVisitas()
    {
        $where = array('v.estado_id' => 1 ,'p.estado_id' => 1);
        $datosUsuario = $this->Crud_cumplimiento->datosPendientecargaVisitas($where);
        if (!is_null($datosUsuario)) {
            foreach ($datosUsuario as $key) {
                $envioDatos = array(
                    'value' => number_format((float) $key->metavisita_totales, 0, '.', ''),
                    'real' => number_format((float) $key->visita_total, 0, '.', ''),
                    'goal' => $key->incentive_id_citas,
                    'date' => '2017-'.$key->metavisita_mes.'-01'
                );
                $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_citas, 'cumplimiento_fecha' => '2017-'.$key->metavisita_mes.'-01',);
                $datosCumplimiento =  $this->Crud_cumplimiento->GetDatosCumplimiento($wherebuscar);
                if (is_null($datosCumplimiento)) {
                    $insertar = array(
                        'usuario_id' => $key->usuario_id, 
                        'tipocumplimiento_id' => $key->incentive_id_citas,
                        'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                        'cumplimiento_fecha' => '2017-'.$key->metavisita_mes.'-01',
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
                $whereMetas = array('metavisita_id' => $key->metavisita_id);
                $whereVenta = array('visita_id' => $key->visita_id);
                $this->Crud_model->actualizarRegistro('produccion_visita',$actualiza,$whereVenta);
                $this->Crud_model->actualizarRegistro('produccion_metavisita',$actualiza,$whereMetas);
            }
        }
        else
        {
            echo "sin datos Ventas";
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

