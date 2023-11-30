<?php
function route($method, $urlData, $formData)
{
    include_once("helpers/addressHelper.php");
    global $addressLink;
    $addressLink = mysqli_connect("webPHP-Hits-backend-php-project-2","dbLab2","0000","address");
    if($method == "GET")
    {
        switch($urlData[1])
        {
            case "chain":
                include_once("addressRequest/chain.php");
                getAddressChain();
                return;
            case "search":
                include_once("addressRequest/search.php");
                getAddressSearch();
                return;
        }
    }
}
