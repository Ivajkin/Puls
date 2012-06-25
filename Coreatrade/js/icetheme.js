/**
 * ***************** icetheme.js ********************
 * 
 * @author LandOfCoder 
 * @project IceTheme demo template application
 * 
 */

$(document).ready(function() { 
    
    //preparing some data :
    var list = $('.list_themes li');
    var popup = $('#popup');
    var overlay = $('#main_view_overlay');
    var mainview = $('#show_demo_page');
    var loadingBar = $('#mainview_loading_icon');
    var demoView = $('#main_content');
    var realHeight = $(window).height()-50;
    //add state loading to mainview :
    mainview.addClass('loading_demo');
    
    //set width is full screen :
    $('#wrapper').css('width', $(window).width());
    $('#wrapper').css('height', $(window).height());
    
    
    
    //set height is full :
    demoView.css('height', realHeight);
    
    //hide popup and overlay layer :
    popup.hide();
        
    list.each(function(idx, item){
        var image = $(item).find('.hiden_data .demo_img').text();
        var title = $(item).find('.hiden_data .demo_title').text();
		var date_s = $(item).find('.hiden_data .demo_date_s').text();
        var date = $(item).find('.hiden_data .demo_date').text();
        var desc = $(item).find('.hiden_data .demo_desc').text();
        var link = $(item).find('.hiden_data .demo_link').text();
        
        //When hover theme name in list :
        $(item).hover(
            function () {
                $('#detail_title').html(title);
                $('#detail_date').html(date);
                $('#detail_image').attr('src',image);
                $('#detail_desc').html(desc);
                $('#loading_icon').css('display', 'block');
                $('#lof_image_overlay').css('display', 'block');
                $('#theme_details').css('display', 'block');
            },
            function () {
                $('#theme_details').hide();
            }
            );
        
        //when click theme name in list :
        $(item).click(function(){
            mainview.addClass('loading_demo');
            mainview.attr('src', link);
            popup.hide()
            loadingBar.show();
            $('#selectbox_theme').text(date_s+' - '+title);
        });
        
    });
    
    //When website demo (iframe) just loaded :
    mainview.load(function(){
        overlay.fadeOut(); 
        loadingBar.hide();        
        mainview.removeClass('loading_demo');
    });
    
    //when click overlay layer (its mean click anywhere out of popup) and demo already loaded :
    overlay.click(function(){
        if(mainview.hasClass('loading_demo') == false) {
            overlay.fadeOut();
            popup.fadeOut();
        }
    });  

    //when click selector and demo already loaded :
    $('#select_theme').click(function(){
        if(mainview.hasClass('loading_demo') == false) {
            popup.fadeToggle();
            overlay.toggle();       
        }
    });        
    
    
    //When image just loaded :
    $('#detail_image').load(function(){
        $('#loading_icon').hide();
        $('#lof_image_overlay').fadeOut();
    });
});
function toggleFrame(){
    var button = $('#btn_panel');
    var page = $('#main_content');
    
    var currentHeight = parseInt(page.css('height')); 
    var currentText = button.text().toLowerCase();
    $('#frame').slideToggle('slow', function(){
        if(currentText == 'hide') {
            button.text('Show'); 
            page.css('height', parseInt(currentHeight+50)+'px');
        } else {
            button.text('Hide');
            page.css('height', parseInt(currentHeight-50)+'px');
        }
        
    });
}