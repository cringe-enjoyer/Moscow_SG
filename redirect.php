<?php
if (isset($_POST["address"])){
    echo "<script src='https://api-maps.yandex.ru/2.1/?apikey=2b7e9cf1-cc82-45e8-b178-813cef04cd6b&lang=ru_RU' 
    type='text/javascript'></script>
    <form name='BBB' method='post'><p>ada</p></form>
<script>window.onload = function (){let userCoordinates = '';
    ymaps.geocode(" . $_POST['address'] . ", {
        results: 1
    }).then(function (res) {
        var firstGeoObject = res.geoObject.get(0),
            // Координаты геообъекта.
            coords = firstGeoObject.geometry.getCoordinates(),
            // Область видимости геообъекта.
            bounds = firstGeoObject.properties.get('boundedBy');


        userCoordinates = coords;
        userBounds = bounds;

    })
    let xhr = new XMLHttpRequest();
    let form = new FormData(BBB);
    form.append('latitude', userCoordinates[0])
    form.append('longitude', userCoordinates[1])

    xhr.open('POST', 'find_page.php');
    xhr.send(form)
 }
    </script>";
}
else{
    echo "bu";
}