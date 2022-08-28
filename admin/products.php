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

//----check product by barcode scan----
$barcode = ((isset($_POST['fetch_barcode']) && $_POST['fetch_barcode'] != '')?sanitize($_POST['fetch_barcode']):'');
$productQ = $db->query("SELECT * FROM product WHERE barcode = '$barcode' AND user_id = $user_id");
$product = mysqli_fetch_assoc($productQ);
$barcodeCount = mysqli_num_rows($productQ);
//--check barcode is available or not.
if($barcodeCount > 0){
  $product_id = $product['id'];
  $barcode = $product['barcode'];
  $db->query("INSERT INTO `add_pro_bar`(`user_id`,`product_id`, `barcode`) VALUES ('$user_id','$product_id','$barcode')");
}

//barcode scan form--->
  $barcode = null;
  $productQ = $db->query("SELECT * FROM add_pro_bar WHERE user_id = $user_id");
  $product_res = mysqli_fetch_assoc($productQ);
  $bar_count = mysqli_num_rows($productQ);
  if($product_res){
    $barcode = $product_res['barcode'];
  }


  if(isset($_POST['save_product'])){
    $db->query("DELETE FROM `add_pro_bar` WHERE user_id = $user_id");
  }

// default value of some variable.
$dbpath = '';
$tmpLoc = '';
$uploadPath = '';


if(isset($_GET['add']) || isset($_GET['edit'])){ //-----1st if condition-------
$brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
$parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY categories");
$Prod_barcode = ((isset($_POST['Prod_barcode']) && $_POST['Prod_barcode'] != '')?sanitize($_POST['Prod_barcode']):'');
$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
$category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
$sell_price = ((isset($_POST['sell_price']) && $_POST['sell_price'] != '')?sanitize($_POST['sell_price']):'');
$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
$description = ((isset($_POST['description']) && $_POST['description'] != '')?$_POST['description']:'');
$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
$sizes = rtrim($sizes,',');
$saved_image = '';

if(isset($_GET['edit'])){//----------2nd if condition-------
  $edit_id = (int)$_GET['edit'];
  $productResults = $db->query("SELECT * FROM product WHERE id = '$edit_id' AND user_id = $user_id");
  $product = mysqli_fetch_assoc($productResults);
  if(isset($_GET['delete_image'])){ // delete image if condition.
    $imgi = (int)$_GET['imgi'] - 1;
    $images = explode(',',$product['image']);
    $image_url = $_SERVER['DOCUMENT_ROOT'].$images[$imgi];
    unlink($image_url);
    unset($images[$imgi]);
    $imageString = implode(',',$images);
    $db->query("UPDATE product SET image = '{$imageString}' WHERE id = '$edit_id'");
    header('Location: products.php?edit='.$edit_id);
  } // end of deleted image if condition.
  $category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):$product['categories']);
  $Prod_barcode = ((isset($_POST['Prod_barcode']) && !empty($_POST['Prod_barcode']))?sanitize($_POST['Prod_barcode']):$product['barcode']);
  $title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
  $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand']);
  $parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
  $pareentResult = mysqli_fetch_assoc($parentQ);
  $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$pareentResult['parent']);
  $sell_price = ((isset($_POST['sell_price']) && !empty($_POST['sell_price']))?sanitize($_POST['sell_price']):$product['buy_price']);
  $price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$product['selll_price']);
  $list_price = ((isset($_POST['list_price']))?sanitize($_POST['list_price']):$product['mrp_price']);
  $description = ((isset($_POST['description']))?$_POST['description']:$product['description']);
  $sizes = ((isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):$product['sizes']);
  $sizes = rtrim($sizes,',');
  $saved_image = (($product['image'] != '')?$product['image']:'');
  $dbpath = $saved_image;

}//-----------------------------end of isset edit if condition-------

