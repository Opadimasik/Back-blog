<?php
function accountPutProfile($formData)
{
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
                if(in_array($key,$allowedFields))
                {
                    $query .= "`$key`='$rowValue', ";  
                }
                else
                {
                    setHTTPStatus("400", "Cannot change field '$key'");
                }              
            }
            $query = rtrim($query, ', '); // Удаляем последнюю запятую
            $query .= " WHERE id='$accountID'";

            $Link->query($query);
            if ($Link->error != "") 
            {
                setHTTPStatus("400", $Link->error);
            }
        } else 
        {
            setHTTPStatus("401");
        }
    }
    else
    {
        setHTTPStatus("401", "The token has expired.");
    }
}
