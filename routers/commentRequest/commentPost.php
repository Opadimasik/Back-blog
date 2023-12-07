<?php
include_once("helpers/commentHelper.php");
function createComment($formData, $postId)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
        $authorIdResult = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
        $authorIdData = $authorIdResult->fetch_assoc();
        if(!is_null($authorIdData))
        {
            if(validateComment($formData))
            {
                $authorId = $authorIdData['accountID'];
                $authorResult = $Link->query("SELECT `fullName` FROM `author` where accountId='$authorId'");
                $authorData = $authorResult->fetch_assoc();
                $author = $authorData['fullName'];
                $content=$formData->content;
                $parentId = $formData->parentId;
                $commentId = bin2hex(random_bytes(16));
                $commentQuery = $Link->query("INSERT INTO comment (
                    `id`,
                    `authorId`,
                    `author`,
                    `content`,
                    `parentId`,
                    `postId`
                ) VALUES (
                    '$commentId',
                    '$authorId',
                    '$author',
                    '$content',
                    '$parentId',
                    '$postId'
                )");
                if(!$commentQuery)
                {
                    setHTTPStatus("400","Problem with creating comment:"."$Link->error");
                    return;
                }
                if (!is_null($parentId))
                {
                    $subCommentQuery = $Link->query("UPDATE comment SET subComments = subComments + 1 WHERE id = '$parentId'");
                    if(!$subCommentQuery)
                    {
                        setHTTPStatus("400","$Link->error");
                        return;
                    }
                }
            }
            else return;
        }
        else
        {
            setHTTPStatus("400","Account id can not be find");
            return;
        }
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}

function validateComment($formData)
{
    
    // if(is_null($postId))
    // {
    //     setHTTPStatus('400','The '.'postId'.' parameter was passed incorrectly');
    //     return false;
    // }
    // if(!checkExistPost($postId))
    // {
    //     setHTTPStatus('404', "Post not find. Check your postId");
    //     return false;
    // }
    $isValidate = true;
    $mesage = array();
    if(!validateStringNoteLess(strlen($formData->content),1))
    {
        $isValidate = false;
        $mesage[]="Content very short, minimum leght 1 or it's not there.";
        // setHTTPStatus("400","Content very short, minimum leght 1 or it's not there.");
        // return false;
    }
    if(!is_null($formData->parentId))
    {
        $isExistComment = true;
        if(!checkExistComment($formData->parentId))
        {
            $isValidate= false;
            $isExistComment = false;
            $mesage[]="There is no such post that was passed to parentId. Try checking the data.";
            // setHTTPStatus("404","There is no such post that was passed to parentId. Try checking the data.");
            // return false;
        }
    }
    if($isValidate == true) return true;
    else 
    {
        setHTTPStatus(!$isExistComment?"404":"400",$mesage);
        return false;
    }
}