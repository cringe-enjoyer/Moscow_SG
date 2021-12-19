<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>Тиры Москвы</title>
    <link rel="stylesheet" href="Style/style.css">
    <script src="https://api-maps.yandex.ru/2.1/?apikey=2b7e9cf1-cc82-45e8-b178-813cef04cd6b&lang=ru_RU" type="text/javascript">
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
            <div class="address_block">
                <form class="address_block" name="search" method="POST">
                    <input name="latitude" id="latitude" value="<?if (isset($_POST['latitude'])) echo $_POST['latitude']?>" type="text" hidden>
                    <input name="longitude" id="longitude" value="<?if (isset($_POST['longitude'])) echo $_POST['longitude']?>" type="text" hidden>
                    <label class="address_label" for="address">Введите ваш адрес</label>
                    <input id="address" name="address" type="text" value="<?if (isset($_POST['address'])) echo $_POST['address']?>" placeholder="Большая Семеновская 38" required>
                    <button id="find" type="button" onclick="submit_form()" class="button">Найти</button>
                </form>
            </div>
        </div>
        <div class="sg_container" id="info">
        <?php $content?>
        </div>
<?php /*if (isset($_POST['user_coordinates'])){
           echo "<div id='map'><script type='text/javascript'>
var myMap;
function init() {
    myMap = new ymaps.Map('map', {
            center: [55.76, 37.64],
            zoom: 10
        }, {
            searchControlProvider: 'yandex#search'
        });

    myMap.geoObjects.add(new ymaps.Placemark(".$_POST['user_coordinates'].", {
            balloonContent: '<strong>Вы</strong>'
        }, {
            preset: 'islands#dotIcon',
            iconColor: '#f10b0b'
        }))
        .add(new ymaps.Placemark([55.833436, 37.715175], {
        preset: 'islands#dotIcon',
        iconColor: '#735184'
    }))
</script></div>";
        }
        */?>
<div id="map"></div>
    </main>
</body>
<footer class="footer">
    <div class="footer-info">
        <p>Корчагин И.В.</p>
    </div>
</footer>
</html>
