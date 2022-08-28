
<?php
require "vendor/autoload.php";

if (!$_GET['text']) {
    header("location: index.php");
    die();
}else {
  $text = $_GET['text'];
  $rand_no = rand(1000,100000);
}

$Bar = new Picqer\Barcode\BarcodeGeneratorHTML();
$code = $Bar->getBarcode($rand_no, $Bar::TYPE_CODE_128);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Bar Codes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

    <!--sweetalert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <style>
    @page{
      size:auto;
      margin: 10px;
    }
        body, html {
            height: 100%;
        }
        .bg {
            background-image: url("images/bg.jpg");
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        #barcode{
          margin-left: auto;
          margin-right: auto;
          margin-top: 100px;
          width:210mm;
          height:150px;
        }

    </style>
</head>
<body>
    <div class="container-fluid" id="center" style="margin-bottom:20px;">
        <br><br><br>
                <div class="text-center">
                    <h1>Generated BarCodes</h1>
                </div>
                <hr>
                  <div class="text-center" id="barcode">
                      <input type="text" id="bar_code" style="font-size:25px; letter-spacing:5px; border-radius:5px;" onmouseover="xover(this)" onmouseout="xout(this)" value="PR<?=strtoupper($text).$rand_no;?>"/>
                      <input type="button" id="barcode_copy" style="font-size:25px; letter-spacing:5px; border-radius:5px;" onmouseover="xover(this)" onmouseout="xout(this)" onclick="xcopy()" value="Copy"/>
                  </div>
                <hr>
                <div class="text-center">
                  <button type="button" name="button" class="btn-default" style="border-radius:5px;"><a href="index.php" style="text-decoration:none;">Generate Again</a></button>
                  <button type="button" name="button" class="btn-default" style="border-radius:5px;"><a href="../admin/index.php" style="text-decoration:none;">Dashboard</a></button>
                </div>
            </div>
    </div>
</body>
</html>

<script type="text/javascript">
  function xover(e){
    e.style.background="#dbffdb";
  }

  function xout(e){
    e.style.background="#fff";
  }

  function xcopy() {
  var copyText = document.getElementById("bar_code");
  copyText.select();
  document.execCommand("copy");
  //swal("Copied Barcode: " + copyText.value);
  Swal.fire({
    icon: 'success',
    title: 'Copied Barcode: '+ copyText.value,
    showConfirmButton: false,
    timer: 1500
  });
}
</script>
