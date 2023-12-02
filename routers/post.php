<?php
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    switch ($method) 
    {
        case "GET":
            if(!is_null($urlData[0]))
            {
                //include_once("postRequest/");
                return;
            }
            else
            {
                //include_once("postRequest/");
                return;
            }
            
        case "POST":
            if(!is_null($urlData[0]))
            {
                //include_once("postRequest/");
                return;
            }
            else
            {
                include_once("postRequest/creating.php");
                createPost($formData);
                return;
            }
        case "DELETE":
            //include_once("postRequest/");
            return;
    }
}
