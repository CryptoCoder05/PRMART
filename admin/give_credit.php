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
  if (isset($_GET['bill_no_gc'])) {
    $bill_no_gc = sanitize($_GET['bill_no_gc']);
    foreach (creditCus('credit_customer',$bill_no_gc,$user_id) as $cus_det) {
      $name = $cus_det['name'];
    }
  }

 if (isset($_POST['give_credit'])) {
   $bill_no = sanitize($_POST['bill_no']);
   $prod = sanitize($_POST['prod']);
   $credit_amt = sanitize($_POST['credit_given']);

   $date = sanitize($_POST['date']);
   $date = date_create($date);
   if (!empty($date)) {
     $date = date_format($date, 'Y-m-d H:i:s');
   }else {
     $date = date('Y-m-d H:i:s');
   }

   $query = "INSERT INTO `credit_cus_statement`(`user_id`, `bill_no`, `product`, `credit_amt`,`txn_date`)
                                        VALUES ('$user_id','$bill_no','$prod','$credit_amt','$date')";
   $run = $db->query($query);
   if ($run === true) {
     redirect('credit_cus_details.php?credit_cus_Name='.$name);
    }
 }
  ?>

  <section id="add_new_credit_cus">
    <div class="container">
      <h3 class="text-center">Give Credit</h3><hr>
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="name">Full Name * :</label>
          <input type="text" form="give_credit" name="name" id="name" class="form-control" value="<?=(isset($name))?$name:'';?>" disabled/>
          <input type="hidden" form="give_credit" name="bill_no" id="bill_no" class="form-control" value="<?=(isset($bill_no_gc))?$bill_no_gc:'';?>"/>
        </div>
        <div class="form-group">
          <label for="prod">Note * :</label>
          <textarea class="form-control" form="give_credit" name="prod" id="prod" rows="3" placeholder="Enter your Product list..." required></textarea>
        </div>
        <div class="form-group">
          <label for="credit_given">Credit Amount * :</label>
          <input type="text" form="give_credit" name="credit_given" id="credit_g" class="form-control" placeholder="Enter Total Amount" value="<?=(isset($credit_given))?$credit_given:'';?>" onchange="echange_gc(this)" required/>
        </div>
        <div class="form-group">
          <label for="date">Date (optional) :</label>
          <input type="date" form="give_credit" name="date" id="date" class="form-control" value="<?=(isset($date))?$date:'';?>" />
        </div>
        <form action="give_credit.php?bill_no_gc=<?=$bill_no_gc;?>" method="POST" id="give_credit">
          <button type="submit" name="give_credit" class="btn btn-lg btn-warning" style="float:right;width:230px;letter-spacing:2px;"><?=(isset($_GET['type']))?'Received Credit':'Give Credit';?> <i class="fas fa-location-arrow"></i></button>
        </form>
      </div>
      <div class="col-md-3"></div>
    </div>
  </section>

   <?php include 'includes/footer.php'; ?>

<!--check numberic value-->
   <script type="text/javascript">
   function echange_gc(e) {
     var crdt_gin = document.getElementById('credit_g').value;
     if(isNaN(crdt_gin)){
       e.style.borderColor="#f34c70";
       swal ( "Oops" ,  "Please Enter only Numberic value in Credit Given!" ,  "error" );
     }else {
       e.style.borderColor="lightgray";
     }
   }
   </script>
