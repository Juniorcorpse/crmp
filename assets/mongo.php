<?php
class mongo {

 private $user = "jpadmin";
 private $pass = "jp4mongo";
 private $host = "localhost";
 private $db = "crm";
 public $Conn;


    function __construct ( ) {
        if(!isset($this->Conn)){
           // $this->Conn = new MongoDB\Driver\Manager("mongodb://{$this->user}:{$this->pass}@{$this->host}");
            $this->Conn = new \MongoDB\Driver\Manager("mongodb://{$this->host}"); 
        }
        $this->Conn;
    }

}