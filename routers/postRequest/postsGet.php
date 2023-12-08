<?php
function getPostsData($formData)
{
    global $Link;
    $token = getBearerToken();
    $page = trim(getParams("page"));
    $size = trim(getParams("size"));
    $min = trim(getParams("min"));
    $max = trim(getParams("max"));
    $sorting = trim(getParams("sorting"));
    $author = trim(getParams("author"));
    $onlyMyCommunities = trim(getParams("onlyMyCommunities"));
    $tags = getParamsForRepetition("tags");
    if(!validateParams($tags,$author,$min,$max,$sorting,$onlyMyCommunities,$page,$size))
    {
        return;
    }
    if (!is_null($token))
    {
        if (isTokenValid($token)) 
        {
            $accountIdQuery = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
            $accountIdData = $accountIdQuery->fetch_assoc();
            if (!is_null($accountIdData)) 
            {
                $accountId = $accountIdData["accountID"];
                getPosts($tags,$author,$min,$max,$sorting,$onlyMyCommunities,$page,$size,$accountId);
            }
            else
            {
                setHTTPStatus("500","Error getting accountId".$Link->error);
            }
        }
        else
        {
            setHTTPStatus("401", "The token has expired.");
        }
    }
    else
    {
        getPosts($tags,$author,$min,$max,$sorting,$onlyMyCommunities,$page,$size,null);
    }
}
function validateParams($tags, $author, $min, $max, $sorting, $onlyMyCommunities, $page, $size)
{
    if (!checkExistTags($tags)) {
        return false;
    }

    if (!empty($min) && ($min < 0 || !preg_match('/[0-9]+/', $min))) {
        setHTTPStatus("400", "Min must be an integer greater than or equal to 0");
        return false;
    }

    if (!empty($max) && ($max < 0 || !preg_match('/[0-9]+/', $max))) {
        setHTTPStatus("400", "Max must be an integer greater than or equal to 0");
        return false;
    }

    if (!empty($sorting) && !in_array($sorting, ["CreateDesc", "CreateAsc", "LikeAsc", "LikeDesc"])) {
        setHTTPStatus("400", "Invalid value for sorting parameter");
        return false;
    }

    if (!empty($onlyMyCommunities) && !in_array($onlyMyCommunities,["true","false"])) {
        setHTTPStatus("400", "OnlyMyCommunities must be a boolean");
        return false;
    }

    if ($page < 0 || !preg_match('/[0-9]+/', $page)) {
        setHTTPStatus("400", "Page must be an integer greater than 0");
        return false;
    }

    if ($size < 0 || !preg_match('/[0-9]+/', $size)) {
        setHTTPStatus("400", "Size must be an integer greater than 0");
        return false;
    }

    return true;
}
function getPosts($tags, $author, $min, $max, $sorting, $onlyMyCommunities, $page, $size,$accountId) {
    global $Link;

    // база запроса
    $query = "SELECT * FROM post WHERE 1 = 1";

    if (!empty($tags)) {
        $query .= " AND id IN (SELECT postId FROM tag_post WHERE tagId IN ('" . implode("','", $tags) . "'))";
    }

    if (!empty($author)) {
        $query .= " AND author LIKE '%$author%'";
    }

    if (!empty($min)) {
        $query .= " AND readingTime >= $min";
    }

    if (!empty($max)) {
        $query .= " AND readingTime <= $max";
    }

    switch ($sorting) {
        case "CreateDesc":
            $query .= " ORDER BY createTime DESC";
            break;
        case "CreateAsc":
            $query .= " ORDER BY createTime ASC";
            break;
        case "LikeAsc":
            $query .= " ORDER BY likes ASC";
            break;
        case "LikeDesc":
            $query .= " ORDER BY likes DESC";
            break;
        default:
            
    }

    if ($onlyMyCommunities == 'true' && !is_null($accountId)) {
        $query .= " AND `communityId` IN (SELECT `communityId` FROM community_role WHERE `userId` = '$accountId' AND `role` IN ('Subscriber', 'Administrator'))";
    } elseif ($onlyMyCommunities == 'false' && !is_null($accountId)) {
        $query .= " AND (`communityId` IS NULL OR `communityId` NOT IN (SELECT `communityId` FROM community_role WHERE `userId` = '$accountId' AND `role` IN ('Subscriber', 'Administrator')))";
    }
    // выполнение запроса
    $result = $Link->query($query);

    if ($result) {
        // вычисление пагинации смещения
        $start = ($page - 1) * $size;

        // счиаю количество 
        $countQuery = "SELECT COUNT(*) AS count FROM ($query) AS filteredPosts";
        $countResult = $Link->query($countQuery);
        $count = $countResult->fetch_assoc()['count'];
        // вычисление общего количества страниц
        $totalPages = ceil($count / $size);
        // обновление запроса для пагинации
        $query .= " LIMIT $start, $size";

        $result = $Link->query($query);
        if ($result) {
            $posts = $result->fetch_all(MYSQLI_ASSOC);

            // получения информации о постах
            foreach ($posts as &$post) {
                $postId = $post['id'];

                $tagPostResult = $Link->query("SELECT * FROM `tag` WHERE `id` IN (SELECT `tagId` FROM tag_post WHERE `postId`='$postId')");
                $tagsPost = $tagPostResult->fetch_all(MYSQLI_ASSOC);
                $hasLike=false;
                if (!is_null($accountId))
                {
                    $hasLikeResult = $Link->query("SELECT `id` FROM `like_account` WHERE `postId`='$postId' AND `accountId`='$accountId'")->fetch_assoc();
                    if(!is_null($hasLikeResult)) $hasLike=true;
                }
                $commentsQuery = $Link->query("SELECT 
                    id, 
                    createTime, 
                    content, 
                    modifiedDate, 
                    deleteDate, 
                    authorId, 
                    author, 
                    subComments
                FROM comment
                WHERE `postId`='$postId' AND `parentId`=''");
                $commentsResult = $commentsQuery->fetch_all(MYSQLI_ASSOC);

                $post['tags'] = $tagsPost;
                $post['comments'] = $commentsResult;
            }

            $pagination = [
                "size" => $size,
                "count" => "$totalPages",
                "current" => $page
            ];

            echo json_encode(["posts" => $posts, "pagination" => $pagination]);
        } else {
            setHTTPStatus("400", "Error fetching posts: " . $Link->error);
        }
    } else {
        setHTTPStatus("400", "Error fetching posts: " . $Link->error);
    }
}