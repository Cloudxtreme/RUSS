<?php
if (!defined('VERSIONCT')) {
    die;
}?>
<?php
if(!$session->isAdmin()){
   die;
}
$ctm = $database->showReported(2);
$cmy = $database->showMessageTM('all');
global $database;
$req_user_info = $database->getUserInfo($session->username);
        $cmt_status = 2;
        $cmt = $database->adminComment($cmt_status);
$orderby = 'regdate';
$result = countAdminActivation($orderby);
function countFolder($dir) {
    return (count(scandir($dir)) - 2);
}
?>



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
      <a class="navbar-brand redicon-logo" href="../<?php echo $config['home_page'];?>">RED<span>ICON</span></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
      <ul class="nav navbar-nav">
        <li <?php if ((isset($_GET['id'])) && ($_GET['id'] == 1)) { echo "class='active'"; } ?>><a href="index.php?id=1" ><span class="glyphicon glyphicon-dashboard"></span></a></li>

    <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-time"></span><?php echo SHORTNAME; ?> <b class="caret"></b></a>
          <ul class="dropdown-menu flipInY animated" role="menu">
          <li><a href="index.php?id=15"><?php echo $lang['PRODUCTS']; ?></a></li>
          <li><a href="index.php?id=13"><?php echo $lang['CATEGORIES']; ?></a></li>
          <li><a href="index.php?id=12"><?php echo $lang['ARTICLES']; ?></a></li>
          <li><a href="../ticket.php" target="_blank"><?php echo $lang['TICKETS']; ?></a></li>

          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span><?php echo $lang['SETTINGS']; ?> <b class="caret"></b></a>
          <ul class="dropdown-menu flipInY animated" role="menu">

        <li <?php if ((isset($_GET['id'])) && ($_GET['id'] == 2)) { echo "class='active'"; } ?>><a href="index.php?id=2" ><span class="glyphicon glyphicon-cog"></span> <?php echo $lang['GENERAL']; ?> <?php echo $lang['SETTINGS']; ?></a></li>

        <li <?php if ((isset($_GET['id'])) && ($_GET['id'] == 11)) { echo "class='active'"; } ?>><a href="index.php?id=11" ><span class="glyphicon glyphicon-lock"></span> <?php echo $lang['SECURITY']; ?></a></li>


        <li><a href="index.php?id=1&cc=1" ><span class="glyphicon glyphicon-trash"></span> <?php echo $lang['CLEAR']; ?> <?php echo $lang['CACHE']; ?> <?php if(countFolder('cache/') > 3) { ?>&nbsp;&nbsp;<span class="badge zoomIn animated"><?php echo countFolder('cache/');?></span><?php }?></a></li>

        <li <?php if ((isset($_GET['id'])) && ($_GET['id'] == 7)) { echo "class='active'"; } ?>><a href="index.php?id=7" ><span class="glyphicon glyphicon-folder-close"></span> <?php echo $lang['BACKUP']; ?> <?php if(countFolder('backup/') > 0) { ?>&nbsp;&nbsp;<span class="badge zoomIn animated"><?php echo countFolder('backup/');?></span><?php }?></a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span><?php echo $lang['USERS']; ?> <b class="caret"></b></a>
          <ul class="dropdown-menu flipInY animated" role="menu">

        <li <?php if ((isset($_GET['id'])) && ($_GET['id'] == 3)) { echo "class='active'"; } ?>><a href="index.php?id=3" ><span class="glyphicon glyphicon-user"></span> <?php echo $lang['USERS']; ?> <?php if($_SESSION['acc_count'] > 0) { ?>&nbsp;&nbsp;<span class="badge zoomIn animated "><?php echo $_SESSION['acc_count'];?></span><?php }?></a></li>

        <li <?php if ((isset($_GET['id'])) && ($_GET['id'] == 10)) { echo "class='active'"; } ?>><a href="index.php?id=10" ><span class="glyphicon glyphicon-retweet"></span> <?php echo $lang['COMMENT_TITLE']; ?>&nbsp;&nbsp;<?php if($_SESSION['cmt_count'] > 0) { ?><span class="badge zoomIn animated"><?php echo $_SESSION['cmt_count'];?></span><?php } ?></a></li>

        <li <?php if ((isset($_GET['id'])) && ($_GET['id'] == 8)) { echo "class='active'"; } ?>><a href="index.php?id=8" ><span class="glyphicon glyphicon-envelope"></span> <?php echo $lang['MSG_TITLE']; ?>&nbsp;&nbsp;<?php if($_SESSION['rep_count'] > 0) { ?><span class="badge zoomIn animated"><?php echo $_SESSION['rep_count'];?></span><?php } ?></a></li>

          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['INFO']; ?> <b class="caret"></b></a>
          <ul class="dropdown-menu flipInY animated" role="menu">

        <li <?php if ((isset($_GET['id'])) && ($_GET['id'] == 9)) { echo "class='active'"; } ?>><a href="index.php?id=9" ><span class="glyphicon glyphicon-signal"></span> <?php echo $lang['STATS']; ?></a></li>
            <li class="divider"></li>
          <li><a href="http://support.redicon.eu" target="_blank"><span class="glyphicon glyphicon-question-sign"></span> <?php echo $lang['HELP']; ?></a></li>
            <li><a href="http://redicon.eu" target="_blank"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['ABOUT']; ?></a></li>

          </ul>
        </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-globe"></span><?php echo $lang['LANGUAGE']; ?> <b class="caret"></b></a>
                     <ul class="dropdown-menu flipInY animated" role="menu">

                <?php
                  if ($handle = opendir('include/lang')) {
                      while (false !== ($entry = readdir($handle))) {
                          if ($entry != "." && $entry != "..") {
                            $entry = substr($entry, 0, -4);
                             ?>
                            <li <?php if ((isset($langid)) && ($langid == $entry)) { echo "class='active'"; } ?>><a href="index.php?lang=<?php echo $entry;?>"><?php echo $entry;?></a></li>
                        <?php  }
                      }
                      closedir($handle);
                  }
                  ?>
                  </ul>
                </li>


      </ul>


    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>