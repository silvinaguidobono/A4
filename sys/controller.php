<?php

namespace A4\Sys;

use A4\Sys\Registry;

/**
 * Description of Controller
 *
 * @author linux
 */
abstract class Controller {
    protected $model;
    protected $view;
    protected $params;
    protected $conf; // object app configuration
    protected $app;
    protected $dataView=array();
    protected $dataTable=array();
    
            
    function __construct($params=null,$dataView=null) {
        $this->params=$params; // es un array
        $this->conf=Registry::getInstance();
        // acces to app data config
        $this->app=(array) $this->conf->app;
        $this->dataView=$dataView;
        $this->addData($this->app);
        
    }
    
    protected function addData($array){
        if(is_array($array)){
            if($this->is_single($array)){
                $this->dataView=array_merge((array)$this->dataView,$array);
            }else{
                $this->dataTable=$array;
            }
            
        }
    }
    /*
     * determines if is multilevel array or not
     * @return boolean
     */
    protected function is_single($data){
        foreach ($data as $value){
            //if(is_array($data)){
            if(is_array($value)){
                return false;
            }
        }
        return true;
    }
    
    protected function ajax($output){
        if(is_array($output)){
            echo json_encode($output);
        }
    }
}
