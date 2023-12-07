<?php
function checkExistPost($postId)
{
    global $Link;
    $result = $Link->query("SELECT `title` FROM `post` where `id`='$postId'");
    if ($result->num_rows > 0) 
    {
        return true;
    }
    else
    {
        //setHTTPStatus("404","This post was not found or does not exist");
        return false;
    } 
}
function checkExistTags($tags)
{
    global $Link;
    $tagsString = implode("', '", $tags);
    $result = $Link->query("SELECT id FROM tag WHERE id IN ('$tagsString')");
    if (!$result) 
    {
        //setHTTPStatus('500', $Link->error);
        return ["500","$Link->error"];
    }
    $existingTags = [];
    while ($row = $result->fetch_assoc()) 
    {
        $existingTags[] = $row['id'];
    }
    $missingTags = array_diff($tags, $existingTags);
    if (!empty($missingTags)) 
    {
        $missingTagsString = implode(', ', $missingTags);
        //setHTTPStatus('404', "Tag(s) not exist: $missingTagsString");
        return ['404',"Tag(s) not exist: $missingTagsString"];
    }
    return null;
}