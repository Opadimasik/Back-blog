<?php
function accountRegister($formData)
{  
    global $Link;
    $user = $Link->query("SELECT id from account where email='$formData->email'")->fetch_assoc();
    if(is_null($user))
    {
        if(!validateStringNoteLess(strlen($formData->fullName),1))
        {
            setHTTPStatus("400","FullName very short, minimum leght 1");
            return;
        }
        if(!validateStringNoteLess(strlen($formData->email),1) || !validateEmail($formData->email))
        {
            setHTTPStatus("400","Email very short, minimum leght 1. Or this email not correct");
            return;
        }
        if(!validateStringNoteLess(strlen($formData->password),6) || !validatePassword($formData->password))
        {
            setHTTPStatus("400","Password very short, minimum leght 6 or incorrect. Password must have minimum one lowercase, uppercase and special character");
            return;
        }
        if(!validateGender($formData->gender))
        {
            setHTTPStatus("400","Gender is not correct");
            return;
        }
        $password = hash("sha1", $formData->password);
        $birth = new DateTime($formData->birthday);
        $birthday = $birth->format("Y-m-d H:i:s");
        $userIsertResalt = $Link->query("INSERT INTO `account`(`fullName`, `password`, `email`, `gender`, `phoneNumber`,`birthDate`) VALUES('$formData->fullName','$password','$formData->email','$formData->gender','$formData->phoneNumber','$birthday')");
        if(!$userIsertResalt)
        {
            setHTTPStatus("400","$Link->error");
        }
        else
        {
            include_once("login.php");
            accountLogin($formData);
        }
        
    }
    else
    {
        setHTTPStatus("400", "User already exists!");
    }
}
