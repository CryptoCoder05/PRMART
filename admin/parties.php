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

 // Add a parties.
 if(isset($_GET['add']) || isset($_GET['edit'])){ // add an users btn if condition.
   $name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
   $addr = ((isset($_POST['addr']))?sanitize($_POST['addr']):'');
   $phone = ((isset($_POST['phone']))?sanitize($_POST['phone']):'');

   //edit parties
   if(isset($_GET['edit'])){
     $edit_id = (int)$_GET['edit'];
     $edit_id = sanitize($edit_id);
     $sql2 = "SELECT * FROM parties WHERE id = '$edit_id' AND user_id = $user_id";
     $edit_result = $db->query($sql2);
     $eparties = mysqli_fetch_assoc($edit_result);
     $name = ((isset($_POST['name']) && !empty($_POST['name']))?sanitize($_POST['name']):$eparties['party_name']);
     $addr = ((isset($_POST['addr']) && !empty($_POST['addr']))?sanitize($_POST['addr']):$eparties['address']);
     $phone = ((isset($_POST['phone']) && !empty($_POST['phone']))?sanitize($_POST['phone']):$eparties['phone']);
     $bill_no = $eparties['id'];
   }

   $errors = array();
   // validating
   if($_POST){ // if submit btn is click, if condition.
     // check if email exits in the database
     $phoneQry = $db->query("SELECT * FROM parties WHERE phone = '$phone' AND user_id = $user_id");
     $phoneCount = mysqli_num_rows($phoneQry);
     if (isset($_GET['add'])) {
       if($phoneCount != 0){
         $errors[] = 'That phone number already exit in our database!';
       }
     }

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
     }

     else{ // if no errors - else condition.
       // add party to database.
       $sql = "INSERT INTO `parties` (`user_id`,`party_name`, `address`, `phone`) VALUES ('$user_id','$name', '$addr', '$phone')";

       if(isset($_GET['edit'])){
           $sql = "UPDATE parties SET party_name = '$name', address = '$addr', phone = '$phone' WHERE id = '$edit_id' ";
       }
       $db->query($sql);
       if(isset($_GET['edit'])){
         $_SESSION['success_flash'] = 'Party has been Updared';
       }else {
         $_SESSION['success_flash'] = 'Party has been Added';
       }
       header('Location: parties.php');
     } // end of - if no errors - else condition.
   } // end of - if submit btn is click, if condition.

  ?>
  <section class="container-fluid">
  <h2 class="text-center"><?=((isset($_GET['edit']))?'Update party':'Add a new party'); ?></h2><hr />
  <form action="parties.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>" method="post">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="name">Party Name * :</label>
        <input type="text" name="name" id="name" class="form-control" value="<?=$name; ?>" />
      </div>
      <div class="form-group">
        <label for="addr">Party address * :</label>
        <input type="text" name="addr" id="addr" class="form-control" value="<?=$addr; ?>" />
      </div>
      <div class="form-group">
        <label for="phone">phone No * :</label>
        <input type="text" name="phone" id="phone" class="form-control" value="<?=$phone;?>" />
      </div>

      <div class="form-group pull-right" style="margin-top:25px;">
        <a href="parties.php" class="btn btn-default">Cancel</a>
        <input type="submit" name="submit" value="<?=((isset($_GET['edit']))?'Update':'add'); ?> party" class="btn btn-primary" />
      </div>
    </div>
    <div class="col-md-4"></div>

    <div class="clearfix"></div>
  </form>
