<?php

namespace A4\App\Models;

use A4\Sys\Model;

/**
 * Clase perteneciente al Modelo
 * Contiene método para validar el usuario logeado contra la base de datos
 *
 * @author Silvina Guidobono <silvinaguidobono@gmail.com>
 */
class mLog extends Model{
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Valida el usuario y contraseña contra la base de datos
     * 
     * @param string $email correo electrónico ingresado en el formulario
     * @param string $clave contraseña ingresada en el formulario
     * @return array $row con datos del usuario rescatados de la base de datos
     * @return array $row vacío si no encuentra el registro en la tabla
     */
    function validar_usuario($email,$clave){
        // Busco usuario y contraseña en la base de datos 
        $sql="SELECT id,clave,nombre,apellidos FROM usuarios WHERE email=:email";
        // preparo la consulta
        $this->query($sql);
        // indico los datos a reemplazar con su tipo
        $this->bind(":email",$email);
        // ejecuto la consulta
        $result= $this->execute();
        // capturo los resultados y los guardo en un array
        $row=array();
        if($result){
            $datos_usuario= $this->singleSet();
            // verifico si la clave ingresada es igual a la clave de bd
            $iguales= password_verify($clave, $datos_usuario['clave']);
            if ($iguales){
            // genera un array con un elemento que es el registro de la tabla 
            // que cumple la condición, en formato array asociativo
                $row[]=array(
                    'id'=>$datos_usuario['id'],
                    'email'=>$email,
                    'clave'=>$datos_usuario['clave'], 
                    'nombre'=>$datos_usuario['nombre'],
                    'apellidos'=>$datos_usuario['apellidos']
                );    
            }
        }
        return $row;
    }    
}
