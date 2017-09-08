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
    public function exportar($tabla= null,$datosPantalla = null)
    {
        if ($tabla == 'exportgeneral') {
            $dia = '01';
            $mes = '07';
            $ano = '2017';
            $fecha = $ano.'-'.$mes.'-'.$dia;
            $datosUsuario = $this->Crud_usuario->GetDatos(array('p.estado_id' => 1,'p.rol_id' => 7));
            $datosIncentive =  $this->consultaRest('/api/clients/3/dategoalvalues/'.$fecha,'GET');
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
                //var_dump(json_encode($datosGenerales));
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
            //var_dump(json_encode($datosGenerales));
            $cargo1 = array();
            $conteo1 =0;
            $cargo2 = array();
            $conteo2 =0;
            $cargo3 = array();
            $conteo3 =0;
            $cargo4 = array();
            $conteo4 =0;
            $cargo5 = array();
            $conteo5 =0;
            $cargo6 = array();
            $conteo6 =0;
            $cargo7 = array();
            $conteo7 =0;
            foreach ($datosGenerales as $key2) {
                switch ($key2['cargo_id']) {
                    case '1':
                        foreach ($datosUsuario as $usuarioUnidad) {
                            if ($usuarioUnidad->usuario_documento == $key2['identification']) {
                                $key2['datos'] = $usuarioUnidad;
                            }
                        }
                        $cargo1[$conteo1] = $key2; 
                        $conteo1 = $conteo1+1;
                    break;
                    case '2':
                        foreach ($datosUsuario as $usuarioUnidad) {
                            if ($usuarioUnidad->usuario_documento == $key2['identification']) {
                                $key2['datos'] = $usuarioUnidad;
                            }
                        }
                        $cargo2[$conteo2] = $key2; 
                        $conteo2 = $conteo2+1;
                    break;
                    case '3':
                        foreach ($datosUsuario as $usuarioUnidad) {
                            if ($usuarioUnidad->usuario_documento == $key2['identification']) {
                                $key2['datos'] = $usuarioUnidad;
                            }
                        }
                        $cargo3[$conteo3] = $key2; 
                        $conteo3 = $conteo3+1;
                    break;
                    case '4':
                        foreach ($datosUsuario as $usuarioUnidad) {
                            if ($usuarioUnidad->usuario_documento == $key2['identification']) {
                                $key2['datos'] = $usuarioUnidad;
                            }
                        }
                        $cargo4[$conteo4] = $key2; 
                        $conteo4 = $conteo4+1;
                    break;
                    case '5':
                        foreach ($datosUsuario as $usuarioUnidad) {
                            if ($usuarioUnidad->usuario_documento == $key2['identification']) {
                                $key2['datos'] = $usuarioUnidad;
                            }
                        }
                        $cargo5[$conteo5] = $key2; 
                        $conteo5 = $conteo5+1;
                    break;
                    case '6':
                        foreach ($datosUsuario as $usuarioUnidad) {
                            if ($usuarioUnidad->usuario_documento == $key2['identification']) {
                                $key2['datos'] = $usuarioUnidad;
                            }
                        }
                        $cargo6[$conteo6] = $key2; 
                        $conteo6 = $conteo6+1;
                    break;
                    case '7':
                        foreach ($datosUsuario as $usuarioUnidad) {
                            if ($usuarioUnidad->usuario_documento == $key2['identification']) {
                                $key2['datos'] = $usuarioUnidad;
                            }
                        }
                        $cargo7[$conteo7] = $key2; 
                        $conteo7 = $conteo7+1;
                    break;
                }
            }
            //var_dump(json_encode($cargo1));
            $this->listaConcesionariosToExcel('07',$cargo1,$cargo2,$cargo3,$cargo4,$cargo5,$cargo6,$cargo7,$datosPantalla);
        }
    }
    /*Excel concesionarios*/
    public function listaConcesionariosToExcel($mes = '07',$cargo1,$cargo2,$cargo3,$cargo4,$cargo5,$cargo6,$cargo7,$datosPantalla)
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
                    <td> Visitas %</td>
                    <td> Test %</td>
                    <td> Grupal %</td>
                    <td> Venas Renovacion puntos</td>
                    <td> Venas Nuevas puntos</td>
                    <td> Visitas puntos</td>
                    <td> Test puntos</td>
                    <td> Grupal puntos</td>
                    <td> Perfil</td>
                    <td> Total</td>
                </tr >  ';

        
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
                        'valor'=> '' 
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
                        'valor'=> '' 
                    );
                    $in= $in+1;
                }
                
                for ($m = 2; $m <= $highestRow; $m++): // RECORRE EL NUMERO DE FILAS QUE TIENE EL ARCHIVO EXCEL
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



