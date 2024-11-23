<?php 
header("Content-type:application/json");
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $image = $_FILES['cover'];
    $game_id =$_POST['game_id'];
    $platform = $_POST['platforms'];
    $edition = $_POST['edition'];
    $price = $_POST['price'];
   
    include '../validations.php';
    include '../functions.php';

    $gameEditionValidation = gameEditionFormValidation($platform, $price, $edition, $image);
    if(count($gameEditionValidation) > 0){
        printErrors($gameEditionValidation);
    }else{
      try {
        require_once '../../config/connection.php';
        if($checkGameEditionPlatform($game_id, $platform, $edition)){
            echo json_encode("Game with this edition on this platform allready exists");
            http_response_code(409);
        }else{
            $image_name = uploadImage($image);
            insertGameEdition($game_id, $platform, $edition, $image_name, $price);
            echo json_encode([
                'data' => getAllEditions($game_id),
                'message' => 'New edition has been inserted'
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