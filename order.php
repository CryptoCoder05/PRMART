<?php
  require_once 'core/init.php';
  if(!is_logged_in()){
    header('Location: regform/login.php');
  }
  include_once 'includes/Header.php';
?>
<div class="" style="margin-bottom:15px; margin-top:80px;">
  <h2 class="text-center">Items Ordered</h2>
</div>
<?php
  $u_id = $user_id;
  $txnQuery = $db->query("SELECT * FROM transactions WHERE user_id = '{$u_id}'");
  while ($txn = mysqli_fetch_assoc($txnQuery)) {
    $cart_id = $txn['cart_id'];
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $cart = mysqli_fetch_assoc($cartQ);
    $items = json_decode($cart['items'],true);
    $idArray = array();
    $products = array();
    foreach($items as $item){
     $idArray[] = $item['id'];
    }
    $ids = implode(',',$idArray);
    $productQ = $db->query(
                "SELECT i.id as 'id', i.title as 'title', c.id as 'cid', c.categories as 'child', p.categories as 'parent'
                FROM product i
                LEFT JOIN categories c ON i.categories = c.id
                LEFT JOIN categories p ON c.parent = p.id
                WHERE i.id IN ({$ids})
              ");
    while($p = mysqli_fetch_assoc($productQ)){
      foreach($items as $item){
        if($item['id'] == $p['id']){
          $x = $item;
          continue;
        }
      }
      $products[] = array_merge($x,$p);
    }
   ?>
   <section class="container-fluid"> <hr>
   <table class="table table-condensed table-bordered table-striped">
     <thead>
       <th>Quantity</th>
       <th>Title</th>
       <th>Category</th>
       <th>Size</th>
     </thead>
     <tbody>
       <?php foreach($products as $product): ?>
       <tr>
         <td><?=$product['quantity'];?></td>
         <td><?=$product['title'];?></td>
         <td><?=$product['parent'].' ~ '.$product['child'];?></td>
         <td><?=$product['size'];?></td>
       </tr>
     <?php endforeach; ?>
     </tbody>
   </table>

   <div class="row">
     <div class="col-md-6">
       <h3 class="text-center">Order Details</h3>
       <table class="table table-condensed table-bordered table-striped">
         <tbody>
           <tr>
             <td>Payment Mode</td>
             <td><?=$txn['payment_mode']; ?></td>
           </tr>
           <tr>
             <td>Status</td>
             <td><?=(($cart['shipped'] == '0')?'pending':'shipped'); ?></td>
           </tr>
           <tr>
             <td>Sub Total</td>
             <td><?=money($txn['sub_total']); ?></td>
           </tr>
           <tr>
             <td>Tax</td>
             <td><?=money($txn['tax']); ?></td>
           </tr>
           <tr>
             <td>Grand Total</td>
             <td><?=money($txn['grand_total']); ?></td>
           </tr>
           <tr>
             <td>Order Date</td>
             <td><?=pretty_date($txn['txn_date']); ?></td>
           </tr>
         </tbody>
       </table>
     </div>
     <div class="col-md-6">
       <h3 class="text-center">Shipping Address</h3>
       <address>
         <?=$txn['full_name']; ?><br />
         <?=$txn['street']; ?><br />
         <?=($txn['street2'] != '')?$txn['street2'].'<br />':''; ?>
         <?=$txn['city'].', '.$txn['state'].' '.$txn['zip_code']; ?><br />
         <?=$txn['country']; ?><br />
       </address>
     </div>
   </div>
  </section>
  <?php } ?>
  <div class="container-fluid pull-right">
    <a href="index.php" class="btn btn-large btn-default">Cancel</a>
  </div>
<?php include 'includes/footer.php'; ?>
