<?php
   require_once '../core/init.php';
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
                  <div style="background-color:#f23a54;">
                    <?php
                    if(isset($_POST['submit'])){ // add an customer btn if condition.
                      $name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
                      $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
                      $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
                      $birthday = ((isset($_POST['birthday']))?sanitize($_POST['birthday']):'');
                      $gender = ((isset($_POST['gender']))?sanitize($_POST['gender']):'');
                      $village = ((isset($_POST['village']))?sanitize($_POST['village']):'');
                      $district = ((isset($_POST['district']))?sanitize($_POST['district']):'');
                      $phone_no = ((isset($_POST['phone_no']))?sanitize($_POST['phone_no']):'');
                      $res_code = ((isset($_POST['res_code']))?sanitize($_POST['res_code']):'');
                      $errors = array();
                      // validating
                      if($_POST){ // if submit btn is click, if condition.
                        // check if email exits in the database
                        $emailQuery = $db->query("SELECT * FROM users WHERE  phone_no = '$phone_no'");
                        $emailCount = mysqli_num_rows($emailQuery);
                        if($emailCount != 0){
                          $errors[] = 'That Phone No. already exit in our database!';
                        }

                        $required = array('name', 'phone_no', 'password', 'confirm', 'birthday','gender','village','district');
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


                        if(!empty($errors)){
                          echo display_errors($errors);
                        }
                        else{ // if no errors - else condition.
                          // add customer to database.
                          $hashed = password_hash($password,PASSWORD_DEFAULT);

                          $sql = "INSERT INTO `users`(`full_name`,`password`, `Birthdate`, `Gender`, `village`, `district`, `phone_no`, `reg_code`)
                                              VALUES ('$name','$hashed','$birthday','$gender','$village','$district','$phone_no','$res_code')";
                          $db->query($sql);
                            header('Location: login.php');
                        } // emd of - if no errors - else condition.
                      } // end of - if submit btn is click, if condition.
                    } // end of - main if condition.
                     ?>
                  </div>
                    <h2 class="title">Registration Info</h2>
                    <form action="signup.php" method="POST">
                        <div class="input-group">
                            <input class="input--style-2" type="text" placeholder="Name" name="name">
                        </div>

                        <div class="reg_row row-space">
                            <div class="reg_col-2">
                                <div class="input-group">
                                    <input class="input--style-2 js-datepicker" type="text" placeholder="Birthdate" name="birthday">
                                    <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                                </div>
                            </div>
                            <div class="reg_col-2">
                                <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="gender">
                                            <option disabled="disabled" selected="selected">Gender</option>
                                            <option>Male</option>
                                            <option>Female</option>
                                            <option>Other</option>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="reg_row row-space">
                           <div class="reg_col-2">
                             <div class="input-group">
                                 <input class="input--style-2" type="text" placeholder="City" name="village">
                             </div>
                           </div>
                           <div class="reg_col-2">
                             <div class="input-group">
                                 <input class="input--style-2" type="text" placeholder="State" name="district">
                             </div>
                           </div>
                         </div>
                        <div class="input-group">
                            <input class="input--style-2" type="text" placeholder="Phone number" name="phone_no">
                        </div>
                        <div class="reg_row row-space">
                          <div class="reg_col-2">
                            <div class="input-group">
                                <input class="input--style-2" type="password" placeholder="Password" name="password">
                            </div>
                          </div>
                          <div class="reg_col-2">
                            <div class="input-group">
                                <input class="input--style-2" type="password" placeholder="Confirm Password" name="confirm">
                            </div>
                          </div>
                        </div>

                        <div class="input-group">
                            <input class="input--style-2" type="text" placeholder="Registration Code (optional)" name="res_code">
                        </div>


                        <div class="p-t-30">
                            <a href="../index.php" class="btn btn--radius btn--default">Cancel</a>
                            <button class="btn btn--radius btn--green" type="submit" name="submit">Sign Up</button>
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
