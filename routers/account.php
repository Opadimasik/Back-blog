<?php
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    switch ($method) 
    {
        case "GET":
            break;
        case "POST":
            switch($urlData[1])
            {
                case "register":
                    break;
                case "login":
                    break;
                case"logout":
                    break;
            }
        case"PUT":
            break;

    }
}
?>