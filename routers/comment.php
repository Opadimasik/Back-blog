<?php
include_once("helpers/postHelper.php");
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    include_once("helpers/postHelper.php");
    include_once("helpers/commentHelper.php");
    switch ($method) 
    {
        case "GET":
            // include_once("commentRequest/");

            // return;
        case "DELETE":
            include_once("commentRequest/commentDelete.php");
            deleteComment($formData);
            return;
        case "PUT":
            include_once("commentRequest/commentPut.php");
            modifieComment($formData);
            return;
    }
}
