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

 <!-- get data from field.-->
<?php
$errors = array();
if (isset($_POST['add_new_cc'])) {
  $bill_no = 'OR'.rand(10000,1000000);
  $name = sanitize($_POST['name']);
  $add = sanitize($_POST['addr']);
  $phone = sanitize($_POST['phone']);
  $prod = sanitize($_POST['prod']);
  $credit_given = sanitize($_POST['credit_given']);
  $type = 'supplier';

  $date = sanitize($_POST['date']);
  $date = date_create($date);
  if (!empty($date)) {
    $date = date_format($date, 'Y-m-d H:i:s');
  }else {
    $date = date('Y-m-d H:i:s');
  }

  // duplicate name exits...
  $query_nam_chk = "SELECT * FROM credit_customer WHERE name = '$name' AND deleted = 0 AND user_id = '$user_id'";
  $result_nam_chk = $db->query($query_nam_chk);
  $count = mysqli_num_rows($result_nam_chk);
  if ($count > 0) {
    $errors[] = $name.' supplier is already exits in your database!';
  }

  if (!is_numeric($credit_given)) {
    $errors[] = 'Please enter numberic value only in Credit Amount';
  }

  if (!is_numeric($phone)) {
    $errors[] = 'Please enter numberic value only in Phone No.';
  }

  if (!empty($errors)) {
    echo display_errors($errors);
  }else {
  $query = "INSERT INTO `credit_customer`(`user_id`, `bill_no`, `name`, `address`, `phone`,`type`,`credit_date`)
                                  VALUES ('$user_id','$bill_no','$name','$add','$phone','$type','$date')";
  $run = $db->query($query);
  if ($run === true) {
    $query1 = "INSERT INTO `credit_cus_statement`(`user_id`, `bill_no`, `product`, `credit_amt`,`txn_date`)
                                         VALUES ('$user_id','$bill_no','$prod','$credit_given','$date')";
    $run1 = $db->query($query1);
    if ($run1 === true) {
      redirect('credit_cus.php');
    }
  }
 }
}
 ?>
<section id="add_new_credit_cus">
  <div class="container">
    <h3 class="text-center text-info">ADD NEW SUPPLIER</h3><hr>
    <div class="row">
      <div class="col-md-6">
        <h3>Supplier details</h3><hr>
        <div class="form-group">
          <label for="name">Full Name * :</label>
          <input type="text" form="add_new_CC" name="name" id="name" class="form-control" placeholder="Enter name" value="<?=(isset($name))?$name:'';?>" required/>
        </div>
        <div class="form-group">
          <label for="addr">Full address * :</label>
          <input type="text" form="add_new_CC" name="addr" id="addr" class="form-control" placeholder="Enter Address" value="<?=(isset($add))?$add:'';?>" required/>
        </div>
        <div class="form-group">
          <label for="phone">phone No * :</label>
          <input type="text" form="add_new_CC" name="phone" id="phone" class="form-control" placeholder="Enter Phone No." value="<?=(isset($phone))?$phone:'';?>" required/>
        </div>
        <div class="form-group">
          <label for="price">Date :</label>
          <input type="date" form="add_new_CC" name="date" id="date" class="form-control" value="<?=(isset($date))?$date:'';?>" />
        </div>
      </div>
      <div class="col-md-6">
        <h3>Product details</h3><hr>
        <div class="form-group">
          <label for="prod">Note * :</label>
          <textarea class="form-control" form="add_new_CC" name="prod" id="prod" rows="3" placeholder="Enter your Product list..." required></textarea>
        </div>
        <div class="form-group">
          <label for="price">Credit Amount * :</label>
          <input type="text" form="add_new_CC" name="credit_given" id="cred_amt" class="form-control" placeholder="Enter Total Credit Amount" value="<?=(isset($credit_given))?$credit_given:'';?>" onchange="echange_add_n_cc(this)" required/>
        </div>
        <form action="add_new_supplier.php" method="POSt" id="add_new_CC">
          <button type="submit" name="add_new_cc" class="btn btn-lg btn-info" style="float:right;width:150px;letter-spacing:2px;">Submit <i class="fas fa-location-arrow"></i></button>
          <a href="credit_cus.php" class="btn btn-lg btn-warning" style="float:right;width:150px;letter-spacing:2px;margin-right:4px;"><i class="fas fa-chevron-circle-left"></i> Back</a>
        </form>
      </div>
    </div>
  </div>
</section>

 <?php include 'includes/footer.php'; ?>

 <script type="text/javascript">
 function echange_add_n_cc(e) {
   var crdt_amt = document.getElementById('cred_amt').value;
   if(isNaN(crdt_amt)){
     e.style.borderColor="#f34c70";
     swal ( "Oops" ,  "Please Enter only Numberic value in Credit Amount!" ,  "error" );
   }else {
     e.style.borderColor="lightgray";
   }
 }
 </script>
