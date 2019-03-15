<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 27/02/19
 * Time: 19:02
 */
namespace Api\Lib\Controllers;

use Api\Lib\SPDO;

//class User implements methods
class User

{
    protected $gbd;

    function __construct()
    {
        $this->gbd=SPDO::singleton();
    }

    /**
     * Get users curl -v -X GET api/user
     * Get user  curl -v -X GET api/user/id
     * @param null $request
     * @return array
     */
    function get($request=null){
        if($_SERVER['REQUEST_METHOD']!='GET'){
            return ['error' => 'Request not valid'];
        }
        // select * from users
        else{
            if($request->parameters==null){
                //$sql="SELECT * FROM usuarios";
                $sql="SELECT id,email,nombre,apellidos FROM usuarios";
                $stmt=$this->gbd->prepare($sql);
                $stmt->execute();
                $rows=$stmt->fetchAll(\PDO::FETCH_ASSOC);
            }else{
                $sql="SELECT id,email,nombre,apellidos FROM usuarios WHERE id=:id";
                $stmt=$this->gbd->prepare($sql);
                $id=$request->parameters;
                $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
                $stmt->execute();
                $rows=$stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
            if($rows==null){
                return ['msg'=>'User not found'];
            }
            return $rows;
        }
    }

    /**
     * Inserts user curl -v -X POST api/user -d '{"field":"value","field2":"value2"}'
     * @param null $request
     * @return array
     */
    function post($request=null){
        if($_SERVER['REQUEST_METHOD']!='POST'){
            return array('error'=>'Request not valid');
        }else{
            if(!empty($request->parameters['email'])&&!empty($request->parameters['clave'])&&
                !empty($request->parameters['nombre'])&& !empty($request->parameters['apellidos'])){
                // encripto la clave antes de guardarla en la base de datos
                $clave = $request->parameters['clave'];
                $clave_encriptada= password_hash($clave, PASSWORD_DEFAULT);
                // asigno la fecha actual
                $fecha_actual=date('Y-m-d H:i:s');

                $sql="INSERT INTO usuarios (email, clave, nombre, apellidos, fecha_creado) ";
                $sql.="VALUES (:email, :clave, :nombre, :apellidos, :fecha_creado)";
                $stmt=$this->gbd->prepare($sql);

                $stmt->bindValue(':email',$request->parameters['email'],\PDO::PARAM_STR);
                $stmt->bindValue(':clave',$clave_encriptada,\PDO::PARAM_STR);
                $stmt->bindValue(':nombre',$request->parameters['nombre'],\PDO::PARAM_STR);
                $stmt->bindValue(':apellidos',$request->parameters['apellidos'],\PDO::PARAM_STR);
                $stmt->bindValue(':fecha_creado',$fecha_actual,\PDO::PARAM_STR);

                $result=$stmt->execute();
                //var_dump($stmt->errorInfo());

                //if($stmt->execute()){
                if($result){
                    return ['msg'=>'User created'];
                }else{
                    return ['msg'=>'Cant create user'];
                }

            }else{
                return ['msg' => 'Parameter missing'];
            }
        }
    }

    /**
     * Delete user curl -v -X DELETE api/user/id
     * @param $request
     * @return array
     */
    function delete($request=null){
        if($_SERVER['REQUEST_METHOD']!='DELETE'){
            return array('error'=>'Request not valid');
        }
        if($request->parameters==null){
            return ['msg'=>'User not defined'];
        }else{
            $id=$request->parameters;
            $sql="DELETE FROM usuarios WHERE id=:id";
            $stmt=$this->gbd->prepare($sql);
            $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
            if($stmt->execute()){
                return ['msg'=>'User deleted'];
            }else{
                return ['msg'=>'Cant delete user'];
            }
        }
    }

    /**
     * Updates user curl -v -X PUT api/user/id -d '{"field":"value","field2":"value2"}'
     * @param $request
     * @return array
     */
    function put($request=null){
        if($_SERVER['REQUEST_METHOD']!='PUT'){
            return array('error'=>'Request not valid');
        }else{
            //$id=$request->getId();
            if(empty($request->parameters['id'])){
                return ['msg'=>'User not defined'];
            }else{
                $id=$request->parameters['id'];
                if(!empty($request->parameters['clave'])){
                    // encripto la clave antes de guardarla en la base de datos
                    $clave = $request->parameters['clave'];
                    $clave_encriptada= password_hash($clave, PASSWORD_DEFAULT);
                    $request->parameters['clave']=$clave_encriptada;
                }
                // Obtengo fecha actual para actualizar fecha de modificación del usuario
                $fecha_actual=date('Y-m-d H:i:s');
                // Verifico si hay campos para actualizar
                $camposUpdate=false;
                foreach ($request->parameters as $field=>$value){
                    if($field!="id"){
                        $camposUpdate=true;
                        //$sql="UPDATE usuarios SET ".$field."=:".$field." WHERE id=:id";
                        $sql="UPDATE usuarios SET $field=:$field,fecha_act=:fecha_act WHERE id=:id";
                        echo "La sql es: ".$sql;

                        //$params=array_keys($request->parameters);
                        //$output=$this->gbd->oper($sql,$request,$field,"User updated");
                        //$data=[$username,$email,$passw];

                        $stmt=$this->gbd->prepare($sql);
                        $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
                        $parameter=":".$field;
                        $stmt->bindValue($parameter,$value,\PDO::PARAM_STR);
                        $stmt->bindValue(':fecha_act',$fecha_actual,\PDO::PARAM_STR);

                        $result=$stmt->execute();
                        if(!$result) {
                            return ['msg'=>'Error updating'];
                        }
                    }
                }
                if($camposUpdate){
                    return ['msg'=>'User updated'];
                }else{
                    return ['msg'=>'No hay campos para actualizar'];
                }
            }
        }
    }
}