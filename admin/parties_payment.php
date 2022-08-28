
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
 $party_id = $_GET['id'];
 $sub_total = $_GET['total'];
 $parQ = $db->query("SELECT * FROM parties WHERE id = '$party_id' AND user_id = $user_id");
 $par_result = mysqli_fetch_assoc($parQ);
 $name = $par_result['party_name'];
 $phone = $par_result['phone'];
  ?>

<!--Report table-->
<section>
  <div id="print_receipt">
  <div class="container-fluid">
    <div class="text-center">
      <h2 class="text-info"><?=SHOPNAME; ?></h2>
      <p>Address: Ramgopalpur-5, Mahottari Nepal, ph. no.:9812189484, Email: shahpranil44@gmail.com</p>
      <h4 style="text-decoration:underline">Payment Details</h4>
    </div>
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <div>
        <span>Name : </span><span style="font-size:13px;"> <?=$name;?></span> <br>
        <span>Contact No. : </span><span style="font-size:13px;"><?=$phone;?></span>
      </div>
      <div class="row">
          <table class="table table-condensed">
              <tr>
                <th>#</th>
                <th>Bill date</th>
                <th>Paid Amount</th>
                <th>Paid date</th>
              </tr>
            <tbody>
              <?php
               $i = 0;
               $total_paid = 0;
               $txnQ = $db->query("SELECT * FROM parties_payment WHERE party_id = '$party_id' ORDER BY bill_date");
               while ($txn_result = mysqli_fetch_assoc($txnQ)):
                 $bill_date = $txn_result['bill_date'];
                 $paid = $txn_result['paid'];
                 $total_paid += $paid;
                 $i++;
               ?>
              <tr>
                <td><?=$i;?></td>
                <td><?=$bill_date;?></td>
                <td><?=money($paid);?></td>
                <td><?=$txn_result['paid_date'];?></td>
              </tr>
            <?php endwhile;
            $balance = $sub_total - $total_paid;
            ?>

            </tbody>
          </table>
      </div><!--end of 1st row-->
      <div class="row">
          <table class="table table-condense">
            <tfoot>
              <tr class="info">
                <td></td>
                <td>Sub Total</td>
                <td><?=$sub_total;?></td>
              </tr>
              <tr class="info">
                <td></td>
                <td>Paid</td>
                <td>
                  <?php
                   echo $total_paid;
                  ?>
                </td>
              </tr>
              <tr class="info">
                <td></td>
                <td>Balance</td>
                <td>
                  <?php
                   echo $balance;
                  ?>
                </td>
              </tr>
              <tr class="text-center">
                <td colspan="3">For, Nepalwoodsale</td>
              </tr>
            </tfoot>
          </table>
      </div><!--end of second row-->
      <div class="pull-right" style="margin:0 10px 20px 0;">
        <a href="parties.php" class="btn btn-default">Back</a>
      </div>
    </div>
    <div class="col-md-4"></div>
  </div><!--end of container fluid-->
</div><!--end of print report-->
</section><!--End of Report table-->

<?php include 'includes/footer.php'; ?>
