<?php
function likeDelete()
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {

        $authorIdResult = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
        $authorIdData = $authorIdResult->fetch_assoc();
        if(!is_null($authorIdData))
        {
            $postId = getParams("postId");
            if(is_null($postId))
            {
                setHTTPStatus('400','The '.'postId'.' parameter was passed incorrectly');
                return;
            }
            if(!checkExistPost($postId))
            {
                setHTTPStatus('404', "Post not find. Check your postId");
                return;
            }
            $authorId = $authorIdData["accountID"];
            $checkQueryResult = $Link->query("SELECT `id` FROM `like_account` WHERE `postId`='$postId' AND `accountId`='$authorId'");
            $checkQuery = $checkQueryResult->fetch_assoc();
            if(!is_null($checkQuery))
            {
                $deleteQuery = $Link->query("DELETE FROM `like_account` WHERE `postId`='$postId' AND `accountId`='$authorId'");
                if ($deleteQuery) 
                {
                    $updatePostQuery = $Link->query("UPDATE post SET likes = likes - 1 WHERE id = '$postId'");
                    $updateAuthorQuery = $Link->query("UPDATE author SET likes = likes - 1 WHERE accountId = '$authorId'");
                    if (!$updatePostQuery || !$updateAuthorQuery) 
                    {
                        setHTTPStatus("400","Error when updating values ​​in the post table or author table: ".$Link->error);
                    }
                } 
                else 
                {
                    setHTTPStatus("400","Like for this post:".$postId."NOT FIND, Data base erorr:".$Link->error);
                }
            }
            else
            {
                setHTTPStatus("400","Like already delete or not add else".$Link->error);
            }
            
        } 
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}