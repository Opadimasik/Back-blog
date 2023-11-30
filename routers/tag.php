<?php
function route($method, $urlData, $formData) 
{ 
    global $Link;
    $result = $Link->query("SELECT * FROM tag")->fetch_all(MYSQLI_ASSOC);
    if(!is_null($result))
    {
        echo json_encode($result);
    }
    else
    {
        setHTTPStatus("500", "Server can not get list of tag");
    }
}
?>