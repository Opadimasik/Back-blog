<?php
function accountGetProfile()
{
    global $Link;
    $token = getBearerToken();
    $result = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");

    if ($result->num_rows > 0) 
    {
        $row = $result->fetch_assoc();
        $accountID = $row['accountID'];
        $accountData = $Link->query("SELECT fullName,email,birthDate,gender,phoneNumber,id,created FROM `account` WHERE id='$accountID'");
        echo json_encode($accountData->fetch_assoc());
    } else 
    {
        setHTTPStatus("401");
    }
}
?>