if(!empty($sizes)){//-----------3rd if condition--------------
  $sizeString = sanitize($sizes);
  $sizeString = rtrim($sizeString,',');
  $sizesArray = explode(',',$sizeString);
  $sArray = array();
  $qArray = array();
  $tArray = array();
  foreach ($sizesArray as $ss) { // foreach loop for explode sizes.
    $s = explode(':',$ss);
    $sArray[] = $s[0];
    $qArray[] = $s[1];
    $tArray[] = $s[2];
  } // end of foreach loop for explode sizes.
}//---------------------end of 3rd if condition---------------
else{$sizesArray = array();}
if($_POST){//-------------------4th if condition--------------
  $errors = array();
  //$required = array('Prod_barcode','title','brand','sell_price','price','child','parent','sizes');
  $required = array('title','sizes','sell_price','price','list_price');
  $allowed = array('png','jpg','jpeg','gif','JFIF');
  $uploadPath = array();
  $temLoc = array();
  foreach ($required as $field) {
    if($_POST[$field] == ''){
      $errors[] = 'All fields with an Astrick are required!';
      break;
    }
  }
  // uploade image file - if condition.
  $photoCount = count($_FILES['photo']['name']);
  if($photoCount > 0){
    for($i = 0; $i<$photoCount; $i++){
    $name = $_FILES['photo']['name'][$i];
    $nameArray = explode('.',$name);
    $fileName = $nameArray[0];
    $fileExt = $nameArray[1];
    $mime = explode('/',$_FILES['photo']['type'][$i]);
    $mimeType = $mime[0];
    $mimeExt = $mime[1];
    $tmpLoc = $_FILES['photo']['tmp_name'];
    $fileSize = $_FILES['photo']['size'][$i];
    $uploadName = md5(microtime().$i).'.'.$fileExt;
    $uploadPath[] = BASEURL.'/images/products/'.$uploadName;
    if($i != 0){
      $dbpath .= ',';
    }
    $dbpath .= 'images/products/'.$uploadName;
    if($mimeType != 'image'){
      $errors[] = 'The file must be image!';
    }
    if(!in_array($fileExt, $allowed)){
      $errors[] = 'The file extention must be a png, jpg, jpeg, gif';
    }
//    if($fileSize > 1500){
//      $errors[] = 'The file size must be less than 10MB!';
//    }
    if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
      $errors[] = 'File extention does not match the file! ';
    }
    }
  } // end of uploade image file - if condition.

  if ($price > $list_price) {
    $errors[] = 'Price must be less then list price!';
  }

  if (isset($_GET['add'])) {
    $prodQ = $db->query("SELECT * FROM product WHERE user_id = '$user_id' AND barcode = $Prod_barcode");
    $count_barcodeQ = mysqli_num_rows($prodQ);
    if ($count_barcodeQ != 0) {
      $errors[] = 'Barcode already exits in our database';
    }
  }


  if(!empty($errors)){
    echo display_errors($errors);
  }else{ // upload file and insert into database - else condition.
    if($photoCount > 0){
      for($i = 0; $i<$photoCount; $i++){
        move_uploaded_file($tmpLoc[$i],$uploadPath[$i]);
      }
    }
    $sell_price = trim($sell_price);
    $price = trim($price);
    $list_price = trim($list_price);

    $insertSql = "INSERT INTO `product`(`user_id`,`barcode`,`title`,`buy_price`, `selll_price`, `mrp_price`, `brand`, `categories`, `image`, `description`, `sizes`)
    VALUES ('$user_id','$Prod_barcode','$title','$sell_price','$price','$list_price','$brand','$category','$dbpath','$description','$sizes')";

    if(isset($_GET['edit'])){
      if (has_permission('seller')) {
        $insertSql = "UPDATE product SET barcode = '$Prod_barcode', title = '$title', buy_price = '$sell_price', selll_price = '$price', mrp_price = '$list_price', brand = '$brand', categories = '$category', sizes = '$sizes', image = '$dbpath', description = '$description' WHERE id = '$edit_id' ";
      }
    }
    $sucess = $db->query($insertSql);
    if ($sucess === True) {
      header('Location: products.php');
    }else {
      ?>
       <script type="text/javascript">
         swal ( "Oops" ,  "Something went wrong!" ,  "error" );
       </script>
      <?php
    }
  } // end of upload file and insert into database - else conditionre
}//-----------------end of 4th if condition-------
 ?>
 <?php /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                                                                        body of add and edit product
 ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>
 <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit ':'Add A New '); ?> Product</h2> <hr />
 <section class="container fluid">
 <form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>" method="POST" enctype="multipart/form-data">
   <div class="form-group col-md-12">
     <label for="title">Title * :</label>
     <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>" />
   </div>
  <div class="form-group col-md-3" >
    <label for="Prod_barcode">Model No. :</label>
    <input type="text" name="Prod_barcode" class="form-control" id="Prod_barcode"  value="<?=$Prod_barcode;?>" />
  </div>

  <div class="form-group col-md-3">
    <label for="brand">Brand :</label>
    <select class="form-control" id="brand" name="brand">
      <option value=""<?=(($brand == '')?'selected': ''); ?>></option>
      <?php while($b = mysqli_fetch_assoc($brandQuery)): ?>
       <option value="<?=$b['id']; ?>"<?=(($brand == $b['id'])?'selected': ''); ?>><?=$b['brand']; ?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="form-group col-md-3">
    <label for="parent">Parent Category :</label>
    <select class="form-control" id="parent" name="parent">
      <option value=""<?=(($parent == '')?'selected': ''); ?>></option>
      <?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
       <option value="<?=$p['id']; ?>"<?=(($parent == $p['id'])?'selected': ''); ?>><?=$p['categories']; ?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="form-group col-md-3">
    <label for="child">Child Category :</label>
    <select id="child" name="child" class="form-control"></select>
  </div>

  <div class="form-group col-md-2">
    <label for="sell_price">Buying price *:</label>
    <input type="text" id="sell_price" onchange="echange(this)" name="sell_price" class="form-control" value="<?=$sell_price; ?>"></input>
  </div>

  <div class="form-group col-md-2">
    <label for="price">Selling price * :</label>
    <input type="text" id="price" onchange="echange(this)" name="price" class="form-control" value="<?=$price; ?>"></input>
  </div>

  <div class="form-group col-md-2">
    <label for="list_price">M.R.P *:</label>
    <input type="text" id="list_price" onchange="echange(this)" name="list_price" class="form-control" value="<?=$list_price; ?>"></input>
  </div>

   <div class="form-group col-md-3">
     <label>Quantity & Size * :</label>
     <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Size</button>
   </div>

   <div class="form-group col-md-3">
     <label for="sizes">Size & Qty Preview</label>
     <input type="text" class="form-control" name="sizes" id="sizes" value="<?=$sizes; ?>" readonly>
   </div>

    <div class="form-group col-md-6">
      <?php if($saved_image != ''): ?>
        <?php
        $imgi = 1;
         $images = explode(',',$saved_image);
         ?>
         <?php foreach($images as $image): ?>
       <div class="saved-image col-md-4">
         <img src="<?="../".$image; ?>" alt="saved image"/>
         <a href="products.php?delete_image=1&edit=<?=$edit_id; ?>&imgi=<?=$imgi; ?>" class="text-danger">Delete Image</a>
       </div>
     <?php
        $imgi++;
        endforeach;
     ?>
     <?php else: ?>
      <label for="photo">Product image:</label>
      <input type="file" class="form-control" name="photo[]" id="photo" multiple="" />
    <?php endif; ?>
    </div>

    <div class="form-group col-md-12">
      <label for="description">Description:</label>
      <textarea id="description" name="description" class="form-control" rows="6"><?=$description; ?></textarea>
    </div>

    <div class="form-group pull-right">
      <a href="products.php" class="btn btn-default">Cancel</a>
      <input type="submit" name="save_product" value="<?=((isset($_GET['edit']))?'Save ':'Add '); ?> Product" class="btn btn-success" />
    </div><div class="clearfix"></div>

