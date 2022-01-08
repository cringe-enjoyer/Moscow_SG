<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>Тиры Москвы</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="Style/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
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
        <div class="address-container">
            <div class="container w-100">
                <form class="container justify-content-center" name="search" method="POST">
                    <input name="latitude" id="latitude" type="hidden">
                    <input name="longitude" id="longitude" type="hidden">
                    <input id="enter" name="enter" type="submit" onclick="return false" hidden>
                    <div class="row">
                        <label class="address-label" for="address">Введите ваш адрес</label>
                        <div class="col-11">
                            <input class="address-input w-100" id="address" name="address" type="text" value="<?if (isset($_POST['address'])) echo $_POST['address']?>" placeholder="Большая Семеновская 38" required>
                        </div>
                        <div class="col-1">
                            <select id="count" name="count" style="max-width: 50px">
                                <?if (isset($_POST['count']))
                                    $limit = $_POST['count'];
                                else $limit = 3?>
                                <option <?if($limit == 3) echo "selected='selected'"?>>3</option>
                                <option <?if($limit == 5) echo "selected='selected'"?>>5</option>
                                <option <?if($limit == 10) echo "selected='selected'"?>>10</option>
                                <option <?if($limit == 15) echo "selected='selected'"?>>15</option>
                            </select>
                        </div>

                    </div>
                    <button name="find" id="find" type="button" onclick="submit_form()" class="button">Найти</button>
                </form>
            </div>
        </div>
        <div class="container pb-0 bg-light" id="info">

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
            $query = "SELECT (ACOS(SIN(latitude * PI() / 180) * SIN(".$latitude." * PI() / 180) + COS(latitude * PI() / 180) * 
            COS(".$latitude." * PI() / 180) * 
             COS((longitude * PI() / 180) - (".$longitude." * PI() / 180)))) as 'distance',
             sg_data2.* FROM sg_data2 
            ORDER BY distance LIMIT ".$limit;
            $result = mysqli_query($conn, $query);
            $content = "";
            while ($sGallery = mysqli_fetch_assoc($result)) {

                $content .= "<h1 class='sg-name' onclick='showText(this, ".$latitude.", ".$longitude.", ".$sGallery['global_id'].")'
                 data-latitude='".$sGallery['latitude']."' 
        data-longitude='".$sGallery['longitude']."'>".$sGallery['ObjectName']."</h1>
                    <div class='sg-object mb-0' style='display: none' id='".$sGallery['global_id']."'><table class='table-light table-bordered'>";
                $column = 0;
                foreach ($sGallery as $col => $row) {
                    if('ObjectName' == $col or 'global_id' == $col or 'PhotoSummer' == $col or
                        'longitude' == $col or 'latitude' == $col or 'geoarea' == $col or 'distance' == $col){
                        continue;
                    }
                    $content .= "<tr class='table-light' >";


                    if (is_null($row)){
                        $content .= "<td class='table-light'>".COLUMNS_NAME[$column]."</td>
                <td class='table-light'>Нет</td>";
                    }

                    elseif ('WorkingHoursSummer' == $col){
                        $day = preg_split($pattern, $row);
                        $workingHours = "";
                        for ($i = 1; $i < count($day);) {
                            $workingHours .= $day[$i]." ".$day[$i+1]."<br>";
                            $i += 2;
                        }
                        $content .= "<td class='table-light'>".COLUMNS_NAME[$column]."</td>
                <td class='table-light'>".$workingHours."</td>";

                    }
                    elseif('DimensionsSummer' == $col){
                        $dimension = preg_split($pattern, $row);
                        $content .= "<td class='table-light'>".COLUMNS_NAME[$column]."</td>
                        <td class='table-light'>
                <p>Площадь: ".$dimension[1]."<br>Длина:".$dimension[2]."<br>Высота:".$dimension[3]."<p></td>";

                    }
                    elseif('WebSite' == $col){
                        $content .= "<td class='table-light'>".COLUMNS_NAME[$column]."</td>
                <td class='table-light'><a href='//".$row."'>".$row."</a></td>";
                        $content .= "</tr>";
                        $column++;
                    }
                    elseif('Email' == $col){
                        $content .= "<td class='table-light'>".COLUMNS_NAME[$column]."</td>
                <td class='table-light'><a href='mailto:".$row."'>".$row."</a></td>";
                        $content .= "</tr>";
                        $column++;
                    }
                    else
                        $content .= "<td class='table-light'>".COLUMNS_NAME[$column]."</td>
                <td class='table-light'>".$row."</td>";
                    $content .= "</tr>";
                    $column++;
                }
                $content .= "</table></div>";

            }
        echo $content;
        }?>

        </div>

<div id="map">

</div>
    </main>
</body>
<footer id="footer" class="footer position-fixed bottom-0">
    <div class="footer-info">
        <p>&copy;Корчагин И.В.</p>
    </div>
</footer>
</html>
