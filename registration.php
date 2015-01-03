<?php

    require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL
    include('header.inc.php');     // INCLUDE <HEAD> PART (SKIN RELATED)
    include('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)
    include ('messages.inc.php');
    if($session->logged_in){ ?>

	<script type="text/javascript">window.location.href = "<?php echo $config['WEB_ROOT'].$config['home_page'];?>"</script><meta http-equiv="refresh" content="0; url=<?php echo $config['WEB_ROOT'].$config['home_page'];?>"/>
                 <?php  }
?>

<div class="container">
    <div class="row">
       <div class="col-sm-12">

        <h1><?php echo $lang['REGISTER'];?></h1>
        <hr />

       </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
<?php
/**
 * The user is already logged in, not allowed to register.
 */
if($session->logged_in){
   echo "<h2>".$lang['SORRY']." <strong>".$session->username."</strong> ".$lang['ALREADY_REGGED']."</h2>";
}
else if($config['ACCOUNT_ACTIVATION'] == 4){  ?>

           <h3><?php echo $lang['SORRY']." <strong>".$_SESSION['reguname']."</strong> ".$lang['REG_DISABLED_M'];?></h3>

	<?php }
/**
 * The user has submitted the registration form and the
 * results have been processed.
 */

	else if(isset($_SESSION['regsuccess'])){
	/* Registration disabled */
	if ($_SESSION['regsuccess']==6){ ?>

           <h3><?php echo $lang['SORRY']." <strong>".$_SESSION['reguname']."</strong> ".$lang['REG_DISABLED_M'];?></h3>

	<?php }
	/* Registration was successful */
	else if($_SESSION['regsuccess']==0 || $_SESSION['regsuccess']==5){ ?>
           <center>
           <h3><?php echo $lang['THANK_YOU']." <strong>".$_SESSION['reguname']."</strong> ".$lang['CAN_LOGIN'];?></h3>

           <a href="login.php" class="btn btn-lg btn-success"><?php echo $lang['LOGIN'];?></a>
           </center>

	<?php }
    /* Registration was successful, Email activation */
	else if($_SESSION['regsuccess']==3){ ?>

           <h3><?php echo $lang['THANK_YOU']." <strong>".$_SESSION['reguname']."</strong> ".$lang['EMAIL_ACTIVATE'];?></h3>

	<?php }
    /* Registration was successful, Admin activation */
	else if($_SESSION['regsuccess']==4){ ?>

           <h3><?php echo $lang['THANK_YOU']." <strong>".$_SESSION['reguname']."</strong> ".$lang['ADMIN_ACTIVATE'];?></h3>


   <?php }
   /* Registration failed */
   else if ($_SESSION['regsuccess']==2){ ?>

        <h3><?php echo $lang['SORRY']." <strong>".$_SESSION['reguname']."</strong> ".$lang['REG_FAILED_M'];?></h3>


   <?php }
   unset($_SESSION['regsuccess']);
   unset($_SESSION['reguname']);
   if ($config['ENABLE_CAPTCHA']){ unset($_SESSION['security']);}
   }
    else if ((isset($_GET['mode'])) && ($_GET['mode'] == 'activate')) {
	$user = $_GET['user'];
	$actkey = $_GET['activatecode'];

	$sql = $database->connection->prepare("UPDATE ".TBL_USERS." SET USERLEVEL = '3' WHERE username=:user AND actkey=:actkey");
	$sql->bindParam(":user",$user);
	$sql->bindParam(":actkey",$actkey);
	$sql->execute();
    ?>
	    <h3><?php echo $lang['THANK_YOU']." <strong>".$_SESSION['reguname']."</strong>. ".$lang['ACC_ACTIVATED'];?></h3>

    <?php
	// some warning if not successful
}
/**
 * The user has not filled out the registration form yet.
 * Below is the page with the sign-up form, the names
 * of the input fields are important and should not
 * be changed.
 */
else{
?>

<form action="admin/process.php" method="post" class="form-horizontal" role="form">

<div class="form-group">
    <label for="username" class="col-sm-4 control-label"><?php echo $lang['USERNAME'];?></label>
    <div class="col-sm-8">
      <input type="text" name="user" id="username" class="form-control" value="<?php echo $form->value("user"); ?>" required/>
      <?php echo $form->error("user"); ?>
    </div>
  </div>

<div class="form-group">
    <label for="password" class="col-sm-4 control-label"><?php echo $lang['PASSWORD'];?></label>
    <div class="col-sm-8">
      <input type="password" id="password" name="pass" class="form-control" value="<?php echo $form->value("pass"); ?>" required/>
      <?php echo $form->error("pass"); ?>
    </div>
  </div>

<div class="form-group">
    <label for="confpassword" class="col-sm-4 control-label"><?php echo $lang['CONF_PASSWORD'];?></label>
    <div class="col-sm-8">
      <input type="password" name="conf_pass" id="confpassword" class="form-control" value="<?php echo $form->value("conf_pass"); ?>" required/>
      <?php echo $form->error("pass"); ?>
    </div>
  </div>

<div class="form-group">
    <label for="email" class="col-sm-4 control-label"><?php echo $lang['EMAIL'];?></label>
    <div class="col-sm-8">
      <input type="text" name="email" id="email" class="form-control" value="<?php echo $form->value("email"); ?>" required/>
      <?php echo $form->error("email"); ?>
    </div>
  </div>

<div class="form-group">
    <label for="conf_email" class="col-sm-4 control-label"><?php echo $lang['CONF_EMAIL'];?></label>
    <div class="col-sm-8">
      <input type="text" name="conf_email" id="conf_email" class="form-control" value="<?php echo $form->value("conf_email"); ?>" required/>
      <?php echo $form->error("email"); ?>
    </div>
  </div>


<?php include ('_captcha.php');?>
<div class="inputer">

<input type="hidden" name="subjoin" value="1" />
<?php echo "<a href=".$config['WEB_ROOT'].$config['home_page']." class='btn btn-lg btn-warning'>".$lang['HOME']."</a>"; ?>
&nbsp;<input type="submit" value="<?php echo $lang['REGISTER'];?>" id="submit" class="btn btn-lg btn-success"/>




</div>
</form>

<?php

}
?>
</div>
</div>
</div>
<?php
    include('footer.inc.php');    // INCLUDE <FOOTER> PART (SKIN RELATED)

?>