<?php
function accountPutProfile($formData)
{
    $mesage = array();
    global $Link;
    $token = getBearerToken();
    if (isTokenValid($token)) 
    {
        $result = $Link->query("SELECT `accountID` FROM `token` WHERE value='$token'");
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $accountID = $row['accountID'];
            $allowedFields = ["email", "fullName", "birthDate", "gender", "phoneNumber"];
            $query = "UPDATE `account` SET ";
            foreach ($formData as $key => $rowValue) 
            {
                if(validateDataForPut($key,$rowValue,$mesage))
                {
                    if(in_array($key,$allowedFields))
                    {
                        $query .= "`$key`='$rowValue', ";  
                    }
                    else
                    {
                        $mesage[] = "Cannot change field '$key'";
                    }
                }              
            }
            $query = rtrim($query, ', '); // Удаляем последнюю запятую
            $query .= " WHERE id='$accountID'";

            $Link->query($query);
            if ($Link->error != "") 
            {
                $mesage[] = $Link->error;
            }
            if(!empty($mesage))
            {
                setHTTPStatus("400",$mesage);
            }
        } 
        else 
        {
            setHTTPStatus("401", "Unauthorized user");
        }
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}

function validateDataForPut($key,$value,&$mesage)
{
    switch ($key) {
        case "email":
            if (!validateStringNoteLess(strlen($value),1) || !validateEmail($value)) 
            {
                $mesage[] = "Email very short, minimum leght 1. Or this email not correct";
                return false;
            }
            break;
        case "fullName":
            if (!validateStringNoteLess(strlen($value), 1)) 
            {
                $mesage[] = "FullName very short, minimum leght 1";
                return false;
            }
            break;
        case "gender":
            if (!validateGender($value)) {
                $mesage[] =  "Invalid gender value";
                return false;
            }
            break;
    }
    return true;
}