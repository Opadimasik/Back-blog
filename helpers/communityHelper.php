<?php
function checkExistCommunity($communityId)
{
    global $Link;
    $result = $Link->query("SELECT `name` FROM `community` where `id`='$communityId'");
    if ($result -> num_rows > 0) 
    {
        return true;
    }
    else 
    {
        setHTTPStatus("404");
        return false;
    }
    
}