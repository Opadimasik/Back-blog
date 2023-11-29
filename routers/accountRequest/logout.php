<?php

function accountLogout()
{
    global $Link;
    $token = getBearerToken();
    $tokenUntilTime = $Link->query("SELECT `validUntil` FROM `token` WHERE value='$token'");

    if ($tokenUntilTime->num_rows > 0) {
        $row = $tokenUntilTime->fetch_assoc();
        $randomValue = random_int(0, 1000000);
        $Link->query("UPDATE `token` SET `value`=$randomValue WHERE value='$token'");
    } else {
        setHTTPStatus("401");
    }
}

?>