<?php
const COLUMNS_NAME = ["Название в летний период", "Административный округ", "Район", "Адрес", "Электронная почта",
    "Сайт", "Телефон", "График работы в летний период", "Возможность проката оборудования",
    "Наличие сервиса тех. обслуживания", "Наличие раздевалки", "Наличие точки питания", "Наличие туалета", "Наличие Wi-Fi",
    "Наличие банкомата", "Наличие медпункта", "Наличие звукового сопровождения", "Период эксплуатации в летний период",
    "Размеры в летний период", "Освещение", "Покрытие в летний период", "Кол-во посадочных мест", "Форма посещения",
    "Комментарий к стоимости посещения", "Приспособленность для занятий инвалидов", "Услуги предоставляемые в летний период"];

$pattern = "/([A-Z a-z]+:)/";
if(isset($_POST["latitude"])){

    $latitude = 55.781291;
    $longitude = 37.711518;

    require("DB.php");
    $query = "SELECT * FROM ((SELECT * from sg_data2 WHERE latitude < ".$latitude ."  OR longitude < ".$longitude." 
    ORDER BY latitude, longitude DESC LIMIT 3) UNION (SELECT * from sg_data2 WHERE latitude > ".$latitude." OR longitude > ".$longitude." 
    ORDER BY latitude, longitude DESC LIMIT 3)) as nearest_sg ORDER BY latitude, longitude DESC LIMIT 3;";
    $result = mysqli_query($conn, $query);
    $content = "";
    while ($sGallery = mysqli_fetch_assoc($result)) {

        $content .= "<h1 class='sg-name' onclick='showText(this)' data-latitude='".$sGallery['latitude']."' 
        data-longitude='".$sGallery['longitude']."'>".$sGallery['ObjectName']."</h1>
                    <div class='sg-container' id='".$sGallery['ObjectName']."'>";
        $column = 0;
        foreach ($sGallery as $col => $row) {
            if('ObjectName' == $col or 'global_id' == $col or 'PhotoSummer' == $col or
                'longitude' == $col or 'latitude' == $col or 'geoarea' == $col){
                continue;
            }
            $content .= "<div class='sg-row' >";


            if (is_null($row)){
                $content .= "<div class='sg-cell'>".COLUMNS_NAME[$column]."</div>
                <div class='sg-cell'><p>Нет</p></div>";
            }

            elseif ('WorkingHoursSummer' == $col){
                $day = preg_split($pattern, $row);
                $workingHours = "";
                for ($i = 1; $i < count($day);) {
                    $workingHours .= $day[$i]." ".$day[$i+1]."<br>";
                    $i += 2;
                }
                $content .= "<div class='sg-cell'>".COLUMNS_NAME[$column]."</div>
                <div class='sg-cell'>".$workingHours."</div>";

            }
            elseif('DimensionsSummer' == $col){
                $dimension = preg_split($pattern, $row);
                $content .= "<div class='sg-cell'>".COLUMNS_NAME[$column]."</div><div class='sg-cell'>
                <p>Площадь: ".$dimension[1]."<br>Длина:".$dimension[2]."<br>Высота:".$dimension[3]."<p></div>";

            }
            else
                $content .= "<div class='sg-cell'>".COLUMNS_NAME[$column]."</div>
                <div class='sg-cell'><p>".$row."</p></div>";
            $content .= "</div>";
            $column++;
        }
        $content .= "</div>";

    }
    require ('template.php');
}
