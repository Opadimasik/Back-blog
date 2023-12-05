<?php
function route($method, $urlData, $formData) 
{
    global $Link;
    $authotQuery = $Link->query("SELECT `fullName`, `gender`, `birthDate`,`createTime`,`posts`,`likes` FROM author");
    
    if($authotQuery)
    {
        $authotQueryArray = $authotQuery->fetch_all(MYSQLI_ASSOC);
        echo json_encode($authotQueryArray);
    }
    else
    {
        setHTTPStatus("500",$Link->error);
    }
}