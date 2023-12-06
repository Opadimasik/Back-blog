<?php
function modifieComment($formData)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
        $commentId = trim(getParams("id"));
        if(!checkExistComment($commentId))
        {
            setHTTPStatus("404","There is no such comment that was passed to id. Try checking the data.");
            return;
        }
        $authorAccessQuery = $Link->query("SELECT `authorId` FROM `comment`where `authorId`=
        (SELECT `accountID` FROM `token`WHERE value='$token') and id = '$commentId';");
        $authorAccess = $authorAccessQuery->fetch_assoc();
        if (!is_null($authorAccess))
        {
            $content = $formData->content;
            $deleteCommentQuery = $Link->query("UPDATE comment
            SET 
                modifiedDate = NOW(),
                content = '$content'
            WHERE
                id = '$commentId';");
            if (!$deleteCommentQuery)
            {
                setHTTPStatus("400","Can't update comment:".$Link->error);
            }

        }
        else
        {
            setHTTPStatus("403","This profile cannot manage this comment.");
        }
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}