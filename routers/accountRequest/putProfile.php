<?php
function accountPutProfile($formData)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
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
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}

?>