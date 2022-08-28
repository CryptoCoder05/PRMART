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

 $party_id = ((isset($_GET['party_id']))?sanitize($_GET['party_id']):'');
 $sql = "SELECT * FROM parties WHERE id = '$party_id' AND user_id = $user_id";
 $result = $db->query($sql);
 $parties = mysqli_fetch_assoc($result);
 $party_name = $parties['party_name'];

 // Add a Bill_no.
 if(isset($_POST['add']) || isset($_POST['edit'])){
   $party_id = sanitize($_GET['party_id']);
   $bill_no = ((isset($_POST['bill_no']))?sanitize($_POST['bill_no']):'');
   $bill_date = ((isset($_POST['bill_date']))?sanitize($_POST['bill_date']):'');
   $payment = ((isset($_POST['paid']))?sanitize($_POST['paid']):'');

   $errors = array();
   // validating
   if($_POST){
     $bill_no_Qry = $db->query("SELECT * FROM party_bill_no WHERE bill_no = '$bill_no' AND parties_id = '$party_id'");
     $billCount = mysqli_num_rows($bill_no_Qry);
       if($billCount != 0){
         $errors[] = 'That Bill number already exit in our database!';
       }
     $required = array('bill_date');
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
       $sql = "INSERT INTO `party_bill_no`(`user_id`,`parties_id`, `bill_no`, `bill_date`,`paid`) VALUES ('$user_id','$party_id','$bill_no','$bill_date','$payment')";
       $db->query($sql);
      if ($payment > 0) {

        $pay_sql = "  INSERT INTO `parties_payment`(`user_id`, `party_id`, `bill_date`, `paid`)
                                            VALUES ('$user_id','$party_id','$bill_date','$payment')";
        $db->query($pay_sql);
      }
       $_SESSION['success_flash'] = 'Bill No. has been Added';
       header('Location: add_bill_no.php?party_id='.$party_id);
     } // end of - if no errors - else condition.
   } // end of - if submit btn is click, if condition.
 }
  ?>

  <section class="container-fluid">
      <div class="col-md-4">
        <h2 class="text-center">Add a new Bill No.</h2><hr />
        <form action="add_bill_no.php?party_id=<?=$party_id;?>" method="post">
            <div class="form-group">
              <label for="name">Party Name * :</label>
              <input type="text" name="name" id="name" class="form-control" value="<?=$party_name;?>" readonly/>
            </div>
            <div class="form-group">
              <label for="bill_no">Bill No :</label>
              <input type="text" name="bill_no" id="bill_no" class="form-control" value="" />
            </div>
            <div class="form-group">
              <label for="bill_date">Bill Date * :</label>
              <input type="date" name="bill_date" id="bill_date" class="form-control" value="" />
            </div>
            <div class="form-group">
              <label for="paid">Paid :</label>
              <input type="text" name="paid" id="paid" class="form-control" value="0" />
            </div>
            <div class="form-group pull-right" style="margin-top:25px;">
              <a href="parties.php" class="btn btn-default">Back</a>
              <input type="submit" name="add" value="Add Bill No." class="btn btn-primary" />
            </div>
          <div class="clearfix"></div>
        </form>
      </div>

    <div class="col-md-8">
      <h2 class="text-center">Bill Details</h2><hr>
      <table class="table table-bordered table-striped table-condensed">
         <thead>
           <th class="text-center">Edit & Delete</th>
           <th>Bill_no</th>
           <th>Bill Date (Y-M-D)</th>
           <th>Total</th>
           <th>Paid</th>
           <th>Balance</th>
           <th>Add product</th>
         </thead>
         <tbody>
           <?php
              $party_bill_no_Q = $db->query("SELECT * FROM party_bill_no WHERE parties_id = '$party_id' AND deleted = '0' ORDER BY bill_date");
              while ($res_party_bill_no = mysqli_fetch_assoc($party_bill_no_Q)) :
                $bill_id = $res_party_bill_no['id'];
                $party_prod_Q = $db->query("SELECT * FROM parties_product WHERE party_id = '$party_id' AND party_bill_id = '$bill_id' AND deleted = '0' AND user_id = $user_id");
                $total = 0;
                $grand_total =0;
                while ($party_prod_result = mysqli_fetch_assoc($party_prod_Q)) {
                  $total = $party_prod_result['cost_price'] * $party_prod_result['qty'];
                  $grand_total += $total;
                }
                $bill_no = $res_party_bill_no['bill_no'];
                $bill_date = $res_party_bill_no['bill_date'];
                $bill_paid = $res_party_bill_no['paid'];
            ?>
           <tr>
             <td class="text-center">
               <a href="update_bill_no.php?edit=<?=$res_party_bill_no['id'];?>&party_id=<?=$res_party_bill_no['parties_id'];?>&grand_total=<?=$grand_total;?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span> Pay</a>
               <button type="button" id="<?=$res_party_bill_no['id']; ?>" name="button" class="btn btn-xs btn-default xdelete"><span class="glyphicon glyphicon-remove"></button>
             </td>
             <td><?=$bill_no;?></td>
             <td><?=$bill_date;?></td>
             <td><?=money($grand_total);?></td>
             <td><?=money($bill_paid);?></td>
             <td><?=money($grand_total-$bill_paid);?></td>
             <td>
               <a href="add_parties_product.php?add=1&bill_id=<?=$res_party_bill_no['id'];?>&party_id=<?=$party_id;?>" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus"></span> Add Product</a>
             </td>
           </tr>
         <?php endwhile ?>
         </tbody>
      </table>
    </div>
</section>

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
                    data     : {delete_bill_id : delete_id},
                    datatype : 'text',
                    success : function(data){
                      th.parents('tr').hide();
                      Swal.fire({
                        icon: 'success',
                        title: 'Bill has been deleted.',
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
