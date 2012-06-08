<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__); ?>

<?php echo $buttons_header // The PDF, Email and Print buttons ?>
<?php echo $browsepage_header // The heading, the category description ?>
<?php echo $parameter_form // The Parameter search form ?>
<?php echo $orderby_form // The sort-by, order-by form PLUS top page navigation ?>

<?php
$data =array(); // Holds the rows of products
$i = 1; $row = 0; // Counters

// Table header
$tableheader[] = $VM_LANG->_('PHPSHOP_CART_NAME');
$tableheader[] = $VM_LANG->_('PHPSHOP_CART_SKU');
if( _SHOW_PRICES && $auth['show_prices'] ) {
	$tableheader[] = $VM_LANG->_('PHPSHOP_CART_PRICE');
}
$tableheader[] = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_THUMB_IMAGE');
$tableheader[] = $VM_LANG->_('PHPSHOP_PRODUCT_DESC_TITLE');
if( _SHOW_PRICES && $auth['show_prices'] && USE_AS_CATALOGUE != '1' ) {
	$tableheader[] = $VM_LANG->_('PHPSHOP_CART_ACTION');
}

// Creates a new HTML_Table object that will help us
// to build a table holding all the products
$table = new HTML_Table('width="100%"');

$table->addRow( $tableheader, 'class="sectiontableheader"', 'th', true );

foreach( $products as $product ) {
		
		foreach( $product as $attr => $val ) {
			// Using this we make all the variables available in the template
			// translated example: $this->set( 'product_name', $product_name );
			$this->set( $attr, $val );
		}
		
		$data[$row][] = '<a href="'.$product['product_flypage'].'" title="'.$product['product_name'].'">'.$product['product_name'].'</a>';
		$data[$row][] = $product['product_sku'];
		if( _SHOW_PRICES && $auth['show_prices'] ) {
			$data[$row][] = $product['product_price'];
		}
		$data[$row][] = '<a href="'.$product['product_flypage'].'" title="'.$product['product_name'].'">'
						. ps_product::image_tag( $product['product_thumb_image'] )
						. '</a>';
		$data[$row][] = $product['product_s_desc'];
		if( $product['has_addtocart'] ) {
			$data[$row][] = $product['form_addtocart'];
		}
		else {
			$data[$row][] = '<a href="'.$product['product_flypage'].'" title="'.$product['product_name'].'">'
							.	$product['product_details']
							.	'</a>';
		}
		
		$row++;
		
}


// Loop through each row and build the table
foreach($data as $key => $value) {
	
	$table->addRow( $data[$key], 'class="sectiontableentry'.$i.'"', 'td', true );
	$i = $i == 1 ? 2 : 1;
}
// Display the table
echo $table->toHtml();
?>
<br class="clr" /><br />
<?php echo $browsepage_footer ?>
<?php 
// Show Featured Products
if( $this->get_cfg( 'showFeatured', 1 )) {
    /* featuredproducts(random, no_of_products,category_based) no_of_products 0 = all else numeric amount
    edit featuredproduct.tpl.php to edit layout */
    echo $ps_product->featuredProducts(true,10,true);
} ?>
<?php echo $recent_products ?>
