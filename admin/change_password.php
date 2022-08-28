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

$hashed = $user_data['password'];
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);
$new_hashed = password_hash($password, PASSWORD_DEFAULT);
$user_id = $user_data['id'];
$errors = array();
?>
<style>
 body{
   background-color: ;
   background-size: 100vw 100vh;
   background-attachment: fixed;
 }
</style>
<?php //-------------login form---------------------?>
<div id="login-form">
  <div> <?php //-------------Error display div---------------------?>
    <?php
      if($_POST){
        // form validation
        if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
          $errors[] = 'You must fill out all feilds!';
        }
         // password is more than 6 character
         if (strlen($password) < 6){
           $errors[] = 'Password must be at least 6 character!';
         }
          // if new password matches confirm
          if($password != $confirm){
            $errors[] = 'The confirm password does not match with new password!';
          }


        if(!password_verify($old_password, $hashed )){
          $errors[] = 'Your old password does not match our records!';
        }

        // check for $errors
        if(!empty($errors)){
          echo display_errors($errors);
        }else{
          //change password
         $db->query("UPDATE users SET password= '$new_hashed' WHERE id = '$user_id'");
         $_SESSION['success_flash'] = 'Your password has been updated!';
         header('Location: index.php');
        }
      }
     ?>
  </div> <?php //-------------end of Error display div---------------------?>
  <h2 class="text-center">Change Password</h2>
  <form action="change_password.php" method="post">
    <div class="form-group">
      <label for="old_password">Old Password:</label>
      <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password; ?>">
    </div>
    <div class="form-group">
      <label for="password">New Password:</label>
      <input type="password" name="password" id="password" class="form-control" value="<?=$password; ?>">
    </div>
    <div class="form-group">
      <label for="confirm">Confirm New Password:</label>
      <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm; ?>">
    </div>
    <div class="form-group">
      <a href="index.php" class="btn btn-default">Cancel</a>
      <input type="submit" value="Save password" class="btn btn-primary" />
    </div>
  </form>
  <p class="text-right"><a href="../index.php" alt="home">Visit Site</a></p>
</div>
