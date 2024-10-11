<?php 
header("Content-type:application/json");
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $name = $_POST['name'];

    include '../validations.php';
    include '../functions.php';

    $publisherValidation = publisherFormValidation($name);
    if(count($publisherValidation) > 0){
        printErrors($publisherValidation);
    }else{
        try {
            require_once '../../config/connection.php';
            $checkPublisherName = $checkPublisherName($name);
            if($checkPublisherName){
                echo json_Encode("Publisher with same name allready exists");
                http_response_code(422);
            }else{
                insertNewPublisher($name);
                echo json_encode([
                    'data' => getAllPublishers(),
                    'message' => 'New publisher has been created'
                ]);
                http_response_code(201);
            }
        } catch (PDOException $th) {
            echo json_encode($th->getMessage());
            http_response_code(500);
        }
    }
}