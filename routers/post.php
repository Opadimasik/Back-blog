<?php
include_once("helpers/postHelper.php");
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
            
            if($urlData[1] == "like")
            {
                include_once("postRequest/postLike.php");
                likePost($formData);
                return;
            }
            else
            {
                // include_once("postRequest/");
                // return;
            }
        case "DELETE":
            include_once("postRequest/deleteLike.php");
            likeDelete();
            return;
    }
}
