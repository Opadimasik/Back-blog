<?php
function checkExistComment($commentId)
{
    global $Link;
    $result = $Link->query("SELECT `content` FROM `comment` where `id`='$commentId' and `isDelete`=0");
    if ($result->num_rows > 0) 
    {
        return true;
    }
    else return false;
}