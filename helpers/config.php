<?php ob_start(); ?>
<?php
define('BASEURL',$_SERVER['DOCUMENT_ROOT'].'/prmart/');

define('CART_COOKIE','SBwi72UKlwiqzzZ');
define('CART_COOKIE_EXPIRE',time() + (86400 *30));
define('TAXRATE', 0);

define('CURRENCY','usd');
define('CHECKOUTMODE','TEST'); //Change TEST to LIVE when you are ready to go LIVE.

if(CHECKOUTMODE == 'TEST'){
  define('STRIPE_PRIVATE','sk_test_Brhhzbm6ZH7vnLAmKARAuCSM');
  define('STRIPE_PUBLIC','pk_test_NZjfu2gz65FPoXlHG04hW6LH');
}

if(CHECKOUTMODE == 'LIVE'){
  define('STRIPE_PRIVATE','');
  define('STRIPE_PUBLIC','');
}

 ?>
