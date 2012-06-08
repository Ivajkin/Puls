<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__);
 //Загрузка картинки
 ?>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
<p width="33%" rowspan="<?php echo $rowspan; ?>" valign="top"><br/>
	  	<?php echo urldecode( $product_image ) ?><?php echo $this->vmlistAdditionalImages( $product_id, $images ) ?></p> 
</td>
<td width="50">
&nbsp;
</td>
<td>
<?php echo $addtocart; ?>
</td>
</tr>
</table>

<?php
jimport('joomla.html.pane');
$myTabs = & JPane::getInstance('tabs', array('startOffset'=>0));
$output = '';
$output .= $myTabs->startPane( 'pane' );

//1 вкладка
$output .= $myTabs->startPanel( '<span>Описание</span>', 'tab1' ); // добавляем вкладку с заголовком «Описание»
$output .= '<div >'.$product_description.'</div>'; // выводим в контейнер под вкладкой описание товара из переменной $product_description
$output .= $myTabs->endPanel();

//2 вкладка
$output .= $myTabs->startPanel( '<span>Отзывы</span>', 'tab2' ); // добавляем вкладку с заголовком «отзывы»
$output .= '<div >'.$product_reviews.'<br>'.$product_reviewform.'</div>'; // выводим в контейнер под вкладкой отзывы о товаре из переменной $product_reviews и форму добавления отзывов из переменной $product_reviewform
echo $output;

$output .= $myTabs->endPanel();

//3 вкладка
$utput .= $myTabs->startPanel( '<span>Дополнительные изображения</span>', 'tab3' ); // добавляем вкладку с заголовком «Дополнительные изображения»

$utput .= $myTabs->endPane();
echo $utput;
?> 