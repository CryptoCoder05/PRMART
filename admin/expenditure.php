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

$edit_id = ((isset($_GET['edit']) && !empty($_GET['edit']))?sanitize($_GET['edit']):'');
$exp_name = ((isset($_POST['exp_name']) && !empty($_POST['exp_name']))?sanitize($_POST['exp_name']):'');
$exp_amt = ((isset($_POST['exp_amt']) && !empty($_POST['exp_amt']))?sanitize($_POST['exp_amt']):'');
$exp_date = ((isset($_POST['exp_date']) && !empty($_POST['exp_date']))?sanitize($_POST['exp_date']):'');

if (isset($_GET['edit'])) {
  $edit_id = (int)$_GET['edit'];
  $edit_id = sanitize($edit_id);
  $sql2 = "SELECT * FROM expenditure WHERE id = '$edit_id' AND user_id = $user_id";
  $edit_result = $db->query($sql2);
  $exp = mysqli_fetch_assoc($edit_result);
  $exp_name = ((isset($_POST['exp_name']) && !empty($_POST['exp_name']))?sanitize($_POST['exp_name']):$exp['exp_name']);
  $exp_amt = ((isset($_POST['exp_amt']) && !empty($_POST['exp_amt']))?sanitize($_POST['exp_amt']):$exp['exp_amt']);
  $exp_date = ((isset($_POST['exp_date']) && !empty($_POST['exp_date']))?sanitize($_POST['exp_date']):$exp['exp_date']);
}

 //--If add form is submited--
 $errors = array();
 if(isset($_POST['add'])){
   $exp_name = ((isset($_POST['exp_name']) && !empty($_POST['exp_name']))?sanitize($_POST['exp_name']):'');
   $exp_amt = ((isset($_POST['exp_amt']) && !empty($_POST['exp_amt']))?sanitize($_POST['exp_amt']):'');
   $exp_date = ((isset($_POST['exp_date']) && !empty($_POST['exp_date']))?sanitize($_POST['exp_date']):'');

   //check if brand is blank
   if($_POST['exp_name'] == ''){
     $errors[].='You must enter a exp. name!';
   }
   if($_POST['exp_amt'] == ''){
     $errors[].='You must enter a exp. Amount!';
   }
   if($_POST['exp_date'] == ''){
     $errors[].='You must enter a exp. Date!';
   }
   //dispaly errors--
   if(!empty($errors)){
     echo display_errors($errors);
   }else {
     //--Add expenditure to database
     $sql = "INSERT INTO `expenditure`(`user_id`,`exp_name`, `exp_amt`,`exp_date`)
                               VALUES ('$user_id','$exp_name','$exp_amt','$exp_date')";
     if(isset($_GET['edit'])){
       $sql = "UPDATE `expenditure` SET `exp_name`='$exp_name',`exp_amt`='$exp_amt', `exp_date` = '$exp_date' WHERE id = '$edit_id'";
     }
     $db->query($sql);
     header('Location: expenditure.php');
   }
 }
 ?>
<section class="text-info text-center">
  <h2>Expenditure</h2><hr />
  <!--Expenditure form-->
<div>
  <form class="form-inline" action="expenditure.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
    <div class="row">
        <div class="form-group">
          <label for="exp_name">Expenditure Name *:</label>
          <input type="text" name="exp_name" id="exp_name" class="form-control" value="<?=$exp_name;?>" >
        </div>
        <div class="form-group">
          <label for="exp_amt">Amount *:</label>
          <input type="text" name="exp_amt" id="exp_amt" class="form-control" value="<?=$exp_amt;?>" >
        </div>
        <div class="form-group">
          <label for="exp_date">Date *:</label>
          <input type="date" name="exp_date" id="exp_date" class="form-control" value="<?=$exp_date;?>" >
        </div>
        <a href="index.php" class="btn btn-default">Back</a>
        <input type="submit" name="add" value="<?=((isset($_GET['edit']))?'Edit ':'Add '); ?> Exp." class="btn btn-success" />
      </div>
  </form>
</div><hr />
</section>
<section>
<!--Expenditure table-->
  <div class="col-md-12">
  <table class="table table-bordered table-striped table-auto table-condensed">
    <thead>
      <th>Edit & Delete</th>
      <th>Exp. Name</th>
      <th>Exp. Amount</th>
      <th>Exp. Date</th>
    </thead>
    <tbody>
      <?php
      $total = 0;
      if (has_permission('seller')){
        $results = $db->query("SELECT * FROM expenditure WHERE deleted = 0 AND user_id = $user_id");
      }else {
        $results = $db->query("SELECT * FROM expenditure WHERE deleted = 0");
      }
      while($exp_res = mysqli_fetch_assoc($results)) :
        $amt = $exp_res['exp_amt'];
        $total += $amt;
        ?>
      <tr>
       <td class="text-center">
         <a href="expenditure.php?edit=<?=$exp_res['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
         <button type="button" id="<?=$exp_res['id']; ?>" name="button" class="btn btn-xs btn-default xdelete"><span class="glyphicon glyphicon-remove"></button>
       </td>
       <td><?=$exp_res['exp_name']; ?></td>
       <td><?=money($amt);?></td>
       <td><?=$exp_res['exp_date']; ?></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
    <tfoot>
      <tr>
        <td class="text-center" colspan="2">Total</td>
        <td><?=money($total);?></td>
        <td></td>
        <td></td>
      </tr>
    </tfoot>
  </table>
  </div>
</section>
<?php include 'includes/footer.php'; ?>

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
                    data     : {delete_expenditure_id : delete_id},
                    datatype : 'text',
                    success : function(data){
                      th.parents('tr').hide();
                      Swal.fire({
                        icon: 'success',
                        title: 'Expenditure has been deleted.',
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
