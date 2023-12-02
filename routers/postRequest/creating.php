<?php
function createPost($formData)
{
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
        if(!validateStringNoteLess(strlen($formData->title),5))
        {
            setHTTPStatus("400","Title very short, minimum leght 5");
            return;
        }
        if(!validateStringNoteLess(strlen($formData->description),5))
        {
            setHTTPStatus("400","Description very short, minimum leght 5");
            return;
        }
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}