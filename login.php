<?php
    require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL

    include('header.inc.php');     // INCLUDE <HEAD> PART
    include ('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)
    include ('messages.inc.php');
    if($session->logged_in){ ?>

	<script type="text/javascript">window.location.href = "<?php echo $config['WEB_ROOT'].$config['home_page'];?>"</script><meta http-equiv="refresh" content="0; url=<?php echo $config['WEB_ROOT'].$config['home_page'];?>"/>

                 <?php  }
?>

<div class="container">
    <div class="row">
       <div class="col-md-12">
       <div class="cleaner"></div>
        <h1><?php echo $lang['LOGIN'];?></h1><hr />
        </div>
    </div>
    <div class="row">
       <div class="col-md-12">

<form class="form-horizontal" role="form" action="admin/process.php" method="POST">

              <div class="form-group">
                  <label for="user" class="col-md-4 control-label"><?php echo $lang['USERNAME'];?></label>
                  <div class="col-md-8">
                    <input type="text" name="user" id="user" required maxlength="30" value="<?php echo $form->value("user"); ?>" class="form-control" placeholder="<?php echo $lang['USERNAME']; ?>" />
                    <?php echo $form->error("user"); ?>
                  </div>
                </div>

              <div class="form-group">
                  <label for="password" class="col-md-4 control-label"><?php echo $lang['PASSWORD'];?></label>
                  <div class="col-md-8">
                    <input type="password" name="pass" id="password" required maxlength="30" value="<?php echo $form->value("pass"); ?>" placeholder="<?php echo $lang['PASSWORD']; ?>" class="form-control">
                    <?php echo $form->error("pass"); ?>
                  </div>
                </div>

              <div class="form-group">
                  <label for="remember" class="col-md-4 control-label"><?php echo $lang['REMEMBER'];?></label>
                  <div class="col-md-8">
                    <input type="checkbox" name="remember" id="remember" class="pull-left" <?php if($form->value("remember") != ""){ echo "checked"; } ?>>
                  </div>
                </div>


                    <span class="inputer">
                    <input type="hidden" name="sublogin" value="1">
                      <a href="forgotpass.php" class="btn btn-default"><span class="glyphicon glyphicon-question-sign"></span> <?php echo $lang['FORGOT']; ?>?</a> <button class="btn btn-success" type="submit"><?php echo $lang['LOGIN']; ?></button>
                    </span>



                </form>
        </div>
    </div>
</div>
 
<?php
    include('footer.inc.php');    // INCLUDE <FOOTER> PART (SKIN RELATED)

?>