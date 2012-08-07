//
// get *.json into var
//
jgetBrand = function(callback) {
	$j.ajax({
		url : "module/dbrand.json",
		dataType : 'text',
		success : function(data) {
			tmp = eval('(' + data + ')');
			callback(tmp);
		}
	});
}
jgetType = function(callback) {
	$j.ajax({
		url : "module/dtype.json",
		dataType : 'text',
		success : function(data) {
			tmp = eval('(' + data + ')');
			callback(tmp);
		}
	});
}
jgetData = function(callback) {
	$j.ajax({
		url : "module/ddata.json",
		dataType : 'text',
		success : function(data) {
			tmp = eval('(' + data + ')');
			callback(tmp);
		}
	});
}
//
// SELECT data FROM json_var BY other_data
//

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
ololo = function(data) {
	alert(data.type);
}; 