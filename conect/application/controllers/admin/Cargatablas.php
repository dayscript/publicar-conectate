<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cargatablas extends MY_Controller {


	public function __construct() {
        parent::__construct();
        $this->load->model('crud/Crud_noticias');
        $this->load->model('crud/Crud_usuario');
        $this->load->model('crud/Crud_menu');
        $this->load->model('crud/Crud_campo');
        $this->load->model('crud/Crud_tabla');
        $this->load->library("Excel/Excel");
        if (is_null($this->session->userdata('id'))) {
        	redirect('Login');
        }
    }

	public function index()
	{

	}
    public function controlador($dato = null,$bandera = null)
    {
        if (!is_null($dato)) {
            $campos = $this->buscarParametria($dato);
            $this->load->view('admin/sobrecargas/head_view');
            $dataSend = array(
                "datos" =>  array(
                    'noticias' => $this->Crud_noticias->GetDatosTotales(5),
                    'menu' => $this->ordenarMenu($this->session->userdata('rol_id'))
                )
            );
            $datoNav = $this->load->view('admin/sobrecargas/nav_view',$dataSend,TRUE);
            $datoDatos = $this->load->view('admin/adminJS/'.$campos["js"],null,TRUE);

            $dataSendFoot = array(
                "datos" => $datoDatos
            );
            $dataFooter = $this->load->view('admin/sobrecargas/footer_view',$dataSendFoot,TRUE);
            
            switch ($bandera) {
                case -1:
                    $mensaje = 'Carga Exitosa';
                    $datosEditar= $campos["datosEditar"];
                break;
                case -2:
                    $mensaje = $this->mensajeExterno;
                    $datosEditar= $campos["datosEditar"];
                break;     
                case -3:
                    $mensaje = 'Formato incompatible debe ser .xls';
                    $datosEditar= $campos["datosEditar"];
                break;  
                case null or 'null':
                    $mensaje = '';
                    $datosEditar= $campos["datosEditar"];
                break;  
                default:
                    $mensaje = '';
                    $where = array($campos["tabla"].'_id' => $bandera);
                    $datosEditar= $campos["datosEditar"];
                break;
            }
            $mes = date('m',$this->ajusteFecha);
            
            switch ((int)$campos['tipoaccion']) {
                case 1:
                    $dataSend = array(
                        "footer" => $dataFooter,
                        'nav' => $datoNav,
                        'error' => $mensaje,
                        'datosCarga' => $datosEditar,
                        'datos' =>$campos
                    );
                    $this->load->view('admin/carga_view',$dataSend);
                break;
                case 2:
                    
                    $dataSend = array(
                        "footer" => $dataFooter,
                        'nav' => $datoNav,
                        'error' => $mensaje,
                        'datosCarga' => $datosEditar,
                        'datos' =>$campos,
                        'form'=> $this->cargarComponentesform($this->Crud_campo->GetDatosMetaGrupo(array('p.tabla_nombre' => $dato)))
                    );
                    $this->load->view('admin/export_view',$dataSend);
                break;
            }
        }
        else
        {
            $this->redirecionar($this->session->userdata('rol_id'));
        }
    }
    public function exportar($tabla= null,$fecha = null,$dominio_id = 1,$datosPantalla = null)
    {
        $arraywhere = array(
            'p.tabla_nombre' => strtolower($tabla)
        );
        $valorCampo = $this->Crud_tabla->GetDatos($arraywhere,null,null,'*');
        $ano =  date('Y',$this->ajusteFecha);
        if (is_null($this->input->post("mes", TRUE))) 
        {
            $mes = date('m',strtotime($fecha));
        }
        else
        {
            $mes = $this->input->post("mes", TRUE);
            $fecha = $ano.'-'.$mes.'-01';
        }
        if (!is_null($this->input->post("dominio_id", TRUE))) 
        {
            $dominio_id = $this->input->post("dominio_id", TRUE);
        }
        if (is_null($fecha) or (int) $fecha == 1) {
            $fecha=date('Y-m-d',$this->ajusteFecha);
            $ano =  date('Y',$this->ajusteFecha);
            $mes =  date('m',$this->ajusteFecha);
            $dia =  date('d',$this->ajusteFecha);
            $fecha= $ano.'-'.$mes.'-01';
        }
        else
        { 
            $ano =  date('Y',strtotime($fecha));
            $mes =  date('m',strtotime($fecha));
            $dia =  date('d',strtotime($fecha));
            $fecha = $ano.'-'.$mes.'-01';
        }
        if (!is_null($dominio_id)) {
            $where = array('dominio_id' => $dominio_id);
            $dominio = $this->Crud_model->obtenerRegistros('produccion_dominio',$where);
        }
        if ($tabla == 'exportgeneral' and !is_null($dominio_id)) {
            $datosUsuario = $this->Crud_usuario->GetDatos(array('p.estado_id' => 1,'p.rol_id' => 7,'p.empresalegal_id'=>$dominio[0]->empresalegal_id));
            $datosIncentive =  $this->consultaRest('/api/clients/'.$dominio[0]->codigo_incentive.'/dategoalvalues/'.$fecha,'GET');
            $datosGenerales = array();
            if (count($datosIncentive) > 0) {
                foreach ($datosIncentive['goal_values'] as $key) {
                    //if ($key['identification'] == 22589702) {
                    if ($key['date'] == $fecha) {
                        $estructura = array();
                        $estructura[$key['goal_id']] = array(
                            'identification' => $key['identification'],
                            'goal_id' => $key['goal_id'],
                            'value' => $key['value'],
                            'real' => $key['real'],
                            'percentage' => $key['percentage'],
                            'percentage_modified' => $key['percentage_modified'],
                            'percentage_weighed' => $key['percentage_weighed'],
                            'date' => $key['date'],
                            'created_at' => $key['created_at']);
                        //var_dump($estructura);
                        if (isset($datosGenerales[$key['identification']])) {
                            $datosGenerales[$key['identification']] = array_merge($datosGenerales[$key['identification']],$estructura);
                        }
                        else
                        {
                            $datosGenerales[$key['identification']] = $estructura;
                        }
                    }
                    //}
                }
                
            }
            //var_dump(json_encode($datosGenerales));
            foreach ($datosGenerales as $key1) {
                $suma =0;
                $conteodos =  0;
                $identificacion = $this->returnIdentificacion($key1);
                foreach ($key1 as $valoressuma) {
                    $suma = $suma + $valoressuma['percentage_weighed'];
                    $goal_id = $valoressuma['goal_id'];
                    $conteodos = $conteodos +1;
                }
                $datosCompletosUsuario = null;
                $datosCompletosUsuario_id = null;
                foreach ($datosUsuario as $usuarioUnidad) {
                    if ($usuarioUnidad->usuario_documento == $identificacion) {
                        $datosCompletosUsuario = $usuarioUnidad;
                        $datosCompletosUsuario_id = $usuarioUnidad->usuario_id;
                    }
                }
                $datos = array('suma' => $suma,'identification' =>$identificacion,'cargo_id' => $this->idCategoria($goal_id),'datos'=>$datosCompletosUsuario,'usuario_id'=>$datosCompletosUsuario_id);
                $datosGenerales[$identificacion] = array_merge($datosGenerales[$identificacion],$datos);    
                $whereIncentive = array('p.cargo_id' => $this->idCategoria($goal_id),'p.incentive_fechainicio <='=>$fecha,'p.incentive_fechafin >='=>$fecha);
                $datosIncentiveCarga =$this->Crud_parametria->datosIncentive($whereIncentive);
                if (is_null($datosIncentiveCarga[0]->incentive_id_ventas)) {
                    $cargaboolrenovacion = false;
                    $cargaboolnueva = false;
                    foreach ($key1 as $valoressuma) {
                        if ($datosIncentiveCarga[0]->incentive_id_renovacion == $valoressuma['goal_id']) {
                            $cargaboolrenovacion = true;
                        }
                        if ($datosIncentiveCarga[0]->incentive_id_nueva == $valoressuma['goal_id']) {
                            $cargaboolnueva = true;
                        }
                    }
                    if (!$cargaboolrenovacion) {
                        $datosMetaCarga = $this->Crud_model->obtenerRegistros('produccion_metaventa',array('usuario_id' => $datosGenerales[$identificacion]['usuario_id'],'metaventa_mes'=>$mes),'max(metaventa_nuevas) metaventa_nuevas');
                        if (is_null($datosMetaCarga)) {
                            $datosMetaCarga = 0;
                        }
                        else
                        {
                            $datosMetaCarga = $datosMetaCarga[0]->metaventa_nuevas;
                        }
                        $datosGenerales[$identificacion][$conteodos] = array(
                            'identification' => $identificacion,
                            'goal_id'=>$datosIncentiveCarga[0]->incentive_id_renovacion,
                            'value'=>$datosMetaCarga,
                            'real'=>0 ,
                            'percentage'=>0,
                            'percentage_modified'=>0,
                            'percentage_weighed'=>0,
                            'date'=>$fecha,
                            'created_at'=> $fecha);
                        $conteodos=$conteodos+1;
                    }
                    if (!$cargaboolnueva) {
                        $datosMetaCarga = $this->Crud_model->obtenerRegistros('produccion_metaventa',array('usuario_id' => $datosGenerales[$identificacion]['usuario_id'],'metaventa_mes'=>$mes),'max(metaventa_recompra) metaventa_nuevas');
                        if (is_null($datosMetaCarga)) {
                            $datosMetaCarga = 0;
                        }
                        else
                        {
                            $datosMetaCarga = $datosMetaCarga[0]->metaventa_nuevas;
                        }
                        $datosGenerales[$identificacion][$conteodos] = array(
                            'identification' => $identificacion,
                            'goal_id'=>$datosIncentiveCarga[0]->incentive_id_nueva,
                            'value'=>$datosMetaCarga,
                            'real'=>0 ,
                            'percentage'=>0,
                            'percentage_modified'=>0,
                            'percentage_weighed'=>0,
                            'date'=>$fecha,
                            'created_at'=> $fecha);
                        $conteodos=$conteodos+1;
                    }
                }
                else
                {
                    $cargaboolventa = false;
                    foreach ($key1 as $valoressuma) {
                        if ($datosIncentiveCarga[0]->incentive_id_ventas == $valoressuma['goal_id']) {
                            $cargaboolventa = true;
                        }
                    }
                    if (!$cargaboolventa) {
                        $datosMetaCarga = $this->Crud_model->obtenerRegistros('produccion_metaventa',array('usuario_id' => $datosGenerales[$identificacion]['usuario_id'],'metaventa_mes'=>$mes),'max(metaventa_nuevas)+max(metaventa_recompra) metaventa_nuevas');
                        if (is_null($datosMetaCarga)) {
                            $datosMetaCarga = 0;
                        }
                        else
                        {
                            $datosMetaCarga = $datosMetaCarga[0]->metaventa_nuevas;
                        }
                        $datosGenerales[$identificacion][$conteodos] = array(
                            'identification' => $identificacion,
                            'goal_id'=>$datosIncentiveCarga[0]->incentive_id_ventas,
                            'value'=>$datosMetaCarga,
                            'real'=>0 ,
                            'percentage'=>0,
                            'percentage_modified'=>0,
                            'percentage_weighed'=>0,
                            'date'=>$fecha,
                            'created_at'=> $fecha);
                        $conteodos=$conteodos+1;
                    }
                }      
                $cargaboolvisitas = false;
                foreach ($key1 as $valoressuma) {
                    if ($datosIncentiveCarga[0]->incentive_id_citas == $valoressuma['goal_id']) {
                        $cargaboolvisitas = true;
                    }
                }
                if (!$cargaboolvisitas) {
                    $datosMetaCarga = $this->Crud_model->obtenerRegistros('produccion_metavisita',array('usuario_id' => $datosGenerales[$identificacion]['usuario_id'],'metavisita_mes'=>$mes),'max(metavisita_totales) metaventa_nuevas');
                    if (is_null($datosMetaCarga)) {
                        $datosMetaCarga = 0;
                    }
                    else
                    {
                        $datosMetaCarga = $datosMetaCarga[0]->metaventa_nuevas;
                    }
                    $datosGenerales[$identificacion][$conteodos] = array(
                        'identification' => $identificacion,
                        'goal_id'=>$datosIncentiveCarga[0]->incentive_id_citas,
                        'value'=>$datosMetaCarga,
                        'real'=>0 ,
                        'percentage'=>0,
                        'percentage_modified'=>0,
                        'percentage_weighed'=>0,
                        'date'=>$fecha,
                        'created_at'=> $fecha);
                    $conteodos=$conteodos+1;
                }
            }
            
            //var_dump(json_encode($datosGenerales));
            $datos = array(
                'cargo_1' => array('datos'=>array(),'conteo'=>0),
                'cargo_2' => array('datos'=>array(),'conteo'=>0),
                'cargo_3' => array('datos'=>array(),'conteo'=>0),
                'cargo_4' => array('datos'=>array(),'conteo'=>0),
                'cargo_5' => array('datos'=>array(),'conteo'=>0),
                'cargo_6' => array('datos'=>array(),'conteo'=>0),
                'cargo_7' => array('datos'=>array(),'conteo'=>0),
                'cargo_8' => array('datos'=>array(),'conteo'=>0)
            );
            foreach ($datosGenerales as $key2) {
                foreach ($datosUsuario as $usuarioUnidad) {
                    if ($usuarioUnidad->usuario_documento == $key2['identification']) {
                        $key2['datos'] = $usuarioUnidad;
                    }
                }
                $datos['cargo_'.$key2['cargo_id']]['datos'][$datos['cargo_'.$key2['cargo_id']]['conteo']] = $key2; 
                $datos['cargo_'.$key2['cargo_id']]['conteo'] = $datos['cargo_'.$key2['cargo_id']]['conteo']+1;
            }
            //echo "<br>";
            //var_dump(json_encode($datos));
            $this->listaConcesionariosToExcel($mes,$datos,$datosPantalla,$valorCampo);
        }
        //$this->controlador('exportgeneral');
    }
    public function listaConcesionariosToExcel($mes = '07',$datos,$datosPantalla,$valorCampo)
    {
        $contador=0;
        
        $valorRetorno = '
            <table cellspacing = "0" cellpadding = "0" border = "1" >
                <tr >
                    <td> Nombre</td>
                    <td> Apellido</td>
                    <td> Correo</td>
                    <td> Cedula</td>
                    <td> Nomina </td>
                    <td> Mes</td>
                    <td> Ventas Renovacion Meta</td>
                    <td> Ventas Renovacion Real</td>
                    <td> Ventas Renovacion %</td>
                    <td> Ventas Renovacion puntos</td>

                    <td> Ventas Nuevas Meta</td>
                    <td> Ventas Nuevas Real</td>
                    <td> Ventas Nuevas %</td>
                    <td> Ventas Nuevas puntos</td>

                    <td> Ventas Meta</td>
                    <td> Ventas Real</td>
                    <td> Ventas %</td>
                    <td> Ventas puntos</td>

                    <td> Visitas Meta</td>
                    <td> Visitas Real</td>
                    <td> Visitas %</td>
                    <td> Visitas puntos</td>

                    <td> Test Meta</td>
                    <td> Test Real</td>
                    <td> Test %</td>
                    <td> Test puntos</td>

                    <td> Grupal Meta</td>
                    <td> Grupal Real</td>
                    <td> Grupal %</td>
                    <td> Grupal puntos</td>

                    <td> Perfil</td>
                    <td> Total</td>
                </tr >  ';
        //var_dump(json_encode($datos));
        foreach ($datos as $key) 
        {
            if ($key["conteo"] > 0) {
                foreach ($key["datos"] as $item) {
                    //var_dump(json_encode($item));
                    $renovacion = null;
                    $nuevo = null;
                    $ventas= null;
                    $llamadas = null;
                    $test = null;
                    $grupal = null;
                    if (!is_null($item["datos"])) 
                    {
                        for ($i=0; $i < count($item); $i++) { 
                            if (isset($item[$i])) {
                                if ($item[$i]["goal_id"]-35 > 0) {
                                    $divisor =4;
                                    $goal_id=$item[$i]["goal_id"]-35;
                                    $datoAjustedo = (($goal_id/$divisor)-intval(($goal_id/$divisor)))*100;
                                    switch (strval($datoAjustedo)) {
                                        case 25:
                                            $ventas = $item[$i];
                                        break;
                                        case 50:
                                            $llamadas = $item[$i];
                                        break;
                                        case 75:
                                            $test = $item[$i];
                                        break;
                                        case 0:
                                            $grupal = $item[$i];
                                        break;
                                    }
                                }
                                else
                                {
                                    $divisor =5;
                                    $goal_id=$item[$i]["goal_id"];
                                    $datoAjustedo = (($goal_id/$divisor)-intval(($goal_id/$divisor)))*100;
                                    switch (strval($datoAjustedo)) {
                                        case 20:
                                            $renovacion = $item[$i];
                                        break;
                                        case 40:
                                            $nuevo = $item[$i];
                                        break;
                                        case 60:
                                            $llamadas = $item[$i];
                                        break;
                                        case 80:
                                            $test = $item[$i];
                                        break;
                                        case 0:
                                            $grupal = $item[$i];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    if (isset($item["datos"]->usuario_nombre)) {
                        $valorRetorno .= '<tr>
                            <td> '.$item["datos"]->usuario_nombre.'</td>
                            <td> '.$item["datos"]->usuario_apellido.'</td>
                            <td> '.$item["datos"]->usuario_correo.'</td>
                            <td> '.$item['identification'].'</td>
                            <td> '.$item["datos"]->usuario_codigonomina.' </td>
                            <td> '.$mes.'</td>
                            <td> '.str_replace('.',',',(double) $renovacion["value"]).'</td>
                            <td> '.str_replace('.',',',(double) $renovacion["real"]).'</td>
                            <td> '.str_replace('.',',',(double) $renovacion["percentage"]).'</td>
                            <td> '.str_replace('.',',',(double) $renovacion["percentage_weighed"]).'</td>

                            <td> '.str_replace('.',',',(double) $nuevo["value"]).'</td>
                            <td> '.str_replace('.',',',(double) $nuevo["real"]).'</td>
                            <td> '.str_replace('.',',',(double) $nuevo["percentage"]).'</td>
                            <td> '.str_replace('.',',',(double) $nuevo["percentage_weighed"]).'</td>

                            <td> '.str_replace('.',',',(double) $ventas["value"]).'</td>
                            <td> '.str_replace('.',',',(double) $ventas["real"]).'</td>
                            <td> '.str_replace('.',',',(double) $ventas["percentage"]).'</td>
                            <td> '.str_replace('.',',',(double) $ventas["percentage_weighed"]).'</td>

                            <td> '.str_replace('.',',',(double) $llamadas["value"]).'</td>
                            <td> '.str_replace('.',',',(double) $llamadas["real"]).'</td>
                            <td> '.str_replace('.',',',(double) $llamadas["percentage"]).'</td>
                            <td> '.str_replace('.',',',(double) $llamadas["percentage_weighed"]).'</td>

                            <td> '.str_replace('.',',',(double) $test["value"]).'</td>
                            <td> '.str_replace('.',',',(double) $test["real"]).'</td>
                            <td> '.str_replace('.',',',(double) $test["percentage"]).'</td>
                            <td> '.str_replace('.',',',(double) $test["percentage_weighed"]).'</td>

                            <td> '.str_replace('.',',',(double) $grupal["value"]).'</td>
                            <td> '.str_replace('.',',',(double) $grupal["real"]).'</td>
                            <td> '.str_replace('.',',',(double) $grupal["percentage"]).'</td>
                            <td> '.str_replace('.',',',(double) $grupal["percentage_weighed"]).'</td>
                            
                            <td> '.$item["datos"]->cargo_nombre.'</td>
                            <td> '.str_replace('.',',',(double) $item['suma']).'</td>
                        </tr>';
                    }
                }
            }
        }
        $valorRetorno .= '</table>';
        if (is_null($datosPantalla)) {
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=lista_puntosmes".$mes."-".date('Y-m-d') . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
        }
        echo $valorRetorno;
    }
    public function guardar($tabla= null)
    {
        $url = $this->input->post("URL", TRUE);
        $inputFileType =$this->obtenerExtensionFichero($url);
        if ($inputFileType == 'xls') {
            $data = '.'.$url;
            define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
            if (!file_exists($data)) {
                exit("Please run 14excel5.php first.\n");
            }
            $objPHPExcel = PHPExcel_IOFactory::load($data);
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $columnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $mensaje = '';
            for ($in = 0; $in < $columnIndex; $in++):
                $columnString = PHPExcel_Cell::stringFromColumnIndex($in);
                $arraywhere = array(
                    'c.columna_nombre like ' => '%'.$sheet->getCell($columnString . "1").'%',
                    'p.tabla_nombre' => strtolower($tabla)
                    );
                $valorCampo = $this->Crud_tabla->GetDatos($arraywhere,null,null,'*')[0];
                if(is_null($valorCampo)){
                    $mensaje = 'La columna del excel '.$sheet->getCell($columnString . "1").' no existe en la tabla';
                    $in = $columnIndex;
                }else
                {
                    $columnaTitulo[$in] = array(
                        'columnaexcel' => $sheet->getCell($columnString . "1").'',
                        'columnatabla' => $valorCampo->columna_relacion,
                        'tabla' => $valorCampo->tabla_nombre,
                        'columna_tipo' => $valorCampo->columna_tipo,
                        'columna_remplasa' => $valorCampo->columna_remplasa,
                        'valor'=> '',
                        'columna_insert' => $valorCampo->columna_insert
                    );
                }
            endfor;
            //var_dump($columnaTitulo);
            if ($mensaje == '') 
            {
                $selectArray = "p.tabla_nombre ='".strtolower($tabla)."' and c.columna_tipo = 'fijo' or c.columna_tipo = 'random' ";
                $valorCampoFijos = $this->Crud_tabla->GetDatos($selectArray,null,null,'*');
                foreach ($valorCampoFijos as $key) {
                    $columnaTitulo[$in] = array(
                        'columnaexcel' => null,
                        'columnatabla' => $key->columna_relacion,
                        'tabla' => $key->tabla_nombre,
                        'columna_tipo' => $key->columna_tipo,
                        'columna_remplasa' => $key->columna_remplasa,
                        'valor'=> '',
                        'columna_insert' => $key->columna_insert
                    );
                    $in= $in+1;
                }
                
                for ($m = 2; $m <= $highestRow; $m++): // RECORRE EL NUMERO DE filter_input_array(type)S QUE TIENE EL ARCHIVO EXCEL
                    //var_dump($columnaTitulo);
                    //echo "<br>";
                    $columnaTitulo = $this->limpiarArray($columnaTitulo);
                    //var_dump($columnaTitulo);
                    //echo "<br>";
                    for ($i = 0; $i < $columnIndex; $i++): 
                        $columnString = PHPExcel_Cell::stringFromColumnIndex($i);
                        $columnaTitulo[$i]['valor'] = $sheet->getCell($columnString.$m)->getFormattedValue().'';
                    endfor;  
                    //var_dump(json_encode($columnaTitulo));echo "<br>";  

                    $lista = $this->crearArray($columnaTitulo,strtolower($tabla));
                    //var_dump(json_encode($lista));
                    if (count($lista) == 0) {

                    }
                    else
                    {
                        //var_dump($lista);
                        $this->Crud_model->agregarRegistro('produccion_'.strtolower($tabla),$lista);
                    }
                endfor;
                $this->Crud_log->Insertar('Carga '.$tabla,1,json_encode(date('Y-m-d H:i:s',$this->ajusteFecha)));
                $campos = $this->buscarParametria($tabla);
                if ($campos['tabla_jobfin'] != '' and !is_null($campos['tabla_jobfin'])) {
                    $retorno = $this->consultaRest($campos['tabla_jobfin'],'GET',null,base_url());
                    if (!$retorno["estado"]) {
                        $this->mensajeExterno = 'error en ejecutar join ';
                        $mensaje =-2;
                        $this->controlador($tabla,$mensaje);
                    }
                    else
                    {
                        //var_dump($retorno["datos"]);
                        $this->controlador($tabla,$mensaje);      
                    }
                }
                else
                {
                    $this->controlador($tabla,$mensaje);
                }
            }else
            {
                $this->mensajeExterno = $mensaje;
                $mensaje =-2;
                $this->controlador($tabla,$mensaje);
            }
        }else
        {
            $mensaje = -3;
            $this->controlador($tabla,$mensaje);
        }
    }
    public function crearArray($columnaTitulo1,$tabla)
    {
        //columna_insert
        $lista = array();
        $cargaExterna = false;
        for ($i=0; $i < count($columnaTitulo1); $i++) { 
            if ($columnaTitulo1[$i]["tabla"] == $tabla) {
                if ($columnaTitulo1[$i]['columnatabla'] != '') {
                    switch ($columnaTitulo1[$i]["columna_tipo"]) {
                        case 'fecha':
                            $lista =array_merge($lista, array($columnaTitulo1[$i]['columnatabla'] => date($this->formatoFecha,strtotime($columnaTitulo1[$i]['valor']))));
                        break;
                        case 'texto':
                            $lista =array_merge($lista, array($columnaTitulo1[$i]['columnatabla'] => $columnaTitulo1[$i]['valor']));
                        break;
                        case 'boolean':
                            $retorno = ($columnaTitulo1[$i]['valor'] == '0') ? false : true ;
                            $lista =array_merge($lista, array($columnaTitulo1[$i]['columnatabla'] => $retorno));
                        break;
                        case 'unico':
                            $buscar = array($columnaTitulo1[$i]["columnatabla"] => $columnaTitulo1[$i]["valor"]);
                            $datosBuscar = $this->Crud_model->obtenerRegistros('produccion_'.strtolower($tabla),$buscar);
                            if (is_null($datosBuscar)) {
                                $lista =array_merge($lista, array($columnaTitulo1[$i]['columnatabla'] => $columnaTitulo1[$i]['valor']));
                            }
                            else
                            {
                                $lista =array_merge($lista, array($columnaTitulo1[$i]['columnatabla'] => $columnaTitulo1[$i]['valor']));
                                //temporal verificar actualizacion de datos pendiente por carga 
                                if ($datosBuscar[0]->cargo_id == '') {
                                    $cargaExterna = true;
                                }
                                else
                                {
                                    $cargaExterna = true;
                                }
                            }
                        break;
                        case 'busqueda':
                            $wheredinamico = array($columnaTitulo1[$i]["columna_remplasa"] => $columnaTitulo1[$i]["valor"]);
                            $valores = $this->Crud_model->obtenerRegistros('basica_'.$columnaTitulo1[$i]["columnatabla"],$wheredinamico);
                            if (!is_null($valores)) {
                                $array = json_decode(json_encode($valores[0]), true);
                                //var_dump($array[$columnaTitulo1[$i]["columnatabla"].'_id']);
                                $lista =array_merge($lista, array($columnaTitulo1[$i]["columnatabla"].'_id' => $array[$columnaTitulo1[$i]["columnatabla"].'_id']));
                            }
                            else
                            {
                                if ((int) $columnaTitulo1[$i]["columna_insert"] == 1) 
                                {
                                    $insertBusqueda = array($columnaTitulo1[$i]["columnatabla"].'_nombre' => $columnaTitulo1[$i]["valor"]);
                                    $this->Crud_model->agregarRegistro('basica_'.$columnaTitulo1[$i]["columnatabla"],$insertBusqueda);
                                    $wheredinamico = array($columnaTitulo1[$i]["columna_remplasa"] => $columnaTitulo1[$i]["valor"]);
                                    $valores = $this->Crud_model->obtenerRegistros('basica_'.$columnaTitulo1[$i]["columnatabla"],$wheredinamico);
                                    if (!is_null($valores)) {
                                        $array = json_decode(json_encode($valores[0]), true);
                                        //var_dump($array[$columnaTitulo1[$i]["columnatabla"].'_id']);
                                        $lista =array_merge($lista, array($columnaTitulo1[$i]["columnatabla"].'_id' => $array[$columnaTitulo1[$i]["columnatabla"].'_id']));
                                    }
                                }
                            }
                        break;
                        case 'fijo':
                            if ($columnaTitulo1[$i]['columna_remplasa'] == 'now') {
                                $lista =array_merge($lista, array($columnaTitulo1[$i]['columnatabla'] => date('Y-m-d',$this->ajusteFecha)));    
                            }
                            else
                            {
                                $lista =array_merge($lista, array($columnaTitulo1[$i]['columnatabla'] => $columnaTitulo1[$i]['columna_remplasa']));    
                            }
                        break;
                        case 'random':
                            $lista =array_merge($lista, array($columnaTitulo1[$i]['columnatabla'] => $this->generarCodigo($columnaTitulo1[$i]['columna_remplasa'])));    
                        break;
                        default:
                            $lista =array_merge($lista, array($columnaTitulo1[$i]['columnatabla'] => $columnaTitulo1[$i]['valor']));
                        break;
                    }
                }
            }
        }
        if ($cargaExterna) 
        {
            $this->Crud_model->agregarRegistro('produccion_update'.$tabla,$lista);
            $lista = array();
            return $lista;
        }
        else
        {
            return $lista;
        }
    }
    public function limpiarArray($columnaTitulo1)
    {
        for ($i=0; $i < count($columnaTitulo1); $i++) { 
            $columnaTitulo1[$i]['valor']= '';
        }
        return $columnaTitulo1;

    }
    public function exportarParametros($tabla= null)
    {
        $arraywhere = array(
            'p.tabla_nombre' => strtolower($tabla)
        );
        $valorCampo = $this->Crud_campo->GetDatosMetaGrupo(array_merge($arraywhere,array('c.campo_oblicatorio' =>1)));
        $whereDinamico = array();
        foreach ($valorCampo as $key) {
            switch ($key->campo_value) {
                case '[[NOT NULL]]':
                    $tempoArray = array($key->campo_nombre.' !=' => null);
                break;
                default:
                    $tempoArray = array($key->campo_nombre => $key->campo_value);
                break;
            }
            
            $whereDinamico = array_merge($tempoArray,$whereDinamico);
        }
        $valorjoin = $this->Crud_tabla->getJoin($arraywhere);
        $datos = $this->Crud_model->obtenerRegistros('produccion_usuario',$whereDinamico,null,null,null,$valorjoin);
        $this->listaToExcel($datos,null,$this->Crud_tabla->GetDatos($arraywhere));
    }
    public function listaToExcel($datos,$datosPantalla= null,$valorCampo)
    {
        $contador=0;
        $valorRetorno = '<table cellspacing = "0" cellpadding = "0" border = "1" ><tr >';

        foreach ($valorCampo as $key) {
            $valorRetorno .= '<td>'.$key->columna_nombre.'</td>';
        }
        $valorRetorno .= '<tr>';
        foreach ($datos as $key1) 
        {
            $valorRetorno .= '<tr>';
            foreach ($valorCampo as $key2) {
                $valorRetorno .= '<td>'.$key1->{$key2->columna_relacion}.'</td>';
            }
            $valorRetorno .= '</tr>';
        }
        $valorRetorno .= '</table>';
        if (is_null($datosPantalla)) {
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=lista_exportable-".date('Y-m-d H:i:s',$this->ajusteFecha) . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
        }
        echo $valorRetorno;
    }

}



