/* 
 * @file
 * Scripts helps to work with the multiple fields.
 */

jQuery('document').ready(function ()
{
  jQuery('div.form-wrapper').delegate('.remove_yamap_button', 'click', function ()
  {
    // Get parent table row
    var row = jQuery(this).closest('td').parent('tr');

    // Hide and empty values
    jQuery(row).hide().find('input').val('');

    // Fix table row classes.
    var table_id = jQuery(row).parent('tbody').parent('table').attr('id');
    jQuery('#' + table_id + ' tr.draggable:visible').each(function (index, element)
    {
      jQuery(element).removeClass('odd').removeClass('even');
      if ((index % 2) == 0) {
        jQuery(element).addClass('odd');
      } else {
        jQuery(element).addClass('even');
      }
    });
  });
});
