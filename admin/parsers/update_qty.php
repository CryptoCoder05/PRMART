<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/prmart/core/init.php';
$domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);
$edit_id = ((isset($_POST['edit_id']) && $_POST['edit_id'] != '')?sanitize($_POST['edit_id']):'');
$edit_quntity = ((isset($_POST['edit_quntity']) && $_POST['edit_quntity'] != '')?sanitize($_POST['edit_quntity']):'');
$barcodeQ = $db->query("SELECT * FROM barcode_cart WHERE id = '$edit_id' AND user_id = $user_id");
$result = mysqli_fetch_assoc($barcodeQ);
$id = $result['product_id'];

$productQ = $db->query("SELECT * FROM product WHERE id = '$id' AND user_id = $user_id");
$product = mysqli_fetch_assoc($productQ);
  $sArray = explode(',',$product['sizes']);
  $s = explode(':',$sArray[0]);
  $qty = $s[1];  var_dump($qty);

// --check availabe quantity----
if($edit_quntity > $qty || $edit_quntity < 0){
  $_SESSION['success_flash'] = 'Enter quantity is more than availabe quantity!';
}else {
    $db->query("UPDATE barcode_cart SET quantity = '$edit_quntity' WHERE id = '$edit_id'");
    //$_SESSION['success_flash'] = 'Your shopping cart has been updated!';
  }
//--check total quantity--
$qtyQ = $db->query("SELECT * FROM barcode_cart WHERE product_id = '$id' AND user_id = $user_id");
$current = array();
$currentTotal = 0;
while($x = mysqli_fetch_assoc($qtyQ)){
  $p_id = $x['product_id'];
  if(!array_key_exists($p_id,$current)){
    $current[$p_id] = $x['quantity'];
  }else{
    $current[$p_id] += $x['quantity'];
  }
  $currentTotal += $x['quantity'];
}

if($currentTotal > $qty){
    $db->query("UPDATE barcode_cart SET quantity = '($qty available)' WHERE id = '$edit_id'");
    $_SESSION['success_flash'] = 'Enter quantity is more than availabe quantity!';
  }
if($edit_quntity == ''){
  $db->query("UPDATE barcode_cart SET quantity = 0 WHERE id = '$edit_id'");
}
?>
