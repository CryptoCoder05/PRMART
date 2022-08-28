<?php
require_once 'core/init.php';
if(!is_logged_in()){
  header('Location: regform\login.php');
}
include_once 'includes/Header.php';
if($cart_id != ''){
  $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
  $result = mysqli_fetch_assoc($cartQ);
  $items = json_decode($result['items'],true);
  $i = 1;
  $sub_total = 0;
  $item_count = 0;
}
?>

<h3 class="text-center" style="margin-top:65px;">Shipping address</h3><hr>
<section>
  <form class="" action="index.html" method="post">
    
  </form>
</section>

<?php include_once 'includes/footer.php'; ?>
