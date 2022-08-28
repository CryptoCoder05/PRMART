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

  if (has_permission('seller')){
    $sql = "SELECT * FROM categories WHERE parent = 0 ORDER BY categories";
  }else {
    $sql = "SELECT * FROM categories WHERE parent = 0";
  }
  $result = $db->query($sql);
  $errors = array();
  $category = '';
  $post_parent = '';

//---edit category--
if(isset($_GET['edit']) && !empty($_GET['edit'])){
  $edit_id = (int)$_GET['edit'];
  $edit_id = sanitize($edit_id);
  $edit_sql = "SELECT * FROM categories WHERE id = '$edit_id'";
  $edit_result = $db->query($edit_sql);
  $edit_category = mysqli_fetch_assoc($edit_result);
}

  //---process form---
  if(isset($_POST) && !empty($_POST)){
    $post_parent = sanitize($_POST['parent']);
    $category = sanitize($_POST['category']);
    $sqlform = "SELECT * FROM categories WHERE categories = '$category' AND parent = '$post_parent'";
    if(isset($_GET['edit'])){
      $id = $edit_category['id'];
      $sqlform = "SELECT * FROM categories WHERE categories = '$category' AND parent ='$post_parent' AND id != '$id'";
    }
    $fresult = $db->query($sqlform);
    $count = mysqli_num_rows($fresult);
    //--if category is blank--
    if($category == ''){
      $errors[] .= 'The category cannot be left blank.';
    }
    //if exits in the database--
    if($count > 0){
      $errors[] .= $category. ' already exit. please choose a new category.';
    }
    //--Display errors or update database---
    if(!empty($errors)){
      //---display errors--
      $display = display_errors($errors); ?>
      <script>
      jQuery('document').ready(function(){
        jQuery('#errors').html('<?=$display; ?>');
      })
      </script>
    <?php } else {
      //update database
      $updatesql = "INSERT INTO categories (user_id,categories, parent) VALUES ('$user_id','$category','$post_parent')";
      if(isset($_GET['edit'])){
        $updatesql = "UPDATE categories SET categories = '$category', parent = '$post_parent' WHERE id = '$edit_id'";
      }
      $db->query($updatesql);
      header('Location: categories.php');
    }
  }
  $category_value = '' ;
  $parent_value = 0;
  if(isset($_GET['edit'])){
    $category_value = $edit_category['categories'];
    $parent_value = $edit_category['parent'];
  }else {
    if(isset($_POST)){
        $category_value = $category;
        $parent_value = $post_parent;
    }
  }
 ?>
<section class="container-fluid">
<div class="row">
  <div class="row">
  <div class="col-md-2" style="padding-top:24px;"><a href="brands.php" class="btn btn-info pull-left"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Back</a></div>
  <div class="col-md-8">
    <h2 class="text-center">Categories</h2>
  </div>
  <div class="col-md-2" style="padding-top:24px;"><a href="products.php?add=1" class="btn btn-info pull-right">Next <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a></div>
 </div>
  <hr />

  <!--- form ------------------------------->
 <div class="col-md-6" id="category">
 <form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post">
   <legend><?=((isset($_GET['edit']))?'Edit':'Add A'); ?> category</legend>
   <div id="errors"></div>
   <div class="form-group">
     <label for="parent">Parent</label>
     <select class="form-control" name="parent" id="parent">
       <option value="0"<?=(($parent_value == 0)?' selected="selected"':''); ?>>Parent</option>
       <?php while($parent = mysqli_fetch_assoc($result)): ?>
         <option value="<?=$parent['id']; ?>"<?=(($parent_value == $parent['id'])?' selected="selected"':''); ?>><?=$parent['categories']; ?></option>
       <?php endwhile; ?>
     </select>
   </div>
   <div class="form-group">
     <label for="category">Category</label>
     <input type="text" class="form-control" id="category" name="category" value="<?=$category_value; ?>"/>
   </div>
   <div class="form-group">
     <input type="submit" name="" value="<?=((isset($_GET['edit']))?'Edit':'Add'); ?> category" class="btn btn-success" />
   </div>
 </form>
 </div>
 <!--categories table-->
 <div class="col-md-6">
   <table class="table table-bordered">
     <thead>
       <th>Category</th><th>Parent</th><th></th>
     </thead>
     <tbody>
       <?php
       if (has_permission('seller')){
         $sql = "SELECT * FROM categories WHERE parent = 0 ORDER BY categories";
       }else {
         $sql = "SELECT * FROM categories WHERE parent = 0";
       }
       $result = $db->query($sql);
       while($parent = mysqli_fetch_assoc($result)) :
         $parent_id = (int)$parent['id'];
         $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id' ORDER BY categories";
         $cresult = $db->query($sql2);
         ?>
       <tr class="bg-primary">
         <td><?=$parent['categories']; ?></td>
         <td>Parent</td>
         <td>
           <a href="categories.php?edit=<?=$parent['id']; ?>" class="btn btn-xs btn-default"><sapn class="glyphicon glyphicon-pencil"></sapn></a>
         </td>
       </tr>
       <?php while($child = mysqli_fetch_assoc($cresult)): ?>
         <tr class="bg-info">
           <td><?=$child['categories']; ?></td>
           <td><?=$parent['categories']; ?></td>
           <td>
             <a href="categories.php?edit=<?=$child['id']; ?>" class="btn btn-xs btn-default"><sapn class="glyphicon glyphicon-pencil"></sapn></a>
           </td>
         </tr>
       <?php endwhile; ?>
     <?php endwhile; ?>
     </tbody>
   </table>
 </div>
</div>
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
                     data     : {delete_categories_id : delete_id},
                     datatype : 'text',
                     success : function(data){
                       th.parents('tr').hide();
                       Swal.fire({
                         icon: 'success',
                         title: 'Category has been deleted.',
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
