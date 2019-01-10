<?php
namespace A4\App\Controllers;

use A4\Sys\Controller;
use A4\App\Views\vLog;
use A4\App\Models\mLog;
use A4\Sys\Session;

/**
 * Clase que extiende de Controller
 * Permite iniciar sesión para los usuarios registrados
 *
 * @author Silvina Guidobono <silvinaguidobono@gmail.com>
 */

class Log extends Controller{
    function __construct($params) {
        parent::__construct($params);
        
        $this->addData([
            "page"=>'Login',
            'titulo'=>'Iniciar sesión'
        ]);
        $this->model=new mLog();
        $this->view=new vLog($this->dataView, $this->dataTable);
    }
    
    function home(){
        // Si hay un usuario logeado voy a mostrar sus tareas
        if(!is_null(Session::get('id_usuario'))){
            header("Location: ".URL."tarea");
            //header("Location: /A4/tarea"); para producción
            //header("Location: /tarea"); para desarrollo
        }
        $this->view->show();
    }
    
    /**
     * Valida los datos del usuario ingresados por formulario
     * Si el usuario existe en la base de datos, va a mostrar sus tareas
     * Si no existe, muestra el formulario con los errores
     * 
     */
    function log(){
        // Recupero los datos ingresados en el formulario
        //$email=filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $email=filter_input(INPUT_POST, 'email');
        $clave=filter_input(INPUT_POST, 'clave');
        $recordar=filter_input(INPUT_POST, 'recordar');
        // filter_input devuelve false si falla el filtro
        // filter_input devuelve NULL si la variable no está definida

        // Validación del lado servidor de los datos ingresados
        $errores=array(); // inicializo vector de errores
        
        // Valido el correo electrónico
        if(!is_null($email) && !empty($email)){
            if (isset($_COOKIE['email']) && $_COOKIE['email']!=$email) {
                $errores['email']="Debe iniciar sesión y pedir acceso con otro usuario";
            }else{
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $errores['email']="El correo electrónico es inválido";
                }    
            }
            
        }else{
            $errores['email']="Debe ingresar su correo electrónico";
        }
        // valido la contraseña
        if(is_null($clave) || empty($clave)){
            $errores['clave']="Debe ingresar la contraseña";
        }
        // Si los datos ingresados son válidos
        if (count($errores)==0){
            // Busco el correo electrónico y contraseña ingresados en la bd
            // y rescato sus datos (id, nombre, apellidos)
            $row=$this->model->validar_usuario($email,$clave);
            
            // Si existe un correo con esa contraseña en la base de datos
            if (count($row)>0){  
                // si marcó la opción de recordar usuario
                if(!is_null($recordar) && $recordar=="Si"){
                    $this->guardarCookies($email, $clave);
                }
                // guardo los datos del usuario en las variables de sesión
                Session::set('id_usuario', $row[0]['id']);
                Session::set('email', $row[0]['email']);
                Session::set('clave', $row[0]['clave']);
                Session::set('nombre', $row[0]['nombre']);
                Session::set('apellidos', $row[0]['apellidos']);

                // Muestro el listado de sus tareas
                header("Location: ".URL."tarea");
                //header("Location: /A4/tarea");  para producción
                //header("Location: /tarea");  para desarrollo

            }else{
                // No recibo datos de la base de datos
                $errores['clave']="Contraseña o usuario inválidos";
                $this->addData([
                    "page"=>'Login',
                    'titulo'=>'Iniciar sesión',
                    'email'=>$email,
                    'errores'=>$errores
                ]);
                $this->model=new mLog();
                $this->view=new vLog($this->dataView, $this->dataTable);
                $this->view->show();
            }
        }else{
            // mostrar el formulario con los errores
            $this->addData([
                "page"=>'Login',
                'titulo'=>'Iniciar sesión',
                'email'=>$email,
                'errores'=>$errores
            ]);
            $this->model=new mLog();
            $this->view=new vLog($this->dataView, $this->dataTable);
            $this->view->show();
        }
        
    }
    
    /**
     * Guarda correo electrónico y contraseña del usuario en Cookie
     * 
     * @param string $email correo electrónico
     * @param string $clave contraseña
     */
    private function guardarCookies($email,$clave){
        // Cookie Producción con ruta
        setcookie('email',$email, time()+1800,"/A4");
        setcookie('clave', $clave, time()+1800,"/A4");

        // Cookie desarrollo
        //setcookie('email',$email, time()+1800,"/");
        //setcookie('clave', $clave, time()+1800,"/");
    }
    
}
