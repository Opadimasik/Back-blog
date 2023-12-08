<?php

function getConcreteCommunity($communityId)
{
    global $Link;
    $communityQuery = $Link->query("SELECT * FROM `community` where `id`='$communityId'");
    $community = $communityQuery->fetch_assoc();
    if (!is_null($community))
    {
        $idQeury = $Link->query("SELECT `userId` 
        FROM `community_role` 
        WHERE  `role` = 'Administrator'")->fetch_assoc();
        $idAdmin = $idQeury['userId'];
        $adminsQuery = $Link->query("
        SELECT `fullName`, `email`, `birthDate`, `gender`, `phoneNumber`, `id`, `created`
        FROM `account` 
        WHERE `id` = '$idAdmin'
    ");
        $admins = $adminsQuery->fetch_all(MYSQLI_ASSOC);
        if (!is_null($admins))
        {
            echo json_encode([
                "name"=>$community["name"],
                "description"=>$community["description"],
                "subscribersCount"=>$community["subscribersCount"],
                "id"=>$community["id"],
                "createTime"=>$community["createTime"],
                "isClosed"=>$community["isClosed"],
                "administrators"=>$admins
            ]);
        }
        else
        {
            setHTTPStatus("500","$Link->error");
        }
    }
    else
    {
        setHTTPStatus("404","If you tried to use the community id, then you were probably mistaken) the server cannot find it");
    }
}