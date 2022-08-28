<?php
require_once 'core/init.php';
$user_id = $user_data['id'];
$full_name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);

$phone = sanitize($_POST['ph_no']);
$house_no = sanitize($_POST['flat_no']);

$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zip_code = sanitize($_POST['zip_code']);
$country = sanitize($_POST['country']);
$tax = sanitize($_POST['tax']);
$sub_total = sanitize($_POST['sub_total']);
$grand_total = sanitize($_POST['grand_total']);
$cart_id = sanitize($_POST['cart_id']);
$description = sanitize($_POST['description']);
$payment_mode = 'COD';

$metadata = array(
  "cart_id"   => $cart_id,
  "tax"       => $tax,
  "sub_total" => $sub_total,
);

// adjust inventory
$itemQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
$iresults = mysqli_fetch_assoc($itemQ);
$items = json_decode($iresults['items'],true);
foreach($items as $item){
  $newSizes = array();
  $item_id = $item['id'];
  $productQ = $db->query("SELECT sizes FROM product WHERE id = '{$item_id}' ");
  $product = mysqli_fetch_assoc($productQ);
  $sizes = sizesToArray($product['sizes']);
  foreach($sizes as $size){
    if($size['size'] == $item['size']){
      $q = $size['quantity'] - $item['quantity'];
      $newSizes[] = array('size' => $size['size'], 'quantity' => $q, 'threshold' => $size['threshold']);
    }else{
      $newSizes[] = array('size' => $size['size'], 'quantity' => $size['quantity'], 'threshold' => $size['threshold']);
    }
  }
  $sizeString = sizesToString($newSizes);
  $db->query("UPDATE product SET sizes = '{$sizeString}' WHERE id = '{$item_id}'");
}

// update cart

$db->query("UPDATE cart SET paid = 1 WHERE id = '{$cart_id}'");
$month = date("m");
$day = date("d");
$db->query("INSERT INTO `transactions`(`user_id`,  `cart_id`, `full_name`, `email`, `phone_no`, `house_no`,
                                      `street`, `street2`, `city`, `state`, `zip_code`, `country`, `sub_total`, `tax`, `grand_total`, `description`,`payment_mode`, `months`,`day`)
                               VALUES ('$user_id','$cart_id','$full_name','$email','$phone','$house_no',
                                       '$street','$street2','$city','$state','$zip_code','$country','$sub_total','$tax','$grand_total','$description','$payment_mode','$month','$day')");

$domain = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST']:false;
setcookie('CART_COOKIE','',1,"/",$domain,false);



//-------------Receipts---------------
include 'includes/Header.php';
require_once 'advertize.php';
?>
<section class="container-fluid">
  <h1 class="text-center" >Thank You!</h1><hr>
  <div class="col-md-6">
    <p style="color:black;">
       Your card has been successfully charged <?=money($grand_total); ?>. You have been emailed a receipt. Please
       check your spam folder if it is not in your inbox. Additionally you can print this page as a receipt.
    </p><br>
    <p style="color:black;">
      Your receipt number is: <strong><?=$cart_id; ?></strong>
    </p><br>
  </div>
  <div class="col-md-6">
    <h3 class="text-center">Shipping Address</h3>
    <address>
      <?=$full_name; ?><br />
      <?=$street; ?><br />
      <?=(($street2 != '')?$street2.'<br />':''); ?>
      <?=$city.', '.$state.' '.$zip_code; ?><br />
      <?=$country; ?><br />
    </address>
  </div>

</section>
<!---end of Receipts-====================-->
<!--==============Text sms=================-->
<?php
    $name = $full_name;
    $mobile = $phone;
    $msg = 'Your orders have been successfully placed. swastikshop';
    $phone = '91'.$mobile;
      // Account details of sms API textlocal-------
      require('textlocal_sms/textlocal.class.php');

      $Textlocal = new Textlocal(false, false, 'mBRCqLjBQbo-kMQAz68aKEetwHXx5xDO9OPUrHZ1I2');

      $numbers = array($phone);
      $sender = 'TXTLCL';
      $message = 'Hello '.$name. ". " .$msg;

      $response = $Textlocal->sendSms($numbers, $message, $sender);
      // end of Account details of sms API textlocal-------
 ?>
<!--=============================end of text sms-========================
<?php
include 'includes/footer.php';
 ?>
