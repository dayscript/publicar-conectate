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
    public $dominio;


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
        $this->dominio = $this->getDominio();
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
                    "value" => $datos->dominio_url,
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
                'tipoaccion' => $datosTabla[0]->tipoaccion_id,
                'tabla_jobfin' => $datosTabla[0]->tabla_jobfin
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
        $query = 'select * from parametria_incentive where incentive_id_renovacion = '.$meta_id.' or incentive_id_nueva = '.$meta_id.' or incentive_id_ventas = '.$meta_id.' or incentive_id_citas = '.$meta_id.' or incentive_id_conocimiento = '.$meta_id.' or incentive_id_grupo = '.$meta_id;
        $datos =  $this->Crud_model->queryConsulta($query);
        return $datos[0]["cargo_id"];
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
        //var_dump($datosRst);
        $datosRst =  json_decode('{"nodes":[{"node":{"Quiz result ID":"11","Puntuaci\u00f3n":"50","Uid":"1","Nombre":"admin","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 23, 2017 - 14:34","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"12","Puntuaci\u00f3n":"0","Uid":"1538","Nombre":"1111","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"13","Puntuaci\u00f3n":"0","Uid":"1","Nombre":"admin","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"14","Puntuaci\u00f3n":"30","Uid":"1659","Nombre":"2222","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 23, 2017 - 18:27","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"15","Puntuaci\u00f3n":"80","Uid":"1816","Nombre":"1033756003","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 10:54","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"16","Puntuaci\u00f3n":"0","Uid":"1629","Nombre":"8432512","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"17","Puntuaci\u00f3n":"0","Uid":"1832","Nombre":"43030006","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"18","Puntuaci\u00f3n":"60","Uid":"1853","Nombre":"93300177","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 09:54","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"19","Puntuaci\u00f3n":"60","Uid":"1858","Nombre":"19386669","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 09:54","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"20","Puntuaci\u00f3n":"50","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 09:59","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"21","Puntuaci\u00f3n":"50","Uid":"1778","Nombre":"1030571314","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 10:07","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"22","Puntuaci\u00f3n":"60","Uid":"1760","Nombre":"1013654514","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 10:56","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"23","Puntuaci\u00f3n":"50","Uid":"1734","Nombre":"37707838","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 10:28","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"24","Puntuaci\u00f3n":"100","Uid":"1810","Nombre":"80766503","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 10:59","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"25","Puntuaci\u00f3n":"80","Uid":"1649","Nombre":"1017161064","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 11:03","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"26","Puntuaci\u00f3n":"0","Uid":"1604","Nombre":"77154251","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"27","Puntuaci\u00f3n":"50","Uid":"1707","Nombre":"1128482496","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 13:57","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"28","Puntuaci\u00f3n":"60","Uid":"1545","Nombre":"10275051","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 15:37","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"29","Puntuaci\u00f3n":"50","Uid":"1789","Nombre":"1110534326","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 14:41","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"30","Puntuaci\u00f3n":"70","Uid":"1710","Nombre":"1152685739","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 15:19","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"31","Puntuaci\u00f3n":"80","Uid":"1643","Nombre":"71264876","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 15:22","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"32","Puntuaci\u00f3n":"80","Uid":"1662","Nombre":"15919346","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 15:30","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"33","Puntuaci\u00f3n":"50","Uid":"1690","Nombre":"1017198935","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 15:31","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"34","Puntuaci\u00f3n":"60","Uid":"1635","Nombre":"43111006","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 15:37","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"35","Puntuaci\u00f3n":"70","Uid":"1631","Nombre":"39284148","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 15:41","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"36","Puntuaci\u00f3n":"70","Uid":"1840","Nombre":"71263924","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 16:05","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"37","Puntuaci\u00f3n":"60","Uid":"1818","Nombre":"8106155","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 15:49","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"38","Puntuaci\u00f3n":"60","Uid":"1633","Nombre":"42730323","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 16:30","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"39","Puntuaci\u00f3n":"80","Uid":"1686","Nombre":"98549155","Evaluado":"S\u00ed","Date finished":"Viernes, Agosto 25, 2017 - 16:48","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"40","Puntuaci\u00f3n":"50","Uid":"1809","Nombre":"79750691","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Agosto 26, 2017 - 11:37","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"41","Puntuaci\u00f3n":"100","Uid":"1812","Nombre":"93088550","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 10:35","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"42","Puntuaci\u00f3n":"90","Uid":"1579","Nombre":"40410885","Evaluado":"S\u00ed","Date finished":"Domingo, Agosto 27, 2017 - 11:38","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"43","Puntuaci\u00f3n":"30","Uid":"1764","Nombre":"1015421201","Evaluado":"S\u00ed","Date finished":"Domingo, Agosto 27, 2017 - 19:28","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"44","Puntuaci\u00f3n":"90","Uid":"1823","Nombre":"23002704","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 09:46","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"45","Puntuaci\u00f3n":"60","Uid":"1687","Nombre":"1010202890","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 10:27","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"46","Puntuaci\u00f3n":"70","Uid":"1862","Nombre":"42160488","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 11:03","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"47","Puntuaci\u00f3n":"60","Uid":"1744","Nombre":"80094751","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 12:01","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"48","Puntuaci\u00f3n":"60","Uid":"1860","Nombre":"38657130","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 14:35","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"49","Puntuaci\u00f3n":"60","Uid":"1652","Nombre":"1033699125","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 12:01","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"50","Puntuaci\u00f3n":"70","Uid":"1670","Nombre":"45706580","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 12:57","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"51","Puntuaci\u00f3n":"70","Uid":"1668","Nombre":"45522972","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 12:57","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"52","Puntuaci\u00f3n":"70","Uid":"1583","Nombre":"45465288","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 13:57","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"53","Puntuaci\u00f3n":"80","Uid":"1586","Nombre":"45536910","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 13:55","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"54","Puntuaci\u00f3n":"70","Uid":"1585","Nombre":"45519854","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 14:00","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"55","Puntuaci\u00f3n":"70","Uid":"1855","Nombre":"79168966","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 14:31","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"56","Puntuaci\u00f3n":"50","Uid":"1706","Nombre":"1128269099","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 17:33","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"57","Puntuaci\u00f3n":"70","Uid":"1596","Nombre":"60302892","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 15:25","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"58","Puntuaci\u00f3n":"80","Uid":"1642","Nombre":"71229034","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 15:43","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"59","Puntuaci\u00f3n":"90","Uid":"1550","Nombre":"15373211","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 15:54","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"60","Puntuaci\u00f3n":"100","Uid":"1647","Nombre":"80815895","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 16:07","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"61","Puntuaci\u00f3n":"100","Uid":"1689","Nombre":"1015439275","Evaluado":"S\u00ed","Date finished":"Lunes, Agosto 28, 2017 - 17:22","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"62","Puntuaci\u00f3n":"80","Uid":"1743","Nombre":"79879677","Evaluado":"S\u00ed","Date finished":"Martes, Agosto 29, 2017 - 09:21","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"63","Puntuaci\u00f3n":"20","Uid":"1783","Nombre":"1073232957","Evaluado":"S\u00ed","Date finished":"Martes, Agosto 29, 2017 - 09:45","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"64","Puntuaci\u00f3n":"50","Uid":"1777","Nombre":"1026275842","Evaluado":"S\u00ed","Date finished":"Martes, Agosto 29, 2017 - 10:09","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"65","Puntuaci\u00f3n":"80","Uid":"1750","Nombre":"1010170641","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:01","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"66","Puntuaci\u00f3n":"70","Uid":"1871","Nombre":"1098628991","Evaluado":"S\u00ed","Date finished":"Martes, Agosto 29, 2017 - 11:50","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"67","Puntuaci\u00f3n":"80","Uid":"1592","Nombre":"52265305","Evaluado":"S\u00ed","Date finished":"Martes, Agosto 29, 2017 - 11:43","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"68","Puntuaci\u00f3n":"80","Uid":"1617","Nombre":"1082869403","Evaluado":"S\u00ed","Date finished":"Martes, Agosto 29, 2017 - 11:37","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"69","Puntuaci\u00f3n":"90","Uid":"1616","Nombre":"1082860622","Evaluado":"S\u00ed","Date finished":"Martes, Agosto 29, 2017 - 11:53","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"70","Puntuaci\u00f3n":"90","Uid":"1634","Nombre":"42897250","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 09:20","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"71","Puntuaci\u00f3n":"40","Uid":"1790","Nombre":"1032400215","Evaluado":"S\u00ed","Date finished":"Martes, Agosto 29, 2017 - 17:13","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"72","Puntuaci\u00f3n":"70","Uid":"1638","Nombre":"51696883","Evaluado":"S\u00ed","Date finished":"Martes, Agosto 29, 2017 - 17:36","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"73","Puntuaci\u00f3n":"100","Uid":"1831","Nombre":"43009269","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 11:15","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"74","Puntuaci\u00f3n":"60","Uid":"1651","Nombre":"1030615668","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 08:21","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"75","Puntuaci\u00f3n":"60","Uid":"1560","Nombre":"30336095","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 09:06","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"76","Puntuaci\u00f3n":"70","Uid":"1618","Nombre":"1088536327","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 09:23","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"77","Puntuaci\u00f3n":"80","Uid":"1653","Nombre":"1090487881","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 09:23","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"78","Puntuaci\u00f3n":"80","Uid":"1830","Nombre":"42087667","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 09:38","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"79","Puntuaci\u00f3n":"80","Uid":"1759","Nombre":"1013625445","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 16:28","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"80","Puntuaci\u00f3n":"60","Uid":"1741","Nombre":"79731874","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 11:38","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"81","Puntuaci\u00f3n":"50","Uid":"1730","Nombre":"1032456259","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 11:23","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"82","Puntuaci\u00f3n":"80","Uid":"1781","Nombre":"1033738395","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 11:36","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"83","Puntuaci\u00f3n":"50","Uid":"1786","Nombre":"1101754639","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 11:36","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"84","Puntuaci\u00f3n":"60","Uid":"1746","Nombre":"80739986","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:04","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"85","Puntuaci\u00f3n":"80","Uid":"1765","Nombre":"1015424170","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 11:44","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"86","Puntuaci\u00f3n":"0","Uid":"1671","Nombre":"51607361","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"87","Puntuaci\u00f3n":"100","Uid":"1695","Nombre":"1037581848","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 14:19","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"88","Puntuaci\u00f3n":"40","Uid":"1775","Nombre":"1022976301","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 14:39","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"89","Puntuaci\u00f3n":"80","Uid":"1754","Nombre":"1012352755","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 14:45","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"90","Puntuaci\u00f3n":"70","Uid":"1749","Nombre":"80824907","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 14:49","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"91","Puntuaci\u00f3n":"70","Uid":"1748","Nombre":"80810256","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 15:04","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"92","Puntuaci\u00f3n":"80","Uid":"1849","Nombre":"52272346","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 15:38","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"93","Puntuaci\u00f3n":"70","Uid":"1739","Nombre":"53891989","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 15:14","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"94","Puntuaci\u00f3n":"80","Uid":"1757","Nombre":"1012409353","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 15:11","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"95","Puntuaci\u00f3n":"60","Uid":"1780","Nombre":"1033733509","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 15:27","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"96","Puntuaci\u00f3n":"70","Uid":"1864","Nombre":"51844643","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 15:50","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"97","Puntuaci\u00f3n":"80","Uid":"1752","Nombre":"1010205610","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 15:43","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"98","Puntuaci\u00f3n":"70","Uid":"1850","Nombre":"52997820","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 15:53","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"99","Puntuaci\u00f3n":"70","Uid":"1817","Nombre":"1032433451","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 16:20","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"100","Puntuaci\u00f3n":"60","Uid":"1612","Nombre":"1047393431","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Agosto 30, 2017 - 23:35","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"101","Puntuaci\u00f3n":"90","Uid":"1543","Nombre":"10248150","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 08:01","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"102","Puntuaci\u00f3n":"60","Uid":"1868","Nombre":"79541464","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 08:46","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"103","Puntuaci\u00f3n":"40","Uid":"1702","Nombre":"1075650251","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 09:07","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"104","Puntuaci\u00f3n":"60","Uid":"1711","Nombre":"1033711742","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 08:56","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"105","Puntuaci\u00f3n":"100","Uid":"1680","Nombre":"71741357","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 09:16","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"106","Puntuaci\u00f3n":"90","Uid":"1698","Nombre":"1040740977","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 09:26","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"107","Puntuaci\u00f3n":"80","Uid":"1542","Nombre":"10135546","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 10:02","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"108","Puntuaci\u00f3n":"80","Uid":"1861","Nombre":"42142517","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 10:28","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"109","Puntuaci\u00f3n":"70","Uid":"1869","Nombre":"80049709","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 10:26","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"110","Puntuaci\u00f3n":"60","Uid":"1558","Nombre":"27893663","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 10:35","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"111","Puntuaci\u00f3n":"90","Uid":"1813","Nombre":"1014214447","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 10:48","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"112","Puntuaci\u00f3n":"80","Uid":"1742","Nombre":"79835477","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 11:06","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"113","Puntuaci\u00f3n":"100","Uid":"1637","Nombre":"43816868","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 11:23","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"114","Puntuaci\u00f3n":"60","Uid":"1703","Nombre":"1090390874","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 12:00","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"115","Puntuaci\u00f3n":"0","Uid":"1645","Nombre":"79117468","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"116","Puntuaci\u00f3n":"90","Uid":"1648","Nombre":"98576310","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 13:37","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"117","Puntuaci\u00f3n":"100","Uid":"1636","Nombre":"43163033","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 14:13","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"118","Puntuaci\u00f3n":"70","Uid":"1761","Nombre":"1014213503","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 15:03","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"119","Puntuaci\u00f3n":"90","Uid":"1758","Nombre":"1013595186","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 15:14","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"120","Puntuaci\u00f3n":"60","Uid":"1747","Nombre":"80745886","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 15:05","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"121","Puntuaci\u00f3n":"60","Uid":"1762","Nombre":"1015397689","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 15:16","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"122","Puntuaci\u00f3n":"70","Uid":"1756","Nombre":"1012407673","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 15:06","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"123","Puntuaci\u00f3n":"30","Uid":"1693","Nombre":"1026262579","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 15:12","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"124","Puntuaci\u00f3n":"90","Uid":"1814","Nombre":"1016027030","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 15:26","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"125","Puntuaci\u00f3n":"70","Uid":"1767","Nombre":"1015455083","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 15:12","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"126","Puntuaci\u00f3n":"90","Uid":"1851","Nombre":"53135316","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 15:19","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"127","Puntuaci\u00f3n":"50","Uid":"1736","Nombre":"52792171","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 16:12","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"128","Puntuaci\u00f3n":"90","Uid":"1708","Nombre":"1129498914","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:03","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"129","Puntuaci\u00f3n":"80","Uid":"1852","Nombre":"80235932","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 17:40","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"130","Puntuaci\u00f3n":"70","Uid":"1699","Nombre":"1044422577","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 17:54","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"131","Puntuaci\u00f3n":"90","Uid":"1745","Nombre":"80115455","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:02","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"132","Puntuaci\u00f3n":"90","Uid":"1557","Nombre":"25872363","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:07","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"133","Puntuaci\u00f3n":"80","Uid":"1595","Nombre":"57416795","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:04","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"134","Puntuaci\u00f3n":"30","Uid":"1700","Nombre":"1044423276","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:01","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"135","Puntuaci\u00f3n":"90","Uid":"1873","Nombre":"45765299","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:06","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"136","Puntuaci\u00f3n":"90","Uid":"1679","Nombre":"55301209","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:07","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"137","Puntuaci\u00f3n":"90","Uid":"1552","Nombre":"22589702","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:05","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"138","Puntuaci\u00f3n":"90","Uid":"1788","Nombre":"1110526308","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:08","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"139","Puntuaci\u00f3n":"100","Uid":"1697","Nombre":"1037602211","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:09","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"140","Puntuaci\u00f3n":"90","Uid":"1774","Nombre":"1022389702","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:12","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"141","Puntuaci\u00f3n":"90","Uid":"1740","Nombre":"79223701","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:18","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"142","Puntuaci\u00f3n":"70","Uid":"1627","Nombre":"1144065018","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:16","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"143","Puntuaci\u00f3n":"80","Uid":"1772","Nombre":"1020794054","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:23","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"144","Puntuaci\u00f3n":"90","Uid":"1755","Nombre":"1012380151","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:23","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"145","Puntuaci\u00f3n":"100","Uid":"1820","Nombre":"19375316","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 18:40","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"146","Puntuaci\u00f3n":"100","Uid":"1857","Nombre":"16767418","Evaluado":"S\u00ed","Date finished":"Jueves, Agosto 31, 2017 - 21:28","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"153","Puntuaci\u00f3n":"0","Uid":"1","Nombre":"admin","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"159","Puntuaci\u00f3n":"100","Uid":"1772","Nombre":"1020794054","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 09:01","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"162","Puntuaci\u00f3n":"70","Uid":"1850","Nombre":"52997820","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 11:41","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"165","Puntuaci\u00f3n":"80","Uid":"1850","Nombre":"52997820","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 12:06","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"166","Puntuaci\u00f3n":"90","Uid":"1850","Nombre":"52997820","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 12:10","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"167","Puntuaci\u00f3n":"80","Uid":"1850","Nombre":"52997820","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 12:20","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"168","Puntuaci\u00f3n":"80","Uid":"1850","Nombre":"52997820","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:06","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"169","Puntuaci\u00f3n":"60","Uid":"1778","Nombre":"1030571314","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 12:56","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"171","Puntuaci\u00f3n":"70","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:18","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"174","Puntuaci\u00f3n":"80","Uid":"1850","Nombre":"52997820","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:11","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"175","Puntuaci\u00f3n":"90","Uid":"1767","Nombre":"1015455083","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:18","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"176","Puntuaci\u00f3n":"60","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:23","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"177","Puntuaci\u00f3n":"90","Uid":"1748","Nombre":"80810256","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:22","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"178","Puntuaci\u00f3n":"70","Uid":"1778","Nombre":"1030571314","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:27","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"179","Puntuaci\u00f3n":"80","Uid":"1749","Nombre":"80824907","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:33","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"180","Puntuaci\u00f3n":"80","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:28","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"181","Puntuaci\u00f3n":"80","Uid":"1735","Nombre":"52362139","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:29","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"182","Puntuaci\u00f3n":"70","Uid":"1778","Nombre":"1030571314","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:33","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"183","Puntuaci\u00f3n":"70","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:35","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"184","Puntuaci\u00f3n":"90","Uid":"1762","Nombre":"1015397689","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:33","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"185","Puntuaci\u00f3n":"90","Uid":"1757","Nombre":"1012409353","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:41","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"186","Puntuaci\u00f3n":"70","Uid":"1756","Nombre":"1012407673","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:38","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"187","Puntuaci\u00f3n":"80","Uid":"1778","Nombre":"1030571314","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:40","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"188","Puntuaci\u00f3n":"70","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:42","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"189","Puntuaci\u00f3n":"90","Uid":"1739","Nombre":"53891989","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:45","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"190","Puntuaci\u00f3n":"80","Uid":"1757","Nombre":"1012409353","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:55","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"191","Puntuaci\u00f3n":"90","Uid":"1747","Nombre":"80745886","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:49","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"192","Puntuaci\u00f3n":"80","Uid":"1758","Nombre":"1013595186","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:50","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"193","Puntuaci\u00f3n":"80","Uid":"1778","Nombre":"1030571314","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:50","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"194","Puntuaci\u00f3n":"80","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:51","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"195","Puntuaci\u00f3n":"80","Uid":"1766","Nombre":"1015436810","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:30","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"196","Puntuaci\u00f3n":"80","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:56","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"198","Puntuaci\u00f3n":"80","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:58","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"199","Puntuaci\u00f3n":"70","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:00","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"200","Puntuaci\u00f3n":"60","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:02","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"201","Puntuaci\u00f3n":"80","Uid":"1742","Nombre":"79835477","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:03","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"203","Puntuaci\u00f3n":"70","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:19","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"206","Puntuaci\u00f3n":"70","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:22","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"207","Puntuaci\u00f3n":"80","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:26","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"208","Puntuaci\u00f3n":"70","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:28","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"209","Puntuaci\u00f3n":"90","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:31","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"210","Puntuaci\u00f3n":"80","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:41","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"211","Puntuaci\u00f3n":"80","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:43","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"212","Puntuaci\u00f3n":"90","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:45","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"213","Puntuaci\u00f3n":"90","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:51","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"214","Puntuaci\u00f3n":"100","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:53","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"215","Puntuaci\u00f3n":"100","Uid":"1850","Nombre":"52997820","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 17:00","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"218","Puntuaci\u00f3n":"100","Uid":"1851","Nombre":"53135316","Evaluado":"S\u00ed","Date finished":"Lunes, Septiembre 25, 2017 - 09:50","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"221","Puntuaci\u00f3n":"100","Uid":"1758","Nombre":"1013595186","Evaluado":"S\u00ed","Date finished":"Lunes, Septiembre 25, 2017 - 10:20","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"225","Puntuaci\u00f3n":"100","Uid":"1852","Nombre":"80235932","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 07:25","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"226","Puntuaci\u00f3n":"100","Uid":"1764","Nombre":"1015421201","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 07:49","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"229","Puntuaci\u00f3n":"100","Uid":"1777","Nombre":"1026275842","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 08:16","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"232","Puntuaci\u00f3n":"100","Uid":"1746","Nombre":"80739986","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 08:28","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"234","Puntuaci\u00f3n":"100","Uid":"1745","Nombre":"80115455","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 08:40","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"236","Puntuaci\u00f3n":"100","Uid":"1750","Nombre":"1010170641","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 08:48","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"239","Puntuaci\u00f3n":"100","Uid":"1783","Nombre":"1073232957","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 08:55","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"249","Puntuaci\u00f3n":"100","Uid":"1790","Nombre":"1032400215","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Septiembre 27, 2017 - 14:41","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"260","Puntuaci\u00f3n":"100","Uid":"1788","Nombre":"1110526308","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 28, 2017 - 17:03","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"261","Puntuaci\u00f3n":"100","Uid":"1782","Nombre":"1072962403","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 28, 2017 - 17:07","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"272","Puntuaci\u00f3n":"70","Uid":"1760","Nombre":"1013654514","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 14:52","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"273","Puntuaci\u00f3n":"0","Uid":"1765","Nombre":"1015424170","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"298","Puntuaci\u00f3n":"60","Uid":"1","Nombre":"admin","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 16:23","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"319","Puntuaci\u00f3n":"100","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 08:16","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"321","Puntuaci\u00f3n":"100","Uid":"1778","Nombre":"1030571314","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 09:52","Time_left":"0 seg","Nombre del sitio":"SUMATE"}},{"node":{"Quiz result ID":"147","Puntuaci\u00f3n":"0","Uid":"1","Nombre":"admin","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"148","Puntuaci\u00f3n":"80","Uid":"1596","Nombre":"60302892","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 21, 2017 - 15:17","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"149","Puntuaci\u00f3n":"90","Uid":"1604","Nombre":"77154251","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 16, 2017 - 07:30","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"150","Puntuaci\u00f3n":"90","Uid":"1862","Nombre":"42160488","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Septiembre 27, 2017 - 07:35","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"151","Puntuaci\u00f3n":"80","Uid":"1563","Nombre":"31403118","Evaluado":"S\u00ed","Date finished":"Lunes, Septiembre 18, 2017 - 17:11","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"152","Puntuaci\u00f3n":"90","Uid":"1823","Nombre":"23002704","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 21, 2017 - 11:32","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"154","Puntuaci\u00f3n":"100","Uid":"1860","Nombre":"38657130","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 12:39","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"155","Puntuaci\u00f3n":"70","Uid":"1601","Nombre":"66951678","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 21, 2017 - 20:01","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"156","Puntuaci\u00f3n":"100","Uid":"1857","Nombre":"16767418","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 21, 2017 - 20:22","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"157","Puntuaci\u00f3n":"100","Uid":"1869","Nombre":"80049709","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 08:54","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"158","Puntuaci\u00f3n":"100","Uid":"1864","Nombre":"51844643","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 08:54","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"160","Puntuaci\u00f3n":"100","Uid":"1785","Nombre":"1098769920","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 11:28","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"161","Puntuaci\u00f3n":"90","Uid":"1778","Nombre":"1030571314","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 11:35","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"163","Puntuaci\u00f3n":"90","Uid":"1555","Nombre":"25289696","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 11:44","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"164","Puntuaci\u00f3n":"100","Uid":"1545","Nombre":"10275051","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 11:49","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"170","Puntuaci\u00f3n":"90","Uid":"1579","Nombre":"40410885","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 12:57","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"172","Puntuaci\u00f3n":"90","Uid":"1583","Nombre":"45465288","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 13:23","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"173","Puntuaci\u00f3n":"70","Uid":"1787","Nombre":"1104707991","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 14:51","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"197","Puntuaci\u00f3n":"80","Uid":"1742","Nombre":"79835477","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 15:59","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"202","Puntuaci\u00f3n":"100","Uid":"1652","Nombre":"1033699125","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:06","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"204","Puntuaci\u00f3n":"100","Uid":"1861","Nombre":"42142517","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:21","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"205","Puntuaci\u00f3n":"90","Uid":"1560","Nombre":"30336095","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 22, 2017 - 16:24","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"216","Puntuaci\u00f3n":"70","Uid":"1702","Nombre":"1075650251","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 23, 2017 - 08:40","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"217","Puntuaci\u00f3n":"80","Uid":"1543","Nombre":"10248150","Evaluado":"S\u00ed","Date finished":"Lunes, Septiembre 25, 2017 - 07:24","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"219","Puntuaci\u00f3n":"100","Uid":"1780","Nombre":"1033733509","Evaluado":"S\u00ed","Date finished":"Lunes, Septiembre 25, 2017 - 10:14","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"220","Puntuaci\u00f3n":"0","Uid":"1758","Nombre":"1013595186","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"222","Puntuaci\u00f3n":"90","Uid":"1643","Nombre":"71264876","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 19:02","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"223","Puntuaci\u00f3n":"100","Uid":"1542","Nombre":"10135546","Evaluado":"S\u00ed","Date finished":"Lunes, Septiembre 25, 2017 - 16:23","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"224","Puntuaci\u00f3n":"90","Uid":"1653","Nombre":"1090487881","Evaluado":"S\u00ed","Date finished":"Lunes, Septiembre 25, 2017 - 16:21","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"227","Puntuaci\u00f3n":"0","Uid":"1777","Nombre":"1026275842","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"228","Puntuaci\u00f3n":"90","Uid":"1629","Nombre":"8432512","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 08:11","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"230","Puntuaci\u00f3n":"100","Uid":"1550","Nombre":"15373211","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 08:25","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"231","Puntuaci\u00f3n":"0","Uid":"1746","Nombre":"80739986","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"233","Puntuaci\u00f3n":"100","Uid":"1686","Nombre":"98549155","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 08:33","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"235","Puntuaci\u00f3n":"100","Uid":"1633","Nombre":"42730323","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 08:38","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"237","Puntuaci\u00f3n":"80","Uid":"1670","Nombre":"45706580","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 09:03","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"238","Puntuaci\u00f3n":"90","Uid":"1668","Nombre":"45522972","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 09:05","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"240","Puntuaci\u00f3n":"100","Uid":"1830","Nombre":"42087667","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 08:55","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"241","Puntuaci\u00f3n":"100","Uid":"1710","Nombre":"1152685739","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 09:16","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"242","Puntuaci\u00f3n":"90","Uid":"1622","Nombre":"1094912089","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 20:12","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"243","Puntuaci\u00f3n":"70","Uid":"1808","Nombre":"52912092","Evaluado":"S\u00ed","Date finished":"Martes, Septiembre 26, 2017 - 21:13","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"244","Puntuaci\u00f3n":"90","Uid":"1585","Nombre":"45519854","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Septiembre 27, 2017 - 07:51","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"245","Puntuaci\u00f3n":"90","Uid":"1586","Nombre":"45536910","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Septiembre 27, 2017 - 07:50","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"246","Puntuaci\u00f3n":"100","Uid":"1662","Nombre":"15919346","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Septiembre 27, 2017 - 08:26","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"247","Puntuaci\u00f3n":"100","Uid":"1818","Nombre":"8106155","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Septiembre 27, 2017 - 13:57","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"248","Puntuaci\u00f3n":"100","Uid":"1648","Nombre":"98576310","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Septiembre 27, 2017 - 14:00","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"250","Puntuaci\u00f3n":"100","Uid":"1680","Nombre":"71741357","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Septiembre 27, 2017 - 15:41","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"251","Puntuaci\u00f3n":"100","Uid":"1706","Nombre":"1128269099","Evaluado":"S\u00ed","Date finished":"Mi\u00e9rcoles, Septiembre 27, 2017 - 15:51","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"252","Puntuaci\u00f3n":"100","Uid":"1635","Nombre":"43111006","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 28, 2017 - 08:17","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"253","Puntuaci\u00f3n":"100","Uid":"1631","Nombre":"39284148","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 28, 2017 - 08:56","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"254","Puntuaci\u00f3n":"80","Uid":"1693","Nombre":"1026262579","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 28, 2017 - 09:33","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"255","Puntuaci\u00f3n":"100","Uid":"1647","Nombre":"80815895","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 28, 2017 - 11:21","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"256","Puntuaci\u00f3n":"100","Uid":"1651","Nombre":"1030615668","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 28, 2017 - 11:25","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"257","Puntuaci\u00f3n":"100","Uid":"1810","Nombre":"80766503","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 28, 2017 - 11:29","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"258","Puntuaci\u00f3n":"100","Uid":"1816","Nombre":"1033756003","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 28, 2017 - 14:49","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"259","Puntuaci\u00f3n":"80","Uid":"1703","Nombre":"1090390874","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 28, 2017 - 14:53","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"262","Puntuaci\u00f3n":"90","Uid":"1688","Nombre":"1014195363","Evaluado":"S\u00ed","Date finished":"Jueves, Septiembre 28, 2017 - 17:11","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"263","Puntuaci\u00f3n":"80","Uid":"1558","Nombre":"27893663","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 08:10","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"264","Puntuaci\u00f3n":"100","Uid":"1855","Nombre":"79168966","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 15:27","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"265","Puntuaci\u00f3n":"100","Uid":"1873","Nombre":"45765299","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 10:36","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"266","Puntuaci\u00f3n":"90","Uid":"1637","Nombre":"43816868","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 13:51","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"267","Puntuaci\u00f3n":"90","Uid":"1634","Nombre":"42897250","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 14:21","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"268","Puntuaci\u00f3n":"90","Uid":"1695","Nombre":"1037581848","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 14:32","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"269","Puntuaci\u00f3n":"80","Uid":"1642","Nombre":"71229034","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 14:38","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"270","Puntuaci\u00f3n":"80","Uid":"1689","Nombre":"1015439275","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 14:36","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"271","Puntuaci\u00f3n":"100","Uid":"1784","Nombre":"1093772736","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 14:39","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"274","Puntuaci\u00f3n":"80","Uid":"1666","Nombre":"36564328","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 15:26","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"275","Puntuaci\u00f3n":"100","Uid":"1859","Nombre":"22460753","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 16:23","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"276","Puntuaci\u00f3n":"80","Uid":"1595","Nombre":"57416795","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 16:26","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"277","Puntuaci\u00f3n":"90","Uid":"1592","Nombre":"52265305","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 16:45","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"278","Puntuaci\u00f3n":"90","Uid":"1871","Nombre":"1098628991","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 16:43","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"279","Puntuaci\u00f3n":"90","Uid":"1690","Nombre":"1017198935","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 18:57","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"280","Puntuaci\u00f3n":"90","Uid":"1549","Nombre":"14899331","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 19:01","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"281","Puntuaci\u00f3n":"100","Uid":"1616","Nombre":"1082860622","Evaluado":"S\u00ed","Date finished":"Viernes, Septiembre 29, 2017 - 19:39","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"282","Puntuaci\u00f3n":"80","Uid":"1617","Nombre":"1082869403","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 10:32","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"283","Puntuaci\u00f3n":"90","Uid":"1813","Nombre":"1014214447","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 10:49","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"284","Puntuaci\u00f3n":"90","Uid":"1812","Nombre":"93088550","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 10:55","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"285","Puntuaci\u00f3n":"0","Uid":"1774","Nombre":"1022389702","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"286","Puntuaci\u00f3n":"80","Uid":"1743","Nombre":"79879677","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 11:29","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"287","Puntuaci\u00f3n":"90","Uid":"1707","Nombre":"1128482496","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 11:29","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"288","Puntuaci\u00f3n":"90","Uid":"1781","Nombre":"1033738395","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 11:38","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"289","Puntuaci\u00f3n":"90","Uid":"1868","Nombre":"79541464","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 11:44","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"290","Puntuaci\u00f3n":"70","Uid":"1700","Nombre":"1044423276","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 11:46","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"291","Puntuaci\u00f3n":"90","Uid":"1679","Nombre":"55301209","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 11:52","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"292","Puntuaci\u00f3n":"90","Uid":"1814","Nombre":"1016027030","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 11:51","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"293","Puntuaci\u00f3n":"80","Uid":"1557","Nombre":"25872363","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 12:07","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"294","Puntuaci\u00f3n":"80","Uid":"1607","Nombre":"94041085","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 12:41","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"295","Puntuaci\u00f3n":"80","Uid":"1552","Nombre":"22589702","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 16:12","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"296","Puntuaci\u00f3n":"80","Uid":"1671","Nombre":"51607361","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Septiembre 30, 2017 - 17:11","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"297","Puntuaci\u00f3n":"90","Uid":"1","Nombre":"admin","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 16:19","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"299","Puntuaci\u00f3n":"60","Uid":"1555","Nombre":"25289696","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 16:55","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"300","Puntuaci\u00f3n":"80","Uid":"1630","Nombre":"24219789","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 16:58","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"301","Puntuaci\u00f3n":"70","Uid":"1702","Nombre":"1075650251","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:00","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"302","Puntuaci\u00f3n":"30","Uid":"1693","Nombre":"1026262579","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:03","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"303","Puntuaci\u00f3n":"80","Uid":"1844","Nombre":"98625286","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:03","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"304","Puntuaci\u00f3n":"90","Uid":"1831","Nombre":"43009269","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:05","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"305","Puntuaci\u00f3n":"90","Uid":"1702","Nombre":"1075650251","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:07","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"306","Puntuaci\u00f3n":"50","Uid":"1693","Nombre":"1026262579","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:13","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"307","Puntuaci\u00f3n":"20","Uid":"1807","Nombre":"52783712","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:16","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"308","Puntuaci\u00f3n":"80","Uid":"1630","Nombre":"24219789","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:13","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"309","Puntuaci\u00f3n":"80","Uid":"1861","Nombre":"42142517","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:21","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"310","Puntuaci\u00f3n":"60","Uid":"1555","Nombre":"25289696","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:32","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"311","Puntuaci\u00f3n":"60","Uid":"1601","Nombre":"66951678","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:22","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"312","Puntuaci\u00f3n":"0","Uid":"1807","Nombre":"52783712","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:19","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"313","Puntuaci\u00f3n":"0","Uid":"1861","Nombre":"42142517","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"314","Puntuaci\u00f3n":"40","Uid":"1563","Nombre":"31403118","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:41","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"315","Puntuaci\u00f3n":"50","Uid":"1563","Nombre":"31403118","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 17:55","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"316","Puntuaci\u00f3n":"60","Uid":"1579","Nombre":"40410885","Evaluado":"S\u00ed","Date finished":"Viernes, Octubre 20, 2017 - 19:08","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"317","Puntuaci\u00f3n":"80","Uid":"1862","Nombre":"42160488","Evaluado":"S\u00ed","Date finished":"S\u00e1bado, Octubre 21, 2017 - 18:23","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"318","Puntuaci\u00f3n":"0","Uid":"1543","Nombre":"10248150","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"320","Puntuaci\u00f3n":"50","Uid":"1703","Nombre":"1090390874","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 09:07","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"322","Puntuaci\u00f3n":"80","Uid":"1706","Nombre":"1128269099","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 14:27","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"323","Puntuaci\u00f3n":"100","Uid":"1706","Nombre":"1128269099","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 14:47","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"324","Puntuaci\u00f3n":"100","Uid":"1840","Nombre":"71263924","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 14:53","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"325","Puntuaci\u00f3n":"100","Uid":"1831","Nombre":"43009269","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 14:55","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"326","Puntuaci\u00f3n":"100","Uid":"1550","Nombre":"15373211","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 14:59","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"327","Puntuaci\u00f3n":"100","Uid":"1686","Nombre":"98549155","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 15:03","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"328","Puntuaci\u00f3n":"100","Uid":"1636","Nombre":"43163033","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 15:13","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"329","Puntuaci\u00f3n":"80","Uid":"1560","Nombre":"30336095","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 15:26","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"330","Puntuaci\u00f3n":"100","Uid":"1648","Nombre":"98576310","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 15:18","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"331","Puntuaci\u00f3n":"100","Uid":"1635","Nombre":"43111006","Evaluado":"S\u00ed","Date finished":"Lunes, Octubre 23, 2017 - 15:18","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"332","Puntuaci\u00f3n":"0","Uid":"1560","Nombre":"30336095","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}},{"node":{"Quiz result ID":"333","Puntuaci\u00f3n":"0","Uid":"1652","Nombre":"1033699125","Evaluado":"No","Date finished":"","Time_left":"0 seg","Nombre del sitio":"CONECTATE"}}]}',true);
        //var_dump($datosRst);
        return $datosRst;
    }
    public function totalactivos()
    {
        $insertar = array(
            'username' => 'admin',
            'password' => 'p0p01234'
        );
        $datosIncentive =  $this->consultaRest('/usuarios/user/login','POST',$insertar,'http://conectatepublicar.com/','',array('Accept : application/json'));
        //$datosRst =  $this->consultaRest('Reportes/usuarios-activos/json','GET',null,'http://conectatepublicar.com/','',array('Accept : application/json','Cookie'=>$datosIncentive['session_name'] . '=' . $datosIncentive['sessid']));
        $datosRst =  json_decode('',true);
        //var_dump($datosRst);
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
    public function getDominio()
    {
        $rest = substr($_SERVER['HTTP_HOST'], 0,6);
        switch ($rest) {
            case 'sumate':
                $variable = 2;
            break;
            case 'conect':
                $variable = 1;
            break;
        }
        return $variable;
    }
    public function cargarComponentesform($datosForm)
    {
        $html = '<form action="'.base_url().index_page().'/admin/Cargatablas/exportar/'.$datosForm[0]->tabla_nombre.'" method="'.$datosForm[0]->tabla_method.'">';
        if (!is_null($datosForm[0]->campo_id)) {
            foreach ($datosForm as $key) {
                switch ($key->tipocampo_nombre) 
                {
                    case 'input':
                        $retVal = ($key->campo_hidden) ? 'hidden' : 'text' ;
                        $rest = substr($key->campo_value, 0,2);
                        switch ($rest) {
                            case '{{':
                                $retValValue = $this->{substr($key->campo_value, 2,-2)}();
                            break;
                            case '[[':
                                switch ($key->campo_value) {
                                    case '[[NOT NULL]]':
                                        $retValValue = 'NOT NULL';
                                    break;
                                    case '[[NULL]]':
                                        $retValValue = 'NULL';
                                    break;
                                    default:
                                        $retValValue = '';
                                    break;
                                }
                            break;
                            default:
                                $retValValue = $key->campo_value;
                            break;
                        }
                        $html .='<input type="'.$retVal.'" name="'.$key->campo_nombre.'" value="'.$retValValue.'">';
                    break;
                    case 'submit':
                        $html .='<input type="submit" value="'.$key->campo_value.'">';
                    break;
                    case 'select':
                        $html .= $this->{$key->campo_funcion}(07);
                    break;
                }
            }
        }else{
            $html .='<input type="submit" value="enviar">';
        }
        $html .= '</form>';
        return $html;
    }
}
?>