<?php
namespace A4\App\Controllers;

use A4\Sys\Controller;
use A4\App\Views\vTarea;
use A4\App\Views\vTareaNueva;
use A4\App\Views\vTareaVer;
use A4\App\Views\vTareaEditar;
use A4\App\Models\mTarea;
use A4\Sys\Session;

/**
 * Clase que extiende de Controller
 * se utiliza para administrar las tareas del usuario
 *
 * @author Silvina Guidobono <silvinaguidobono@gmail.com>
 */
class Tarea extends Controller{
    function __construct($params) {
        parent::__construct($params);
    }
    /**
     * Lista las tareas de usuario logeado
     * y muestra mensaje con resultado de la acción solicitada
     */
    function home(){
        // Recupera id del usuario logueado para buscar sus tareas
        $id_usuario=Session::get('id_usuario');
        // Recupera nombre y apellido del usuario para mostar en la vista
        $nombre=Session::get('nombre');
        $apellidos=Session::get('apellidos');
        // Recupera el mensaje a mostrar y su tipo
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
        // Borra el mensaje una vez mostrado
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
        $this->model=new mTarea();
        // Busca todas las tareas del usuario en la base de datos
        $tareas= $this->model->listarTareas($id_usuario);
        // Guarda cantidad de tareas del usuario
        $cant_tareas= count($tareas);
        // Construye titulo para enviar a la vista
        $titulo="Tareas del usuario: ".$nombre." ".$apellidos;
        // Guarda los datos en dataTable para mostrar en la vista
        $this->addData([
            'page'=>'Tareas',
            'titulo'=>$titulo,
            'tareas'=>$tareas,
            'cantTareas'=>$cant_tareas,
            'mensaje'=>$mensaje,
            'tipo_mensaje'=>$tipo_mensaje
        ]);
        $this->view=new vTarea($this->dataView, $this->dataTable);
        $this->view->show();
    }
    
    /**
     * Permite ingresar los datos de una nueva tarea del usuario
     * 
     */
    function nueva(){
        
        $titulo="Nueva tarea";
        
        $this->addData([
            'page'=>'Nueva Tarea',
            'titulo'=>$titulo
        ]);
        
        $this->view=new vTareaNueva($this->dataView, $this->dataTable);
        $this->view->show();
    }
    
    /**
    * Valida los datos ingresados por el formulario de Nueva tarea
    * Inserta el registro en la tabla de tareas para el usuario logueado
    * Guarda mensaje con resultado de inserción en variables de sesión
    * Va a la página de tareas donde muestra el mensaje de éxito o error
    * 
    */
    function agregar(){
        // Recupero los datos ingresados por el formulario
        $titulo=filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $descripcion=filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
        // filter_input devuelve false si falla el filtro
        // filter_input devuelve NULL si la variable no está definida
        
        // Validaciones del lado servidor de los datos ingresados
        $errores=array(); // inicializo vector de errores
        // Valido el titulo de la tarea
        if(!is_null($titulo) && !empty($titulo) && $titulo!=FALSE){
            //$titulo= htmlspecialchars($_POST['titulo']);
            $long_titulo=strlen($titulo);
            if($long_titulo < 5){
                $errores['titulo']="La longitud del titulo debe ser mayor o igual a 5";
            }
            if($long_titulo > 40){
                $errores['titulo']="La longitud del titulo debe ser menor o igual a 40";
            }
        }else{
            $errores['titulo']="Debe ingresar el titulo de la tarea";
        }
        // Valido la descripción de la tarea
        if(!is_null($descripcion) && !empty($descripcion) && $descripcion!=FALSE){
            //$descripcion= htmlspecialchars($_POST['descripcion']);
            if(strlen($descripcion) < 5){
                $errores['descripcion']="La longitud de la descripción debe ser mayor o igual a 5";
            }
        }else{
            $errores['descripcion']="Debe ingresar la descripción de la tarea";
        }
        // Si no hay errores de validación
        if (count($errores)==0){
            // Recupero el id del usuario logueado
            $id_usuario=Session::get('id_usuario');
            if(is_null($id_usuario)){
                $id_usuario=0;
            }
            $this->model=new mTarea();
            // inserto la tarea en la base de datos
            $result=$this->model->agregarTarea($titulo,$descripcion,$id_usuario);
            // Si no hubo error de inserción, envío mensaje de éxito
            if ($result){ 
                Session::set('mensaje', "Tarea insertada correctamente");
                Session::set('tipo_mensaje', "success");
            }else{
                Session::set('mensaje', "No se pudo insertar la tarea");
                Session::set('tipo_mensaje', "danger");
            }
            header("Location: ".URL."tarea");
        }else{
            // mostrar el formulario con los errores
            $this->addData([
                    'page'=>'Nueva tarea',
                    'titulo'=>'Nueva tarea',
                    'titulo_tarea'=>$titulo,
                    'descripcion'=>$descripcion,
                    'errores'=>$errores
            ]);
            $this->view=new vTareaNueva($this->dataView, $this->dataTable);
            $this->view->show();
        }
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
