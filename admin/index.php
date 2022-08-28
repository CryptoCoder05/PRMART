<?php
// seller core file...
 require_once '../core/init.php';

 if(!is_logged_in()){
   permisssion_redirect('login.php');
 }
  if (has_permission('customer')) {
    permisssion_error_redirect_login('login.php');
  }
 //count new order.................................................
 $count_order = $db->query("SELECT * FROM transactions WHERE delivered = 0 AND customer_id = $user_id");
 $order = mysqli_num_rows($count_order);
  ?>

<!---Sales by Month-------------------------->
  <?php
     $thisYr = date("Y");
     $lastYr = $thisYr - 1;
     $thisYr_2 = date("Y");
     $lastYr_2 = $thisYr - 1;
     //--from transactions--
     $thisYrQ = $db->query("SELECT * FROM transactions WHERE YEAR(txn_date) = '{$thisYr}' AND customer_id = $user_id");
     $lastYrQ = $db->query("SELECT * FROM transactions WHERE YEAR(txn_date) = '{$lastYr}' AND customer_id = $user_id");
     //--from barcode_transaction--
     $thisYrBQ = $db->query("SELECT * FROM barcode_transaction WHERE YEAR(txn_date) = '{$thisYr}' AND user_id = $user_id");
     $lastYrBQ = $db->query("SELECT * FROM barcode_transaction WHERE YEAR(txn_date) = '{$lastYr}' AND user_id = $user_id");

     $current = array();
     $last = array();
     $currentTotal = 0;
     $lastTotal = 0;

     //--from barcode_transaction--
     while($x = mysqli_fetch_assoc($thisYrBQ)){
       $month = $x['months'];
       if(!array_key_exists($month,$current)){
         $current[$month] = $x['grand_total'];
       }else{
         $current[$month] += $x['grand_total'];
       }
       $currentTotal += $x['grand_total'];
     }
     while($y = mysqli_fetch_assoc($lastYrBQ)){
       $month = $y['months'];
       if(!array_key_exists($month,$last)){
         $last[$month] = $y['grand_total'];
       }else{
         $last[$month] += $y['grand_total'];
       }
       $lastTotal += $y['grand_total'];
     }

     //--from transactions--
     while($x = mysqli_fetch_assoc($thisYrQ)){
       $date = date_create($x['txn_date']);
       $month = date_format($date,'m');
       foreach (getPayment($x['payment_details']) as $payment) {}
       if(!array_key_exists($month,$current)){
         $current[$month] = $payment['subtotal'];
       }else{
         $current[$month] += $payment['subtotal'];
       }
       $currentTotal += $payment['subtotal'];
     }

     while($y = mysqli_fetch_assoc($lastYrQ)){
       $month = $y['months'];
       if(!array_key_exists($month,$last)){
         $last[$month] = $y['grand_total'];
       }else{
         $last[$month] += $y['grand_total'];
       }
       $lastTotal += $y['grand_total'];
     }
   ?>

  <!---sales by day---------------------------------------->
     <?php
        $thisYr = date("m");
        $lastYr = $thisYr - 1;
        //--from transactions--
        $thisYrQ = $db->query("SELECT * FROM transactions WHERE MONTH(txn_date) = '{$thisYr}' AND customer_id = $user_id");
        $lastYrQ = $db->query("SELECT * FROM transactions WHERE MONTH(txn_date) = '{$lastYr}' AND customer_id = $user_id");
        //--from barcode_transaction--
        $thisYrBQ = $db->query("SELECT * FROM barcode_transaction WHERE MONTH(txn_date) = '{$thisYr}' AND user_id = $user_id");
        $lastYrBQ = $db->query("SELECT * FROM barcode_transaction WHERE MONTH(txn_date) = '{$lastYr}' AND user_id = $user_id");

        $day_current = array();
        $day_last = array();
        $day_currentTotal = 0;
        $day_lastTotal = 0;

        //--from barcode_transaction--
        while($x = mysqli_fetch_assoc($thisYrBQ)){
          $day = $x['day'];
          if(!array_key_exists($day,$day_current)){
            $day_current[$day] = $x['grand_total'];
          }else{
            $day_current[$day] += $x['grand_total'];
          }
          $day_currentTotal += $x['grand_total'];
        }
        while($y = mysqli_fetch_assoc($lastYrBQ)){
          $day = $y['day'];
          if(!array_key_exists($day,$day_last)){
            $day_last[$day] = $y['grand_total'];
          }else{
            $day_last[$day] += $y['grand_total'];
          }
          $day_lastTotal += $y['grand_total'];
        }
        //--from transactions--
        while($x = mysqli_fetch_assoc($thisYrQ)){
          $day = $x['day'];
          if(!array_key_exists($day,$day_current)){
            $day_current[$day] = $x['grand_total'];
          }else{
            $day_current[$day] += $x['grand_total'];
          }
          $day_currentTotal += $x['grand_total'];
        }
        while($y = mysqli_fetch_assoc($lastYrQ)){
          $date = date_create($y['txn_date']);
          $day = date_format($date, 'd');
          foreach (getPayment($y['payment_details']) as $payment) {}
          if(!array_key_exists($day,$day_last)){
            $day_last[$day] = $payment['subtotal'];
          }else{
            $day_last[$day] += $payment['subtotal'];
          }
          $day_lastTotal += $payment['subtotal'];
        }

      ?>

