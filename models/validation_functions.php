<?php 
function inputFormValidation(&$errorArray, $errorMessage, $input, $reInput = ""){
    if(str_contains($input, "@"))
        !filter_var($input, FILTER_VALIDATE_EMAIL) ? array_push($errorArray, $errorMessage) : "";
    else 
        !preg_match($reInput, $input) ? array_push($errorArray, $errorMessage) : "";
}

function selectFormValidation(&$errorArray, $errorMessage, $selectInput) {
    if($selectInput === 'default' || $selectInput === '0')
        array_push($errorArray, $errorMessage);
    
}

function checkBoxFormValidation(&$errorArray, $errorMessage, $checkBoxArray){
    if(count($checkBoxArray) === 0){
        array_push($errorArray, $errorMessage);
    }
}

function inputDateValidation(&$errorArray, $errorMessages, $date){
   list($emptyDate, $dateLower) = $errorMessages;
   if($date === ""){
        array_push($errorArray, $emptyDate);
   }else {
      $currentDate = date("Y-m-d");
      $currentTimeStamp = strtotime($currentDate);
      $inputDateTimeStamp = strtotime($date);
      if($inputDateTimeStamp < $currentTimeStamp){
        array_push($errorArray, $dateLower);
      }
   }
}

function inputFileValidation(&$errorArray, $errorMessages, $inputFile){
    list($emptyFile, $invalidFormat, $sizeError)= $errorMessages;
    $file_size = $inputFile['size'];
    $file_type = $inputFile['type'];
    if($file_size === 0){
        array_push($errors, $emptyFile);
    }else{
        $allowedTypes  = ["image/png", "image/jpg", "image/jpeg"];
        if(!in_array($file_type, $allowedTypes)){
            array_push($errorArray, $invalidFormat);
        }else if($file_size > 3 * 1024 * 1024){
            array_push($errorArray, $sizeError);
        }
    }
}