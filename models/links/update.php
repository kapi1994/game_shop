<?php 
header("Content-type:application/json");
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $id = $_POST['link_id'];
    $name = $_POST['name'];
    
    include '../validations.php';
    include '../functions.php';

    $linkvalidation = linkFormValidation($name);
    if(count($linkvalidation) > 0){
        printErrors($linkvalidation);
    }else{
        try {
            require_once '../../config/connection.php';
           $checkLinkName = $checkLink($name);
           if($checkLinkName && $checkLinkName->name === $name && $checkLinkName->id !== $id){
                echo json_encode("Link with same title allready exiists");
                http_response_code(409);
           }else{
              updateLink($name, $id);
              echo json_encode($getOneLinkFullRow($id));
           }
        } catch (PDOException $th) {
            echo json_encode($th->getMessage());
            http_response_code(500);
        }
    }
}
else http_response_code(404);