<!--low Inventory----------------------------------------------------->
   <?php
     $iQuery = $db->query("SELECT * FROM product WHERE deleted = 0 AND user_id = $user_id ORDER BY title");
     $lowItems = array();
     while($product = mysqli_fetch_assoc($iQuery)){
       $item = array();
       $sizes = sizesToArray($product['sizes']);
       foreach($sizes as $size){
         if($size['quantity'] <= $size['threshold']){
           $cat = get_category($product['categories']);
           $item = array(
             'title' => $product['title'],
             'size'  => $size['size'],
             'quantity'  => $size['quantity'],
             'threshold'  => $size['threshold'],
             'Category'  => $cat['parent'] . ' ~ ' .$cat['child']
           );
           $lowItems[] = $item;
         }
       }
     }
    ?>
<!-- end of low Inventory----------------------------------------------------->
 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <meta name="description" content="">
     <meta name="author" content="">

   <title>Administrator</title>
   <!-- Bootstrap core CSS-->
     <link href="../vendor/bootstrap2/css/bootstrap.css" rel="stylesheet">

     <!-- Custom fonts for this template-->
     <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

     <!-- Custom styles for this template-->
     <link href="../css/sb-admin.css" rel="stylesheet">

     <!-- Custom Fonts -->
     <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

     <!-- jquery-3 -->
     <script src="../js/jquery-3.2.1.min.js"></script>
     <script src="../js/bootstrap.min.js"></script>
     <script src="../js/jquery.js"></script>
     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
     <!-- css -->
     <link rel="stylesheet" href="../css/style.css">
 </head>
 <body>


    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

      <a class="navbar-brand mr-1" href="index.php"><?=SHOPNAME; ?></a>

      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Navbar Search -->
      <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
          <div class="input-group-append">
            <button class="btn btn-primary" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>

      <!-- Navbar -->
      <ul class="navbar-nav ml-auto ml-md-0">
        <li class="nav-item dropdown no-arrow mx-1">
          <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw"></i>
            <span class="badge badge-danger">0</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
        <li class="nav-item dropdown no-arrow mx-1">
          <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-envelope fa-fw"></i>
            <span class="badge badge-danger">0</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user-circle fa-fw"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#">Hello <?=$user_data['first']; ?>!</a>
            <a class="dropdown-item" href="profile.php">Profile</a>
            <a class="dropdown-item" href="change_password.php">Change Password</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="logout.php"  >Logout</a>
          </div>
        </li>
      </ul>

    </nav>

    <div id="wrapper">

      <!-- Sidebar -->
      <ul class="sidebar navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="users.php">
            <i class="fas fa-users"></i>
            <span>Users</span></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="advertise.php">
            <i class="fas fa-bullhorn"></i>
            <span>Advertiser</span></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="brands.php">
            <i class="fab fa-accusoft"></i>
            <span>Brand</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categories.php">
            <i class="fas fa-fw fa-table"></i>
            <span>Categories</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="brands.php">
            <i class="fas fa-cart-plus"></i>
            <span>Add Product</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="products.php">
          <i class="fas fa-info-circle"></i>
            <span>Product details</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="archived.php">
            <i class="fas fa-archive"></i>
            <span>Archived</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../Genbarcode">
            <i class="fas fa-barcode"></i>
            <span>Generate Barcode</span></a>
        </li>
      </ul>
      <!-- end Sidebar -->

      <div id="content-wrapper">
        <div class="container-fluid">
          <!-- Breadcrumbs-->
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="cart.php">Sell Product</a>
            </li>
            <li class="breadcrumb-item">
              <a href="report.php?day">Sales Report</a>
            </li>
            <li class="breadcrumb-item">
              <a href="credit_cus.php">Credit Customer</a>
            </li>
            <li class="breadcrumb-item">
              <a href="parties.php">Parties</a>
            </li>
            <li class="breadcrumb-item">
              <a href="expenditure.php">Expenditure</a>
            </li>
            <li class="breadcrumb-item">
              <a href="../index.php">My product</a>
            </li>
          </ol>

          <!--==================== Icon Cards=========================-->
          <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-primary o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-money-bill-wave"></i>
                  </div>
                  <div class="mr-5">
                    <?php
                      for($i = 1; $i <= 32; $i++):
                        $dt = DateTime::createFromFormat('!m',$i);
                     ?>
                    <div><?=(date("d") == $i)?(array_key_exists($i,$day_current))?money($day_current[$i]):money(0):''; ?></div>
                    <?php endfor; ?>
                    <div><?='Sales in '.date("l"); ?></div>
                  </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="report.php?day=<?=date("d"); ?>">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-danger o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                  </div>
                  <div class="mr-5">
                    <?php
                      $Out_off_stock = 0;
                      foreach($lowItems as $item):
                       ($item['quantity'] == 0)? $Out_off_stock++:'';
                      endforeach; ?>
                    <?=$Out_off_stock; ?> </br> Out of Stock!
                  </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="products.php">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-success o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                  </div>
                  <div class="mr-5"><?=$order ?></br> New Orders!</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#.php">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-info o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                  </div>
                  <div class="mr-5">
                    <?php
                      for($i = 1; $i <= 12; $i++):
                        $dt = DateTime::createFromFormat('!m',$i);
                     ?>
                    <div><?=(date("m") == $i)?(array_key_exists($i,$current))?money($current[$i]):money(0):''; ?></div>
                    <div><?=(date("m") == $i)?'Sales in '.$dt->format("F"):''; ?></div>
                   <?php endfor; ?>
                  </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="report.php?month=<?=date("m"); ?>">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
          </div>
