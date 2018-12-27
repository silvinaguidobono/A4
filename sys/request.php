<?php

namespace A4\Sys;

/**
 * Process REQUEST_URI
 *
 * @author Silvina Guidobono <silvinaguidobono@gmail.com>
 */
class Request {
    static private $query=array();
    
    static function exploding(){
        //echo  "La URI es: ".$_SERVER['REQUEST_URI'].'<br><br>';
        
        $array_query= explode('/',$_SERVER['REQUEST_URI']);
        // para quitar el blanco del comienzo
        array_shift($array_query);
        array_shift($array_query); // elimina A4 solo para producción
        // para quitar barra del final 
        if (end($array_query)==""){
            array_pop($array_query);
        }
        self::$query=$array_query;
    }
    
    //static function extract():?string{
    static function extract(){
        return array_shift(self::$query);
    }
    /**
     * extract array parameters from request uri
     * 
     * @return array associative array
     */
    
    //static function getParams():?array{
    static function getParams(){
        // pares impares
        $result=[];
        
        $cant_elem=count(self::$query);
        if ($cant_elem!=0){
            if (($cant_elem%2)!=0){
                echo "error, el número de parámetros es impar";
            }else{ // el número de parámetros es par
                for ($i=1;$i<=$cant_elem/2;$i++){
                    $indice=array_shift(self::$query);
                    $valor=array_shift(self::$query);

                    $result["$indice"]= $valor;
                }
            }
        }
        return $result;
    }
}
