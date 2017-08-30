<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job extends MY_Controller {


    public function __construct() 
    {
        parent::__construct();
        $this->load->library("Excel/Excel");
        $this->load->library('Array_conevrt');
        $this->load->model('crud/Crud_usuario');
        $this->load->model('crud/Crud_cumplimiento');
        $this->load->model('crud/Crud_grupo');
        $this->load->model('crud/Crud_test');
    }
    public function index()
    {
        $jobGeneral = $this->Crud_parametria->obtenerParametria('jobGeneral');
        echo date('Y-m-d H:i:s',$this->ajusteFecha);
        echo "<br>";
        if ($jobGeneral == '0') {
            echo "job inactivo";
        }else
        {
            if ($jobGeneral == '-1') {
                $enviar  = array(
                    'usuarios' => $this->cargaUsuarios(),
                    'ventas' => $this->cargarCumplimientosVentas(),
                    'visitas' => $this->cargarCumplimientosVisitas(),
                    'grupos' => $this->cargarCumplimientoGrupo(),
                    'test' => $this->cargarCumplimientoTest(),
                    'fecha'=> date('Y-m-d',$this->ajusteFecha)
                );
                $this->Crud_log->Insertar('Ejecucion Job',0,json_encode($enviar));
            }
            else
            {
                $enviar  = array(
                    'usuarios' => $this->cargaUsuarios($jobGeneral),
                    'ventas' => $this->cargarCumplimientosVentas($jobGeneral),
                    'visitas' => $this->cargarCumplimientosVisitas($jobGeneral),
                    'grupos' => $this->cargarCumplimientoGrupo($jobGeneral),
                    'test' => $this->cargarCumplimientoTest($jobGeneral),
                    'fecha'=> date('Y-m-d',$this->ajusteFecha)
                );
                $this->Crud_log->Insertar('Ejecucion Job',0,json_encode($enviar));
            }
        }
    }
    public function rankingxgrupo()
    {
        if ($this->input->is_ajax_request()) {
            $datos = $this->rankingxgrupoxMes('07');
            $html = '';
            for ($i=1; $i < 6; $i++) { 
                $html =$html .'<br>'. $this->cargarHtmlRanking($datos["cargo".$i."Final"]);
            }
            $return = array('estado' => true,'carga'=>$html);
            echo json_encode($return, JSON_FORCE_OBJECT);
        }
    }
    
    public function cargarHtmlSelect($mes)
    {
        $html ='<select name="mes" id="mes" class="mes" >
                  <option value="">Selecione un mes</option>';
        for ($i=7; $i <= 12; $i++) { 
            if ($mes == $i) {
                $carga= 'selected';
            }
            else
            {
                $carga= '';
            }
            $html = $html.'<option value="'.$i.'" '.$carga.'>'.$this->traerNombremes($i).'</option>';
        }
        $html =$html.'</select> ';
        return $html;
    }
    
    public function cargarHtmlRanking($arrayDatos)
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        $bandera = true;
        $html = '<div class="encabezadoTituloGrupo">'.$arrayDatos[0]['datosUsuario']->cargo_nombre.'</div><table class="responsive rendimientoTotal" border="0" cellpadding="1" cellspacing="1" style="width:100%">
        <thead>
            <tr>
                <th width="40%" class="encabezado">Documento</th>
                <th class="encabezado">Grupo</th>
                <th class="encabezado">Nombre</th>
                <th class="encabezado">Puntos</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($arrayDatos as $key) {
            $gruponombre = (isset($key['datosUsuario'])) ? $key['datosUsuario']->grupo_nombre : '' ;
            $usuario_nombre = (isset($key['datosUsuario'])) ? $key['datosUsuario']->usuario_nombre : '' ;
            $html = $html.'
                        <tr>
                            <td height="36">'.$key['identification'].'</td>
                            <td>'.$gruponombre.'</td>
                            <td>'.$usuario_nombre.'</td>
                            <td align="right" class="puntos">'.number_format($key['suma'],1).'</td>
                        </tr>';
        }
        $html =  $html.'</tbody></table>';
        return $html;
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
            if (!isset($datodCarga["message"])) {
                foreach ($datodCarga['entity']["goalvalues"] as $key) 
                {
                    if (date("m", strtotime($key["date"])) == date("m", strtotime($fecha))) {
                        //var_dump($key);
                        $suma = $suma+ $key["percentage_weighed"];
                    }
                }
                $suma = number_format($suma, 1);
            }
            $return = array('estado' => true,'carga'=>$suma);
            echo json_encode($return, JSON_FORCE_OBJECT);
        }
    }
    public function metasPorUsuario($carga = null)
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
            //$mes =  '07';
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
                            $whereTempo = array('cargosubmenu_id' => $key1->cargosubmenu_id);
                            $datosTempoUno = $this->Crud_parametria->datosMenuIncentive($whereTempo);
                            if (!is_null($datosDemetas)) {
                                $merta = $datosDemetas[0]->metaventa_recompra;
                                $merta1 = $datosDemetas[0]->metaventa_nuevas;
                            }
                            else
                            {
                                $merta =0;
                                $merta1 = 0;
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
                            $whereTempo = array('cargosubmenu_id' => $key1->cargosubmenu_id+1);
                            $datosTempoUno = $this->Crud_parametria->datosMenuIncentive($whereTempo);
                            $arrayName = array(
                                'menu' => $key1->cargomenu_nombre, 
                                'menuid' => $key1->cargomenu_id, 
                                'indicarod' => $datosTempoUno[0]->cargosubmenu_nombre, 
                                'meta' => $merta1, 
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
            if (is_null($carga)) {
                $enviodatos = $this->ordenar($enviodatos);
                $htmlText = $this->cargarHtml($enviodatos,$suma,$mes);
            }
            else
            {
                $enviodatos = $this->ordenar($enviodatos);
                $htmlText = $this->cargarHtmlComprimido($enviodatos,$suma);   
            }
            $return = array('estado' => true,'carga'=>$htmlText);
            echo json_encode($return, JSON_FORCE_OBJECT);
        }
    }
    public function ordenar($ordenar)
    {
        $datos = array();
        $datos1 = array();
        $contador =1;
        $bandera = true;
        $tempo =0;
        $contadorArray = 1;
        while ($contador <= 5) 
        { 
            for ($i=0; $i < count($ordenar); $i++) { 
                if ($ordenar[$i]['menuid'] == $contador) 
                {
                    if ($ordenar[$i]['menuid']  != 1) 
                    {
                        $datos[$contadorArray] = $ordenar[$i];
                        $ordenar[$i] = null;
                        $contadorArray = $contadorArray+1;
                    }
                    else
                    {
                        if ($tempo == 0) {
                            $datos[$contadorArray] = $ordenar[$i];
                            $ordenar[$i] = null;
                            $contadorArray = $contadorArray+1;
                            $tempo =1;
                        }
                        else
                        {
                            
                            $datos[$contadorArray] = $ordenar[$i];
                            $ordenar[$i] = null;
                            $contadorArray = $contadorArray+1;
                            /*
                            $datos[$contadorArray-1]["indicarod"] = 'Presupuesto de venta';
                            $datos[$contadorArray-1]["meta"] = (int) $datos[$contadorArray-1]["meta"] +(int) $ordenar[$i]['meta'];
                            $datos[$contadorArray-1]["cumplimiento"] = (int) $datos[$contadorArray-1]["cumplimiento"]+(int) $ordenar[$i]['cumplimiento'];
                            $datos[$contadorArray-1]["puntos"] = (int) $datos[$contadorArray-1]["puntos"]+(int) $ordenar[$i]['puntos'];
                            */
                        }
                    }
                    
                }
            }
            $contador=$contador+1;
        }
        return $datos;
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
    public function cargarHtmlComprimido($arrayDatos,$suma)
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        $bandera = true;
        $html = '<table class="rendimientoMini" id="rendimientoMini"  border="0" cellpadding="1" cellspacing="1" style="width:320px">
        <thead>
        <tr>
            <th width="200" class="encabezado">Indicador</th>
            <th class="encabezado">Puntos</th>
        </tr>
        </thead>
        <tbody>';
        foreach ($arrayDatos as $key) {
            switch ($key['menuid']) {
                case '1':
                    if ($bandera) {
                        $html = $html.'
                    <tr>
                        <td colspan="2" class="group">'.$key['menuid'].'. '.$key['menu'].'</td>
                    </tr>
                    <tr>
                        <td height="36">'.$key['indicarod'].'</td>
                        <td align="right" class="puntos" center>'.number_format($key['puntos'],1).'</td>
                    </tr>';
                        $bandera= false;
                    }
                    else
                    {
                        $html = $html.'
                    <tr>
                        <td height="36">'.$key['indicarod'].'</td>
                        <td align="right" class="puntos" center>'.number_format($key['puntos'],1).'</td>
                    </tr>';
                    }
                break;
                case '4':
                    $html = $html.'
                        <tr>
                            <td colspan="2" class="group">'.$key['menuid'].'. '.$key['menu'].'</td>
                        </tr>
                        <tr>
                            <td height="36">'.$key['indicarod'].'</td>
                            <td align="right" class="puntos" center>'.number_format($key['puntos'],1).'</td>
                        </tr>';
                break;

                default:
                        $html = $html.'
                        <tr>
                            <td colspan="2" class="group">'.$key['menuid'].'. '.$key['menu'].'</td>
                        </tr>
                        <tr>
                            <td height="36">'.$key['indicarod'].'</td>
                            <td align="right" class="puntos" center>'.number_format($key['puntos'],1).'</td>
                        </tr>';
                    
                break;
            }
        }
        $html =  $html.'<hr><tr><td > Total </td><td colspan="4"  align="" class="puntos">'.number_format($suma,1).'</td></tr></tbody></table>';
        return $html;
    }
    public function cargarHtml($arrayDatos,$suma,$mes)
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        $bandera = true;
        $datosLogVentas = $this->Crud_log->GetDatos(array('tabla' => 'Carga venta'));
        $datosLogVisita = $this->Crud_log->GetDatos(array('tabla' => 'Carga visita'));
        $retVentas = (!is_null($datosLogVentas)) ? '<div>Fecha de corte Ventas '.str_replace('"','',$datosLogVentas[0]->mensaje).'</div>' : '' ;
        $retVisita = (!is_null($datosLogVisita)) ? '<div>Fecha de corte Visita '.str_replace('"','',$datosLogVisita[0]->mensaje).'</div>' : '' ;
        $html = '<div class="rendimientoTotal">'.$this->cargarHtmlSelect($mes).'<table class="responsive " border="0" cellpadding="1" cellspacing="1" style="width:100%">
        <thead>
            <tr>
                <th width="40%" class="encabezado">Indicador</th>
                <th class="encabezado">Meta</th>
                <th class="encabezado">Cumplimiento</th>
                <th class="encabezado">Puntos</th>
            </tr>
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
                        <td height="36">'.$key['indicarod'].'</td>
                        <td>'.money_format('%(#10n',(int) $key['meta']).'</td>
                        <td>'.money_format('%(#10n',(int) $key['cumplimiento']).'</td>
                        <td align="right" class="puntos">'.number_format($key['puntos'],1).'</td>
                    </tr>';
                        $bandera= false;
                    }
                    else
                    {
                        $html = $html.'
                    <tr>
                        <td height="36">'.$key['indicarod'].'</td>
                        <td>'.money_format('%(#10n',(int) $key['meta']).'</td>
                        <td>'.money_format('%(#10n',(int) $key['cumplimiento']).'</td>
                        <td align="right" class="puntos">'.number_format($key['puntos'],1).'</td>
                    </tr>';
                    }
                break;
                case '4':
                    $html = $html.'
                        <tr>
                            <td colspan="4" class="group">'.$key['menuid'].'. '.$key['menu'].'</td>
                        </tr>
                        <tr>
                            <td height="36">'.$key['indicarod'].'</td>
                            <td>'.money_format('%(#10n',(int) $key['meta']).'</td>
                            <td>'.money_format('%(#10n',(int) $key['cumplimiento']).'</td>
                            <td align="right" class="puntos">'.number_format($key['puntos'],1).'</td>
                        </tr>';
                break;

                default:
                        $html = $html.'
                        <tr>
                            <td colspan="4" class="group">'.$key['menuid'].'. '.$key['menu'].'</td>
                        </tr>
                        <tr>
                            <td height="36">'.$key['indicarod'].'</td>
                            <td>'.(int) $key['meta'].'</td>
                            <td>'.(int) $key['cumplimiento'].'</td>
                            <td align="right" class="puntos">'.number_format($key['puntos'],1).'</td>
                        </tr>';
                    
                break;
            }
        }
        $html =  $html.'<tr><td class="encabezado" colspan="3"> Total </td><td class="encabezado puntos" align="">'.number_format($suma,1).'</td></tr></tbody></table>'.$retVentas.$retVisita.'</div>';
        return $html;
    }
    public function cargarCumplimientoGrupo($fecha = null)
    {
        if (is_null($fecha)) {
            $fecha=date('m');
        }
        else
        {
            $fecha=date('m',strtotime($fecha));
        }
        $jobGrupo = $this->Crud_parametria->obtenerParametria('jobGrupo');
        if ($jobGrupo == '1') {
            $mes =(string) $fecha;
            $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;
            $datosUsuario = $this->Crud_usuario->GetDatos(array('p.estado_id' => 1,'p.rol_id' => 7));
            if (!is_null($datosUsuario)) {
                foreach ($datosUsuario as $key) 
                {
                    $where = array('p.metagrupo_mes' => $mes,'p.grupo_id' => $key->grupo_id); 
                    $metas = $this->Crud_grupo->GetDatosMetaGrupoFijo($where);
                    $where = array('p.venta_mess' => $mes,'g.grupo_id' => $key->grupo_id); 
                    $venta = $this->Crud_grupo->GetdatosQuery($mes,$key->grupo_id);
                    if (!is_null($metas) and !is_null($venta)) {
                        $metasTotal = (int) $metas[0]->metagrupo_meta;
                        $ventatotal = (int) $venta[0]['ventasumaRecompra'] +(int) $venta[0]['ventasumaNuevo'];
                        $envioDatos = array(
                            'value' => $metasTotal,
                            'real' => $ventatotal,
                            'goal' => $key->incentive_id_grupo,
                            'date' => '2017-'.$mes.'-01'
                        );
                        $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                        $this->Crud_log->Insertar('meta incentive',$key->usuario_id,json_encode($datodCarga));
                        $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_grupo, 'cumplimiento_fecha' => '2017-'.$mes.'-01',);
                        $datosCumplimiento =  $this->Crud_cumplimiento->GetDatosCumplimiento($wherebuscar);
                        if (is_null($datosCumplimiento)) {
                            $insertar = array(
                                'usuario_id' => $key->usuario_id, 
                                'tipocumplimiento_id' => $key->incentive_id_grupo,
                                'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                                'cumplimiento_fecha' => '2017-'.$mes.'-01',
                                'incentive_id' => $datodCarga["value"]["id"],
                                'cumplimiento_modified'=>$datodCarga["value"]["percentage_modified"],
                                'cumplimiento_weighed'=>$datodCarga["value"]["percentage_weighed"]
                            );
                            //var_dump($insertar);
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
                        if ($key->cargo_id == 6 || $key->cargo_id == 7) {
                            $ventatotal = (int) $venta[0]['ventasumaRecompra'];
                            $envioDatos = array(
                                'value' => $metasTotal,
                                'real' => $ventatotal,
                                'goal' => $key->incentive_id_renovacion,
                                'date' => '2017-'.$mes.'-01'
                            );
                            $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                            $this->Crud_log->Insertar('meta incentive',$key->usuario_id,json_encode($datodCarga));
                            $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_renovacion, 'cumplimiento_fecha' => '2017-'.$mes.'-01',);
                            if (is_null($datosCumplimiento)) 
                            {
                                $insertar = array(
                                    'usuario_id' => $key->usuario_id, 
                                    'tipocumplimiento_id' => $key->incentive_id_renovacion,
                                    'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                                    'cumplimiento_fecha' => '2017-'.$mes.'-01',
                                    'incentive_id' => $datodCarga["value"]["id"],
                                    'cumplimiento_modified'=>$datodCarga["value"]["percentage_modified"],
                                    'cumplimiento_weighed'=>$datodCarga["value"]["percentage_weighed"]
                                );
                                //var_dump($insertar);
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
                            $ventatotal = (int) $venta[0]['ventasumaNuevo'];
                            $envioDatos = array(
                                'value' => $metasTotal,
                                'real' => $ventatotal,
                                'goal' => $key->incentive_id_nueva,
                                'date' => '2017-'.$mes.'-01'
                            );
                            $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                            $this->Crud_log->Insertar('meta incentive',$key->usuario_id,json_encode($datodCarga));
                            $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_nueva, 'cumplimiento_fecha' => '2017-'.$mes.'-01',);
                            if (is_null($datosCumplimiento)) {
                                $insertar = array(
                                    'usuario_id' => $key->usuario_id, 
                                    'tipocumplimiento_id' => $key->incentive_id_nueva,
                                    'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                                    'cumplimiento_fecha' => '2017-'.$mes.'-01',
                                    'incentive_id' => $datodCarga["value"]["id"],
                                    'cumplimiento_modified'=>$datodCarga["value"]["percentage_modified"],
                                    'cumplimiento_weighed'=>$datodCarga["value"]["percentage_weighed"]
                                );
                                //var_dump($insertar);
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
                        }
                    }
                    
                }
            }
            return true;
        }
        else
        {
            echo "no esta activo grupo";
            echo "<br>";
            return false;
        }
    }
    public function cargarCumplimientosVentas($fecha = null)
    {
        if (is_null($fecha)) {
            $fecha=date('Y-m-d',$this->ajusteFecha);
        }
        $where = array('v.estado_id' => 1 ,'v.venta_fechacarga' => $fecha);
        $datosUsuario = $this->Crud_cumplimiento->datosPendientecarga($where,'max(`p`.`metaventa_id`) metaventa_id, p.metaventa_mes, p.metaventa_fecha, max(p.metaventa_recompra) metaventa_recompra, max(p.metaventa_nuevas) metaventa_nuevas, p.usuario_id, p.estado_id, p.metaventa_fechacarga, p.metaventa_nomina,v.*,u.*,pi.*','v.usuario_id');
        if (!is_null($datosUsuario)) {
            foreach ($datosUsuario as $key) {
                $envioDatos = array(
                    'value' => number_format((float) $key->metaventa_recompra, 0, '.', ''),
                    'real' => number_format((float) $key->venta_recompra, 0, '.', ''),
                    'goal' => $key->incentive_id_renovacion,
                    'date' => '2017-'.$key->metaventa_mes.'-01'
                );
                $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                $this->Crud_log->Insertar('ventas incentive',$key->usuario_id,json_encode($datodCarga));
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
                $this->Crud_log->Insertar('ventas incentive',$key->usuario_id,json_encode($datodCarga));
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
            return true;
        }
        else
        {
            echo "sin datos ventas<br>";
            return false;
        }
    }
    public function cargarCumplimientosVisitas($fecha = null)
    {
        if (is_null($fecha)) {
            $fecha=date('Y-m-d',$this->ajusteFecha);
        }
        $where = array('v.estado_id' => 1 ,'v.visita_fechacarga' => $fecha);
        $datosUsuario = $this->Crud_cumplimiento->datosPendientecargaVisitas($where,'p.metavisita_id, 
                p.metavisita_mes, 
                p.metavisita_fecha, 
                max(p.metavisita_diarias) metavisita_diarias, 
                max(p.metavisita_habiles) metavisita_habiles, 
                max(p.metavisita_totales) metavisita_totales, 
                p.estado_id, 
                p.metavisita_fechacarga, 
                p.usuario_id, 
                p.metavisita_nomina,v.*,u.*,pi.*','v.usuario_id');
        if (!is_null($datosUsuario)) {
            foreach ($datosUsuario as $key) {
                $envioDatos = array(
                    'value' => number_format((float) $key->metavisita_totales, 0, '.', ''),
                    'real' => number_format((float) $key->visita_total, 0, '.', ''),
                    'goal' => $key->incentive_id_citas,
                    'date' => '2017-'.$key->metavisita_mes.'-01'
                );
                $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                $this->Crud_log->Insertar('visitas incentive',$key->usuario_id,json_encode($datodCarga));
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
            return true;
        }
        else
        {
            echo "sin datos Visitas<br>";
            return false;
        }
    }
    public function cargarCumplimientoTest()
    {
        $jobGrupo = $this->Crud_parametria->obtenerParametria('jobTest');
        if ($jobGrupo == '1') {
            $datosTest = $this->totaltest();
            foreach ($datosTest["nodes"] as $key) {
                if ($key["node"]["Evaluado"] == 'Sí') {
                    //var_dump(json_encode($key["node"]));
                    //echo('<br>');
                    $where = array('p.usuario_documento' => $key["node"]["Nombre"]);
                    $datosusuario = $this->Crud_usuario->GetDatos($where);
                    if (!is_null($datosusuario)) {
                        $fecha = explode(',', $key["node"]["Date finished"]); 
                        $mes = $this->retornoMesxString($fecha[1]);
                        $where = array('p.usuario_id' => $datosusuario[0]->usuario_id,'p.test_fecha'=>date('Y').'-'.$mes.'-'.'01');
                        $registroExiste = $this->Crud_model->obtenerRegistros('produccion_test',$where);
                        if (is_null($registroExiste)) {
                            $insert = array(
                                'usuario_id' => $datosusuario[0]->usuario_id, 
                                'test_fecha'=> (date('Y').'-'.$mes.'-'.'01'), 
                                'estado_id'=> 1, 
                                'test_valores'=> $key["node"]["Puntuación"], 
                                'test_rest'=> json_encode($key["node"])
                            );
                            $this->Crud_model->agregarRegistro('produccion_test',$insert);
                        }
                    }
                }
            }
            $where = array('p.estadoexportacion' => 0);
            $datosTest = $this->Crud_test->GetDatos($where);
            if (!is_null($datosTest)) {
                foreach ($datosTest as $key) {
                    $where = array('p.usuario_id' => $key->usuario_id);
                    $datosUsurio = $this->Crud_usuario->GetDatos($where);
                    $envioDatos = array(
                        'value' => 10,
                        'real' => $key->test_valores/10,
                        'goal' => $datosUsurio[0]->incentive_id_conocimiento,
                        'date' => $key->test_fecha
                    );
                    $datodCarga =  $this->consultaRest('/api/entities/'.$datosUsurio[0]->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                    $this->Crud_log->Insertar('visitas incentive',$key->usuario_id,json_encode($datodCarga));
                    $wherebuscar = array('usuario_id' => $datosUsurio[0]->usuario_id, 'tipocumplimiento_id' => $datosUsurio[0]->incentive_id_conocimiento, 'cumplimiento_fecha' => $key->test_fecha);
                    $datosCumplimiento =  $this->Crud_cumplimiento->GetDatosCumplimiento($wherebuscar);
                    if (is_null($datosCumplimiento)) {
                        $insertar = array(
                            'usuario_id' => $datosUsurio[0]->usuario_id, 
                            'tipocumplimiento_id' => $datosUsurio[0]->incentive_id_conocimiento,
                            'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                            'cumplimiento_fecha' => $key->test_fecha,
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
                    $id = array('test_id' => $datosTest[0]->test_id);
                    $update = array('estadoexportacion' => 1);
                    $this->Crud_test->editar($update,$id);
                }
            }
        }
        else
        {
            echo "no esta activo test";
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
                    if (!isset($result->uid)) {
                        $editar = array('drupal_estado_id' => 3,'drupal_id' => null);
                        $busqueda = array('usuario_id' => $key->usuario_id);
                        $this->Crud_usuario->editar($editar,$busqueda);
                        $this->Crud_log->Insertar('usuario drupal',$key->usuario_id,json_encode($result));
                    }else
                    {
                        $editar = array('drupal_id' => $result->uid,'drupal_estado_id' => 4);
                        $busqueda = array('usuario_id' => $key->usuario_id);
                        $this->Crud_usuario->editar($editar,$busqueda);
                        $this->Crud_log->Insertar('usuario drupal',$key->usuario_id,json_encode($result));
                    }
                    $tempo = $this->crearUsuario($this->crearUsuarioAgile($key,'Archivo Plano',null,"Carga Manual"));
                    if(!$tempo['estado'])
                    {   
                        $editar = array('agile_estado_id' => 4);
                        $busqueda = array('usuario_id' => $key->usuario_id);
                        $this->Crud_usuario->editar($editar,$busqueda);
                        $this->Crud_log->Insertar('usuario agile',$key->usuario_id,json_encode($tempo));
                    }else
                    {   
                        $editar = array(
                            'agile_estado_id' => 3,
                            'agile_id' => json_decode($tempo['mensaje'], true)["id"],
                            'agile_fecha'=>date($this->formatoFecha)
                        );
                        $busqueda = array('usuario_id' => $key->usuario_id);
                        $this->Crud_usuario->editar($editar,$busqueda);
                        $this->Crud_log->Insertar('usuario agile',$key->usuario_id,json_encode($tempo));
                    }
            }
            return true;
        }else
        {
            echo "no hay carga de usuarios";
            echo "<br>";
            return false;
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
    public function datosCedula()
    {
        $arrayName = array();
        $arrayName[0] = 79731874;
        $arrayName[1] = 1013595186;
        $arrayName[2] = 1013625445;
        $arrayName[3] = 80094751;
        $arrayName[4] = 80745886;
        $arrayName[5] = 1013654514;
        $arrayName[6] = 80216675;
        $arrayName[7] = 1022389702;
        $arrayName[8] = 1073232957;
        $arrayName[9] = 1019138507;
        $arrayName[10] = 1110526308;
        $arrayName[11] = 1015421201;
        $arrayName[12] = 1032456259;
        $arrayName[13] = 1015436810;
        $arrayName[14] = 1024556043;
        $arrayName[15] = 1072962403;
        $arrayName[16] = 1020763416;
        $arrayName[17] = 1032381046;
        $arrayName[18] = 1022388176;
        $arrayName[19] = 1030544063;
        $arrayName[20] = 1020751434;
        $arrayName[21] = 1073675708;
        $arrayName[22] = 53932071;
        $arrayName[23] = 52977543;
        $arrayName[24] = 1015448282;
        $arrayName[25] = 80739979;
        $arrayName[26] = 1031122179;
        $arrayName[27] = 1032440919;
        $arrayName[28] = 1019006108;
        $arrayName[29] = 34325529;
        $arrayName[30] = 1032400215;
        $arrayName[31] = 52792171;
        $arrayName[32] = 37707838;
        $arrayName[33] = 79879677;
        $arrayName[34] = 1030571314;
        $arrayName[35] = 1093772736;
        $arrayName[36] = 1012352755;
        $arrayName[37] = 1015424170;
        $arrayName[38] = 1110534326;
        $arrayName[39] = 1033738395;
        $arrayName[40] = 1098769920;
        $arrayName[41] = 52533081;
        $arrayName[42] = 52778847;
        $arrayName[43] = 1016032085;
        $arrayName[44] = 1012409353;
        $arrayName[45] = 1019057276;
        $arrayName[46] = 52362139;
        $arrayName[47] = 80824907;
        $arrayName[48] = 53891989;
        $arrayName[49] = 80810256;
        $arrayName[50] = 1016002693;
        $arrayName[51] = 52730173;
        $arrayName[52] = 1015455083;
        $arrayName[53] = 1030615228;
        $arrayName[54] = 52200330;
        $arrayName[55] = 1033733509;
        $arrayName[56] = 1012407673;
        $arrayName[57] = 1014196916;
        $arrayName[58] = 1010225996;
        $arrayName[59] = 52439231;
        $arrayName[60] = 1022976301;
        $arrayName[61] = 1053775428;
        $arrayName[62] = 1019066602;
        $arrayName[63] = 1104707991;
        $arrayName[64] = 1010205610;
        $arrayName[65] = 1101754639;
        $arrayName[66] = 1018467144;
        $arrayName[67] = 1020794054;
        $arrayName[68] = 1019006825;
        $arrayName[69] = 1014241338;
        $arrayName[70] = 1010170641;
        $arrayName[71] = 1026275842;
        $arrayName[72] = 80115455;
        $arrayName[73] = 1015397689;
        $arrayName[74] = 79223701;
        $arrayName[75] = 1014213503;
        $arrayName[76] = 1012380151;
        $arrayName[77] = 79835477;
        $arrayName[78] = 80739986;
        foreach ($arrayName as $key) {
            $this->eliminarDatosIncentivexCedula($key);
        }
    }
    public function  eliminarDatosIncentivexCedula($docuemnto = 80216675,$fecha = '2017-07-01')
    {
        $datodCarga =  $this->consultaRest('/api/entities/'.$docuemnto);
        $where = array('p.usuario_documento' => $docuemnto);
        $datosUsuario = $this->Crud_usuario->GetDatos($where);
        if (!is_null($datosUsuario)) {
            if (!isset($datodCarga["status"])) 
            {
                foreach ($datodCarga['entity']["goalvalues"] as $key) 
                {
                    if (date("m", strtotime($key["date"])) == date("m", strtotime($fecha))) 
                    {
                        echo "<pre>";                        
                        $datosIncentive =  $this->consultaRest('/api/entities/'.$docuemnto.'/delgoalvalue/'.$key['id'],'GET');
                        $arrayName = array('fecha' => $fecha,'Documento'=> $docuemnto,'goal_id'=>$key['id'] );
                        $arrayName = array_merge($datosIncentive,$arrayName);
                        var_dump($arrayName);
                        $this->Crud_log->Insertar('Eliminar goal',$datosUsuario[0]->usuario_id,json_encode($arrayName));
                        echo "</pre>";
                    }
                }
            }
        }
    }
    public function pruebaregistros()
    {
        $where = array('r.rol_id' => 7 ,'p.agile_estado_id' => 1);
        $datosUsuario = $this->Crud_usuario->GetDatos($where);
        var_dump($datosUsuario);
    }

    public function testNoRealizado($fecha = NULL, $bandera = NULL)
    {
        echo "badera";
        var_dump($bandera);
        echo "<br>";
        if (!is_null($fecha)) {
            $where = 'usuario_id not in(select usuario_id from produccion_test where test_fecha = '.$fecha.')';
            $notest = $this->Crud_usuario->noTest($where,'p.agile_id,p.usuario_documento,p.usuario_nombre,p.usuario_correo,p.usuario_codigounico,p.usuario_codigonomina');
            foreach ($notest as $key) {
                if (is_null($bandera)) {
                    var_dump(json_encode($notest));
                }
                else
                {
                    if ($bandera == 1) {
                        $agileUser = $this->buscarUsuarioAgile($key->agile_id, 'id');
                        $tag = array('tipo' => 'codigoagile','datos' => $key->agile_id,'tag'=>'No ha realizado test');
                        $this->agregarTag($tag);
                    }
                }
                echo 'ok';
            }
        }
        else
        {
            echo "fecha no cargada";
        }
    }
}

