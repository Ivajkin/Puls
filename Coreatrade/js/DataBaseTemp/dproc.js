//
// get *.json into var
//
jgetBrand = function (callback) {
    $j.ajax({
        url: "/js/dbrand.js",
        dataType: 'text',
        success: function (data) { tmp = eval('(' + data + ')'); callback(tmp); }
    });
}

jgetType = function (callback) {
    $j.ajax({
        url: "/js/dtype.js",
        dataType: 'text',
        success: function (data) { tmp = eval('(' + data + ')'); callback(tmp); }
    });
}
jgetData = function (callback) {
    $j.ajax({
        url: "/js/ddata.js",
        dataType: 'text',
        success: function (data) { tmp = eval('(' + data + ')'); callback(tmp); }
    });
}

jgetDataIndexT = function (first, end, callback) {
    $j.ajax({
        type: "GET",
        url: "/admin/DataBaseOps/getOperations.php",
        data: { fisrt: first, end: end, operation: 'car' },
        dataType: 'text',
        success: function (data) { tmp = eval('(' + data + ')'); callback(tmp); }
    });
}
jgetDataIndex = function (callback, first, end) {
    $j.ajax({
        type: "GET",
        url: "/admin/DataBaseOps/getOperations.php",
        data: { first: first, end: end, operation: 'car' },
        dataType: 'text',
        success: function (data) { tmp = eval('(' + data + ')'); callback(tmp); }
    });
}

//
// SELECT data FROM json_var BY other_data
//

//Нумерация: id входящий == id принимаемый. 
//id входящий формируется в цикле.
getByMark = function (id, callback) {
    jgetBrand(function (dbrand) {
        tmp = dbrand[id];
        callback(tmp);
    });
};

//Нумерация: id входящий % 100 - 1 == id принимаемый.
//Формирование id на входе: '1234' -- 
//'1' Menu level 1, 
//'2' Menu level 2, 
//'34' (Menu level 3) - 1 == id принимаемый
getByType = function (id, callback) {
    jgetType(function (dtype) {
        tmp = dtype[id];
        callback(tmp);
        //window.location = "list.html";
    });
};


getMarkId = function (name, callback) {
    jgetBrand(function (dbrand) {
        tmp = -1;
        for (i = 0; i < dbrand.length; i++)
            if (dbrand[i].mark == name) {
                tmp = i;
                break;
            }
        callback(tmp);
    });
};

getTypeId = function (name, callback) {
    jgetType(function (dtype) {
        tmp = -1;
        for (i = 0; i < dtype.length; i++)
            if (dtype[i].type == name) {
                tmp = i;
                break;
            }
        callback(tmp);
        //window.location = "list.html";
    });
};


ololo = function (data) { alert(data.type); };