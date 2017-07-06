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

	function __construct()
    {
        parent::__construct();
        $this->cargarVariablesGlobales();
        $this->load->model('crud/Crud_rol');
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
    
    public function restDrupal($datos = NULL,$metodo = 'get')
    {
        $this->load->library('basic_RestClient/my_restclient');
        $datosConect = array(
            'urlServicesDrupal' => $this->urlServicesDrupal, 
            'usuarioServicesDrupal' =>$this->usuarioServicesDrupal , 
            'claveServicesDrupal'=>$this->claveServicesDrupal
        );
        return $this->my_restclient->crearUsuarioDrupal($datosConect,$metodo,$datos);
    }
}
?>