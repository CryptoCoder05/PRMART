<!--==========================================================Main footer-------------------------------------------------------->
<div class="clearfix"></div>
<!--<footer class="text-center" id="footer">&copy; Copyright 2018-2020 prmart</footer>-->
<script>
function detailsmodal(id) {
  var data = {"id" : id};
  jQuery.ajax({
    url : '/prmart/details_model.php',
    method : "post",
    data : data,
    success : function(data){
      jQuery('body').append(data);
      jQuery('#details-modal').modal('toggle');
    },
    error : function(){
      alert("Something went wrong!");
    }
  });
}

function update_cart(mode,edit_id,edit_size){
  var data = {"mode" : mode, "edit_id" : edit_id, "edit_size" : edit_size};
  jQuery.ajax({
   url : '/prmart/admin/parsers/update_cart.php',
    method : "post",
    data : data,
    success : function(){location.reload();},
    error : function(){
      alert("Something went wrong!");
    },
  });
}
  </script>
<!--==========================================================End of Main footer-------------------------------------------------------->
<!-- Footer -->
<footer class="bg6" id="footer">
<div class="container">
  <div class="row">
          <div class="w-size6 p-t-30 p-l-15 p-r-15 respon3">
            <h4 class="s-text12 p-b-30">GET IN TOUCH</h4>
            <div>
              <p class="s-text7 w-size27">
                Any questions? Let us know or call us on 9825828666
              </p>
            </div>
          </div>
      </div>
      <div class="t-center p-l-15 p-r-15" style="padding-bottom:10px;">
        <div class="t-center s-text8 p-t-20">
          Copyright © 2020 All rights reserved.
        </div>
      </div>
  </div>
</footer>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/animsition/js/animsition.min.js"></script>
<script type="text/javascript" src="vendor/select2/select2.min.js"></script>
<script type="text/javascript">
  $(".selection-1").select2({
    minimumResultsForSearch: 20,
    dropdownParent: $('#dropDownSelect1')
  });
</script>
<script type="text/javascript" src="vendor/slick/slick.min.js"></script>
<script type="text/javascript" src="js/slick-custom.js"></script>
<script type="text/javascript" src="vendor/countdowntime/countdowntime.js"></script>
<script type="text/javascript" src="vendor/lightbox2/js/lightbox.min.js"></script>
<script type="text/javascript" src="vendor/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript">
  $('.block2-btn-addcart').each(function(){
    var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
    $(this).on('click', function(){
      swal(nameProduct, "is added to cart !", "success");
    });
  });

  $('.block2-btn-addwishlist').each(function(){
    var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
    $(this).on('click', function(){
      swal(nameProduct, "is added to wishlist !", "success");
    });
  });
</script>
<script src="js/main.js"></script>

</body>
</html>
