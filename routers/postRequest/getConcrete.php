<?php
function getDataConcretePost($formData)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
        $accountIdResult = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
        $accountIdData = $accountIdResult->fetch_assoc();
        if(!is_null($accountIdData))
        {
            $accountId = $accountIdData["accountID"];
            $postId = getParams("id");
            if(is_null($postId))
            {
                setHTTPStatus('400','The '.'Id'.' parameter was passed incorrectly');
                return;
            }
            if(!checkExistPost($postId))
            {
                setHTTPStatus('404', "Post not find. Check your postId");
                return;
            }
            $dataPostResult = $Link->query("SELECT * FROM post where `id`='$postId'");
            $dataPost = $dataPostResult->fetch_array(MYSQLI_ASSOC);
            if(!is_null($dataPost))
            {
                $tagPostResult = $Link->query("SELECT * FROM `tag` where `id` in
                (SELECT `tagId` FROM tag_post where `postId`='$postId')");
                $tagsPost = $tagPostResult->fetch_all(MYSQLI_ASSOC);
                $hasLike=false;
                $hasLikeResult = $Link->query("SELECT `id` FROM `like_account` WHERE `postId`='$postId' AND `accountId`='$accountId'")->fetch_assoc();
                if(!is_null($hasLikeResult)) $hasLike=true;
                
                echo json_encode([
                    "id"=>$dataPost["id"],
                    "createTime"=> $dataPost['createTime'],
                    "title"=> $dataPost["title"],
                    "description"=> $dataPost["description"],
                    "image"=> $dataPost["image"],
                    "authorId"=> $dataPost["authorId"],
                    "author"=> $dataPost["author"],
                    "communityId"=> $dataPost["communityId"],
                    "communityName"=> $dataPost["communityName"],
                    "addressId"=> $dataPost["addressId"],
                    "likes"=> $dataPost["likes"],
                    "commentsCount"=> $dataPost["commentsCount"],
                    "readingTime"=> $dataPost["readingTime"],
                    "tags"=>$tagsPost,
                    'hasLike'=>$hasLike
                ]);
            }
            else setHTTPStatus("400","Sever can not get information about post :".$Link->error);
        }
        else
        {
            setHTTPStatus("400","AccountId not find, please check your Bearer token.");
        }
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}
