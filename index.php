<?php
const COLUMNS_NAME = ["Название в летний период", "Административный округ", "Район", "Адрес", "Электронная почта",
    "Сайт", "Телефон", "График работы в летний период", "Возможность проката оборудования",
    "Наличие сервиса тех. обслуживания", "Наличие раздевалки", "Наличие точки питания", "Наличие туалета", "Наличие Wi-Fi",
    "Наличие банкомата", "Наличие медпункта", "Наличие звукового сопровождения", "Период эксплуатации в летний период",
    "Размеры в летний период", "Освещение", "Покрытие в летний период", "Кол-во посадочных мест", "Форма посещения",
    "Комментарий к стоимости посещения", "Приспособленность для занятий инвалидов", "Услуги предоставляемые в летний период"];

require ('DB.php');
$content = '';
if (isset($_POST['count']))
    $limit = $_POST['count'];
else $limit = 3;


$pattern = "/([A-Z a-z]+:)/";
if (isset($_POST["latitude"])) {

    $latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];
    require("DB.php");
    $query = "SELECT (ACOS(SIN(latitude * PI() / 180) * SIN(" . $latitude . " * PI() / 180) + COS(latitude * PI() / 180) * 
            COS(" . $latitude . " * PI() / 180) * 
             COS((longitude * PI() / 180) - (" . $longitude . " * PI() / 180)))) as 'distance',
             sg_data2.* FROM sg_data2 
            ORDER BY distance LIMIT " . $limit;
    $result = mysqli_query($conn, $query);
    $content = "";
    while ($sGallery = mysqli_fetch_assoc($result)) {

        $content .= "<h1 class='sg-name' onclick='showText(this, " . $latitude . ", " . $longitude . ", " . $sGallery['global_id'] . ")'
                 data-latitude='" . $sGallery['latitude'] . "' 
        data-longitude='" . $sGallery['longitude'] . "'>" . $sGallery['ObjectName'] . "</h1>
                    <div class='sg-object mb-0' style='display: none' id='" . $sGallery['global_id'] . "'><table class='table-light table-bordered'>";
        $column = 0;
        foreach ($sGallery as $col => $row) {
            if ('ObjectName' == $col or 'global_id' == $col or 'PhotoSummer' == $col or
                'longitude' == $col or 'latitude' == $col or 'geoarea' == $col or 'distance' == $col) {
                continue;
            }
            $content .= "<tr class='table-light' >";


            if (is_null($row)) {
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$column] . "</td>
                <td class='table-light'>Нет</td>";
            } elseif ('WorkingHoursSummer' == $col) {
                $day = preg_split($pattern, $row);
                $workingHours = "";
                for ($i = 1; $i < count($day);) {
                    $workingHours .= $day[$i] . " " . $day[$i + 1] . "<br>";
                    $i += 2;
                }
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$column] . "</td>
                <td class='table-light'>" . $workingHours . "</td>";

            } elseif ('DimensionsSummer' == $col) {
                $dimension = preg_split($pattern, $row);
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$column] . "</td>
                        <td class='table-light'>
                <p>Площадь: " . $dimension[1] . "<br>Длина:" . $dimension[2] . "<br>Высота:" . $dimension[3] . "<p></td>";

            } elseif ('WebSite' == $col) {
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$column] . "</td>
                <td class='table-light'><a href='//" . $row . "'>" . $row . "</a></td>";

            } elseif ('Email' == $col) {
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$column] . "</td>
                <td class='table-light'><a href='mailto:" . $row . "'>" . $row . "</a></td>";

            } else
                $content .= "<td class='table-light'>" . COLUMNS_NAME[$column] . "</td>
                <td class='table-light'>" . $row . "</td>";
            $content .= "</tr>";
            $column++;
        }
        $content .= "</table></div>";

    }

}
require ('template.php');