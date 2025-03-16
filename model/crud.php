<?php
require_once "config.php";

class crud extends dbconfig
{
    public function __construct()
    {
        parent::__contruct();
    }
    // in du lieu ra man hinh
    public function getData($sql)
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
    public function action($sql)
    {
        $this->conn->exec($sql);
    }
}
