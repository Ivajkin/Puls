
var itPage = 0; //Id's current page
var ifr_h = 0; //Const for correct iframe height
var navid = "#Navigate";
var link_count = 5; //Links Count in #navid
var pageLoaded = undefined;
var sitename = "http://coreatrade.com";
var hwsitename = "http://127.0.0.1";
var sitelocation = "/";
/*function showInfo(id) {
  	$j(itPage).css('display', "none");
  	$j('#Page_' + id).css('display', "inline");
  	itPage = id;
}*/

//Stek for Navigating
var StekInfo = new Class({
    initialize: function (name, prev) {
        this.name = name;
        this.prev = prev;
        this.next = 0;
        this.activ = true;
    }
});

var Stek = new Class({
    initialize: function () {
        this.start = new StekInfo("Новости", 0);
        this.end = this.start;
        this.start.next = this.start;
        this.end.prev = this.end;
        this.cur = 0;
        this.count = 0;
        //this.cash = '<li><a onclick="nav.show(0)" class="pathway"><span>Главная</span></a></li>';
    },

    move: function (id) {
        if (this.count - id < id) {
            tmp = this.end;
            for (i = this.count; i > id; i--) tmp = tmp.prev;
        }
        else {
            tmp = this.start;
            for (i = 0; i < id; i++) tmp = tmp.next;
        }
        return tmp;
    },
    moveup: function (id) {
        tmp = this.start;
        for (i = 0; i < id; i++) tmp = tmp.next;
        return tmp;
    },
    movedown: function (id) {
        tmp = this.end;
        for (i = this.count; i > id; i--) tmp = tmp.prev;
        return tmp;
    },

    add: function (name) {
        this.end.next = new StekInfo(name, this.end);
        $j(navid + ' li').eq(this.cur).removeClass("lastitem");
        $j(navid + ' span').eq(this.cur).removeClass("cur");
        //$j(navid + ' li').eq(this.cur).replaceWith(this.cash);
        $j(navid + ' ol').append('<li class="lastitem"><a class="pathway" onclick="nav.showByName(\'' + name + '\')"><span class="cur">' + name + '</span></a></li>');
        this.end = this.end.next;
        if (this.count != link_count) {
            this.count++;
        }
        else {
            this.start.next = this.start.next.next;
            this.start.next.prev = this.start; 
            $j('.navarray').eq(1).remove();
            $j(navid + ' li').eq(1).remove();
        }
        this.cur = this.count;
        //this.cash = '<li><a  class="pathway" onclick="nav.show(' + this.count + ')"><span>' + name + '</span></a></li>';
    },
    addAt: function (name) {
        this.end = this.move(this.cur);
        this.end.next = 0; 
        for (i = this.cur + 1; i <= this.count; i++) {
            $j('.navarray').eq(this.cur + 1).remove();
            $j(navid + ' li').eq(this.cur + 1).remove();
        }
        $j(navid + ' span').eq(this.cur).toggleClass("cur");
        this.end.next = new StekInfo(name, this.end);
        //$j(navid + ' ol').append(this.cash);       
        $j(navid + ' ol').append('<li class="lastitem"><a class="pathway" onclick="nav.showByName(\'' + name + '\')"><span class="cur">' + name + '</span></a></li>');
        //this.cash = '<li><a  class="pathway" onclick="nav.show(' + (this.cur + 1) + ')"><span>' + name + '</span></a></li>';
        this.end = this.end.next;
        this.count = ++this.cur;
    },

    upActiv: function (id) {
        tmp = this.move(id);
        count = id;
        $j(navid + ' span').eq(this.cur).toggleClass("cur");
        while (!tmp.activ) {
            tmp.activ = true;
            $j(navid + ' span').eq(count).toggleClass("down");
            tmp = tmp.prev;
            count--;
        }
        this.cur = id;
        $j(navid + ' span').eq(this.cur).toggleClass("cur");
    },
    downActiv: function (id) {
        tmp = this.move(id + 1);
        count = id + 1;
        $j(navid + ' span').eq(this.cur).toggleClass("cur");
        while (tmp.activ) {
            tmp.activ = false;
            $j(navid + ' span').eq(count).toggleClass("down");
            tmp = tmp.next;
            count++;
        }
        this.cur = id;
        $j(navid + ' span').eq(this.cur).toggleClass("cur");
    },

    check: function (name) {
        up = this.start;
        down = this.end;
        //result= undefined;
        for (i = 0, j = this.count; i <= j; i++, j--) {
            if (up.name == name) {
                //result = true;
                return i;
            }
            if (down.name == name) {
                //result = false;
                return j;
            }
            up = up.next;
            down = down.prev;
        }
        return -1;
    }
});

