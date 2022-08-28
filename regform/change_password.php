<?php
   require_once '../core/init.php';
 ?>
<?php
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

 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <meta name="description" content="e-commerce">
     <meta name="author" content="Anirudh singh">

   <title><?=SHOPNAME; ?></title>
   <!--=============================================================== signup form---------------------------------->
   <!-- Icons font CSS-->
   <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
   <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
   <!-- Font special for pages-->
   <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
   <!-- Vendor CSS-->
   <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
   <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">
   <!-- Main CSS-->
   <link href="css/main.css" rel="stylesheet" media="all">
<!--=============================================================== signup form---------------------------------->
</head>
<body>
    <div class="page-wrapper bg-red p-t-180 p-b-100 font-robo">
        <div class="wrapper wrapper--w960">
            <div class="card card-2">
                <div class="card-heading"></div>
                <div class="card-body">
                    <div style="background-color:#f23a54;"> <?php //-------------Error display div---------------------?>
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
                           header('Location: ../index.php');
                          }
                        }
                       ?>
                    </div> <?php //-------------end of Error display div---------------------?>
                    <h2 class="title">Change Password</h2>
                    <form action="change_password.php" method="POST">
                        <div class="input-group">
                            <input class="input--style-2" type="password" placeholder="Old Password" name="old_password">
                        </div>
                        <div class="input-group">
                            <input class="input--style-2" type="password" placeholder="New Password" name="password">
                        </div>
                        <div class="input-group">
                            <input class="input--style-2" type="password" placeholder="Confirm Password" name="confirm">
                        </div>

                        <div class="p-t-30">
                            <a href="../index.php" class="btn btn--radius btn--default">Cancel</a>
                            <button class="btn btn--radius btn--green" type="submit">Save Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>


<!-- end document-->
