<?php
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
            elseif(is_null($urlData[1]))
            {
                include_once("postRequest/postsGet.php");
                getPostsData($formData);
                return;
            }
            
        case "POST":
            
            if($urlData[2] == "like" && checkExistPost($urlData[1]))
            {
                include_once("postRequest/postLike.php");
                likePost($formData, $urlData[1]);
                return;
            }
            elseif($urlData[2] == "comment" && checkExistPost($urlData[1]))
            {
                include_once("commentRequest/commentPost.php");
                createComment($formData, $urlData[1]);
                return;
            }
            elseif(is_null($urlData[1]))
            {
                include_once("postRequest/creating.php");
                createPost($formData,null,null);
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