var Navigation = new Class({
    initialize: function () {
        this.stk = new Stek();
    },
    add: function (title) {
        id = this.stk.check(title);
        if (id == -1) {
            $j('.navcur').toggleClass('navcur');
            //$j(navid + ' li').eq(this.stk.cur).on("click", { value: this.stk.cur }, function (event) { show(event.data.value); });
            if (this.stk.count == this.stk.cur) {
                this.stk.add(title);
                /*$j('.view_ok').removeClass('view_ok').addClass('view_none');
                $j('.view_none').eq(this.stk.cur).removeClass('view_none').addClass('view_ok');
                $j('.view_ok').load('aboutload.html');*/
            }
            else {
                this.stk.addAt(title);
            }
            return true;
            //$j(navid + ' li').eq(this.stk.cur).off("click");
        }
        this.show(id);
        return false;
    },
    cur: function () { return this.stk.cur; },
    fadeout: function () {
        tmp = this.stk.cur;
        $j('.navarray').eq(tmp).fadeOut("slow", function () {
            $j('.navarray').eq(tmp).css('display', 'none');
        });
    },
    fadein: function (prev, call) {
        next = this.stk.cur;
        $j('.navarray').eq(prev).fadeOut("slow", function () {
            $j('.navarray').eq(prev).css('display', 'none');
            $j('.navarray').eq(next).fadeIn("slow", function () { if (call) call(); });
        });
    },
    show: function (id) {
        prev = this.stk.cur;
        $j('.navcur').toggleClass('navcur');
        $j('.navarray').eq(id).toggleClass('navcur');
        //$j(navid + ' li').eq(prev).on("click", { value: id }, function (event) { show(event.data.value) });
        //$j(navid + ' li').eq(id).off("click");
        (prev > id) ? this.stk.downActiv(id) : this.stk.upActiv(id);
        this.fadein(prev);
    },
    showByName: function (name) {
        tmp = this.stk.check(name);
        (tmp != -1) ? this.show(tmp) : alert('In function showByName('+name+') -- Unknown name');
    }
});

show = function (id) { nav.show(id);}


nav = new Navigation();


/***********Seo Solution***********/

var linkck = ".linkcheckflag";

