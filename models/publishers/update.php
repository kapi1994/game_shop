<?php
header("Content-type:application/json");
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $publisher_id = $_POST['publisher_id'];
    $name = $_POST['name'];

    include '../functions.php';
    include '../validations.php';

    $publisherValidation = publisherFormValidation($name); 
    if(count($publisherValidation) > 0){
        printErrors($publisherValidation);
    }else{
        try {
            require_once '../../config/connection.php';
            $checkName = $checkPublisherName($name);
            if($checkName && $checkName->name !== $name && $checkName->id !== $publisher_id){
                echo json_encode("Publisher with same name allready exists");
                http_response_code(409);
            }else{
                updatePublisher($name, $publisher_id);
                echo json_encode($getPublisherFullRow($publisher_id));
            }
        } catch (PDOException $th) {
            echo json_encode($th->getMessage());
            http_response_code(500);
        }
    }
}
else http_response_code(404);