<?php
namespace A4\App\Controllers;

use A4\Sys\Controller;
use A4\App\Views\vReg;
use A4\App\Models\mReg;
use A4\Sys\Session;

/**
 * Clase que extiende de Controller
 * Incorpora nuevos usuarios a la aplicación
 *
 * @author Silvina Guidobono <silvinaguidobono@gmail.com>
 */
class Reg extends Controller {
    
   function __construct($params) {
       parent::__construct($params);
       $this->addData([
            'page'=>'Registro',
            'titulo'=>'Registro de usuarios'
        ]);
        $this->model=new mReg();
        $this->view=new vReg($this->dataView, $this->dataTable);
   }
   
   function home(){
        $this->view->show();
   }
   
   /**
    * Valida los datos ingresados por el formulario de Registrar Usuario
    * Inserta el registro en la tabla de usuarios
    * Guarda mensaje con resultado de inserción en variables de sesión
    * Va a la página Home donde muestra el mensaje de éxito o error
    * 
    */
   function reg(){
        // comprobación de formulario
        // ver si existe ese email
        /* código de clase
        $email=filter_input(INPUT_POST, $_POST['email'], FILTER_VALIDATE_EMAIL);
        $password=filter_input(INPUT_POST, $_POST['password']);
        $password2=filter_input(INPUT_POST, $_POST['password2']);
        $username=filter_input(INPUT_POST, $_POST['username'], FILTER_SANITIZE_STRING);
        */
        // Recupero los datos ingresados por el formulario
        $nombre=filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $apellidos=filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING);
        //$email=filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $email=filter_input(INPUT_POST, 'email');
        $clave=filter_input(INPUT_POST, 'clave');
        // filter_input devuelve false si falla el filtro
        // filter_input devuelve NULL si la variable no está definida
        
        // Validaciones del lado servidor de los datos ingresados
        $errores=array(); // inicializo vector de errores
        
        // Valido el nombre
        if(!is_null($nombre) && !empty($nombre) && $nombre!=FALSE){
            //$nombre= htmlspecialchars($_POST['nombre']);
            if(strlen($nombre) > 50){
                $errores['nombre']="La longitud del nombre debe ser menor a 50";
            }
        }else{
            $errores['nombre']="Debe ingresar el nombre";
        }
        // valido los apellidos
        if(!is_null($apellidos) && !empty($apellidos) && $apellidos!=FALSE){
            //$apellidos= htmlspecialchars($_POST['apellidos']);
            if(strlen($apellidos) > 100){
                $errores['apellidos']="La longitud de apellidos debe ser menor a 100";
            }
        }else{
            $errores['apellidos']="Debe ingresar sus apellidos";
        }
        // valido el correo electrónico
        if(!is_null($email) && !empty($email)){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errores['email']="El correo electrónico es inválido";
            }
        }else{
            $errores['email']="Debe ingresar un correo electrónico";
        }
        // valido la contraseña
        if(!is_null($clave) && !empty($clave)){
            // encripto la clave antes de guardar
            $clave_encriptada= password_hash($clave, PASSWORD_DEFAULT);
        }else{
            $errores['clave']="Debe ingresar la contraseña";
        }
        
        // Si los datos ingresados son válidos        
        if (count($errores)==0){
            // inserto registro en la tabla usuarios
            $result=$this->model->reg($nombre,$apellidos,$email,$clave_encriptada);
            // guardo mensaje con resultado de la inserción en variable de sesión
            if ($result){ 
                Session::set('mensaje', "Usuario registrado");
                Session::set('tipo_mensaje', "success");
            }else{ 
                Session::set('mensaje', "No se pudo insertar el usuario");
                Session::set('tipo_mensaje', "danger");
            }
            header("Location: ".URL."home");
                
            /*
            if($result){
                $this->ajax(['redir'=>'home','msg'=>'OK.Please signin']);
            }else{
                $this->ajax('redir'=>'reg','msg'=>'Review your data');
            }
            */
        }else{
            // mostrar el formulario con los errores
            $this->addData([
                    'page'=>'Registro',
                    'titulo'=>'Registro de usuarios',
                    'nombre'=>$nombre,
                    'apellidos'=>$apellidos,
                    'email'=>$email,
                    'errores'=>$errores
            ]);
            $this->view=new vReg($this->dataView, $this->dataTable);
            $this->view->show();
        }
    }
    
    /*
     * Valida correo electrónico ingresado por formulario
     * Retorna mensaje de error correspondiente
     * 
     */
   function valemail(){
       $email=filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
       if(!$email){
           $this->ajax(['msg'=>'Email inválido']);
       }else{
           $res= $this->model->validate_email($email);
            if($res){
                $this->ajax(['msg'=>'Email en uso']);
            }else{
                $this->ajax(['msg'=>'Email permitido']);
            }
       }
       
   }
    
}
