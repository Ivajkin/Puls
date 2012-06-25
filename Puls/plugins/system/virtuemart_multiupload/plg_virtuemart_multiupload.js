window.addEvent('domready', function() {
	var rows  = $$('table.adminlist tr[class^=row]');
        
    if(rows.length == 0)
        return;
    
    rows.each(function(row){
        row     = row.getElements('td')
        
        id      = row[2].getElement('a').getProperty('href').match(/product_id=([0-9]*)/)[1];
        
        cell    = row[3];
        cell.adopt(new Element('br'));
        
        var multiLink = new Element('a', { 
            'onclick'   : "void window.open('" + mod_virtuemart_muliupload_root + "administrator/index3.php?page=product.file_form_multi.php&product_id=" + id + "&no_menu=1&option=com_virtuemart', '_blank', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=540,directories=no,location=no,screenX=100,screenY=100');return false;",
            'href'      :  mod_virtuemart_muliupload_root + "administrator/index3.php?page=product.file_form_multi.php&product_id=" + id + "&no_menu=1&option=com_virtuemart"
            });
        multiLink.adopt( new Element('img', { 'border': '0', 'align': 'middle', 'src': mod_virtuemart_muliupload_root + 'includes/js/ThemeOffice/backup.png'}));
        
        cell.adopt(multiLink.appendText(' Multi'));

    });

});
