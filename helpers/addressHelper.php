<?php
function getAddressFormatData($addresList)
{
    $levelMapping = [
        "Region" => "Субъект РФ",
        "AdministrativeArea" => "Административная область",
        "MunicipalArea" => "Муниципальный район",
        "RuralUrbanSettlement" => "Сельско-городская местность",
        "City" => "Город",
        "Locality" => "Населенный пункт",
        "ElementOfPlanningStructure" => "Элемент планировочной структуры",
        "ElementOfRoadNetwork" => "Элемент улично-дорожной сети",
        "Land" => "Земельный участок",
        "Building" => "Здание",
        "Room" => "Комната",
        "RoomInRooms" => "Помещение в комнатах",
        "AutonomousRegionLevel" => "Уровень автономного округа",
        "IntracityLevel" => "Уровень внутригородских территорий",
        "AdditionalTerritoriesLevel" => "Уровень дополнительных территорий",
        "LevelOfObjectsInAdditionalTerritories" => "Уровень объектов в дополнительных территориях",
        "CarPlace" => "Место для автомобиля",
    ];
    $objectLevel=['Region', 'AdministrativeArea', 'MunicipalArea', 'RuralUrbanSettlement', 'City', 'Locality', 'ElementOfPlanningStructure', 'ElementOfRoadNetwork', 'Land', 'Building', 'Room', 'RoomInRooms', 'AutonomousRegionLevel', 'IntracityLevel', 'AdditionalTerritoriesLevel', 'LevelOfObjectsInAdditionalTerritories', 'CarPlace' ];
    $newData = [];

    foreach ($addresList as $item) {
        $objectLevelText = isset($levelMapping[$objectLevel[$item['level']]]) ? $levelMapping[$objectLevel[$item['level']]] : '';
        $newItem = [
            "objectId" => (int)$item['objectid'],
            "objectGuid" => $item['objectguid'],
            "text" => "{$item['typename']} {$item['name']}",
            "objectLevel" => $objectLevel[$item['level']],
            "objectLevelText" => $objectLevelText,
        ];
        $newData[] = $newItem;
    }
    return json_encode($newData);
}