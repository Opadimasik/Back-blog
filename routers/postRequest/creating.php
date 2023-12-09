<?php
function createPost($formData, $communityId, $communityName)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
       
        $authorIdResult = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
        $authorIdData = $authorIdResult->fetch_assoc();
        if(!is_null($authorIdData))
        {
            $authorId = $authorIdData['accountID'];
            $authorResult = $Link->query("SELECT `fullName` FROM `account` where id='$authorId'");
            $authorData = $authorResult->fetch_assoc();
            if (validateDataPost($formData,$formData->tags, $communityId))
            {
                $guid = bin2hex(random_bytes(16));
                $title = $formData->title;
                $description = $formData->description;
                $image = $formData->image;
                $author = $authorData['fullName'];
                $addressId = $formData->addressId;
                $readingTime = $formData->readingTime;
                $query = "INSERT INTO `post` (`id`, `title`, `description`, `image`, `authorId`, `author`, `addressId`, `readingTime`,`communityName`,`communityId`)
                        VALUES ('$guid', '$title', '$description', '$image', '$authorId', '$author', '$addressId', '$readingTime','$communityName','$communityId')";
                $postInsertResult = $Link->query($query);
                if(!$postInsertResult)
                {
                    setHTTPStatus("400","$Link->error");
                }
                else
                {
                    if(addTagsToPost($guid,$formData->tags)) 
                    {
                        if(checkExistAuthor($authorId))
                        {
                            $authorUpdate = $Link->query("UPDATE author SET posts=posts+1 WHERE accountID='$authorId'");
                            if ($authorUpdate)
                            {
                                echo json_encode($guid);
                            }
                            else 
                            {
                                setHTTPStatus('500', $Link->error);
                            }
                            
                        }
                    }
                    
                }
            }
            else return;
        }
        else
        {
            setHTTPStatus("400","Account id can not be find");
            return;
        }
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}
function addTagsToPost($postId,$tags)
{
    global $Link;
    // Подготовленный запрос для вставки значений
    $query = "INSERT INTO `tag_post` (`postId`, `tagId`) VALUES (?, ?)";
    $stmt = $Link->prepare($query);
    if ($stmt) 
    {
        // Привязываем параметры к запросу
        $stmt->bind_param("ss", $postId, $tagId);
        // Итерируемся по массиву тегов и вставляем каждый тег для заданного postId
        foreach ($tags as $tagId) 
        {
            $stmt->execute();
            
        }
        $stmt->close();
        return true;
    } 
    else 
    {
        // Обработка ошибки подготовки запроса
        setHTTPStatus('500', $Link->error);
        return false;
    }
}
function validateDataPost($formData,$tags,$communityId)
{
    $isValidate = true;
    $mesage = array();
    $isExistTags=checkExistTags($tags);
    if (!is_null($isExistTags)) {
        $mesage[] = $isExistTags[1];
        $isValidate = false;
        //return false;
    }
    if(!validateStringNoteLess(strlen($formData->title),5))
        {
            $isValidate = false;
            $mesage[] = "Title very short, minimum leght 5 or it's not there.";
            // setHTTPStatus("400","Title very short, minimum leght 5 or it's not there.");
            // return false;
        }
        if(!validateStringNoteLess(strlen($formData->description),5))
        {
            $isValidate = false;
            $mesage[] = "Description very short, minimum leght 5";
            // setHTTPStatus("400","Description very short, minimum leght 5");
            // return false;
        }
        if (!isImage($formData->image)) 
        {
            $mesage[] = "This is not an image or the image is not available.";
            $isValidate = false;
            // setHTTPStatus("400","This is not an image or the image is not available.");
            // return false;
        }
        $isForbidden = true;
        if (!is_null($communityId))
        {
            include_once("routers/communityRequest/getRole.php");
            $roleInCommutity = getUserRole($communityId,true);
            if ($roleInCommutity != "Administrator")
            {
                $isValidate = false;
                $isForbidden = false;
                if($roleInCommutity == "Subscriber")
                {
                    $mesage[] = "This user is a community subscriber, he cannot add posts to the group";
                }
                else $mesage[] = "Error getting role from community, please check if token is correct";
            }
        }
        if($isValidate == true) return true;
        else 
        {
            if(!is_null($isExistTags))setHTTPStatus("404",$mesage);
            elseif(!$isForbidden)setHTTPStatus("403",$mesage);
            else setHTTPStatus("400",$mesage);
            //setHTTPStatus(!is_null($isExistTags)?"404":!$isForbidden?"403":"400",$mesage);
            return false;
        }
        
}
function checkExistAuthor($authorId)
{
    global $Link;
    $query = $Link->query("SELECT `id` from author where `accountId`='$authorId'")->fetch_assoc();
    if(!is_null($query))
    {
        return true;
    }
    else
    {
        $authorData= $Link->query("SELECT `fullName`, `gender`, `birthDate` FROM `account` WHERE id='$authorId';
        ")->fetch_assoc();
        if (!is_null($authorData))
        {
            $fullName = $authorData["fullName"];
            $gender= $authorData["gender"];
            $birthDate= $authorData["birthDate"];
            $insertAuthorQuery = $Link->query("
            INSERT INTO author(`fullName`, `gender`, `accountID`, `birthDate`) 
            VALUES (
                '$fullName',
                '$gender',
                '$authorId',
                '$birthDate'
            )
            ");
            if(!$insertAuthorQuery)
            {
                setHTTPStatus('500', $Link->error);
                return false;
            }
            return true;
        }
        else
        {
            setHTTPStatus('500', $Link->error);
            return false;
        }
        
    }

}