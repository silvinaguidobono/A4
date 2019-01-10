<?php
namespace A4\App\Controllers;

use A4\Sys\Controller;
//use A4\Sys\View;
use A4\App\Views\vHome;
use A4\App\Models\mHome;
use A4\Sys\Session;

/**
 * Clase que extiende de Controller
 *
 * @author Silvina Guidobono <silvinaguidobono@gmail.com>
 */
class Home extends Controller {
    function __construct($params) {
        parent::__construct($params);
        $mensaje=Session::get('mensaje');
        $tipo_mensaje=Session::get('tipo_mensaje');
        if(is_null($tipo_mensaje)){
            $tipo_mensaje="";
        }else{
            Session::del('tipo_mensaje');
        }
        if(is_null($mensaje)){
            $mensaje="";
        }else{
            Session::del('mensaje');
        }
        
        $this->addData([
            'page'=>'Home',
            'titulo'=>'Tareas a hacer',
            'mensaje'=>$mensaje,
            'tipo_mensaje'=>$tipo_mensaje
        ]);
        $this->model=new mHome();
        $this->view=new vHome($this->dataView, $this->dataTable);
    }
    
    function home(){
        $this->view->show();
    }
    
}
