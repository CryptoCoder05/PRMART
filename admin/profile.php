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

   $user_Q = $db->query("SELECT * FROM users WHERE id = '$user_id'");
   $user_res = mysqli_fetch_assoc($user_Q);
   $name = ((isset($_POST['name']))?sanitize($_POST['name']):$user_res['full_name']);
   $email = ((isset($_POST['email']))?sanitize($_POST['email']):$user_res['email']);
   $birthdate = ((isset($_POST['birthdate']))?sanitize($_POST['birthdate']):$user_res['Birthdate']);
   $addr = ((isset($_POST['addr']))?sanitize($_POST['addr']):$user_res['addr']);
   $gender = ((isset($_POST['gender']))?sanitize($_POST['gender']):$user_res['Gender']);

   $district = ((isset($_POST['district']))?sanitize($_POST['district']):$user_res['district']);
   $phone_no = ((isset($_POST['phone_no']))?sanitize($_POST['phone_no']):$user_res['phone_no']);
   $errors = array();
   if (isset($_POST['save'])) {
     $name = ((isset($_POST['name']))?sanitize($_POST['name']):$user_res['full_name']);
     $email = ((isset($_POST['email']))?sanitize($_POST['email']):$user_res['email']);
     $birthdate = ((isset($_POST['birthdate']))?sanitize($_POST['birthdate']):$user_res['Birthdate']);
     $addr = ((isset($_POST['addr']))?sanitize($_POST['addr']):$user_res['addr']);
     $gender = ((isset($_POST['gender']))?sanitize($_POST['gender']):'');
     $district = ((isset($_POST['district']))?sanitize($_POST['district']):$user_res['district']);
     $phone_no = ((isset($_POST['phone_no']))?sanitize($_POST['phone_no']):$user_res['phone_no']);
     $errors = array();

     $required = array('name', 'email', 'phone_no', 'district', 'addr');
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
       $sql = "UPDATE `users` SET `full_name`= '$name',`email`='$email',`Birthdate`='$birthdate', `Gender` = '$gender',`addr`='$addr',`district`='$district',`phone_no`='$phone_no' WHERE id = '$user_id'";
       $success = $db->query($sql);
       if ($success === TRUE) {
         header('Location: index.php');
       }
     } // emd of - if no errors - else condition.
   } // end of - if submit btn is click, if condition.

  ?>
  <section class="container-fluid">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <h2 class="text-center text-info" style="letter-spacing:3px">Profile</h2><hr />
      <form action="profile.php" method="post">
        <div class="form-group">
          <label for="name">Full Name:</label>
          <input type="text" name="name" id="name" class="form-control" value="<?=$name; ?>" />
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="text" name="email" id="email" class="form-control" value="<?=$email; ?>" />
        </div>
        <div class="form-group">
          <label for="Birthdate">Birthdate:</label>
          <input type="date" name="birthdate" id="birthdate" class="form-control" value="<?=$birthdate; ?>" />
        </div>
        <div class="form-group">
          <label for="gender">Gender: </label>
          <input type="radio" name="gender" id="gender"  value="Male" <?=(($gender=='Male')?'checked':'');?>  /><span> Male</span>
          <input type="radio" name="gender" id="gender"  value="Female" <?=(($gender=='Female')?'checked':'');?> /><span> Female</span>
        </div>
        <div class="form-group">
          <label for="addr">address:</label>
          <input type="text" name="addr" id="addr" class="form-control" value="<?=$addr; ?>" />
        </div>
        <div class="form-group">
          <label for="district">District:</label>
          <input type="text" name="district" id="district" class="form-control" value="<?=$district; ?>" />
        </div>
        <div class="form-group">
          <label for="phone_no">Phone no:</label>
          <input type="text" name="phone_no" id="phone_no" class="form-control" value="<?=$phone_no; ?>" />
        </div>
        <div class="form-group text-right" style="margin-top:25px;">
          <a href="index.php" class="btn btn-default">Cancel</a>
          <input type="submit" name="save" value="update" class="btn btn-primary" style="letter-spacing:3px;" />
        </div>
        <div class="clearfix"></div>
      </form>
    </div>
    <div class="col-md-4"></div>
  </section>
<?php
 include 'includes/footer.php';
 ?>
