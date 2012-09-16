<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__);

        $fullpath= dirname(__FILE__);
        $dirpath= explode("bazisvostokmed.ru", $fullpath);
 ?>

<script type="text/javascript" src="/libraries/jquery-1.8.1.min.js"></script>
<script type="text/javascript">
            var $j = jQuery.noConflict();

$j(document).ready(function() {
    $j('div.vmCartContainer input[type="submit"]').attr('value', 'В корзину')
                           .css('background', 'url("<?php echo $dirpath[1] ?>/../../images/add-to-cart_green.gif") no-repeat 50%')
                           .css('color', 'white')
                           .css('border', '0')
                           .css('width', '150px')
                           .css('height', '30px')
                           .css('cursor', 'pointer');

    $j('.prod_desc td').css('background-color', 'transparent');
    $j('.prod_desc th').css('background-color', 'transparent');
    $j('.prod_desc tr').filter(":odd").css('background-color', 'white');
    $j('.prod_desc tr').filter(":even").css('background-color', '#CCE0D6');
    $j('.prod_desc table').css('border', '#009049 outset 2px');
});
</script>

<div>
     <div style="width: 50%; display: block; float: left;">
          <h1><?php echo $product_name ?> <?php echo $edit_link ?></h1>
    </div>
    <div align="middle" style="width: 50%; display: block; float: left; vertical-align: middle">
          <?php echo $addtocart; ?>               
    </div>
</div>
<div style="clear: both"></div>
<hr align="left" width="500" size="2" color="#afdfef" />
<div align="top" style="position: relative">   
        <div style="width: 25%; display: block; float: left;">
            <p>
                <h2 style="font-size: 1.5em">Стоимость: <?php echo $product_price ?></h2>
            </p>
            <p><?php echo $ask_seller ?></p>
	<!--<?php echo urldecode( $product_image ) ?>-->
            <a href="/components/com_virtuemart/shop_image/product/<?php echo $product_full_image ?>" title="Unsigned" rel="lightbox[product22]">
                <img src="/components/com_virtuemart/shop_image/product/<?php echo $product_full_image ?>" style="width: 180px; height:150px;">
            </a>
            <br />	
            <?php echo $this->vmlistAdditionalImages( $product_id, $images ) ?>
        </div>
        <div style="width:75%; position: relative; display: block; float:left;">
	 <?php
         jimport('joomla.html.pane');
         $myTabs = & JPane::getInstance('tabs', array('startOffset'=>0));
         $output = '';
         $output .= $myTabs->startPane( 'pane' );

         //1 вкладка
         $output .= $myTabs->startPanel( '<span>Описание</span>', 'tab1' ); // добавляем вкладку с заголовком «Описание»
         $output .= '<div class="prod_desc">'.$product_description.'</div>'; // выводим в контейнер под вкладкой описание товара из переменной                            $product_description
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
</div>
<div style="clear: both"></div>
<!--</tr>
</table>-->