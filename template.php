<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>Тиры Москвы</title>
    <link rel="stylesheet" href="Style/style.css">
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=2b7e9cf1-cc82-45e8-b178-813cef04cd6b" type="text/javascript">
    </script>
    <script src="Scripts/main.js"></script>

</head>

<body>
<header class="header">
    <img src="img/logo.svg" class="logo">
    <div class="container-header">
        <h1 class="title">СТРЕЛКОВЫЕ ТИРЫ МОСКВЫ</h1>
    </div>

</header>
    <main class="main">
        <div class="container">
            <div class="address-block">
                <form class="address-block" name="search" method="POST">
                    <input name="latitude" id="latitude" type="hidden">
                    <input name="longitude" id="longitude" type="hidden">
                    <label class="address-label" for="address">Введите ваш адрес</label>
                    <input id="address" name="address" type="text" value="<?if (isset($_POST['address'])) echo $_POST['address']?>" placeholder="Большая Семеновская 38" required>
                    <button id="find" type="button" onclick="submit_form()" class="button">Найти</button>
                </form>
            </div>
        </div>
        <div class="sg-container" id="info">
        <?php const COLUMNS_NAME = ["Название в летний период", "Административный округ", "Район", "Адрес", "Электронная почта",
            "Сайт", "Телефон", "График работы в летний период", "Возможность проката оборудования",
            "Наличие сервиса тех. обслуживания", "Наличие раздевалки", "Наличие точки питания", "Наличие туалета", "Наличие Wi-Fi",
            "Наличие банкомата", "Наличие медпункта", "Наличие звукового сопровождения", "Период эксплуатации в летний период",
            "Размеры в летний период", "Освещение", "Покрытие в летний период", "Кол-во посадочных мест", "Форма посещения",
            "Комментарий к стоимости посещения", "Приспособленность для занятий инвалидов", "Услуги предоставляемые в летний период"];

        $pattern = "/([A-Z a-z]+:)/";
        if(isset($_POST["latitude"])){

            $latitude = $_POST["latitude"];
            $longitude = $_POST["longitude"];

            require("DB.php");
            $query = "SELECT * FROM ((SELECT * from sg_data2 WHERE latitude < ".$latitude ." AND longitude < ".$longitude." 
            OR latitude < ".$latitude ." AND longitude > ".$longitude." ORDER BY latitude DESC, longitude DESC LIMIT 6) 
            UNION (SELECT * from sg_data2 WHERE latitude > ".$latitude." AND longitude > ".$longitude." 
    OR latitude > ".$latitude." AND longitude < ".$longitude." ORDER BY latitude DESC, longitude DESC LIMIT 6))
     as nearest_sg ORDER BY latitude ASC, longitude ASC LIMIT 3;";
            $result = mysqli_query($conn, $query);
            $content = "";
            while ($sGallery = mysqli_fetch_assoc($result)) {

                $content .= "<h1 class='sg-name' onclick='showText(this, ".$latitude.", ".$longitude.", ".$sGallery['global_id'].")'
                 data-latitude='".$sGallery['latitude']."' 
        data-longitude='".$sGallery['longitude']."'>".$sGallery['ObjectName']."</h1>
                    <div class='sg-object' style='display: none' id='".$sGallery['ObjectName']."'>";
                $column = 0;
                foreach ($sGallery as $col => $row) {
                    if('ObjectName' == $col or 'global_id' == $col or 'PhotoSummer' == $col or
                        'longitude' == $col or 'latitude' == $col or 'geoarea' == $col){
                        continue;
                    }
                    $content .= "<div class='sg-row' >";


                    if (is_null($row)){
                        $content .= "<div class='sg-cell'>".COLUMNS_NAME[$column]."</div>
                <div class='sg-cell'>Нет</div>";
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
                <div class='sg-cell'>".$row."</div>";
                    $content .= "</div>";
                    $column++;
                }
                $content .= "</div>";

            }
        echo $content;
        }?>
        </div>

<div id="map">

</div>
    </main>
</body>
<footer class="footer">
    <div class="footer-info">
        <p>&copy;Корчагин И.В.</p>
    </div>
</footer>
</html>
