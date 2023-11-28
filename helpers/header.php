<?php
// В этом файле собраны функции для помощи формирования http статусов
function setHTTPStatus($status="200",$message=null) 
{
    switch ($status) 
    {
        default:
        case"200":
            $status = "HTTP/1.0 200 OK";
            break;
    }

    header($status);
    if(!is_null($message))
    {
        echo json_encode(['message'=>$message]);
    }
}

?>