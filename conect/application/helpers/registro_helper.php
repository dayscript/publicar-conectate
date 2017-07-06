<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('mensaje')) {

    function mensaje($pMensaje, $pTipo = "") {
        //valido que el mensaje no llegue vacio
        if ($pMensaje != '') {
            echo '<!-- inicio mensaje --><div class="alert ' . $pTipo . ' fade in"><button type="button" class="close" data-dismiss="alert">x</button>' . $pMensaje . '</div><!-- fin de mensaje -->';
        }//fin del if
    }

}//fin de la función


if (!function_exists('reemplazarEstado')) {

    function reemplazarEstado($pIdEstado) {

        //creo el valor retorno
        $valorRetorno = 'Activo';
        if ($pIdEstado == 2) {
            $valorRetorno = 'Inactivo';
        }//fin del if
        //devolvemos el valor retorno
        return $valorRetorno;
    }

}//fin de la función
/**
 * Funcion para remplaza el tipo de banner
 */
if (!function_exists('reemplazarTipoBanner')) {

    function reemplazarTipoBanner($pIdTipoBanner) {
        //creo el valor retorno
        $valorRetorno = 'Banner Index';
        if ($pIdTipoBanner == 2) :
            $valorRetorno = 'Banner Interno';
        elseif ($pIdTipoBanner == 3):
            $valorRetorno = 'Banner Superior';
        endif;
        ////fin del if
        //devolvemos el valor retorno
        return $valorRetorno;
    }

}//fin de la función
/**
 * Funcion para remplaza el tipo de banner
 */
if (!function_exists('reemplazarTipoProducto')) {

    function reemplazarTipoProducto($pIdTipoProducto) {
        //creo el valor retorno
        $valorRetorno = 'Servicio';
        if ($pIdTipoProducto == 1) :
            $valorRetorno = 'Producto';
        endif;
        ////fin del if
        //devolvemos el valor retorno
        return $valorRetorno;
    }

}//fin de la función

if (!function_exists('controlSelect')) {

    /**
     * 
     * @param type $pDataRegistros
     * @param type $pCampoValue
     * @param type $pCampoNombre
     * @param type $pDataPlaceholder
     * @param type $valueSelect
     * @return string
     */
    function controlSelect($pDataRegistros = NULL, $pCampoValue = NULL, $pCampoNombre = NULL, $pDataPlaceholder = 'Seleccione una opción', $valueSelect = 0) 
    {
        //validamos el parámetro
        if ($pDataRegistros != NULL) {
            //iteramos el arreglo que llega
            $dataRegistros[''] = $pDataPlaceholder;
            foreach ($pDataRegistros as $filaRegistros):
                $dataRegistros[$filaRegistros->$pCampoValue] = $filaRegistros->$pCampoNombre;
            endforeach;
        }//fin del if
        else {
            $dataRegistros = array(
                '' => 'Seleccione una opción',
                '1' => 'Activo',
                '2' => 'Inactivo',
            );
        }//fin del if else
        //devolvemos los datos
        return $dataRegistros;
    }

}//fin de la función
if (!function_exists('controlSelectEys')) {

    function controlSelectEys($pDataRegistros = NULL, $pCampoValue = NULL, $pCampoNombre = NULL) {

        //validamos el parámetro
        if ($pDataRegistros != NULL) {
            //iteramos el arreglo que llega
            $dataRegistros[''] = 'Seleccione una opción';
            foreach ($pDataRegistros as $filaRegistros):
                if ($filaRegistros->pro_tipo == 1):
                    $pCampoNombre = "pro_nombre_eys";
                else:
                    $pCampoNombre = "pro_nombre";
                endif;
                $dataRegistros[$filaRegistros->$pCampoValue] = $filaRegistros->$pCampoNombre;
            endforeach;
        }//fin del if
        else {
            $dataRegistros = array(
                '' => 'Seleccione una opción',
                '1' => 'Activo',
                '2' => 'Inactivo',
            );
        }//fin del if else
        //devolvemos los datos
        return $dataRegistros;
    }

}//fin de la función


if (!function_exists('controlInput')) {

    function controlInput($pNombre, $pValor, $pPlaceHolder, $pClase = "", $pDisabled = FALSE, $pMaxLength = 45,$pType = "",$pRequire = false) {
        if($pRequire)
        {
        $valorInput = array(
            'name' => $pNombre,
            'id' => $pNombre,
            'class' => "input-xlarge " . $pClase,
            'value' => $pValor,
            'maxlength' => $pMaxLength,
            'placeholder' => $pPlaceHolder,
            'placeholder' => $pPlaceHolder,
            'autocomplete' => 'off',
            'type' => $pType,
            'required' => $pRequire
        );
    }else{
        $valorInput = array(
            'name' => $pNombre,
            'id' => $pNombre,
            'class' => "input-xlarge " . $pClase,
            'value' => $pValor,
            'maxlength' => $pMaxLength,
            'placeholder' => $pPlaceHolder,
            'placeholder' => $pPlaceHolder,
            'autocomplete' => 'off',
            'type' => $pType
        );
    }
        if ($pDisabled):
            $valorAdicional = array(
                //'disabled' => 'disabled'
                'readonly' => 'readonly',
            );
            $valorInput = array_merge((array) $valorInput, (array) $valorAdicional);
        endif;
        return $valorInput;
    }

}


