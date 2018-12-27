<?php

namespace A4\App\Models;

use A4\Sys\Model;

/**
 * Description of mtarea
 *
 * @author linux
 */

class mTarea extends Model{
    public function __construct() {
        parent::__construct();
    }
    
    // rescato las tareas del usuario logueado
    function listarTareas($id_usuario){
        $sql="SELECT id,titulo,descripcion,estado,fecha_creado,fecha_act FROM tareas "
                . "WHERE id_usuario=$id_usuario ORDER BY fecha_creado DESC";
        //$resultado = mysqli_query($conn, $sql);
        // preparo la consulta
        $this->query($sql);
        // indico los datos a reemplazar con su tipo
        //$this->bind(":email",$email);
        // ejecuto la consulta
        $result= $this->execute();
        
        $array_tareas=array();
        if ($result){
            if($this->rowCount()==0){
                $cant_tareas=0;
            }else{
                $array_tareas=$this->resultSet();
                /*
                $array_tareas=array();
                while($fila = mysqli_fetch_assoc($resultado)){  
                    $array_tareas[]=$fila;
                }
                */
                $cant_tareas=count($array_tareas);
            }
        }else{
            //die($mysqli_error($conn));
            die("Error en listado de tareas");
        }
        return $array_tareas;
    }
    
    function agregarTarea($titulo,$descripcion,$id_usuario){
        // preparo la sentencia para insertar registro en la tabla tareas
        $sql="INSERT INTO tareas(id_usuario,titulo,descripcion,estado,fecha_creado)
                VALUES(:id_usuario,:titulo,:descripcion,:estado,:fecha_creado)";
        // preparo los datos a insertar
        // preparo la inserción
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
        return $result;
        
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
