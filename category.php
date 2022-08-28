<?php
 require_once 'core/init.php';
 include_once 'includes/Header.php';
 require_once 'advertize.php';

 if(isset($_GET['cat'])){
   $cat_id = sanitize($_GET['cat']);
 }else{
   $cat_id = '';
 }
 $sql = "SELECT * FROM product WHERE categories = '$cat_id'";
 $productQ = $db->query($sql);
 $category = get_category($cat_id);
 ?>

<section class="text-info text-center">
  <!--++++++++++++++++-background image=====
  <div id="partial-background-image"></div>-->
  <!--++++++++++++++++-Products as center------------------>
  <?php include_once 'leftbar.php'; ?>
  <!--++++++++++++++++-Products------------------>
  <div class="col-md-8">
    <div class="row">
      <h2 class="text-center"><?=$category['parent']. ' ' .$category['child']; ?> </h2>
      <?php while ($product = mysqli_fetch_assoc($productQ)) : ?>
      <div class="col-md-3">
        <h4><?= $product['title']; ?></h4>
        <?php $photos = explode(',',$product['image']); ?>
        <img src="<?= $photos[0]; ?>" alt="<?= $product['title']; ?>" id="images"/>
        <p class="list-price text-danger">M.R.P: <s>Rs.<?= $product['mrp_price']; ?></s></p>
        <p class="price">Our Price: Rs.<?= $product['selll_price']; ?></p>
        <button type="button" class="btn btn-success" onclick="detailsmodal(<?= $product['id']; ?>)">Details</button>
      </div>
    <?php endwhile; ?>
    </div>
  </div>
  <?php include_once 'rightbar.php'; ?>
</section>

<?php include_once 'includes/footer.php'; ?>
