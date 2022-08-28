<?php
 require_once 'core/init.php';
 include_once 'includes/Header.php';

$sql = "SELECT * FROM product";
$cat_id = (($_POST['cat'] != '')?sanitize($_POST['cat']):'');
if($cat_id == ''){
  $sql .= ' WHERE deleted = 0';
}else{
  $sql .=  " WHERE categories = '{$cat_id}' AND deleted = 0";
}
$price_sort = (($_POST['price_sort'] != '')?sanitize($_POST['price_sort']):'');
$min_price = (($_POST['min_price'] != '')?sanitize($_POST['min_price']):'');
$max_price = (($_POST['max_price'] != '')?sanitize($_POST['max_price']):'');
$brand = (($_POST['brand'] != '')?sanitize($_POST['brand']):'');
if($min_price != ''){
  $sql .= " AND price >= '{$min_price}'";
}
if($max_price != ''){
  $sql .= " AND price <= '{$max_price}'";
}
if($brand != ''){
  $sql .= " AND brand = '{$brand}'";
}
if($price_sort == 'low'){
  $sql .= " ORDER BY selll_price";
}
if($price_sort == 'high'){
  $sql .= " ORDER BY selll_price DESC";
}
 $productQ = $db->query($sql);
 $category = get_category($cat_id);
 ?>

<section class="text-info text-center" style="margin-top:80px;">
  <!--++++++++++++++++-Products as center------------------>
  <?php include_once 'leftbar.php'; ?>
  <!--++++++++++++++++-Products------------------>
  <div class="col-md-10">
    <div class="row">
      <?php if($cat_id != ''): ?>
      <h2 class="text-center"><?=$category['parent']. ' ' .$category['child']; ?> </h2>
    <?php else: ?>
      <h2 class="text-center">Product</h2><hr>
    <?php endif; ?>
      <?php while ($product = mysqli_fetch_assoc($productQ)) : ?>
      <div class="col-md-3" style="padding:5px;">
        <h4 style="padding:5px;"><?= substr($product['title'],0,47); ?></h4>
        <?php $photos = explode(',',$product['image']); ?>
        <img src="<?=$photos[0]; ?>" alt="<?= $product['title']; ?>" id="images" class="img-fluid"/>
        <p class="list-price text-danger">List price: <s>Rs.<?= $product['mrp_price']; ?></s></p>
        <p class="price">Our Price: Rs.<?= $product['selll_price']; ?></p>
        <button type="button" class="btn btn-success" onclick="detailsmodal(<?= $product['id']; ?>)">Details</button>
      </div>
    <?php endwhile; ?>
    </div>
  </div>

</section>
<?php include_once 'includes/footer.php'; ?>
