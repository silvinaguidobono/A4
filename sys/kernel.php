<?php

namespace A4\Sys;
use A4\Sys\Request;

/**
 * Application Kernel
 *
 * @author Silvina Guidobono <silvinaguidobono@gmail.com>
 */
class kernel {
    /**
      * This methode allows initializa application
      * Frontend Controller
      * @return void
      */

    static private $controller;
    static private $action;
    static private $params;
    

    public static function init(){
        //echo 'INIT';
        
        //process the REQUEST_URI
        Request::exploding();
        // attributes extraction
        self::$controller= Request::extract();
        self::$action=Request::extract();
        self::$params=Request::getParams(); // completar
        /*
        var_dump(self::$controller);
        var_dump(self::$action);
        var_dump(self::$params);
        die;
        */
        //call to Router applying 
        self::router();
        //controller and action
        
    }
    
    //static function getFileCont():?string{
    static function getFileCont(){
        // recibe la variable controller
        // select default action and controller
        // home es la accion y el controlador por defecto
        self::$controller=(self::$controller!="")?self::$controller:'home';
        self::$action=(self::$action!="")?self::$action:'home';
        
        //$filename= strtolower(self::$controller).'Controller.php';
        $filename= strtolower(self::$controller).'.php';
        
        //$fileroute=       **** completar
        //$fileroute="app/controllers/".$filename;
        $fileroute=APP."controllers/".$filename;
        
        return $fileroute;
    }

    /**
     * looks for controller and action
     * instantiate controller and calls the action
     * 
     * @return void
     */
    static function router(){
        self::$controller=(self::$controller!="")?self::$controller:'home';
        self::$action=(self::$action!="")?self::$action:'home';
        
        $class='\\A4\App\Controllers\\'.ucfirst(self::$controller);
        if(class_exists($class)){
            // instantiate class
            // Reemplaza el nombre controlador por objeto clase controlador??
            self::$controller= new $class(self::$params);
            // new object calls action
            // action call
            if(is_callable(array(self::$controller,self::$action))){
                // si existe ese método dentro de ese controller, llamarlo
                call_user_func(array(self::$controller, self::$action));
            }else{ // is not callable
                //echo "No es llamable esa acción";
                //die;
                self::$action='error';
                // lanzar un método error
                // $mensaje_error = "No es llamable esa acción";
                // Ver como enviar también el mensaje de error
                call_user_func(array(self::$controller, self::$action));
                
            }
        }else{ // si la clase no existe
            // error controller
            echo "La clase: ".$class." no existe";
            die;
            //self::$controller=new Error;
            // generar nueva clase Error en app/controllers con atributo mensaje
            $mensaje="No existe el controlador";
            self::$controller=new Error($mensaje);
            self::$action="mostrarError";
            call_user_func(array(self::$controller, self::$action));
        }
    }
}
