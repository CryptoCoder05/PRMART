<?php ob_start(); ?>
<?php
function display_errors($errors){
  $display = '<ul class="bg-danger">';
  foreach($errors as $error){
    $display .='<li class="text-danger">'.$error.'</li>';
  }
  $display .= '</ul>';
  return $display;
}

function sanitize($dirty){
  return htmlentities($dirty,ENT_QUOTES,"UTF-8");
}

function money($number){
  return 'Rs. '.number_format($number,2);
}

function login($user_id){
  $_SESSION['SBUser'] = $user_id;
  global $db;
  $date = date("Y-m-d H:i:s");
  $db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
  //$_SESSION['success_flash'] = 'You are now logged in!';
  header('Location: index.php');
}

function login_cus_side($user_id){
  $_SESSION['SBUser'] = $user_id;
  global $db;
  $date = date("Y-m-d H:i:s");
  $db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
  //$_SESSION['success_flash'] = 'You are now logged in!';
  header('Location: ../index.php');
}

function is_logged_in(){
  if(isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0){
    return true;
  }
  return false;
}

function login_error_redirect($url = 'login.php'){
  $_SESSION['error_flash'] = 'You must be logged in to access that page!';
  header('Location:'.$url);
}

function permisssion_error_redirect($url){
  $_SESSION['error_flash'] = 'You do not have permission to access this page!';
  header('Location:'.$url);
}

function permisssion_error_redirect_login($url){
  $_SESSION['error_flash_login'] = 'You do not have permission to access this page!';
  header('Location:'.$url);
}

function permisssion_redirect($url){
  header('Location:'.$url);
}

function has_permission($permission){
  global $user_data;
  $permissions = explode(',', $user_data['permissions']);
  if(in_array($permission,$permissions,true)){
    return true;
  }
  return false;
}

function pretty_date($date){
  return date("M d, Y h:i A",strtotime($date));
}

function get_category($child_id){
  global $db;
  $id = sanitize($child_id);
  $sql = "SELECT p.id AS 'pid', p.categories AS 'parent', c.id AS 'cid', c.categories AS 'child'
          FROM categories c
          INNER JOIN categories p
          ON c.parent = p.id
          WHERE c.id = '$id'";
  $query = $db->query($sql);
  $category = mysqli_fetch_assoc($query);
  return $category;
}

function sizesToArray($string){
  $sizesArray = explode(',',$string);
  $returnArray = array();
  foreach($sizesArray as $size){
    $s = explode(':',$size);
    $returnArray[] = array('size' => $s[0], 'quantity' => $s[1],'threshold' => $s[2]);
  }
  return $returnArray;
}

function sizesToString($sizes){
  $sizeString = '';
  foreach($sizes as $size){
    $sizeString .=$size['size'].':'.$size['quantity'].':'.$size['threshold'].',';
  }
  $trimmed = rtrim($sizeString, ',');
  return $trimmed;
}

// get undispatched data from transactions table...
function getOrder(){
  global $db;
  $query = "SELECT * FROM transactions WHERE delivered = 0 ORDER BY txn_date";
  $run = $db->query($query);
  $resultArray = array();
  while ($result = mysqli_fetch_assoc($run)) {
    $resultArray[] = $result;
  }
  return $resultArray;
}

// get address detail from transaction table...
function getAdd($add){
  if (isset($add)) {
    $addDetail[] = json_decode($add,true);
    return $addDetail;
  }
}

// get payment detail from transaction table...
function getPayment($payment){
  if (isset($payment)) {
    $payDetail[] = json_decode($payment,true);
    return $payDetail;
  }
}

function getProd($prod){
  if (isset($prod)) {
    $prodDetail = json_decode($prod,true);
    return $prodDetail;
  }
}

// for order page...
function getOrderDet($txn_id){
  global $db;
  $query = "SELECT * FROM transactions WHERE id = '$txn_id'";
  $run = $db->query($query);
  $result = mysqli_fetch_assoc($run);
  return $result;
}

// get product details from product table...
function getProduct($id){
  if (isset($id)) {
    global $db;
    $query = "SELECT * FROM product WHERE id = '$id'";
    $run = $db->query($query);
    $resultArray = array();
    while ($result = mysqli_fetch_assoc($run)) {
      $resultArray[] = $result;
    }
    return $resultArray;
  }
}

// for shop details from user table...
function getShop($id){
  global $db;
  $query = "SELECT * FROM users WHERE id = '$id'";
  $run = $db->query($query);
  $result[] = mysqli_fetch_assoc($run);
  return $result;
}

// update txn table...
function updateTxn($col,$val,$cond,$val2,$table = 'transactions'){
  if (isset($val2)) {
    global $db;
    $res = $db->query("UPDATE $table SET $col = '$val' WHERE  $cond = '$val2'");
    if ($res === true) {
      header('Location: index.php');
    }else {
      print 'Something Wrong!';
    }
  }
}

function redirect($url){
  header('Location:'.$url);
}

// get data from credit cus statement
function creditCusStm($table,$bill_no,$user_id){
  global $db;
  $query = "SELECT * FROM $table WHERE bill_no = '$bill_no' AND deleted = 0 AND user_id = '$user_id' ORDER BY txn_date";
  $run = $db->query($query);
  $resultArray = array();
  while ($result = mysqli_fetch_assoc($run)) {
    $resultArray[] = $result;
  }
  return $resultArray;
}

// get data from credit cus statement
function creditCus($table,$bill_no,$user_id){
  global $db;
  $query = "SELECT * FROM $table WHERE bill_no = '$bill_no' AND user_id = '$user_id' AND deleted = 0";
  $run = $db->query($query);
  $resultArray = array();
  while ($result = mysqli_fetch_assoc($run)) {
    $resultArray[] = $result;
  }
  return $resultArray;
}

// get balance details from credit customer statement
function getBalance($bill_no,$user_id){
  $due = 0;
  $total_credit = 0;
  $total_deposit = 0;
  $resultArray = array();
  foreach (creditCusStm('credit_cus_statement',$bill_no,$user_id) as $credit_cus_statement):
    $credit = $credit_cus_statement['credit_amt'];
    $deposit = $credit_cus_statement['deposit_amt'];
    $total_credit += $credit;
    $total_deposit += $deposit;
  endforeach;
  $due = $total_credit - $total_deposit;
  $resultArray[] = array(
    'credit_given' => $total_credit,
    'payment_received' => $total_deposit,
    'balance_due' => $due
  );
  return $resultArray;
}

// get total supplier balance
function toPay($user_id,$type){
  global $db;
  $credit_amt = 0;
  $deposit_amt = 0;
  $toPay = 0;
  $query_cc = "SELECT * FROM credit_customer WHERE deleted = 0 AND user_id = $user_id AND type = '$type'";
  $run1 = $db->query($query_cc);
  while ($row1 = mysqli_fetch_assoc($run1)) {
    $bill_no = $row1['bill_no'];
    $query_cc_det = "SELECT * FROM credit_cus_statement WHERE deleted = 0 AND user_id = $user_id AND bill_no = '$bill_no'";
    $run2 = $db->query($query_cc_det);
    while ($row2 = mysqli_fetch_assoc($run2)) {
      $credit = $row2['credit_amt'];
      $deposit = $row2['deposit_amt'];
      $credit_amt += $credit;
      $deposit_amt += $deposit;
    }
  }
  $toPay = $credit_amt - $deposit_amt;
  return $toPay;
}
