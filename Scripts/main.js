const pattern = /^(ул|ул.|улица|улица.|Улица|Улица.)*\s*[А-Яа-я]+\s*[А-Яа-я]*[\s,.]+(дом|д|д\.|дом\.|Дом|Дом\.|Д\.)*\s*\d+/g

let latitude;
let longitude;
var myMap = undefined;
let sgOpenCount = 0;
let limit = 3;

function showText(element, user_latitude, user_longitude, id) {
    let sg_info = document.getElementById(id);

    //Hiding additional information after the user clicks on the name of the shooting gallery
    if (sg_info.style.display != 'none') {
        sg_info.style.display = 'none';
        element.classList.remove('mb-0');
        element.classList.remove('align-center');
        let map = document.getElementById("map" + id);
        sg_info.removeChild(map);

    }
    //Showing more information to the user after he clicks on the name of the shooting gallery
    else {
        sg_info.style.display = 'flex'
        element.classList.add('mb-0');
        latitude = element.dataset.latitude;
        longitude = element.dataset.longitude;
        let map = document.createElement("div");
        map.className = "map";
        map.classList.add('justify-content-center')
        element.classList.add('align-center');
        map.id = "map" + id;
        sg_info.append(map);
        init(map, element, user_latitude, user_longitude)
    }
}

function checkAddress(address){
    let check = address.matchAll(pattern)
    let result = Array.from(check)[0]
    if(result !== undefined){
        return result[0]
    }
    return null
}


function init(map, sg_name, user_latitude, user_longitude) {
    //Create a route between the user's address and the shooting gallery
    var multiRoute = new ymaps.multiRouter.MultiRoute({
        referencePoints: [
            [user_latitude, user_longitude],
            [parseFloat(sg_name.dataset.latitude), parseFloat(sg_name.dataset.longitude)]
        ],
        params: {
            results: 2
        }}, {
        boundsAutoApply: true
    })

    //Add buttons for the user that allow him to choose a route for a car or public transport
    var carRouteButton = new ymaps.control.Button({
            data: {content: "На машине"},
            options: {selectOnClick: true}
        }),
        masstransitButton = new ymaps.control.Button({
            data: {content: "На общественном транспорте"},
            options: {selectOnClick: true}
        });

    carRouteButton.events.add('select', function (){
        multiRoute.model.setParams({routingMode: 'auto'}, true)
        masstransitButton.deselect()
    });

    masstransitButton.events.add('select', function (){
        multiRoute.model.setParams({routingMode: 'masstransit'}, true)
        carRouteButton.deselect()
    });

    myMap = new ymaps.Map(map.id + "", {
            center: [55.76, 37.64],
            zoom: 11,
            controls: [carRouteButton, masstransitButton]
        }, {
        buttonMaxWidth: 300
    },
        {
            searchControlProvider: 'yandex#search'
        }, {
        boundsAutoApply: true
    })
    carRouteButton.select()
    myMap.geoObjects.add(multiRoute)

}

function submit_form(){
    let form = document.forms.search
    let address = document.getElementById('address').value;
    let good_address = checkAddress(address);
    let userCoordinates = [];
    if(good_address !== null) {
        good_address = 'Москва ' + good_address;
        var myMap = new ymaps.Map('map', {
            center: [55.753994, 37.622093],
            zoom: 10
        });

        //Getting latitude and longitude by geocode and sending them to the server
        ymaps.geocode(good_address, {

            results: 1
        }).then(function (res) {

            var firstGeoObject = res.geoObjects.get(0),

                coords = firstGeoObject.geometry.getCoordinates(),

                bounds = firstGeoObject.properties.get('boundedBy');

            userCoordinates[0] = coords[0];
            userCoordinates[1] = coords[1];



            firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');

            firstGeoObject.properties.set('iconCaption', firstGeoObject.getAddressLine());


            myMap.geoObjects.add(firstGeoObject);

            myMap.setBounds(bounds, {

                checkZoomRange: false
            });

            //Add user coordinates to the form
            form.elements.latitude.value = userCoordinates[0].toString()
            form.elements.longitude.value = userCoordinates[1].toString()
            form.submit()
        });

    }
    else{
        alert('Вы ввели некорректный адрес. Попробуйте ещё раз.')
    }
}

window.onload = function () {
    document.getElementById('address').onkeydown = function (event) {
        if (event.keyCode == 13)
            document.getElementById('find').click();
    }
    limit = Number(document.getElementById('count').value)
}