if (!function_exists('cambiarPortal')) {

    function listarOtrosPortales($dataInstacias) {
        //inicializar los otros portales
        $valorRetorno = '<ul class="dropdown-menu"><li class="nav-header">Otros Portales</li>';

        //Iterar el array de los portales
        foreach ($dataInstacias as $intancia) :
            //crear la lista de icono
            $valorRetorno .= '<li><a href="#" data-toggle="modal" data-target="#modalCambiar' . $intancia->por_id . '">'
                    . '<span class="' . $intancia->por_clase_icono . '"></span>'
                    . $intancia->por_nombre . '</a></li>';
        endforeach;

        $valorRetorno .= '</ul>';

        //Iterar el para crear los modals
        foreach ($dataInstacias as $intancia) :
            //crear la lista de icono
            $valorRetorno .= ''
                    . '<!--inicio del modal-->
          <div class="modal fade" id="modalCambiar' . $intancia->por_id . '">
          <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Confirmar Acción</h4>
          </div>
          <div class="modal-body">
          Está cambiando de portal a <span class="text-info">' . $intancia->por_nombre . '</span>
          <p>¿Está seguro de <span class="text-danger">REALIZAR</span> esta acción?</p>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <a href="' . base_url("seleccionarportal/cambiarPortal/" . $intancia->por_id) . '" type="button" class="btn btn-primary">Confirmar</a>
          </div>
          </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->';
        endforeach; //fin de la iteracion de los modals

        echo $valorRetorno;
    }

}//fin de la función
//FUNCION PARA LIMPIAR LOS CARACTERES ESPECIAL
if (!function_exists('quitarBakcSlash')) {

    function quitarBakcSlash($string) {
        $string = htmlentities($string);
        $string = stripslashes($string);
        return $string;
    }

}//FIN DE LA FUNCION
//funcion para calcular dias transucrridos entre dos fechas
if (!function_exists('dias_transcurridos')) {

    function dias_transcurridos($fecha_i, $fecha_f) {
        $dias = (strtotime($fecha_i) - strtotime($fecha_f)) / 86400;
        $dias = abs($dias);
        $dias = floor($dias);
        return $dias;
    }

}
//funcion para generar codigo aleatorio
if (!function_exists('generarCodigo')) {

    function generarCodigo($longitud) {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($pattern) - 1;
        for ($i = 0; $i < $longitud; $i++)
            $key
                    .= $pattern{mt_rand(0, $max)};
        return $key;
    }

}

//FUNCION PARA LIMPIAR LOS CARACTERES ESPECIAL
if (!function_exists('limpiarCaracteresEspeciales')) {

    function limpiarCaracteresEspeciales($string) {
        $string = htmlentities($string);
        $string = preg_replace('/\&(.)[^;]*;/', '\\1', $string);
        $string = str_replace(" ", "-", $string);
        $string = str_replace(",", "", $string);
        $string = str_replace(".", "", $string);
        $string = str_replace('""', "", $string);
        $string = str_replace("''", "", $string);
        $string = str_replace("@", "", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("#", "", $string);
        $string = str_replace("$", "", $string);
        $string = str_replace("%", "", $string);
        $string = str_replace("&", "", $string);
        $string = str_replace("/", "", $string);
        $string = str_replace("(", "", $string);
        $string = str_replace(")", "", $string);
        $string = str_replace("=", "", $string);
        $string = str_replace("?", "", $string);
        $string = str_replace("¿", "", $string);
        $string = str_replace("¡", "", $string);
        $string = str_replace("'", "", $string);
        $string = str_replace("[", "", $string);
        $string = str_replace("]", "", $string);
        $string = str_replace("{", "", $string);
        $string = str_replace("}", "", $string);
        $string = str_replace("{", "", $string);
        $string = str_replace("´", "", $string);
        $string = str_replace("*", "", $string);
        $string = str_replace("+", "", $string);
        $string = str_replace("~", "", $string);
        $string = str_replace("*", "", $string);
        $string = str_replace("¨", "", $string);
        $string = str_replace("`", "", $string);
        $string = str_replace(":", "", $string);
        $string = str_replace(";", "", $string);
        $string = str_replace("@", "", $string);
        $string = str_replace("^", "", $string);
        $string = str_replace("¬", "", $string);
        $string = str_replace("°", "", $string);
        $string = str_replace("|", "", $string);
        return $string;
    }

}

//FIN DE LA FUNCION



