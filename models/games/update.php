<?php
header("Content-type:application/json");
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $id  = $_POST['game_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $trailer = isset($_POST['trailer']) ? $_POST['trailer'] : "";
    $genres = explode(',', $_POST['genres']);
    $publisher = $_POST['publisher'];
    $pegi_rating = $_POST['pegi_rating'];
    $published_at = $_POST['published_at'];


    include '../functions.php';
    include '../validations.php';
    


    $gameValidation = gameFormValidation($name, $description, $publisher,$pegi_rating, $published_at,  $genres, $trailer);
    if(count($gameValidation) > 0){
        printErrors($gameValidation);
    }else{
        try {
            require_once '../../config/connection.php';
            $checkGameName = $checkGameName($name);
            if($checkGameName && $checkGameName->name === $name && $checkGameName->id !== (int)$id){
                echo json_encode("Game with that name allready exists");
                http_response_code(409);
            }else {
               updateGame($id, $name, $description, $pegi_rating, $published_at, $publisher, $genres, $trailer);
               echo json_encode($getGameFullRow($id));
            }
           
        } catch (PDOException $th) {
            echo json_encode($th->getMessage());
            http_response_code(500);
        }
    }
}else http_response_code(404);