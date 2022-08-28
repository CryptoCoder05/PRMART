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

 if(isset($_GET['edit'])){
   $edit_id = (int)$_GET['edit'];

   $name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
   $addr = ((isset($_POST['addr']))?sanitize($_POST['addr']):'');
   $phone = ((isset($_POST['phone']))?sanitize($_POST['phone']):'');

   $sql2 = "SELECT * FROM credit_customer WHERE id = '$edit_id' AND user_id = $user_id";
   $edit_result = $db->query($sql2);
   $eparties = mysqli_fetch_assoc($edit_result);
   $bill_no = $eparties['bill_no'];
   //update customer
     $name = ((isset($_POST['name']) && !empty($_POST['name']))?sanitize($_POST['name']):$eparties['name']);
     $addr = ((isset($_POST['addr']) && !empty($_POST['addr']))?sanitize($_POST['addr']):$eparties['address']);
     $phone = ((isset($_POST['phone']) && !empty($_POST['phone']))?sanitize($_POST['phone']):$eparties['phone']);
   $errors = array();
   // validating
   if($_POST){ // if submit btn is click, if condition.
     $required = array('name','addr','phone');
     // Check empty feild.
     foreach($required as $f){
       if(empty($_POST[$f])){
         $errors[] = 'You must fill out all field!';
         break;
       }
     }
     if(!empty($errors)){
       echo display_errors($errors);
     } else{ // if no errors - else condition.
         $db->query("UPDATE `credit_customer` SET `name`='$name',`address`='$addr',`phone`='$phone' WHERE id = '$edit_id'");

       $_SESSION['success_flash'] = 'Customer has been Updated';
       header('Location: credit_cus.php');
     } // end of - if no errors - else condition.
   } // end of - if submit btn is click, if condition.

  ?>
  <section class="container-fluid">
  <h2 class="text-center">Update Customer</h2><hr />
  <form action="credit_cus.php?edit=<?=$edit_id;?>" method="post">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="name">Full Name * :</label>
        <input type="text" name="name" id="name" class="form-control" value="<?=$name; ?>" />
      </div>
      <div class="form-group">
        <label for="addr">Full address * :</label>
        <input type="text" name="addr" id="addr" class="form-control" value="<?=$addr; ?>" />
      </div>
      <div class="form-group">
        <label for="phone">phone No * :</label>
        <input type="text" name="phone" id="phone" class="form-control" value="<?=$phone;?>" />
      </div>
      <div class="form-group pull-right" style="margin-top:25px;">
        <a href="credit_cus.php" class="btn btn-default">Cancel</a>
        <input type="submit" name="submit" value="Update Customer" class="btn btn-primary" />
      </div>
    </div>
    <div class="col-md-4"></div>
    <div class="clearfix"></div>
  </form>
</section>

  <?php
 } // end of add an users btn if condition.
 else{ // show user table.
 ?>
<section class="container-fluid" id="credit_customer">
  <h2 class="text-center">CREDIT CUSTOMER & SUPPLIER</h2>
  <hr />
  <div class="row text-center">
    <form class="form-inline" action="credit_cus_details.php" method="post">
      <input list="serach_cc" name="credit_cus_Name" class="form-control" placeholder="Search By Name..." value="" style="width:350px;">
           <datalist id="serach_cc">
             <?php
             $query = "SELECT * FROM credit_customer WHERE deleted = 0 AND user_id = $user_id";
             $result_cc = $db->query($query);
             while ($result = mysqli_fetch_assoc($result_cc)):
              ?>
             <option value="<?=$result['name'];?>">
            <?php endwhile; ?>
           </datalist>
          <button type="submit" name="search_cc" class="btn btn-success" style="letter-spacing:2px"><i class="fas fa-search"></i> Search</button>
    </form>
  </div>

  <div class="row" style="margin-right:2px;letter-spacing:2px;">
    <a href="add_new_credit_cus.php" class="btn btn-lg btn-warning" style="float:right;margin-bottom:10px;">Add Customer <i class="fas fa-address-book"></i></a>
    <a href="add_new_supplier.php" class="btn btn-lg btn-info" style="float:right;margin:0 4px 10px 0;">Add Supplier <i class="fas fa-address-book"></i></a>
  </div>

  <table class="table table-bordered table-striped table-condensed">
    <thead>
      <th>Edit & Delete</th>
      <th>Name</th>
      <th>Address</th>
      <th>Phone No.</th>
      <th>Balance Due</th>
      <th>Bill date</th>
      <th>Details</th>
    </thead>
    <tbody>
      <?php
      if (has_permission('seller')){
        $credit_Query = $db->query("SELECT * FROM credit_customer WHERE deleted = 0 AND user_id = $user_id ORDER BY name");
      }else {
        $credit_Query = $db->query("SELECT * FROM credit_customer WHERE deleted = 0 ORDER BY name");
      }
      while($customer = mysqli_fetch_assoc($credit_Query)):
        $bill_no = $customer['bill_no'];
        foreach (getBalance($bill_no,$user_id) as $balance) {}
      ?>
        <tr class="<?=($balance['balance_due'] >= 2000)?'danger':'';?>">
          <td>
            <?php if($customer['id'] != $user_data['id']): ?>
              <a href="credit_cus.php?edit=<?=$customer['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
              <button type="button" id="<?=$customer['id'];?>" name="button" class="btn btn-xs btn-default xdelete"><span class="glyphicon glyphicon-remove"></button>
            <?php endif; ?>
          </td>
          <td><?=$customer['name']; ?></td>
          <td><?=$customer['address']; ?></td>
          <td><?=$customer['phone']; ?></td>
          <?php
           if ($customer['type'] == 'supplier') {
             ?>
             <td>
               <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up-right text-info" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                 <path fill-rule="evenodd" d="M6.5 4a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V4.5H7a.5.5 0 0 1-.5-.5z"/>
                 <path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-9 9a.5.5 0 0 1-.708-.708l9-9a.5.5 0 0 1 .708 0z"/>
               </svg>
               <?=money($balance['balance_due']); ?>
             </td>
             <?php
           }else {
             ?>
             <td>
               <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down-left text-warning" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                 <path fill-rule="evenodd" d="M3 7.5a.5.5 0 0 1 .5.5v4.5H8a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5V8a.5.5 0 0 1 .5-.5z"/>
                 <path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-9 9a.5.5 0 0 1-.708-.708l9-9a.5.5 0 0 1 .708 0z"/>
               </svg>
               <?=money($balance['balance_due']); ?>
             </td>
             <?php
           }
           ?>
          <td><?=pretty_date($customer['credit_date']) ;?></td>
          <td>
            <a href="credit_cus_details.php?credit_cus_Name=<?=$customer['name'];?>" class="btn btn-xs btn-<?=($customer['type'] == 'supplier')?'info':'warning';?>" style="width:110px;letter-spacing:1px;"><?=$customer['type'];?> <i class="fas fa-info-circle"></i></a>
          </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</section>
<?php } // show user table.
 include 'includes/footer.php';
 ?>

 <!--deleted through ajax-->
 <script type="text/javascript">
     $(document).ready(function() {
         $(".xdelete").click(function() {
           var delete_id = $(this).attr('id');
           var th = $(this);
             Swal.fire({
               title: 'Are you sure?',
               icon: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yes, Delete it!'
             }).then((result) => {
               if (result.value) {
                 if(delete_id != ''){
                   $.ajax({
                     url      : 'deleted.php',
                     method   : 'POST',
                     data     : {delete_credit_cus_id : delete_id},
                     datatype : 'text',
                     success : function(data){
                       th.parents('tr').hide();
                       Swal.fire({
                         icon: 'success',
                         title: 'Customer has been deleted.',
                         showConfirmButton: false,
                         timer: 1500
                       });
                     },
                     error : function(){
                       swal ( "Oops" ,  "Something went wrong!" ,  "error" );
                     },
                   });
                 }
               }
             })
         });
     });
 </script>
