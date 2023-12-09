<?php
function likePost($formData, $postId)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {

        $authorIdResult = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
        $authorIdData = $authorIdResult->fetch_assoc();
        if(!is_null($authorIdData))
        {
            
            $authorIdData = $authorIdData["accountID"];
            // Проверяю, есть ли уже лайк от этого пользователя к этому посту
            $checkQueryResult = $Link->query("SELECT `id` FROM `like_account` WHERE `postId`='$postId' AND `accountId`='$authorIdData'");
            $checkQuery = $checkQueryResult->fetch_assoc();
            if (is_null($checkQuery))
            {
                $insertQuery = $Link->query("INSERT INTO like_account (postId, accountId) VALUES ('$postId', '$authorIdData')");
                if ($insertQuery)
                {
                    // Обновляю значения в таблице post
                    $updatePostQuery = $Link->query("UPDATE post SET likes = likes + 1 WHERE id = '$postId'");
                    $authorPost = $Link->query("SELECT authorId FROM post WHERE id = '$postId'")->fetch_assoc()["authorId"];
                    $updateAuthorQuery = $Link->query("UPDATE author SET likes = likes + 1 WHERE accountId = '$authorPost'");
                    if (!$updatePostQuery || !$updateAuthorQuery) 
                    {
                        // Обработка ошибки при обновлении значений в таблице post или author
                        setHTTPStatus('400', "Error when updating values ​​in the post table: " . $Link->error);
                    }
                } 
                else 
                {
                    setHTTPStatus('400',"Error when adding a like:" . $Link->error);
                }
            }
            else
            {
                setHTTPStatus("400","Like already exist");
            }
        } 
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}
