<?php 
header("Content-type:application/json");
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    include '../functions.php';
    include '../validations.php';

    $registerForm = registerFormValidation($first_name, $last_name, $email, $password);
    if(count($registerForm) > 0){
        printErrors($registerForm);
    }else{
        try {
            require_once '../../config/connection.php';
            $checkEmail = $checkMail($email);
            if($checkEmail){
                echo json_encode("Account with this email allready exists");
                http_response_code(409);
            }else{
                storeNewAccount($first_name, $last_name, $email, $password);
                echo json_encode("New account has been created");
                http_response_code(201);
            }
        } catch (PDOException $th) {
            echo json_encode($th->getMessage());
            http_response_code(500);
        }
    }
}
else http_response_code(404);