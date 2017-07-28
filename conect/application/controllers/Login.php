<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('crud/Crud_usuario');
    }

	public function index($mensaje = null,$popup = null)
	{
		if (is_null($this->session->userdata('id'))) {
            $dataSend = array(
                "mensaje" => $mensaje ,
                'popup' => $popup
            );
	        $this->load->view('admin/login_view',$dataSend);
	    }else
	    {
	    	$this->redirecionar($this->session->userdata('rol_id'));
	    }
	}
    public function logeo()
    {
        $clave = $this->input->post("Password1", TRUE);
        $usuario_usuario = $this->input->post("usuario", TRUE);
        $capcha = $this->input->post("g-recaptcha-response", TRUE);
        $capcha = 'ssss';
        if ($capcha != '') 
        {
            $usuario_id = $this->Crud_usuario->GetExiste($usuario_usuario,$clave);
            if ($usuario_id) {
                $where = array('p.usuario_id' => $usuario_id);
                $usuario = $this->Crud_usuario->GetDatos($where)[0];
                $this->crearSesion($usuario);
                $this->redirecionar($this->session->userdata('rol_id'));
                
            }else{
                $mensaje = 'Usuario o contraseña incorecto';
                $this->index($mensaje);
            };
        }
        else
        {
            $mensaje = 'No olvides llenar la CAPTCHA';
            $this->index($mensaje);
        }
    }
    public function callupload($width = null,$height = null,$input = null,$tipoarchivo,$tipocarga) {
        if ($width == 'null' or $width == 'NULL') {
            $width = null;
        }
        if ($height == 'null' or $height == 'NULL') {
            $height = null;
        }
        echo $this->upload($width,$height,$input,$tipoarchivo,$tipocarga);
    }
    public function cerrarSesion() 
    {
        $this->cerrarSession();
    }
    /*
	public function mensaje($mensaje = null)
	{
		$this->index(null,$mensaje);
	}
	public function tarjeta($mensaje = null)
	{
		if (is_null($this->session->userdata('id'))) {
			$dataSend = array(
	            "mensaje" => $mensaje 
	        );
	        $nav = array('tipomenu' => 2);
	        $this->load->view('cliente/sobrecargas/head_view');
	        $this->load->view('cliente/sobrecargas/nav_view',$nav);
	        $this->load->view('cliente/tarjeta_view',$dataSend);
	    }else
	    {
	    	$this->redirecionar($this->session->userdata('rol_id'));
	    }
	}
	public function olvidecontrasena($mensaje = null)
	{
		if (is_null($this->session->userdata('id'))) {
			$dataSend = array(
	            "mensaje" => $mensaje 
	        );
	        $nav = array('tipomenu' => 2);
	        $this->load->view('cliente/sobrecargas/head_view');
	        $this->load->view('cliente/sobrecargas/nav_view',$nav);
	        $this->load->view('cliente/olvidemicontrasena_view',$dataSend);
	    }else
	    {
	    	$this->redirecionar($this->session->userdata('rol_id'));
	    }
	}
	
	
    public function activacion($dato = null)
    {
    	if ($this->input->is_ajax_request()) 
    	{
    		$insertar = array('usuario_clave' => md5($this->input->post('password2')),'usuario_actualizado' => 1);
    		$where = array('usuario_codigounico' => $this->input->post('d'));
    		$usuario = $this->Crud_usuario->GetDatos($where)[0];
    		if (is_null($dato)) {
                 if ($usuario->usuario_actualizado == "0") {
    				$contact1 = $this->buscarUsuarioAgile($usuario->usuario_codigoagile,'id');
    				$result  = json_decode($contact1, false, 512, JSON_BIGINT_AS_STRING);
    				$result1 = $this->editarContactoAgile($result,'on','Actualizado');
    				$result1 = $this->editarContactoAgile($result,'Activado','Estado_cliente');
    				$tag = array('tipo' => 'codigoagile','datos' => $usuario->usuario_codigoagile,'tag'=>'Activado');
    	            $return = $this->agregarTag($tag);
                    $this->agregarPuntosIncentive($usuario->usuario_documento,1,$usuario->usuario_id);
                    if ($usuario->genero_id == 1) {
                        $genero =  'o';
                    }
                    else
                    {
                        $genero =  'a';
                    }
                    $this->envioSMS($usuario->usuario_celular,$usuario->usuario_nombre.', bienvenid'.$genero.' A VIVE+. Para saber cómo acumular y redimir tus puntos ingresa a www.vive-mas.co');
                }      
                $return = array('estado' => true,'carga'=>'cambio de clave');
                $this->Crud_usuario->editar($insertar,$where);
                $this->crearSesion($usuario);
    		}
            else
            {
                $return = array('estado' => true,'carga'=>'cambio de clave');
                $this->Crud_usuario->editar($insertar,$where);
                $this->crearSesion($usuario);
            }
    	}
        else
        {
            $return = array('estado' => false,'carga'=>'falla');
        }
        echo json_encode($return, JSON_FORCE_OBJECT);
    }
    public function recuperarContra()
    {
    	if ($this->input->is_ajax_request()) {
    		$where = array('usuario_correo' => $this->input->post('Email'));
			$usuario = $this->Crud_usuario->GetDatos($where);
			if (!is_null($usuario)) 
			{
                if ($usuario[0]->usuario_actualizado == '0') {
                    $tag = array('tipo' => 'codigoagile','datos' => $usuario[0]->usuario_codigoagile,'tag'=>'Reactivacion');
                    $return = $this->agregarTag($tag);
                    $return = array('estado' => true,'carga'=>'Acabamos de enviarte un correo de confirmación.'); 
                }
                else
                {
                    $tag = array('tipo' => 'codigoagile','datos' => $usuario[0]->usuario_codigoagile,'tag'=>'Recuperar Clave');
                    $return = $this->agregarTag($tag);
                    $return = array('estado' => true,'carga'=>'Acabamos de enviarte un correo de confirmación.'); 
                }
			}
			else
			{
				$return = array('estado' => false,'carga'=>'Este correo no está registrado en vive-mas.co.');		
			}
    	}
        else
        {
            $return = array('estado' => false,'carga'=>'falla');
        }
        echo json_encode($return, JSON_FORCE_OBJECT);
    }
    public function buscarDocumentoAsignacion(){
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->asignaciousuario($this->input->post('documento')),NULL);
        }
    }
    public function buscarTarjetaAsignacion(){
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->asignaciousuario($this->input->post('tarjeta'),1),NULL);
        }
    }
    public function cambiarUsuarioAsignado()
    {
        $this->session->set_userdata('id_uduariosBusqueda', NULL);
        $this->index();
    }
    public function buscarDocumento(){
        if ($this->input->is_ajax_request()) {
            $documento = $this->input->post('documento');
            $tpdocumento = $this->input->post('tpdocumento');
            $where = array(
                'usuario_documento' => $documento,
                'tipodocumento_id' => $tpdocumento 
                );
            $resultado = $this->Crud_usuario->GetDatos($where);
            if ($resultado != NULL) {
                if (!is_null($this->session->userdata('id'))) {
                    $this->Crud_log->Insertar('BusquedaKiosco',$this->session->userdata('id'),'Busqueda de documeto '.$documento);
                }
                echo json_encode($resultado, JSON_FORCE_OBJECT);  
            }else{
                if (!is_null($this->session->userdata('id'))) {
                    $tempo = array('false' => false);
                    $this->Crud_log->Insertar('BusquedaKiosco',$this->session->userdata('id'),'Busqueda de documeto '.$documento.' no encoontrado');
                }
                echo json_encode(false, JSON_FORCE_OBJECT);
            }
        }
    }
    public function validarTarjeta(){
        if ($this->input->is_ajax_request()) {
            $uno = $this->input->post('uno');
            $dos = $this->input->post('dos');
            $tres = $this->input->post('tres');
            $where = array(
                'tarjeta_consecutivo' => (int) ($uno.$dos)
            );
            $tarjeta = $this->Crud_tarjeta->GetDatos($where);
            if (!is_null($tarjeta)) 
            {
            	if ($tarjeta[0]->estadotarjeta_id == 1) {
            		if ($tarjeta[0]->tarjeta_codigo == $tres) {
	            		$return = array('estado' => true,'carga'=>$tarjeta[0]->tarjeta_id);	
	            	}
	            	else
	            	{
	            		$return = array('estado' => false,'carga'=>'Código de activación incorrecto');
	            	}
            	}
            	else
            	{
            		$return = array('estado' => false,'carga'=>'Esta tarjeta ya está activada');
            	}
            }
            else
            {
            	$return = array('estado' => false,'carga'=>'Esta tarjeta no está registrada ');
            }
            echo json_encode($return, JSON_FORCE_OBJECT);
        }
    }
    public function  ajusteDeHora()
    {
        var_dump(date('Y-m-j H:i:s'));
        var_dump(date('Y-m-j H:i:s',$this->ajusteFecha));
    }
    */
    
}
