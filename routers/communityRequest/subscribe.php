<?php
function subscribeCommunity($communityId)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
        $authorIdResult = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
        $authorIdData = $authorIdResult->fetch_assoc();
        if (!is_null($authorIdData)) {
            $authorIdData = $authorIdData["accountID"];
            $checkQueryResult = $Link->query("SELECT `role` FROM `community_role` WHERE `communityId`='$communityId' AND `userId`='$authorIdData'");
            $checkQuery = $checkQueryResult->fetch_assoc();

            // Если пользователь еще не подписан, выполняем подписку
            if (is_null($checkQuery)) 
            {
                $insertQuery = $Link->query("INSERT INTO community_role (`role`, `communityId`, `userId`) VALUES ('Subscriber','$communityId', '$authorIdData')");
                if ($insertQuery) 
                {
                    // Обновляем количество подписчиков в таблице community
                    $updateCommunityQuery = $Link->query("UPDATE community SET subscribersCount = subscribersCount + 1 WHERE id = '$communityId'");
                    // Обрабатываем ошибку при обновлении значений в таблице community
                    if (!$updateCommunityQuery) 
                    {
                        setHTTPStatus('400', "Error when updating values in the community table: " . $Link->error);
                    }
                } 
                else 
                {
                    // Обрабатываем ошибку при добавлении подписки
                    setHTTPStatus('400', "Error when adding a subscription: " . $Link->error);
                }
            } 
            else 
            {
                // Пользователь уже подписан на данное сообщество
                setHTTPStatus("400", "Subscription already exists");
            }
        }
    } 
    else 
    {
        // Невалидный токен
        setHTTPStatus("401", "The token has expired.");
    }
}
