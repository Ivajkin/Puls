if (jQuery('#page-title').text() == "Blogs")          
    jQuery('#page-title').css('display', 'none');
if ( jQuery('.pager-next a').text().search('next') != -1) jQuery('.pager-next a').text('вперёд');
if ( jQuery('.pager-next a').text().search('prev') != -1) jQuery('.pager-next a').text('назад');
if ( jQuery('.pager-next a').text().search('last') != -1) jQuery('.pager-next a').text('в конец');
if ( jQuery('.pager-next a').text().search('first') != -1) jQuery('.pager-next a').text('в начало');