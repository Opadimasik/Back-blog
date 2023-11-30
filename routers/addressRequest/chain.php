<?php
function getAddressChain() 
{
    global $addressLink;
    $objectGuid = $_GET['objectGuid'];
    $isAddress = $addressLink->query("SELECT * FROM `as_addr_obj` where `objectguid` = '$objectGuid'")->fetch_assoc();
    if (!is_null($isAddress))
    {
        $objectId = $isAddress["objectid"];
        $addresChain = $addressLink->query("SELECT `path` from `as_adm_hierarchy` where `objectid`='$objectId'")->fetch_assoc();
        if (!is_null($addresChain))
        {
            $chainArray = explode(".", $addresChain['path']);
            $objectIdList = "'" . implode("','", $chainArray) . "'";
            $result = $addressLink->query("SELECT * FROM as_addr_obj WHERE objectid IN ($objectIdList) ORDER BY `level`")->fetch_all(MYSQLI_ASSOC);
            if (!is_null($result))
            {
                echo (getAddressFormatData($result));
            }
            else
            {
                setHTTPStatus("500","Server can not get path for objectId:'$objectId'.");
            }
        }
        else
        {
            setHTTPStatus("500","Server can not find path for objectId:'$objectId'.");
        }

    }
    else
    {
        setHTTPStatus("400","This objectGuid incrorrect or this object not in DB");
    }

}
