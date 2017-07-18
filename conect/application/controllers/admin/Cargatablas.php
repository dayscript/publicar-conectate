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
        	redirect('admin/Login');
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
            $this->load->view('admin/controler_view',$dataSend);
        }
        else
        {
            $this->redirecionar($this->session->userdata('rol_id'));
        }
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
                'datosEditar' => null
            );
        }
        return $info;
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
                    //var_dump(json_encode($columnaTitulo));  
                    $lista = $this->crearArray($columnaTitulo,strtolower($tabla));
                    //var_dump(json_encode($lista));
                    //echo "<br>";
                    //$this->Crud_ventas->Insertar($lista);
                    //var_dump($this->Crud_model->agregarRegistro('produccion_'.strtolower($tabla),$lista));
                    $this->Crud_model->agregarRegistro('produccion_'.strtolower($tabla),$lista);
                    //echo "<br>";
                endfor;
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
        return $lista;
    }
    public function limpiarArray($columnaTitulo1)
    {
        for ($i=0; $i < count($columnaTitulo1); $i++) { 
            $columnaTitulo1[$i]['valor']= '';
        }
        return $columnaTitulo1;

    }

}



