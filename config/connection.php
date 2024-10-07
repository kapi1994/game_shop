<?php 
include 'config.php';
try {
    $connection = new PDO("mysql-host=".SQLHOST.';dbname='.DBNAME, USERNAME, PASSWORD);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (PDOException $th) {
    echo json_encode($th->getMessage());
}