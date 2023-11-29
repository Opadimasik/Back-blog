<?php

function accountLogout()
{
    global $Link;
    $token = getBearerToken();
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

?>