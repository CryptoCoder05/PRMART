<?php
if(!is_logged_in()){
  permisssion_error_redirect_login('login.php');
}
 if (has_permission('customer')) {
   permisssion_error_redirect_login('login.php');
 }

 //---Cancel orders---
 if(isset($_GET['cancel']) && $_GET['cancel'] == 1){
   $db->query("DELETE FROM barcode_cart WHERE user_id = $user_id");
   header('Location: cart.php');
 }
 ?>
