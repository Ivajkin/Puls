/******Common function*****/

function include(file, id) {

    var script = document.createElement('script');
    script.src = file;
    script.type = 'text/javascript';
    script.defer = true;

    document.getElementById(id).appendChild(script);

}

function loadScript(url, callback) {
    $j.ajax({
        url: url,
        dataType: 'text',
        success: function (data) { tmp = eval('(' + data + ')'); callback(tmp); }
    });
}

// UTF-8 data encode (php style)

var Utf8 = {
    utf8str : "\u0430\u0431\u0432\u0433\u0434\u0435\u0451\u0436\u0437\u0438\u0439\u043a\u043b\u043c\u043d\u043e\u043f\u0440\u0441\u0442\u0443\u0444\u0445\u0446\u0447\u0448\u0449\u044a\u044b\u044c\u044d\u044e\u044f\u0410\u0411\u0412\u0413\u0414\u0415\u0401\u0416\u0417\u0418\u0419\u041a\u041b\u041c\u041d\u041e\u041f\u0420\u0421\u0422\u0423\u0424\u0425\u0426\u0427\u0428\u0429\u042a\u042b\u042c\u042d\u042e\u042f\u2116",
    rustr : "абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ№",
    convert: function (str) {
        inarr = str.split("");
        
        indarr = 0;
        indstr = 0;
        while (indarr != str.length-1) { // && indstr != this.rustr.length-1
            while (1>0) {
                tmp = inarr.indexOf(this.rustr.charAt(indstr));
                if (tmp == -1) break;
                inarr[tmp] = this.utf8str.charAt(indstr);
                indarr++
            }
            indstr++;
        }
        outstr = inarr.join("");
        return outstr;
    }
}