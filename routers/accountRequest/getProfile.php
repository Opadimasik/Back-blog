<?php
function accountGetProfile()
{
    global $Link;
    $token = getBearerToken();
    if(isTokenValid($token))
    {
        $currentTime = date("Y-m-d H:i:s");
        $result = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");

        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $accountID = $row['accountID'];

            $accountData = $Link->query("SELECT * FROM `account` WHERE id='$accountID'");
            echo json_encode($accountData->fetch_assoc());
        } 
        else 
        {
            setHTTPStatus("401");
        }
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}
?>