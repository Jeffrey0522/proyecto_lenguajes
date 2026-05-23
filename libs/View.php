<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of View
 *
 * @author Nelson
 */
class View {
    
    public function __construct() {
        ;
    } // constructor
    
     public function show($nombreVista, $vars=array()){
         $config= Config::singleton();
         $path=$config->get('viewFolder').$nombreVista;
         
         if(is_file($path)==FALSE){
             trigger_error('Pagina ' . $path . 'No existe', E_USER_NOTICE);
             return FALSE;
         }
         
         if(is_array($vars)){
             foreach ($vars as $key=>$value){
                 $key=$value;
             } //  foreach
         } // if(is_array($vars))
         
         include $path;
         
     } // show
    
} // fin clase

?>
