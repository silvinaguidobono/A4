<?php
namespace A4\App\Controllers;

use A4\Sys\Controller;
use A4\App\Views\vError;

/**
 * Clase que extiende de Controller
 * Muestra los errores referidos al controlador
 *
 * @author Silvina Guidobono <silvinaguidobono@gmail.com>
 */
class Error extends Controller{
    private $mensaje;
    
    function __construct($params) {
        parent::__construct($params);
        $this->mensaje=$params;
    }
    /*
    function __construct($textoMensaje) {
        $this->mensaje=$textoMensaje;
    }
    */
    function mostrarError(){
        $this->addData([
            'page'=>'Error',
            'titulo'=>'Tareas a hacer',
            'mensajeError'=> $this->mensaje
        ]);
        $this->view=new vError($this->dataView, $this->dataTable);
        $this->view->show();
    }
    
    function setMensaje($textoMensaje){
        $this->mensaje=$textoMensaje;
    }
    
        /**
     * Muestra un mensaje de error en el controlador Error
     * 
     * @param string $mensaje Mensaje de error
     * return void
     */
    function error($mensaje="Error en controlador de errores"){
        echo $mensaje;
    }
}
