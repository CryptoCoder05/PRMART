<?php
   require_once '../core/init.php';
 ?>
<?php
$phon_no = ((isset($_POST['phon_no']))?sanitize($_POST['phon_no']):'');
$phon_no = trim($phon_no);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$hashed = password_hash($password, PASSWORD_DEFAULT);
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
                        if(empty($_POST['phon_no']) || empty($_POST['password']) ){
                          $errors[] = 'You must provide phone No. and password!';
                        }

                        // check if email exits in the database
                        $query = $db->query("SELECT * FROM users WHERE phone_no = '$phon_no'");
                        $user = mysqli_fetch_assoc($query);
                        $userCount = mysqli_num_rows($query);
                        if($userCount < 1){
                          $errors[] = 'That Phone No. doesn\'t exit in our database';
                        }

                        if(!password_verify($password, $user['password'] )){
                          $errors[] = 'The password does not match our records. Please try again!';
                        }

                        // check for $errors
                        if(!empty($errors)){
                          echo display_errors($errors);
                        }else{
                          // Log user in.
                          $user_id = $user['id'];
                          login_cus_side($user_id);
                        }
                      }
                     ?>
                  </div> <?php //-------------end of Error display div---------------------?>
                    <h2 class="title">Login</h2>
                    <form action="login.php" method="POST">
                        <div class="input-group">
                            <input class="input--style-2" type="text" placeholder="Phone number" name="phon_no">
                        </div>
                        <div class="input-group">
                            <input class="input--style-2" type="password" placeholder="Password" name="password">
                        </div>

                        <div class="p-t-30">
                            <a href="../index.php" class="btn btn--radius btn--default">Cancel</a>
                            <button class="btn btn--radius btn--green" type="submit">Login</button>
                        </div>
                    </form>
                    <p style="margin-top:10px; float:right; text-decoration:underline;"><a href="signup.php" alt="sign up" style="color:#096dad;">Create New Account!</a></p>
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
