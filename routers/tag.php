<?php
function route($method, $urlData, $formData) 
{ 
    global $Link;
    $result = $Link->query("SELECT * FROM tag")->fetch_all(MYSQLI_ASSOC);
    echo json_encode($result);
}
?>