<section>

  <?php
    /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                                                                          End of add of users
   ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
 } // end of add an users btn if condition.
 else{ // show user table.
 ?>
 <?php /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                                                                        User Table
 ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>
<section class="container-fluid">
  <h2 class="text-center">Parties</h2>
  <a href="parties.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add new Parties</a><div class="clearfix"></div>
  <hr />
  <table class="table table-bordered table-striped table-condensed">
    <thead>
      <th class="text-center">Edit & Delete</th>
      <th>Name</th>
      <th>Address</th>
      <th>Phone No.</th>
      <th>Total Amount</th>
      <th>Paid</th>
      <th>Balance</th>
      <th>Join date</th>
      <th class="text-center">Details</th>
    </thead>
    <tbody>
      <?php
      $all_total = 0;
      $all_paid=0;
      $all_balance=0;
      if (has_permission('seller')){
        $userQuery = $db->query("SELECT * FROM parties WHERE deleted = 0 AND user_id = $user_id ORDER BY party_name");
      }else {
        $userQuery = $db->query("SELECT * FROM parties WHERE deleted = 0 ORDER BY party_name");
      }
      while($user = mysqli_fetch_assoc($userQuery)):
        $party_id = $user['id'];
        $grand_total = 0;
        $balance = 0;
        $threshold = 0;
        $total = 0;
        $product_sql = $db->query("SELECT * FROM parties_product WHERE party_id = '$party_id' AND deleted = 0 AND user_id = $user_id");
        while ($product_result = mysqli_fetch_assoc($product_sql)) {
          $total = $product_result['qty'] * $product_result['cost_price'];
          $grand_total += $total;
        }

        $bill_paid = 0;
        $total_paid = 0;
        $party_bill_sql = $db->query("SELECT * FROM party_bill_no WHERE parties_id = '$party_id' AND deleted = 0");
        while ($party_bill_result = mysqli_fetch_assoc($party_bill_sql)) {
          $bill_paid = $party_bill_result['paid'];
          $total_paid += $bill_paid;
        }
        $total_party_paid = $total_paid;

        $all_total +=$grand_total;
        $all_paid+=$total_party_paid;
        $balance = $grand_total - $total_party_paid;
        $all_balance=$all_total-$all_paid;
      ?>
        <tr>
          <td class="text-center">
            <?php if($user['id'] != $user_data['id']): ?>
              <a href="parties.php?edit=<?=$user['id'];?>&total=<?=$grand_total;?>&balance=<?=$balance;?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
              <button type="button" id="<?=$user['id']; ?>" name="button" class="btn btn-xs btn-default xdelete"><span class="glyphicon glyphicon-remove"></button>
            <?php endif; ?>
          </td>
          <td><?=$user['party_name']; ?></td>
          <td><?=$user['address']; ?></td>
          <td><?=$user['phone']; ?></td>
          <td><?=money($grand_total); ?></td>
          <td><?=money($total_party_paid); ?></td>
          <td><?=money($balance); ?></td>
          <td><?=pretty_date($user['join_date']) ;?></td>
          <td class="text-center">
            <?php if($user['id'] != $user_data['id']): ?>
              <a href="parties_payment.php?id=<?=$user['id'];?>&total=<?=$grand_total;?>&paid=<?=$total_party_paid;?>&balance=<?=$balance;?>" class="btn btn-info btn-xs"><span></span> Payment</a>
              <a href="add_bill_no.php?party_id=<?=$user['id'];?>" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-plus"></span> Bill_No</a>
              <a href="report_parties.php?party_id=<?=$user['id'];?>&paid=<?=$total_party_paid;?>" class="btn btn-info btn-xs"><span></span> Report</a>

            <?php endif; ?>
          </td>
        </tr>
    <?php endwhile; ?>
    <tr class="info">
      <td colspan="4" class="text-center">Total</td>
      <td><?=money($all_total);?></td>
      <td><?=money($all_paid);?></td>
      <td id="parties_balance"><?=money($all_balance);?></td>
      <td colspan="2"></td>
    </tr>
    </tbody>
  </table>
</section>
<?php /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                                                                       End of user Table
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>
<?php } // show user table.

 include 'includes/footer.php';
 ?>

 <script type="text/javascript">
   localStorage.setItem("parties_balance","<?=$all_balance;?>")
 </script>

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
                     data     : {delete_parties_id : delete_id},
                     datatype : 'text',
                     success : function(data){
                       th.parents('tr').hide();
                       Swal.fire({
                         icon: 'success',
                         title: 'Parties has been deleted.',
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
