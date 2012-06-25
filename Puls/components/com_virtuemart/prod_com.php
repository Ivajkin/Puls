<?php global $mainframe;

$product_reviews = $product_reviewform = "";
/* LIST ALL REVIEWS **/
if (PSHOP_ALLOW_REVIEWS == '1') {
	/*** Show all reviews available ***/
	//$product_reviews = ps_reviews::product_reviews( $product_id );
	/*** Show a form for writing a review ***/
	$comments = $mosConfig_absolute_path . '/components/com_jcomments/jcomments.php';
	$cl_rew = $mosConfig_absolute_path . '/administrator/components/com_virtuemart/classes/ps_reviews.php';
  if (file_exists($comments) && file_exists($cl_rew)) {
    require_once($comments);
    require_once($cl_rew);
    $product_reviews = JComments::showComments($product_id, 'com_virtuemart', $product_name);
    $product_reviewform = "";
    $pr_com_count = JComments::getCommentsCount($product_id, 'com_virtuemart');
  }	
  if( $auth['user_id'] > 0 ) {
		//$product_reviewform = ps_reviews::reviewform( $product_id );
                $product_reviewform = "";
  }
}

function getData ($id, $prod_price) {
  //$db->query( 'SELECT `id`, FROM `#__product`');
  //$tmp= $db->next_record();
   $db =& JFactory::getDBO();
   $query = "SELECT * FROM #__vm_product WHERE product_id = ".$db->quote($id) ;
   $db->setQuery($query);
   $data= $db->loadAssoc();
   
   /*$query = "SELECT file_name FROM #__vm_product_files WHERE (product_id = ".$db->quote($id).") AND (file_is_image = 1)" ;
   $db->setQuery($query);
   $data['imgs']= $db->loadResultArray();

   $data['price']= true_look($prod_price);*/

   $data['product_desc']= true_look($data['product_desc']);
   $data['product_s_desc']= true_look($data['product_s_desc']);
   $tmp= json_encode($data);
   return $data['product_desc'];
}

function true_look($some) {
   $tmp= str_replace(array("\n", "\r", "\n\r", "&nbsp&nbsp"), "", $some); //htmlentities addslashes
   return $tmp;
}
?>