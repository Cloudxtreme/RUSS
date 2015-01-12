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

<div class="row"><p class="hilite">Installation: <strong>Install Details</strong></p></div>

    <div class="row">
        <div class="col-md-4">
              <center><img src="../admin/img/redicon-logo.png" alt="" /></center>
        </div>
        <div class="col-md-8">
				<form class="form-horizontal" role="form" action="process.php" method="POST">

                 <div class="form-group">
                  <label for="server" class="col-md-4 control-label">DB Server</label>
                  <div class="col-md-8">
                    <input type="text" name="server" id="server" maxlength="30" class="form-control" placeholder="localhost" required />
                  </div>
                </div>

                 <div class="form-group">
                  <label for="username" class="col-md-4 control-label">DB Username</label>
                  <div class="col-md-8">
                    <input type="text" name="username" id="username" maxlength="30" class="form-control" required />
                  </div>
                </div>

                <div class="form-group">
                  <label for="password" class="col-md-4 control-label">DB Password</label>
                  <div class="col-md-8">
                    <input type="text" name="password" id="password" maxlength="30" class="form-control" required />
                  </div>
                </div>

                <div class="form-group">
                  <label for="name" class="col-md-4 control-label">DB Name</label>
                  <div class="col-md-8">
                    <input type="text" name="name" id="name" maxlength="30" class="form-control" required />
                  </div>
                </div>

                <hr />

                <div class="form-group">
                  <label for="webmail" class="col-md-4 control-label">Website E-Mail address</label>
                  <div class="col-md-8">
                    <input type="text" name="webmail" id="webmail" maxlength="30" class="form-control" required />
                  </div>
                </div>

                <input type="hidden" name="url" id="url" value="<?php echo RURL;?>" />

                <input class="btn btn-lg btn-warning pull-right" type="submit" value="Install"></input>

                </form>

       </div>
    </div>


</div>
<?php include ('../admin/footer.inc.php');?>
</body>