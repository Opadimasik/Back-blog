<?php
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    switch ($method) 
    {
        case "GET":
            include_once("accountRequest/getProfile.php");
            accountGetProfile();
            break;
        case "POST":
            switch($urlData[1])
            {
                case "register":
                    include_once("accountRequest/register.php");
                    accountRegister($formData);
                    break;
                case "login":
                    include_once("accountRequest/login.php");
                    accountLogin($formData);
                    break;
                case"logout":
                    include_once("accountRequest/logout.php");
                    accountLogout();
                    break;
            }
        case"PUT":
            include_once("accountRequest/putProfile.php");
            accountPutProfile($formData);
            break;

    }
}
?>