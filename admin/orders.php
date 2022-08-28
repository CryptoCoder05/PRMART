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

  //Complete orders
  if(isset($_GET['pickup'])){
    $txn_id = sanitize((int)$_GET['pickup']);
    updateTxn('pickup','1','id',$txn_id);
  }

  if(isset($_GET['dispatched'])){
    $txn_id = sanitize((int)$_GET['dispatched']);
    updateTxn('pickup','1','id',$txn_id);
    updateTxn('dispatched','1','id',$txn_id);
  }

  if(isset($_GET['delivered'])){
    $txn_id = sanitize((int)$_GET['delivered']);
    updateTxn('pickup','1','id',$txn_id);
    updateTxn('dispatched','1','id',$txn_id);
    updateTxn('delivered','1','id',$txn_id);
  }

  // get txn_id
  $txn_id = sanitize((int)$_GET['txn_id']);

  // get order details from transaction table
  $newOrder = getOrderDet($txn_id);
 ?>
 <section class="container">
 <h2 class="text-center">Order Details</h2>
 <table class="table table-condensed table-bordered table-striped">
   <thead>
     <th>Shop Name</th>
     <th>Product</th>
     <th>Title</th>
     <th>Price</th>
     <th>Qty</th>
     <th>Category</th>
     <th>Size</th>
   </thead>
   <tbody>
     <?php
     // get orderd product details from transaction table
     foreach(getProd($newOrder['prod_details']) as $prod_details):

     // get product data from product table
     foreach(getProduct($prod_details['prod_id']) as $product):

    // get shop details from users table...
    foreach (getShop($prod_details['seller_id']) as $shop_del) :
     ?>
     <tr>
       <td>
         <?=$shop_del['shop_name'];?><br><br>
         <!-- Button trigger modal -->
         <button type="button" class="btn btn-xs btn-info seller_popup" id="<?=$shop_del['id']; ?>" data-toggle="modal" data-target="#dataModal">
          View Details
         </button>
       </td>
       <td class="text-center">
         <?php $photo = explode(',',$product['image']) ?>
         <img src="<?='../'.$photo[0];?>" alt="product" class="img-fluid" style="height:120px;">
       </td>
       <td><?=$product['title'];?></td>
       <td><?=money($product['selll_price']);?></td>
       <td><?=$prod_details['qty'];?></td>
       <td><!--<?=$product['parent'].' ~ '.$product['child'];?>--></td>
       <td><?=$prod_details['size'];?></td>
     </tr>
   <?php
     endforeach; // closing getShop()
    endforeach; // closing getProduct()
   endforeach; // closing getProd()
   ?>
   </tbody>
 </table>

 <div class="row">
   <div class="col-md-6">
     <h3 class="text-center">Payment Details</h3>
     <table class="table table-condensed table-bordered table-striped">
       <tbody>
         <?php
         // get payment details from transaction table
         foreach(getPayment($newOrder['payment_details']) as $pay_details){}
          ?>
         <tr>
           <td>Payment Mode</td>
           <td><?=$newOrder['payment_mode']; ?></td>
         </tr>
         <tr>
           <td>Sub Total</td>
           <td><?=money($pay_details['subtotal']); ?></td>
         </tr>
         <tr>
           <td>Delivery</td>
           <td><?=money($pay_details['delivery']); ?></td>
         </tr>
         <tr>
           <td>Discount</td>
           <td><?=money($pay_details['discount']); ?></td>
         </tr>
         <tr>
           <td>Grand Total</td>
           <td><?=money($pay_details['total']); ?></td>
         </tr>
         <tr>
           <td>Order Date</td>
           <td><?=pretty_date($newOrder['txn_date']); ?></td>
         </tr>
       </tbody>
     </table>
   </div>
   <div class="col-md-6">
     <h3 class="text-center">Shipping Address</h3><hr>
     <address class="pl-5">
       <?php // get shipping address details from transaction table
        foreach(getAdd($newOrder['address_details']) as $add_details){}
       ?>
       <?='Name     : '.$add_details['first_name'].' '.$add_details['last_name']; ?><br />
       <?='District : '.$add_details['district']; ?><br />
       <?='City     : '.$add_details['city']; ?><br />
       <?='Village  : '.$add_details['village']; ?><br>
       <?='Phone No : '.$newOrder['phone_no']; ?><br>
     </address>
   </div>
 </div>

<!--order tracking button-->
 <div class="pull-right" style="margin-bottom:35px;letter-spacing:2px;">
   <div class="" style="margin-bottom:10px;">
     <a href="orders.php?pickup=<?=$txn_id; ?>" class="btn btn-primary btn-lg active" style="width:200px;">Picked Up <?=($newOrder['pickup'] == 1)?'<i class="fas fa-check-circle"></i>':'';?></a>
   </div>
   <div class="" style="margin-bottom:10px;">
     <a href="orders.php?dispatched=<?=$txn_id; ?>" class="btn btn-warning btn-lg active" style="width:200px;">Dispatched <?=($newOrder['dispatched'] == 1)?'<i class="fas fa-check-circle"></i>':'';?></a>
   </div>
   <div class="" style="margin-bottom:10px;">
     <a href="orders.php?delivered=<?=$txn_id; ?>" class="btn btn-success btn-lg active" style="width:200px;">Delivered <?=($newOrder['delivered'] == 1)?'<i class="fas fa-check-circle"></i>':'';?></a>
   </div>
   <div class="" style="margin-bottom:10px;">
     <a href="index.php" class="btn btn-large btn-default btn-lg active" style="width:200px;">Cancel</a>
   </div>
 </div>

</section>

<!-- Modal -->
<div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Seller Detail</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="seller_details">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Dispaly seller details-->
<script type="text/javascript">
  $(document).ready(function(){
    $('.seller_popup').click(function(){
      var seller_id = $(this).attr("id");
      $.ajax({
          url:"orders_ajax.php",
          type:"post",
          data:{seller_id:seller_id},
          success:function(data){
            //alert(data);
            $('#seller_details').html(data);
            $('#dataModal').modal("show");
          }
        });
    });
  });
</script>
