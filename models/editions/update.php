<?php
header("Content-type:application/json");
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $game_id = $_POST['game_id'];
    $id = $_POST['edition_id'];
    $platform = $_POST['platforms'];
    $image = $_FILES['cover']['size'] > 0 ? $_FILES['cover'] : "";
    $edition = $_POST['edition'];
    $price = $_POST['price'];
    $old_img_cover = $_POST['old_img_cover'];

    include '../functions.php';
    include '../validations.php';

    $game_edition_validation = gameEditionFormValidation($platform, $price, $edition, $image);
    if(count($game_edition_validation) > 0){
        var_dump($game_edition_validation);
    }else{
        try {
            require_once '../../config/connection.php';
            $checkGameEditionPlatform = $checkGameEditionPlatform($game_id, $platform, $edition);
            if($checkGameEditionPlatform && $checkGameEditionPlatform->platform_id === $platform 
            && $checkGameEditionPlatform->game_id === $game_id && $checkGameEditionPlatform->edition_id === $edition
            && $checkGameEditionPlatform->id !== $id){
                echo json_encode("Game with that edition and on the that platfrom allready exists");
                http_response_code(409);
            }else{
               $image_name = "";
               if($image!=""){
                    $image_name =  uploadImage($image);
                    removeOldImage($image_name);
               }
               updateGameEdition($id, $platform, $edition, $price, $image_name);
               echo json_encode(getGameEditionFullRow(edition_id: $id));
               
            }
               
        } catch (PDOException $th) {
            echo json_encode($th->getMessage());
            http_response_code(500);
        }
    }
}else http_response_code(404);