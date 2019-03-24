<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 07/03/19
 * Time: 16:54
 */

namespace Api\Lib\Controllers;

use Api\Lib\SPDO;

class Task // implements Methods
{
    protected $gbd;

    function __construct()
    {
        $this->gbd=SPDO::singleton();
    }

    /**
     * Get tasks curl -v -X GET api/task
     * Get task  curl -v -X GET api/task/id
     * @param null $request
     * @return array
     */
    function get($request=null){
        if($_SERVER['REQUEST_METHOD']!='GET'){
            return ['error' => 'Request not valid'];
        }
        // select * from tareas
        else{
            if($request->parameters==null){
                //$sql="SELECT * FROM tareas";
                $sql="SELECT id,id_usuario,titulo,estado,fecha_creado,fecha_act FROM tareas";
                $stmt=$this->gbd->prepare($sql);
                $stmt->execute();
                $rows=$stmt->fetchAll(\PDO::FETCH_ASSOC);
            }else{
                $sql="SELECT id,id_usuario,titulo,estado,fecha_creado,fecha_act FROM tareas WHERE id=:id";
                $stmt=$this->gbd->prepare($sql);
                $id=$request->parameters;
                $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
                $stmt->execute();
                $rows=$stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
            if($rows==null){
                return ['msg'=>'Task not found'];
            }
            return $rows;
        }

    }

    /**
     * Inserts task curl -v -X POST api/task -d '{"field":"value","field2":"value2"}'
     * @param null $request
     * @return array
     */
    function post($request=null){
        if($_SERVER['REQUEST_METHOD']!='POST'){
            return array('error'=>'Request not valid');
        }else{
            if(!empty($request->parameters['id_usuario']) && !empty($request->parameters['titulo']) &&
                !empty($request->parameters['descripcion'])){
                // asigno estado pendiente
                $estado=0;
                // asigno la fecha actual
                $fecha_actual=date('Y-m-d H:i:s');

                $sql="INSERT INTO tareas (id_usuario, titulo, descripcion, estado, fecha_creado) ";
                $sql.="VALUES (:id_usuario, :titulo, :descripcion, :estado, :fecha_creado)";
                $stmt=$this->gbd->prepare($sql);

                $stmt->bindValue(':id_usuario',$request->parameters['id_usuario'],\PDO::PARAM_INT);
                $stmt->bindValue(':titulo',$request->parameters['titulo'],\PDO::PARAM_STR);
                $stmt->bindValue(':descripcion',$request->parameters['descripcion'],\PDO::PARAM_STR);
                $stmt->bindValue(':estado',$estado,\PDO::PARAM_INT);
                $stmt->bindValue(':fecha_creado',$fecha_actual,\PDO::PARAM_STR);

                $result=$stmt->execute();
                //var_dump($stmt->errorInfo());

                //if($stmt->execute()){
                if($result){
                    return ['msg'=>'Task created'];
                }else{
                    return ['msg'=>'Cant create task'];
                }

            }else{
                return ['msg' => 'Parameter missing'];
            }
        }
    }

    /**
     * Delete task curl -v -X DELETE api/task/id
     * @param $request
     * @return array
     */
    function delete($request=null){
        if($_SERVER['REQUEST_METHOD']!='DELETE'){
            return array('error'=>'Request not valid');
        }
        if($request->parameters==null){
            return ['msg'=>'Task not defined'];
        }else{
            $id=$request->parameters;
            $sql="DELETE FROM tareas WHERE id=:id";
            $stmt=$this->gbd->prepare($sql);
            $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
            if($stmt->execute()){
                if($stmt->rowCount()!=0){
                    return ['msg'=>'Task deleted'];
                }else{
                    return ['msg'=>'Cant delete task'];
                }
            }else{
                return ['msg'=>'Cant delete task'];
            }
        }
    }

    /**
     * Updates task curl -v -X PUT api/task/id -d '{"field":"value","field2":"value2"}'
     * @param $request
     * @return array
     */
    function put($request=null){
        if($_SERVER['REQUEST_METHOD']!='PUT'){
            return array('error'=>'Request not valid');
        }else{
            //$id=$request->getId();
            if(empty($request->parameters['id'])){
                return ['msg'=>'Task not defined'];
            }else{
                $id=$request->parameters['id'];
                // Obtengo fecha actual para actualizar fecha de modificación del tarea
                $fecha_actual=date('Y-m-d H:i:s');
                // Verifico si hay campos para actualizar
                $camposUpdate=false;
                foreach ($request->parameters as $field=>$value){
                    if($field!="id"){
                        $camposUpdate=true;
                        // Los campos a actualizar pueden ser titulo, descripción y estado
                        $sql="UPDATE tareas SET $field=:$field,fecha_act=:fecha_act WHERE id=:id";
                        //echo "La sql es: ".$sql;

                        $stmt=$this->gbd->prepare($sql);
                        $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
                        $parameter=":".$field;
                        if($field=="estado"){
                            if ($value=="Pendiente"){
                                $value=0;
                            }elseif($value=="Finalizada"){
                                $value=1;
                            }else{
                                return ['msg'=>'Estado invalido'];
                            }
                            $stmt->bindValue($parameter,$value,\PDO::PARAM_INT);
                        }else{
                            $stmt->bindValue($parameter,$value,\PDO::PARAM_STR);
                        }
                        $stmt->bindValue(':fecha_act',$fecha_actual,\PDO::PARAM_STR);

                        $result=$stmt->execute();
                        if(!$result) {
                            return ['msg'=>'Error updating task'];
                        }else{
                            if($stmt->rowCount()==0){
                                return ['msg'=>'Error updating task'];
                            }
                        }
                    }
                }
                if($camposUpdate){
                    return ['msg'=>'Task updated'];
                }else{
                    return ['msg'=>'No fields to update'];
                }
            }
        }
    }
}