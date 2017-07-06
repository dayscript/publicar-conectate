<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

//clase para tomar como libreria
class Array_conevrt {

    /*
     * -------------------------------------------------------------------------
     * Metodo para enviar mensajes por SMTP
     * -------------------------------------------------------------------------
     */

    public function array_group_by($arr, $key)
	{
		if (!is_array($arr)) {
			trigger_error('array_group_by(): The first argument should be an array', E_USER_ERROR);
		}
		if (!is_string($key) && !is_int($key) && !is_float($key)) {
			trigger_error('array_group_by(): The key should be a string or an integer', E_USER_ERROR);
		}
		// Load the new array, splitting by the target key
		$grouped = [];
		foreach ($arr as $value) {
			$grouped[$value[$key]][] = $value;
		}
		// Recursively build a nested grouping if more parameters are supplied
		// Each grouped array value is grouped according to the next sequential key
		if (func_num_args() > 2) {
			$args = func_get_args();
			foreach ($grouped as $key => $value) {
				$parms = array_merge([$value], array_slice($args, 2, func_num_args()));
				$grouped[$key] = call_user_func_array('array_group_by', $parms);
			}
		}
		return $grouped;
	}
	public function generateRandomString($length = 10) { 
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
    } 
    public function GenerarFecha($datosTotal)
    {
    	$time = strtotime($datosTotal);
		switch (date('m',$time)) {
	        case '01':
	            $mes = 'Enero';
	        break;
	        case '02':
	            $mes = 'Febrero';
	        break;
	        case '03':
	            $mes = 'Marzo';
	        break;
	        case '04':
	            $mes = 'Abril';
	        break;
	        case '05':
	            $mes = 'Mayo';
	        break;
	        case '06':
	            $mes = 'Junio';
	        break;
	        case '07':
	            $mes = 'Julio';
	        break;
	        case '08':
	            $mes = 'Agosto';
	        break;
	        case '09':
	            $mes = 'Septiembre';
	        break;
	        case '10':
	            $mes = 'Octubre';
	        break;
	        case '11':
	            $mes = 'Noviembre';
	        break;
	        case '12':
	            $mes = 'Diciembre';
	        break;
	  }
  		return $mes.' /'.date('d', $time). ' de '.date('Y', $time);
    }


}
