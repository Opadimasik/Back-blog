<?php
function getUserCommuninty()
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
        $result = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $accountID = $row['accountID'];
            $commutiyRoleUserQuery = $Link->query("
                SELECT MAX(role) AS highestRole, userId, communityId
                FROM community_role
                WHERE userId='$accountID'
                GROUP BY communityId
            ")->fetch_all(MYSQLI_ASSOC);
            if(!is_null($commutiyRoleUserQuery))
            {
                echo json_encode($commutiyRoleUserQuery);
            }
            else
            {
                setHTTPStatus("404","User roles were not found, the user is probably not a subscriber or administrator in any of the communities");
            }
        } 
        else 
        {
            setHTTPStatus("401","The account cannot be found, try logging in again.");
        }
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}