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
$users_Q = $db->query("SELECT * FROM users WHERE id = $user_id");
$users_R = mysqli_fetch_assoc($users_Q);
if(isset($_GET['complete']) && $_GET['complete'] == 1){
  $txn_type = "cash";
}
elseif (isset($_GET['complete']) && $_GET['complete'] == 2) {
  $txn_type = "e-sewa";
}
elseif (isset($_GET['complete']) && $_GET['complete'] == 'credit') {
  $txn_type = "credit";
}
if(isset($_GET['complete'])){
  $bill_no = 'PR'.rand(10000,1000000);
  $db->query("UPDATE barcode_cart SET paid = 1");
  $barcodeQ = $db->query("SELECT * FROM barcode_cart WHERE paid = 1 AND user_id = $user_id");
  while($barResult = mysqli_fetch_assoc($barcodeQ)):
  $barcode_id = $barResult['product_id'];
  $title = $barResult['title'];
  $items = $barResult['items'];
  $cost_price = $barResult['cost_price'];
  $MRP = $barResult['MRP'];
  $price = $barResult['selll_price'];
  $quantity = $barResult['quantity'];
  $discount = $barResult['discount'];
  $sub_total = ($barResult['selll_price'] * $barResult['quantity']);
  $tax = TAXRATE * $sub_total;
  $grand_total = ($sub_total + $tax) - $discount;
  $month = date("m",strtotime($barResult['txn_date']));
  $day = date("d",strtotime($barResult['txn_date']));
  $db->query("INSERT INTO `barcode_transaction`(`user_id`,`barcode_id`,`bill_no` ,`title`,`items`,`cost_price`, `selll_price`,`qty`,`discount`,`grand_total`, `txn_type`,`months`,`day`)
                VALUES ('$user_id','$barcode_id','$bill_no','$title','$items','$cost_price','$price','$quantity','$discount','$grand_total','$txn_type','$month','$day')");

              //---update qty in low inventary--
              $id = $barResult['product_id'];
              $bar_txt_Result_count = mysqli_num_rows($barcodeQ);
              $qtyQ = $db->query("SELECT * FROM barcode_cart WHERE product_id = '$id' AND deleted = 0 AND user_id = $user_id");
              $current = array();
              $currentTotal = 0;
              //--from barcode_cart--
              while($x = mysqli_fetch_assoc($qtyQ)){
                $p_id = $x['product_id'];
                if(!array_key_exists($p_id,$current)){
                  $current[$p_id] = $x['quantity'];
                }else{
                  $current[$p_id] += $x['quantity'];
                }
                $currentTotal += $x['quantity'];
              }

              if($bar_txt_Result_count > 0){
              $productQ = $db->query("SELECT * FROM product WHERE id = '$id' AND user_id = $user_id");
              $product = mysqli_fetch_assoc($productQ);

              $sizes = sizesToArray($product['sizes']);
              $newSizes = array();
              foreach($sizes as $size){
                if($size['size'] == $barResult['items']){
                  $q = $size['quantity'] - $currentTotal;
                  $newSizes[] = array('size' => $size['size'], 'quantity' => $q, 'threshold' => $size['threshold']);
                }else{
                  $newSizes[] = array('size' => $size['size'], 'quantity' => $size['quantity'], 'threshold' => $size['threshold']);
                }
                }
                 $sizeString = sizesToString($newSizes);
                 $db->query("UPDATE product SET sizes = '{$sizeString}' WHERE id = '$id'");
                 $db->query("UPDATE barcode_cart SET deleted = 1 WHERE product_id = '$id'");
                 //$db->query("DELETE FROM barcode_cart WHERE product_id = '$id'");
               }
   endwhile;
   //delete from barcode trasaction if qty = o;
   $bar_txn_Q = $db->query("SELECT qty FROM barcode_transaction WHERE user_id = $user_id");
   $bar_txt_Q_count = mysqli_num_rows($bar_txn_Q);
   if($bar_txt_Q_count > 0){
       $db->query("DELETE FROM barcode_transaction WHERE qty = '0'");
   }
}
 ?>
 <?php if (isset($_GET['complete']) && $_GET['complete'] == 'credit'){?>
 <section class="container-fluid">
   <div class="row">
       <!--Receipt-->
       <?php
       $printQ = $db->query("SELECT * FROM barcode_cart WHERE paid = 1 AND user_id = $user_id");
        ?>
         <div id="center" style="margin-top:30px;">
           <div class="text-center" id="div1">
             <span style="font-size:25px;"><?=SHOPNAME; ?></span><br>
             <span style="font-size:15px;"><?=$users_R['addr'];?></span><br>
             <span style="font-size:15px;">PHONE: <?=$users_R['phone_no'];?></span><br>
             <span style="font-size:15px;">INVOICE</span>
           </div>

         <div id="div3">
           <table class="table table-condensed" id="center">
               <tr>
                 <td colspan="3">
                   <span>Payment : <?=$txn_type;?></span><br>
                   <span>Name : </span><br>
                   <span>Mobile : </span>
                 </td>
                 <td colspan="3">
                   <span>Bill No : <?=$bill_no;?></span><br>
                   <span>Date : <?=date("d/m/y");?></span><br>
                   <span>Time : <?=date("h:i A");?></span>
                 </td>
               </tr>

                   <tr>
                     <th>S.No</th>
                     <th>Desc.</th>
                     <th>RATE</th>
                     <th>QTY</th>
                     <th>Disc</th>
                     <th>Amt</th>
                   </tr>
                   <tbody>
                     <?php
                       $grand_total_price=0;
                       $total_qty=0;
                       $i=0;
                       while($printResult = mysqli_fetch_assoc($printQ)):
                         $total=$printResult['selll_price']*$printResult['quantity'];
                         $discount = $printResult['discount'];
                         $New_total = $total - $discount;
                         $grand_total_price+=$New_total;
                         $total_qty+=$printResult['quantity'];
                         $i++;
                      ?>
                     <tr>
                       <td><?=$i;?></td>
                       <td><?=substr($printResult['title'], 0,15);?></td>
                       <td><?=$printResult['selll_price'];?></td>
                       <td><?=$printResult['quantity'];?></td>
                       <td><?=$discount;?></td>
                       <td><?=$New_total;?></td>
                     </tr>
                     <?php
                     endwhile;
                      $db->query("DELETE FROM barcode_cart");
                    ?>
                   </tbody>
       <tfoot>
         <tr>
           <th colspan="2">Item Qty</th>
           <th><?=$total_qty;?></th>
           <th colspan="2">Sub total</th>
           <th><?=$grand_total_price;?></th>
         </tr>
         <tr>
           <td colspan="6">
             <div id="div4">
               <p>1. Goods once sold not be taken back & no cash Refund.</p>
               <p>2. Goods may be exchanged with in week only on the presentation of Bill.</p>
             </div>
             <div class="text-center">
               <span style="font-size:12px; font-weight:bold;">Thank You Visit Again</span><br>
               <span style="font-size:10px;">Our Billing Software 9844057518</span>
             </div>
           </td>
         </tr>
       </tfoot>
       </table>
       </div><!--End of Table-->
       </div><!--End of Receipt-->
       <div class="text-center" style="margin-top:30px;">
         <div>
           <button type="button" class="btn btn-default" aria-label="Left Align" onclick="print_fun('center')" style="margin-left:10px;">
             <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
           </button>
           <a href="cart.php" class="btn btn-default">Back</a>
         </div>
     </div><!--end of print button row-->
   </div><!--end of main row-->
 </section>
<?php } else { ?>
  <!--Receipt-->
  <?php
  $printQ = $db->query("SELECT * FROM barcode_cart WHERE paid = 1 AND user_id = $user_id");
   ?>
   <section class="container-fluid">
     <div id="center" style="margin-top:10px;">
       <div class="text-center" id="div1">
         <span style="font-size:25px;"><?=SHOPNAME; ?></span><br>
         <span style="font-size:15px;"><?=$users_R['addr'];?></span><br>
         <span style="font-size:15px;">PHONE: <?=$users_R['phone_no'];?></span><br>
         <span style="font-size:15px;">INVOICE</span>
       </div>

     <div id="div3">
       <table class="table table-condensed" id="center">
           <tr>
             <td colspan="3">
               <span>Payment : <?=$txn_type;?></span><br>
               <span>Name : </span><br>
               <span>Mobile : </span>
             </td>
             <td colspan="3">
               <span>Bill No : <?=$bill_no;?></span><br>
               <span>Date : <?=date("d/m/y");?></span><br>
               <span>Time : <?=date("h:i A");?></span>
             </td>
           </tr>

               <tr>
                 <th>S.No</th>
                 <th>Desc.</th>
                 <th>Rate</th>
                 <th>Qty</th>
                 <th>Disc</th>
                 <th>Amt</th>
               </tr>
               <tbody>
                 <?php
                   $grand_total_price=0;
                   $total_qty=0;
                   $i=0;
                   while($printResult = mysqli_fetch_assoc($printQ)):
                     $total=$printResult['selll_price']*$printResult['quantity'];
                     $discount = $printResult['discount'];
                     $New_total = $total - $discount;
                     $grand_total_price+=$New_total;
                     $total_qty+=$printResult['quantity'];
                     $i++;

                     $full_title = $printResult['title'];
                     $sub_title = substr($full_title, 0,25).'...';
                  ?>
                 <tr>
                   <td><?=$i;?></td>
                   <td><?=$sub_title;?></td>
                   <td><?=$printResult['selll_price'];?></td>
                   <td><?=$printResult['quantity'];?></td>
                   <td><?=$discount;?></td>
                   <td><?=$New_total;?></td>
                 </tr>
                 <?php
                 endwhile;
                  $db->query("DELETE FROM barcode_cart");
                ?>
               </tbody>
   <tfoot>
     <tr>
       <th colspan="2">Item Qty</th>
       <th><?=$total_qty;?></th>
       <th colspan="2">Sub total</th>
       <th><?=$grand_total_price;?></th>
     </tr>
     <tr>
       <td colspan="6">
         <div id="div4">
           <p>1. Goods once sold not be taken back & no cash Refund.</p>
           <p>2. Goods may be exchanged with in week only on the presentation of Bill.</p>
         </div>
         <div class="text-center">
           <span style="font-size:12px; font-weight:bold;">Thank You Visit Again</span><br>
           <span style="font-size:10px;">Our Billing Software 9825828666</span>
         </div>
       </td>
     </tr>
   </tfoot>
   </table>
   </div><!--End of Table-->
   </div><!--End of Receipt-->
   <div class="row">
     <div class="col-md-4"></div>
     <div class="col-md-4 text-center">
       <div style="margin-top:20px;">
         <button type="button" class="btn btn-warning" aria-label="Left Align" onclick="print_fun('center')" style="margin-left:10px;height:40px;width:130px;">
           <span class="glyphicon glyphicon-print" aria-hidden="true"> Print</span>
         </button>
         <a href="cart.php" class="btn btn-default" style="height:40px;width: 130px;line-height:30px;letter-spacing:2px;">Back</a>
       </div>
     </div>
     <div class="col-md-4"></div>
   </div>
   </section>
<?php } ?>

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
