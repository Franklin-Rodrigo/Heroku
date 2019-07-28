<?php
class Connection {
    private static $instance;
    public function getConnection(){
        if (!isset(self::$instance)) {
            $host = "127.0.0.1";
            $bd = "test";
            $usuario = "root";
            $senha = "";
            $port = "";
            
            self::$instance = new PDO('mysql:host=' . $host .";port=" . $port . 
		';dbname=' . $bd, $usuario, $senha, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));            
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }
       
        return self::$instance;
    }
    public function printError($error){
        echo $error[2];
    }
}
?>