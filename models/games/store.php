<?php 
header("Content-type:application/json");
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $publisher = $_POST['publisher'];
    $genres = explode(",", $_POST['genres']);
    $trailer_url = $_POST['trailer'];
    $published_at = $_POST['published_at'];
    $pegi_rating = $_POST['pegi_rating'];

    $page = $_POST['link_active'];
    include '../validations.php';
    include '../functions.php';

    $gameFormValidation = gameFormValidation($name, $description, $publisher,$pegi_rating, $published_at, $genres, $trailer_url);
    if(count($gameFormValidation) > 0)
        printErrors($gameFormValidation);
    else{
        try {
            require_once '../../config/connection.php';
            if($checkGameName($name)){
               echo json_encode("Game with that name allready exists");
               http_response_code(409);
            }else{
                insertGame($name, $description, $publisher, $pegi_rating, $published_at, $genres, $trailer_url);
                echo json_encode([
                    'data' => getAllGames($page),
                    'pages' => gamePagination(),
                    'activeLink' => $page, 
                    'message' => "New game has been inserted"
                ]);
                http_response_code(201);
            }
        } catch (PDOException $th) {
            echo json_encode($th->getMessage());
            http_response_code(500);
        }
    }
}else http_response_code(404);