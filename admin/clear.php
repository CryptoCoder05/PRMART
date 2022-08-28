<?php
require_once '../core/init.php';
$db->query("DELETE FROM `add_pro_bar` WHERE user_id = $user_id");
header('Location: products.php');
