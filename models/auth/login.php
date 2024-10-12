<?php 
    session_start();
    header("Content-type:application/json");
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $email = $_POST['email'];
        $password = $_POST['password'];

        include '../validations.php';
        include '../functions.php';

        $loginValidation = loginFormValidation($email, $password);
        if(count($loginValidation) > 0)
            printErrors($loginValidation);
        else{
            try {
                require_once '../../config/connection.php';
                $checkEmail  = $checkMail($email);
                if(!$checkEmail){
                    echo json_encode("Account with this email doesn't exists");
                    http_response_code(401);
                }else{
                    $checkAccountByEmailAndPassword = checkAccountByEmailAndPassword($email, $password);
                    if($checkAccountByEmailAndPassword){
                        $_SESSION['user'] = $checkAccountByEmailAndPassword;
                        echo json_encode($checkAccountByEmailAndPassword->role_id);
                    }
                    else{
                        echo json_encode("Account with this credentials doesn't exists!");
                        http_response_code(401);
                    }
                }
            } catch (PDOException $th) {
                echo json_encode($th->getMessage());
                http_response_code(500);
            }
        }

    }else http_response_code(404);