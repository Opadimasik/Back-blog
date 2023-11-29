<?php

function accountLogout()
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
        $tokenUntilTime = $Link->query("SELECT `validUntil` FROM `token` WHERE value='$token'");
        if ($tokenUntilTime->num_rows > 0) 
        {
            spoilToken($token);
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