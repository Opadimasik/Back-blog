<?php
function getBearerToken() {
    $headers = apache_request_headers(); 

    if (isset($headers['Authorization'])) {
        $authorizationHeader = $headers['Authorization'];

        // Проверяем, что Authorization Header начинается с "Bearer"
        if (preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            return $matches[1]; // Возвращаем токен
        }
    }

    return null; // Токен не найден
}

function isTokenValid($token) {
    global $Link; 
    $currentTime = date("Y-m-d H:i:s");
    $result = $Link->query("SELECT `validUntil` FROM `token` WHERE value='$token'");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $validUntil = $row['validUntil'];
        if ($currentTime < $validUntil) 
        {
            // Токен действителен
            return true;
        }
        else 
        {
            spoilToken($token);
            return false;
        }
    } else 
    {
        // Токен не найден в базе данных
        return false;
    }
}

// функиция для порчи токена 
function spoilToken($token) 
{
    global $Link;
    $randomValue = random_int(0, 1000000);
    $Link->query("UPDATE `token` SET `value`=$randomValue WHERE value='$token'");
}
?>