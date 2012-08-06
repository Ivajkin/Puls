// dbrand.js
var dBrand = [{
	"mark" : "Chevrolet",
	"model" : ["1Model1", "1Model2", "1Model3"],
	"img" : "http://upload.wikimedia.org/wikipedia/ru/thumb/3/34/Chevrolet_logo.png/200px-Chevrolet_logo.png"
}, {
	"mark" : "Daewoo",
	"model" : ["2Model1", "2Model2", "2Model3"],
	"img" : "http://img1.loadtr.com/b-303188-Daewoo_logo.jpg"
}, {
	"mark" : "Honda",
	"model" : ["3Model1", "3Model2", "3Model3"],
	"img" : "http://farm5.staticflickr.com/4064/4253120289_058ed34263_s.jpg"
}, {
	"mark" : "Hyundai",
	"model" : ["4Model1", "4Model2"],
	"img" : "http://www.cars-area.ru/pic/news/Hyundai_logo_1_small.jpg"
}, {
	"mark" : "KIA",
	"model" : ["4Model1", "4Model2"],
	"img" : "http://www.sewabah.com/logotype/KIA-LOGO1.gif"
}, {
	"mark" : "SsangYong",
	"model" : ["4Model1", "4Model2"],
	"img" : "http://www.avtologo.ru/img/ssangyong/ssangyong1_smal.jpg"
}, {
	"mark" : "Toyota",
	"model" : ["1Model1", "1Model2", "1Model3"],
	"img" : "http://www.pickup-center.ru/img/pickups_logo/toyota_intro.jpg"
}];

// ddata.js
/*
 * <th>Изображение</th>
 * <th>Марка</th>
 * <th>Модель</th>
 * <th>Год</th>
 * <th>Цена</th>
 */
var dData = [{
	image_url: 'css/expauto/images/thumbs/3_1314745445.jpg',
	year:  1996,
	name: 'BMW X5 16.6 l',
	price: 120000
}, {
	image_url: 'css/expauto/images/thumbs/2_1337344871.jpg',
	year:  2006,
	name: 'Audi A3 1.9 l ;145 kW',
	price: 200000
}

/*{
	"type" : "0",
	"mark" : "0",
	"model" : "1",
	"price" : "133000",
	"name" : "Авто_1",
	"shortinfo" : "Краткая инфа о товаре",
	"mainInfo" : "Подробная инфа о товаре",
	"image" : ["imagePath_1", "ImagePath_2", "ImagePath_3"],
	"tableHead" : ["Характеристика_1", "Характеристика_2", "Характеристика_3"],
	"tableData" : ["инфа о Характеристике_1", "инфа о Характеристике_2", "инфа о Характеристике_3"]
}, {
	"type" : "0",
	"mark" : "0",
	"model" : "1",
	"price" : "133000",
	"name" : "Авто_2",
	"shortinfo" : "Краткая инфа о товаре",
	"mainInfo" : "Подробная инфа о товаре",
	"image" : ["imagePath_1", "ImagePath_2", "ImagePath_3"],
	"tableHead" : ["Характеристика_1", "Характеристика_2", "Характеристика_3"],
	"tableData" : ["инфа о Характеристике_1", "инфа о Характеристике_2", "инфа о Характеристике_3"]
}, {
	"type" : "0",
	"mark" : "0",
	"model" : "1",
	"price" : "133000",
	"name" : "Авто_3",
	"shortinfo" : "Краткая инфа о товаре",
	"mainInfo" : "Подробная инфа о товаре",
	"image" : ["imagePath_1", "ImagePath_2", "ImagePath_3"],
	"tableHead" : ["Характеристика_1", "Характеристика_2", "Характеристика_3"],
	"tableData" : ["инфа о Характеристике_1", "инфа о Характеристике_2", "инфа о Характеристике_3"]
}*/];

// dtype.js
var dType = [{
	"type" : "Легковая",
	"model" : ["1Model1", "1Model2", "1Model3"]
}, {
	"type" : "Внедорожник",
	"model" : ["2Model1", "2Model2", "2Model3"]
}, {
	"type" : "Малогрузная",
	"model" : ["3Model1", "3Model2", "3Model3"]
}, {
	"type" : "Большегрузная",
	"model" : ["4Model1", "4Model2"]
}, {
	"type" : "Автобус",
	"model" : ["4Model1", "4Model2"]
}, {
	"type" : "Микроавтобус",
	"model" : ["4Model1", "4Model2"]
}, {
	"type" : "Сельхозтехника",
	"model" : ["1Model1", "1Model2", "1Model3"]
}, {
	"type" : "Спецтехника",
	"model" : ["2Model1", "2Model2", "2Model3"]
}]

function jgetBrand(callback) { callback(dBrand); }

function jgetType(callback) { callback(dType); }

//Нумерация: id входящий == id принимаемый.
//id входящий формируется в цикле.
getByMark = function(id) {
	jgetBrand(function(dbrand) {
		tmp = dbrand[id];
		callback(tmp);
	});
};

//Нумерация: id входящий % 100 - 1 == id принимаемый.
//Формирование id на входе: '1234' --
//'1' Menu level 1,
//'2' Menu level 2,
//'34' (Menu level 3) - 1 == id принимаемый
getByType = function(id/*, callback*/) {
	jgetType(function(dtype) {
		tmp = dtype[id];
		//alert(tmp.type);
		window.location = "list.html";
	});
};
/*ololo = function(data) {
	alert(data.type);
// };*/

