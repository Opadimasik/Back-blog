<?php
include_once("helpers/postHelper.php");
function route($method, $urlData, $formData) 
{ //Обрабатываем запрос.
    global $Link;
    include_once("helpers/communityHelper.php");
    switch ($method) 
    {
        case "GET":
            if($urlData[1] == "my")
            {
                include_once("communityRequest/getUsersCommunity.php");
                getUserCommuninty();
                return;
            }
            elseif($urlData[2] == "post" && checkExistCommunity($urlData[1]))
            {
                
            }
            elseif($urlData[2] == "role" && checkExistCommunity($urlData[1]))
            {
                include_once("communityRequest/getRole.php");
                getUserRole($urlData[1]);
                return;
            }
            elseif(checkExistCommunity($urlData[1]))
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
            if($urlData[2] == "post" && checkExistCommunity($urlData[1]))
            {
                
            }
            elseif($urlData[2] == "subscribe" && checkExistCommunity($urlData[1]))
            {
                
            }
        case"DELETE":
            if($urlData[2] == "unsubscribe" && checkExistCommunity($urlData[1]))
            {
                include_once("communityRequest/unsubscribe.php");
                unsubscribeCommunity($urlData[1]);
                return;
            }
        default:
            setHTTPStatus("404", "If you tried to use the community id, then you were probably mistaken) the server cannot find it or the request is incorrect");
    }
}
