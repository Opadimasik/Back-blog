<?php
function likePost($formData)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
        $authorIdResult = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
        $authorIdData = $authorIdResult->fetch_assoc();
        if(!is_null($authorIdData))
        {
            $postId = $_POST["postId"];
            echo json_encode($postId);
            // Проверяю, есть ли уже лайк от этого пользователя к этому посту
            $checkQueryResult = $Link->query("SELECT `id` FROM `like_account` WHERE `postId`='$postId' AND `accountId`='$authorIdData'");
            //$checkQuery = $checkQueryResult->fetch_assoc();
            if (!$checkQueryResult) 
            {
                $insertQuery = $Link->query("INSERT INTO likes (post_id, user_id) VALUES ('$postId', '$authorIdData')");
                if (!$insertQuery)
                {
                    // Обновляю значения в таблице post
                    $updatePostQuery = $Link->query("UPDATE post SET likes = likes + 1, hasLike = 1 WHERE id = '$postId'");
                    if (!$updatePostQuery) 
                    {
                        // Обработка ошибки при обновлении значений в таблице post
                        setHTTPStatus('400', "Ошибка при обновлении значений в таблице post: " . $Link->error);
                    }
                } 
                else 
                {
                    echo setHTTPStatus('400',"Ошибка при добавлении лайка: " . $Link->error);
                }
            }
        } 
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}