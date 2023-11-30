<?php
function getAddressSearch()
{
    global $addressLink;
    $parentObjectId = $_GET['parentObjectId'];
    $objectIdList = $addressLink->query("SELECT `objectid` FROM `as_adm_hierarchy` where `parentobjid`='$parentObjectId'")->fetch_all(MYSQLI_ASSOC);
    if(!is_null($objectIdList) && !is_null($parentObjectId))
    {
        $objectIdArray = array_column($objectIdList, 'objectid');
        $objectIdString = implode(',', $objectIdArray);
        $result = $addressLink->query("SELECT * FROM as_addr_obj WHERE objectid IN ($objectIdString) ORDER BY `level`")->fetch_all(MYSQLI_ASSOC);
        if (!is_null($result))
        {
            $query = $_GET['query'];
            if($query != '')
            {
                $filteredResult = array_filter($result, function ($item) use ($query) 
                {
                    return stripos($item['name'], $query) !== false;
                });
                echo (getAddressFormatData($filteredResult));
            }
            else
            {
                echo (getAddressFormatData($result));
            }
        }
        else
        {
            setHTTPStatus("500","Server can not get object with parentObjectId:'$parentObjectId'.");
        }
    }
    else
    {
        setHTTPStatus("400", "This parentObjectId('$parentObjectId') cannot find in DB, try to check it");
    }
     
}