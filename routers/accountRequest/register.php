<?php
function accountRegister($formData)
{  
    global $Link;
    $user = $Link->query("SELECT id from account where email='$formData->email'")->fetch_assoc();
    if(is_null($user))
    {
        if(validateRegister($formData))
        {
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
    }
    else
    {
        setHTTPStatus("400", "User already exists!");
    }
}
function validateRegister($formData)
{
    $isValidate = true;
    $mesage = array();
    if(!validateStringNoteLess(strlen($formData->fullName),1))
        {
            $isValidate = false;
            $mesage[] = "FullName very short, minimum leght 1";
        }
        if(!validateStringNoteLess(strlen($formData->email),1) || !validateEmail($formData->email))
        {
            $isValidate = false;
            $mesage[] = "Email very short, minimum leght 1. Or this email not correct";
        }
        if(!validateStringNoteLess(strlen($formData->password),6) || !validatePassword($formData->password))
        {
            $isValidate = false;
            $mesage[] = "Password very short, minimum leght 6 or incorrect. Password must have minimum one lowercase, uppercase and special character";
        }
        if(!validateGender($formData->gender))
        {
            $isValidate = false;
            $mesage[] = "Gender is not correct";
        }
        if($isValidate == true) return true;
        else 
        {
            setHTTPStatus("400",$mesage);
            return false;
        }
}