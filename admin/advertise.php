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

//----show and Hide advertisement image----
 if(isset($_GET['featured'])){
   $sql = "SELECT * FROM advertise WHERE user_id = $user_id";
   $presults = $db->query($sql);
   $id = (int)$_GET['id'];
   $featured = (int)$_GET['featured'];
   $featuredsql = "UPDATE advertise SET featured = '$featured' WHERE id = '$id'";
   $db->query($featuredsql);
 }

 //----Delete advertisement image----
 if(isset($_GET['delete'])){// deleted if condition.
   $id = sanitize($_GET['delete']);
   $qry = "SELECT * FROM advertise WHERE id='$id' AND user_id = $user_id";
   $res = $db->query($qry);
   $result = mysqli_fetch_assoc($res);
   $image_path = $result['image'];
   unlink($image_path);
   $db->query("DELETE from advertise  WHERE id = '$id' ");
   $_SESSION['success_flash'] = "Image deleted successfull!";
   header('Location: advertise.php');
 }// end of deleted if condition.

 //---Insert image--
 if(isset($_POST['submit'])){
   $title = $_POST['title'];
   $img_name = $_FILES["advertise"]["name"];
   $temp_img_name = $_FILES["advertise"]["tmp_name"];
   $adv_image = "../data1/advertise/".$img_name;
   move_uploaded_file($temp_img_name,$adv_image);

 if($title == ''){
     $errors[].='You must enter a title!';
   }
   if($img_name == ''){
     $errors[].='You must choose an image!';
   }
   //dispaly errors--
   if(!empty($errors)){
     echo display_errors($errors);
   }else {
     //--Insert advertisement image to database...
     $insertSql = "INSERT INTO `advertise`(`user_id`,`title`,`image`) VALUES ('$user_id','$title','$adv_image')";
     $data = $db->query($insertSql);
     $_SESSION['success_flash'] = "Image inserted successfull! !";
     header('Location: advertise.php');
     }
   }
  ?>

 <section class="text-info text-center">
   <h2>Advertise Here</h2><hr />
   <!--Brand form-->
 <div >
   <form class="form-inline" action="advertise.php" method="post" enctype="multipart/form-data">
     <div class="form-group">
       <label for="advertise">Advertisement image:</label>
       <input type="text" name="title" class="form-control" id="title" placeholder="Advertisement Title...." value="" />
       <input type="file" name="advertise" id="advertise" class="form-control" value="" >
        <a href="index.php" class="btn btn-default">Cancel</a>
       <input type="submit" name="submit" value="Post" class="btn btn-success" />
     </div>
   </form>
 </div><hr />
 </section>

<?php
 //---Display image---
 if (has_permission('seller')){
   $sql = "SELECT * FROM advertise WHERE user_id = $user_id";
 }else {
   $sql = "SELECT * FROM advertise";
 }
 $data = $db->query($sql);
 while ($result = mysqli_fetch_assoc($data)) {
   ?>
   <div class="container-fluid" style="margin-top:5px;" >
     <div class="rows">
     <div class="col-md-9">
      <img src="<?=$result['image']; ?>"  style="width:100%; height: 140px;" />
     </div>
     <div class="col-md-3" style="margin:0px;padding:50px;">
       <a href="advertise.php?featured=<?=(($result['featured'] == 0)?'1':'0'); ?>&id=<?=$result['id']; ?>" class="btn btn-md btn-info">
         <span class="glyphicon glyphicon-<?=(($result['featured'] == 1)?'minus':'plus'); ?>"> <?=(($result['featured'] == 1)?'Hide':'Show'); ?></span>
      </a>
       <a href="advertise.php?delete=<?= $result['id']; ?>" class="text btn btn-danger" style="margin:2px;"><span class="glyphicon glyphicon-remove"> Delete</span></a>
     </div>
   </div>
 </div>
   <?php
 }
?>

<?php include 'includes/footer.php'; ?>
