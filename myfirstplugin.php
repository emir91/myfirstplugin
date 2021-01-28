<?php
/**
 * Plugin Name: Table Data Adding
 * Description: Fetch and add data to table.
 * Version: 1.0
 * Author: emirvranac
 * Text Domain: employee
 */

 defined('ABSPATH') or die('Unautorized Access!');

 add_action('admin_menu', 'plugin_setup_menu');

 function plugin_setup_menu(){
     add_menu_page('Plugin Page', 'Table Data Adding', 'manage_options', 'table-data-adding', 'callback_function_name');
 }

 function get_api_info(){
     global $apiInfo;
     if( empty($apiInfo) ) $apiInfo = get_transient('api_info');
     if( !empty($apiInfo) ) return $apiInfo;

    $response = wp_remote_get("https://dummy.restapiexample.com/public/api/v1/employees");
    $data = wp_remote_retrieve_body($response);

    if( empty($data) ) return false;

    $apiInfo = json_decode($data);
    set_transient( 'api_info', $apiInfo, 12 * HOURS);

    return $apiInfo;
     
 }

 add_shortcode('external_data', 'callback_function_name');

 function callback_function_name(){
    
    $results = get_api_info();
    //var_dump($results);
    $objects = $results->data;
    

    $html = '';
    $html .= '<table>';
    $html .= '<tr>';
    $html .= '<td>ID</td>';
    $html .= '<td>Name</td>';
    $html .= '<td>Salary</td>';
    $html .= '<td>Age</td>';
    $html .= '</tr>';
    
    foreach( $objects as $object ){

        $html .= '<tr>';
        $html .= '<td>' . $object->id . '</td>';
        $html .= '<td>' . $object->employee_name . '</td>';
        $html .= '<td>' . $object->employee_salary . '</td>';
        $html .= '<td>' . $object->employee_age . '</td>';
        $html .= '</tr>';
    }


    $html .= '</table>';

    echo $html; 
 }

 