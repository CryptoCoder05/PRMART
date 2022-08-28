<nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="index.php" class="navbar-brand font-weight-bold" style="letter-spacing:3px;"><span style="color:white;"><?=SHOPNAME; ?></span></a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li ><a href="index.php">My Dashboard</a></li>
        <li><a href="brands.php" >Brand</a></li>
        <li><a href="categories.php" >Categories</a></li>
        <li><a href="products.php" >Product</a></li>
        <li><a href="archived.php" >Archived</a></li>
        <?php if(has_permission('admin')): ?>
         <li><a href="users.php">Users</a></li>
        <?php endif; ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data['first']; ?>!<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="change_password.php">Change Password</a></li>
            <li><a href="logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php $errors = array(); ?>
