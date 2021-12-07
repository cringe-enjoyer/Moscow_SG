<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>Тиры Москвы</title>
    <link rel="stylesheet" href="Style/style.css">
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
                <label class="address_label" for="address">Введите ваш адрес</label>
                <input id="address" name="address" type="text" placeholder="Большая Семеновская 38" required>
                <form action="">
                    <button type="submit" class="button" id="find">Найти</button>
                </form>

            </div>
        </div>
        <div class="sg_container" id="info">
        <?$content?>
        </div>
        <div class="map">
            <img id="map" src="img/map.png" style="display: none" width="100%" height="100%">
        </div>
    </main>
</body>
<footer class="footer">
    <div class="footer-info">
        <p>Корчагин И.В.</p>
    </div>
</footer>
<script src="Scripts/main.js"></script>
</html>
