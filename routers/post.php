<?php
include_once("helpers/postHelper.php");
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    include_once("helpers/postHelper.php");
    switch ($method) 
    {
        case "GET":
            if(checkExistPost($urlData[1]))
            {
                include_once("postRequest/getConcrete.php");
                getDataConcretePost($formData, $urlData[1]);
                return;
            }
            else
            {
                include_once("postRequest/postsGet.php");
                getDataConcretePost($formData);
                return;
            }
            
        case "POST":
            
            if($urlData[2] == "like" && checkExistPost($urlData[1]))
            {
                include_once("postRequest/postLike.php");
                likePost($formData, $urlData[1]);
                return;
            }
            elseif($urlData[1] == "comment")
            {
                include_once("commentRequest/commentPost.php");
                createComment($formData);
                return;
            }
            else
            {
                include_once("postRequest/creating.php");
                createComment($formData);
                return;
            }
        case "DELETE":
            if($urlData[2] == "like" && checkExistPost($urlData[1]))
            {
                include_once("postRequest/deleteLike.php");
                likeDelete($urlData[1]);
                return;
            }
        default:
            setHTTPStatus("404","This post was not found or does not exist or you sent a non-core request");
    }
}
