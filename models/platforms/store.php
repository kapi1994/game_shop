<?php 
header("Conent-type:application/json");
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['name'];
    include '../validations.php';
    include '../functions.php';
    $platformValidation = platformFormValidation($name);
    if(count($platformValidation) > 0){
        printErrors($platformValidation);
    }else{
        try {
            require_once '../../config/connection.php';
            if($checkPlatformName($name)){
                echo json_encode("Platform with same name allready exists");
                http_response_code(409);
            }else{
                insertNewPlatform($name);
                echo json_encode([
                    'data' => getAllPlatforms(),
                    'message' => "New platform has been inserted"
                ]);
                http_response_code(201);
            }
        } catch (PDOException $th) {
            echo json_encode($th->getMessage());
            http_response_code(500);
        }
    }
}
else http_response_code(404);