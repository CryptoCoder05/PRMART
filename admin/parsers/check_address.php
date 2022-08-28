<?php
 require_once $_SERVER['DOCUMENT_ROOT'].'/prmart/core/init.php';
 $name = sanitize($_POST['full_name']);
 $email = sanitize($_POST['email']);
 $street = sanitize($_POST['ph_no']);
 $street = sanitize($_POST['street']);
 $street2= sanitize($_POST['street2']);
 $city = sanitize($_POST['city']);
 $state = sanitize($_POST['state']);
 $zip_code = sanitize($_POST['zip_code']);
 $country = sanitize($_POST['country']);
 $errors = array();
 $required =array(
    'full_name' => 'Full name',
    'email'     => 'Email',
    'ph_no'     => 'Phone',
    'street'    => 'Street Address',
    'city'      => 'City',
    'zip_code'  => 'Pin code',
    'country'   => 'Country',
 );

// Check if all required fields are filled out.
foreach($required as $f => $d){
  if(empty($_POST[$f]) || $_POST[$f] == ''){
    $errors[] = $d.' is required!';
  }
}
// check if valid email Address
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
  $errors[] = 'Please enter a valid email';
}

if(!empty($errors)){
  echo display_errors($errors);
}else{
  echo 'passed';
}
 ?>
