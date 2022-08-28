<?php  require_once '../core/init.php'; ?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/login_style.css">

<!-- Include the above in your HEAD tag ---------->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!--sweetalert-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
//--login--
function display_errorss($errors){
  $display = '<ul>';
  foreach($errors as $error){
    $display .='<li class="text-danger">'.$error.'</li>';
  }
  $display .= '</ul>';
  return $display;
}

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$hashed = password_hash($password, PASSWORD_DEFAULT);
$errors = array();
 ?>
<div class="container register">
    <div class="row">
        <div class="col-md-3 register-left">
            <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt=""/>
            <h3>Welcome</h3>
            <p>You are 30 seconds away from selling your own product!</p>
        </div>
        <div class="col-md-9 register-right">
              <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Login</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Register</a>
                  </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <h3 class="register-heading">Register as a Seller</h3>
                     <form action="login.php" method="post">
                       <div class="row register-form">
                        <div class="col-md-6">
                          <div class="form-group">
                              <input type="text" class="form-control" name="name" placeholder="Full Name *" value="" required/>
                          </div>
                          <div class="form-group">
                              <input type="email" class="form-control" name="email" placeholder="Email *" value="" required/>
                          </div>
                          <div class="form-group">
                              <input type="password" class="form-control" name="password" placeholder="Password *" value="" required/>
                          </div>
                          <div class="form-group">
                              <input type="password" class="form-control" name="confirm" placeholder="Confirm Password *" value="" required/>
                          </div>
                          <div class="form-group">
                              <div class="maxl">
                                  <label class="radio inline">
                                      <input type="radio" name="gender" value="male" checked>
                                      <span> Male </span>
                                  </label>
                                  <label class="radio inline">
                                      <input type="radio" name="gender" value="female">
                                      <span>Female </span>
                                  </label>
                              </div>
                          </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <input type="text" class="form-control" name="shop_name" placeholder="Shop Name *" value="" required/>
                              </div>
                              <div class="form-group">
                                  <input type="text" name="phone_no" class="form-control" placeholder="Your Phone *" value="" required/>
                              </div>
                              <div class="form-group">
                                  <input type="text" name="addr" class="form-control" placeholder="Address *" value="" required/>
                              </div>
                              <div class="form-group">
                                  <input type="text" class="form-control" name="res_code" placeholder="Referral Code (optional)" value="" />
                              </div>
                              <input type="submit" name="signup" class="btnRegister" id="sign_up" value="Register"/>
                      </div>
                      </div>
                     </form>
                  </div>

                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <h3  class="register-heading">Login</h3>
                      <div class="row register-form">
                        <div class="col-md-3">
                          <?php
                          if(isset($_SESSION['error_flash_login'])){
                            ?>
                             <script type="text/javascript">
                               swal ( "Oops!" ,  "<?=$_SESSION['error_flash_login']; ?>" ,  "error" );
                             </script>
                            <?php
                            unset($_SESSION['error_flash_login']);
                          }

                          if(isset($_POST['login'])){
                            if(empty($_POST['email']) || empty($_POST['password']) ){
                              $errors[] = 'You must provide email and password!';
                            }
                            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                              $errors[] = 'You must enter a valid email!';
                            }
                            $query = $db->query("SELECT * FROM users WHERE email = '$email'");
                            $user = mysqli_fetch_assoc($query);
                            $userCount = mysqli_num_rows($query);
                            if($userCount < 1){
                              $errors[] = 'That email doesn\'t exit in our database';
                            }

                            if(!password_verify($password, $user['password'] )){
                              $errors[] = 'The password does not match our records. Please try again!';
                            }
                            if(!empty($errors)){
                              echo display_errorss($errors);
                            }else{
                              $user_id = $user['id'];
                              login($user_id);
                            }
                          }
                           ?>
                           <!--for signup-->
                           <?php
                           if(isset($_POST['signup'])){ // add an customer btn if condition.
                            $name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
                            $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
                            $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
                            $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
                            $gender = ((isset($_POST['gender']))?sanitize($_POST['gender']):'');
                            $shop_name = ((isset($_POST['shop_name']))?sanitize($_POST['shop_name']):'');
                            $phone_no = ((isset($_POST['phone_no']))?sanitize($_POST['phone_no']):'');
                            $addr = ((isset($_POST['addr']))?sanitize($_POST['addr']):'');
                            $res_code = ((isset($_POST['res_code']))?sanitize($_POST['res_code']):'');
                            $permissions = 'admin,seller';
                            $errors = array();
                            // validating
                            if($_POST){ // if submit btn is click, if condition.
                              // check if email exits in the database
                              $emailQuery = $db->query("SELECT * FROM users WHERE  phone_no = '$phone_no' AND permissions = 'admin,seller'");
                              $emailCount = mysqli_num_rows($emailQuery);
                              if($emailCount != 0){
                                $errors[] = 'That Phone No. already exit in our database!';
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
                                echo display_errorss($errors);
                              }
                              else{ // if no errors - else condition.
                                // add customer to database.
                                $hashed = password_hash($password,PASSWORD_DEFAULT);

                                $sql = "INSERT INTO `users`(`full_name`,`email`, `password`, `Gender`, `addr`, `phone_no`, `reg_code`, `shop_name`, `permissions`)
                                                    VALUES ('$name','$email','$hashed','$gender','$addr','$phone_no','$res_code','$shop_name','$permissions')";
                                $result = $db->query($sql);
                                if ($result === TRUE) {
                                  echo "<h5 class='text-success'>You are successfully registered.<h5>";
                                }
                              } // emd of - if no errors - else condition.
                            } // end of - if submit btn is click, if condition.
                          } // end of - main if condition.
                            ?>
                        </div>
                          <div class="col-md-6">
                            <form action="login.php" method="post">
                              <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="Enter your email..."  value="<?=$email; ?>">
                              </div>
                              <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password..."  value="<?=$password; ?>">
                              </div>
                              <input type="submit" name="login" value="Login" class="btnRegister" />
                            </form>
                          </div>
                          <div class="col-md-3"></div>
                      </div>
                  </div>
          </div>
        </div>
      </div>
    </div>