</form>
<script>
 CKEDITOR.replace('description');
</script>

<!-- size Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Size & Quantity</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
              <?php for($i=1; $i <= 1; $i++): ?>
                <div class="col-md-3"></div>
                <div class="form-group col-md-7">
                  <label for="size<?=$i; ?>">Size: </label>
                  <input type="text" name="size<?=$i; ?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:''); ?>" class="form-control">
                </div>
                <div class="col-md-3"></div>
                <div class="form-group col-md-7">
                  <label for="qty<?=$i; ?>">Quantity: </label>
                  <input type="text" name="qty<?=$i; ?>" id="qty<?=$i; ?>" onchange="echange(this)" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:''); ?>" min="0" class="form-control">
                </div>
                <div class="col-md-3"></div>
                <div class="form-group col-md-7">
                  <label for="threshold<?=$i; ?>">Threshold: </label>
                  <input type="text" name="threshold<?=$i; ?>" id="threshold<?=$i; ?>" onchange="echange(this)" value="<?=((!empty($tArray[$i-1]))?$tArray[$i-1]:''); ?>" min="0" class="form-control">
                </div>
              <?php endfor; ?>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>
 </section>
 <?php /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                          end of  body of add and edit product
 ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>
<?php } else{ ?>
<!--product_details-->
<?php include 'template/_product_details.php'; ?>
<!--!product_details-->
<?php } ?>

<!--footer-->
<?php include 'includes/footer.php'; ?>
<!--!footer-->

<script>
 $(document).ready(function(){
   get_child_options('<?=$category; ?>');
 });
</script>
