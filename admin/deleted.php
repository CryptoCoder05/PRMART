<?php
require_once '../core/init.php';
//delete item from cart.php
if(isset($_POST['delete_cart_id']) && !empty($_POST['delete_cart_id'])){
  $delete_id = (int)$_POST['delete_cart_id'];
  $delete_id = sanitize($delete_id);
  $db->query("DELETE FROM barcode_cart WHERE id = '$delete_id'");
}

// Delete an credit customer from credit_cus.php.
if(isset($_POST['delete_credit_cus_id'])){
  $delete_id = sanitize($_POST['delete_credit_cus_id']);
  $db->query("UPDATE `credit_customer` SET `deleted`= 1 WHERE id = '$delete_id'");

  $credit_cusQ = $db->query("SELECT * FROM credit_customer WHERE id = '$delete_id' AND user_id = $user_id");
  $credit_cusRes = mysqli_fetch_assoc($credit_cusQ);
  $delete_bill_no = $credit_cusRes['bill_no'];
  $db->query("UPDATE `credit_cus_statement` SET `deleted`= 1 WHERE bill_no = '$delete_bill_no'");
}

// Delete an parties from parties.php
if(isset($_POST['delete_parties_id'])){
  $delete_id = sanitize($_POST['delete_parties_id']);
  $db->query("UPDATE parties SET deleted = 1 WHERE id = '$delete_id'");

  $partiesQ = $db->query("SELECT * FROM parties WHERE deleted = 1 AND user_id = $user_id");
  $partiesRes = mysqli_fetch_assoc($partiesQ);
  $parties_id = $partiesRes['id'];
  $db->query("UPDATE `party_bill_no` SET `deleted`= 1 WHERE parties_id = '$parties_id'");
  $db->query("UPDATE `parties_payment` SET `deleted`= 1 WHERE party_id = '$parties_id'");
  $db->query("UPDATE `parties_product` SET `deleted`= 1 WHERE party_id = '$parties_id'");
}

// Delete bill from add-bill_no.php
if(isset($_POST['delete_bill_id'])){
  $delete_id = sanitize($_POST['delete_bill_id']);
  $db->query("UPDATE party_bill_no SET deleted = 1 WHERE id = '$delete_id'");
}

//----Delete products from add_parties_product.php----
if(isset($_POST['delete_party_product_id'])){
  $id = sanitize($_POST['delete_party_product_id']);
  $db->query("UPDATE parties_product SET deleted = 1 WHERE id = '$id' ");
}

//delete expenditure from expenditure.php
if(isset($_POST['delete_expenditure_id']) && !empty($_POST['delete_expenditure_id'])){
  $delete_id = (int)$_POST['delete_expenditure_id'];
  $delete_id = sanitize($delete_id);
  $db->query("UPDATE `expenditure` SET `deleted`= 1 WHERE id = '$delete_id'");
}

//----Delete products from products.php----
if(isset($_POST['delete_product_id'])){
  $id = sanitize($_POST['delete_product_id']);
  $db->query("UPDATE product SET deleted = 1, featured = 0 WHERE id = '$id' ");
}

//delete brands from brands.php
if(isset($_POST['delete_brands_id']) && !empty($_POST['delete_brands_id'])){
  $delete_id = (int)$_POST['delete_brands_id'];
  $delete_id = sanitize($delete_id);
  $sql = "DELETE FROM brand WHERE id = '$delete_id'";
  $db->query($sql);
}

//--delete category from categories.phpdelete_categories_id---
if(isset($_POST['delete_categories_id']) && !empty($_POST['delete_categories_id'])){
  $delete_id = (int)$_POST['delete_categories_id'];
  $delete_id = sanitize($delete_id);
  $sql = "SELECT * FROM categories WHERE id = '$delete_id' AND user_id = $user_id";
  $result = $db->query($sql);
  $category = mysqli_fetch_assoc($result);
  if($category['parent'] == 0){
    $sql = "DELETE FROM categories WHERE parent = '$delete_id'";
    $db->query($sql);
  }
  $dsql = "DELETE FROM categories WHERE id = '$delete_id'";
  $db->query($dsql);
}

// Delete an user from users.php.
if(isset($_POST['delete_user_id'])){
  $delete_id = sanitize($_POST['delete_user_id']);
  $db->query("UPDATE users SET deleted = 1 WHERE id = '$delete_id' ");
}
 ?>
