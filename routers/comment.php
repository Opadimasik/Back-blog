<?php
include_once("helpers/postHelper.php");
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    include_once("helpers/commentHelper.php");
    switch ($method) 
    {
        case "GET":
            if($urlData[1] == "tree")
            include_once("commentRequest/commentTree.php");
            getCommentTree();
            return;
        case "DELETE":
            // include_once("commentRequest/");

            // return;
        case "PUT":
            // include_once("commentRequest/");

            // return;
    }
}
