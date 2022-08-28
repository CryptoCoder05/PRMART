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
  $productQ = $db->query("SELECT * FROM add_pro_bar WHERE user_id = $user_id");
  $product_res = mysqli_fetch_assoc($productQ);
  $bar_count = mysqli_num_rows($productQ);
  $barcode = $product_res['barcode'];

  if(isset($_POST['save_product'])){
    $db->query("DELETE FROM `add_pro_bar` WHERE user_id = $user_id");
  }

// default value of some variable.
$dbpath = '';
$tmpLoc = '';
$uploadPath = '';


if(isset($_GET['add']) || isset($_GET['edit'])){ //-----1st if condition-------
$brandQuery = $db->query("SELECT * FROM brand WHERE user_id = $user_id ORDER BY brand");
$parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 AND user_id = $user_id ORDER BY categories");
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
  $parentQ = $db->query("SELECT * FROM categories WHERE id = '$category' AND user_id = $user_id");
  $pareentResult = mysqli_fetch_assoc($parentQ);
  $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$pareentResult['parent']);
  $sell_price = ((isset($_POST['sell_price']) && !empty($_POST['sell_price']))?sanitize($_POST['sell_price']):$product['sell_price']);
  $price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$product['price']);
  $list_price = ((isset($_POST['list_price']))?sanitize($_POST['list_price']):$product['list_price']);
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
  $required = array('Prod_barcode','title','sizes','sell_price','price','list_price');
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
    $insertSql = "INSERT INTO `product`(`user_id`,`barcode`,`title`,`sell_price`, `price`, `list_price`, `brand`, `categories`, `image`, `description`, `sizes`)
    VALUES ('$user_id','$Prod_barcode','$title','$sell_price','$price','$list_price','$brand','$category','$dbpath','$description','$sizes')";

    if(isset($_GET['edit'])){
      if (has_permission('admin')) {
        $insertSql = "UPDATE product SET barcode = '$Prod_barcode', title = '$title', sell_price = '$sell_price', price = '$price', list_price = '$list_price', brand = '$brand', categories = '$category', sizes = '$sizes', image = '$dbpath', description = '$description' WHERE id = '$edit_id' ";
      }else {
        $insertSql = "UPDATE product SET user_id = '$user_id', barcode = '$Prod_barcode', title = '$title', sell_price = '$sell_price', price = '$price', list_price = '$list_price', brand = '$brand', categories = '$category', sizes = '$sizes', image = '$dbpath', description = '$description'
                      WHERE id = '$edit_id' ";
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
  <div class="form-group col-md-12" >
    <label for="Prod_barcode">Barcode * :</label>
    <input type="text" name="Prod_barcode" class="form-control" id="Prod_barcode"  value="<?=$Prod_barcode;?>" />
  </div>

  <div class="form-group col-md-3">
    <label for="title">Title * :</label>
    <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>" />
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
     <label>Quantity & Items * :</label>
     <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Items</button>
   </div>

   <div class="form-group col-md-3">
     <label for="sizes">Items & Qty Preview</label>
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
        <h4 class="modal-title" id="sizesModalLabel">Items & Quantity</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <?php for($i=1; $i <= 12; $i++): ?>
            <div class="form-group col-md-2">
              <label for="size<?=$i; ?>">Items: </label>
              <input type="text" name="size<?=$i; ?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:''); ?>" class="form-control">
            </div>
            <div class="form-group col-md-2">
              <label for="qty<?=$i; ?>">Quantity: </label>
              <input type="text" name="qty<?=$i; ?>" id="qty<?=$i; ?>" onchange="echange(this)" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:''); ?>" min="0" class="form-control">
            </div>
            <div class="form-group col-md-2">
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
<?php }//-----end of isset add if----------
else{
    if (has_permission('seller')){
      $sql = "SELECT * FROM product WHERE deleted = 0 AND user_id = '$user_id' ORDER BY title";
    }else {
      $sql = "SELECT * FROM product WHERE deleted = 0 ORDER BY title";
    }

$presults = $db->query($sql);
if(isset($_GET['featured'])){
  $id = (int)$_GET['id'];
  $featured = (int)$_GET['featured'];
  $featuredsql = "UPDATE product SET featured = '$featured' WHERE id = '$id'";
  $db->query($featuredsql);
  header('Location: products.php');
}
 ?>
  <?php //-----------------------------product details-----------------------?>
<section class="container-fluid">
<div class="row" >
  <h2 class="text-center">Products</h2><hr>
</div>
<div class="row container-fluid text-center" >
  <div class="col-md-4">
    <div class="col-md-10" ></div>
    <div class="col-md-2">
      <label for="barcode"  id="barcode_text_p" >Barcode:</label>
    </div>
  </div>
  <div class="form-group col-md-4">
    <input type="text" name="barcode"  class="form-control" id="add_prod_barcode" placeholder="Scan barcode to check product..." value="<?=$barcode;?>"/>
  </div>
  <div class="col-md-4" >
    <div class="col-md-6" >
      <?php if ($bar_count > 0): ?>
          <a href="clear.php" name class="btn btn-default"> clear</a>
          <a href="products.php?edit=<?=$product_res['product_id']; ?>" class="btn btn-info"><span class="glyphicon glyphicon-pencil"></span> Update product</a>
        <?php else : ?>
          <a href="products.php?add=1" class="btn btn-success" id="add-product-btn" >Add New Product</a>
      <?php endif; ?>
    </div>
    <div class="col-md-6" ></div>
  </div>
</div><hr />
<div class="row container-fluid">
  <table class="table table-bordered table-condensed table-striped" style="font-size:13.8px;">
   <thead>
     <tr class="info">
       <th>S.No</th>
       <th>Edit Delete</th>
       <th>Products</th>
       <th>Title</th>
       <th>Buying Rate</th>
       <th>Selling Price</th>
       <th>MRP</th>
       <th>Category</th>
       <th>Featured</th>
       <th>Quantity</th>
       <th>Threshold</th>
       <th>Buying Amount</th>
       <th>Selling Amount</th>
     </tr>
   </thead>
   <tbody>
     <?php
     $i =0;
     $total_stock = 0;
     $total_sell_stock = 0;
      while($product = mysqli_fetch_assoc($presults)):
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
        $i++;
       ?>
      <tr<?=($qty == 0)?' class="danger"':''; ?>>
        <td><?=$i;?></td>
        <td class="text-center">
          <a href="products.php?edit=<?=$product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
          <button type="button" id="<?=$product['id']; ?>" name="button" class="btn btn-xs btn-default xdelete"><span class="glyphicon glyphicon-remove"></button>
        </td>
        <td><img src="<?='../'.$product['image'];?>" alt="" class="img-fluid" style="height:120px;"></td>
        <td><?=($product['title']); ?></td>
        <td><?=money($product['sell_price']); ?></td>
        <td><?=money($product['price']); ?></td>
        <td><?=money($product['list_price']); ?></td>
        <td><?=$category; ?></td>
        <td>
          <a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0'); ?>&id=<?=$product['id']; ?>" class="btn btn-xs btn-default">
            <span class="glyphicon glyphicon-<?=(($product['featured'] == 1)?'minus':'plus'); ?>"></span>
          </a>&nbsp <?=(($product['featured'] == 1)?'Hide':'Show'); ?>
        </td>
         <td><?=($qty == 0 )?'Out of Stock!':$qty ; ?></td>
         <td><?=$threshold;?></td>
         <td><?=money($qty * $product['sell_price']);?></td>
         <td><?=money($qty * $product['price']);?></td>
      </tr>
     <?php
     $total_stock += $qty * $product['sell_price'];
     $total_sell_stock += $qty * $product['price'];
    endwhile;
    ?>
   </tbody>
   <tfoot>
     <tr class="info">
       <td colspan="11" class="text-center">Stock Values</td>
       <td><?=money($total_stock);?></td>
       <td><?=money($total_sell_stock);?></td>
     </tr>
     <tr class="success">
       <td colspan="11" class="text-center">Total Profit</td>
       <td colspan="2"><?=money($total_sell_stock-$total_stock);?></td>
     </tr>
   </tfoot>
  </table>
</div>
</section>
<?php //-----------------------------enf of product details-----------------------?>
<?php } include 'includes/footer.php';?>
<script>
 $(document).ready(function(){
   get_child_options('<?=$category; ?>');
 });
</script>

<!--deleted through ajax-->
<script type="text/javascript">
    $(document).ready(function() {
        $(".xdelete").click(function() {
          var delete_id = $(this).attr('id');
          var th = $(this);
            Swal.fire({
              title: 'Are you sure?',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
              if (result.value) {
                if(delete_id != ''){
                  $.ajax({
                    url      : 'deleted.php',
                    method   : 'POST',
                    data     : {delete_product_id : delete_id},
                    datatype : 'text',
                    success : function(data){
                      th.parents('tr').hide();
                      Swal.fire({
                        icon: 'success',
                        title: 'Product has been deleted.',
                        showConfirmButton: false,
                        timer: 1500
                      });
                    },
                    error : function(){
                      swal ( "Oops" ,  "Something went wrong!" ,  "error" );
                    },
                  });
                }
              }
            })
        });
    });
</script>

<!--check numric value-->
<script type="text/javascript">
  function echange(e) {
    var check_val = e.value;
    if(isNaN(check_val)){
      e.style.background="#f26565";
      swal ( "Oops" ,  "Enter only numberic value!" ,  "error" );
    }else{
      e.style.background="white";
    }
  }
</script>
