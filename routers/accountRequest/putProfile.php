<?php
function accountPutProfile($formData)
{
    // необходимо добавить проверку на дату токена
    global $Link;
    $token = getBearerToken();
    $currentTime = date("Y-m-d H:i:s");
    
    $result = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");

    if ($result->num_rows > 0) 
    {
        $row = $result->fetch_assoc();
        $accountID = $row['accountID'];

        foreach ($formData as $key => $rowValue)
        {
            $Link->query("UPDATE `account` SET `$key`='$rowValue' WHERE id='$accountID'");
            if ($Link->error != "")
            {
                setHTTPStatus("400", $Link->error);
            }
        }
    } else 
    {
        setHTTPStatus("401");
    }
}

?>