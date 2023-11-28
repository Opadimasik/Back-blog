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
?>