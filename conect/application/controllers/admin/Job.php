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
        $this->load->model('crud/Crud_update');
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
                    'Actiualizausuarios'=> $this->cargarActualizaciones(),
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
                    'Actiualizausuarios'=> $this->cargarActualizaciones(),
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
    public function fechaSistema()
    {
        echo date('Y-m-d H:i:s',$this->ajusteFecha);
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
            //$docuemnto = 1022976301;
            $where = array('p.usuario_documento' => $docuemnto);
            $datosUsuario = $this->Crud_usuario->GetDatos($where);
            $dia = $this->input->post("dia", TRUE);
            $mes = $this->input->post("mes", TRUE);
            $ano = $this->input->post("ano", TRUE);
            $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;
            $dia = (strlen($dia) == 1) ? '0'.$dia : $dia;
            //$dia =  01;
            //$mes =  '08';
            //$ano =  2017;
            $fecha = $ano.'-'.$mes.'-'.$dia;
            $datodCarga =  $this->consultaRest('/api/entities/'.$docuemnto);
            $enviodatos = array();
            $contador = 0;
            $suma =  0;
            //var_dump(strpos($_SERVER['HTTP_HOST'], 'conectate'));
            if (strpos($_SERVER['HTTP_HOST'], 'conectate')) {
                $dominio_id =  1;
                if ((int) $mes <= 7) {
                    $menupordominio = array('dominio_id' => 1);
                }
                else
                {
                    $datosMenuIncentive = array('dominio_id' => 1,'cargosubmenu_id <>'=>2);
                }
            }
            else
            {
                $dominio_id =  2;
                if ((int) $mes <= 7) {
                    $datosMenuIncentive = array('dominio_id' => 2);
                }
                else
                {
                    $datosMenuIncentive = array('dominio_id' => 2,'cargosubmenu_id <>'=>7);
                }
            }
            if (!isset($datodCarga["status"])) 
            {
                foreach ($datodCarga['entity']["goalvalues"] as $key) 
                {

                    if (date("m", strtotime($key["date"])) == date("m", strtotime($fecha))) {
                        $datoIcentive = $this->buscarTipoDeCarga($key["goal_id"],$datosUsuario[0]->cargo_id,$dominio_id,$fecha);
                        $arrayName = array(
                            'menu' => $datoIcentive[0]->cargomenu_nombre, 
                            'menuid' => $datoIcentive[0]->cargomenu_id, 
                            'indicarod' => $datoIcentive[0]->cargosubmenu_nombre, 
                            'meta' => $key["value"], 
                            'cumplimiento' => $key["real"], 
                            'puntos' => $key["percentage_weighed"],
                            'date'=> $key["date"],
                            'cargosubmenu_tipo' => $datoIcentive[0]->cargosubmenu_tipo
                        );
                        $enviodatos[$contador] = $arrayName;
                        $contador = $contador + 1;
                        $suma = $suma+$key["percentage_weighed"];
                        
                    }
                }
            }
            //var_dump(json_encode($enviodatos));
            //echo "<br>";
            $datosIncentive = $this->Crud_parametria->datosMenuIncentive($datosMenuIncentive);
            //var_dump(json_encode($datosIncentive));
            //echo "<br>";
            foreach ($datosIncentive as $key1) {
                //var_dump($key1->cargosubmenu_tipo);
                //echo "<br>";
                switch ($key1->cargosubmenu_tipo) {
                    case '1' :
                        $bandera = true;
                        $conta = 0;
                        while ($bandera and count($enviodatos) > $conta ) {
                            if ($enviodatos[$conta]["cargosubmenu_tipo"] == $key1->cargosubmenu_tipo) {
                                $bandera = false;
                            }
                            else
                            {
                                $conta = $conta+1;
                            }
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
                                'date'=> '',
                                'cargosubmenu_tipo' => $key1->cargosubmenu_tipo
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
                                'date'=> '',
                                'cargosubmenu_tipo' => $key1->cargosubmenu_tipo
                            );
                            $enviodatos[$contador] = $arrayName;
                            $contador = $contador + 1;
                        }
                    break;
                    case '2':
                            $bandera = true;
                            $conta = 0;
                            while ($bandera and count($enviodatos) > $conta ) {
                                if ($enviodatos[$conta]["cargosubmenu_tipo"] == $key1->cargosubmenu_tipo) {
                                $bandera = false;
                                }
                                else
                                {
                                    $conta = $conta+1;
                                }
                            }
                            if ($bandera) {
                                $arrayName = array(
                                    'menu' => $key1->cargomenu_nombre, 
                                    'menuid' => $key1->cargomenu_id, 
                                    'indicarod' => $key1->cargosubmenu_nombre, 
                                    'meta' => '', 
                                    'cumplimiento' => 0, 
                                    'puntos' => 0,
                                    'date'=> '',
                                    'cargosubmenu_tipo' => $key1->cargosubmenu_tipo
                                );
                                $enviodatos[$contador] = $arrayName;
                                $contador = $contador + 1;
                            }
                    break;
                    case '3':
                        $bandera = true;
                        $conta = 0;
                        while ($bandera and count($enviodatos) > $conta ) {
                            if ($enviodatos[$conta]["cargosubmenu_tipo"] == $key1->cargosubmenu_tipo) {
                                $bandera = false;
                            }
                            else
                            {
                                $conta = $conta+1;
                            }
                        }
                        if ($bandera) {
                            $arrayName = array(
                                'menu' => $key1->cargomenu_nombre, 
                                'menuid' => $key1->cargomenu_id, 
                                'indicarod' => $key1->cargosubmenu_nombre, 
                                'meta' => '', 
                                'cumplimiento' => 0, 
                                'puntos' => 0,
                                'date'=> '',
                                'cargosubmenu_tipo' => $key1->cargosubmenu_tipo
                            );
                            $enviodatos[$contador] = $arrayName;
                            $contador = $contador + 1;
                        }
                    break;
                    case '4':
                        $bandera = true;
                        $conta = 0;
                        while ($bandera and count($enviodatos) > $conta ) {
                            if ($enviodatos[$conta]["cargosubmenu_tipo"] == $key1->cargosubmenu_tipo) {
                                $bandera = false;
                            }
                            else
                            {
                                $conta = $conta+1;
                            }
                        }
                        if ($bandera) {
                            $arrayName = array(
                                'menu' => $key1->cargomenu_nombre, 
                                'menuid' => $key1->cargomenu_id, 
                                'indicarod' => $key1->cargosubmenu_nombre, 
                                'meta' => '', 
                                'cumplimiento' => 0, 
                                'puntos' => 0,
                                'date'=> '',
                                'cargosubmenu_tipo' => $key1->cargosubmenu_tipo
                            );
                            $enviodatos[$contador] = $arrayName;
                            $contador = $contador + 1;
                        }
                    break;
                    case '5':
                        $bandera = true;
                        $conta = 0;
                        while ($bandera and count($enviodatos) > $conta ) {
                            if ($enviodatos[$conta]["cargosubmenu_tipo"] == $key1->cargosubmenu_tipo) {
                                $bandera = false;
                            }
                            else
                            {
                                $conta = $conta+1;
                            }
                        }
                        if ($bandera) {
                            $arrayName = array(
                                'menu' => $key1->cargomenu_nombre, 
                                'menuid' => $key1->cargomenu_id, 
                                'indicarod' => $key1->cargosubmenu_nombre, 
                                'meta' => '', 
                                'cumplimiento' => 0, 
                                'puntos' => 0,
                                'date'=> '',
                                'cargosubmenu_tipo' => $key1->cargosubmenu_tipo
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
            //echo $htmlText;
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
                if ($ordenar[$i]['cargosubmenu_tipo'] == $contador) 
                {
                    if ($ordenar[$i]['cargosubmenu_tipo']  != 1) 
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
    public function buscarTipoDeCarga($goal_id,$cargo_id,$dominio_id,$fecha)
    {
        if ($fecha <= '2017-07-31') {
            $divisor = 5;
        }
        else
        {
            $divisor = 4;
        }
        if ($divisor == 5) {
            $datoAjustedo = (($goal_id/$divisor)-intval(($goal_id/$divisor)))*100;
            switch (strval($datoAjustedo)) 
            {
                case 20:
                    if ($dominio_id == 1) {
                        $where = array('c.cargosubmenu_id' => 1,'p.dominio_id' => $dominio_id);
                    }
                    else
                    {
                        $where = array('c.cargosubmenu_id' => 6,'p.dominio_id' => $dominio_id);
                    }
                    
                    $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
                break;
                case 40:

                    if ($dominio_id == 1) {
                        $where = array('c.cargosubmenu_id' => 2,'p.dominio_id' => $dominio_id);
                    }
                    else
                    {
                        $where = array('c.cargosubmenu_id' => 7,'p.dominio_id' => $dominio_id);
                    }
                    $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
                break;
                case 60:

                    if ($dominio_id == 1) {
                        $where = array('c.cargosubmenu_id' => 3,'p.dominio_id' => $dominio_id);
                    }
                    else
                    {
                        $where = array('c.cargosubmenu_id' => 8,'p.dominio_id' => $dominio_id);
                    }
                    $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
                break;
                case 80:

                    if ($dominio_id == 1) {
                        $where = array('c.cargosubmenu_id' => 4,'p.dominio_id' => $dominio_id);
                    }
                    else
                    {
                        $where = array('c.cargosubmenu_id' => 9,'p.dominio_id' => $dominio_id);
                    }
                    $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
                break;
                case 0:
                    if ($dominio_id == 1) {
                        $where = array('c.cargosubmenu_id' => 5,'p.dominio_id' => $dominio_id);
                    }
                    else
                    {
                        $where = array('c.cargosubmenu_id' => 10,'p.dominio_id' => $dominio_id);
                    }
                    $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
                break;
            }
        }
        else
        {
            $goal_id=$goal_id-35;
            $datoAjustedo = (($goal_id/$divisor)-intval(($goal_id/$divisor)))*100;
            switch (strval($datoAjustedo)) 
            {
                case 25:
                    if ($dominio_id == 1) {
                        $where = array('c.cargosubmenu_id' => 1,'p.dominio_id' => $dominio_id);
                    }
                    else
                    {
                        $where = array('c.cargosubmenu_id' => 6,'p.dominio_id' => $dominio_id);
                    }
                    $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
                break;
                case 50:
                    if ($dominio_id == 1) {
                        $where = array('c.cargosubmenu_id' => 3,'p.dominio_id' => $dominio_id);
                    }
                    else
                    {
                        $where = array('c.cargosubmenu_id' => 8,'p.dominio_id' => $dominio_id);
                    }
                    $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
                break;
                case 75:
                    if ($dominio_id == 1) {
                        $where = array('c.cargosubmenu_id' => 4,'p.dominio_id' => $dominio_id);
                    }
                    else
                    {
                        $where = array('c.cargosubmenu_id' => 9,'p.dominio_id' => $dominio_id);
                    }
                    $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
                break;
                case 0:
                    if ($dominio_id == 1) {
                        $where = array('c.cargosubmenu_id' => 5,'p.dominio_id' => $dominio_id);
                    }
                    else
                    {
                        $where = array('c.cargosubmenu_id' => 10,'p.dominio_id' => $dominio_id);
                    }
                    $datosIncentive = $this->Crud_parametria->datosMenuIncentive($where,'*');
                break;
            }
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
            switch ($key['cargosubmenu_tipo']) {
                case '1':
                    if ($bandera) {
                        $html = $html.'
                    <tr>
                        <td colspan="2" class="group">'.$key['cargosubmenu_tipo'].'. '.$key['menu'].'</td>
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
                case '5':
                    $html = $html.'
                        <tr>
                            <td colspan="2" class="group">'.$key['cargosubmenu_tipo'].'. '.$key['menu'].'</td>
                        </tr>
                        <tr>
                            <td height="36">'.$key['indicarod'].'</td>
                            <td align="right" class="puntos" center>'.number_format($key['puntos'],1).'</td>
                        </tr>';
                break;

                default:
                        $html = $html.'
                        <tr>
                            <td colspan="2" class="group">'.$key['cargosubmenu_tipo'].'. '.$key['menu'].'</td>
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
            switch ($key['cargosubmenu_tipo']) {
                case '1':
                    if ($bandera) {
                        $html = $html.'
                    <tr>
                        <td colspan="4" class="group">'.$key['cargosubmenu_tipo'].'. '.$key['menu'].'</td>
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
                case '5':
                    $html = $html.'
                        <tr>
                            <td colspan="4" class="group">'.$key['cargosubmenu_tipo'].'. '.$key['menu'].'</td>
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
                            <td colspan="4" class="group">'.$key['cargosubmenu_tipo'].'. '.$key['menu'].'</td>
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
            $fecha=date('m',$this->ajusteFecha);
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
                //if ($key->usuario_codigonomina == 746104) {
                    if ($key->cargo_grupo == 1) 
                    {
                        //$where = array('p.metagrupo_mess' => $mes,'p.grupo_id' => $key->grupo_id); 
                        //$metas = $this->Crud_grupo->GetDatosMetaGrupoFijo($where);
                        $where = array('p.metagrupo_mes' => $mes,'p.grupo_id' => $key->grupo_id); 
                        $metas = $this->Crud_grupo->GetDatosMetaGrupoFijo($where);
                        $stringwhere = 'p.usuario_codigojefe =  '.$key->usuario_codigonomina.' and v.venta_mes = '.$mes.'';
                        $venta = $this->Crud_grupo->GetdatosQuery($stringwhere,'usuario_codigojefe');
                    }else
                    {
                        $where = array('p.metagrupo_mes' => $mes,'p.grupo_id' => $key->grupo_id); 
                        $metas = $this->Crud_grupo->GetDatosMetaGrupoFijo($where);
                        $stringwhere = 'p.grupo_id =  '.$key->grupo_id.' and v.venta_mes = '.$mes.'';
                        $venta = $this->Crud_grupo->GetdatosQuery($stringwhere,'grupo_id');
                    }
                    
                    if (!is_null($metas) and !is_null($venta) and count($venta) != 0) {
                        $metasTotal = (int) $metas[0]->metagrupo_meta;
                        $ventatotal = (int) $venta[0]['ventasumaRecompra'] +(int) $venta[0]['ventasumaNuevo'];
                        $envioDatos = array(
                            'value' => $metasTotal,
                            'real' => $ventatotal,
                            'goal' => $key->incentive_id_grupo,
                            'date' => '2017-'.$mes.'-01'
                        );
                        $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                        $this->Crud_log->Insertar('Meta Grupo incentive',$key->usuario_id,json_encode($datodCarga));
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
                        if ($key->cargo_grupo == 1) {
                            if (is_null($venta) or count($venta) == 0) {
                                var_dump(json_encode($venta));
                                echo "<br>";
                                var_dump(json_encode($key));
                            }
                            else{
                            //break;
                                if (is_null($key->incentive_id_ventas)) {
                                    $ventatotal = (int) $venta[0]['ventasumaRecompra'];
                                    $envioDatos = array(
                                        'value' => $metasTotal,
                                        'real' => $ventatotal,
                                        'goal' => $key->incentive_id_renovacion,
                                        'date' => '2017-'.$mes.'-01'
                                    );
                                    $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                                    $this->Crud_log->Insertar('Venta incentive',$key->usuario_id,json_encode($datodCarga));
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
                                else
                                {
                                    $ventatotal = (int) $venta[0]['ventasumaRecompra'] + (int) $venta[0]['ventasumaNuevo'];
                                    $envioDatos = array(
                                        'value' => $metasTotal,
                                        'real' => $ventatotal,
                                        'goal' => $key->incentive_id_ventas,
                                        'date' => '2017-'.$mes.'-01'
                                    );
                                    $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                                    $this->Crud_log->Insertar('meta incentive',$key->usuario_id,json_encode($datodCarga));
                                    $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_ventas, 'cumplimiento_fecha' => '2017-'.$mes.'-01',);
                                    if (is_null($datosCumplimiento)) 
                                    {
                                        $insertar = array(
                                            'usuario_id' => $key->usuario_id, 
                                            'tipocumplimiento_id' => $key->incentive_id_ventas,
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
                }
                //}   
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
            $ano =  date('Y',$this->ajusteFecha);
        }
        else
        {
            $ano =  date('Y',$fecha);   
        }
        $where = array('v.estado_id' => 1 ,'v.venta_fechacarga' => $fecha);
        $datosUsuario = $this->Crud_cumplimiento->datosPendientecarga($where,'max(`p`.`metaventa_id`) metaventa_id, p.metaventa_mes, p.metaventa_fecha, max(p.metaventa_recompra) metaventa_recompra, max(p.metaventa_nuevas) metaventa_nuevas, p.usuario_id, p.estado_id, p.metaventa_fechacarga, p.metaventa_nomina,v.*,u.*,pi.*','v.usuario_id',null,$fecha);
        if (!is_null($datosUsuario)) {
            foreach ($datosUsuario as $key) {
                if (!is_null($key->incentive_id_ventas)) 
                {
                    $mes = (strlen($key->metaventa_mes) == 1) ? '0'.$key->metaventa_mes : $key->metaventa_mes;
                    $envioDatos = array(
                        'value' => number_format((float) ($key->metaventa_recompra + $key->metaventa_nuevas), 0, '.', ''),
                        'real' => number_format((float) ($key->venta_recompra + $key->venta_nuevas), 0, '.', ''),
                        'goal' => $key->incentive_id_ventas,
                        'date' => $ano.'-'.$mes.'-01'
                    );
                    $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                    $this->Crud_log->Insertar('ventas incentive',$key->usuario_id,json_encode($datodCarga));
                    $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_renovacion, 'cumplimiento_fecha' => $ano.'-'.$mes.'-01',);
                    $datosCumplimiento =  $this->Crud_cumplimiento->GetDatosCumplimiento($wherebuscar);
                    if (is_null($datosCumplimiento)) {
                        $insertar = array(
                            'usuario_id' => $key->usuario_id, 
                            'tipocumplimiento_id' => $key->incentive_id_ventas,
                            'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                            'cumplimiento_fecha' => $ano.'-'.$mes.'-01',
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
                }
                else
                {
                    $mes = (strlen($key->metaventa_mes) == 1) ? '0'.$key->metaventa_mes : $key->metaventa_mes;
                    $envioDatos = array(
                        'value' => number_format((float) $key->metaventa_recompra, 0, '.', ''),
                        'real' => number_format((float) $key->venta_recompra, 0, '.', ''),
                        'goal' => $key->incentive_id_renovacion,
                        'date' => $ano.'-'.$mes.'-01'
                    );
                    $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                    $this->Crud_log->Insertar('ventas incentive',$key->usuario_id,json_encode($datodCarga));
                    $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_renovacion, 'cumplimiento_fecha' => $ano.'-'.$mes.'-01',);
                    $datosCumplimiento =  $this->Crud_cumplimiento->GetDatosCumplimiento($wherebuscar);
                    if (is_null($datosCumplimiento)) {
                        $insertar = array(
                            'usuario_id' => $key->usuario_id, 
                            'tipocumplimiento_id' => $key->incentive_id_renovacion,
                            'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                            'cumplimiento_fecha' => $ano.'-'.$mes.'-01',
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
                        'date' => $ano.'-'.$mes.'-01'
                    );
                    $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                    $this->Crud_log->Insertar('ventas incentive',$key->usuario_id,json_encode($datodCarga));
                    $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_nueva, 'cumplimiento_fecha' => $ano.'-'.$mes.'-01',);
                    $datosCumplimiento =  $this->Crud_cumplimiento->GetDatosCumplimiento($wherebuscar);
                    if (is_null($datosCumplimiento)) {
                        $insertar = array(
                            'usuario_id' => $key->usuario_id, 
                            'tipocumplimiento_id' => $key->incentive_id_nueva,
                            'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                            'cumplimiento_fecha' => $ano.'-'.$mes.'-01',
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
            $ano =  date('Y',$this->ajusteFecha);
        }
        else
        {
            $ano =  date('Y',$fecha);   
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
            foreach ($datosUsuario as $key) 
            {
                $mes = (strlen($key->metavisita_mes) == 1) ? '0'.$key->metavisita_mes : $key->metavisita_mes;
                $envioDatos = array(
                    'value' => number_format((float) $key->metavisita_totales, 0, '.', ''),
                    'real' => number_format((float) $key->visita_total, 0, '.', ''),
                    'goal' => $key->incentive_id_citas,
                    'date' => $ano.'-'.$mes.'-01'
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
                        'cumplimiento_fecha' => $ano.'-'.$mes.'-01',
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
    public function cargarUsuariosActivos()
    {
        $jobGrupo = $this->Crud_parametria->obtenerParametria('jobActivos');
        if ($jobGrupo == '1') {
            $datosTest = $this->totalactivos();
            if (!is_null($datosTest)) {
                foreach ($datosTest["nodes"] as $key) {
                    if ($key["node"]["Último acceso"] != '') {
                        $contact1 = $this->buscarUsuarioAgile($key["node"]["Correo electrónico"],'email');
                        if ($contact1 != '') {
                            $result = json_decode($contact1, false, 512, JSON_BIGINT_AS_STRING);
                            $result1 =$this->editarContactoAgile($result,'on','Actualizado');
                        }
                    }
                    else
                    {
                        $contact1 = $this->buscarUsuarioAgile($key["node"]["Correo electrónico"],'email');
                        if ($contact1 != '') {
                            $result = json_decode($contact1, false, 512, JSON_BIGINT_AS_STRING);
                            $result1 =$this->editarContactoAgile($result,'off','Actualizado');
                        }
                    }
                    
                }
            }
        }
    }
    public function cargarCumplimientoTest()
    {
        $jobGrupo = $this->Crud_parametria->obtenerParametria('jobTest');
        if ($jobGrupo == '1') {
            $datosTest = $this->totaltest();
            if (!is_null($datosTest)) {
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
                    $id = array('test_id' => $key->test_id);
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
                        $editar = array('drupal_estado_id' => 3);
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
    public function buscarUsuario($correo='daniel.paez@inxaitcorp.com')
    {
        var_dump($this->buscarUsuarioAgile($correo,'email'));
    }
    public function archivos()
    {
        echo $this->listar_archivos(getcwd(),"/File/uploader/courier");
    }
    public function cargarActualizaciones()
    {
        $select = 'p.usuario_id as usuario_idUpdate,u.usuario_id,p.usuario_documento, p.grupo_id as grupo_idUpdate, u.grupo_id as grupo_id,p.cargo_id as cargo_idUpdate,u.cargo_id as cargo_id,u.usuario_codigojefe as usuariocodigojefe,p.usuario_codigojefe as usuariocodigojefeUpdate,p.empresalegal_id as empresalegal_idUpdate,u.empresalegal_id,u.agile_id';
        $datosUpdate = $this->Crud_update->datosConsulta(null,$select);
        if (!is_null($datosUpdate)) {
            foreach ($datosUpdate as $key) {
                $where = array('usuario_id' => $key->usuario_idUpdate);
                $datosusuario = $this->Crud_update->GetDatos($where);
                $contact1 = $this->buscarUsuarioAgile($key->agile_id, 'id');
                if ($key->grupo_idUpdate != $key->grupo_id) {
                    $where = array('usuario_id' => $key->usuario_id);
                    $actualiza = array('grupo_id' => $key->grupo_idUpdate);
                    $this->Crud_usuario->editar($actualiza,$where);
                    if ($contact1 != '') {
                        $result = json_decode($contact1, false, 512, JSON_BIGINT_AS_STRING);
                        $result1 =$this->editarContactoAgile($result,$datosusuario[0]->grupo_nombre,'Grupo');
                    }
                    $carga = array('grupo_Viejo' => $key->grupo_id, 'grupo_nuevo'=>$key->grupo_idUpdate);
                    $this->Crud_log->Insertar('Actualizaciones Grupo',$datosusuario[0]->usuario_id,json_encode($carga));
                }
                if ($key->cargo_idUpdate != $key->cargo_id) {
                    $where = array('usuario_id' => $key->usuario_id);
                    $actualiza = array('cargo_id' => $key->cargo_idUpdate);
                    $this->Crud_usuario->editar($actualiza,$where);
                    if ($contact1 != '') {
                        $result = json_decode($contact1, false, 512, JSON_BIGINT_AS_STRING);
                        $result1 =$this->editarContactoAgile($result,$datosusuario[0]->cargo_nombre,'Cargo');
                        $result1 =$this->editarContactoAgile($result,$datosusuario[0]->cargo_nombre,'Posicion');
                    }
                    $carga = array('cargo_Viejo' => $key->cargo_id, 'cargo_nuevo'=>$key->cargo_idUpdate);
                    $this->Crud_log->Insertar('Actualizaciones Cargo',$datosusuario[0]->usuario_id,json_encode($carga));
                }
                if ($key->empresalegal_idUpdate != $key->empresalegal_id) {
                    $where = array('usuario_id' => $key->usuario_id);
                    $actualiza = array('empresalegal_id' => $key->empresalegal_idUpdate);
                    $this->Crud_usuario->editar($actualiza,$where);
                    if ($contact1 != '') {
                        $result = json_decode($contact1, false, 512, JSON_BIGINT_AS_STRING);
                        $result1 =$this->editarContactoAgile($result,$datosusuario[0]->empresalegal_nombre,'Empresa legal');
                        $result1 =$this->editarContactoAgile($result,$datosusuario[0]->empresalegal_nombre,'company');
                        $result1 =$this->editarContactoAgile($result,$datosusuario[0]->dominio_url,'URLSISTEMA');
                    }
                    $carga = array('empresalegal_Viejo' => $key->empresalegal_id, 'empresalegal_nuevo'=>$key->empresalegal_idUpdate);
                    $this->Crud_log->Insertar('Actualizaciones Empresa Legal',$datosusuario[0]->usuario_id,json_encode($carga));
                }
                if ($key->usuariocodigojefe != $key->usuariocodigojefeUpdate) {
                    $where = array('usuario_id' => $key->usuario_id);
                    $actualiza = array('usuario_codigojefe' => $key->usuariocodigojefeUpdate);
                    $this->Crud_usuario->editar($actualiza,$where);
                    $carga = array('usuariocodigojefe_Viejo' => $key->usuariocodigojefe, 'usuariocodigojefe_nuevo'=>$key->usuariocodigojefeUpdate);
                    $this->Crud_log->Insertar('Actualizaciones usuariocodigojefe',$datosusuario[0]->usuario_id,json_encode($carga));
                }
                $actualiza = array('estado_id' => 5);
                $where = array('usuario_id' => $key->usuario_idUpdate);
                $this->Crud_update->editar($actualiza,$where);
            }
        }
    }
    public function bachPortipo($tiporegla = null,$where= null,$fecha = '2017-01-01')
    {
        if (is_null($tiporegla)) {
            echo "regla nula";
        }
        else
        {
            switch ($tiporegla) {
                case 'elimina':
                    if (!is_null($where)) {
                        //$where = array('r.rol_id' => 7);
                        $datosUsuario = $this->Crud_usuario->GetDatos($where);
                        foreach ($datosUsuario as $key) {
                            $this->eliminarDatosIncentivexCedula($key->usuario_documento,$fecha);
                        }
                    }
                break;
                case 'regalar':
                    if (!is_null($where)) {
                        //$where = array('r.rol_id' => 7 ,'p.empresalegal_id' => 1);
                        $this->cargaregaloTest($where,$fecha,'Regalo de conocimiento');
                    }
                break;
                default:
                    echo "regla no existente";
                break;
            }
        }
    }
    public function  eliminarDatosIncentivexCedula($docuemnto = 80216675,$fecha = '2017-08-01')
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
    public function cargaregaloTest($where,$fecha = '2017-07-01',$descripcion='')
    {
        $datosUsuario = $this->Crud_usuario->GetDatos($where);
        if (!is_null($datosUsuario)) {
            foreach ($datosUsuario as $key) {
                $envioDatos = array(
                    'value' => 10,
                    'real' => 10,
                    'goal' => $key->incentive_id_conocimiento,
                    'date' => $fecha
                );
                $datodCarga =  $this->consultaRest('/api/entities/'.$key->usuario_documento.'/addgoalvalue','POST',$envioDatos);
                $this->Crud_log->Insertar('visitas incentive'.$descripcion,$key->usuario_id,json_encode($datodCarga));
                $wherebuscar = array('usuario_id' => $key->usuario_id, 'tipocumplimiento_id' => $key->incentive_id_conocimiento, 'cumplimiento_fecha' => $fecha);
                $datosCumplimiento =  $this->Crud_cumplimiento->GetDatosCumplimiento($wherebuscar);
                if (is_null($datosCumplimiento)) {
                    $insertar = array(
                        'usuario_id' => $key->usuario_id, 
                        'tipocumplimiento_id' => $key->incentive_id_conocimiento,
                        'cumplimiento_porcentaje' => $datodCarga["value"]["percentage"],
                        'cumplimiento_fecha' => $fecha,
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
            }
        }
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

