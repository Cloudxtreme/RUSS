<?php
require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL

  include('header.inc.php');     // INCLUDE <HEAD> PART
  include ('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)
?>
<div class="container">
    <div class="row">
       <div class="col-md-12">
<?php
/**
 * Forgot Password form has been submitted and no errors
 * were found with the form (the username is in the database)
 */
if(isset($_SESSION['forgotpass'])){
   /**
    * New password was generated for user and sent to user's
    * email address.
    */
   if($_SESSION['forgotpass']){
      echo "<h1>".$lang['PASS_SENT']."</h1><hr />";
      echo "<p>".$lang['PASS_SENT_TEXT']."</p></div></div></div>";
   }
   /**
    * Email could not be sent, therefore password was not
    * edited in the database.
    */
   else{
      echo "<h1>".$lang['PASS_FAIL']."</h1><hr />";
      echo "<p>".$lang['PASS_FAIL_TEXT']."</p></div></div></div>";
   }

   unset($_SESSION['forgotpass']);
}
else{

/**
 * Forgot password form is displayed, if error found
 * it is displayed.
 */
?>
<div class="cleaner"></div>
<h1><?php echo $lang['FORGOT']; ?></h1><hr />
<p><?php echo $lang['FORGOT_PASS_INFO']; ?></p>
<div class="cleaner"></div>
            </div>
        </div>

<div class="row">

<form action="admin/process.php" method="POST" class="form-horizontal" role="form">

              <div class="form-group">
                  <label for="user" class="col-md-4 control-label"><?php echo $lang['USERNAME'];?></label>
                  <div class="col-md-8">
                    <input type="text" name="user" id="user" maxlength="30" required value="<?php echo $form->value("user"); ?>" class="form-control" placeholder="<?php echo $lang['USERNAME']; ?>" />
                    <?php echo $form->error("user"); ?>
                  </div>
                </div>

              <div class="form-group">
                  <label for="email" class="col-md-4 control-label"><?php echo $lang['EMAIL'];?></label>
                  <div class="col-md-8">
                    <input type="text" name="email" id="email" maxlength="30" required value="<?php echo $form->value("email"); ?>" class="form-control" placeholder="<?php echo $lang['EMAIL']; ?>" />
                    <?php echo $form->error("email"); ?>
                  </div>
                </div>
		<input type="hidden" name="subforgot" value="1">
 		<input type="submit" class="inputer btn btn-success" value="<?php echo $lang['GET_NEW_PASSWORD']; ?>">

</form>
<?php
}
?>

    </div>
</div>
<?php
  include('footer.inc.php');    // INCLUDE <FOOTER> PART (SKIN RELATED)

?>