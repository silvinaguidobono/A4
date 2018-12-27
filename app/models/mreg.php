<?php

namespace A4\App\Models;

use A4\Sys\Model;

/**
 * Clase que extiende de Model
 * Contiene métodos para insertar y validar usuarios
 *
 * @author Silvina Guidobono <silvinaguidobono@gmail.com>
 */
class mReg extends Model{
    public function __construct() {
        parent::__construct();
    }
    /**
     * Inserta registros en la tabla usuarios
     * 
     * @param string $nombre nombre ingresado en el formulario
     * @param string $apellidos apellidos ingresados en el formulario
     * @param string $email correo electrónico ingresado en el formulario
     * @param clave $clave contraseña encriptada
     * 
     * @return boolean true en caso de éxito, false en caso de error
     */
    function reg($nombre,$apellidos,$email,$clave){
        $sql="INSERT INTO usuarios(email,clave,nombre,apellidos,fecha_creado)
                VALUES(:email,:clave,:nombre,:apellidos,:fecha_creado)";
        $this->query($sql);
        // guardo fecha actual en variable para campo fecha_creado
        $fecha_actual=date('Y-m-d H:i:s');
        // ligamos parametros mysqli con variables php
        $this->bind(":email",$email);
        $this->bind(":clave",$clave);
        $this->bind(":nombre",$nombre);
        $this->bind(":apellidos",$apellidos);
        $this->bind(":fecha_creado",$fecha_actual);
        // ejecutar sentencia
        $result= $this->execute();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Verifica si el correo electrónico ya existe en la base de datos
     * 
     * @param string $email correo electrónico a verificar
     * @return booleano true si el correo existe en la base de datos
     */
    function validate_email($email){
        try{
            $sql="SELECT id FROM usuarios WHERE email=:email";
            // preparo la consulta
            $this->query($sql);
            // indico los datos a reemplazar con su tipo
            $this->bind(":email",$email);
            // ejecuto la consulta
            $result= $this->execute();
            if($result){
                $res= $this->rowCount();
            }else{
                echo "Error al validar email en base de datos";
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        if($res==1){
            return true;
        }else{
            return false;
        }
       
    }
    
}
