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
        if(isset($params['mensaje'])){
            $this->mensaje=$params['mensaje'];
        }else{
            $this->mensaje="Se ha producido un error";
        }
        
    }
    
    function home(){
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
    
}
