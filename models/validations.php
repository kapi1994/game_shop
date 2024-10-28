<?php 
include 'validation_functions.php';

function linkFormValidation($name){
    $errors = [];
    $reName = "/^[a-z]{1,}$/";
    inputFormValidation($errors, "Name for the link isn't good", $name, $reName);
    return $errors;
}

function platformFormValidation($name){
    $errors = [];
    $reName = "/^[a-z]{1,}$/";
    inputFormValidation($errors, "Name of the platform ins't ok!", $name, $reName);
    return $errors;
}

function publisherFormValidation($name){
    $errors = [];
    $reName = "/^[a-z]{1,}$/";
    inputFormValidation($errors, "Name of the publisher ins't ok!", $name, $reName);
    return $errors;
}

// auth
function registerFormValidation($first_name, $last_name, $email, $password){
    $errors = [];
    $reFirstLastName = "/\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/";
    $rePassword = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";

    inputFormValidation($errors, "First name isn't valid", $first_name, $reFirstLastName);
    inputFormValidation($errors, "Last name isn't valid", $last_name, $reFirstLastName);
    inputFormValidation($errors, "Email isn't valid", $email);
    inputFormValidation($errors, "Password isn't valid", $password, $rePassword);
    return $errors; 
}

function loginFormValidation($email, $password){
    $errors = [];
    $rePassword = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
    inputFormValidation($errors, "Email isn't valid", $email);
    inputFormValidation($errors, "Password isn't valid", $password, $rePassword);
    return $errors;
}

function gameFormValidation($name, $description, $publisher, $pegi_rating, $published_at, $genres, $trailer_url = ""){
    $errors = [];

    $reName = "/\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/";
    $reDescription = "/\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/";
    $reTrailerUrl = "/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/";

    inputFormValidation($errors, "Name of the game isn't valid", $name, $reName);
    inputFormValidation($errors, "Description for the game isn't good", $description, $reDescription);
    selectFormValidation($errors, "Choose publisher for the game", $publisher);
    selectFormValidation($errors, "Please choose rating for the game", $pegi_rating);
    inputDateValidation($errors, ["Date can't be empty","Picked date can't be lower than empty"], $published_at);
    checkBoxFormValidation($errors, "Choose at least one genre", $genres);
    $trailer_url !== "" ? inputFormValidation($errors, "Traialer url isn't good", $trailer_url, $reTrailerUrl) : "";
    
    return $errors;  
}

function gameEditionFormValidation($platform_id, $price, $edition,  $image = "") {
    $errors = [];
    $rePrice = "/^[\d]{1,5}$/";

    inputFormValidation($errors, "Price isn't valid", $price, $rePrice);
    selectFormValidation($errors, "Choose platform", $platform_id);
    selectFormValidation($errors, "Choose type of edition", $edition);
    inputFileValidation($errors, ["Choose a file", "File is invalid format", "File must be less than 3mb"], $image);
    return $errors;
}