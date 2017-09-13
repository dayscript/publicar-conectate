<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cargatablas extends MY_Controller {


	public function __construct() {
        parent::__construct();
        $this->load->model('crud/Crud_noticias');
        $this->load->model('crud/Crud_usuario');
        $this->load->model('crud/Crud_menu');
        $this->load->library("Excel/Excel");
        $this->load->model('crud/Crud_tabla');
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
            $dataSend = array(
                "footer" => $dataFooter,
                'nav' => $datoNav,
                'error' => $mensaje,
                'datosCarga' => $datosEditar,
                'datos' =>$campos
            );
            switch ((int)$campos['tipoaccion']) {
                case 1:
                    $this->load->view('admin/controler_view',$dataSend);
                break;
                case 2:
                    $this->load->view('admin/controlerexport_view',$dataSend);
                break;
            }
        }
        else
        {
            $this->redirecionar($this->session->userdata('rol_id'));
        }
    }
    public function exportar($tabla= null,$fecha = null,$dominio_id,$datosPantalla = null)
    {
        if (is_null($fecha) or (int) $fecha == 1) {
            $fecha=date('Y-m-d',$this->ajusteFecha);
            $ano =  date('Y',$this->ajusteFecha);
            $mes =  date('m',$this->ajusteFecha);
            $dia =  date('d',$this->ajusteFecha);
            $fecha = $ano.'-'.$mes.'-01';
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
        if ($tabla == 'exportgeneral' and !is_null($dominio)) {
            
            $datosUsuario = $this->Crud_usuario->GetDatos(array('p.estado_id' => 1,'p.rol_id' => 7,'p.empresalegal_id'=>$dominio[0]->empresalegal_id));
            $datosIncentive =  $this->consultaRest('/api/clients/'.$dominio[0]->codigo_incentive.'/dategoalvalues/'.$fecha,'GET');
            $datosGenerales = array();
            if (count($datosIncentive) > 0) {
                foreach ($datosIncentive['goal_values'] as $key) {
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
                }
                
            }
            //var_dump($datosGenerales);
            foreach ($datosGenerales as $key1) {
                $suma =0;
                $identificacion = $this->returnIdentificacion($key1);
                foreach ($key1 as $valoressuma) {
                    $suma = $suma + $valoressuma['percentage_weighed'];
                    $goal_id = $valoressuma['goal_id'];
                }
                $datos = array('suma' => $suma,'identification' =>$identificacion,'cargo_id' => $this->idCategoria($goal_id),'datos'=>null);
                $datosGenerales[$identificacion] = array_merge($datosGenerales[$identificacion],$datos);             
            }
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
            $this->listaConcesionariosToExcel($mes,$datos,$datosPantalla);
            
        }
    }
    /*Excel concesionarios*/
    public function listaConcesionariosToExcel($mes = '07',$datos,$datosPantalla)
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
                    <td> Venas Renovacion %</td>
                    <td> Venas Nuevas %</td>
                    <td> Venas %</td>
                    <td> Visitas %</td>
                    <td> Test %</td>
                    <td> Grupal %</td>
                    <td> Ventas Renovacion puntos</td>
                    <td> Ventas Nuevas puntos</td>
                    <td> Ventas puntos</td>
                    <td> Visitas puntos</td>
                    <td> Test puntos</td>
                    <td> Grupal puntos</td>
                    <td> Perfil</td>
                    <td> Total</td>
                </tr >  ';

        foreach ($datos as $key) 
        {
            if ($key["conteo"] > 0) {
                foreach ($key["datos"] as $item) {
                    //var_dump(json_encode($key2));
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
                    $valorRetorno .= '<tr>
                        <td> '.$item["datos"]->usuario_nombre.'</td>
                        <td> '.$item["datos"]->usuario_apellido.'</td>
                        <td> '.$item["datos"]->usuario_correo.'</td>
                        <td> '.$item['identification'].'</td>
                        <td> '.$item["datos"]->usuario_codigonomina.' </td>
                        <td> '.$mes.'</td>
                        <td> '.str_replace('.',',',(double) $renovacion["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $ventas["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage"]).'</td>

                        <td> '.str_replace('.',',',(double) $renovacion["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $ventas["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage_weighed"]).'</td>
                        <td> '.$item["datos"]->cargo_nombre.'</td>
                        <td> '.str_replace('.',',',(double) $item['suma']).'</td>
                    </tr>';
                }
            }
            break;
        }
        /*
        foreach ($cargo1 as $item):
            $renovacion = null;
            $nuevo = null;
            $llamadas = null;
            $test = null;
            $grupal = null;
            if (!is_null($item["datos"])) 
            {
                for ($i=0; $i < count($item); $i++) { 
                    if (isset($item[$i])) {
                        if ($item[$i]["goal_id"] == 1) {
                            $renovacion = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 2) {
                            $nuevo = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 3) {
                            $llamadas = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 4) {
                            $test = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 5) {
                            $grupal = $item[$i];
                        }
                    }
                }

                $bodytag = str_replace("%body%", "black", "<body text='%body%'>");
                $valorRetorno .= '<tr>
                        <td> '.$item["datos"]->usuario_nombre.'</td>
                        <td> '.$item["datos"]->usuario_apellido.'</td>
                        <td> '.$item["datos"]->usuario_correo.'</td>
                        <td> '.$item['identification'].'</td>
                        <td> '.$item["datos"]->usuario_codigonomina.' </td>
                        <td> '.$mes.'</td>
                        <td> '.str_replace('.',',',(double) $renovacion["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage"]).'</td>

                        <td> '.str_replace('.',',',(double) $renovacion["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage_weighed"]).'</td>
                        <td> '.$item["datos"]->cargo_nombre.'</td>
                        <td> '.str_replace('.',',',(double) $item['suma']).'</td>
                    </tr>';
            }
        endforeach;
        */
        /*
        foreach ($cargo2 as $item):
            $renovacion = null;
            $nuevo = null;
            $llamadas = null;
            $test = null;
            $grupal = null;
            if (!is_null($item["datos"])) 
            {
                for ($i=0; $i < count($item); $i++) { 
                    if (isset($item[$i])) {
                        if ($item[$i]["goal_id"] == 6) {
                            $renovacion = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 7) {
                            $nuevo = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 8) {
                            $llamadas = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 9) {
                            $test = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 10) {
                            $grupal = $item[$i];
                        }
                    }
                }
                $valorRetorno .= '<tr>
                        <td> '.$item["datos"]->usuario_nombre.'</td>
                        <td> '.$item["datos"]->usuario_apellido.'</td>
                        <td> '.$item["datos"]->usuario_correo.'</td>
                        <td> '.$item['identification'].'</td>
                        <td> '.$item["datos"]->usuario_codigonomina.' </td>
                        <td> '.$mes.'</td>
                        <td> '.str_replace('.',',',(double) $renovacion["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage"]).'</td>

                        <td> '.str_replace('.',',',(double) $renovacion["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage_weighed"]).'</td>
                        <td> '.$item["datos"]->cargo_nombre.'</td>
                        <td> '.str_replace('.',',',(double) $item['suma']).'</td>
                    </tr>';
            }
        endforeach;
        foreach ($cargo3 as $item):
            $renovacion = null;
            $nuevo = null;
            $llamadas = null;
            $test = null;
            $grupal = null;
            if (!is_null($item["datos"])) 
            {
                for ($i=0; $i < count($item); $i++) { 
                    if (isset($item[$i])) {
                        if ($item[$i]["goal_id"] == 11) {
                            $renovacion = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 12) {
                            $nuevo = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 13) {
                            $llamadas = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 14) {
                            $test = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 15) {
                            $grupal = $item[$i];
                        }
                    }
                }
                $valorRetorno .= '<tr>
                        <td> '.$item["datos"]->usuario_nombre.'</td>
                        <td> '.$item["datos"]->usuario_apellido.'</td>
                        <td> '.$item["datos"]->usuario_correo.'</td>
                        <td> '.$item['identification'].'</td>
                        <td> '.$item["datos"]->usuario_codigonomina.' </td>
                        <td> '.$mes.'</td>
                        <td> '.str_replace('.',',',(double) $renovacion["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage"]).'</td>

                        <td> '.str_replace('.',',',(double) $renovacion["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage_weighed"]).'</td>
                        <td> '.$item["datos"]->cargo_nombre.'</td>
                        <td> '.str_replace('.',',',(double) $item['suma']).'</td>
                    </tr>';
            }
        endforeach;
        foreach ($cargo4 as $item):
            $renovacion = null;
            $nuevo = null;
            $llamadas = null;
            $test = null;
            $grupal = null;
            if (!is_null($item["datos"])) 
            {
                for ($i=0; $i < count($item); $i++) { 
                    if (isset($item[$i])) {
                        if ($item[$i]["goal_id"] == 16) {
                            $renovacion = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 17) {
                            $nuevo = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 18) {
                            $llamadas = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 19) {
                            $test = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 20) {
                            $grupal = $item[$i];
                        }
                    }
                }
                $valorRetorno .= '<tr>
                        <td> '.$item["datos"]->usuario_nombre.'</td>
                        <td> '.$item["datos"]->usuario_apellido.'</td>
                        <td> '.$item["datos"]->usuario_correo.'</td>
                        <td> '.$item['identification'].'</td>
                        <td> '.$item["datos"]->usuario_codigonomina.' </td>
                        <td> '.$mes.'</td>
                        <td> '.str_replace('.',',',(double) $renovacion["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage"]).'</td>

                        <td> '.str_replace('.',',',(double) $renovacion["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage_weighed"]).'</td>
                        <td> '.$item["datos"]->cargo_nombre.'</td>
                        <td> '.str_replace('.',',',(double) $item['suma']).'</td>
                    </tr>';
            }
        endforeach;
        foreach ($cargo5 as $item):
            $renovacion = null;
            $nuevo = null;
            $llamadas = null;
            $test = null;
            $grupal = null;
            if (!is_null($item["datos"])) 
            {
                for ($i=0; $i < count($item); $i++) { 
                    if (isset($item[$i])) {
                        if ($item[$i]["goal_id"] == 21) {
                            $renovacion = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 22) {
                            $nuevo = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 23) {
                            $llamadas = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 24) {
                            $test = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 25) {
                            $grupal = $item[$i];
                        }
                    }
                }
                $valorRetorno .= '<tr>
                        <td> '.$item["datos"]->usuario_nombre.'</td>
                        <td> '.$item["datos"]->usuario_apellido.'</td>
                        <td> '.$item["datos"]->usuario_correo.'</td>
                        <td> '.$item['identification'].'</td>
                        <td> '.$item["datos"]->usuario_codigonomina.' </td>
                        <td> '.$mes.'</td>
                        <td> '.str_replace('.',',',(double) $renovacion["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage"]).'</td>

                        <td> '.str_replace('.',',',(double) $renovacion["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage_weighed"]).'</td>
                        <td> '.$item["datos"]->cargo_nombre.'</td>
                        <td> '.str_replace('.',',',(double) $item['suma']).'</td>
                    </tr>';
            }
        endforeach;
        foreach ($cargo6 as $item):
            $renovacion = null;
            $nuevo = null;
            $llamadas = null;
            $test = null;
            $grupal = null;
            if (!is_null($item["datos"])) 
            {
                for ($i=0; $i < count($item); $i++) { 
                    if (isset($item[$i])) {
                        if ($item[$i]["goal_id"] == 26) {
                            $renovacion = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 27) {
                            $nuevo = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 28) {
                            $llamadas = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 29) {
                            $test = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 30) {
                            $grupal = $item[$i];
                        }
                    }
                }
                $valorRetorno .= '<tr>
                        <td> '.$item["datos"]->usuario_nombre.'</td>
                        <td> '.$item["datos"]->usuario_apellido.'</td>
                        <td> '.$item["datos"]->usuario_correo.'</td>
                        <td> '.$item['identification'].'</td>
                        <td> '.$item["datos"]->usuario_codigonomina.' </td>
                        <td> '.$mes.'</td>
                        <td> '.str_replace('.',',',(double) $renovacion["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage"]).'</td>

                        <td> '.str_replace('.',',',(double) $renovacion["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage_weighed"]).'</td>
                        <td> '.$item["datos"]->cargo_nombre.'</td>
                        <td> '.str_replace('.',',',(double) $item['suma']).'</td>
                    </tr>';
            }
        endforeach;
        foreach ($cargo7 as $item):
            $renovacion = null;
            $nuevo = null;
            $llamadas = null;
            $test = null;
            $grupal = null;
            if (!is_null($item["datos"])) 
            {
                for ($i=0; $i < count($item); $i++) { 
                    if (isset($item[$i])) {
                        if ($item[$i]["goal_id"] == 31) {
                            $renovacion = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 32) {
                            $nuevo = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 33) {
                            $llamadas = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 34) {
                            $test = $item[$i];
                        }
                        if ($item[$i]["goal_id"] == 35) {
                            $grupal = $item[$i];
                        }
                    }
                }
                $valorRetorno .= '<tr>
                        <td> '.$item["datos"]->usuario_nombre.'</td>
                        <td> '.$item["datos"]->usuario_apellido.'</td>
                        <td> '.$item["datos"]->usuario_correo.'</td>
                        <td> '.$item['identification'].'</td>
                        <td> '.$item["datos"]->usuario_codigonomina.' </td>
                        <td> '.$mes.'</td>
                        <td> '.str_replace('.',',',(double) $renovacion["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage"]).'</td>

                        <td> '.str_replace('.',',',(double) $renovacion["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $nuevo["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $llamadas["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $test["percentage_weighed"]).'</td>
                        <td> '.str_replace('.',',',(double) $grupal["percentage_weighed"]).'</td>
                        <td> '.$item["datos"]->cargo_nombre.'</td>
                        <td> '.str_replace('.',',',(double) $item['suma']).'</td>
                    </tr>';
            }
        endforeach;
        */

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
                $mensaje =-1;
                $this->controlador($tabla,$mensaje);
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

}



