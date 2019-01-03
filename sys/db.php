<?php
namespace A4\Sys;

use A4\Sys\Registry;
/**
 * Conexión con la base de datos
 *
 * @author Silvina Guidobono <silvinaguidobono@gmail.com>
 */
class DB extends \PDO { //implements DBAdapter
    
    use Singleton;
    
    public function __construct() {
        $config= Registry::getInstance();
        $dbconf=(array)$config->dbconf;
        //$dsn driver:host=
        $dsn=$dbconf['driver'].':host='.$dbconf['dbhost'].';dbname='.$dbconf['dbname'];
        $username=$dbconf['dbuser'];
        $passwd=$dbconf['dbpass'];
        try{
            parent::__construct($dsn, $username, $passwd);    
        } catch (\PDOException $e) {
            echo "Fallo en la conexion";
            // $e->getMessage(); puedo guardar los mensajes en una clase Log
        }
        
    }
    
    function connect(){
        
    }
    function disconnect(){
        
    }
}
