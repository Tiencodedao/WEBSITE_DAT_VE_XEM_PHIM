<?php
class dbconfig
{
    private $host = "locahost";
    private $name = "root";
    private $pass = "";
    private $db = "move";
    protected $conn = null;
    public function __contruct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host; dbname=$this->db; charset=utf8", $this->name, $this->pass);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
