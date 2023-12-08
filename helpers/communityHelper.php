<?php
function getCommunityName($communityId)
{
    global $Link;
    $result = $Link->query("SELECT `name` FROM `community` where `id`='$communityId'");
    if ($result -> num_rows > 0) 
    {
        $name = $result -> fetch_assoc()["name"];
        return $name;
    }
    else 
    {
        //setHTTPStatus("404");
        return null;
    }
    
}