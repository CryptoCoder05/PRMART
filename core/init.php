<?php ob_start(); ?>
<?php
date_default_timezone_set("Asia/Kolkata");
$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "prmart";

$db = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/prmart/helpers/config.php';
require_once BASEURL.'helpers/helpers.php';
require BASEURL.'vendor/autoload.php';

$cart_id = '';
if(isset($_COOKIE['CART_COOKIE'])){
  $cart_id = sanitize($_COOKIE['CART_COOKIE']);
}

if(isset($_SESSION['SBUser'])){
  $user_id = $_SESSION['SBUser'];
  $query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
  $user_data = mysqli_fetch_assoc($query);
  $shop_name = $user_data['shop_name'];
  define('SHOPNAME',$shop_name);
  $fn = explode(' ',$user_data['full_name']);
  $user_data['first'] = $fn[0];
  // $user_data['last'] = $fn[1];
  ((isset($fn[1]))?$user_data['last']=$fn[1]:'');
}


if(isset($_SESSION['success_flash'])){
  echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
  unset($_SESSION['success_flash']);
}

if(isset($_SESSION['error_flash'])){
  echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
  unset($_SESSION['error_flash']);
}

//if(isset($_SESSION['error_flash'])){
  ?>
   <script type="text/javascript">
  //   swal ( "Oops!" ,  "<?=$_SESSION['error_flash']; ?>" ,  "error" );
   </script>
  <?php
//  unset($_SESSION['error_flash']);
//}
