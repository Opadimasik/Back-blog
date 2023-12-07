<?php
include_once("helpers/postHelper.php");
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    include_once("helpers/commentHelper.php");
    switch ($method) 
    {
        case "GET":
            if($urlData[2] == "tree" && checkExistComment($urlData[1]))
            {
                include_once("commentRequest/commentTree.php");
                getCommentTree($urlData[1]);
                return;
            }
        case "DELETE":
            if(checkExistComment($urlData[1]))
            {
                include_once("commentRequest/commentDelete.php");
                deleteComment($formData, $urlData[1]);
                return;
            }
        case "PUT":
            if(checkExistComment($urlData[1]))
            {
                include_once("commentRequest/commentPut.php");
                modifieComment($formData, $urlData[1]);
                return;
            }
        default:
            setHTTPStatus("404", "The id comment passed in the request parameters is incorrect, or the request itself was composed incorrectly");
    }
}
