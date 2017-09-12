<?php

class MY_Controller extends CI_Controller
{
	public $Titulo;
	public $index;
	public $NombreApp;
    public $logo;
	public $analitickey;
	public $AGILE_DOMAIN;
    public $AGILE_USER_EMAIL;
    public $AGILE_REST_API_KEY;
    public $AGILE_JS_API_KEY;
    public $formatoFecha;
    public $formatoFechaAgile;
    public $incentive;
    public $ajusteFecha;
    public $PoweredBy;
    public $recaptcha;
    public $urlServicesDrupal;
    public $usuarioServicesDrupal;
    public $claveServicesDrupal;
    public $urlambiente;


	function __construct()
    {
        parent::__construct();
        $this->cargarVariablesGlobales();
        $this->load->model('crud/Crud_rol');
        $this->load->model('crud/Crud_usuario');
        $this->load->model('crud/Crud_grupo');
        $this->load->library('agileRes/Curlwrap');
        $this->load->library('basic_RestClient/My_restclient','My_restclient');
    }
    public function cargarVariablesGlobales()
    {
        if (is_null($this->index)) {
            $this->index = $this->Crud_parametria->obtenerParametria('index');
        }
        if (is_null($this->Titulo)) {
            $this->Titulo = $this->Crud_parametria->obtenerParametria('Titulo');
        }
        if (is_null($this->NombreApp)) {
            $this->NombreApp = $this->Crud_parametria->obtenerParametria('NombreApp');
        }
        if (is_null($this->analitickey)) {
            $this->analitickey = $this->Crud_parametria->obtenerParametria('analitickey');
        }
        if (is_null($this->AGILE_DOMAIN)) {
            $this->AGILE_DOMAIN =$this->Crud_parametria->obtenerParametria('AGILE_DOMAIN');
        }
        if (is_null($this->AGILE_USER_EMAIL)) {
            $this->AGILE_USER_EMAIL =$this->Crud_parametria->obtenerParametria('AGILE_USER_EMAIL');
        }
        if (is_null($this->AGILE_REST_API_KEY)) {
            $this->AGILE_REST_API_KEY =$this->Crud_parametria->obtenerParametria('AGILE_REST_API_KEY');
        }
        if (is_null($this->AGILE_JS_API_KEY)) {
            $this->AGILE_JS_API_KEY =$this->Crud_parametria->obtenerParametria('AGILE_JS_API_KEY');
        }
        if (is_null($this->formatoFecha)) {
            $this->formatoFecha =$this->Crud_parametria->obtenerParametria('formatoFecha');
        }
        if (is_null($this->formatoFechaAgile)) {
            $this->formatoFechaAgile =$this->Crud_parametria->obtenerParametria('formatoFechaAgile');
        }
        if (is_null($this->recaptcha)) {
            $this->recaptcha =$this->Crud_parametria->obtenerParametria('recaptcha');
        }
        $this->urlambiente = $this->Crud_parametria->obtenerParametria('urlambiente');
        $this->urlServicesDrupal =  $this->Crud_parametria->obtenerParametria('urlServicesDrupal');
        $this->usuarioServicesDrupal =  $this->Crud_parametria->obtenerParametria('usuarioServicesDrupal');
        $this->claveServicesDrupal =  $this->Crud_parametria->obtenerParametria('claveServicesDrupal');
        $this->logo =  $this->Crud_parametria->obtenerParametria('ruteLogo');
        $this->incentive =  $this->Crud_parametria->obtenerParametria('incentive');
        $this->PoweredBy =  $this->Crud_parametria->obtenerParametria('PoweredBy');
        $this->ajusteFecha = strtotime($this->Crud_parametria->obtenerParametria('ajusteFecha'),strtotime(date('Y-m-j H:i:s')));
    }
    public function crearSesion($pObjetoUsuario) {
        try {
            $this->session->set_userdata('id', $pObjetoUsuario->usuario_id);
            $this->session->set_userdata('nombre', $pObjetoUsuario->usuario_nombre.' '.$pObjetoUsuario->usuario_apellido);
            $this->session->set_userdata('imagen', $pObjetoUsuario->usuario_imagen);
            $this->session->set_userdata('rol_id', $pObjetoUsuario->rol_id);   
            if ($pObjetoUsuario->rol_nombre == 'Programador') {
                $this->session->set_userdata('programador', 1);  
            }else
            {
                $this->session->set_userdata('programador', 0);  
            }
        } catch (Exception $ex) {
            redirect($this->index);
        }

    }
    public function cerrarSession(){
        $array_sesiones = array('id' => '', 'nombre' => '','rol' => '','imagen' =>'');
        $this->session->unset_userdata($array_sesiones);
        $this->session->sess_destroy();
        redirect($this->index);
    }
    public function redirecionar($rol_id)
    {
        $DatoRol = $this->Crud_rol->GetDatos($rol_id);
        redirect($DatoRol->rol_index);
    }
    public function consultaRest($urlConsulta = '/api/test',$metodo = 'GET',$datos = null,$url = NULL,$content_type=null,$header=false)
    {
        if (is_null($url)) {
            $url = $this->incentive;
        }
        $datosRetorno = $this->curlwrap->curl_wrapIncentive($url.$urlConsulta,$datos,$metodo,$content_type,$header);
        $retornoValidar = json_decode($datosRetorno,true);

        if (is_null($retornoValidar)) 
        {
            echo "<br>";
            echo "datos de envio";
            echo "<br>";
            var_dump($datos);
            echo "<br>";
            echo "datos de url";
            echo "<br>";
            var_dump($url);
            echo "<br>";
            echo "datos de url concatenado";
            echo "<br>";
            var_dump($urlConsulta);
            echo "<br>";
            echo "datos de metodo";
            echo "<br>";
            var_dump($metodo);
            echo "<br>";
            echo "datos retorno sin editar ";
            echo "<br>";
            var_dump($datosRetorno);
            echo "<br>";
            return $retornoValidar;
        }else
        {
            return $retornoValidar;
        }
    }
    public function ordenarMenu($rol)
    {
        $where = array('l.estado_id' => 1,'pr.rol_id '=>$rol);
        $menu = $this->Crud_menu->GetDatos($where,"cm.categoria_menu_id,cm.categoria_menu_nombre",",'menu' menu");
        $submenu = $this->Crud_menu->GetDatos($where,'cs.categoria_submenu_id, cs.categoria_submenu_nombre, cs.categoria_submenu_icono,p.menu_id',",'submenu' menu");
        $data = $this->Crud_menu->GetDatos($where);
        $menuarray = array();
        $camnio= true;
        if (!is_null($menu)) 
        {
            foreach ($menu as $key) {
                $submenuarray = array();
                foreach ($submenu as $key1) {
                    $dataarray = array();
                    foreach ($data as $key2) {
                        if ($camnio) 
                        {
                            if ($key->categoria_menu_id == $key2->categoria_menu_id && $key1->categoria_submenu_id == $key2->categoria_submenu_id) {
                                array_push($dataarray,array('Nombre' => $key2->link_nombre,'Link'=>$key2->link_link));
                            }
                        }
                    }
                    (count($dataarray) != 0) ? array_push($submenuarray,array('Nombre'=>$key1->categoria_submenu_nombre,'logo'=>$key1->categoria_submenu_icono,'submenu' => $dataarray)) : '' ;
                }
                (count($submenuarray) != 0) ? array_push($menuarray,array('Nombre'=>$key->categoria_menu_nombre,'menu' => $submenuarray)) : '' ; 
            }
        }
        return $menuarray;
    }
    public function menuviejo($visual,$datos = null){
        $retVal = (is_null($datos)) ? 'datos_js/datos_js_0' : 'datos_js/'.$datos ;
        $this->load->view('admin/sobrecargas/head_view');
        $dataSend = array(
            "datos" =>  array(
                'noticias' => $this->Crud_noticias->GetDatosTotales(5),
                'menu' => $this->ordenarMenu($this->session->userdata('rol_id'))
            )
        );
        $datoNav = $this->load->view('admin/sobrecargas/nav_view',$dataSend,TRUE);
        $datoDatos = $this->load->view('admin/'.$retVal,null,TRUE);
        $dataSend = array(
            "datos" => $datoDatos
        );
        $dataFooter = $this->load->view('admin/sobrecargas/footer_view',$dataSend,TRUE);
        $dataSend = array(
            "footer" => $dataFooter,
            'nav' => $datoNav
        );
        $this->load->view('template/'.$visual,$dataSend);
    }
    public function upload($width = null,$height = null,$input = null,$tipoarchivo,$tipocarga) {
        if (!empty($_FILES)) {
            if ($width == 'null' or $width == 'NULL') {
                $width = null;
            }
            if ($height == 'null' or $height == 'NULL') {
                $height = null;
            }
            $tempFile = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $extension = $_FILES['file']['type'];
            $carpetaCarga = $this->Crud_parametria->obtenerParametria('uploader');
            $tipo = $this->obtenerExtensionFichero($_FILES['file']['name']);
            $randomize = $this->array_conevrt->generateRandomString();
            switch ($tipoarchivo) {
                case 'FILE':
                    $urlCarga = $carpetaCarga.'File/';
                    $nombreCarga=$randomize.'_'.$tipocarga.'.'.$tipo;
                break;
                case 'VIDEO':
                    $urlCarga = $carpetaCarga.'videos/';
                    $nombreCarga=$randomize.'tempo.'.$tipo;
                break;
                case 'IMG':
                    $urlCarga = $carpetaCarga.'img/';
                    $nombreCarga=$randomize.'tempo.'.$tipo;
                break;
                case 'PNG':
                    $urlCarga = $carpetaCarga.'img/';
                    $nombreCarga=$randomize.'tempo.'.$tipo;
                break;
                default:
                    $urlCarga = $carpetaCarga;
                    $nombreCarga=$randomize.'tempo.'.$tipo;
                break;
            }
            $targetPath = getcwd() .$urlCarga;
            $targetFile = $targetPath.$nombreCarga;
            $targetFileRedimencion = $targetPath . $randomize.'.'.$tipo;
            move_uploaded_file($tempFile, $targetFile);
            switch ($tipoarchivo) {
                case 'FILE':
                    $retorno = array('error' => 'Carga Exitosa','url' => $urlCarga. $nombreCarga,'input'=>$input,'tipoarchivo' => $tipoarchivo,'extension' => $extension);
                    echo json_encode($retorno, JSON_FORCE_OBJECT); 
                break;
                case 'VIDEO':
                    $retorno = array('error' => 'Carga Exitosa','url' => $urlCarga. $nombreCarga,'input'=>$input,'tipoarchivo' => $tipoarchivo);
                    echo json_encode($retorno, JSON_FORCE_OBJECT); 
                break;
                case 'IMG':
                    if (true !== ($pic_error = $this->image_resize($targetFile,$targetFileRedimencion, $width,$height, 1))) {
                        unlink($targetFile);
                        $retorno = array('error' => $pic_error,'url' =>'','input'=>$input,'tipoarchivo' => $tipoarchivo);
                        echo json_encode($retorno, JSON_FORCE_OBJECT); 
                    }
                    else{
                        unlink($targetFile);
                        $retorno = array('error' => 'Carga Exitosa','url' => $urlCarga. $randomize.'.'.$tipo,'input'=>$input,'tipoarchivo' => $tipoarchivo);
                        echo json_encode($retorno, JSON_FORCE_OBJECT); 
                    }
                break;
                default:
                    $retorno = array('error' => 'Carga Exitosa','url' => $urlCarga. $nombreCarga,'input'=>$input,'tipoarchivo' => $tipoarchivo);
                    echo json_encode($retorno, JSON_FORCE_OBJECT); 
                break;
            }
        }
    }
    public function obtenerExtensionFichero($str)
    {
        $filename = substr(strrchr($str, "."), 1);
        return $filename;
    }
    public function generarCodigo($longitud,$tipo = 'alfaNumerico') {
         $key = '';
         switch ($tipo) {
            case 'alfaNumerico':
                 $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
            break;
            case 'numerico':
                 $pattern = '1234567890';
            break;
            case 'alfa':
                 $pattern = 'abcdefghijklmnopqrstuvwxyz';
            break;
            case 'clave':
                 $pattern = '123456789';
            break;
             default:
                 $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
                 break;
         }
         $max = strlen($pattern)-1;
         for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
         return $key;
    }
    
