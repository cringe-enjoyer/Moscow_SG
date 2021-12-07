const pattern = /^[А-Яа-я]+\s*[А-Яа-я]*[\s,.]+(дом|д|д\.|дом\.|Дом|Дом\.|Д\.)*\s*\d+/g

let data

function showText(id) {
    let text = document.getElementById(id)
    if (text.style.display !== 'block') {

        text.style.display = 'block';
        text.style.transition = '.2s';
        text.style.transitionTimingFunction = 'ease-in-out';
    }
    else
        text.style.display = 'none'
}
function checkAddress(address){
    let check = address.matchAll(pattern)
    let result = Array.from(check)[0]
    if(result !== undefined){
        return result[0]
    }
    return null
}

/*function selectData() {
    let xhr = new XMLHttpRequest()
    xhr.open('GET', 'https://apidata.mos.ru/v1/datasets/888/rows?$orderby=global_id&api_key=87bff77c6c5da179bff24b46f5359dec')
    xhr.responseType = 'json'
    xhr.send();
    xhr.timeout = 10000
    xhr.ontimeout = function (){
        alert('boom')
    }
    xhr.onload = function (){
        if(xhr.status != 200){
            alert(`Ошибка ${xhr.status}: ${xhr.statusText}`)
        } else {
            data = xhr.response;
            console.log(xhr.response)
        }
    }
    /!*let get = fetch('https://apidata.mos.ru/v1/datasets/888/rows?$orderby=global_id&api_key=87bff77c6c5da179bff24b46f5359dec')
    if (get.ok) {
        let respopnse = get.json()
        data = respopnse
    }*!/
}*/

function createMap(address){
    let map = document.createElement('img')
    let img = new Image(100, 100);
    img.src = "F:\\ЯСтудент\\Базы данных\\Cursach\\map.png"
    map.src = img.src
    map.width = '100%'
    map.height = '100%'
    map.style.display = 'block'
    //map.parentElement.style.display = 'block'
}

/*function findPlaces(address){
    let name = data[0].Cells.ObjectName;
    let item = document.createElement('div')
    let info = document.createElement('table')
    info.id = 'inf'
    info.style.display = 'none'
    for (const Key in data[0].Cells) {
        if (Key !== 'DimensionsSummer' && Key !== 'ObjectName' && Key !== 'global_id' && Key !== 'PhotoSummer' && Key !== 'geoData') {
            if (Key == 'WorkingHoursSummer'){
                let row = document.createElement('tr')
                let cell = document.createElement('td')
                cell.innerHTML = 'График работы'
                row.append(cell)
                info.append(row)
                for(const Days in data[0].Cells[Key]){
                    let row = document.createElement('tr')
                    let cell = document.createElement('td')
                    cell.innerHTML = data[0].Cells[Key][Days].DayOfWeek + ' ' + data[0].Cells[Key][Days].Hours
                    row.append(cell)
                    info.append(row)
                }
                continue
            }
            if(Key == 'WebSite'){
                let row = document.createElement('tr')
                let cell = document.createElement('td')
                let site = data[0].Cells[Key]
                site.url = data[0].Cells[Key]
                cell.innerHTML = site
                row.append(cell)
                info.append(row)
            }

            let row = document.createElement('tr')
            let cell = document.createElement('td')
            cell.innerHTML = data[0].Cells[Key]
            row.append(cell)
            info.append(row)
        }
    }

    item.classList.add('test')
    item.id = 'te';
    let p = document.createElement('p')
    p.innerHTML = name;
    //item.innerHTML = name
    item.appendChild(p)
    item.appendChild(info)

    let map = createMap(address)
    item.append(map)
    info.classList.add('hidden_text')


    p.onclick = function (){
        showText(item.lastElementChild.id)
    }
    return item
}*/

let find = document.getElementById('find')
find.onclick = function (e) {
    let footer = document.styleSheets[0].cssRules[24].style
    footer.removeProperty('position')
    let address = document.getElementById('address').value;
    let good_address = checkAddress(address);
    if(good_address !== null) {
        let old_data = document.querySelector('.test')
        if (old_data !== null)
            old_data.remove()
        let shooting_galleries = findPlaces(good_address);
        document.getElementById('info').append(shooting_galleries)

    }
    else{
        alert('Вы ввели странный адрес. Попробуйте ещё.')
    }
}
window.onload = function (){
    setInterval(selectData(), 30000)

}

const EART_RADIUS = 6371210; //Радиус земли
const DISTANCE = 20000; //Интересующее нас расстояние

//https://en.wikipedia.org/wiki/Longitude#Length_of_a_degree_of_longitude
function computeDelta(degrees) {
    return Math.PI / 180 * EART_RADIUS * Math.cos(deg2rad(degrees));
}

function deg2rad(degrees) {
    return degrees * Math.PI / 180;
}

const latitude = 55.460531; //Интересующие нас координаты широты
const longitude = 37.210488; //Интересующие нас координаты долготы

const deltaLat = computeDelta(latitude); //Получаем дельту по широте
const deltaLon = computeDelta(longitude); // Дельту по долготе

const aroundLat = DISTANCE / deltaLat; // Вычисляем диапазон координат по широте
const aroundLon = DISTANCE / deltaLon; // Вычисляем диапазон координат по долготе

console.log(aroundLat, aroundLon);

function findPlace(address){
    let name = data[0].Cells.ObjectName;
    let item = document.createElement('div')
    let info = document.createElement('table')
    info.id = 'inf'
    info.style.display = 'none'
    for (const Key in data[0].Cells) {
        if (Key !== 'DimensionsSummer' && Key !== 'ObjectName' && Key !== 'global_id' && Key !== 'PhotoSummer' && Key !== 'geoData') {
            if (Key == 'WorkingHoursSummer'){
                let row = document.createElement('tr')
                let cell = document.createElement('td')
                cell.innerHTML = 'График работы'
                row.append(cell)
                info.append(row)
                for(const Days in data[0].Cells[Key]){
                    let row = document.createElement('tr')
                    let cell = document.createElement('td')
                    cell.innerHTML = data[0].Cells[Key][Days].DayOfWeek + ' ' + data[0].Cells[Key][Days].Hours
                    row.append(cell)
                    info.append(row)
                }
                continue
            }
            if(Key == 'WebSite'){
                let row = document.createElement('tr')
                let cell = document.createElement('td')
                let site = data[0].Cells[Key]
                site.url = data[0].Cells[Key]
                cell.innerHTML = site
                row.append(cell)
                info.append(row)
            }

            let row = document.createElement('tr')
            let cell = document.createElement('td')
            cell.innerHTML = data[0].Cells[Key]
            row.append(cell)
            info.append(row)
        }
    }

    item.classList.add('test')
    item.id = 'te';
    let p = document.createElement('p')
    p.innerHTML = name;
    //item.innerHTML = name
    item.appendChild(p)
    item.appendChild(info)

    let map = createMap(address)
    item.append(map)
    info.classList.add('hidden_text')


    p.onclick = function (){
        showText(item.lastElementChild.id)
    }
    return item
}

function createDate(){

}
