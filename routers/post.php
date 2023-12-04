<?php
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    switch ($method) 
    {
        case "GET":
            if(!is_null($urlData[0]))
            {
                // include_once("postRequest/");
                // return;
            }
            else
            {
                // include_once("postRequest/");
                // return;
            }
            
        case "POST":
            // echo "svffs";
            // include_once("postRequest/postLike.php");
            // likePost($formData);
            if($urlData[0] =="")
            {
                
            }
            else
            {
                // include_once("postRequest/");
                // return;
            }
        case "DELETE":
            // include_once("postRequest/");
            // return;
    }
}
