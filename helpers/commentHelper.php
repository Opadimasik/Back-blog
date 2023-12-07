<?php
function checkExistComment($commentId)
{
    global $Link;
    $result = $Link->query("SELECT `deleteDate` FROM `comment` where `id`='$commentId'");
    if ($result -> num_rows > 0) 
    {
        return true;
    }
    else return false;
}