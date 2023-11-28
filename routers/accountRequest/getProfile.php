<?php
function accountGetProfile()
{
    //нужно добавить проверку токена на соответсвие времени
    global $Link;
    $token = getBearerToken();
    $currentTime = date("Y-m-d H:i:s");
    $result = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");

    if ($result->num_rows > 0) 
    {
        $row = $result->fetch_assoc();
        $accountID = $row['accountID'];

        $accountData = $Link->query("SELECT * FROM `account` WHERE id='$accountID'");
        echo json_encode($accountData->fetch_assoc());
    } else 
    {
        setHTTPStatus("401");
    }
}
?>