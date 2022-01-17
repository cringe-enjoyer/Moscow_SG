<?php
const COLUMNS_NAME = ["SecondName" => "Название в летний период", "AdmArea" => "Административный округ", "District" => "Район",
    "Street" => "Улица", "House" => "Дом", "Email" => "Электронная почта", "WebSite" => "Сайт", "HelpPhone" => "Телефон",
    "schedule_id" => "График работы", "HasEquipmentRental" => "Возможность проката оборудования",
    "HasTechService" => "Наличие сервиса тех. обслуживания", "HasDressingRoom" => "Наличие раздевалки",
    "HasEatery" => "Наличие точки питания", "HasToilet" => "Наличие туалета", "HasWifi" => "Наличие Wi-Fi",
    "HasCashMachine" => "Наличие банкомата", "HasFirstAidPost" => "Наличие медпункта",
    "HasMusic" => "Наличие музыкального сопровождения", "UsagePeriod" => "Период эксплуатации", "Dimensions_id" => "Размеры",
    "Lighting" => "Освещение", "SurfaceType" => "Покрытие", "Seats" => "Количество посадочных мест",
    "Paid" => "Форма посещения", "PaidComments" => "Комментарий к стоимости посещения",
    "DisabilityFriendly" => "Приспособленность для занятий инвалидов", "Services" => "Дополнительные услуги"];

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
             ShootingGallery.*, Address.*, Dimension.*, Schedule.* 
             FROM ShootingGallery INNER JOIN Address ON address_id = Address.id 
             INNER JOIN Dimension ON Dimension.id = Dimensions_id INNER JOIN Schedule ON schedule_id = Schedule.id";

    $filter = checkFilter();

    if($filter != "")
        $query .= " WHERE ".$filter;
    $query .= " ORDER BY distance LIMIT " . $limit;
    $result = mysqli_query($conn, $query);
    $content = "";
    while ($sGallery = mysqli_fetch_assoc($result)) {

        $content .= "<h1 class='sg-name bg-light' onclick='showText(this, " . $latitude . ", " . $longitude . ", " . $sGallery['global_id'] . ")'
                 data-latitude='" . $sGallery['latitude'] . "' 
        data-longitude='" . $sGallery['longitude'] . "'><strong>" . $sGallery['ObjectName'] . "</strong><br>(".$sGallery['SecondName'].")</h1>
                    <div class='sg-object mb-0' style='display: none' id='" . $sGallery['global_id'] . "'><table class='table-light table-bordered'>";

        foreach ($sGallery as $col => $row) {
            if (!COLUMNS_NAME[$col]){
                continue;
            }
            $content .= "<tr class='table-light' >";

            if (is_null($row)) {
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$col] . "</td>
                <td class='table-light'>Нет</td>";
            } elseif ('schedule_id' == $col) {
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$col] . "</td>
                <td class='table-light'> Понедельник: " . $sGallery['monday'] . "<br>
                Вторник: ".$sGallery['tuesday']."<br>Среда: ".$sGallery['wednesday']."<br>
                Четверг: ".$sGallery['thursday']."<br>Пятница: ".$sGallery['friday']."<br>
                Суббота: ".$sGallery['saturday']."<br>Воскресенье: ".$sGallery['sunday']."</td>";

            } elseif ('Dimensions_id' == $col) {
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$col] . "</td>
                        <td class='table-light'>Площадь: ".$sGallery['square']."<br>Длина: ".$sGallery['length']."<br>
                Высота: ".$sGallery['width']."</td>";
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
