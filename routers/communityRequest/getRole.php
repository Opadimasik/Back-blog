<?php
function getUserRole($communityId,$isCheck=false) 
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
            $id = "$communityId"."\r\n";
            $commutiyRoleUserQuery = $Link->query("
            SELECT MIN(`role`) as role
            FROM community_role
            WHERE `userId`='$accountID' AND `communityId`='$id'
            ")->fetch_assoc();
            if(!is_null($commutiyRoleUserQuery))
            {
                if(!$isCheck)echo json_encode($commutiyRoleUserQuery['role']);
                else return $commutiyRoleUserQuery['role'];
            }
            else
            {
                return !$isCheck ?setHTTPStatus("404","User roles were not found, the user is probably not a subscriber or administrator of this community"):["404","User roles were not found, the user is probably not a subscriber or administrator of this community"];
            }
        } 
        else 
        {
            return !$isCheck?setHTTPStatus("401","The account cannot be found, try logging in again."):["401","The account cannot be found, try logging in again."];
        }
    }
    else
    {
        return !$isCheck ? setHTTPStatus("401", "The token has expired.") : ["401", "The token has expired."];
    }
}
