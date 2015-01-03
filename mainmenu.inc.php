<?php
if (!defined('VERSIONCT')) {
    die;
}?>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $config['home_page'];?>"> <?php echo $config['SITE_NAME']; ?> </a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">



            </ul>

        <ul class="nav navbar-nav navbar-right">

        <li class="alert-warning"><a href="kdb.php"><span class="glyphicon glyphicon glyphicon-info-sign"></span> <?php echo $lang['KB']; ?></a></li>

        <?php if($session->logged_in){ ?>


        <li class="alert-info"><a href="ticket.php"><span class="glyphicon glyphicon glyphicon-ok-sign"></span> <?php echo $lang['YOUR_TICKET']; ?></a></li>
        <li class="alert-success"><a href="index.php"><span class="glyphicon glyphicon glyphicon-plus-sign"></span> <?php echo $lang['OPEN_TICKET']; ?></a></li>
<li class="dropdown">
                <a href="#" class="dropdown-toggle langer" data-toggle="dropdown"><span class="glyphicon glyphicon-globe"></span><?php echo $lang['LANGUAGE'];?>: <strong><?php if (isset($langid)){echo $langid;} else {echo LANGUAGE;}; ?></strong> <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu">

                        <?php include('_lang.php');?>

                </ul>
              </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo $session->username;?> <?php if( (isset($_SESSION['unread']) && ($_SESSION['unread'] > 0)) && ($config['ENABLE_MESSAGES'] == 1)) { ?><span class="badge zoomIn animated"><?php echo $_SESSION['unread'];?></span><?php } ?> <span class="caret"></span></a>

            <ul class="dropdown-menu loginer" role="menu">
                 <img src="https://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( $gen_info['email'] ) ) );?>?s=50" class="gravimg" alt="Gravatar"/>
                 <li><a href="userinfo.php?user=<?php echo $session->username;?>"><?php echo $lang['MY_PROFILE'];?></a></li>
                 <li><a href="useredit.php"><?php echo $lang['EDIT'];?> <?php echo $lang['MY_PROFILE'];?></a></li>
                 <?php if (($config['ENABLE_COMMENTS'] == 1)) { ?><li><a href="mycomments.php"><?php echo $lang['COMMENT_TITLE'];?></a></li><?php }?>
                 <?php if (($config['ENABLE_MESSAGES'] == 1)) { ?><li><a href="messages.php"><?php if( (isset($_SESSION['unread']) && ($_SESSION['unread'] > 0))) { ?><span class="badge zoomIn animated"><?php echo $_SESSION['unread'];?></span>&nbsp;&nbsp;&nbsp;<?php } ?><?php echo $lang['MSG_TITLE'];?></a></li><?php }?>
                 <?php if($session->isAdmin()){?><li class="divider"></li><li><a href="admin/index.php"> <?php echo $lang['ADMIN_CENTER'];?></a></li><?php }?>
                 <li class="divider"></li><li><a href="admin/process.php"><?php echo $lang['LOGOUT'];?></a></li>
            </ul>
        </li>

        <?php } else { ?>

 <li class="dropdown">
                <a href="#" class="dropdown-toggle langer" data-toggle="dropdown"><span class="glyphicon glyphicon-globe"></span><?php echo $lang['LANGUAGE'];?>: <strong><?php if (isset($langid)){echo $langid;} else {echo LANGUAGE;}; ?></strong> <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu">

                        <?php include('_lang.php');?>

                </ul>
              </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $lang['LOGINREGISTER']; ?> <b class="caret"></b></a>
          <ul class="dropdown-menu loginer" role="menu">

            <li>

                <form class="navbar-form" role="form" action="admin/process.php" method="POST">
                  <div class="form-group">
                    <input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>" class="form-control" placeholder="<?php echo $lang['USERNAME']; ?>" />
                  </div>
                  <div class="input-group">
                    <input type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>" placeholder="<?php echo $lang['PASSWORD']; ?>" class="form-control">
                    <span class="input-group-btn">
                    <input type="hidden" name="sublogin" value="1">
                      <button class="btn btn-success" type="submit"><?php echo $lang['LOGIN']; ?></button>
                    </span>
                  </div>
                  <div class="form-group">
                    <label for="remember"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['REMEMBER']; ?>
                    <input type="checkbox" name="remember" id="remember" <?php if($form->value("remember") != ""){ echo "checked"; } ?>></label>
                  </div><br />

                </form>



            </li>
            <li><a href="forgotpass.php"><span class="glyphicon glyphicon-question-sign"></span> <?php echo $lang['FORGOT']; ?>?</a></li>
            <li><a href="registration.php"><span class="glyphicon glyphicon-ok-sign"></span> <?php echo $lang['REGISTER']; ?></a></li>

          </ul>
        </li>
        <?php } ?>
        </ul>
        </div>
      </div>
    </div>
    <?php if((isset($_GET['error'])) && ($_GET['error'] == 1)){ ?>

           <div class="errno">
           <div class="alert alert-danger alert-dismissible fadeInRight animated" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
              <?php echo $lang['GENERAL_ERROR'];?>
            </div>
           </div>
        <?php } ?>
