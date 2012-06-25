
/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

function ajaxgetchained(valurl,valto,disid){
    if(disid){
        var iclass = document.getElementById(disid)
        iclass.style.display='none'
    }
    var mygetrequest=new ajaxRequest();
    mygetrequest.onreadystatechange=function(){
        if (mygetrequest.readyState==4){
            if (mygetrequest.status==200 || window.location.href.indexOf("http")==-1){
                var d = document.getElementById(valto);
                var jsondata=eval("("+mygetrequest.responseText+")");
                d.options.length=0;
                for (var i=0; i<jsondata.length; i++){
                    d.options[d.options.length] = new Option(jsondata[i].text,jsondata[i].value)
                }
                if(disid){
                    iclass.style.display='block'
                }
            }
            else{
                alert("An error has occured making the request")
                if(disid){
                    iclass.style.display='block'
                }
            }
        }
    }
    mygetrequest.open("GET", valurl, true)
    mygetrequest.send(null)
 
}
