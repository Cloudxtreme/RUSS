<?php
    require_once("../admin/include/constants.php");
    require_once("../admin/versioncontrol.php");
    $mysqlImportFilename ='db.sql';
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
            <li><a href="http://redicon.eu" ><span class="glyphicon glyphicon-info-sign"></span> About</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="container" id="installer">

<div class="row"><p class="hilite">Installation: <strong>Finished</strong></p></div>

    <div class="row">
        <div class="col-md-4">
              <center><img src="../admin/img/redicon-logo.png" alt="" /></center>
        </div>
        <div class="col-md-8">

         <?php

				//Export the database and output the status to the page
                  $command='mysql -h' .DB_SERVER.' -u' .DB_USER.' -p' .DB_PASS.' ' .DB_NAME.' < ' .$mysqlImportFilename;
                  $output=array();
                  exec($command,$output,$worked);
                  switch($worked){
                      case 0:
                          echo "<div class='alert alert-success animated bounceInRight' role='alert'><span class='glyphicon glyphicon-ok'></span> <strong>".$mysqlImportFilename." successfully imported to database ".DB_NAME." ...</strong> Installation completed!<br /><br />Please now REGISTER with the username: <strong>admin (must be that username, you can change it later!)</strong> and a password of your choice.<br /><br />After you register with the admin account, first thing you need to do is to visit the administration panel and fill out the configuration fields with your website details. <br /><br />
										<strong>For security reasons, please remove the INSTALL folder from your server.</strong></div><br /><center><a href='../registration.php' class='btn btn-lg btn-warning pull-right'>REGISTER</a></center>";
                          break;
                      case 1:
                          echo "<div class='alert alert-danger animated bounceInRight' role='alert'><span class='glyphicon glyphicon-remove'></span> <strong>Instalation aborted:</strong> Error while trying to setup database. Why?<br /><br /><ul><li>Database already exist (trying to install once again?)</li><li>Database does not exist (did you created one?)</li><li>Database or tables cannot be found (Database details in constants.php file are wrong)</li></ul><br />Please open /admin/include/constants.php file and see if details written below /* END STATIC */ are O.K. If you do not know what is going on, please <a href='http://redicon.eu/?smd_process_download=1&download_id=303' target='_blank'>click here</a> to see steps for manual setup.</div><div class='pull-right'><a href='../registration.php' class='btn btn-lg btn-default'>TRY TO REGISTER ANYWAY</a></div>";

                          break;
                  }  ?>
        </div>
    </div>
</div>
<?php
        rename ("../install", "../PLEASE_DELETE_THIS_FOLDER");
        include ('../admin/footer.inc.php');
        ?>
</body>