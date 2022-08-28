<?php
 require_once '../core/init.php';
 include 'includes/head.php';
 include 'includes/navigation.php';
 include 'php_action/_fast_add_product.php';

 if(isset($_POST['save_product'])){
   $db->query("DELETE FROM `add_pro_bar` WHERE user_id = $user_id");
 }

if(isset($_GET['add']) || isset($_GET['edit'])){ //-----1st if condition-------
$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$price = ((isset($_POST['selll_price']) && $_POST['selll_price'] != '')?sanitize($_POST['selll_price']):'');
$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
$sizes = rtrim($sizes,',');

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
  $required = array('title','sizes','selll_price');
  foreach ($required as $field) {
    if($_POST[$field] == ''){
      $errors[] = 'All fields with an Astrick are required!';
      break;
    }
  }

  if(!empty($errors)){
    echo display_errors($errors);
  }else{ // upload file and insert into database - else condition.

    $insertSql = "INSERT INTO `product`(`user_id`,`title`,`selll_price`, `sizes`)
    VALUES ('$user_id','$title','$price','$sizes')";

    $sucess = $db->query($insertSql);
    if ($sucess === True) {
      header('Location: cart.php');
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

 <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit ':'Add A New '); ?> Product</h2> <hr />

<section>
  <!--new fast form-->
  <div class="container">
    <div class="row" style="margin:0 25% 0 25%;">
      <form action="fast_add_product.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group col-md-12">
          <label for="title">Title * :</label>
          <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>" />
        </div>
        <div class="form-group col-md-12">
          <label for="price">Selling price * :</label>
          <input type="text" id="price" onchange="echange(this)" name="selll_price" class="form-control" value="<?=$price; ?>"></input>
        </div>
        <div class="form-group col-md-12">
          <label>Quantity & Items * :</label>
          <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Items</button>
        </div>
        <div class="form-group col-md-12">
          <label for="sizes">Items & Qty Preview</label>
          <input type="text" class="form-control" name="sizes" id="sizes" value="<?=$sizes; ?>" readonly>
        </div>
        <div class="form-group pull-right" style="margin-right:15px;">
          <a href="cart.php" class="btn btn-default">Cancel</a>
          <input type="submit" name="save_product" value="<?=((isset($_GET['edit']))?'Save ':'Add '); ?> Product" class="btn btn-success" />
        </div>
      </form>
    </div>
  </div>
  <!--end of new fast form-->

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

<?php } //-----end of isset add if----------?>

<?php include 'includes/footer.php';?>

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
