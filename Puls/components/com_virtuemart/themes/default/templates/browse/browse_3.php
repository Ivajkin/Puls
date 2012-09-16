<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__);
?>

<!--<div style="word-wrap: break-word">-->
<?php 
/*var_dump($product_id);
var_dump($product_name );
var_dump($product_price);
var_dump($product_full_image);
var_dump($product_desc);
var_dump($product_reviews);
var_dump($product_reviewform);
var_dump($images);*/

/*global $mainframe;
$comments = $mosConfig_absolute_path . '/components/com_jcomments/jcomments.php';
if (file_exists($comments)) {
     require_once($comments);
     $coment_count = JComments::getCommentsCount($product_id, 'com_virtuemart');
     $product_reviews = JComments::showComments($product_id, 'com_virtuemart', $product_name);
     $product_reviewform = '';
}echo $product_reviews;*/

$strtmp = <<<EOD
<script type='text/javascript'>
var jcomments=new JComments(21, 'com_virtuemart','/paradigm/Puls/index.php?option=com_jcomments&amp;tmpl=component');
jcomments.setList('comments-list');
</script>
EOD;
?>
<!--</div>-->

 <div class="browseProductContainer" style="padding:0; margin:0; border:0;">
     <a href="javascript:void(0)" title="<?php echo $product_name?>" style="text-align: center; margin-left: 25%" onclick="moreinfoDown(<?php echo $product_id ?>)" >
           <img src="<?php echo $product_full_image ?>" style="width: auto; height:150px;" />
     </a>  
     <h2 style="text-align: center; font-size: 1.5em">
           <a class="browse_expand" style="word-wrap: break-word;" href="javascript:void(0)" onclick="moreinfoDown(<?php echo $product_id ?>)"> <!--font-size:16px; font-weight:bold; -->
           <?php echo $product_name ?>
          </a>
    </h2>
    <p class="tm_price"><?php echo $product_price ?></p>

  <br style="clear:both;" />

   <div id="moreinfo_<?php echo $product_id ?>" style="display:none; padding: 0; border: 0; margin: 20px 0;">

     <div>
       <div style="width: 50%; display: block; float: left;">
         <h1> <!--style="font-size: 20px; line-height: 20px"-->
           <?php echo $product_name ?>
           <?php echo $edit_link ?>
         </h1>
       </div>
       <div align="middle" style="width: 40%; display: block; float: left; vertical-align: middle; margin-left: 20px">
         <?php echo $form_addtocart ?>
       </div>
     </div>
     <div style="clear: both"></div>
     <hr align="left" width="500" size="2" color="#afdfef" />
     <div align="top" style="position: relative">
       <div style="width: 26%; display: block; float: left;">
         <p>
           <h2 style="font-size: 1.5em">
             Стоимость: <?php echo $product_price ?>
           </h2>
         </p>
         <p>
           <a class="button" href="<?php echo $ask_seller_href; ?>"><?php echo $ask_seller_text; ?></a>
         </p>
         <!--<?php echo urldecode( $product_image ) ?>-->
         <a href="<?php echo $product_full_image ?>" title="<?php echo $product_name?>" rel="lightbox[product22]">
           <img src="<?php echo $product_full_image ?>" style="width: 180px; height:150px;" />
         </a>
         <br />
         <?php echo $this->vmlistAdditionalImages( $product_id, $images ) ?>
       </div>
       <div style="width:74%; position: relative; display: block; float:left;">
         <?php
         jimport('joomla.html.pane');
         $myTabs = & JPane::getInstance('tabs', array('startOffset'=>0));
         $output = '';
         $output .= $myTabs->startPane( 'pane' );

         //1 вкладка
         $output .= $myTabs->startPanel( '<span>Описание</span>', 'tab1' ); // добавляем вкладку с заголовком «Описание»
         $output .= '<div >'.$product_desc.'</div>'; // выводим в контейнер под вкладкой описание товара из переменной                            $product_description
         $output .= $myTabs->endPanel();

         // Создаем 2 вкладку
         $output .= $myTabs->startPanel( 'Вконтакте', 'tab2' );
         $output .= '<div id="vk_comments_prod'.$product_id.'"></div>'.
              '<script type="text/javascript">'.
                 'VK.Widgets.Comments("vk_comments_prod'.$product_id.'", {limit: 10, width: "500", attach: "*"},'.$product_id.');'.
              '</script>';
         $output .= $myTabs->endPanel();
       
         // Создаем 3 вкладку
         $output .= $myTabs->startPanel( '<span>Facebook</span>', 'facebook_com' );
         $output .= '<div class="facebook_com"><div class="fb-comments" data-href="http://bazisvostokmed.ru/index.php#pdoduct='.$product_id.'" data-num-posts="10" data-width="500"></div><div id="fb-root"></div></div>';
         $output .= $myTabs->endPanel();

         $output .= $myTabs->endPane();
         echo $output;
        ?>
       </div>
       <button type="button" class="browse_expand less tm_button_slideup" onclick="moreinfoUp(<?php echo $product_id ?>)">Свернуть <<< </button>
     </div>
   </div>
 </div>