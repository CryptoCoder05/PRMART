<div class="clearfix"></div>
<!--<footer class="text-center" id="footer">&copy; Copyright 2018-2020 seller.</footer>-->
<!--Barcode scan--->
<script>
 $(document).ready(function(){
   $('#barcode').keypress(function(){
     var scan_barcode = $(this).val();
     if(scan_barcode != ''){
       $.ajax({
         url      : 'fetch_barcode.php',
         method   : 'POST',
         data     : {fetch_barcode : scan_barcode},
         datatype : 'text',
         success : function(data){
           $('#result').html(data);
           setInterval(function(){
             location.reload();
           },500);
         },
         error : function(){
           alert("Something went wrong!");
         },
       });
     }
   });
 });
</script>

<!--Barcode scan while clicking on add to cart--->
<script>
 $(document).ready(function(){
   $('#add_to_cart').on('click',function(){
     var scan_barcode = $('#barcode_values').val();
     if(scan_barcode != ''){
       $.ajax({
         url      : 'fetch_barcode.php',
         method   : 'POST',
         data     : {fetch_barcode : scan_barcode},
         datatype : 'text',
         success : function(data){
           $('#refress').load('cart.php');
           //alert(scan_barcode);
         },
         error : function(){
           alert("Something went wrong!");
         },
       });
     }
   });
 });
</script>

<!--Barcode scan during add product--->
<script>
 $(document).ready(function(){
   $('#add_prod_barcode').keypress(function(){
     var scan_barcode = $(this).val();
     if(scan_barcode != ''){
       $.ajax({
         url      : 'products.php',
         method   : 'POST',
         data     : {fetch_barcode : scan_barcode},
         datatype : 'text',
         success : function(data){
           $('#result').html(data);
           setInterval(function(){
             location.reload();
           },500);
         },
         error : function(){
           alert("Something went wrong!");
         },
       });
     }
   });
 });
</script>


<!--update qty in barcode cart--->
<script>
function update_quantity(qty_box){
  var new_qty = qty_box.value;
  // unique id of this product
  var id = $(qty_box).parent().parent().attr("id");
  jQuery.ajax({
    url     : '/seller/admin/parsers/update_qty.php',
    method  : "post",
    data    : {edit_id : id, edit_quntity : new_qty},
    success : function (data){
        $('#refress').load('cart.php');
    },
 })
}
</script>

<!--update discount in barcode cart--->
<script>
function update_discount(dis_box){
  var new_dis = dis_box.value;
  var id = $(dis_box).parent().parent().attr("id");
  jQuery.ajax({
    url     : '/seller/admin/parsers/update_discount.php',
    method  : "post",
    data    : {edit_id : id, edit_dis : new_dis},
    success : function (data){
      $('#refress').load('cart.php');
    },
 })
}
</script>

<script>
function updateSizes(){
  var sizeString = '';
  for(var i=1; i <= 1; i++){
    if(jQuery('#size'+i).val()!= ''){
      sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+':'+jQuery('#threshold'+i).val()+',';
    }
  }
  jQuery('#sizes').val(sizeString);
}

function get_child_options(selected){
  if(typeof selected === 'undefined'){
    var selected = '';
  }
  var parentID = jQuery('#parent').val();
  jQuery.ajax({
    url: 'parsers/child_categories.php',
    type: 'POST',
    data: {parentID : parentID, selected : selected},
    success: function(data){
      jQuery('#child').html(data);
    },
    error: function(){alert("Somethig went wrong with the child option.")},
  });
}

 jQuery('select[name="parent"]').change(function(){
   get_child_options();
 });

</script>

<!--script to change background color of input field-->
<script type="text/javascript">
  function xfocus(e) {
    e.style.background = "#dbffdb";
    }
  function xblur(e) {
    e.style.background = "#fff";
    }
</script>

<!--deleted product from products.php through ajax -->
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

<!--check numric value from products.php-->
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script src="../js/ajax_script.js"></script>
<!--<script type="text/javascript" src="chartjs/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="chartjs/js/chart.js"></script>-->
<script type="text/javascript" src="chartjs/js/lineChart.js"></script>
<script type="text/javascript" src="chartjs/js/barChart.js"></script>
<script type="text/javascript" src="chartjs/js/app.js"></script>



</body>
</html>
