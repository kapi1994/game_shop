<?php 
header("Content-type:application/json");
if($_SERVER["REQUEST_METHOD"] == "GET"){
    $limit = $_GET['limit'];
    try {
        require_once '../../config/connection.php';
        include '../functions.php';
        echo json_encode([
            'data' => getAllPublishers($limit),
            'pages' => publisherPagination(),
            'currentPage' => $limit
        ]);
    } catch (PDOException $th) {
        echo json_encode($th->getMessage());
        http_response_code(500);
    }
}else http_response_code(404);