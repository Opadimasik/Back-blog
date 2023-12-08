<?php
function unsubscribeCommunity($communityId)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {

        $authorIdResult = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
        $authorIdData = $authorIdResult->fetch_assoc();
        if (!is_null($authorIdData)) 
        {
            $authorIdData = $authorIdData["accountID"];

            // Проверяем, подписан ли пользователь на данное сообщество
            $checkQueryResult = $Link->query("SELECT `role` FROM `community_role` WHERE `communityId`='$communityId' AND `userId`='$authorIdData'");
            $checkQuery = $checkQueryResult->fetch_assoc();

            // Если пользователь подписан, выполняем отписку
            if (!is_null($checkQuery)) 
            {
                $deleteQuery = $Link->query("DELETE FROM `community_role` WHERE `communityId`='$communityId' AND `userId`='$authorIdData'");
                if ($deleteQuery) 
                {
                    // Обновляем количество подписчиков в таблице community
                    $updateCommunityQuery = $Link->query("UPDATE community SET subscribersCount = subscribersCount - 1 WHERE id = '$communityId'");
                    if (!$updateCommunityQuery) 
                    {
                        setHTTPStatus('400', "Error when updating values in the community table: " . $Link->error);
                    }
                } 
                else 
                {
                    setHTTPStatus('400', "Error when deleting a subscription: " . $Link->error);
                }
            } 
            else 
            {
                setHTTPStatus("400", "Subscription does not exist");
            }
        }
    } 
    else 
    {
        // Невалидный токен
        setHTTPStatus("401", "The token has expired.");
    }
}