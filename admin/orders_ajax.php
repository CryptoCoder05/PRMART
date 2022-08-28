<?php
require_once '../core/init.php';
// get data by ajax from orders.php
if (isset($_POST['seller_id'])) {
  $seller_id = $_POST['seller_id'];
  $output = '';
  $query = "SELECT * FROM users WHERE id = '$seller_id'";
  $run = $db->query($query);
  $output .='
  <table class="table table-bordered">';
  while ($row = mysqli_fetch_assoc($run)) {
    $output .='
    <tr>
      <th>Shop Name</th>
      <td>'.$row['shop_name'].'</td>
    </tr>
    <tr>
      <th>Seller Name</th>
      <td>'.$row['full_name'].'</td>
    </tr>
    <tr>
      <th>Phone No.</th>
      <td>'.$row['phone_no'].'</td>
    </tr>
    <tr>
      <th>Address</th>
      <td>'.$row['addr'].'</td>
    </tr>
    ';
  }
  $output .= '</table>';
  echo $output;
}

 ?>
