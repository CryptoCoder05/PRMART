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
  $channel = '';
  $type = 'paid';
  if(isset($_POST['search'])){
    $channel = sanitize($_POST['channel']);
    $from = sanitize($_POST['from']);
    $to = sanitize($_POST['to']);
    //check if  blank
    if($_POST['from'] == '' && $_POST['to'] == ''){
      $errors[].='You must enter a starting and ending date!';
    }
    // check if exits in database--
    if ($_POST['channel'] == 'offline') {
      $type = 'channel';
    }
    if ($_POST['channel'] == 'cash') {
      $type = 'txn_type';
    }
    if ($_POST['channel'] == 'e-sewa') {
      $type = 'txn_type';
    }
    if ($_POST['channel'] == 'credit') {
      $type = 'txn_type';
    }
    if ($_POST['channel'] == 'online') {
      $type = 'channel';
    }

    if($_POST['channel'] == 'online'){
      $iQuery1 = $db->query("SELECT * FROM transactions WHERE channel = '$channel' AND txn_date Between '$from' and '$to' AND customer_id = $user_id order by txn_date");
      $count_txn = mysqli_num_rows($iQuery1);
      if ($count_txn == '0') {
        $errors[] .= 'There is no sales in that months from web!';
      }
    }
    $sql = "SELECT * FROM barcode_transaction WHERE  $type = '$channel' AND txn_date Between '$from' and '$to' AND user_id = $user_id order by txn_date";
    $result_2 = $db->query($sql);
    if($_POST['channel'] != 'online'){
      $count_bar_txn = mysqli_num_rows($result_2);
      if ($count_bar_txn == '0') {
        $errors[] .= 'There is no sales in that months!';
       }
    }
    //dispaly errors--
    if(!empty($errors)){
      echo display_errors($errors);
    }else {
      while($txn_date = mysqli_fetch_array($result_2)){
        //var_dump($txn_date);
      }
    }
  }


//---Today sales------
  if(isset($_GET['day'])){
    $channel = 'offline and online';
    $from = date("Y-m-d");
    $tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
    $to = date('Y-m-d', $tomorrow);
  }
//---this month sales---
  if(isset($_GET['month'])){
    $channel = 'offline and online';
    $first_day  = strtotime('first day of this month');
    $from = date("Y-m-d", $first_day);
    $last_day  = strtotime('last day of this month');
    $to = date("Y-m-d", $last_day);
  }
  ?>
<!--enter date -->
  <section class="text-center" style="margin-top:50px;">
  <div class="container-fluid">
    <form class="form-inline" action='report.php' method="post">
      <div class="form-group">
        <label for="channel" class="text-info">Channel :</label>
        <select class="form-control" id="channel" name="channel">
          <option value="">select</option>
          <option value="offline and online">All</option>
          <option value="offline">Barcode</option>
          <option value="cash">cash</option>
          <option value="e-sewa">e-sewa</option>
          <option value="credit">credit</option>
          <option value="online">web</option>
        </select>
      </div>
      <div class="form-group">
        <label for="from" class="text-info">From Date:</label>
        <input type="date" name="from" id="from" class="form-control"  value="" >
      </div>
      <div class="form-group">
        <label for="to" class="text-info">To Date:</label>
         <input type="date" name="to" id="to" class="form-control" value="" >
         <button type="submit" class="btn btn-danger" name="search" id="search">
           <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
         </button>
      </div>
    </form>
  </div><hr />
  </section>
<!--End of date table-->

