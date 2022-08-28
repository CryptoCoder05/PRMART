<?php
require_once 'core/init.php';
$item_count = 0;
$sql = "SELECT * FROM `categories` WHERE parent = 0";
$pquery = $db->query($sql);
$cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
$result = mysqli_fetch_assoc($cartQ);
if($result){
  $item_count = $result['count_cart'];
}

 ?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar" style="margin-top:00px;">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="index.php" class="navbar-brand font-weight-bold" style="letter-spacing:2px;"><span style="color:white;">PRADESH</span><small>BAZAR</small></a>

    </div>
    <div class="navbar-collapse collapse" >
      <ul class="nav navbar-nav navbar-right" >
        <li><a href="index.php">Home</a></li>
        <li><a href="admin/index.php" >Dashboard</a></li>
        <li><a href="admin/login.php" >Login</a></li>
      </ul>
    </div>
  </div>
</nav>
