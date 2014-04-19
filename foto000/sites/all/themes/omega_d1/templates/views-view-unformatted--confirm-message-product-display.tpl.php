<?php
/**
 * Created by PhpStorm.
 * User: storm
 * Date: 4/16/14
 * Time: 1:25 AM
 */

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>

<div id="userinfo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userinfoLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="userinfoLabel">Товар успешно добавлен в корзину!</h3>
            </div>
            <div class="modal-body">
                <?php if (!empty($title)): ?>
                    <h3><?php print $title; ?></h3>
                <?php endif; ?>
                <?php foreach ($rows as $id => $row): ?>
                    <div<?php if ($classes_array[$id]) { print ' class="' . $classes_array[$id] .'"';  } ?>>
                        <?php print $row; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary">В корзину</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    Drupal.theme('atcloaded');
</script>