<!--================================Row 2========================================-->
<?php
//product stock value..
$total_stock = 0;
$parties_balance=0;
$sql = "SELECT * FROM product WHERE deleted = 0 AND user_id = '$user_id' ORDER BY title";
$presults = $db->query($sql);
while($productt = mysqli_fetch_assoc($presults)){
  $sizes = sizesToArray($productt['sizes']);
  foreach($sizes as $size){
    $qty = $size['quantity'];
  }
  if ($productt['buy_price'] == 0) {
    $total_stock = 0;
  }else {
    $total_stock += $qty * $productt['buy_price'];
  }
}

//expenditure....
$total_expend = 0;
$results = $db->query("SELECT * FROM expenditure WHERE deleted = 0 AND user_id = $user_id");
while($exp_res = mysqli_fetch_assoc($results)){
  $amt = $exp_res['exp_amt'];
  $total_expend += $amt;
}
//parties balance...
$all_total = 0;
$all_paid=0;
$parties_balance=0;
$userQuery = $db->query("SELECT * FROM parties WHERE deleted = 0 AND user_id = $user_id ORDER BY party_name");
while($user = mysqli_fetch_assoc($userQuery)){
  $party_id = $user['id'];
  $grand_total = 0;
  $total = 0;
  $product_sql = $db->query("SELECT * FROM parties_product WHERE party_id = '$party_id' AND deleted = 0 AND user_id = $user_id");
  while ($product_result = mysqli_fetch_assoc($product_sql)) {
    $total = $product_result['qty'] * $product_result['cost_price'];
    $grand_total += $total;
  }
  $bill_paid = 0;
  $total_paid = 0;
  $party_bill_sql = $db->query("SELECT * FROM party_bill_no WHERE parties_id = '$party_id' AND deleted = 0");
  while ($party_bill_result = mysqli_fetch_assoc($party_bill_sql)) {
    $bill_paid = $party_bill_result['paid'];
    $total_paid += $bill_paid;
  }
  $total_party_paid = $total_paid;
  $all_total +=$grand_total;
  $all_paid+=$total_party_paid;
  $parties_balance=$all_total-$all_paid;
}
 ?>
          <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-primary o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                  </div>
                  <div class="mr-5">
                    <div><?=money($total_stock);?></div>
                    <div>Stock Value</div>
                  </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="products.php">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-danger o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-american-sign-language-interpreting"></i>
                  </div>
                  <div class="mr-5">
                    <?php $toReceive = toPay($user_id,'customer') ?>
                    <div><?=money($toReceive);?></div>
                    <div>To Receive</div>
                  </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="credit_cus.php">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-success o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                  </div>
                  <div class="mr-5">
                    <?php $toPay = toPay($user_id,'supplier'); ?>
                    <div><?=money($parties_balance + $toPay);?></div>
                    <div>To Pay</div>
                  </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="credit_cus.php">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-info o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                  </div>
                  <div class="mr-5">
                    <div class="mr-5">
                      <div><?=money($total_expend);?></div>
                      <div>Expenditure</div>
                    </div>
                  </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="expenditure.php">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
          </div>
