<?php
function accountRegistr($formData)
{
    global $Link;
    $user = $Link->query("SELECT id from account where email='$formData->email'")->fetch_assoc();
    if(is_null($user))
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
    else
    {
        setHTTPStatus("400", "User already exists!");
    }
}
?>