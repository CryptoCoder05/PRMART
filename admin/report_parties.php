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
 $party_id = ((isset($_GET['party_id']))?sanitize($_GET['party_id']):'');
 $paid = ((isset($_GET['paid']))?sanitize($_GET['paid']):'');

 $party_sql = $db->query("SELECT * FROM parties WHERE id = $party_id AND user_id = $user_id");
 $party_result = mysqli_fetch_assoc($party_sql);

 $iQuery = $db->query("SELECT * FROM parties_product WHERE  party_id = '$party_id' AND user_id = $user_id order by title");

 if(isset($_POST['search'])){
   $billdate = sanitize($_POST['bill_date']);
   $billdateQ = $db->query("SELECT * FROM party_bill_no WHERE parties_id ='$party_id' AND bill_date = '$billdate' AND deleted = 0");
   $bill_date_res = mysqli_fetch_assoc($billdateQ);
   $bill_date_id = $bill_date_res['id'];
   $bill_date_paid = $bill_date_res['paid'];
    if ($billdate == "") {
      ?>
      <script>swal('Oops!','You Don\'t Selected Any Bill Date!','warning')</script>
      <?php
    }else {
      $iQuery = $db->query("SELECT * FROM parties_product WHERE  party_id = '$party_id' AND party_bill_id = '$bill_date_id' AND user_id = $user_id order by title");
    }
 }
  ?>
<!--enter date -->
  <section class="text-center" style="margin-top:50px;">
  <div class="container-fluid">
    <form class="form-inline" action="report_parties.php?party_id=<?=$party_id;?>&paid=<?=$paid;?>" method="POST">
      <div class="form-group">
        <label for="channel" class="text-info">Bill Date :</label>
        <select class="form-control" id="bill_date" name="bill_date">
          <option value="">Select Bill Date</option>
          <?php
             $par_prodQ = $db->query("SELECT * FROM party_bill_no WHERE  parties_id = '$party_id' AND deleted = 0 order by bill_date");
             while ($par_prod_res = mysqli_fetch_assoc($par_prodQ)):
              $bill_date = $par_prod_res['bill_date'];
           ?>
          <option value="<?=$bill_date;?>"><?=$bill_date;?></option>
        <?php endwhile ?>
        </select>
      </div>

      <div class="form-group">
         <button type="submit" class="btn btn-danger" name="search" id="search">
           <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
         </button>
      </div>
    </form>
    <button type="button" class="btn btn-default pull-right" aria-label="Left Align" onclick="print_fun('print_receipt')">
       <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
    </button>
  </div>
  <hr />
  </section>
<!--End of date search-->

<!--Report table-->
<section id="refress">
  <div id="print_receipt">
  <div class="container-fluid">
    <div class="text-center">
      <h2 class="text-info"><?=SHOPNAME; ?></h2>
      <p>Address: Ramgopalpur-5, Mahottari Nepal, ph. no.:9812189484, Email: shahpranil44@gmail.com</p>
      <h4 style="text-decoration:underline">Purchased Goods</h4>
    </div>
    <div>
      <span>Bill From : </span><span style="font-size:13px;">
        <?php
          echo $party_result['party_name'];
        ?>
      </span> <br>
      <?php
         echo  '<span>Contact No. : </span><span style="font-size:13px;">'.$party_result['phone'].'</span>';
      ?>
      <span class="pull-right">Bill Date : <span style="font-size:13px; margin-right:20px;"><?=((isset($_POST['search']))?$billdate:'All'); ?></span></span>
    </div>

    <div class="row">
     <div class="col-md-12">
        <table class="table table-condensed">
            <tr>
              <th>#</th>
              <th>Item name</th>
              <th>Size</th>
              <th>Price/unit</th>
              <th>Quantity</th>
              <th>Amount</th>
            </tr>
          <tbody>
            <?php
            $i=0;
            $total_qty = 0;
            $grand_total = 0;
             while ($product_result = mysqli_fetch_assoc($iQuery)):
               $qty = $product_result['qty'];
               $total = $qty * $product_result['cost_price'];
               $grand_total += $total;
               $total_qty +=$qty;
               $i++;
             ?>
            <tr>
              <td><?=$i;?></td>
              <td><?=$product_result['title'];?></td>
              <td><?=$product_result['size'];?></td>
              <td><?=money($product_result['cost_price']);?></td>
              <td><?=$qty;?></td>
              <td><?=money($total);?></td>
            </tr>
          <?php endwhile; ?>
            <tr>
              <td></td>
              <td colspan="3" class="text-center">Total</td>
              <td><?=$total_qty;?></td>
              <td><?=money($grand_total);?></td>
            </tr>
          </tbody>
        </table>
      </div><!--end of column 12-->
    </div><!--end of 1st row-->
     <div class="row">
       <div class="col-md-6">
         <span>Bill Amount in words : </span><span style="font-size:13px;">working on this..</span>
       </div>
       <div class="col-md-6">
         <table class="table table-condense">
           <tfoot>
             <tr>
               <td></td>
               <td>Sub Total</td>
               <td><?=money($grand_total);?></td>
             </tr>
             <tr>
               <td></td>
               <td>Paid</td>
               <td>
                 <?=((isset($_POST['search']))?money($bill_date_paid):money($paid));?>
               </td>
             </tr>
             <tr>
               <td></td>
               <td>Balance</td>
               <td>
                 <?=((isset($_POST['search']))?money($grand_total-$bill_date_paid):money($grand_total-$paid));?>
               </td>
             </tr>
             <tr class="text-center">
               <td colspan="3">For, Nepalwoodsale</td>
             </tr>
           </tfoot>
         </table>
       </div><!--end of col-md-6-->
     </div><!--end of second row-->
  </div><!--end of container fluid-->
</div><!--end of print report-->
<div class="pull-right" style="margin:0 10px 20px 0;">
  <a href="parties.php" class="btn btn-default">Back</a>
</div>
</section><!--End of Report table-->

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
