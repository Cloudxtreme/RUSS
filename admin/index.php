<?php
include("include/session.php");
include ('adminfunctions.php');
$config = $database->getConfigs();
if(!$session->isAdmin()){
   header("Location: ".$config['WEB_ROOT'].$config['home_page']);
}
  require_once ('include/language.php');
  include ('header.inc.php');
  include ('cache.php');
  include ('warning.inc.php');

  include ('mainmenu.inc.php');
  ?>
  <div class="container-fluid">
  
      <div class="row">
        <div class="col-md-3">
        <?php include ('sidebar.inc.php'); ?>
      </div>
      <div class="col-md-9">
      <?php
          if (((isset($_GET['id'])) &&($_GET['id'] == 1)) || (!isset($_GET['id']))) { include('admin_home.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 2)) { include('configs.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 3)) { include('userconfig.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 4)) { include('help_support.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 5)) { include('aboutus.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 6)) { include('adminuseredit.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 7)) { include('backup.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 8)) { include('messages.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 9)) { include('stats.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 10)) { include('comments.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 11)) { include('security.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 12)) { include('kdb.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 13)) { include('category.php'); };
          if ((isset($_GET['id'])) && ($_GET['id'] == 15)) { include('products.php'); };
      ?>
      </div>

    </div>
  </div>
<?php


include('footer.inc.php');
include('modal.inc.php');
?>