    public function restDrupal($datos = NULL,$metodo = 'get',$url = null)
    {
        $this->load->library('basic_RestClient/my_restclient');
        $datosConect = array(
            'urlServicesDrupal' => $this->urlServicesDrupal, 
            'usuarioServicesDrupal' =>$this->usuarioServicesDrupal , 
            'claveServicesDrupal'=>$this->claveServicesDrupal
        );
        return $this->my_restclient->crearUsuarioDrupal($datosConect,$metodo,$datos,$url);
    }
    public function crearUsuario($contact_json = null,$metodoCarga = "POST"){
        
        $contact_json_input = json_encode($contact_json);
        $contact4 = $this->curlwrap->curl_wrap("contacts", $contact_json_input, $metodoCarga, "application/json",$this->AGILE_DOMAIN,$this->AGILE_USER_EMAIL,$this->AGILE_REST_API_KEY);
        
        //echo $contact4;
        switch ($contact4) {
            case 'Sorry, duplicate contact found with the same email address.':
                $envio = array('mensaje' => $contact4, 'estado'=> false);
            break;
            case '{"status":"401","exception message":"authentication issue"}':
                echo $contact4;
                $envio = array('mensaje' => $contact4, 'estado'=> false);
            break;
            default:
                $envio = array('mensaje' => $contact4, 'estado'=> true);
            break;
        }
        return $envio;
    }
    public function buscarUsuarioAgile($variable=null,$campo = null)
    {
        switch ($campo) {
            case 'id':
                $cadena = "contacts/".$variable;
            break;    
            default:
                $cadena = "contacts/search/".$campo."/".$variable;
            break;
        }
        if (!is_null($variable)) {
            $contact1 = $this->curlwrap->curl_wrap($cadena, null, "GET", NULL,$this->AGILE_DOMAIN,$this->AGILE_USER_EMAIL,$this->AGILE_REST_API_KEY);
            return $contact1;
        }else
        {
            return 'error';
        }
    }
    public function editarContactoAgile($id,$kilometros,$variablecostum)
    {
        $numero = $this->buscarCampoAgile($id->properties,$variablecostum,true);
        if (!$numero['datos']) {
            $insert =(object) array('type' => "CUSTOM",'name'=> $variablecostum,'value'=> $kilometros);
            $id->properties[$numero['conteo']] =  $insert;
        }
        else
        {
            $id->properties[$numero['datos']]->value = $kilometros;
        }
        $contact_json_update_input = json_encode($id);
        $contact5 = $this->curlwrap->curl_wrap("contacts", $contact_json_update_input, "PUT", "application/json",$this->AGILE_DOMAIN,$this->AGILE_USER_EMAIL,$this->AGILE_REST_API_KEY);
        return $contact5;
    }
    public function buscarCampoAgile($properties,$campo,$posi = FALSE)
    {
        $conteo = 0;
        foreach ($properties as $key) {
            if ($key->name == $campo) {
                if ($posi) {
                    return array('datos' => $conteo,'conteo'=> NULL);
                }else
                {
                    return array('datos' => $key,'conteo'=> NULL);
                }
            }
            $conteo= $conteo+1;
        }
        return array('datos' => false,'conteo'=> $conteo);
    }
    public function agregarTag($tag)
    {
        switch ($tag["tipo"]) {
            case 'codigoagile':
                $contact_json_tags = array(
                    "id" => $tag["datos"], //It is mandatory field. Id of contact
                   "tags" => array($tag["tag"])
                );
                $contact_json_tags_input = json_encode($contact_json_tags);
                $tags1 = $this->curlwrap->curl_wrap("contacts/edit/tags", $contact_json_tags_input, "PUT", "application/json",$this->AGILE_DOMAIN,$this->AGILE_USER_EMAIL,$this->AGILE_REST_API_KEY);
            break;
            default:
                $form_fields1 = array(
                    'email' => urlencode($correo),
                    'tags' => urlencode('["'.$tag["tag"].'"]')
                );
                $fields_string1 = '';
                foreach ($form_fields1 as $key => $value) {
                    $fields_string1 .= $key . '=' . $value . '&';
                }

                $tags1 = $this->curlwrap->curl_wrap("contacts/email/tags/add", rtrim($fields_string1, '&'), "POST", "application/x-www-form-urlencoded",$this->AGILE_DOMAIN,$this->AGILE_USER_EMAIL,$this->AGILE_REST_API_KEY);
            break;
        }
        return $tags1;
    }
    public function crearUsuarioAgile($datos,$cargamodulo = 'Archivo Carga',$idAgile = NULL,$etiquetaAdicional =  NULL){
        if (!isset($datos->urlCorreoReferidos)) {
            $referido = '';
        }
        else
        {
            $referido = $datos->urlCorreoReferidos;
        }
        if (is_null($etiquetaAdicional)) {
            $insertar = array($cargamodulo,"Carga Inicial",$this->Crud_parametria->obtenerParametria('ambiente'));
        }
        else
        {
            $insertar = array($cargamodulo,$etiquetaAdicional,$this->Crud_parametria->obtenerParametria('ambiente'),$etiquetaAdicional);
        }
        $contact_json = array(
            "lead_score" => "0",
            "star_value" => "0",
            "tags" => $insertar,
            "properties" => array(
                array(
                    "name" => "first_name",
                    "value" => $datos->usuario_nombre,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "last_name",
                    "value" => $datos->usuario_apellido,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "email",
                    "value" => $datos->usuario_correo,
                    "subtype" => 'home',
                    "type" => "SYSTEM"
                )/*,
                array(
                    "name" => "email",
                    "value" => $datos->usuario_codigounico,
                    "subtype" => 'home',
                    "type" => "SYSTEM"
                )*/,
                array(
                    "name" => "company",
                    "value" => $datos->empresalegal_nombre,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "title",
                    "value" => '',
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "address",
                    "value" => json_encode(array(
                        "address" => '',//$this->crearDirrecion($datos),
                        "city" => $datos->ciudad_nombre,
                        "country" => $datos->pais_nombre,
                    )
                ),
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "phone",
                    "value" => $datos->usuario_celular,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "habeasData",
                    "value" => 'on',
                    "type" => "CUSTOM"
                ),
                array(
                    "name" => "Actualizado",
                    "value" => ($datos->usuario_actualizado) ? 'on' : 'off',
                    "type" => "CUSTOM"
                ),
                array(
                    "name" => "Codigo_Unico",
                    "value" => $datos->usuario_codigounico,
                    "type" => "CUSTOM"
                ),
                array(
                    "name" => "EstadoUsuario",
                    "value" => $datos->estado_nombre,
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "Fecha de inicio",
                    "value" => date_format(date_create($datos->usuario_ingreso), $this->formatoFechaAgile),
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "Genero",
                    "value" => $datos->genero_nombre,
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "URLSISTEMA",
                    "value" => $this->Crud_parametria->obtenerParametria('urlambiente'),
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "Documento",
                    "value" => $datos->usuario_documento,
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "FechaNacimiento",
                    "value" => date_format(date_create($datos->usuario_fechanacimiento), $this->formatoFechaAgile),
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "Empresa legal",
                    "value" => $datos->empresalegal_nombre,
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "Posicion",
                    "value" => $datos->cargo_nombre,
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "Regional",
                    "value" => $datos->regional_nombre,
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "Tipo Vendedor",
                    "value" => $datos->tipocontrato_nombre,
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "Grupo",
                    "value" => $datos->grupo_nombre,
                    "type" => "CUSTOM"
                )
            )
        );
        if (!is_null($idAgile)) {
            $merge = array('id' => $idAgile);
            $contact_json = array_merge($merge,$contact_json);
        }
        return $contact_json;
    }
    public function buscarParametria($dato)
    {   
        $info = array();
        $where = array('tabla_nombre' => $dato);
        $datosTabla = $this->Crud_tabla->getTablas($where);
        if (!is_null($datosTabla)) {
            $datosLista= null;
            $info = array(
                'js' => $datosTabla[0]->tabla_js,
                'tabla' => $dato,
                'controlador' => $datosTabla[0]->tabla_controlador,
                'general'=> $datosLista,
                'mensaje' =>  $datosTabla[0]->tabla_mesaje,
                'datosEditar' => null,
                'tipoaccion' => $datosTabla[0]->tipoaccion_id
            );
        }
        return $info;
    }
    public function returnIdentificacion($key1)
    {
        foreach ($key1 as $valoressuma) {
            $identificacion = $valoressuma['identification'];
            return $identificacion;
        }
    }
    public function idCategoria($meta_id)
    {
        if ($meta_id <= 5) {
            return 1;
        } elseif ($meta_id <= 10) {
            return 2;
        } elseif ($meta_id <= 15) {
            return 3;
        } elseif ($meta_id <= 20) {
            return 4;
        } elseif ($meta_id <= 25) {
            return 5;
        } elseif ($meta_id <= 30) {
            return 6;
        } elseif ($meta_id <= 35) {
            return 7;
        }
    }
    public function traerNombremes($mes)
    {
        switch ($mes) {
            case 1:
                return 'Enero';
            break;
            case 2:
                return 'Febrero';
            break;
            case 3:
                return 'Marzo';
            break;
            case 4:
                return 'Abril';
            break;
            case 5:
                return 'Mayo';
            break;
            case 6:
                return 'Junio';
            break;
            case 7:
                return 'Julio';
            break;
            case 8:
                return 'Agosto';
            break;
            case 9:
                return 'Septiembre';
            break;
            case 10:
                return 'Octubre';
            break;
            case 11:
                return 'Noviembre';
            break;
            case 12:
                return 'Diciembre';
            break;
        }
    }
    public function cargarDatosHome($mes = NULL)
    {
        if (is_null($mes)) {
            $mes = date('m',$this->ajusteFecha);
        }
        $where = array('p.rol_id' => 7);
        $datos = $this->Crud_usuario->GetDatos($where);
        
        $Masculino = 0;
        $Femenino =0;
        foreach ($datos as $key) {
            switch ($key->genero_nombre) 
            {
                case 'Masculino':
                    $Masculino=$Masculino+1;
                break;
                case 'Femenino':
                    $Femenino=$Femenino+1;
                break;
            }
        }
        $datosEnvio = array(
            'totalusuarios' => count($datos),
            'masculino' => $Masculino,
            'femenino' => $Femenino
        );
        return $datosEnvio;
    }
    public function rankingxgrupoxMes($mes = null,$limite = 5,$grupo_id = null)
    {
        if (!is_null($mes)) 
        {
            $dia = '01';
            $mes = $mes;
            $ano = '2017';
            $fecha = $ano.'-'.$mes.'-'.$dia;
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
            }
            foreach ($datosGenerales as $key1) {
                $suma =0;
                $identificacion = $this->returnIdentificacion($key1);
                foreach ($key1 as $valoressuma) {
                    $suma = $suma + $valoressuma['percentage_weighed'];
                    $goal_id = $valoressuma['goal_id'];
                }
                $datos = array('suma' => $suma,'identification' =>$identificacion,'cargo_id' => $this->idCategoria($goal_id));
                $datosGenerales[$identificacion] = array_merge($datosGenerales[$identificacion],$datos);             
            }
            $cargo1 = array();
            $cargo1Final = array();
            $conteo1 =0;
            $cargo2 = array();
            $cargo2Final = array();
            $conteo2 =0;
            $cargo3 = array();
            $cargo3Final = array();
            $conteo3 =0;
            $cargo4 = array();
            $cargo4Final = array();
            $conteo4 =0;
            $cargo5 = array();
            $cargo5Final = array();
            $conteo5 =0;
            $cargo6 = array();
            $cargo6Final = array();
            $conteo6 =0;
            $cargo7 = array();
            $cargo7Final = array();
            $conteo7 =0;
            foreach ($datosGenerales as $key2) {
                switch ($key2['cargo_id']) {
                    case '1':
                        $cargo1[$conteo1] = $key2; 
                        $conteo1 = $conteo1+1;
                    break;
                    case '2':
                        $cargo2[$conteo2] = $key2; 
                        $conteo2 = $conteo2+1;
                    break;
                    case '3':
                        $cargo3[$conteo3] = $key2; 
                        $conteo3 = $conteo3+1;
                    break;
                    case '4':
                        $cargo4[$conteo4] = $key2; 
                        $conteo4 = $conteo4+1;
                    break;
                    case '5':
                        $cargo5[$conteo5] = $key2; 
                        $conteo5 = $conteo5+1;
                    break;
                    case '6':
                        $cargo6[$conteo6] = $key2; 
                        $conteo6 = $conteo6+1;
                    break;
                    case '7':
                        $cargo7[$conteo7] = $key2;
                        $conteo7 = $conteo7+1;
                    break;
                }
            }
            $cargo1 = $this->ordenarPosision($cargo1);
            $cargo2 = $this->ordenarPosision($cargo2);
            $cargo3 = $this->ordenarPosision($cargo3);
            $cargo4 = $this->ordenarPosision($cargo4);
            $cargo5 = $this->ordenarPosision($cargo5);
            $cargo6 = $this->ordenarPosision($cargo6);
            $cargo7 = $this->ordenarPosision($cargo7);
            $cargo1Final =$this->cargarDatosUsuario($cargo1,$limite,$grupo_id);
            $cargo2Final =$this->cargarDatosUsuario($cargo2,$limite,$grupo_id);
            $cargo3Final =$this->cargarDatosUsuario($cargo3,$limite,$grupo_id);
            $cargo4Final =$this->cargarDatosUsuario($cargo4,$limite,$grupo_id);
            $cargo5Final =$this->cargarDatosUsuario($cargo5,$limite,$grupo_id);
            $cargo6Final =$this->cargarDatosUsuario($cargo6,$limite,$grupo_id);
            $cargo7Final =$this->cargarDatosUsuario($cargo7,$limite,$grupo_id);
            $datosCraga = array(
                'cargo1Final' => $cargo1Final, 
                'cargo2Final' => $cargo2Final, 
                'cargo3Final' => $cargo3Final, 
                'cargo4Final' => $cargo4Final, 
                'cargo5Final' => $cargo5Final, 
            );
            return $datosCraga;
        }
        else
        {
            return NULL;
        }
    }
    public function totaltest()
    {
        $insertar = array(
            'username' => 'admin',
            'password' => 'p0p01234'
        );
        $datosIncentive =  $this->consultaRest('/usuarios/user/login','POST',$insertar,'http://conectatepublicar.com/','',array('Accept : application/json'));
        $datosRst =  $this->consultaRest('/resultados-quiz','GET',null,'http://conectatepublicar.com/','',array('Accept : application/json','Cookie'=>$datosIncentive['session_name'] . '=' . $datosIncentive['sessid']));
        var_dump($datosRst);
        return $datosRst;
    }
    public function cargarDatosUsuario($cargo1,$limite,$grupo_id)
    {
        $cargo1Final = array();
        for ($i=0; $i < count($cargo1); $i++) {
            if (!is_null($limite)) 
            {
                if ($i <= $limite) {
                    $where = array('p.usuario_documento' => $cargo1[$i]["identification"]);
                    $datosUsuario = $this->Crud_usuario->GetDatos($where);
                    if (is_null($grupo_id)) {
                        if (is_null($datosUsuario)) {
                            $insertar = array('datosUsuario' =>  null);
                        }else
                        {
                            $insertar = array('datosUsuario' => $datosUsuario[0]);
                        }
                        $cargo1[$i] = array_merge($insertar,$cargo1[$i]);
                        $cargo1Final[$i] = $cargo1[$i];
                    }
                    else
                    {
                        if (!is_null($datosUsuario)) {
                            if ($grupo_id == (int) $datosUsuario[0]->grupo_id) {
                                $cargo1Final[$i] = $cargo1[$i];
                            }
                        }
                    }
                } 
            }
            else
            {
                $where = array('p.usuario_documento' => $cargo1[$i]["identification"]);
                $datosUsuario = $this->Crud_usuario->GetDatos($where);
                $insertar = array('datosUsuario' => $datosUsuario[0]);
                $cargo1[$i] = array_merge($insertar,$cargo1[$i]);
                if (is_null($grupo_id)) {
                    if (is_null($datosUsuario)) {
                        $insertar = array('datosUsuario' =>  null);
                    }else
                    {
                        $insertar = array('datosUsuario' => $datosUsuario[0]);
                    }
                    $cargo1[$i] = array_merge($insertar,$cargo1[$i]);
                    $cargo1Final[$i] = $cargo1[$i];
                }
                else
                {
                    if (!is_null($datosUsuario)) {
                        if ($grupo_id == (int) $datosUsuario[0]->grupo_id) {
                            $cargo1Final[$i] = $cargo1[$i];
                        }
                    }
                }
            }
        }
        return $cargo1Final;
    }
    public function getdatosxgrupo($mes = NULL)
    {
        if (is_null($mes)) 
        {
            $mes = date('m',$this->ajusteFecha);
        }
        $datosReturn = array();
        $con =0;
        $datos = $this->Crud_model->obtenerRegistros('basica_grupo');
        foreach ($datos as $key) {
            $where = array('p.grupo_id' => $key->grupo_id);
            $datosUsuarios = $this->Crud_grupo->GetDatosGrupo($where,'count(*) total');
            $where = array(
                'p.grupo_id' => $key->grupo_id,
                'p.metagrupo_mes' => (int) $mes
            );
            $datosMetas = $this->Crud_model->obtenerRegistros('produccion_metagrupo',$where,'max(p.metagrupo_meta) metagrupo_meta');
            if (is_null($datosMetas[0]->metagrupo_meta)) {
                $datosMetas[0]->metagrupo_meta = 0;
            }
           
           if ((int) $datosUsuarios[0]->total > 1) 
           {
               $carga = array('datos' => $key, 'total' => (int) $datosUsuarios[0]->total);
               $datosReturn[$con] = $carga;
               $con =$con+1;
           }
        }
        return $datosReturn;
    }
    public function ordenarPosision($people)
    {
        $sortArray = array(); 
        if (count($people) > 0) {
            foreach($people as $person){
                foreach($person as $key=>$value){
                    if(!isset($sortArray[$key])){
                        $sortArray[$key] = array();
                    }
                    $sortArray[$key][] = $value;
                }
            } 
            $orderby = "suma";
            array_multisort($sortArray[$orderby],SORT_DESC,$people);
        }   
        return $people; 
    }
    public function listTablaCargo($cargo1,$mes = '07')
    {
        $valorRetorno = '
            <table cellspacing = "0" cellpadding = "0" border = "1" >
                <tr >
                    <td> Nombre</td>
                    <td> Apellido</td>
                    <td> Cedula</td>
                    <td> Nomina </td>
                    <td> Ventas Renovacion %</td>
                    <td> Ventas Nuevas %</td>
                    <td> Visitas %</td>
                    <td> Test %</td>
                    <td> Grupal %</td>
                    <td> Venas Renovacion puntos</td>
                    <td> Venas Nuevas puntos</td>
                    <td> Visitas puntos</td>
                    <td> Test puntos</td>
                    <td> Grupal puntos</td>
                    <td> Total</td>
                </tr >  ';
        foreach ($cargo1 as $item):
            $renovacion = null;
            $nuevo = null;
            $llamadas = null;
            $test = null;
            $grupal = null;
            if (!is_null($item["datosUsuario"])) 
            {
                for ($i=0; $i < count($item); $i++) { 
                    if (isset($item[$i])) 
                    {                        
                        $r=(($item[$i]["goal_id"]/5)-intval(($item[$i]["goal_id"]/5)));  
                        switch ((string) ($r*10)) {
                            case '2':             
                                $renovacion = $item[$i];
                            break;
                            case '4':
                                $nuevo = $item[$i];
                            break;
                            case '6':
                                $llamadas = $item[$i];
                            break;
                            case '8':
                                $test = $item[$i];
                            break;
                            case '0':
                                $grupal = $item[$i];
                            break;
                        }
                    }
                }
                $bodytag = str_replace("%body%", "black", "<body text='%body%'>");
                $valorRetorno .= '<tr>
                        <td> '.$item["datosUsuario"]->usuario_nombre.'</td>
                        <td> '.$item["datosUsuario"]->usuario_apellido.'</td>
                        <td> '.$item['identification'].'</td>
                        <td> '.$item["datosUsuario"]->usuario_codigonomina.' </td>
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
                        <td> '.str_replace('.',',',(double) $item['suma']).'</td>
                    </tr>';
            }
        endforeach;
        $valorRetorno .= '</table>';
        return $valorRetorno;
    }
    public function retornoMesxString($fecha)
    {
        $returnMes = null;
        if (strpos($fecha, 'Enero') >0) {
            $returnMes = '01';
        }
        if (strpos($fecha, 'Febrero') >0) {
            $returnMes = '02';
        }
        if (strpos($fecha, 'Marzo') >0) {
            $returnMes = '03';
        }
        if (strpos($fecha, 'Abril') >0) {
            $returnMes = '04';
        }
        if (strpos($fecha, 'Mayo') >0) {
            $returnMes = '05';
        }
        if (strpos($fecha, 'junio') >0) {
            $returnMes = '06';
        }
        if (strpos($fecha, 'Julio') >0) {
            $returnMes = '07';
        }
        if (strpos($fecha, 'Agosto') >0) {
            $returnMes = '08';
        }
        if (strpos($fecha, 'Septiembre') >0) {
            $returnMes = '09';
        }
        if (strpos($fecha, 'octubre') >0) {
            $returnMes = '10';
        }
        if (strpos($fecha, 'Noviembre') >0) {
            $returnMes = '11';
        }
        if (strpos($fecha, 'Diciembre') >0) {
            $returnMes = '12';
        }
        return $returnMes;
    }
}
?>