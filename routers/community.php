<?php
include_once("helpers/postHelper.php");
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    include_once("helpers/communityHelper.php");
    $communityName = getCommunityName($urlData[1]);
    switch ($method) 
    {
        case "GET":
            if($urlData[1] == "my")
            {
                include_once("communityRequest/getUsersCommunity.php");
                getUserCommuninty();
                return;
            }
            elseif($urlData[2] == "post" && !is_null($communityName))
            {
                include_once("communityRequest/getPostsCommunity.php");
                getPostsCommunity($urlData[1]);
                return;
            }
            elseif($urlData[2] == "role" && !is_null($communityName))
            {
                include_once("communityRequest/getRole.php");
                getUserRole($urlData[1]);
                return;
            }
            elseif(!is_null($communityName))
            {
                include_once("communityRequest/getById.php");
                getConcreteCommunity($urlData[1]);
                return;
            }
            else
            {
                include_once("communityRequest/getList.php");
                getListOfCommunuty();
                return;
            }
        case "POST":
            if($urlData[2] == "post" && !is_null($communityName))
            {
                include_once("postRequest/creating.php");
                createPost($formData,$urlData[1],$communityName);
                return;
            }
            elseif($urlData[2] == "subscribe" && !is_null($communityName))
            {
                include_once("communityRequest/subscribe.php");
                subscribeCommunity($urlData[1]);
                return;
            }
        case"DELETE":
            if($urlData[2] == "unsubscribe" && !is_null($communityName))
            {
                
            }
        default:
            setHTTPStatus("404", "If you tried to use the community id, then you were probably mistaken) the server cannot find it or the request is incorrect");
    }
}
