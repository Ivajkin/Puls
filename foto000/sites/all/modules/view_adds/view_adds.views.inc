<?php

/*function view_adds_views_pre_build(&$view) {
    if ($view->name == "gallery") {

    }
}*/

function view_adds_views_post_execute(&$view) {
    if ($view->name == "gallery") {
        //$img= array();
        foreach ($view->result as $i => $row) {
            $img_styled= '';
            foreach (explode('src="', $row->field_field_image[0]['rendered']) as $j => $col) {
                if ($j % 2) {
                    $tmp= explode('" ', $col, 2)[0];
                    preg_match('/catalog.*/', $tmp, $tmp2);
                    $tmp3= image_style_url('medium', 'public://'.urldecode($tmp2[0]));

                    $img_styled .= '<div class="field--image" data-full="'. $tmp .'"> <img typeof="foaf:image" src="' . $tmp3 . '"/> </div>';
                }
            }
            $view->result[$i]->field_field_image[0]['rendered']= $img_styled;
            $tt=0;
        }
        $tt=0;
        //drupal_add_js('(function ($) { Drupal.settings })(jQuery);', 'inline');
        //drupal_add_js(array('gallery' => $img), 'setting');
    }
}