<!--================================End of Row 2========================================-->
<!-- end Breadcrumbs-->
<?php
//include 'template/_order_to_ship.php';
 ?>
        <div class="container-fluid">
          <section>


            <div class="row container-fluid">
              <div class="col-md-4">
                <h3 class="py-2">Sales By Month</h3>
                <table class="table table-condensed table-bordered table-striped">
                  <thead>
                    <th>Months</th>
                    <th><?=$lastYr_2; ?></th>
                    <th><?=$thisYr_2; ?></th>
                  </thead>
                  <tbody>
                    <?php
                      for($i = 1; $i <= 12; $i++):
                        $dt = DateTime::createFromFormat('!m',$i);
                     ?>
                    <tr<?=(date("m") == $i)?' class="info"':''; ?>>
                      <td><?=$dt->format("F"); ?></td>
                      <td><?=(array_key_exists($i,$last))?money($last[$i]):money(0); ?></td>
                      <td><?=(array_key_exists($i,$current))?money($current[$i]):money(0); ?> </td>
                    </tr>
                  <?php endfor; ?>
                    <tr class="success">
                      <td>Total</td>
                      <td><?=money($lastTotal);?></td>
                      <td><?=money($currentTotal);?></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!--low Inventory-->
              <?php
                $iQuery = $db->query("SELECT * FROM product WHERE deleted = 0 AND user_id = $user_id ORDER BY title");
                $lowItems = array();
                while($product = mysqli_fetch_assoc($iQuery)){
                  $item = array();
                  $sizes = sizesToArray($product['sizes']);
                  foreach($sizes as $size){
                    if($size['quantity'] <= $size['threshold']){
                      $cat = get_category($product['categories']);
                      $item = array(
                        'title' => $product['title'],
                        'size'  => $size['size'],
                        'quantity'  => $size['quantity'],
                        'threshold'  => $size['threshold'],
                        'Category'  => $cat['parent'] . ' ~ ' .$cat['child']
                      );
                      $lowItems[] = $item;
                    }
                  }
                }
               ?>

               <div class="col-md-8">
                 <h3 class="py-2">Low Inventory</h3>
                 <table class="table table-condensed table-bordered table-striped">
                   <thead>
                     <th>Product</th>
                     <th>Category</th>
                     <th>Items</th>
                     <th>Quantity</th>
                     <th>Threshold</th>
                   </thead>
                   <tbody>
                     <?php foreach($lowItems as $item): ?>
                     <tr<?=($item['quantity'] == 0)?' class="danger"':''; ?>>
                       <td><?=$item['title']; ?></td>
                       <td><?=$item['Category']; ?></td>
                       <td><?=$item['size']; ?></td>
                       <td><?=$item['quantity']; ?></td>
                       <td><?=$item['threshold']; ?></td>
                     </tr>
                   <?php endforeach; ?>
                   </tbody>
                 </table>
               </div>
            </div>
          </section>
