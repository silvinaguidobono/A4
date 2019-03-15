<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 27/02/19
 * Time: 18:40
 */

namespace Api\Lib;

use Api\Lib\Responsejson;

class Response
{
    public static function create($data,$format){
        switch ($format){
            case 'application/json':
            default:
                $obj=new Responsejson($data);
                //$obj=new ResponseJson($data);
                break;

        }
        return $obj;
    }

}