<?php
//--setting header to json
 //header('Content-Type: application/json');
 require_once $_SERVER['DOCUMENT_ROOT'].'/nws_admin/core/init.php';
 $productResults = $db->query("SELECT * FROM product WHERE id = 27");
 $product = mysqli_fetch_assoc($productResults);
 $title = $product['title'];
 var_dump($title);
 ?>
 <button class="btn btn-xs btn-default" onclick="update_cart('<?=$product['id']; ?>');">submit</button>

<script>
function update_cart(edit_id){
  var data = {"edit_id" : edit_id};
  jQuery.ajax({
   url : '/electroni/admin/chartjs/js/barChart.js',
    method : "post",
    data : data,
    success : function(){location.reload();},
    error : function(){
      alert("Something went wrong!");
    },
  });
}
</script>
