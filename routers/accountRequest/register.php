<?php
function accountRegistr($formData)
{
    global $Link;
    $flag = true;
    $user = $Link->query("SELECT id from account where email='$formData->email'")->fetch_assoc();
    if(is_null($user))
    {
        if(!validateStringNoteLess($formData->fullname))
        {
            $flag = false;
            setHTTPStatus("400","FullName very short, minimum leght 1");
            return;
        }
        if(!validateStringNoteLess($formData->email) || !validateEmail($formData->email))
        {
            $flag = false;
            setHTTPStatus("400","Email very short, minimum leght 1. Or this email not correct");
            return;
        }
        if(!validateStringNoteLess($formData->password,6))
        {
            $flag = false;
            setHTTPStatus("400","Password very short, minimum leght 6");
            return;
        }
        if(!validateGender($formData->gender))
        {
            $flag = false;
            setHTTPStatus("400","Gender is not correct");
            return;
        }
        if($flag)
        {
            $password = hash("sha1", $formData->password);
            echo json_encode($formData);
            $userIsertResalt = $Link->query("INSERT INTO account(fullname,email,password,birthDate,gender,phoneNumber) VALUES('$formData->fullname','$formData->email','$password','$formData->birthDate','$formData->gender','$formData->phoneNumber')")->fetch_assoc();
            if(!$userIsertResalt)
            {
                setHTTPStatus("400");
            }
            else
            {
                //accountLogin($formData);
            }
        }
        
    }
    else
    {
        setHTTPStatus("400", "User already exists!");
    }
}
?>