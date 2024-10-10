<?php 
header("Content-type:application/json");
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = $_POST['platform_id'];
    $name = $_POST['name'];

    include '../validations.php';
    include '../functions.php';

    $platformValidation = platformFormValidation($name);
    if(count($platformValidation) > 0){
        printErrors($platformValidation);
    }else{
        try {
            require_once '../../config/connection.php';
            $checkPlatformName = $checkPlatformName($name);
            if($checkPlatformName && $checkPlatformName->name === $name && $checkPlatformName->id !== $id){
                echo json_encode("Platform with that name allready exixts");
                http_response_code(409);
            }else{
                updatePlatform($name, $id);
                echo json_encode($getOnePlatformFullRow($id));
            }
            
        } catch (PDOException $th) {
            echo json_encode($th->getMessage());
            http_response_code(500);
        }
    }
}else http_response_code(404);