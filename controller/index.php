<?php
// day la file index trong cotroller 
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}
switch ($action) {
    case "add":
        // logic 
        require_once "view/insert.php";
        break;
    case "edit":
        // logic 
        require_once "view/edit.php";
        break;
    case "delete":
        // logic 
        break;
    default:
        // logic 
        break;
}
