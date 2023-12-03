<?php
function validateStringNoteLess($string, $lenght) 
{
    if ($string > $lenght) 
    {
        return true;
    }
    else
    {
        return false;
    }
}
function validateGender($gender)
{
    if($gender == "Female" or $gender == "Male")
    {
        return true;
    }
    else
    {
        return false;
    }
}
function validateEmail($email) {
    $filteredEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if ($filteredEmail === false || !preg_match('/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $email)) {
        return false; 
    } else {
        return true; 
    }
}
function validatePassword($password) 
{
    // Проверка наличия хотя бы одной заглавной буквы
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }

    // Проверка наличия хотя бы одной строчной буквы
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }

    // Проверка наличия хотя бы одной цифры
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }

    // Проверка наличия хотя бы одного специального символа
    if (!preg_match('/[!@#$%^&*]/', $password)) {
        return false;
    }
    return true;
}
function isImage($url) 
{
    $headers = get_headers($url);
    if (strpos($headers[0], '200') !== false) 
    {
        $image_info = getimagesize($url);
        // Проверяю, что файл имеет тип "image"
        if ($image_info && strpos($image_info['mime'], 'image') !== false) 
        {
            return true; // Это изображение
        }
    }

    return false; // Не изображение
}