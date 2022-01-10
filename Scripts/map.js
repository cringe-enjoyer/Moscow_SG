function init(){
    myMap = new ymaps.Map("bigmap", {
            center: [55.76, 37.64],
            zoom: 11
        },
        {
            searchControlProvider: 'yandex#search'
        })
    let info = document.getElementById('bigmap').dataset.info;
    console.log(document.getElementById('bigmap').dataset.info)
    for (i = 0; i < info.length; i++){
        console.log(info[i][0])
        console.log(info[i][1])
        console.log(info[i][2])
        console.log(info[i][3])
        myMap.geoObjects.add(new ymaps.Placemark([parseFloat(info[i][2]), parseFloat(info[i][3])], {
            balloonContent: '<strong>Название:</strong>' + info[i][0] + ' (' + info[i][1] + ')'
        }, {
            preset: 'islands#dotIcon',
            iconColor: '#f10b0b'
        }))
    }

}
window.onload = function () {
    ymaps.ready(init);
}