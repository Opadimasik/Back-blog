<?php
function checkExistPost($postId)
{
    global $Link;
    $result = $Link->query("SELECT `title` FROM `post` where `id`='$postId'");
    if ($result->num_rows > 0) 
    {
        return true;
    }
    else return false;
}