<?php include 'includes/footer.php'; ?>
<!--Sales by Month for bar graph-->
   <?php
      $thisYr = date("Y");
      $lastYr = $thisYr - 1;
      //--from transactions--
      $thisYrQ = $db->query("SELECT * FROM transactions WHERE YEAR(txn_date) = '{$thisYr}' AND customer_id = $user_id");
      $lastYrQ = $db->query("SELECT * FROM transactions WHERE YEAR(txn_date) = '{$lastYr}' AND customer_id = $user_id");
      //--from barcode_transaction--
      $thisYrBQ = $db->query("SELECT * FROM barcode_transaction WHERE YEAR(txn_date) = '{$thisYr}' AND user_id = $user_id");
      $lastYrBQ = $db->query("SELECT * FROM barcode_transaction WHERE YEAR(txn_date) = '{$lastYr}' AND user_id = $user_id");
      $current = array();
      $last = array();
      $currentTotal = 0;
      $lastTotal = 0;
      $jan = 0;
      $feb = 0;
      $mar = 0;
      $apr = 0;
      $may = 0;
      $jun = 0;
      $jul = 0;
      $aug = 0;
      $sep = 0;
      $oct = 0;
      $nov = 0;
      $dec = 0;

      $last_jan = 0;
      $last_feb = 0;
      $last_mar = 0;
      $last_apr = 0;
      $last_may = 0;
      $last_jun = 0;
      $last_jul = 0;
      $last_aug = 0;
      $last_sep = 0;
      $last_oct = 0;
      $last_nov = 0;
      $last_dec = 0;


      //--from barcode_transaction--
      while($x = mysqli_fetch_assoc($thisYrBQ)){
        $month = '1';
        if($month == $x['months']){
           $jan += $x['grand_total'];
         }
        $month = '2';
        if($month == $x['months']){
           $feb += $x['grand_total'];
         }
        $month = '3';
        if($month == $x['months']){
           $mar += $x['grand_total'];
        }
        $month = '4';
        if($month == $x['months']){
           $apr += $x['grand_total'];
        }
        $month = '5';
        if($month == $x['months']){
          $may += $x['grand_total'];
        }
        $month = '6';
        if($month == $x['months']){
          $jun += $x['grand_total'];
        }
        $month = '7';
        if($month == $x['months']){
          $jul += $x['grand_total'];
        }
        $month = '8';
        if($month == $x['months']){
           $aug += $x['grand_total'];
        }
       $month = '9';
       if($month == $x['months']){
           $sep += $x['grand_total'];
        }
       $month = '10';
       if($month == $x['months']){
         $oct += $x['grand_total'];
        }
       $month = '11';
       if($month == $x['months']){
         $nov += $x['grand_total'];
        }
       $month = '12';
       if($month == $x['months']){
         $dec += $x['grand_total'];
       }
      }

       while($y = mysqli_fetch_assoc($lastYrBQ)){
         $month = '1';
         if($month == $y['months']){
            $last_jan += $y['grand_total'];
          }
         $month = '2';
         if($month == $y['months']){
            $last_feb += $y['grand_total'];
          }
         $month = '3';
         if($month == $y['months']){
            $last_mar += $y['grand_total'];
         }
         $month = '4';
         if($month == $y['months']){
            $last_apr += $y['grand_total'];
         }
         $month = '5';
         if($month == $y['months']){
           $last_may += $y['grand_total'];
         }
         $month = '6';
         if($month == $y['months']){
           $last_jun += $y['grand_total'];
         }
         $month = '7';
         if($month == $y['months']){
           $last_jul += $y['grand_total'];
         }
         $month = '8';
         if($month == $y['months']){
            $last_aug += $y['grand_total'];
         }
        $month = '9';
        if($month == $y['months']){
            $last_sep += $y['grand_total'];
         }
        $month = '10';
        if($month == $y['months']){
          $last_oct += $y['grand_total'];
         }
        $month = '11';
        if($month == $y['months']){
          $last_nov += $y['grand_total'];
         }
        $month = '12';
        if($month == $y['months']){
          $last_dec += $y['grand_total'];
        }
      }
      //--from transactions--
      while($x = mysqli_fetch_assoc($thisYrQ)){
        $date = date_create($x['txn_date']);
        $mon = date_format($date, 'm');

        foreach (getPayment($x['payment_details']) as $payment) {}
        $month = '1';
        if($month == $mon){
           $jan += $payment['subtotal'];
         }
        $month = '2';
        if($month == $mon){
           $feb += $payment['subtotal'];
         }
        $month = '3';
        if($month == $mon){
           $mar += $payment['subtotal'];
        }
        $month = '4';
        if($month == $mon){
           $apr += $payment['subtotal'];
        }
        $month = '5';
        if($month == $mon){
          $may += $payment['subtotal'];
        }
        $month = '6';
        if($month == $mon){
          $jun += $payment['subtotal'];
        }
        $month = '7';
        if($month == $mon){
          $jul += $payment['subtotal'];
        }
        $month = '8';
        if($month == $mon){
           $aug += $payment['subtotal'];
        }
       $month = '9';
       if($month == $mon){
           $sep += $payment['subtotal'];
        }
       $month = '10';
       if($month == $mon){
         $oct += $payment['subtotal'];
        }
       $month = '11';
       if($month == $mon){
         $nov += $payment['subtotal'];
        }
       $month = '12';
       if($month == $mon){
         $dec += $payment['subtotal'];
       }
      }
      while($y = mysqli_fetch_assoc($lastYrQ)){
        $month = '1';
        if($month == $y['months']){
           $last_jan += $y['grand_total'];
         }
        $month = '2';
        if($month == $y['months']){
           $last_feb += $y['grand_total'];
         }
        $month = '3';
        if($month == $y['months']){
           $last_mar += $y['grand_total'];
        }
        $month = '4';
        if($month == $y['months']){
           $last_apr += $y['grand_total'];
        }
        $month = '5';
        if($month == $y['months']){
          $last_may += $y['grand_total'];
        }
        $month = '6';
        if($month == $y['months']){
          $last_jun += $y['grand_total'];
        }
        $month = '7';
        if($month == $y['months']){
          $last_jul += $y['grand_total'];
        }
        $month = '8';
        if($month == $y['months']){
           $last_aug += $y['grand_total'];
        }
       $month = '9';
       if($month == $y['months']){
           $last_sep += $y['grand_total'];
        }
       $month = '10';
       if($month == $y['months']){
         $last_oct += $y['grand_total'];
        }
       $month = '11';
       if($month == $y['months']){
         $last_nov += $y['grand_total'];
        }
       $month = '12';
       if($month == $y['months']){
         $last_dec += $y['grand_total'];
       }
      }

    ?>
    <!---Sales by Month for pie chart-------------------------->
      <?php
         $thisYr = date("Y");
         $lastYr = $thisYr - 1;
         //--from transactions--
         $thisYrQ = $db->query("SELECT * FROM transactions WHERE YEAR(txn_date) = '{$thisYr}' AND customer_id = $user_id");
         $lastYrQ = $db->query("SELECT * FROM transactions WHERE YEAR(txn_date) = '{$lastYr}' AND customer_id = $user_id");
         //--from barcode_transaction--
         $thisYrBQ = $db->query("SELECT * FROM barcode_transaction WHERE YEAR(txn_date) = '{$thisYr}' AND user_id = $user_id");
         $lastYrBQ = $db->query("SELECT * FROM barcode_transaction WHERE YEAR(txn_date) = '{$lastYr}' AND user_id = $user_id");

         $current = array();
         $last = array();
         $currentTotal = 0;
         $lastTotal = 0;

         //--from barcode_transaction--
         while($x = mysqli_fetch_assoc($thisYrBQ)){
           $month = $x['months'];
           if(!array_key_exists($month,$current)){
             $current[$month] = $x['grand_total'];
           }else{
             $current[$month] += $x['grand_total'];
           }
           $currentTotal += $x['grand_total'];
         }
         while($y = mysqli_fetch_assoc($lastYrBQ)){
           $month = $y['months'];
           if(!array_key_exists($month,$last)){
             $last[$month] = $y['grand_total'];
           }else{
             $last[$month] += $y['grand_total'];
           }
           $lastTotal += $y['grand_total'];
         }
         //--from transactions--
         while($x = mysqli_fetch_assoc($thisYrQ)){
           $date = date_create($x['txn_date']);
           $month = date_format($date, 'm');

           foreach (getPayment($x['payment_details']) as $payment) {}

           if(!array_key_exists($month,$current)){
             $current[$month] = $payment['subtotal'];
           }else{
             $current[$month] += $payment['subtotal'];
           }
           $currentTotal += $payment['subtotal'];
         }
         while($y = mysqli_fetch_assoc($lastYrQ)){
           $month = $y['months'];
           if(!array_key_exists($month,$last)){
             $last[$month] = $y['grand_total'];
           }else{
             $last[$month] += $y['grand_total'];
           }
           $lastTotal += $y['grand_total'];
         }
       ?>
       <!--This months sales--->
       <?php
         for($i = 1; $i <= 12; $i++):
           $dt = DateTime::createFromFormat('!m',$i);
        ?>
       <?//=(date("m") == $i)?(array_key_exists($i,$current))?$current[$i]:0:''; ?>
       <?php endfor; ?>

