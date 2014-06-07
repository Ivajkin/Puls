/**
 * Created by storm on 5/2/14.
 */
(function ($) {
    $('ul.products li a').not('ul.product-caregory-top li a').click(function(e){
        e.preventDefault();

        $('body').append(
            '<div id="product-view" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userinfoLabel" aria-hidden="true" style="display: none;">' +
                '<div class="modal-dialog">' +
                    '<div class="modal-content">' +
                        '<div class="modal-header">' +
                            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
                            '<h3 class="modal-title" id="userinfoLabel">Подробно</h3>' +
                        '</div>' +
                        '<div class="modal-body">' +
                        '</div>' +
                        '<div class="modal-footer">' +
                            '<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>' +
                        '</div>' +
                    '</div><!-- /.modal-content -->' +
                '</div><!-- /.modal-dialog -->' +
            '</div>'
        );

        linkpath= $(this).attr('href');
        $(this).before('<img class="wait-img" src="'+location.origin+'/wp-content/themes/bones/library/images/wait000.gif" style="width:50px; height: 50px;"/>');

        $.ajax({
            type: "POST",
            url: linkpath,
            success: function(data){
                $('.wait-img').remove();
                $('#product-view .modal-body').append(data);
                $('#product-view header.header, #product-view footer.footer').remove();


                $('#product-view').modal({keyboard: false})
                .on('hidden.bs.modal', function (e) {
                    $('#product-view').remove();
                })
            }
        });

    });
})(jQuery);