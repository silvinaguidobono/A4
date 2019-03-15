<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 27/02/19
 * Time: 17:21
 */

namespace Api\Lib;

use Api\Lib\Request;
use Api\Lib\Response;

class Rest
{
    function __construct()
    {
        $request=new Request();
        if(isset($_SERVER['PATH_INFO'])){
            //echo "PATH_INFO: ".$_SERVER['PATH_INFO']."*";
            // elimino la barra del principio y final de la cadena con trim
            $request->url_elements=explode('/',trim($_SERVER['PATH_INFO'],'/'));
        }
        // Método en mayúsculas
        $request->method=strtoupper($_SERVER['REQUEST_METHOD']);
        // obtener array parametros
        switch ($request->method){
            case 'GET':
                $request->parameters=count($request->url_elements)>1?$request->url_elements[1]:$_GET;
                break;
            case 'POST':
                $request->parameters=json_decode(file_get_contents('php://input'),true);
                break;
            case 'PUT':
                $request->parameters=json_decode(file_get_contents('php://input'),true);
                $request->parameters['id']=count($request->url_elements)>1?$request->url_elements[1]:$_GET;
                break;
            CASE 'DELETE':
                $request->parameters=count($request->url_elements)>1?$request->url_elements[1]:$_GET;
                break;
            default:
                header('HTTP/1.1 405 Method not allowed');
                header('Allow: GET, PUT, POST and DELETE');
                break;

        }
        // enrutamiento
        if(!empty($request->url_elements)){
            $controller_name=$request->url_elements[0];
            // Nombre archivo controlador en minúsculas
            $file=LIB.'controllers'.DS.strtolower($controller_name).'.php';
            if(is_readable($file)){
                // Nombre controlador con primera letra en mayúsculas
                $path_controller='\Api\Lib\Controllers\\'.ucfirst($controller_name);
                $controller=new $path_controller;
                $action_name=$request->method;
                $response_str=call_user_func_array(array($controller,$action_name),array($request));
            }else{
                header('HTTP/1.1 404 Not found');
                $response_str='Unknown request: '.$request->url_elements[0];
            }
        }else{
            $response_str='Unknown request';
        }
        // enviar respuesta
        $resp=Response::create($response_str,$_SERVER['HTTP_ACCEPT']);
        echo $resp->render();

    }
}