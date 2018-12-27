<?php

namespace A4\App\Controllers;

/**
 * Description of tarea
 *
 * @author linux
 */

use A4\Sys\Controller;
use A4\App\Views\vTarea;
use A4\App\Views\vTareaNueva;
use A4\App\Views\vTareaVer;
use A4\App\Views\vTareaEditar;
use A4\App\Models\mTarea;
use A4\Sys\Session;

class Tarea extends Controller{
    function __construct($params) {
        parent::__construct($params);
    }
    
    function home(){
        $id_usuario=Session::get('id_usuario');
        $nombre=Session::get('nombre');
        $apellidos=Session::get('apellidos');
        $mensaje=Session::get('mensaje');
        $tipo_mensaje=Session::get('tipo_mensaje');
        
        if(is_null($id_usuario)){
            $id_usuario=0;
        }
        if(is_null($nombre)){
            $nombre="";
        }
        if(is_null($apellidos)){
            $apellidos="";
        }
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
        
        $titulo="Tareas del usuario: ".$nombre." ".$apellidos;
    
        $this->model=new mTarea();
        
        $tareas= $this->model->listarTareas($id_usuario);
        
        $cant_tareas= count($tareas);
        
        $this->addData([
            'page'=>'Tareas',
            'titulo'=>$titulo,
            'tareas'=>$tareas,
            'cantTareas'=>$cant_tareas,
            'mensaje'=>$mensaje,
            'tipo_mensaje'=>$tipo_mensaje
        ]);
        
        //print_r($this->dataTable);
        //print_r($this);
        //exit;
        
        $this->view=new vTarea($this->dataView, $this->dataTable);
        //$this->view->__construct($this->dataView);
        $this->view->show();
        //print_r($this->dataView);
        //echo "<br>";
    }
    
    function nueva(){
        
        $titulo="Nueva tarea";
        
        $this->addData([
            'page'=>'Nueva Tarea',
            'titulo'=>$titulo
        ]);
        
        $this->view=new vTareaNueva($this->dataView, $this->dataTable);
        $this->view->show();
    }
    
    function agregar(){
        // comprobación de formulario
        // Devuelve false si falla el filtro
        // Devuelve NULL si la variable no está definida
        $titulo=filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $descripcion=filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
        //$this->ajax(''=>'');
        
        $id_usuario=Session::get('id_usuario');
        if(is_null($id_usuario)){
            $id_usuario=0;
        }
        
        $this->model=new mTarea();
        
        $result=$this->model->agregarTarea($titulo,$descripcion,$id_usuario);
        
        // Si no hubo error de inserción, envío mensaje de éxito
        if ($result){ 
            Session::set('mensaje', "Tarea insertada correctamente");
            Session::set('tipo_mensaje', "success");
        }else{
            Session::set('mensaje', "No se pudo insertar la tarea");
            Session::set('tipo_mensaje', "danger");
        }
        
        header("Location: /tarea");
    }
    
    function ver(){
        if (isset($this->params['id_tarea'])){
            $id_tarea= $this->params['id_tarea'];
        }else{
            echo "No recibo identificación de tarea";
            exit;
        }
        
        $this->model=new mTarea();
        
        $tarea=$this->model->verTarea($id_tarea);
        
        // Si no encuentro la tarea en la base de datos
        if (empty($tarea)){  
            Session::set('mensaje', "Tarea no encontrada");
            Session::set('tipo_mensaje', "danger");
            header("Location: /tarea");
        }else{
            // controlo que la tarea sea del usuario logeado
            $id_usuario=$tarea['id_usuario'];
            $id_usuario_sesion=Session::get('id_usuario');
            if ($id_usuario<>$id_usuario_sesion){
                Session::set('mensaje', "La tarea no pertenece al usuario logeado");
                Session::set('tipo_mensaje', "danger");
                header("Location: /tarea");
            }
        }
        
        $titulo='Tareas a Hacer';
        $this->addData([
            'page'=>'Ver Tarea',
            'titulo'=>$titulo,
            'tarea'=>$tarea
        ]);
        
        $this->view=new vTareaVer($this->dataView, $this->dataTable);
        $this->view->show();
        
    }
    
    // muestro formulario con tarea a modificar rescatada de base de datos
    function editar(){
        if (isset($this->params['id_tarea'])){
            $id_tarea= $this->params['id_tarea'];
        }else{
            echo "No recibo identificación de tarea";
            exit;
        }
        
        $this->model=new mTarea();
        
        $tarea=$this->model->verTarea($id_tarea);
        
        // Si no encuentro la tarea en la base de datos
        if (empty($tarea)){  
            Session::set('mensaje', "Tarea no encontrada");
            Session::set('tipo_mensaje', "danger");
            header("Location: /tarea");
        }else{
            // controlo que la tarea sea del usuario logeado
            $id_usuario=$tarea['id_usuario'];
            $id_usuario_sesion=Session::get('id_usuario');
            if ($id_usuario<>$id_usuario_sesion){
                Session::set('mensaje', "La tarea no pertenece al usuario logeado");
                Session::set('tipo_mensaje', "danger");
                header("Location: /tarea");
            }
            $estado=$tarea['estado'];
            if ($estado==1) {
                Session::set('mensaje', "No puede editar una tarea finalizada");
                Session::set('tipo_mensaje', "danger");
                header("Location: /tarea");
            }
        }
        
        $titulo='Editar tarea';
        $this->addData([
            'page'=>'Editar Tarea',
            'titulo'=>$titulo,
            'id_tarea'=>$id_tarea,
            'tarea'=>$tarea
        ]);
        
        $this->view=new vTareaEditar($this->dataView, $this->dataTable);
        $this->view->show();
    }
    
    function modificar(){
        // comprobación de formulario
        // Devuelve false si falla el filtro
        // Devuelve NULL si la variable no está definida
        $titulo=filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $descripcion=filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
        $estado=filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);
        $id_tarea=filter_input(INPUT_POST, 'id_tarea');
        
        //$this->ajax(''=>'');
        
        $id_usuario=Session::get('id_usuario');
        if(is_null($id_usuario)){
            $id_usuario=0;
        }
        $this->model=new mTarea();
        
        $result=$this->model->modificarTarea($id_tarea,$titulo,$descripcion,$estado,$id_usuario);
        
        // Si no hubo error de update, envío mensaje de éxito
        if ($result){ 
            Session::set('mensaje', "Tarea modificada correctamente");
            Session::set('tipo_mensaje', "success");
        }else{
            Session::set('mensaje', "No se pudo modificar la tarea");
            Session::set('tipo_mensaje', "danger");
        }
        
        header("Location: /tarea");        
    }
            
    function cerrarSesion(){
        Session::destroy();  
        header("Location: ".URL."log");
            
    }
    
}
