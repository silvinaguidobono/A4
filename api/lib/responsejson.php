<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 27/02/19
 * Time: 18:46
 */

namespace Api\Lib;


class Responsejson
{
    protected $data;
    public function __construct($data)
    {
        $this->data=$data;
        return $this;
    }
    public function render(){
        //header("Access-Control-Allow-Origin: *");
        //header("Access-Control-Allow-Methods: *");
        header('Content-Type:application/json');
        return json_encode($this->data);
    }
}