<!--===========================barchart===================-->
          <script>
          const CHART = document.getElementById('barChart');
          let barChart = new Chart(CHART, {
              // The type of chart we want to create
              type: 'bar',

              // The data for our dataset
              data: {
                  labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
                  datasets: [
                  {
                    label: "Sales By Last Year",
                    borderWidth: 1,
                    backgroundColor: 'rgba(0, 255, 255, 0.1)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    data: [<?=$last_jan ?>, <?=$last_feb ?>, <?=$last_mar ?>, <?=$last_apr ?>, <?=$last_may ?>, <?=$last_jun ?>, <?=$last_jul ?>,<?=$last_aug ?>,<?=$last_sep ?>,<?=$last_oct ?>,<?=$last_nov ?>,<?=$last_dec ?>],
                  },
                  {
                    label: "Sales By This Year",
                    borderWidth: 1,
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    data: [<?=$jan ?>, <?=$feb ?>, <?=$mar ?>, <?=$apr ?>, <?=$may ?>, <?=$jun ?>, <?=$jul ?>,<?=$aug ?>,<?=$sep ?>,<?=$oct ?>,<?=$nov ?>,<?=$dec ?>],
                  }
                ]
              }
          });

          const PCHART = document.getElementById('pieChart');
          let pieChart = new Chart(PCHART, {
              // The type of chart we want to creat
              type: 'doughnut',

              // The data for our dataset
              data: {
                  labels: ["Today sales","Out of Stock","New Orders","This months sales","stock value","Credit Pending","Expenditure","Party Balance"],
                  datasets: [
                  {
                    label: "Dashboard",
                    borderWidth: 0,
                    backgroundColor: ['#007bff','#dc3545','#28a745','#17a2b8','green','yellow','red','orange'],
                    borderColor: 'rgba(255, 99, 132, 1)',
                    data: [
                      //--Today sales
                      <?php
                        for($i = 1; $i <= 32; $i++):
                          $dt = DateTime::createFromFormat('!m',$i);
                       ?>
                      <?=(date("d") == $i)?(array_key_exists($i,$day_current))?$day_current[$i]:0:''; ?>
                      <?php endfor; ?>,

                      //--out of stock--
                      <?=$Out_off_stock; ?>,

                      //--new orders---
                      <?php
                      $count_order = $db->query("SELECT * FROM transactions WHERE delivered = 0 AND customer_id = $user_id");
                      $order = mysqli_num_rows($count_order);
                      ?>
                      <?=$order ?>,

                      //--This months sales--->
                      <?php
                        for($i = 1; $i <= 12; $i++):
                          $dt = DateTime::createFromFormat('!m',$i);
                       ?>
                      <?=(date("m") == $i)?(array_key_exists($i,$current))?$current[$i]:0:''; ?>
                      <?php endfor; ?>,

                      //stock value...
                      <?=$total_stock;?>,

                      //credit pending...
                      <?=$all_balance;?>,

                      //expenditure
                      <?=$total_expend;?>,

                      //party balance..
                      <?=$parties_balance;?>
                    ],
                  }
                ]
              }
          });
          </script>
    <!--===========================end of barchart===================-->
        </div>
        </div>
        <!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright © <?=SHOPNAME; ?> 2018</span>
            </div>
          </div>
        </footer>

      </div>
      <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core sb-admin-->
        <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
        <script src="../vendor/bootstrap2/js/bootstrap.bundle.min.js"></script>
    <!-- Custom scripts for all pages-->
        <script src="../js/sb-admin.min.js"></script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5c430428ab5284048d0da3e6/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
