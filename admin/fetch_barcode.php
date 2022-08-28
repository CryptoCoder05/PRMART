<?php
  require_once '../core/init.php';
  $barcode = ((isset($_POST['fetch_barcode']) && $_POST['fetch_barcode'] != '')?sanitize($_POST['fetch_barcode']):'');
  $productQ = $db->query("SELECT * FROM product WHERE (barcode = '$barcode' OR title = '$barcode') AND user_id = $user_id");
  $product = mysqli_fetch_assoc($productQ);
  $barcodeCount = mysqli_num_rows($productQ);
  //--check barcode is available or not.
  if($barcodeCount > 0){
    $barcode_id = $product['id'];
    $del_item = $product['deleted'];
    $title = $product['title'];
    $cost_price = $product['buy_price'];
    $MRP = $product['mrp_price'];
    $price = $product['selll_price'];
    $sArray = explode(',',$product['sizes']);
    $s = explode(':',$sArray[0]);
    $qty = $s[1];
    $items = $s[0];
    $quantity = '1';
    if($del_item == 1){
      $errors[] = 'That product was deleted from database';
    }elseif($qty > 0){
      //---Insert into Barcode cart---
        $db->query("INSERT INTO `barcode_cart`(`user_id`,`product_id`, `title`,`cost_price`,`MRP`, `selll_price`, `quantity`, `items`)
                                       VALUES ('$user_id','$barcode_id','$title','$cost_price','$MRP','$price','$quantity','$items')");
    }else {
      $errors[] = 'Out of Stock!';
    }
  }else{
    //$errors[] = 'That barcode doesn\'t exit in our database';
  }
  if($barcodeCount > 0){
    $barcode_id = $product['id'];
    $productQ = $db->query("SELECT * FROM product WHERE id = '$barcode_id' AND user_id = $user_id");
    $product = mysqli_fetch_assoc($productQ);
      $sArray = explode(',',$product['sizes']);
      $s = explode(':',$sArray[0]);
      $qty = $s[1];
    //--check total quantity--
    $qtyQ = $db->query("SELECT * FROM barcode_cart WHERE product_id = '$barcode_id' AND user_id = $user_id");
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
        $db->query("UPDATE barcode_cart SET quantity = '($qty available)' WHERE product_id = '$barcode_id'");
        $_SESSION['success_flash'] = 'Enter quantity is more than availabe quantity!';
      }
  }
  if(!empty($errors)){
    echo display_errors($errors);
  }
 ?>
