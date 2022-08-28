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
 <!-- Insert if credit given-->
 <?php
  if (isset($_GET['bill_no_rc'])) {
    $bill_no_gc = sanitize($_GET['bill_no_rc']);
    foreach (creditCus('credit_customer',$bill_no_gc,$user_id) as $cus_det) {
      $name = $cus_det['name'];
    }
  }

 if (isset($_POST['receive_credit_button'])) {
   $bill_no = sanitize($_POST['bill_no']);
   $prod = sanitize($_POST['prod']);
   $receive_amt = sanitize($_POST['receive_credit']);

   $date = sanitize($_POST['date']);
   $date = date_create($date);
   if (!empty($date)) {
     $date = date_format($date, 'Y-m-d H:i:s');
   }else {
     $date = date('Y-m-d H:i:s');
   }

   $query = "INSERT INTO `credit_cus_statement`(`user_id`, `bill_no`, `product`, `deposit_amt`,`txn_date`)
                                        VALUES ('$user_id','$bill_no','$prod','$receive_amt','$date')";
   $run = $db->query($query);
   if ($run === true) {
     redirect('credit_cus_details.php?credit_cus_Name='.$name);
   }
 }
  ?>

  <section id="receive_credit">
    <div class="container">
      <h3 class="text-center">Receive Payment</h3><hr>
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <form action="receive_credit.php?bill_no_rc=<?=$bill_no_gc;?>" method="POST">
        <div class="form-group">
          <label for="name">Full Name * :</label>
          <input type="text" name="name" id="name" class="form-control" value="<?=(isset($name))?$name:'';?>" disabled/>
          <input type="hidden" name="bill_no" id="bill_no" class="form-control" value="<?=(isset($bill_no_gc))?$bill_no_gc:'';?>"/>
        </div>
        <div class="form-group">
          <label for="prod">Note * :</label>
          <textarea class="form-control"  name="prod" id="prod" rows="3" placeholder="Enter your Note here..." required></textarea>
        </div>
        <div class="form-group">
          <label for="receive_credit">Receive Payment * :</label>
          <input type="text" name="receive_credit" id="rec_credit" class="form-control" placeholder="Enter Total Amount" value="<?=(isset($receive_amt))?$receive_amt:'';?>" onchange="echange_rc(this)" required/>
        </div>
        <div class="form-group">
          <label for="date">Date (optional) :</label>
          <input type="date" name="date" id="date" class="form-control" value="<?=(isset($date))?$date:'';?>" />
        </div>
          <button type="submit" name="receive_credit_button" class="btn btn-lg btn-info" style="float:right;width:230px;letter-spacing:2px;"><?=(isset($_GET['type']))?'Give Payment':'Received Payment';?> <i class="fas fa-location-arrow"></i></button>
        </form>
      </div>
      <div class="col-md-3"></div>
    </div>
  </section>

   <?php include 'includes/footer.php'; ?>

   <!--check numberic value-->
   <script type="text/javascript">
   function echange_rc(e) {
     var rec_crdt = document.getElementById('rec_credit').value;
     if(isNaN(rec_crdt)){
       e.style.borderColor="#f34c70";
       swal ( "Oops" ,  "Please Enter only Numberic value in Receive Payment!" ,  "error" );
     }else {
       e.style.borderColor="lightgray";
     }
   }
   </script>
