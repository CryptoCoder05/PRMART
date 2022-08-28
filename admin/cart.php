<?php
 require_once '../core/init.php';
 require 'php_action/_cart_action.php';
 include 'includes/head.php';
 include 'includes/navigation.php';
?>

<!---HTML file-->
<!--left side column-->
<?php include 'template/_cart.php';  ?>
<!--end of left side column-->

<!--footer-->
<?php include 'includes/footer.php'; ?>
<!--end of footer-->

<!---Script file-->
<!--script to focus in barcode scan box-->
<script type="text/javascript">
 function getfocustxt()
  {
   document.getElementById('barcode').focus();
  }
</script>

<!--substracting input money-->
<script type="text/javascript">
   $(document).ready(function () {
     output();
     $('#Input, #Total').on('keydown keyup', function () {
       output();
     });
   });

   function output() {
     var input = $('#Input').val();
     var total = $('#Total').val();
     var output = parseInt(input) - parseInt(total);
     if(!isNaN(output)){
       document.getElementById('output').value = output;
     }
   }
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
              confirmButtonText: 'Yes, Remove it!'
            }).then((result) => {
              if (result.value) {
                if(delete_id != ''){
                  $.ajax({
                    url      : 'deleted.php',
                    method   : 'POST',
                    data     : {delete_cart_id : delete_id},
                    datatype : 'text',
                    success : function(data){
                      th.parents('tr').hide();
                      Swal.fire({
                        icon: 'success',
                        title: 'Product has been removed.',
                        showConfirmButton: false,
                        timer: 1500
                      });
                      $('#refress').load('cart.php');
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
