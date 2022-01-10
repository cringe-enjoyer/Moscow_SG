<?php
require ("DB.php");
$query = "SELECT ObjectName, NameSummer, latitude, longitude FROM sg_data2";
$result = mysqli_query($conn, $query);
$info = [];
$number = 0;
while ($sGallery = mysqli_fetch_assoc($result)) {
    $info[$number] = [];
    foreach ($sGallery as $col => $row) {
        $info[$number][$col] = $row;
    }
    $number++;
}
echo "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport'
          content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title>Тиры Москвы</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3' crossorigin='anonymous'>
    <link rel='stylesheet' href='Style/style.css'>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p' crossorigin='anonymous'></script>
    <script src='https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=2b7e9cf1-cc82-45e8-b178-813cef04cd6b' type='text/javascript'>
    </script>


</head>

<body style='height: 100vh'>
    <header class='header justify-content-between'>
        <img src='img/logo.svg' class=''>
        <div class='container-header'>
            <a class='mscmap p-0' style='text-decoration: none' href='index.php'>
                <h1 class='title'>СТРЕЛКОВЫЕ ТИРЫ МОСКВЫ</h1>
            </a>
        </div>
        <div class='d-flex justify-content-right'>
            <a class='mscmap p-0' href='map.php' style='text-decoration: none'>Карта</a>
        </div>

    </header>
    <div id='bigmap' class='bigmap' data-info=''>

    </div>
</body>
<script>

function init(){
    let info = ".json_encode($info).";
    myMap = new ymaps.Map('bigmap', {
            center: [55.76, 37.64],
            zoom: 11
        },
        {
            searchControlProvider: 'yandex#search'
        })

    for (i = 0; i < info.length; i++){
        let placemark = new ymaps.Placemark([parseFloat(info[i]['latitude']), parseFloat(info[i]['longitude'])], {
            balloonContentHeader: '<strong>Название:</strong> ' + info[i]['ObjectName'] + ' (' + info[i]['NameSummer'] + ')'
        }, {
            preset: 'islands#dotIcon',
            iconColor: '#f10b0b'
        })
        placemark.events.add('balloonopen', function (e) {
        //placemark.properties.set('balloonContent', 'Идет загрузка данных...');

        ymaps.geocode(placemark.geometry.getCoordinates(), {
            results: 1
        }).then(function (res) {
            var newContent = res.geoObjects.get(0) ?
                    '<strong>Адресс</strong>: ' + res.geoObjects.get(0).properties.get('name') :
                    'Не удалось определить адрес'
            placemark.properties.set('balloonContentBody', newContent);
        });
        
    });
        myMap.geoObjects.add(placemark)
    }

}
window.onload = function () {
    ymaps.ready(init);
}
</script>
</html>";
