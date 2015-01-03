<?php
if (!defined('VERSIONCT')) {
    die;
}?>
<?php
/**
 * AdminUserEdit.php
 *
 * This page is for admin to edit user's account information
 * such as their password, email address, etc.
 *
 * Written by: Siggles
 * Last Updated: December 26, 2011
 */

/**
 * User has submitted form without errors and user's
 * account has been edited successfully.
 */
if(isset($_SESSION['adminedit'])){

   unset($_SESSION['adminedit']);

                         echo "<div class='container'><div class='cleaner'></div><h1>".$lang['SAVE_SUCCESS']."!</h1><div class='cleansmall'></div>";

                         echo "<center><h2><b>".$_SESSION['usertoedit']."</b> ".$lang['ACCOUNT_UPDATE']."</h2><br><br><a href='../admin/index.php?id=3' class='btn btn-lg btn-info'>".$lang['BACK']."</a></center></div>";
                      		unset($_SESSION['usertoedit']);
                      }

else{

/**
 * If user is not logged in, then do not display anything.
 * If user is logged in, then display the form to edit
 * account information, with the current email address
 * already in the field.
 */
if (!$session->isAdmin()) {
   header("Location: ".$config['WEB_ROOT'].$config['home_page']);
}

else if ($_GET['usertoedit'] == FALSE) {
  echo "<div class='container'><div class='cleaner'></div><h1>".$lang['ERROR']."!</h1><div class='cleansmall'></div>";

                         echo "<center><h2><b>".$lang['ERROR_USERNOTFOUND']."</h2><br><br><a href='../admin/index.php?id=3' class='btn btn-lg btn-info'>".$lang['BACK']."</a></center></div>";
}


else {
$usertoedit=$_GET['usertoedit'];
$req_user_info = $database->getUserInfo($usertoedit);
?>

<div class="container-fluid">
<div class="row">
<div class="col-sm-12">
<div class="positioner"><?php echo $lang['ACCOUNT'];?>: <?php echo $usertoedit; ?></div>

<?php
if($form->num_errors > 0){
   echo "<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button>".$form->num_errors." ".$lang['ERRORS_FOUND']."</div>";}
?>
<div class="cleaner"></div>
</div>
<div class="col-sm-10">

<form class="form-horizontal" action="adminprocess.php" method="POST">

<div class="form-group">
    <label for="username" class="col-sm-4 control-label"><?php echo $lang['USERNAME'];?>:</label>
    <div class="col-sm-8">
      <input class="form-control" name="username" type="text" value="<?php if($form->value("username") == ""){ echo $req_user_info['username']; }else{ echo $form->value("username"); }?>" size="30" maxlength="30">
    </div>
  </div>
  <?php echo $form->error("username"); ?>

<div class="form-group">
    <label for="newpass" class="col-sm-4 control-label"><?php echo $lang['NEW_PASSWORD'];?>:</label>
    <div class="col-sm-8">
      <input class="form-control" name="newpass" type="password" value="<?php echo $form->value("newpass"); ?>" size="30" maxlength="30">
    </div>
  </div>
  <?php echo $form->error("newpass"); ?>

<div class="form-group">
    <label for="conf_newpass" class="col-sm-4 control-label"><?php echo $lang['CONF_PASSWORD'];?>:</label>
    <div class="col-sm-8">
      <input class="form-control" name="conf_newpass" type="password" value="<?php echo $form->value("newpass"); ?>" size="30" maxlength="30">
    </div>
  </div>
  <?php echo $form->error("newpass"); ?>

<div class="form-group">
    <label for="email" class="col-sm-4 control-label"><?php echo $lang['EMAIL'];?>:</label>
    <div class="col-sm-8">
     <input class="form-control" name="email" type="text" value="<?php if($form->value("email") == ""){ echo $req_user_info['email'];}else{ echo $form->value("email");}?>" size="30" maxlength="50">
    </div>
  </div>
  <?php echo $form->error("email"); ?>

<div class="form-group">
    <label for="userlevel" class="col-sm-4 control-label"><?php echo $lang['LEVEL'];?>:</label>
    <div class="col-sm-8">



    <select name="userlevel" id="userlevel" class="form-control">

    <option value="9" <?php if ($req_user_info['userlevel'] == 9) { echo "selected='selected'"; }?>><?php echo $lang['LEVEL_9'];?></option>
    <option value="8" <?php if ($req_user_info['userlevel'] == 8) { echo "selected='selected'"; }?>><?php echo $lang['LEVEL_8'];?></option>
    <option value="7" <?php if ($req_user_info['userlevel'] == 7) { echo "selected='selected'"; }?>><?php echo $lang['LEVEL_7'];?></option>
    <option value="6" <?php if ($req_user_info['userlevel'] == 6) { echo "selected='selected'"; }?>><?php echo $lang['LEVEL_6'];?></option>
    <option value="5" <?php if ($req_user_info['userlevel'] == 5) { echo "selected='selected'"; }?>><?php echo $lang['LEVEL_5'];?></option>
    <option value="4" <?php if ($req_user_info['userlevel'] == 4) { echo "selected='selected'"; }?>><?php echo $lang['LEVEL_4'];?></option>
    <option value="3" <?php if ($req_user_info['userlevel'] == 3) { echo "selected='selected'"; }?>><?php echo $lang['LEVEL_3'];?></option>
    <option value="2" <?php if ($req_user_info['userlevel'] == 2) { echo "selected='selected'"; }?>><?php echo $lang['LEVEL_2'];?></option>
    <option value="1" <?php if ($req_user_info['userlevel'] == 1) { echo "selected='selected'"; }?>><?php echo $lang['LEVEL_1'];?></option>
    <option value="0" <?php if ($req_user_info['userlevel'] == 0) { echo "selected='selected'"; }?>><?php echo $lang['LEVEL_0'];?></option>

    </select>

    </div>
  </div>
  <?php echo $form->error("userlevel"); ?>

<div class="pull-right">
<input type="hidden" name="subedit" value="1">
<input type="hidden" name="usertoedit" value="<?php echo $usertoedit; ?>">
<input type="submit" name="button" value="⊚" class="btn btn-success butmod" title="<?php echo $lang['SUBMIT'];?>">
<input type="submit" name="button" title="<?php echo $lang['DELETE'];?>" value="⊘" class="btn btn-danger butmod" onclick="return confirm ('<?php echo $lang['SURE_DELETE'];?>')">
</div>

</form>
</div>
<div class="col-sm-2">
      <?php echo "<img src='http://www.gravatar.com/avatar/".md5( strtolower( trim( $req_user_info['email'] ) ) )."?s=150' alt='' class='gravatar' /> ";?>
</div>


</div></div>
<?php
}
}
?>