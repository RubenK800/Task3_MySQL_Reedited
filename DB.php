<?php

class DB
{
    private mysqli $_mysqli;

    public static DB $instance;

    public static function getInstance(): DB
    {
        if (!isset(self::$instance)){
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this -> _mysqli = new mysqli("localhost", "Ruben455", "20power20good", "online_shop");
        if ($this->_mysqli->connect_error){
            die($this->_mysqli->connect_error);
        }
    }

    public function query($sql)
    {
        return $this->_mysqli->query($sql);
    }

}