function fileExist(file){
    return $j.ajax({
        url: file,
        async: false,
        type:'HEAD'
    });
}
linkControl = function () {
    return;
    var obj= undefined;
    var name = undefined;
    var link = undefined;
    var hd = undefined;
    var count = 0;
    var countlimit = 50;

    if (window.opener) {
            setTimeout(function waitload() {
                if (pageLoaded) {
                    uri = parseUri(location.href);
                    obj = uri.queryKey.showobjvar;
                    name = uri.queryKey.shownamevar;
                    link = decodeURIComponent(uri.queryKey.nvar);

                    htmlstaff =
        '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' +
        '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" dir="ltr" >' +
        $j('html').clone(true).html() + '</html>';

                    hlst = htmlstaff.search('MARKSTART');
                    hlnd = htmlstaff.search('MARKEND');
                    htmlstaff = htmlstaff.replace(htmlstaff.substring(hlst, hlnd + 1), "");

                    htmlstaff = htmlstaff.replace(/"css/gi, '"/css');
                    htmlstaff = htmlstaff.replace(/"js/gi, '"/js');
                    htmlstaff = htmlstaff.replace(/"images/gi, '"/images');
                    htmlstaff = htmlstaff.replace(/"pages/gi, '"/pages');

                    htmlstaff = htmlstaff.replace('//ARKEND', 'setTimeout(function(){' +
                            'window.open("'+sitename+'?showobjvar=' + obj + '&shownamevar=' + name + '", "_top");'
                            + '},5000);');
//console.log('link: ' + link);

                    $j.ajax({
                        type: "POST",
                        url: sitelocation + 'pages/load.php',
                        async: false,
                        data: {
                            htmldata: htmlstaff,
                            htmlname: link
                        },
                        success: function (data) {
//console.log('result_name   ' + data + "\n\rwindow.close();");
                            window.close();
                            //alert(decodeURIComponent(data));
                            //window.open('http://127.0.0.1/coreatrade?showobjvar='+ov+'&shownamevar='+nv+', '_top');
                            //showInfo('all', 'Автомобили')
                        }
                    });
                } 
                else if ((typeof pageLoaded) == 'undefined') {
                    $j(window).load(function () {
                        link = 'base';

                        htmlstaff =
            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' +
            '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" dir="ltr" >' +
            $j('html').clone(true).html() + '</html>';

                        hlst = htmlstaff.search('MARKSTART');
                        hlnd = htmlstaff.search('MARKEND');
                        htmlstaff = htmlstaff.replace(htmlstaff.substring(hlst, hlnd + 1), "");

                        htmlstaff = htmlstaff.replace(/"css/gi, '"/css');
                        htmlstaff = htmlstaff.replace(/"js/gi, '"/js');
                        htmlstaff = htmlstaff.replace(/"images/gi, '"/images');
                        htmlstaff = htmlstaff.replace(/"pages/gi, '"/pages');

                        htmlstaff = htmlstaff.replace('//ARKEND', 'setTimeout(function(){' +
                                'window.open("'+sitename+'", "_top");'
                                + '},5000);');
//console.log('link: ' + link);

                        $j.ajax({
                            type: "POST",
                            url: sitelocation + 'pages/load.php',
                            async: false,
                            data: {
                                htmldata: htmlstaff,
                                htmlname: link
                            },
                            success: function (data) {
//console.log('result_name   ' + data + "\n\rwindow.close();");
                                window.close();
                                //alert(decodeURIComponent(data));
                                //window.open('http://127.0.0.1/coreatrade?showobjvar='+ov+'&shownamevar='+nv+', '_top');
                                //showInfo('all', 'Автомобили')
                            }
                        });
                    });
                }
                else
                    setTimeout(waitload, 500);
            }, 500);
        return;
    }
    if ($j(linkck).length) {
        var antid = [];
        antid[0] = { o: '1q1', n: '2q2' };

        setTimeout(function waitlck() {
            if (pageLoaded || (typeof pageLoaded) == "undefined") {
                for (i = 0; i < $j(linkck).length && count < countlimit; i++) {
                    link = $j(linkck).eq(i).attr('href');
//console.log('\n\r i  ' + i + '\n\r');

                    fileExist(link).fail(function () {
                        count++

                        lo = $j(linkck).eq(i).parent().clone(true).html();
                        st = lo.indexOf('showInfo');
                        nd = lo.indexOf(';', st);
                        lo = lo.substring(st, nd);
                        los = lo.split("'");
                        if (los.length == 5) {
                            obj = los[1];
                            name = los[3];
                        }
                        else if (los.length == 3) {
                            obj = parseInt(los[0].substr(los[0].lastIndexOf('(') + 1));
                            name = los[1];
                        }
                        var antic = false;
                        for (j = 0; j < antid.length; j++)
                            if (antid[j].o == obj && antid[j].n == name) { antic = true; break; }
                        if (!antic) {
                            antid.push({ o: obj, n: name });

                            link = link.slice(link.indexOf('/') + 1, link.indexOf('.html'));
                            if (link.search(/[А-я]/) != -1) link = link.translit(); //encodeURIComponent(htname);
console.log(i + "  " + obj + "  " + name + "  " + link);
                            if ((typeof name) == 'string' &&
                                name.search(/[А-я]/) != -1) name = encodeURIComponent(name);

                            if ((typeof obj) != 'undefined' && (typeof name) != 'undefined') {
                                hw = window.open(hwsitename+'?showobjvar=' + obj + '&shownamevar=' + name + '&nvar=' + link, '_blank', 'width=200,height=100');
                            } else
                                hw = window.open(hwsitename, '_blank', 'width=200,height=100');
                        }
                        /*$j(hw).load(function () {
        console.log('link2: ' + link);
                            htmlstaff =
        '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' +
        '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" dir="ltr" >' +
        $j(hw.document).find('html').clone(true).html() + '</html>';
        
                            hlst = htmlstaff.search('MARKSTART');
                            hlnd = htmlstaff.search('MARKEND');
                            htmlstaff = htmlstaff.replace(htmlstaff.substring(hlst, hlnd + 1), "");
        
                            htmlstaff = htmlstaff.replace(/"css/gi, '"../css');
                            htmlstaff = htmlstaff.replace(/"js/gi, '"../js');
                            htmlstaff = htmlstaff.replace(/"images/gi, '"../images');
        
                            if ((typeof obj) != 'undefined' && (typeof name) != 'undefined')
                                htmlstaff = htmlstaff.replace('//ARKEND', 'setTimeout(function(){' +
                                    'window.open("http://127.0.0.1/coreatrade?showobjvar=' + obj + '&shownamevar=' + name + '", "_top");'
                                    + '},3000);');
                            else
                                htmlstaff = htmlstaff.replace('//ARKEND', 'setTimeout(function(){' +
                                    'window.open("http://127.0.0.1/coreatrade", "_top");'
                                    + '},3000);');
        
        console.log('link: ' + link);
                            $j.ajax({
                                type: "POST",
                                url: sitelocation + 'pages/load.php',
                                async: false,
                                data: {
                                    htmldata: htmlstaff,
                                    htmlname: link
                                },
                                success: function (data) {
                                    console.log('result_name   ' + data);
                                    hw.close();
                                    setTimeout(function waitforclose() {
                                        if (hw.closed == true) {
        console.log('wait   exit');
                                            return;
                                        }
                                        else {
        console.log('wait   ' + 100);
                                            setTimeout(waitforclose, 100);
                                        }
                                    }, 100);
                                    //alert(decodeURIComponent(data));
                                    //window.open('http://127.0.0.1/coreatrade?showobjvar='+ov+'&shownamevar='+nv+', '_top');
                                    //showInfo('all', 'Автомобили')
                                }
                            });
                        });*/
                        //$j(linkck).eq(i).removeClass(linkck);
                    });
                }
            } else
                setTimeout(waitlck, 200);
        }, 200);
    }
}

//$j('#right-column').clone().appendTo('.view_ok #inner-column-container');
/***********Ignition***********/
function showInfo(obj, name, call) {
    if ( obj == 'search_advance' && (typeof name) == 'string') {
        if (nav.add(name)) {
            pageLoaded = false;
            $j('#ContentInsert').append('<div class="view navarray navcur" style="display: none;"></div>');
            tableSearch('.navcur');

            nav.fadein(nav.cur() - 1, function () {
                linkControl(obj, name);
            });
        }
        return 7;
    }

    if ((typeof obj) == 'undefined' && (typeof name) == 'undefined') {
        window.open(sitename, "_top");
        return 6;
    }
    if ( (typeof obj) == 'object' && name == 'compare') {
        if (nav.add(name)) {
            pageLoaded = false;
            $j('#ContentInsert').append('<div class="view navarray navcur" style="display: none;"></div>');
            carCompareBase('.navcur', obj);

            nav.fadein(nav.cur() - 1, function () {
                linkControl(obj, name);
            });
        }
        return 5;
    }
    if ((typeof obj) == 'number' && (typeof name) == 'string') {
        if (nav.add(name)) {
            pageLoaded = false;
            $j('#ContentInsert').append('<div class="view navarray navcur" style="display: none;"></div>');
            carMoreInfo('.navcur', obj);

            nav.fadein(nav.cur() - 1, function () {
                linkControl(obj, name);
            });
        }
        return 4;
    }
    if (obj == 'type' && (typeof name) == 'string') {
        if (nav.add(name)) {
            pageLoaded = false;
            $j('#ContentInsert').append('<div class="view navarray navcur" style="display: none;"></div>');
            getTypeId(name, function (id) {
                tableBase('.navcur', -1, id);
            });

            nav.fadein(nav.cur() - 1, function () {
                linkControl(obj, name);
            });
        }
        return 3;
    }
    if (obj == 'brand' && (typeof name) == 'string') {
        if (nav.add(name)) {
            pageLoaded = false;
            $j('#ContentInsert').append('<div class="view navarray navcur" style="display: none;"></div>');
            getMarkId(name, function (id) {
                tableBase('.navcur', id, -1);
            });

            nav.fadein(nav.cur() - 1, function () {
                linkControl(obj, name);
            });
        }
        return 2;
    }
    if (obj == 'all' && (typeof name) == 'string') {
        if (nav.add(name)) {
            pageLoaded = false;
            $j('#ContentInsert').append('<div class="view navarray navcur" style="display: none;"></div>');
            tableBase('.navcur', -1, -1);

            nav.fadein(nav.cur() - 1, function () {
                linkControl(obj, name);
            });
        }
        return 1;
    }
    if ((typeof obj) == 'string' && (typeof name) == 'string') {
        //nav.fadeout();
        if (nav.add(name)) {
            pageLoaded = false;
            /*$j('body').append('<div class="view navarray" style="display: none;"><iframe src="' + obj + '" ></iframe></div>');
            $j('iframe').css('height', $j(window).attr('innerHeight') - ifr_h);
            $j('body').css('height', $j(window).attr('innerHeight'));*/

            $j('#ContentInsert').append('<div class="view navarray navcur" style="display: none;"></div>');
            $j('.navcur').load(obj + '.html', function () {
                //call();
                eval(obj + "Func();");
            });
            
            nav.fadein(nav.cur() - 1, function () {
                linkControl(obj, name);
            });
        }
        return 0;
    }
}

// Twitter panel
function twitshow(twitid, twitpage) {
    tmp = (!twitpage) ? 'cometokorea' : twitpage;
    $j.getScript('js/widget_2.js', function () {
            new TWTR.Widget({
                version: 2,
                type: 'profile',
                rpp: 3,
                interval: 6000,
                width: 180,
                height: 300,
                theme: {
                    shell: {
                        background: '#353535',
                        color: '#ffffff'
                    },
                    tweets: {
                        background: '#353535',
                        color: '#ffffff',
                        links: '#408EDA'
                    }
                },
                features: {
                    scrollbar: false,
                    loop: true,
                    live: true,
                    hashtags: true,
                    timestamp: true,
                    avatars: true,
                    behavior: 'default'
                },
                id: twitid
            }).render().setUser(tmp).start();
        })
}
