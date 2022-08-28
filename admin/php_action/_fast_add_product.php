<?php
if(!is_logged_in()){
  permisssion_error_redirect_login('login.php');
}
 if (has_permission('customer')) {
   permisssion_error_redirect_login('login.php');
 }

 //----check product by barcode scan----
 $barcode = ((isset($_POST['fetch_barcode']) && $_POST['fetch_barcode'] != '')?sanitize($_POST['fetch_barcode']):'');
 $productQ = $db->query("SELECT * FROM product WHERE barcode = '$barcode' AND user_id = $user_id");
 $product = mysqli_fetch_assoc($productQ);
 $barcodeCount = mysqli_num_rows($productQ);
 //--check barcode is available or not.
 if($barcodeCount > 0){
   $product_id = $product['id'];
   $barcode = $product['barcode'];
   $db->query("INSERT INTO `add_pro_bar`(`user_id`,`product_id`, `barcode`) VALUES ('$user_id','$product_id','$barcode')");
 }

 //barcode scan form--->
   $productQ = $db->query("SELECT * FROM add_pro_bar WHERE user_id = $user_id");
   $product_res = mysqli_fetch_assoc($productQ);
   $bar_count = mysqli_num_rows($productQ);
   $barcode = $product_res['barcode'];

 ?>
