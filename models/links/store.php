<?php
header("Content-type:application/json");
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];

    include '../validations.php';
    include '../functions.php';

    $linkValidation = linkFormValidation($name);
    if(count($linkValidation) > 0){
        printErrors($linkValidation);
    }else{
        try {
            require_once '../../config/connection.php';
            if($checkLink($name)){
                echo json_encode("Link with that name allready exists");
                http_response_code(409);
            }else{
                insertNewLink($name);
                echo json_encode([
                    'links' => $getAllLinks,
                    'message' => 'New link has been created'
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