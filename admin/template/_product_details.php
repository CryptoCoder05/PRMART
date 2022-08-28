<style media="screen">
#searchBox{
  width: 50%;
  margin: auto;
}
#searchBox input,button{
  width: 75%;
  border: 2px solid black;
  font-size: 18px;
  padding: 12px;
}
#searchBox button{
  width: 25%;
  background: #eee;
  color: black;
  float: right;
  border-left: 0px;
}
#searchBox button:hover{
  opacity: .8;
}
#searchBox input:hover{
  opacity: .8;
}
</style>
<!--Search product-->
<h2 class="text-center">Products</h2><hr>
<section>
  <div id="searchBox">
    <form class="" action="products.php" method="post">
      <input list="search" name="search_prod" placeholder="Search Product" value="">
      <datalist id="search">
        <?php
        if (has_permission('seller')){
          $prod_search_sql = "SELECT * FROM product WHERE deleted = 0 AND user_id = '$user_id' ORDER BY title";
        }else {
          $prod_search_sql = "SELECT * FROM product WHERE deleted = 0 ORDER BY title";
        }
        $pred_res = $db->query($prod_search_sql);
        while($prod_title = mysqli_fetch_assoc($pred_res)):
          ?>
        <option value="<?=$prod_title['title'];?>">
        <?php endwhile ?>
      </datalist>
      <button type="submit" name="submit"><i class="fas fa-search"></i> Search</button>
    </form>
  </div>
</section><hr>
<!--!Search product-->

<!--product details-->
<section id="product_details">
  <div class="container-fluid">
    <div class="row container-fluid">
      <table class="table table-bordered table-condensed table-striped" style="font-size:13.8px;">
       <thead>
         <tr class="info">
           <th>S.No</th>
           <th>Edit Delete</th>
           <th>Products</th>
           <th>Title</th>
           <th>Buying price</th>
           <th>Selling Price</th>
           <th>MRP</th>
           <th>Category</th>
           <th>Qty</th>
           <th>Buying Amt</th>
           <th>Selling Amt</th>
           <th>status</th>
         </tr>
       </thead>
       <tbody>
         <?php
         $i =0;
         $total_stock = 0;
         $total_sell_stock = 0;
         if (has_permission('seller')){
           $sql = "SELECT * FROM product WHERE deleted = 0 AND user_id = '$user_id' ORDER BY title";
         }
         else {
           $sql = "SELECT * FROM product WHERE deleted = 0 ORDER BY title";
         }

         if (!empty($_POST['search_prod'])) {
           $prod_title = $_POST['search_prod'];
           $sql = "SELECT * FROM product WHERE deleted = 0 AND title = '$prod_title' AND user_id = '$user_id'";
         }
         $presults = $db->query($sql);
          while($product = mysqli_fetch_assoc($presults)):
           $feature = $product['featured'];
           $childID = $product['categories'];
           $catSql = " SELECT * FROM categories WHERE id = '$childID'";
           $result = $db->query($catSql);
           $child = mysqli_fetch_assoc($result);
           $parentID = $child['parent'];
           $parSql = "SELECT * FROM categories WHERE id = '$parentID'";
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
            <td class="text-center">
              <?php
               $photos = explode(',',$product['image']);
               foreach($photos as $photo){}
               ?>
              <img src="<?='../'.$photo;?>" alt="" class="img-fluid" style="height:120px;">
            </td>
            <td><?=($product['title']); ?></td>
            <td><?=money($product['buy_price']); ?></td>
            <td><?=money($product['selll_price']); ?></td>
            <td><?=money($product['mrp_price']); ?></td>
            <td><?=$category; ?></td>
             <td><?=($qty == 0 )?'Out of Stock!':$qty ; ?></td>
             <td><?=money($qty * $product['buy_price']);?></td>
             <td><?=money($qty * $product['selll_price']);?></td>
             <td class="text-<?=($feature == 0)?'danger':'info';?>"><?=($feature == 0)?'Pending for approval':'Approved';?></td>
          </tr>
         <?php
         $total_stock += $qty * $product['buy_price'];
         $total_sell_stock += $qty * $product['selll_price'];
        endwhile;
        ?>
       </tbody>
       <tfoot>
         <tr class="info">
           <td colspan="9" class="text-center">Stock Values</td>
           <td><?=money($total_stock);?></td>
           <td colspan="2"><?=money($total_sell_stock);?></td>
         </tr>
         <tr class="success">
           <td colspan="9" class="text-center">Total Profit</td>
           <td colspan="3"><?=money($total_sell_stock-$total_stock);?></td>
         </tr>
       </tfoot>
      </table>
    </div>
  </div>
</section>
<!--!product details-->
