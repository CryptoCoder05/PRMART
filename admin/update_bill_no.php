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

 $total_pay = 0;

 $party_id = ((isset($_GET['party_id']))?sanitize($_GET['party_id']):'');
 $edit_id = ((isset($_GET['edit']))?sanitize($_GET['edit']):'');
 $grand_total = ((isset($_GET['grand_total']))?sanitize($_GET['grand_total']):'0');
 $sql = "SELECT * FROM parties WHERE id = '$party_id' AND user_id = $user_id";
 $result = $db->query($sql);
 $parties = mysqli_fetch_assoc($result);
 $party_name = $parties['party_name'];

 $bill_no = ((isset($_POST['bill_no']))?sanitize($_POST['bill_no']):'');
 $bill_date = ((isset($_POST['bill_date']))?sanitize($_POST['bill_date']):'');
 $payment_parties = ((isset($_POST['paid']))?sanitize($_POST['paid']):'');

   $sql2 = "SELECT * FROM party_bill_no WHERE id = '$edit_id'";
   $edit_result = $db->query($sql2);
   $eparties = mysqli_fetch_assoc($edit_result);
   $bill_no = ((isset($_POST['bill_no']) && !empty($_POST['bill_no']))?sanitize($_POST['bill_no']):$eparties['bill_no']);
   $bill_date = ((isset($_POST['bill_date']) && !empty($_POST['bill_date']))?sanitize($_POST['bill_date']):$eparties['bill_date']);
   $payment = ((isset($_POST['paid']) && !empty($_POST['paid']))?sanitize($_POST['paid']):$eparties['paid']);

   // update a Bill_no.
   if(isset($_POST['update'])){
   $errors = array();
   // validating
   if($_POST){
     $required = array('bill_no','bill_date');
     // Check empty feild.
     foreach($required as $f){
       if(empty($_POST[$f])){
         $errors[] = 'You must fill out all field!';
         break;
       }
     }

     if(!empty($errors)){
       echo display_errors($errors);
     }

     else{ // if no errors - else condition.
       $pay_ment = $eparties['paid'];
       if ($payment != $pay_ment) {
         $total_pay = $pay_ment + $payment_parties;
       }
       elseif ($payment_parties == $pay_ment) {
         $total_pay = $pay_ment + $payment_parties;
       }else {
         $total_pay = $payment;
       }

       if ($payment_parties > 0) {
         $pay_sql = "INSERT INTO `parties_payment`(`party_id`, `bill_date`,`paid`) VALUES ('$party_id','$bill_date','$payment_parties')";
         $db->query($pay_sql);
       }

       if (is_numeric($payment_parties)) {
         $db->query("UPDATE `party_bill_no` SET `bill_no`='$bill_no',`bill_date`='$bill_date',`paid`='$total_pay' WHERE id = '$edit_id'");
       }
       $_SESSION['success_flash'] = 'Bill No. has been Updated';
       header('Location: add_bill_no.php?party_id='.$party_id);
     } // end of - if no errors - else condition.
   } // end of - if submit btn is click, if condition.
 }
  ?>

  <section class="container-fluid">
    <div class="col-md-3"></div>
      <div class="col-md-6">
        <h2 class="text-center">Update a new Bill No.</h2><hr />
        <form action="update_bill_no.php?edit=<?=$edit_id;?>&party_id=<?=$party_id;?>" method="post">
            <div class="form-group">
              <label for="name">Party Name * :</label>
              <input type="text" name="name" id="name" class="form-control" value="<?=$party_name;?>" readonly/>
            </div>
            <div class="form-group">
              <label for="bill_no">Bill No * :</label>
              <input type="text" name="bill_no" id="bill_no" class="form-control" value="<?=$bill_no;?>" />
            </div>
            <div class="form-group">
              <label for="bill_date">Bill Date * :</label>
              <input type="date" name="bill_date" id="bill_date" class="form-control" value="<?=$bill_date;?>" />
            </div>
            <div class="form-group">
              <label for="paid">Paid :</label>
              <input type="text" name="paid" id="paid" class="form-control" placeholder="Total = <?=money($grand_total);?>, Paid = <?=money($payment);?>, Balance = <?=money($grand_total-$payment);?>" value="" />
            </div>
            <div class="form-group pull-right" style="margin-top:25px;">
              <a href="parties.php?" class="btn btn-default">Back</a>
              <input type="submit" name="update" value="Update" class="btn btn-primary" />
            </div>
          <div class="clearfix"></div>
        </form>
      </div>


</section>
