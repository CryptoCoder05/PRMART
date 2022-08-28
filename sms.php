<?php
require_once 'core/init.php';
include_once 'includes/Header.php';
$response = false;

  if(isset($_POST['submit'])){
    $name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
    $mobile = ((isset($_POST['phone']))?sanitize($_POST['phone']):'');
    $msg = ((isset($_POST['msg']))?sanitize($_POST['msg']):'');
    $phone = '91'.$mobile;
      // Account details of sms API textlocal-------
      require('textlocal_sms/textlocal.class.php');

      $Textlocal = new Textlocal(false, false, 'mBRCqLjBQbo-kMQAz68aKEetwHXx5xDO9OPUrHZ1I2');

      $numbers = array($phone);
      $sender = 'TXTLCL';
      $message = 'Hello '.$name. ". " .$msg;

      $response = $Textlocal->sendSms($numbers, $message, $sender);
      // end of Account details of sms API textlocal-------
  }
 ?>

<section class="text-info" style="margin-top:64px;">
  <!--++++++++++++++++-Products as center------------------>
  <?php include_once 'leftbar.php'; ?>
  <!--++++++++++++++++-Products------------------>
  <div class="col-md-8">
    <h2 class="text-center">Send SMS</h2><hr>
    <?php if ($response == true) {
      echo "SMS has been sent.";
    }
     ?>
    <div class="" style="margin-top:15px;">
      <form action="sms.php" method="post">
        <div class="form-group col-md-6">
          <label for="name">Name:</label>
          <input type="text" name="name" id="name" class="form-control" value="" />
        </div>
        <div class="form-group col-md-6">
          <label for="phone">Mobile No.:</label>
          <input type="text" name="phone" id="phone" class="form-control" value="" />
        </div>
        <div class="form-group col-md-12">
          <label for="msg">Message:</label>
          <textarea id="msg" name="msg" class="form-control" rows="4"></textarea>
        </div>
        <div class="form-group col-md-12" style="margin-top:25px;">
          <a href="index.php" class="btn btn-default">Cancel</a>
          <input type="submit" name="submit" value="Send SMS" class="btn btn-primary" />
        </div>
        <div class="clearfix"></div>
      </form>
    </div>
  </div>

  <?php include_once 'rightbar.php'; ?>

</section>
<?php include_once 'includes/footer.php'; ?>
