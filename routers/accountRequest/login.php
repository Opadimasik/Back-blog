<?php
function accountLogin($formData)
{
    global $Link;
    $password = hash("sha1", $formData->password);
    $user = $Link->query("SELECT id from account where login='$formData->login' AND password = '$password'")->fetch_assoc();
    if(!is_null($user))
    {
        $token = bin2hex(random_bytes(16));
        $userID = $user["id"];
        $currentTimestamp = time();
        // Прибавляем к текущему времени один час (3600 секунд)
        $validTimestamp = $currentTimestamp + 3600;
        $tokenIsertResalt = $Link->query("INSERT INTO token (value,userID,validUntil) VALUES ('$token','$userID','$validTimestamp)");
        if(!$tokenIsertResalt)
        {
            setHTTPStatus("400");
        }
        else
        {
            setHTTPStatus("200");
            echo json_encode(['token'=>$token]);
        }
    }
    else
    {
        setHTTPStatus("400");
    }
}
?>