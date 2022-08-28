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
 //--get brands from database----
 if (has_permission('seller')){
   $sql = "SELECT * FROM brand ORDER BY brand";
 }else {
   $sql = "SELECT * FROM brand ORDER BY brand";
 }
 $results = $db->query($sql);
 $errors = array();

 //edit Brands
 if(isset($_GET['edit']) && !empty($_GET['edit'])){
   $edit_id = (int)$_GET['edit'];
   $edit_id = sanitize($edit_id);
   $sql2 = "SELECT * FROM brand WHERE id = '$edit_id'";
   $edit_result = $db->query($sql2);
   $eBrand = mysqli_fetch_assoc($edit_result);
 }

 //--If add form is submited--
 if(isset($_POST['add_submit'])){
   $brand = sanitize($_POST['brand']);
   //check if brand is blank
   if($_POST['brand'] == ''){
     $errors[].='You must enter a brand!';
   }
   // check if brand exits in database--
   $sql = "SELECT * FROM brand WHERE brand = '$brand'";
   if(isset($_GET['edit'])){
     $sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'";
   }
   $result = $db->query($sql);
   $count = mysqli_num_rows($result);
   if($count > 0){
     $errors[] .= $brand.' brand already exits. Please choose another brand name:';
      }
   //dispaly errors--
   if(!empty($errors)){
     echo display_errors($errors);
   }else {
     //--Add brand to database
     $sql = "INSERT INTO `brand`(`user_id`, `brand`) VALUES ('$user_id','$brand')";
     if(isset($_GET['edit'])){
       $sql = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
     }
     $db->query($sql);
     header('Location: brands.php');
   }

 }
 ?>

<section class="text-info text-center">
  <h2>Brands</h2><hr />
  <!--Brand form-->
<div >
  <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
    <div class="form-group">
      <?php
      $brand_value = '';
       if(isset($_GET['edit'])){
        $brand_value = $eBrand['brand'];
      }else{
        if(isset($_POST['brand'])){
          $brand_value = sanitize($_POST['brand']);
        }
      } ?>
      <label for="brand"><?=((isset($_GET['edit']))?'Edit ':'Add a '); ?>Brand:</label>
      <input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value; ?>" >
      <?php if(isset($_GET['edit'])): ?>
       <a href="brands.php" class="btn btn-default">Cancel</a>
      <?php endif; ?>
      <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add '); ?> Brand" class="btn btn-success" />
    </div>
  </form>
</div><hr />
<!--brand table-->

  <div class="col-md-2" style="padding-top:120px;"><a href="index.php" class="btn btn-info pull-left"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Back</a></div>
  <div class="col-md-8">
  <table class="table table-bordered table-striped table-auto table-condensed">
    <thead>
      <th></th><th>Brand</th>
    </thead>
    <tbody>
      <?php while($brand = mysqli_fetch_assoc($results)) : ?>
      <tr>
       <td><a href="brands.php?edit=<?=$brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
       <td><?=$brand['brand']; ?></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
  </div>
  <div class="col-md-2" style="padding-top:120px;"><a href="categories.php" class="btn btn-info pull-right">Next <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a></div>

<!--End of brand table-->
</section>
<?php include 'includes/footer.php'; ?>

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
                    data     : {delete_brands_id : delete_id},
                    datatype : 'text',
                    success : function(data){
                      th.parents('tr').hide();
                      Swal.fire({
                        icon: 'success',
                        title: 'Brands has been deleted.',
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
