<?php
 require_once '../core/init.php';
 if(!is_logged_in()){
   permisssion_error_redirect_login('login.php');
 }
  if (has_permission('customer')) {
    permisssion_error_redirect_login('login.php');
  }
 include 'includes/head.php';
 include 'includes/navigation.php';
 ?>

<?php
  //--If  form is submited--
  $from = '';
  $to = '';
  if(isset($_POST['search'])){
    $from = sanitize($_POST['from']);
    $to = sanitize($_POST['to']);
    //check if  blank
    if($_POST['from'] == '' && $_POST['to'] == ''){
      $errors[].='You must enter a starting and ending date!';
    }
    // check if exits in database--
    $sql = "SELECT * FROM barcode_transaction WHERE txn_date Between '$from' and '$to' AND user_id = $user_id order by txn_date";
    $result_2 = $db->query($sql);
    $count = mysqli_num_rows($result_2);
    if($count == '0'){
      $errors[] .= 'There is no sales in that months!';
       }
    //dispaly errors--
    if(!empty($errors)){
      echo display_errors($errors);
    }else {
      while($txn_date = mysqli_fetch_array($result_2)){
        //var_dump($txn_date['id']);
      }
    }
  }
//---Today sales------
  if(isset($_GET['day'])){
    $from = date("Y-m-d");
    $tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
    $to = date('Y-m-d', $tomorrow);
  }
//---this month sales---
  if(isset($_GET['month'])){
    $first_day  = strtotime('first day of this month');
    $from = date("Y-m-d", $first_day);
    $last_day  = strtotime('last day of this month');
    $to = date("Y-m-d", $last_day);

  }

  ?>
<section>
  <div class="container-fluid">
    <div class="row">
    <?php
      $Total_qty = 0;
      $Total_grand = 0;
      $iQuery = $db->query("SELECT * FROM barcode_transaction WHERE txn_date Between '$from' and '$to' AND user_id = $user_id order by txn_date");
     ?>
     <div class="col-md-12">
       <h2 class="text-info text-center">P.R mart</h2><hr />
       <h3 class="text-center">
         <?php if(isset($_POST['search']) && $_POST['from'] != '' && $_POST['to'] != ''): ?>
          <span class="text-info">Sales Report from </span> <?=$from;?> <span class="text-info">To</span> <?=$to ;?>
         <?php endif; ?>

         <?php if(isset($_GET['day'])): ?>
          <span class="text-info">Today sales</span>
         <?php endif; ?>

         <?php if(isset($_GET['month'])): ?>
          <span class="text-info">This month sales </span>
         <?php endif; ?>
       </h3>
       <table class="table table-condensed table-bordered table-striped">
         <tr class="info">
           <th>Date / Time</th>
           <th>Product</th>
           <th>Items</th>
           <th>Quantity</th>
           <th>price</th>
           <th>sub_total</th>
           <th>Tax</th>
           <th>grand_total</th>
         </tr>
         <tbody>
           <?php while($product = mysqli_fetch_array($iQuery)): ?>
           <tr>
             <td><?=$product['txn_date']; ?></td>
             <td><?=$product['title']; ?></td>
             <td><?=$product['items']; ?></td>
             <td><?=$product['qty']; ?></td>
             <td><?=money($product['selll_price']); ?></td>
             <?php $sub_total = $product['selll_price'] * $product['qty']; ?>
             <td><?=money($sub_total); ?></td>
             <?php $tax = TAXRATE * $sub_total; ?>
             <td><?=money($tax); ?></td>
             <td><?=money($product['grand_total']); ?></td>
           </tr>
           <?php
           $Total_qty += $product['qty'];
           $Total_grand += $product['grand_total'];
           ?>
         <?php endwhile; ?>
         </tbody>
         <tbody>
           <?php
             $iQuery1 = $db->query("SELECT * FROM transactions WHERE txn_date Between '$from' and '$to' AND user_id = $user_id order by txn_date");
             while($product1 = mysqli_fetch_array($iQuery1)):
             $cart_idd = $product1['cart_id'];
              // from cart----
              $itemQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_idd}' AND user_id = $user_id");
              $iresults = mysqli_fetch_assoc($itemQ);
              $items = json_decode($iresults['items'],true);
            ?>

           <?php foreach($items as $item):
             $product_id = $item['id'];
             $cart_qty = $item['quantity'];
             $productQ = $db->query("SELECT * FROM product WHERE id = '{$product_id}' AND user_id = $user_id");
             $product = mysqli_fetch_assoc($productQ);
             $sizes = sizesToArray($product['sizes']);
             foreach($sizes as $size){
              $item = $size['size'];
             }
             ?>
           <tr>
             <td><?=$product1['txn_date']; ?></td>
             <td><?=$product['title']; ?></td>
             <td><?=$item; ?></td>
             <td><?=$cart_qty; ?></td>
             <td><?=money($product['selll_price']); ?></td>
             <?php $sub_total = $cart_qty * $product['selll_price']; ?>
             <td><?=money($sub_total); ?></td>
             <?php $tax = TAXRATE * $sub_total; ?>
             <td><?=money($tax); ?></td>
             <?php $grand_total = $sub_total + $tax; ?>
             <td><?=money($grand_total); ?></td>
           </tr>
         <?php
         $Total_qty += $cart_qty;
         $Total_grand += $grand_total;
         ?>
         <?php endforeach; ?>
         <?php endwhile;
           $Total = 0;
           $grand = 0;
         ?>
         </tbody>
         <tbody>
           <tr class="success">
             <th>Total</th>
             <th></th>
             <th></th>
             <th><?=$Total += $Total_qty; ?></th>
             <th></th>
             <th></th>
             <th></th>
             <?php $grand += $Total_grand; ?>
             <th><?=money($grand); ?></th>
           </tr>
         </tbody>
       </table>
     </div>
  </div>
</div>
</section>




<?php include 'includes/footer.php'; ?>
