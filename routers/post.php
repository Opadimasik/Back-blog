<?php
include_once("helpers/postHelper.php");
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    include_once("helpers/postHelper.php");
    switch ($method) 
    {
        case "GET":
            if(!is_null(getParams("id")))
            {
                include_once("postRequest/getConcrete.php");
                getDataConcretePost($formData);
                return;
            }
            else
            {
                include_once("postRequest/postsGet.php");
                getDataConcretePost($formData);
                return;
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
                include_once("postRequest/creating.php");
                createPost($formData);
                return;
            }
        case "DELETE":
            include_once("postRequest/deleteLike.php");
            likeDelete();
            return;
    }
}
