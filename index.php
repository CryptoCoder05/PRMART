<?php
   ob_start();
   require_once 'core/init.php';
   include_once 'includes/Header.php';
 ?>

<?php
if (isset($user_id)) {
  $query = "SELECT * FROM product WHERE featured = 1 AND deleted = 0 AND user_id = $user_id";
  $sql = $db->query($query);
  $rowcount = mysqli_num_rows($sql);
  if ($rowcount > 0) {
    ?>
    <section class="text-info text-center">
      <?php include_once 'product.php'; ?>
    </section>
    <?php
  }else {
    require 'template/_home.php';
  }
}else {
  require 'template/_home.php';
}
?>

<?php  include_once 'includes/footer.php'; ?>
