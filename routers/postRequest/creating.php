<?php
function createPost($formData)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
        if (validateDataPost($formData))
        {
            $authorIdResult = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
            $authorIdData = $authorIdResult->fetch_assoc();
            if(!is_null($authorIdData))
            {
                $authorId = $authorIdData['accountID'];
                $authorResult = $Link->query("SELECT `fullName` FROM `account` where id='$authorId'");
                $authorData = $authorResult->fetch_assoc();
                $guid = bin2hex(random_bytes(16));
                $title = $formData->title;
                $description = $formData->description;
                $image = $formData->image;
                $author = $authorData['fullName'];
                $addressId = $formData->addressId;
                $readingTime = $formData->readingTime;
                $query = "INSERT INTO `post` (`id`, `title`, `description`, `image`, `authorId`, `author`, `addressId`, `readingTime`)
                        VALUES ('$guid', '$title', '$description', '$image', '$authorId', '$author', '$addressId', '$readingTime')";
                $postInsertResult = $Link->query($query);
                if(!$postInsertResult)
                {
                    setHTTPStatus("400","$Link->error");
                }
                else
                {
                    echo"ok";
                }
            }
            else
            {
                setHTTPStatus("400","Account id can not be find");
                return;
            }
            
        }
        
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}
function validateDataPost($formData)
{
    if(!validateStringNoteLess(strlen($formData->title),5))
        {
            setHTTPStatus("400","Title very short, minimum leght 5 or it's not there.");
            return false;
        }
        if(!validateStringNoteLess(strlen($formData->description),5))
        {
            setHTTPStatus("400","Description very short, minimum leght 5");
            return false;
        }
        if (!isImage($formData->image)) 
        {
            setHTTPStatus("400","This is not an image or the image is not available.");
            return false;
        }
        return true;
}