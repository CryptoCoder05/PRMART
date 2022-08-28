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
 // Add an user.
 if(isset($_GET['add'])){ // add an users btn if condition.
   /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                                                                          Add an user
   ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
   $name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
   $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
   $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
   $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
   $permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
   $errors = array();
   // validating
   if($_POST){ // if submit btn is click, if condition.
     // check if email exits in the database
     $emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
     $emailCount = mysqli_num_rows($emailQuery);
     if($emailCount != 0){
       $errors[] = 'That email already exit in our database!';
     }

     $required = array('name', 'email', 'password', 'confirm', 'permissions');
     // Check empty feild.
     foreach($required as $f){
       if(empty($_POST[$f])){
         $errors[] = 'You must fill out all field!';
         break;
       }
     }
     // check password is more than 6 character
     if (strlen($password) < 6){
       $errors[] = 'Password must be at least 6 character!';
     }
     // check password match.
     if($password != $confirm ){
       $errors[] = 'The password does not match!';
     }

     if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
       $errors[] = 'You must enter a valid email!';
     }

     if(!empty($errors)){
       echo display_errors($errors);
     }
     else{ // if no errors - else condition.
       // add user to data base.
       $hashed = password_hash($password,PASSWORD_DEFAULT);
       $sql = "INSERT INTO `users` (`full_name`, `email`, `password`, `permissions`) VALUES ('$name', '$email', '$hashed', '$permissions')";
       $db->query($sql);
         $_SESSION['success_flash'] = 'User has been added';
         header('Location: users.php');
     } // emd of - if no errors - else condition.
   } // end of - if submit btn is click, if condition.

  ?>
  <section class="container-fluid">
  <h2 class="text-center">Add a New Users</h2><hr />
  <form action="users.php?add=1" method="post">
    <div class="form-group col-md-6">
      <label for="name">Full Name:</label>
      <input type="text" name="name" id="name" class="form-control" value="<?=$name; ?>" />
    </div>
    <div class="form-group col-md-6">
      <label for="email">Email:</label>
      <input type="text" name="email" id="email" class="form-control" value="<?=$email; ?>" />
    </div>
    <div class="form-group col-md-6">
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" class="form-control" value="<?=$password; ?>" />
    </div>
    <div class="form-group col-md-6">
      <label for="confirm">Confirm Password:</label>
      <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm; ?>" />
    </div>
    <div class="form-group col-md-6">
      <label for="permissions">Permission:</label>
      <select class="form-control" name="permissions">
        <option value=""<?=(($permissions == '')?' selected':''); ?>></option>
        <option value="admin,editor,seller"<?=(($permissions == 'admin,seller')?' selected':''); ?>>Admin</option>
        <option value="seller"<?=(($permissions == 'admin,seller')?' selected':''); ?>>Seller</option>
      </select>
    </div>
    <div class="form-group col-md-6 text-right" style="margin-top:25px;">
      <a href="users.php" class="btn btn-default">Cancel</a>
      <input type="submit" name="submit" value="Add User" class="btn btn-primary" />
    </div>
    <div class="clearfix"></div>
  </form>
<section>

  <?php
    /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                                                                          End of add of users
   ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
 } // end of add an users btn if condition.
 else{ // show user table.
 $userQuery = $db->query("SELECT * FROM users WHERE permissions = 'customer' ORDER BY full_name");
 ?>
 <?php /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                                                                        User Table
 ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>
<section class="container-fluid">
  <h2 class="text-center">Users</h2>
  <a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add new Users</a><div class="clearfix"></div>
  <hr />
  <table class="table table-bordered table-striped table-condensed">
    <thead><th>SN</th><th>Name</th><th>Phone No</th><th>Referral</th><th>Join Dates</th><th>Last Login</th></thead>
    <tbody>
      <?php
      $i = 0;
      while($user = mysqli_fetch_assoc($userQuery)):
        $i++;
        ?>
        <tr>
          <th><?=$i;?></th>
          <td><?=$user['full_name'].' '.$user['last_name']; ?></td>
          <td><?=$user['phone_no']; ?></td>
          <td><?=$user['reg_code']; ?></td>
          <td><?=pretty_date($user['join_date']) ;?></td>
          <td><?=(($user['last_login'] == '')?'Never': pretty_date($user['last_login'])); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</section>
<?php /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                                                                       End of user Table
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>
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
                     data     : {delete_user_id : delete_id},
                     datatype : 'text',
                     success : function(data){
                       th.parents('tr').hide();
                       Swal.fire({
                         icon: 'success',
                         title: 'User has been deleted.',
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
