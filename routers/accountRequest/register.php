<?php
function accountRegister($formData)
{
    global $Link;
    $flag = true;
    $user = $Link->query("SELECT id from account where email='$formData->email'")->fetch_assoc();
    if(is_null($user))
    {
        // if(!validateStringNoteLess($formData->fullname,1))
        // {
        //     $flag = false;
        //     setHTTPStatus("400","FullName very short, minimum leght 1");
        //     return;
        // }
        // if(!validateStringNoteLess($formData->email,1) || !validateEmail($formData->email))
        // {
        //     $flag = false;
        //     setHTTPStatus("400","Email very short, minimum leght 1. Or this email not correct");
        //     return;
        // }
        // if(!validateStringNoteLess($formData->password,6))
        // {
        //     $flag = false;
        //     setHTTPStatus("400","Password very short, minimum leght 6");
        //     return;
        // }
        // if(!validateGender($formData->gender))
        // {
        //     $flag = false;
        //     setHTTPStatus("400","Gender is not correct");
        //     return;
        // }
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
        if($flag)
        {
            
        }
        
    }
    else
    {
        setHTTPStatus("400", "User already exists!");
    }
}
?>