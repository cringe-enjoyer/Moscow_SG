<?php
const COLUMNS_NAME = ["Название в летний период", "Административный округ", "Район", "Адрес", "Электронная почта",
    "Сайт", "Телефон", "График работы в летний период", "Возможность проката оборудования",
    "Наличие сервиса тех. обслуживания", "Наличие раздевалки", "Наличие точки питания", "Наличие туалета", "Наличие Wi-Fi",
    "Наличие банкомата", "Наличие медпункта", "Наличие звукового сопровождения", "Период эксплуатации в летний период",
    "Размеры в летний период", "Освещение", "Покрытие в летний период", "Кол-во посадочных мест", "Форма посещения",
    "Комментарий к стоимости посещения", "Приспособленность для занятий инвалидов", "Услуги предоставляемые в летний период"];

$pattern = "/([A-Z a-z]+:)/";
if (isset($_POST['latitude']) and $_POST['latitude'] != ''){


    $longitude = (double) $_POST['longitude'];
    $latitude = (double) $_POST['latitude'];
/*}
if (isset($_POST['longitude'])) {
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];*/

    require("DB.php");
    $query = "SELECT * FROM ((SELECT * from sg WHERE latitude < " . $latitude . " AND longitude < " . $longitude . "  -- OR или And?
    ORDER BY latitude, longitude DESC LIMIT 3) UNION (SELECT * from sg WHERE latitude > " . $latitude . " AND longitude > " . $longitude . " 
    ORDER BY latitude, longitude DESC LIMIT 3)) as nearest_sg ORDER BY latitude, longitude DESC LIMIT 3;";
    $result = mysqli_query($conn, $query);

    $content = "";
    while ($sGallery = mysqli_fetch_assoc($result)) {

        $content .= "<h1 class='sg_name' onclick='showText(this)' data-latitude='".$sGallery['latitude']."' 
        data-longitude='".$sGallery['longitude']."'></h1>
                    <div class='sg_container' id='".$sGallery['ObjectName']."'>";
        $column = 0;
        foreach ($sGallery as $col => $row) {
            if('ObjectName' == $col or 'global_id' == $col or 'PhotoSummer' == $col or
                'longitude' == $col or 'latitude' == $col or 'geoarea' == $col){
                continue;
            }
            $content .= "<div class='sg_row' >";


            if (is_null($row)){
                $content .= "<div class='sg_cell'>".COLUMNS_NAME[$column]."</div>
                <div class='sg_cell'><p>Нет</p></div>";
            }

            elseif ('WorkingHoursSummer' == $col){
                $day = preg_split($pattern, $row);
                $workingHours = "";
                for ($i = 1; $i < count($day);) {
                    $workingHours .= $day[$i]." ".$day[$i+1]."<br>";
                    $i += 2;
                }
                $content .= "<div class='sg_cell'>".COLUMNS_NAME[$column]."</div>
                <div class='sg_cell'>".$workingHours."</div>";

            }
            elseif('DimensionsSummer' == $col){
                $dimension = preg_split($pattern, $row);
                $content .= "<div class='sg_cell'>".COLUMNS_NAME[$column]."</div><div class='sg_cell'>
                <p>Площадь: ".$dimension[1]."<br>Длина:".$dimension[2]."<br>Высота:".$dimension[3]."<p></div>";

            }
            else
                $content .= "<div class='sg_cell'>".COLUMNS_NAME[$column]."</div>
                <div class='sg_cell'><p>".$row."</p></div>";
            $content .= "</div>";
            $column++;
        }
        $content .= "</div>";

    }

}
else{

}
require ('template.php');

