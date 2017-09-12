<?php

/**
 * Agile CRM \ Curl Wrap
 * 
 * The Curl Wrap is the entry point to all services and actions.
 *
 * @author    Agile CRM developers <Ghanshyam>
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Curlwrap {

    # Enter your domain name , agile email and agile api key
    /*
    define("AGILE_DOMAIN", "gmloyalty");  # Example : define("domain","gmloyalty");
    define("AGILE_USER_EMAIL", "idelvalle@grupo-link.com");
    define("AGILE_REST_API_KEY", "al4nh10g13v6ggn23k4936khnv");
    */

    function curl_wrap($entity, $data, $method, $content_type,$AGILE_DOMAIN,$AGILE_USER_EMAIL,$AGILE_REST_API_KEY) {
        if ($content_type == NULL) {
            $content_type = "application/json";
        }
        $agile_url = "https://" . $AGILE_DOMAIN . ".agilecrm.com/dev/api/" . $entity;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
        switch ($method) {
            case "POST":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case "GET":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "PUT":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case "DELETE":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                break;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-type : $content_type;", 'Accept : application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $AGILE_USER_EMAIL . ':' . $AGILE_REST_API_KEY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    function curl_wrapIncentive($entity, $data, $method, $content_type = NULL,$header = true) {
        if ($content_type == NULL) {
            $content_type = "application/json";
        }
        $agile_url = $entity;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
        switch ($method) {
            case "POST":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case "GET":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "PUT":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case "DELETE":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                break;
        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-type : $content_type;", 'Accept : application/json'
            ));
        }
        else
        { 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept : application/json'));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_USERPWD, $AGILE_USER_EMAIL . ':' . $AGILE_REST_API_KEY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}