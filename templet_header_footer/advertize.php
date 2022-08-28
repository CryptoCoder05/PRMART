<?php
require_once '../core/init.php';
$qry = "SELECT * FROM advertise WHERE featured = 1";
$res = $db->query($qry);
?>
<!-- Start WOWSlider.com BODY section -->
<div id="wowslider-container1" style="margin-bottom:15px;">
<div class="ws_images">
  <ul>
    <?php  while($result = mysqli_fetch_assoc($res)):
      ?>
       <li><img src="../data1/<?=$result['image']; ?>" style="height:180px; "/></li>
      <?php
    endwhile;
     ?>
  </ul>
</div>
<div class="ws_script" style="position:absolute;left:-99%"><a href="http://wowslider.net">slider jquery</a> by WOWSlider.com v8.8</div>
<div class="ws_shadow"></div>
</div>
<script type="text/javascript" src="../engine1/wowslider.js"></script>
<script type="text/javascript" src="../engine1/script.js"></script>
<!-- End WOWSlider.com BODY section -->
