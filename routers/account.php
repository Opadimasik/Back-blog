<?php
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    switch ($method) 
    {
        case "GET":
            include_once("accountRequest/getProfile.php");
            accountGetProfile();
            return;
        case "POST":
            switch($urlData[1])
            {
                case "register":
                    include_once("accountRequest/register.php");
                    accountRegister($formData);
                    return;
                case "login":
                    include_once("accountRequest/login.php");
                    accountLogin($formData);
                    return;
                case"logout":
                    include_once("accountRequest/logout.php");
                    accountLogout();
                    return;
            }
        case"PUT":
            include_once("accountRequest/putProfile.php");
            accountPutProfile($formData);
            return;
    }
}
