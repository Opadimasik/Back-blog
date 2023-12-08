<?php
function getPostsCommunity($communityId)
{
    global $Link;
    $token = getBearerToken();
    $page = trim(getParams("page"));
    $size = trim(getParams("size"));
    $sorting = trim(getParams("sorting"));
    $tags = getParamsForRepetition("tags");
    
    if (!is_null($token))
    {
        if (isTokenValid($token)) 
        {
            if(!validateParams($tags,$sorting,$page,$size,$communityId))
            {
                return;
            }
            $accountIdQuery = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
            $accountIdData = $accountIdQuery->fetch_assoc();
            if (!is_null($accountIdData)) 
            {
                $accountId = $accountIdData["accountID"];
                getPosts($communityId,$tags,$sorting,$page,$size,$accountId);
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
        if(!validateParams($tags,$sorting,$page,$size,null))
        {
            return;
        }
        getPosts($communityId,$tags,$sorting,$page,$size,null);
    }
    
}
function validateParams($tags,$sorting,$page,$size,$communityId)
{
    $isValidate = true;
    $mesage = array();
    $isExistTags=checkExistTags($tags);
    if (!is_null($isExistTags)) {
        $mesage[] = $isExistTags[1];
        $isValidate = false;
        //return false;
    }


    if (!empty($sorting) && !in_array($sorting, ["CreateDesc", "CreateAsc", "LikeAsc", "LikeDesc"])) {
        $isValidate = false;
        $mesage[] = "Invalid value for sorting parameter";
        // setHTTPStatus("400", "Invalid value for sorting parameter");
        // return false;
    }

    if ($page < 0 || !preg_match('/[0-9]+$/', $page)) {
        $isValidate = false;
        $mesage[] = "Page must be an integer greater than 0";
        // setHTTPStatus("400", "Page must be an integer greater than 0");
        // return false;
    }

    if ($size < 0 || !preg_match('/[0-9]+$/', $size)) {
        $isValidate = false;
        $mesage[] = "Size must be an integer greater than 0";
        // setHTTPStatus("400", "Size must be an integer greater than 0");
        // return false;
    }
    $isForbidden = true;
    if (!is_null($communityId))
    {
        include_once("routers/communityRequest/getRole.php");
        $roleInCommutity = getUserRole($communityId,true);
        if ($roleInCommutity == null)
        {
            $isValidate = false;
            $isForbidden = false;
            $mesage[] = "The user does not have access to the community";
        }
    }
    if($isValidate == true) return true;
    else 
    {
        if(!is_null($isExistTags))setHTTPStatus("404",$mesage);
        elseif(!$isForbidden)setHTTPStatus("403",$mesage);
        else setHTTPStatus("400",$mesage);
        return false;
    }

}
function getPosts($communityId,$tags,$sorting,$page,$size,$accountId) {
    global $Link;
    // база запроса
    $query = "SELECT * FROM post WHERE communityId = '$communityId'";

    if (!empty($tags)) {
        $query .= " AND id IN (SELECT postId FROM tag_post WHERE tagId IN ('" . implode("','", $tags) . "'))";
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
                $post['hasLike'] = $hasLike;
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