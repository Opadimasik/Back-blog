<?php
function getDataConcretePost($formData)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
        $page = getParams("page");
        getPosts(["e587312f-4df7-4879-e6e8-08dbea521a91"],"D",null,null,"CrefdateDesc",null,$page,5);
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}
function getPosts($tags, $author, $min, $max, $sorting, $onlyMyCommunities, $page, $size) {
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

    // if ($onlyMyCommunities) {
    //     
    //     $query .= " AND communityId IN (SELECT communityId FROM community_subscription WHERE accountId = '$accountId')";
    // }

    // выполнение запроса
    $result = $Link->query($query);

    if ($result) {
        // вычисление пагинации смещения
        $start = ($page - 1) * $size;

        // счиаю количество 
        $countQuery = "SELECT COUNT(*) AS count FROM ($query) AS filteredPosts";
        $countResult = $Link->query($countQuery);
        $count = $countResult->fetch_assoc()['count'];

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
                "count" => $count,
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