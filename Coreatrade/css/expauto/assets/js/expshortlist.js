
/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

function expshortlist(val,valid,text){
    var valurl = 'index.php?option=com_expautospro&view=expuser&format=ajax&expshortlist=1&expval='+val+'&expid='+valid;
    var mygetrequest=new ajaxRequest();
    mygetrequest.onreadystatechange=function(){
        if (mygetrequest.readyState==4){
            if (mygetrequest.status==200 || window.location.href.indexOf("http")==-1){
                var link = document.getElementById('expshortlist'+valid);
                var clearlink = document.getElementById('expmodshortlist_clearlink');
                var modshort = document.getElementById('expmodshortlist');
                modshort.innerHTML=mygetrequest.responseText
                if(text){
                    link.innerHTML=text
                }else{
                    clearlink.innerHTML=''  
                }
            }
            else{
                alert("An error has occured making the request")
                //i.style.display='none'
            }
        }
    }
    mygetrequest.open("GET", valurl, true)
    mygetrequest.send(null)
}

function get_check_value()
{

}
