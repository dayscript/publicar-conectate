<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('crud/Crud_noticias');
        $this->load->model('crud/Crud_menu');
        $this->load->library('Array_conevrt');
        if (is_null($this->session->userdata('id'))) {
        	redirect($this->index);
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
		$datoDatos = $this->load->view('admin/adminJS/datos_js',null,TRUE);
		$dataSend = array(
            "datos" => $datoDatos
        );
		$dataFooter = $this->load->view('admin/sobrecargas/footer_view',$dataSend,TRUE);
		$dataSend = array(
            "footer" => $dataFooter,
            'nav' => $datoNav
        );
        $this->load->view('admin/home_view',$dataSend); 
    }
    public function menuviejoControler($visual,$datos = null){
        $this->menuviejo($visual,$datos);
    }
}