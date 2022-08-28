<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/prmart/core/init.php';
$domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);

$edit_id = ((isset($_POST['edit_id']) && $_POST['edit_id'] != '')?sanitize($_POST['edit_id']):'');
$edit_discount = ((isset($_POST['edit_dis']) && $_POST['edit_dis'] != '')?sanitize($_POST['edit_dis']):'');

$db->query("UPDATE `barcode_cart` SET `discount`='$edit_discount' WHERE id='$edit_id'");
?>
