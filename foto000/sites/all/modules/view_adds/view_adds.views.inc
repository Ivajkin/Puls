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
    return;
    }
    if ($view->name == "catalog") {
        $tt=0;
        $out= array();
        foreach ($view->result as $data) {
            if (!isset($out[$data->tid])) {
                $out[$data->tid]= array();
                $out[$data->tid]['title']= $data->taxonomy_term_data_name;
                $out[$data->tid]['place']= json_decode($data->field_field_location_1[0]['raw']['placemarks'])[0]->coords;

                $out[$data->tid]['product']= array();
                $out[$data->tid]['route']= array();
            }
            //$data->field_place_ref_taxonomy_term_data_nid;
            $pid= $data->commerce_product_field_data_field_product_ref_product_id;
            if (isset($pid) && !isset($out[$data->tid]['product'][$pid])) {
                $out[$data->tid]['product'][$pid]= array();
                $out[$data->tid]['product'][$pid]['disp_title']= $data->field_place_ref_taxonomy_term_data_title;
                $out[$data->tid]['product'][$pid]['title']= $data->commerce_product_field_data_field_product_ref_title;
                $out[$data->tid]['product'][$pid]['img']= $data->field_field_image[0]['rendered'];
            }
            $rid= $data->commerce_product_field_data_field_route_ref_product_id;
            if (isset($rid) && !isset($out[$data->tid]['product'][$rid])) {
                $out[$data->tid]['route'][$rid]= array();
                $out[$data->tid]['route'][$rid]['disp_title']= $data->field_place_ref_taxonomy_term_data_title;
                $out[$data->tid]['route'][$rid]['title']= $data->commerce_product_field_data_field_route_ref_title;
                $tmp= image_style_url('product_thumbnails', $data->field_field_image_1[0]['raw']['uri']);
                $out[$data->tid]['route'][$rid]['img']= '<div class="field--image"> <img typeof="foaf:image" src="' . $tmp . '"/> </div>';
            }


        }
        $tt=0;
        drupal_add_js(array('catalog' => $out), 'setting');
        return;
    }
    if ($view->name == "catalog_full" && $view->current_display == "catalog_full") {
        $tt=0;
        $targ= array();
        foreach ($view->result as $data) {
            if (isset($data->commerce_product_field_data_field_route_ref_title))
                $targ []= $data->commerce_product_field_data_field_route_ref_title;
        }
        $tmp= $view->result[0];
        $view->result= '';
        $view->result= array();
        $view->result []= $tmp;
        $view->result[0]->commerce_product_field_data_field_route_ref_title= implode('+++' ,$targ);

        return;
    }
    if ($view->name == "top_product" && $view->current_display == "top_pane") {
        $view->result[0]->_field_data['custom']= array();
        $tmp= &$view->result[0]->_field_data['custom'];
        foreach($view->result as $i => $data) {
            if (!$i) {$tmp[]= ''; continue;}
            $tmp[]= $data;
        }
        $tmp2= $view->result[0];
        $view->result= '';
        $view->result= array();
        $view->result []= $tmp2;

        drupal_add_js(array('ismain' => '.view-display-id-top_pane .field--image'), 'setting');
        return;
    }
}
