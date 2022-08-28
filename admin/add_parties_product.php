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

$party_id = ((isset($_GET['party_id']) && $_GET['party_id'] != '')?sanitize($_GET['party_id']):'');
$bill_id = ((isset($_GET['bill_id']) && $_GET['bill_id'] != '')?sanitize($_GET['bill_id']):'');

if(isset($_GET['add']) || isset($_GET['edit'])){ //-----1st if condition-------
  $party_id = ((isset($_GET['party_id']) && $_GET['party_id'] != '')?sanitize($_GET['party_id']):'');
  $bill_id = ((isset($_GET['bill_id']) && $_GET['bill_id'] != '')?sanitize($_GET['bill_id']):'');
  $partyQuery = $db->query("SELECT * FROM parties WHERE id = '$party_id' AND deleted = 0 AND user_id = $user_id");
  $party_res = mysqli_fetch_assoc($partyQuery);
  $party_name = $party_res['party_name'];

  $BillQuery = $db->query("SELECT * FROM party_bill_no WHERE id = '$bill_id' AND deleted = 0");
  $bill_res = mysqli_fetch_assoc($BillQuery);
  $bill_no = $bill_res['bill_no'];
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
    $cost_price = ((isset($_POST['cost_price']) && $_POST['cost_price'] != '')?sanitize($_POST['cost_price']):'');
    $selll_price = ((isset($_POST['selll_price']) && $_POST['selll_price'] != '')?sanitize($_POST['selll_price']):'');
    $size = ((isset($_POST['size']) && !empty($_POST['size']))?sanitize($_POST['size']):'');
    $qty = ((isset($_POST['qty']) && !empty($_POST['qty']))?sanitize($_POST['qty']):'');

if(isset($_GET['edit'])){//----------2nd if condition-------
  $edit_id = (int)$_GET['edit'];
  $productResults = $db->query("SELECT * FROM parties_product WHERE id = '$edit_id' AND user_id = $user_id");
  $product = mysqli_fetch_assoc($productResults);

  $title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
  $cost_price = ((isset($_POST['cost_price']) && !empty($_POST['cost_price']))?sanitize($_POST['cost_price']):$product['cost_price']);
  $selll_price = ((isset($_POST['selll_price']) && !empty($_POST['selll_price']))?sanitize($_POST['selll_price']):$product['selll_price']);
  $size = ((isset($_POST['size']) && !empty($_POST['size']))?sanitize($_POST['size']):$product['size']);
  $qty = ((isset($_POST['qty']) && !empty($_POST['qty']))?sanitize($_POST['qty']):$product['qty']);

}//-----------------------------end of isset edit if condition-------

if($_POST){//-------------------4th if condition--------------
  $errors = array();
  $required = array('party_name','title','size','cost_price','selll_price','bill_no','qty');
  foreach ($required as $field) {
    if($_POST[$field] == ''){
      $errors[] = 'All fields with an Astrick are required!';
      break;
    }
  }

  if (!is_numeric($cost_price) || !is_numeric($qty)){
    $errors[] = 'Enter Numberic value in Buy price and in Quantity!';
  }

  if (!is_numeric($selll_price)){
    $errors[] = 'Enter Numberic value in Buy price and in Quantity!';
  }

  if(!empty($errors)){
    echo display_errors($errors);
  }else{ // upload file and insert into database - else condition.
      $insertSql = "INSERT INTO `parties_product`(`user_id`, `title`, `cost_price`,`selll_price`, `party_id`, `party_bill_id`, `size`, `qty`)
                                          VALUES ('$user_id','$title','$cost_price','$selll_price','$party_id','$bill_id','$size','$qty')";
    if(isset($_GET['edit'])){
      if (has_permission('admin')) {
        $insertSql = "UPDATE `parties_product` SET `title`='$title',`cost_price`='$cost_price',`selll_price`='$selll_price',`party_id`='$party_id',`party_bill_id`='$bill_id',`size`='$size',`qty`='$qty' WHERE id = '$edit_id' ";
      }else {
      $insertSql = "UPDATE `parties_product` SET `user_id`='$user_id',`title`='$title',`cost_price`='$cost_price',`selll_price`='$selll_price',`party_id`='$party_id',`party_bill_id`='$bill_id',`size`='$size',`qty`='$qty' WHERE id = '$edit_id' ";
      }
    }
    $db->query($insertSql);
    header('Location: add_parties_product.php?party_id='.$party_id.'&bill_id='.$bill_id);
  } // end of upload file and insert into database - else conditionre
}//-----------------end of 4th if condition-------


 ?>
 <?php /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                                                                        body of add and edit product
 ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>
 <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit ':'Add A New '); ?>Parties Product</h2> <hr />
 <section class="container fluid">
 <form action="add_parties_product.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>&party_id=<?=$party_id;?>&bill_id=<?=$bill_id;?>" method="POST" enctype="multipart/form-data">
   <div class="form-group col-md-6">
     <label for="party_name">Party Name * :</label>
     <input type="text" name="party_name" class="form-control" id="party_name" value="<?=$party_name;?>" readonly/>
   </div>

  <div class="form-group col-md-6">
    <label for="bill_no">Bill No * :</label>
    <input type="text" name="bill_no" class="form-control" id="bill_no" value="<?=$bill_no;?>" readonly/>
  </div>

  <div class="form-group col-md-6">
    <label for="title">Title * :</label>
    <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>" />
  </div>

  <div class="form-group col-md-3">
    <label for="cost_price">Buying price *:</label>
    <input type="text" id="cost_price" name="cost_price" class="form-control" value="<?=$cost_price;?>" onchange="echange_cp(this)"></input>
  </div>

  <div class="form-group col-md-3">
    <label for="sell_price">Selling price *:</label>
    <input type="text" id="selll_price" name="selll_price" class="form-control" value="<?=$selll_price;?>" onchange="echange_sp(this)"></input>
  </div>

  <div class="form-group col-md-6">
    <label for="size">Size *:</label>
    <input type="text" id="size" name="size" class="form-control" value="<?=$size; ?>"></input>
  </div>

  <div class="form-group col-md-6">
    <label for="qty">Quantity *:</label>
    <input type="text" id="qty" name="qty" class="form-control" value="<?=$qty;?>" onchange="echange_qty(this)"></input>
  </div>

    <div class="form-group pull-right">
      <a href="add_parties_product.php?party_id=<?=$party_id;?>&bill_id=<?=$bill_id;?>" class="btn btn-default">Cancel</a>
      <input type="submit" name="save_product" value="<?=((isset($_GET['edit']))?'Save ':'Add '); ?> Product" class="btn btn-success" />
    </div><div class="clearfix"></div>

</form>
 </section>
 <?php /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                                                                      end of  body of add and edit product
 ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>
<?php }//-----end of isset add if----------
else{ ?>
  <?php //-----------------------------product details-----------------------?>
<section class="container-fluid">
<div class="row" >
  <h2 class="text-center">Parties Products</h2><hr>
</div>
<div class="row container-fluid text-center" >
  <a href="add_parties_product.php?add=1&party_id=<?=$party_id;?>&bill_id=<?=$bill_id;?>" class="btn btn-success pull-right" id="add-product-btn">Add New Product</a>
  <a href="parties.php" class="btn btn-info pull-right" id="add-product-btn" style="margin-right:10px;" >Back</a>
</div>
</div><hr />
<div class="row container-fluid">
  <table class="table table-bordered table-condensed table-striped">
   <thead>
     <th class="text-center">Edit and Delete</th>
     <th>Products</th>
     <th>Buying Rate</th>
     <th>Selling Rate</th>
     <th>Party Name ~ Bill No.</th>
     <th>Size</th>
     <th>Quantity</th>
   </thead>
   <tbody>
     <?php
     if (has_permission('seller')) {
       $sql = "SELECT * FROM parties_product WHERE deleted = 0 AND user_id = '$user_id' AND party_id = '$party_id' AND party_bill_id ='$bill_id'";
     }else {
       $sql = "SELECT * FROM parties_product WHERE deleted = 0 AND party_id = '$party_id' AND party_bill_id ='$bill_id'";
     }
   $presults = $db->query($sql);
     while($product = mysqli_fetch_assoc($presults)):
       $party_id = $product['party_id'];
       $bill_id = $product['party_bill_id'];
       $cost_price = $product['cost_price'];
       $selll_price = $product['selll_price'];
       $party_id = $product['party_id'];
       $size = $product['size'];
       $qty = $product['qty'];
       $partySql = "SELECT * FROM parties WHERE id = '$party_id' AND user_id = $user_id";
       $party_result = $db->query($partySql);
       $party_name = mysqli_fetch_assoc($party_result);
       $billSql = "SELECT * FROM party_bill_no WHERE id = '$bill_id'";
       $bill_result = $db->query($billSql);
       $bill_name = mysqli_fetch_assoc($bill_result);
       $parties = $party_name['party_name'].' ~ '.$bill_name['bill_no'];
       ?>
      <tr>
        <td class="text-center">
          <a href="add_parties_product.php?edit=<?=$product['id'];?>&party_id=<?=$party_id;?>&bill_id=<?=$bill_id;?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
          <button type="button" id="<?=$product['id']; ?>" name="button" class="btn btn-xs btn-default xdelete"><span class="glyphicon glyphicon-remove"></button>
        </td>
        <td><?=($product['title']); ?></td>
        <td><?=money($cost_price);?></td>
        <td><?=money($selll_price);?></td>
        <td><?=$parties;?></td>
        <td><?=$size;?></td>
        <td><?=$qty;?></td>
      </tr>
     <?php endwhile; ?>
   </tbody>
  </table>
</div>
</section>
<?php //-----------------------------enf of product details-----------------------?>
<?php } include 'includes/footer.php';?>

<script type="text/javascript">
  function echange_qty(e) {
    var qty = document.getElementById('qty').value;
    if(isNaN(qty)){
      e.style.background="#f34c70";
      alert('Enter numberic value!');
    }else{
      e.style.background="white";
    }
  }
  function echange_cp(b) {
    var buy_price = document.getElementById('cost_price').value;
    if (isNaN(buy_price)) {
      b.style.background="#f34c70";
      swal ( "Oops" ,  "Enter only numberic value!" ,  "error" );
    }else {
      b.style.background="white";
    }
  }
  function echange_sp(s) {
    var selll_price = document.getElementById('selll_price').value;
    if (isNaN(selll_price)) {
      s.style.background="#f34c70";
      swal ( "Oops" ,  "Enter only numberic value!" ,  "error" );
    }else {
      s.style.background="white";
    }
  }
</script>

<!--deleted through ajax-->
<script type="text/javascript">
    $(document).ready(function() {
        $(".xdelete").click(function() {
          var delete_id = $(this).attr('id');
          var th = $(this);
          console.log(delete_id);
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
                    data     : {delete_party_product_id : delete_id},
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
