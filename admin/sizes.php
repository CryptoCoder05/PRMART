<?php
 require_once '../core/init.php';
 if(!is_logged_in()){
   permisssion_error_redirect_login('login.php');
 }
  if (has_permission('customer')) {
    permisssion_error_redirect_login('login.php');
  }
$iQuery = $db->query("SELECT * FROM product WHERE deleted = 0 AND user_id = $user_id");
while($product = mysqli_fetch_assoc($iQuery)){
  $product_id = $product['id'];
  $sizes = sizesToArray($product['sizes']);
  foreach($sizes as $size){
    $qty = $size['quantity'];
    $threshold = $size['threshold'];
    }
    $db->query("INSERT INTO `sizes`(`product_id`, `qty`, `threshhold`) VALUES ('$product_id','$qty','$threshold')");
    $db->query("UPDATE `sizes` SET `qty`='$qty',`threshhold`='$threshold' WHERE product_id = '$product_id' ");
  }

?>
