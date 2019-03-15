<?php


use Api\Lib\Autoload;
use Api\Lib\Rest;

    ini_set('display_errors','on');
    define('DS',DIRECTORY_SEPARATOR);
    define('ROOT',realpath(dirname(__FILE__)).DS);
    define('LIB',ROOT.'lib'.DS);
    //echo "La REQUEST_URI es: ".$_SERVER['REQUEST_URI']."<br>";
    require_once __DIR__.'/lib/autoload.php';

    $loader=new Autoload();
    $loader->register();
    $loader->addNamespace('Api\Lib','lib');
    $loader->addNamespace('Api\Lib\Controllers','lib/controllers');

    $app=new Rest();
    /*
    // nuevo
    $resp=$app->getReponse_Str();
    $response_obj = Response::create($resp, $_SERVER['HTTP_ACCEPT']);
    echo $response_obj->render();
    */