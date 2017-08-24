<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('crud/Crud_noticias');
        $this->load->model('crud/Crud_menu');
        $this->load->library('Array_conevrt');
        if (is_null($this->session->userdata('id'))) {
        	redirect('Login');
        }
    }

    public function index()
    {	
		$this->load->view('admin/sobrecargas/head_view');
        $dataSend = array(
            "datos" =>  array(
                'noticias' => $this->Crud_noticias->GetDatosTotales(5),
                'menu' => $this->ordenarMenu($this->session->userdata('rol_id'))
            )
        );
		$datoNav = $this->load->view('admin/sobrecargas/nav_view',$dataSend,TRUE);
		$datoDatos = $this->load->view('admin/adminJS/datos_js_home',null,TRUE);
		$dataSend = array(
            "datos" => $datoDatos            
        );
        $da = $this->cargarDatosHome();
		$dataFooter = $this->load->view('admin/sobrecargas/footer_view',$dataSend,TRUE);
		$dataSend = array(
            "footer" => $dataFooter,
            'nav' => $datoNav,
            'datosCarga' => $da
        );
        $this->load->view('admin/home_view',$dataSend);
    }
    public function reporteGrupal()
    {
        $this->load->view('admin/sobrecargas/head_view');
        $dataSend = array(
            "datos" =>  array(
                'noticias' => $this->Crud_noticias->GetDatosTotales(5),
                'menu' => $this->ordenarMenu($this->session->userdata('rol_id'))
            )
        );
        $datoNav = $this->load->view('admin/sobrecargas/nav_view',$dataSend,TRUE);
        $datoDatos = $this->load->view('admin/adminJS/datos_js_reportegrupo',null,TRUE);
        $dataSend = array(
            "datos" => $datoDatos            
        );
        $dataFooter = $this->load->view('admin/sobrecargas/footer_view',$dataSend,TRUE);
        $dataSend = array(
            "footer" => $dataFooter,
            'nav' => $datoNav,
            'datosCargo' => $this->getdatosxgrupo()
        );
        $this->load->view('admin/reportegrupo_view',$dataSend);
    }
    public function reportexgrupo($grupo)
    {
        $datos = $this->rankingxgrupoxMes('07',null,$grupo);
        $envioTablas = array(
            'cargo1FinalTabla' => $this->listTablaCargo($datos['cargo1Final']), 
            'cargo2FinalTabla' => $this->listTablaCargo($datos['cargo2Final']), 
            'cargo3FinalTabla' => $this->listTablaCargo($datos['cargo3Final']), 
            'cargo4FinalTabla' => $this->listTablaCargo($datos['cargo4Final']), 
            'cargo5FinalTabla' => $this->listTablaCargo($datos['cargo5Final']), 
        );
        $this->load->view('admin/sobrecargas/head_view');
        $dataSend = array(
            "datos" =>  array(
                'noticias' => $this->Crud_noticias->GetDatosTotales(5),
                'menu' => $this->ordenarMenu($this->session->userdata('rol_id'))
            )
        );
        $datoNav = $this->load->view('admin/sobrecargas/nav_view',$dataSend,TRUE);
        $datoDatos = $this->load->view('admin/adminJS/datos_js_reportegrupo',null,TRUE);
        $dataSend = array(
            "datos" => $datoDatos            
        );
        $dataFooter = $this->load->view('admin/sobrecargas/footer_view',$dataSend,TRUE);
        $dataSend = array(
            "footer" => $dataFooter,
            'nav' => $datoNav,
            'datos' => $datos,
            'envioTablas' => $envioTablas
        );
        $this->load->view('admin/reportegrupodiscriminado_view',$dataSend);
    }
    public function menuviejoControler($visual,$datos = null){
        $this->menuviejo($visual,$datos);
    }
}