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
function checkExistTags($tags)
{
    global $Link;
    $tagsString = implode("', '", $tags);
    $result = $Link->query("SELECT id FROM tag WHERE id IN ('$tagsString')");
    if (!$result) 
    {
        setHTTPStatus('500', $Link->error);
        return false;
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
        setHTTPStatus('404', "Tag(s) not exist: $missingTagsString");
        return false;
    }
    return true;
}