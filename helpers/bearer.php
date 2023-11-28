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
?>