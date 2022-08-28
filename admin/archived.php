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

//----Recover products----
if(isset($_GET['recover'])){// Recover if condition.
  $id = sanitize($_GET['recover']);
  $db->query("UPDATE product SET deleted = 0 WHERE id = '$id' ");
  header('Location: archived.php');
}// end of recover if condition.
else{ // Query of deleted section.
  if (has_permission('seller')) {
    $sql = "SELECT * FROM product WHERE deleted = 1 AND user_id = '$user_id'";
  } else {
    $sql = "SELECT * FROM product WHERE deleted = 1";
  }

$presults = $db->query($sql);
 ?>
  <?php //-----------------------------Archived product table-----------------------?>
<section class="container-fluid">
<h2 class="text-center">Archived Products</h2><hr />
<table class="table table-bordered table-condensed table-striped">
 <thead><th>Recover</th><th>Products</th><th>Price</th><th>Category</th><th>Quantity</th></thead>
 <tbody>
   <?php while($product = mysqli_fetch_assoc($presults)):
     $childID = $product['categories'];
     $catSql = " SELECT * FROM categories WHERE id = '$childID' AND user_id = $user_id";
     $result = $db->query($catSql);
     $child = mysqli_fetch_assoc($result);
     $parentID = $child['parent'];
     $parSql = "SELECT * FROM categories WHERE id = '$parentID' AND user_id = $user_id";
     $presult = $db->query($parSql);
     $parent = mysqli_fetch_assoc($presult);
     $category = $parent['categories'].'-'.$child['categories'];
     $sizes = sizesToArray($product['sizes']);
     foreach($sizes as $size){
       $qty = $size['quantity'];
       $threshold = $size['threshold'];
       }
     ?>
    <tr>
      <td><a href="archived.php?recover=<?=$product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a></td>
      <td><?=($product['title']); ?></td>
      <td><?=money($product['selll_price']); ?></td>
      <td><?=$category; ?></td>
      <td><?=($qty == 0 )?'Out of Stock!':$qty ; ?></td>
    </tr>
   <?php endwhile; ?>
 </tbody>
</table>
</section>
<?php //-----------------------------enf of product table-----------------------?>
<?php } // end of deleted section
include 'includes/footer.php'; ?>
