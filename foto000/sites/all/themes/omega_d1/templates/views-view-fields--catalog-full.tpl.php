<?php
/**
 * Created by PhpStorm.
 * User: storm
 * Date: 4/12/14
 * Time: 11:10 PM
 */

/**
 * @file
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */

//if (!empty($fields['$name']->separator))
//    $out.= $fields['$name']->separator;
//$out.= $fields['$name']->wrapper_prefix;
//$out.= $fields['$name']->label_html;
//$out.= $fields['$name']->content;
//$out.= $fields['$name']->wrapper_suffix;

?>
<?php $thp= drupal_get_path('theme', 'omega_d1'); ?>
<?php switch ($view->current_display):
    case 'catalog_full': ?>
        <h1 class="page-title"><?php print str_replace('-', ' ', arg(2)); ?></h1>
        <article>
            <div class="left">
                <img src="/<?php echo $thp; ?>/images/cd_same.png" alt="Диск" title="" />
                <div class="p-big">
                    <?php
                        print $fields['commerce_price']->content;
                     ?>
                </div>
                <div class="clearfix">
<!--                    <img src="/--><?php //echo $thp; ?><!--/images/basket_origin.png" alt="Корзина" title="" />-->
<!--                    <span>Добавить в корзину</span>-->
                    <?php
                    print $fields['add_to_cart_form']->content;
                    ?>
                </div>
            </div>
            <div class="right">
                <?php
                print $fields['title']->content;

                print $fields['field_text']->content;
                ?>
                <div class="p-small field--screen-list">
                    <a href="#">Посмотреть содержимое
                        <?php
                        if (isset($fields['field_screen_list']->content) && !empty($fields['field_screen_list']->content)) {
                            $tmp= '<' . explode('<', $fields['field_screen_list']->content)[2];
                            print ($tmp);
                        }
                        ?>
                    </a>
                </div>
            </div>
            <div class="simple clearfix"></div>
            <div class="slider clearfix">
                <div class="gl-head clearfix">
                    <h3>Примеры снимков</h3>
                    <a href="/gallery">Перейти в галлерею</a>
                </div>

                <div class="slider-viewport">
                    <div class="bxslider hoverBox hoverBox466">
                        <?php
                            print $fields['field_image']->content;
                        ?>
                    </div>
                    <div class="time-indicator"></div>
                </div>
            </div>
            <div class="hr-line"></div>
            <div class="route">
                <div class="cat-head">
                    <h3>Маршруты по теме</h3>
                </div>
                <?php
                    $tmp= explode('+++', $fields['title_1']->raw);
                    if (empty($tmp)) $tmp[0]= $fields['title_1']->raw;
                    foreach ($tmp as $out)
                        print '<h5><a href="/routes/'. mb_strtolower(str_replace(' ' ,'-' ,$fields['title_2']->raw  .'/'. $out)) .'">'.$out.'</a></h5>';
                ?>
            </div>
        </article>
    <?php   break;
    default: ?>
            <section>
                <div class="img">
                    <img src="/<?php echo $thp; ?>/images/cd_same.png" alt="Диск" title="" />
                </div>
                <div class="text">
                    <?php
                        print $fields['title_1']->content;
                        print $fields['title']->content;

                        print $fields['field_text']->content;
                    ?>
                    </div>
                <div class="buy">
                    <div class="add-to-cart">
                    <?php
                        print $fields['add_to_cart_form']->content;
                    ?>
                    </div>
                    <div class="p-middle">
                    <?php
                        print $fields['commerce_price']->content;
                    ?>
                    </div>
                </div>
            </section>
            <div></div>
    <?php endswitch; ?>