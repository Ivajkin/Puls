<?php

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
 *  * GACKT / do as infinity / coldrain
 * @ingroup views_templates
 */
?>

<?php
$view->result[0]->_field_data['custom'][0]= &$view->result[0];
$cf= &$view->result[0]->_field_data['custom'];
?>

<?php //foreach ($fields as $id => $field): ?>
<!--    --><?php //if (!empty($field->separator)): ?>
<!--        --><?php //print $field->separator; ?>
<!--    --><?php //endif; ?>
<!---->
<!--    --><?php //print $field->wrapper_prefix; ?>
<!--    --><?php //print $field->label_html; ?>
<!--    --><?php //print $field->content; ?>
<!--    --><?php //print $field->wrapper_suffix; ?>
<?php //endforeach; ?>

<div class="popular-container">
    <section class="popular clearfix index">
        <header class="wrapper">
            <div></div>
            <h2>Популярные сборники</h2>
        </header>
        <div class="content">
            <article class="wrapper clearfix">
                <?php foreach ($cf as $data) : ?>
                <section>
                    <h4><?php print $data->commerce_product_field_data_commerce_product_title; ?></h4>
                    <?php print $data->field_field_text[0]['rendered']; ?>
                </section>
                <?php endforeach; ?>
                <footer>
                    <img src="/sites/all/themes/omega_d1/images/pin.png" alt="Текущая галлерея">
                </footer>
            </article>
        </div>
        <div class="slider wrapper clearfix">
            <div class="slider-viewport">
                <div class="bxslider">
                    <?php
                        $tmp= array();
                        $tmp2= array();
                        print $cf[0]->field_field_image[0]['rendered'];

                        foreach($cf as $i => $data) {
                            $tmp []= $data->field_field_image[0]['rendered'];
                            $tmp2 []= '/catalog/'.mb_strtolower(str_replace(' ', '-', $data->field_product_ref_commerce_product_title.'/'.$data->commerce_product_field_data_commerce_product_title));
                        }
                        drupal_add_js(array('imgrow' => $tmp, 'imgpath' => $tmp2), 'setting');
                    ?>
                </div>
                <div class="time-indicator"></div>
            </div>
        </div>
    </section>
</div>

<?php
$tt=0;
?>