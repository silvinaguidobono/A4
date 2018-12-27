<?php

namespace A4\App\Models;

use A4\Sys\Model;

/**
 * Clase perteneciente al Modelo
 * Contiene métodos para listar, insertar, actualizar y borrar tareas de un 
 * usuario
 *
 * @author Silvina Guidobono <silvinaguidobono@gmail.com>
 */

class mTarea extends Model{
    public function __construct() {
        parent::__construct();
    }
    /**
     * Recupera las tareas de un usuario accediendo a la tabla tareas de la base
     * de datos.
     * 
     * @param int $id_usuario id del usuario a listar sus tareas
     * @return array $array_tareas arreglo con tantos elementos como tareas
     * tenga el usuario, cada elemento del arreglo contiene un array asociativo
     * con los campos de la tarea
     * 
     */
    function listarTareas($id_usuario){
        $sql="SELECT id,titulo,descripcion,estado,fecha_creado,fecha_act FROM tareas "
                . "WHERE id_usuario=:id_usuario ORDER BY fecha_creado DESC";
        // preparo la consulta
        $this->query($sql);
        // indico los datos a reemplazar
        $this->bind(":id_usuario",$id_usuario);
        // ejecuto la consulta
        $result= $this->execute();
        
        $array_tareas=array();
        // Si la consulta termina bien, recupero las tareas en un array donde
        // cada elemente contiene un array asociativo de la tarea
        if ($result){
                $array_tareas=$this->resultSet();
        }else{ 
            die("Error en listado de tareas");
        }
        return $array_tareas;
    }
    
    /**
     * Insertar la tarea en la base de datos
     * 
     * @param string $titulo titulo de la tarea
     * @param string $descripcion descripcion de la tarea
     * @param int $id_usuario id del usuario al que agregar la tarea
     * @return boolean true en caso de éxito, false en caso de error
     */
    function agregarTarea($titulo,$descripcion,$id_usuario){
        // preparo la sentencia para insertar registro en la tabla tareas
        $sql="INSERT INTO tareas(id_usuario,titulo,descripcion,estado,fecha_creado)
                VALUES(:id_usuario,:titulo,:descripcion,:estado,:fecha_creado)";
        $this->query($sql);
        // ligamos parametros mysqli con variables php
        $estado=0;
        $fecha_actual=date('Y-m-d H:i:s');
        $this->bind(":id_usuario",$id_usuario);
        $this->bind(":titulo",$titulo);
        $this->bind(":descripcion",$descripcion);
        $this->bind(":estado",$estado);
        $this->bind(":fecha_creado",$fecha_actual);
        // ejecutar sentencia
        $result= $this->execute();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
// rescato la tarea a mostrar
    function verTarea($id_tarea){
        $sql="SELECT id_usuario,titulo,descripcion,estado,fecha_creado,fecha_act FROM tareas WHERE id=:id_tarea";
        // preparo la consulta
        $this->query($sql);
        // ligamos parametros mysqli con variables php
        $this->bind(":id_tarea",$id_tarea);
        // ejecutar sentencia
        $result= $this->execute();
        $datos_tarea=array();
        if ($result){
            $datos_tarea= $this->singleSet();
        }
        return $datos_tarea;
        
    }
    
    function modificarTarea($id_tarea,$titulo,$descripcion,$estado,$id_usuario){
        $fecha_actual=date('Y-m-d H:i:s');
        
        if ($estado=="Pendiente"){
            $estado_act=0;
        }elseif($estado=="Finalizada"){
            $estado_act=1;
        }
        
        // modificar registro en la tabla tareas
        $sql="UPDATE tareas SET titulo=:titulo,descripcion=:descripcion,estado=:estado,fecha_act=:fecha_act "
                . "WHERE id=:id_tarea";
        $this->query($sql);
        // ligamos parametros mysqli con variables php
        $this->bind(":titulo",$titulo);
        $this->bind(":descripcion",$descripcion);
        $this->bind(":estado",$estado_act);
        $this->bind(":fecha_act",$fecha_actual);
        $this->bind(":id_tarea",$id_tarea);
        // ejecutar sentencia
        $result= $this->execute();
        
        return $result;
            
    }
}