<section>
  <div class="container-fluid">
    <div class="row">
    <?php
      $Total_qty = 0;
      $Total_grand = 0;
      $iQuery = $db->query("SELECT * FROM barcode_transaction WHERE  $type = '$channel' AND txn_date Between '$from' and '$to' AND user_id = $user_id order by txn_date");
     ?>
     <div class="col-md-12">
       <div id="print_receipt">
         <h2 class="text-info text-center"><?=SHOPNAME; ?></h2>
       <h3 class="text-center">
         <?php if(isset($_POST['search']) && $_POST['from'] != '' && $_POST['to'] != ''): ?>
          <span class="text-info">Sales Report from </span> <?=$from;?> <span class="text-info">To</span> <?=$to ;?> <span class="text-info">By</span> <?=$channel ;?>
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
            <th>Product Name</th>
            <th>Items</th>
            <th>price</th>
            <th>Quantity</th>
            <th>sub_total</th>
            <th>Discount</th>
            <th>Profit</th>
            <th>Loss</th>
            <th>grand_total</th>
            <th>Channel</th>
          </tr>
          <tbody>
            <?php
            $total_dis = 0;
            $total_sub = 0;
            $total_profit = 0;
            $total_loss = 0;
            while($product = mysqli_fetch_array($iQuery)):
              $cost_price = $product['cost_price'];
              $sell_price = $product['selll_price'];
              $qty = $product['qty'];
              $discount = $product['discount'];
              $sub_total = $product['selll_price'] * $product['qty'];
              $grand_total = $sub_total - $discount;
              $loss = 0;
              $profit = 0;
              if ($sell_price > $cost_price) {
                $profit = (($sell_price - $cost_price) * $qty)-$discount;
              }else {
                $loss = (($cost_price - $sell_price) * $qty)+$discount;
              }
              ?>
            <tr>
              <td><?=pretty_date($product['txn_date']); ?></td>
              <td><?=$product['title']; ?></td>
              <td><?=$product['items']; ?></td>
              <td><?=money($product['selll_price']); ?></td>
              <td><?=$qty; ?></td>
              <td><?=money($sub_total); ?></td>
              <td><?=money($discount); ?></td>
              <td><?=money($profit);?></td>
              <td><?=money($loss);?></td>
              <td><?=money($grand_total); ?></td>
              <td><?=$product['txn_type']; ?></td>
            </tr>
            <?php
            $Total_qty += $product['qty'];
            $Total_grand += $grand_total;
            $total_dis += $discount;
            $total_sub += $sub_total;
            $total_profit += $profit;
            $total_loss += $loss;
            ?>
          <?php endwhile; ?>
          </tbody>
          <!--data from online-->
          <tbody>
            <?php
              $iQuery1 = $db->query("SELECT * FROM transactions WHERE channel = '$channel' AND txn_date Between '$from' and '$to' AND customer_id = $user_id order by txn_date");
              while($product1 = mysqli_fetch_array($iQuery1)):
              $cart_idd = $product1['cart_id'];
              $channel = $product1['channel'];
               // from cart----
               $itemQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_idd}' AND user_id = $user_id");
               $iresults = mysqli_fetch_assoc($itemQ);
               $items = json_decode($iresults['items'],true);
             ?>

            <?php foreach($items as $item):
              $product_id = $item['id'];
              $cart_qty = $item['quantity'];
              $productQ = $db->query("SELECT * FROM product WHERE id = '{$product_id}' AND user_id = $user_id ");
              $product = mysqli_fetch_assoc($productQ);
              $sizes = sizesToArray($product['sizes']);
              foreach($sizes as $size){
               $item = $size['size'];
              }
              ?>
            <tr>
              <td><?=pretty_date($product1['txn_date']); ?></td>
              <td><?=$product['title']; ?></td>
              <td><?=$item; ?></td>
              <td><?=money($product['selll_price']); ?></td>
              <td><?=$cart_qty; ?></td>
              <?php $sub_total = $cart_qty * $product['selll_price']; ?>
              <td><?=money($sub_total); ?></td>
              <?php $tax = TAXRATE * $sub_total; ?>
              <td>Discount</td>
              <?php $grand_total = $sub_total + $tax; ?>
              <td>Profit</td>
              <td>Loss</td>
              <td><?=money($grand_total);?></td>
              <td><?=$channel; ?></td>
            </tr>
          <?php
          $Total_qty += $cart_qty;
          $Total_grand += $grand_total;
          $total_sub += $sub_total;
          ?>
          <?php endforeach; ?>
          <?php endwhile;
            $Total = 0;
            $grand = 0;
          ?>
          </tbody>
          <tfoot>
            <tr class="success">
              <th colspan="4" class="text-center">Total</th>
              <th><?=$Total += $Total_qty; ?></th>
              <th><?=money($total_sub);?></th>
              <th><?=money($total_dis);?></th>
              <th><?=money($total_profit);?></th>
              <th><?=money($total_loss);?></th>
              <?php $grand += $Total_grand; ?>
              <th><?=money($grand); ?></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
        <div class="row">
          <div class="col-md-8"></div>
          <div class="col-md-4">
            <table class="table table-condensed table-bordered table-striped">
              <tr>
                <th>Total sell</th>
                <td><?=money($grand);?></td>
              </tr>
              <tr>
                <th>Total Profit</th>
                <td><?=money($total_profit);?></td>
              </tr>
              <tr>
                <th>Total loss</th>
                <td><?=money($total_loss);?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
     </div>
  </div>
  <button type="button" class="btn btn-default pull-right" aria-label="Left Align" onclick="print_fun('print_receipt')">
     <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
  </button>
</div>
</section>

<script type="text/javascript">
  function print_fun(paravalue) {
    var backup = document.body.innerHTML;
    var print_content = document.getElementById(paravalue).innerHTML;
    document.body.innerHTML = print_content;
    window.print();
    document.body.innerHTML = backup;
  }
</script>

<?php include 'includes/footer.php'; ?>
