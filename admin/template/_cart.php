<section id="refress">
<div class="container-fluid">
<div style="width:85%;">
<h2 class="text-center" id="top_cart" style="letter-spacing:2px;">Sell Product</h2> <hr />

<!--==barcode form row=====================--->
<div class="row">
  <div class="form-group col-md-4">
    <label for="barcode"  id="barcode_text" >Barcode :</label>
    <input type="text" name="barcode"  class="form-control" id="barcode" onfocus="xfocus(this)" onblur="xblur(this)" placeholder="Scan barcode..." value="" />
  </div>
  <div class="form-group col-md-6">
    <label for="product_name"  id="product_name" >Product Name :</label>
    <form>
    <input class="form-control" list="barcode_value" id="barcode_values" name="product_name" placeholder="Search product..." value="">
    <datalist id="barcode_value" name="product_name" onfocus="xfocus(this)" onblur="xblur(this)">
    <?php
    $prd_title_sql = $db->query("SELECT * FROM product WHERE deleted = 0 AND user_id = '$user_id' ORDER BY title");
      while ($result = mysqli_fetch_assoc($prd_title_sql)) :
    ?>
      <option value="<?=$result['title'];?>">
      <?php
        endwhile ?>
    </datalist>
    </form>
  </div>

  <div class="col-md-2">
    <a href="fast_add_product.php?add=1" class="btn btn-warning" style="width:180px;height:40px;line-height:30px;letter-spacing:2px;border-radius:20px;margin-bottom:5px;">Add new product</a>

    <button type="button" class="btn btn-info" name="button" id="add_to_cart" style="width:180px;height:40px;letter-spacing:2px;border-radius:20px;">Add to cart</button>
  </div>
  </div><hr>
<!--==end of barcode form row===============-->

<!--==ajax result div=======================-->
<div id="result"></div>
<!--==end of ajax result div================-->

<!--==Table row=============================-->
<div class="row" id="ref_bar_scan" style="margin:0 2px 0 2px;">
  <?php
  $i = 1;
  $sub_total = 0;
  $item_count = 0;
  $total = 0;
   ?>
   <table class="table table-bordered table-condensed table-striped" id="table" >
     <thead>
       <th>Delete</th>
       <th>S.No</th>
       <th>Product Name</th>
       <th>Price</th>
       <th>Quantity</th>
       <th>Sub Total</th>
       <th>Discount in Rs.</th>
       <th>Grand Total</th>
     </thead>
     <tbody>
       <?php
         $Total_dis = 0;
         $act_total = 0;
         $grand_totall = 0;
         if (has_permission('seller')){
           $productQ = $db->query("SELECT * FROM barcode_cart WHERE paid = 0 AND user_id=$user_id ");
         }else {
           $productQ = $db->query("SELECT * FROM barcode_cart WHERE paid = 0 ");
         }
         while($product = mysqli_fetch_assoc($productQ)):
           $sale_price = $product['selll_price'];
           $qty = $product['quantity'];

           $sub_total = ($sale_price * $qty);
           $act_total+=$sub_total;

           $discount = $product['discount'];
           $New_sale_price = $sub_total-$discount;

           $grand_totall +=$New_sale_price;
           $Total_dis+=$discount;
         ?>
          <tr id="<?=$product['id']; ?>">
            <td class="text-center">
              <button type="button" id="<?=$product['id']; ?>" name="button" class="btn btn-xs btn-default xdelete"><span class="glyphicon glyphicon-remove"></button>
            </td>
            <td><?=$i; ?></td>
            <td style="width:300px;"><?=substr($product['title'],0,70).'...';?></td>
            <td><?=money($product['selll_price']); ?></td>
            <td>
             <input type="text" class="form-control" onchange="update_quantity(this);" onfocus="xfocus(this)" onblur="xblur(this)"  placeholder="Enter quantity..."  value="<?=$product['quantity']; ?>"/>
            </td>
            <td><?=money($sub_total); ?></td>
            <td>
              <input type="text" class="form-control" onchange="update_discount(this);" onfocus="xfocus(this)" onblur="xblur(this)"  placeholder="Enter Discount in Rs..."  value="<?=$discount;?>"/>
            </td>
            <td><?=money($New_sale_price);?></td>
          </tr>
         <?php
           $i++;
           $item_count += $product['quantity'];
           $total += $sub_total;
         endwhile;
           $tax = 0 * $total;
           $grand_total = $tax + $total;
        ?>
     </tbody>
   </table>
</div>
<!--==end of Table row======================-->

<!--==calculator row========================-->
<div class="row">
  <div id="left_side_bottom">
    <div class="col-md-3"></div>
      <div class="col-md-3" >
        <form class="form-group" action="cart.php" method="post">
          <div class="form-group">
            <label for="Input" class="text-info">Input Cash:</label>
            <input type="text" name="Input" id="Input" class="form-control" onfocus="xfocus(this)" onblur="xblur(this)" placeholder="Rs...."  value="" >
          </div>
          <div class="form-group">
            <label for="Total" class="text-info">Total Amount:</label>
            <input type="text" name="Total" id="Total" class="form-control"  value="<?=$grand_totall ; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="Output" class="text-info">Returns Amount:</label>
             <input type="text" name="output" id="output" class="form-control" value="" readonly>
          </div>
        </form>
      </div>
    <!-- Check out button -->
     <div class="col-md-3">
       <a href="print_receipt.php?complete=1"  name="casPay"><img src="../images/icons/cash-button.png" alt="CASH" class="img-responsive" class="payment"></a>
       <a href="print_receipt.php?complete=2"  name="casPay"><img src="../images/icons/esewa.png" alt="e-sewa" class="img-responsive" class="payment"></a>
       <a href="print_receipt.php?complete=credit"  name="casPay"><img src="../images/icons/credit.png" alt="Credit/debit card payment"  class="img-responsive"></a>
       <a href="cart.php?cancel=1" name="casPay"><img src="../images/icons/cancel.png" alt="cancel" class="img-responsive" class="payment"></a>
     </div>
     <div class="col-md-3"></div>
     <!-- end of Check out button -->
  </div><!--end of buttom row --->
</div>
<!--==end of calculator row=================-->

</div><!--end of 80% width-->

<!--right side 15% width-->
<div style="width:15%;" id="right_side">
      <div class="box-3">
        <div>Sub Total</div>
        <div id="txt"><?=money($act_total) ; ?></div>
      </div>
      <div class="box-1">
        <div>Discount</div>
        <div id="txt"><?=money($Total_dis) ; ?></div>
      </div>
      <div class="box-4">
        <div>Grand Total</div>
        <div id="txt"><?=money($grand_totall) ; ?></div>
      </div>
      <div class="box-2">
        <div>Total Items</div>
        <div id="txt"><?=$item_count; ?></div>
      </div>
      <div class="box-5"></div>
</div>
<!--end right side of 15% width-->

</div><!--end of container-fluid-->
</section>
