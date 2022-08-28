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
 if (isset($_GET['credit_cus_Name'])) {
   $credit_cus_name = $_GET['credit_cus_Name'];
 }

 if (isset($_POST['search_cc'])) {
   $credit_cus_name = $_POST['credit_cus_Name'];
 }

if ($credit_cus_name == '') {
  redirect('credit_cus.php');
}

 $cusQ = $db->query("SELECT * FROM credit_customer WHERE name = '$credit_cus_name' AND user_id = $user_id AND deleted = 0");
 $cus_result = mysqli_fetch_assoc($cusQ);
 $type = $cus_result['type'];
 $count = mysqli_num_rows($cusQ);

 // check data in database...
 if ($count == 0) {
   ?>
    <script type="text/javascript">
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '<?=$credit_cus_name;?> doesn\'t exits in our records!!',
      })
    </script>
   <?php
 }
  ?>

<!--Report table-->
<?php
if ($type == 'supplier') {
  ?>
  <section id="credit_cus_detail">
    <div id="print_receipt">
    <div class="container">
      <div class="text-center row">
        <h2 class="text-dark" style="font-weight: bold;"><?=SHOPNAME; ?></h2>
        <span>Email: <?=getShop($user_id)[0]['email'];?></span>
        <hr>
        <h4 style="font-weight: bold;text-decoration:underline;">Party Statement Report</h4>
        <h5 style="font-weight: bold;">Party : <?=$cus_result['name'];?></h5>
        <h5 style="font-weight: bold;">Generated on : <?=date('d/m/Y g:i a');?></h5>
      </div>

      <div class="row">
        <?php
        $due = 0;
        $total_credit = 0;
        $total_deposit = 0;
        $cusQ = $db->query("SELECT * FROM credit_customer WHERE name = '$credit_cus_name' AND user_id = $user_id");
        $cus_result = mysqli_fetch_assoc($cusQ);
        $cus_bill_no = $cus_result['bill_no'];

        foreach (creditCusStm('credit_cus_statement',$cus_bill_no,$user_id) as $credit_cus_statement):
          $credit = $credit_cus_statement['credit_amt'];
          $deposit = $credit_cus_statement['deposit_amt'];
          $total_credit += $credit;
          $total_deposit += $deposit;
        endforeach;
        $due = $total_credit - $total_deposit;
          ?>
        <div class="" style="height:100px;width:250px;border:2px solid black;margin:20px;padding:2px 0 0 15px;border-radius:12px;">
          <h3><?=money($due);?></h3>
          <h4>To Pay</h4>
        </div>
      </div>

      <div class="row">
        <table class="table">
          <thead>
            <th></th>
            <th>Date</th>
            <th>Transaction Type</th>
            <th>Note</th>
            <th>Given</th>
            <th>Received</th>
            <th>Balance Due</th>
          </thead>
          <tbody>
            <?php
            $i = 0;
            $balance = 0;
            $total_credit = 0;
            $total_deposit = 0;
            $cusQ = $db->query("SELECT * FROM credit_customer WHERE name = '$credit_cus_name' AND user_id = $user_id");
            $cus_result = mysqli_fetch_assoc($cusQ);
            $cus_bill_no = $cus_result['bill_no'];

            foreach (creditCusStm('credit_cus_statement',$cus_bill_no,$user_id) as $credit_cus_statement):
              $date = date_create($credit_cus_statement['txn_date']);
              $date = date_format($date, 'd/m/Y g:i A');
              $credit = $credit_cus_statement['credit_amt'];
              $deposit = $credit_cus_statement['deposit_amt'];
              $total_credit += $credit;
              $total_deposit += $deposit;
              $balance += $credit - $deposit;
              $i++;
              ?>
            <tr>
              <td><?=$i;?></td>
              <td><?=$date;?></td>
              <td><?=($credit_cus_statement['credit_amt'] > 0)?'Credit Received':'Payment Given';?></td>
              <td><?=$credit_cus_statement['product'];?></td>

              <td>
                <?php
                  if ($deposit > 0) {
                    ?>
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up-right text-warning" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M6.5 4a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V4.5H7a.5.5 0 0 1-.5-.5z"/>
                      <path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-9 9a.5.5 0 0 1-.708-.708l9-9a.5.5 0 0 1 .708 0z"/>
                    </svg>
                    <?php
                    echo money($deposit);
                  }else {
                    echo '';
                  }
                 ?>
              </td>

              <td>
                <?php
                 if ($credit > 0) {
                   ?>
                   <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down-left text-info" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                     <path fill-rule="evenodd" d="M3 7.5a.5.5 0 0 1 .5.5v4.5H8a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5V8a.5.5 0 0 1 .5-.5z"/>
                     <path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-9 9a.5.5 0 0 1-.708-.708l9-9a.5.5 0 0 1 .708 0z"/>
                   </svg>
                   <?php
                   echo money($credit);
                 }else {
                   echo '';
                 }
                 ?>
              </td>

              <td><?=money($balance);?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="success">
              <td colspan="3"></td>
              <td>Total</td>
              <td><?=money($total_deposit);?></td>
              <td><?=money($total_credit);?></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div><!--end of container fluid-->
  </div><!--end of print report-->
  <div class="container">
    <div class="row" style="float:right;letter-spacing:2px;margin-bottom:25px;">
      <button type="button" name="button" onclick="print_fun('print_receipt')" class="btn btn-default btn-lg" style="letter-spacing:2px;"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
      <a href="receive_credit.php?bill_no_rc=<?=$cus_bill_no;?>&type=<?=$type;?>" class="btn btn-info btn-lg" style="width:230px;">Give Payment
        <svg width="3em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up-right text-warning" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M6.5 4a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V4.5H7a.5.5 0 0 1-.5-.5z"/>
          <path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-9 9a.5.5 0 0 1-.708-.708l9-9a.5.5 0 0 1 .708 0z"/>
        </svg>
      </a>
      <a href="give_credit.php?bill_no_gc=<?=$cus_bill_no;?>&type=<?=$type;?>" class="btn btn-warning btn-lg" style="width:230px;">Receive Credit
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down-left text-info" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M3 7.5a.5.5 0 0 1 .5.5v4.5H8a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5V8a.5.5 0 0 1 .5-.5z"/>
          <path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-9 9a.5.5 0 0 1-.708-.708l9-9a.5.5 0 0 1 .708 0z"/>
        </svg>
      </a>
    </div>
  </div>
  </section><!--End of Report table-->
  <?php
}else {
  ?>
  <section id="credit_cus_detail">
    <div id="print_receipt">
    <div class="container">
      <div class="text-center row">
        <h2 class="text-dark" style="font-weight: bold;"><?=SHOPNAME; ?></h2>
        <span>Email: <?=getShop($user_id)[0]['email'];?></span>
        <hr>
        <h4 style="font-weight: bold;text-decoration:underline;">Party Statement Report</h4>
        <h5 style="font-weight: bold;">Party : <?=$cus_result['name'];?></h5>
        <h5 style="font-weight: bold;">Generated on : <?=date('d/m/Y g:i a');?></h5>
      </div>

      <div class="row">
        <?php
        $due = 0;
        $total_credit = 0;
        $total_deposit = 0;
        $cusQ = $db->query("SELECT * FROM credit_customer WHERE name = '$credit_cus_name' AND user_id = $user_id");
        $cus_result = mysqli_fetch_assoc($cusQ);
        $cus_bill_no = $cus_result['bill_no'];

        foreach (creditCusStm('credit_cus_statement',$cus_bill_no,$user_id) as $credit_cus_statement):
          $credit = $credit_cus_statement['credit_amt'];
          $deposit = $credit_cus_statement['deposit_amt'];
          $total_credit += $credit;
          $total_deposit += $deposit;
        endforeach;
        $due = $total_credit - $total_deposit;
          ?>
        <div class="" style="height:100px;width:250px;border:2px solid black;margin:20px;padding:2px 0 0 15px;border-radius:12px;">
          <h3><?=money($due);?></h3>
          <h4>To Receive</h4>
        </div>
      </div>

      <div class="row">
        <table class="table">
          <thead>
            <th></th>
            <th>Date</th>
            <th>Transaction Type</th>
            <th>Note</th>
            <th>Given</th>
            <th>Received</th>
            <th>Balance Due</th>
          </thead>
          <tbody>
            <?php
            $i = 0;
            $balance = 0;
            $total_credit = 0;
            $total_deposit = 0;
            $cusQ = $db->query("SELECT * FROM credit_customer WHERE name = '$credit_cus_name' AND user_id = $user_id");
            $cus_result = mysqli_fetch_assoc($cusQ);
            $cus_bill_no = $cus_result['bill_no'];

            foreach (creditCusStm('credit_cus_statement',$cus_bill_no,$user_id) as $credit_cus_statement):
              $date = date_create($credit_cus_statement['txn_date']);
              $date = date_format($date, 'd/m/Y g:i A');
              $credit = $credit_cus_statement['credit_amt'];
              $deposit = $credit_cus_statement['deposit_amt'];
              $total_credit += $credit;
              $total_deposit += $deposit;
              $balance += $credit - $deposit;
              $i++;
              ?>
            <tr>
              <td><?=$i;?></td>
              <td><?=$date;?></td>
              <td><?=($credit_cus_statement['credit_amt'] > 0)?'Credit Given':'Payment Received';?></td>
              <td><?=$credit_cus_statement['product'];?></td>
              <td>
                <?php
                 if ($credit > 0) {
                   ?>
                   <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up-right text-warning" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                     <path fill-rule="evenodd" d="M6.5 4a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V4.5H7a.5.5 0 0 1-.5-.5z"/>
                     <path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-9 9a.5.5 0 0 1-.708-.708l9-9a.5.5 0 0 1 .708 0z"/>
                   </svg>
                   <?php
                   echo money($credit);
                 }else {
                   echo '';
                 }
                 ?>
              </td>
              <td>
                <?php
                  if ($deposit > 0) {
                    ?>
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down-left text-info" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M3 7.5a.5.5 0 0 1 .5.5v4.5H8a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5V8a.5.5 0 0 1 .5-.5z"/>
                      <path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-9 9a.5.5 0 0 1-.708-.708l9-9a.5.5 0 0 1 .708 0z"/>
                    </svg>
                    <?php
                    echo money($deposit);
                  }else {
                    echo '';
                  }
                 ?>
              </td>
              <td><?=money($balance);?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="success">
              <td colspan="3"></td>
              <td>Total</td>
              <td>
                <?=money($total_credit);?>
              </td>
              <td>
                <?=money($total_deposit);?>
              </td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div><!--end of container fluid-->
  </div><!--end of print report-->
  <div class="container">
    <div class="row" style="float:right;letter-spacing:2px;margin-bottom:25px;">
      <button type="button" name="button" onclick="print_fun('print_receipt')" class="btn btn-default btn-lg" style="letter-spacing:2px;"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
      <a href="receive_credit.php?bill_no_rc=<?=$cus_bill_no;?>" class="btn btn-info btn-lg" style="width:230px;">Receive Payment
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down-left text-info" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M3 7.5a.5.5 0 0 1 .5.5v4.5H8a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5V8a.5.5 0 0 1 .5-.5z"/>
          <path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-9 9a.5.5 0 0 1-.708-.708l9-9a.5.5 0 0 1 .708 0z"/>
        </svg>
      </a>
      <a href="give_credit.php?bill_no_gc=<?=$cus_bill_no;?>" class="btn btn-warning btn-lg" style="width:230px;">Give Credit
        <svg width="3em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up-right text-warning" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M6.5 4a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V4.5H7a.5.5 0 0 1-.5-.5z"/>
          <path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-9 9a.5.5 0 0 1-.708-.708l9-9a.5.5 0 0 1 .708 0z"/>
        </svg>
      </a>
    </div>
  </div>
  </section><!--End of Report table-->
  <?php
}
 ?>

<?php include 'includes/footer.php'; ?>

<!--print statement-->
<script type="text/javascript">
  function print_fun(paravalue) {
    var backup = document.body.innerHTML;
    var print_content = document.getElementById(paravalue).innerHTML;
    document.body.innerHTML = print_content;
    window.print();
    document.body.innerHTML = backup;
  }
</script>
