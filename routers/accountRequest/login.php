<?php
function accountLogin($formData)
{
    global $Link;
    $password = hash("sha1", $formData->password);
    $user = $Link->query("SELECT id from account where email='$formData->email' AND password = '$password'")->fetch_assoc();
    if(!is_null($user))
    {
        $token = bin2hex(random_bytes(16));
        $userID = $user["id"];
        $currentDateTime = date("Y-m-d H:i:s");
        $dateTime = new DateTime($currentDateTime);
        // Прибавление 24 часов
        $dateTime->add(new DateInterval('PT24H'));
        $newDateTime = $dateTime->format("Y-m-d H:i:s");
        $tokenIsertResalt = $Link->query("INSERT INTO `token` (`value`,`accountID`,`validUntil`) VALUES ('$token','$userID','$newDateTime')");
        if(!$tokenIsertResalt)
        {
            setHTTPStatus("400","$Link->error");
        }
        else
        {
            setHTTPStatus("200");
            echo json_encode(['token'=>$token]);
        }
    }
    else
    {
        setHTTPStatus("400","User not exist");
    }
}
