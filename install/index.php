<?php
    require_once("../admin/include/constants.php");
    require_once("../admin/versioncontrol.php");
    include ('../admin/header.inc.php');
?>

<body>
<nav class="navbar navbar-default navbar-fixed-top adminmenu" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="http://redicon.eu" class="navbar-brand redicon-logo" >RED<span>ICON</span></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php" ><?php echo SCRIPTNAME." v".$version." installer";?></a></li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Help <b class="caret"></b></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="http://redicon.eu/support" target="_blank"><span class="glyphicon glyphicon-question-sign"></span> Help</a></li>
            <li><a href="http://redicon.eu" target="_blank" ><span class="glyphicon glyphicon-info-sign"></span> About</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container" id="installer">

<div class="row"><p class="hilite">Installation: <strong>Checking Prerequisites</strong></p></div>

<?php

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $iswin = 1;
} else {
      // CHMODING
                  chmod("./db.sql", 0777);
                  chmod("../admin/include/constants.php", 0777);
                  chmod("../admin/cache", 0755);
                  chmod("../admin/backup", 0755);
}


?>

    <div class="row">
        <div class="col-md-4">
              <center><img src="../admin/img/redicon-logo.png" alt="" /></center>
        </div>
        <div class="col-md-8">
				<?php
				if ((phpversion()) < 5.1) {
				    $cpi = 1;
					echo "<div class='alert alert-danger animated bounceInRight' role='alert'><span class='glyphicon glyphicon-remove'></span> <strong>PHP version: ".phpversion()."</strong> - The script will not work correctly unless you update your PHP version!</div>";

				} else {
					echo "<div class='alert alert-success animated bounceInRight' role='alert'><span class='glyphicon glyphicon-ok'></span> <strong>PHP version: ".phpversion()."</strong></div>";

				    } ?>

                  <?php
                  if ( extension_loaded('pdo') ) {

                    echo "<div class='alert alert-success animated bounceInRight' role='alert'><span class='glyphicon glyphicon-ok'></span> <strong>PDO Enabled: YES</strong></div>";
                    } else {
                    $cpi = 1;
                      echo "<div class='alert alert-danger animated bounceInRight' role='alert'><span class='glyphicon glyphicon-remove'></span> <strong>PDO Enabled: NO</strong> - The script will not work. Please contact your host to enable PDO.</div>";
                  } ?>

                  <?php
                  if(function_exists('exec')) {

                    echo "<div class='alert alert-success animated bounceInRight' role='alert'><span class='glyphicon glyphicon-ok'></span> <strong>EXEC is Allowed: YES</strong></div>";
                    } else {
                    $cpi = 1;
                      echo "<div class='alert alert-danger animated bounceInRight' role='alert'><span class='glyphicon glyphicon-remove'></span> <strong>EXEC is  Allowed: NO</strong> - The INSTALL will not work. Please <a href='../_Support_Files-Do_Not_Upload/russ_manual_install.zip' target='_blank'>click here</a> to see steps for manual setup.</div>";
                  } ?>

                  <?php

                  if ($iswin !=1) {

                  if ( (substr(sprintf('%o', fileperms('./db.sql')), -4)) == "0777" ) {

                    echo "<div class='alert alert-success animated bounceInRight' role='alert'><span class='glyphicon glyphicon-ok'></span> <strong>DB file writeable: YES</strong></div>";
                    } else {
                    $cpi = 1;
                      echo "<div class='alert alert-danger animated bounceInRight' role='alert'><span class='glyphicon glyphicon-remove'></span> <strong>DB file writeable: NO - ".substr(sprintf('%o', fileperms('./db.sql')), -4)." - Should be 0777</strong> - The script will not work. Please login with your FTP client and set file permission to 0777 for db.sql.</div>";
                  }

                  if ( (substr(sprintf('%o', fileperms('../admin/include/constants.php')), -4)) == "0777" ) {

                    echo "<div class='alert alert-success animated bounceInRight' role='alert'><span class='glyphicon glyphicon-ok'></span> <strong>Constants file writeable: YES</strong></div>";
                    } else {
                    $cpi = 1;
                      echo "<div class='alert alert-danger animated bounceInRight' role='alert'><span class='glyphicon glyphicon-remove'></span> <strong>Constants file writeable: NO - ".substr(sprintf('%o', fileperms('./db.sql')), -4)." - Should be 0777</strong> - The script will not work. Please login with your FTP client and set file permission to 0777 for admin/include/constants.php.</div>";
                  }

                }
                else {
                     echo "<div class='alert alert-success animated bounceInRight' role='alert'><span class='glyphicon glyphicon-ok'></span> <strong>You are on Windows Server.</strong> Please ensure that /cache and /backup folders are writeable.</div>";
                }

                ?>

                          <?php if(!(isset($cpi))) { ?>
                              <center><a href="write_file.php" class="btn btn-lg btn-warning pull-right">Next >>></a></center>
                          <?php } ?>

       </div>
    </div>


</div>
<?php include ('../admin/footer.inc.php');?>
</body>