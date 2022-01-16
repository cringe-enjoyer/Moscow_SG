<?php
const COLUMNS_NAME = ["NameSummer" => "Название в летний период", "AdmArea" => "Административный округ", "District" => "Район",
    "Street" => "Улица", "House" => "Адрес", "Email" => "Электронная почта", "WebSite" => "Сайт", "HelpPhone" => "Телефон",
    "WorkingHoursSummer" => "График работы", "HasEquipmentRental" => "Возможность проката оборудования",
    "HasTechService" => "Наличие сервиса тех. обслуживания", "HasDressingRoom" => "Наличие раздевалки",
    "HasEatery" => "Наличие точки питания", "HasToilet" => "Наличие туалета", "HasWifi" => "Наличие Wi-Fi",
    "HasCashMachine" => "Наличие банкомата", "HasFirstAidPost" => "Наличие медпункта",
    "HasMusic" => "Наличие звукового сопровождения", "UsagePeriodSummer" => "Период эксплуатации", "DimensionsSummer" => "Размеры",
    "Lighting" => "Освещение", "SurfaceTypeSummer" => "Покрытие", "Seats" => "Количество посадочных мест",
    "Paid" => "Форма посещения", "PaidComments" => "Комментарий к стоимости посещения",
    "DisabilityFriendly" => "Приспособленность для занятий инвалидов", "ServicesSummer" => "Дополнительные услуги"];

$pattern = "/([A-Z a-z]+:)/";

function checkFilter(){
    $query_change = "";
    $filterCount = 0;
    if(isset($_POST['WiFi'])) {
        $query_change .= "NOT HasWifi LIKE 'нет' ";
        $filterCount++;
    }
    if(isset($_POST['disability'])) {
        if($filterCount > 0)
            $query_change .= "AND ";
        $query_change .= "NOT DisabilityFriendly LIKE 'нет' ";
        $filterCount++;
    }
    if(isset($_POST['food'])) {
        if($filterCount > 0)
            $query_change .= "AND ";
        $query_change .= "NOT HasEatery LIKE 'нет' ";
        $filterCount++;
    }
    if(isset($_POST['music'])) {
        if($filterCount > 0)
            $query_change .= "AND ";
        $query_change .= "NOT HasMusic LIKE 'нет'";
    }
    return $query_change;
}

require ('DB.php');
$content = '';
if (isset($_POST['count']))
    $limit = $_POST['count'];
else $limit = 3;



if (isset($_POST["latitude"])) {

    $latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];
    require("DB.php");
    $query = "SELECT (ACOS(SIN(latitude * PI() / 180) * SIN(" . $latitude . " * PI() / 180) + COS(latitude * PI() / 180) * 
            COS(" . $latitude . " * PI() / 180) * 
             COS((longitude * PI() / 180) - (" . $longitude . " * PI() / 180)))) as 'distance',
             ShootingGallery.*, Address.AdmArea, Address.District, Address.Street, Address.House 
             FROM ShootingGallery INNER JOIN Address ON address_id = Address.id";

    $filter = checkFilter();

    if($filter != "")
        $query .= "WHERE ".$filter;
    $query .= " ORDER BY distance LIMIT " . $limit;
    $result = mysqli_query($conn, $query);
    $content = "";
    while ($sGallery = mysqli_fetch_assoc($result)) {

        $content .= "<h1 class='sg-name bg-light' onclick='showText(this, " . $latitude . ", " . $longitude . ", " . $sGallery['global_id'] . ")'
                 data-latitude='" . $sGallery['latitude'] . "' 
        data-longitude='" . $sGallery['longitude'] . "'><strong>" . $sGallery['ObjectName'] . "</strong><br>(".$sGallery['NameSummer'].")</h1>
                    <div class='sg-object mb-0' style='display: none' id='" . $sGallery['global_id'] . "'><table class='table-light table-bordered'>";

        foreach ($sGallery as $col => $row) {
            if ('ObjectName' == $col or 'global_id' == $col or 'longitude' == $col or 'latitude' == $col or 'distance' == $col
            or 'address_id' == $col) {
                continue;
            }
            $content .= "<tr class='table-light' >";

            if (is_null($row)) {
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$col] . "</td>
                <td class='table-light'>Нет</td>";
            } elseif ('WorkingHoursSummer' == $col) {
                $day = preg_split($pattern, $row);
                $workingHours = "";
                for ($i = 1; $i < count($day);) {
                    $workingHours .= $day[$i] . " " . $day[$i + 1] . "<br>";
                    $i += 2;
                }
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$col] . "</td>
                <td class='table-light'>" . $workingHours . "</td>";

            } elseif ('DimensionsSummer' == $col) {
                $dimension = preg_split($pattern, $row);
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$col] . "</td>
                        <td class='table-light'>
                <p>Площадь: " . $dimension[1] . "<br>Длина:" . $dimension[2] . "<br>Высота:" . $dimension[3] . "<p></td>";

            } elseif ('WebSite' == $col) {
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$col] . "</td>
                <td class='table-light'><a href='//" . $row . "'>" . $row . "</a></td>";

            } elseif ('Email' == $col) {
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$col] . "</td>
                <td class='table-light'><a href='mailto:" . $row . "'>" . $row . "</a></td>";
            } else
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$col] . "</td>
                <td class='table-light'>" . $row . "</td>";
            $content .= "</tr>";
        }
        $content .= "</table></div>";

    }

}
require ('template.php');
