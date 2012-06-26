
/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

function ajaxgetpost(text){
    var i = document.getElementById("expautos_mailimg")
    i.style.display='block'
    var mygetrequest=new ajaxRequest()
    mygetrequest.onreadystatechange=function(){
        if (mygetrequest.readyState==4){
            if (mygetrequest.status==200 || window.location.href.indexOf("http")==-1){
                var d = document.getElementById("expautos_post_result")
                if(mygetrequest.responseText == "1"){
                var c = document.getElementById("expautos_mail_form")
                d.className = "expautos_send_ok"
                c.className = "expautos_displaynone"
                d.innerHTML=text
                }else{
                d.className = "expautos_send_no"
                d.innerHTML=mygetrequest.responseText
                }
                i.style.display='none'
            }
            else{
                alert("An error has occured making the request")
                i.style.display='none'
            }
        }
    }
    var expsender_name      =encodeURIComponent(document.getElementById("expsender_name").value)
    var expsender_phone     =encodeURIComponent(document.getElementById("expsender_phone").value)
    var expsender_email     =encodeURIComponent(document.getElementById("expsender_email").value)
    var expmessage          =encodeURIComponent(document.getElementById("expmessage").value)
    var expid               =encodeURIComponent(document.getElementById("expid").value)
    mygetrequest.open("GET", "index.php?option=com_expautospro&view=expdetail&format=ajax&task=expsellerpost&expsender_name="+expsender_name+"&expsender_phone="+expsender_phone+"&expsender_email="+expsender_email+"&expmessage="+expmessage+"&expid="+expid, true)
    mygetrequest.send(null)
 
}