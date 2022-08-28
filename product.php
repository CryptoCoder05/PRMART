<?php
require_once 'core/init.php';
$sql = "SELECT * FROM product WHERE featured = 1 AND deleted = 0 AND user_id = $user_id";
$featured = $db->query($sql);
?>

<?php include_once 'leftbar.php'; ?>
<!--<div class="col-md-2"></div>-->
<!--++++++++++++++++-Products------------------>
<section style="margin-top:60px;">
  <div class="col-md-10">
    <div class="row">
      <h2 class="text-center">Featured Product</h2>
      <hr/>
      <?php while ($product = mysqli_fetch_assoc($featured)) : ?>
      <div class="col-md-3" style="padding:5px;" >
        <h5 style="padding:5px;"><?= substr($product['title'],0,47)."..."; ?></h5>
        <?php $photos = explode(',',$product['image']); ?>
        <img src="<?= $photos[0]; ?>" alt="<?= $product['title']; ?>"  id="images" class="img-fluid" style="width:250px"/>
        <p class="list-price text-danger">M.R.P: <s>Rs.<?= $product['mrp_price']; ?></s></p>
        <p class="price">Our Price: Rs.<?= $product['selll_price']; ?></p>
        <button type="button" class="btn btn-success" onclick="detailsmodal(<?= $product['id']; ?>)">Details</button>
        <div class="row"><hr></div>
      </div>
    <?php endwhile; ?>
  </div>
  </div>
</section>
