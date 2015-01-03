<?php
if (!defined('VERSIONCT')) {
    die;
}?>
<div class="container-fluid">
<div class="positioner"><?php echo $lang['SETTINGS'];?></div>
<div class="row">
      <div class="col-md-12">
          <?php
          if($form->num_errors > 0){
             echo "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button><strong>".$form->num_errors." ".$lang['ERRORS_FOUND']."</div>";
          }
          if(isset($_SESSION['configedit'])){
          	unset($_SESSION['configedit']);
                echo "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button><strong>".$lang['DETAILS_UPDATE']."</div>";
             }
          ?>

          <form action="adminprocess.php" method="POST">


          <div role="tabpanel" class="panelche">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#settings" role="tab" data-toggle="tab"><?php echo $lang['GENERAL'];?>  <?php echo $lang['SETTINGS'];?></a></li>
              <li role="presentation"><a href="#registration" role="tab" data-toggle="tab"><?php echo $lang['REGISTRATION'];?> &amp; <?php echo $lang['SESSION'];?></a></li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane fade in active" id="settings">

              <div class="col-md-6">

              <label for="sitename"><?php echo $lang['WEBSITE'];?> <?php echo $lang['NAME'];?>:</label>
              	 <input class="form-control" id="sitename" type="text" size="40" maxlength="255" name="sitename" value="<?php echo $config['SITE_NAME']; ?>" /><?php echo $form->error("sitename"); ?>
                 <div class="cleansmall"></div>
              	 <label for="sitedesc"><?php echo $lang['WEBSITE'];?> <?php echo $lang['DESCRIPTION'];?>:</label>
              	 <input class="form-control" id="sitedesc" type="text" size="40" maxlength="255" name="sitedesc" value="<?php echo $config['SITE_DESC']; ?>" /><?php echo $form->error("sitedesc"); ?>
                </div><div class="col-md-6">

                 <label for="emailfromname"><?php echo $lang['EMAIL'];?> <?php echo $lang['FROM'];?>:</label>
              	 <input class="form-control" id="emailfromname" type="text" size="40" maxlength="255" name="emailfromname" value="<?php echo $config['EMAIL_FROM_NAME']; ?>" /><?php echo $form->error("emailfromname"); ?>

                 <div class="cleansmall"></div>
                 <label for="adminemail"><?php echo $lang['EMAIL'];?> <?php echo $lang['ADDRESS'];?>:</label>
              	 <input class="form-control" id="adminemail" type="text" size="40" maxlength="255" name="adminemail" value="<?php echo $config['EMAIL_FROM_ADDR']; ?>" /><?php echo $form->error("adminemail"); ?>

                </div><div class="col-md-12">
                <div class="cleansmall"></div>
                 <label for="webroot"><?php echo $lang['SITE_ROOT'];?></label>
              	 <input  class="form-control" id="webroot" type="text" size="40" maxlength="255" name="webroot" value="<?php echo $config['WEB_ROOT']; ?>" /><?php echo $form->error("webroot"); ?>
                 <div class="cleansmall"></div>
              	 <label for="home_page"><?php echo $lang['HOMEPAGE_URL'];?></label>
              	 <input class="form-control" id="home_page" type="text" size="40" maxlength="255" name="home_page" value="<?php echo $config['home_page']; ?>" /><?php echo $form->error("home_page"); ?>
            <div class="cleansmall"></div>
          	 <label for="comments"><?php echo $lang['COMMENT_TITLE'];?>:</label>
          	 <select class="form-control" name="comments" id="comments"><option value="1" <?php if ($config['ENABLE_COMMENTS'] == 1) { echo "selected='selected'"; }?>><?php echo $lang['YES'];?></option><option value="0" <?php if ($config['ENABLE_COMMENTS'] == 0) { echo "selected='selected'"; }?>><?php echo $lang['NO'];?></option></select>
                <div class="cleansmall"></div>
                             <label for="messages"><?php echo $lang['MSG_TITLE'];?>:</label>
                          	 <select class="form-control" name="messages" id="messages"><option value="1" <?php if ($config['ENABLE_MESSAGES'] == 1) { echo "selected='selected'"; }?>><?php echo $lang['YES'];?></option><option value="0" <?php if ($config['ENABLE_MESSAGES'] == 0) { echo "selected='selected'"; }?>><?php echo $lang['NO'];?></option></select>

                <div class="cleansmall"></div>
                             <label for="messages"><?php echo $lang['ALLOW_CALLS'];?>:</label>
                          	 <select class="form-control" name="calls" id="calls"><option value="1" <?php if ($config['ENABLE_CALLS'] == 1) { echo "selected='selected'"; }?>><?php echo $lang['YES'];?></option><option value="0" <?php if ($config['ENABLE_CALLS'] == 0) { echo "selected='selected'"; }?>><?php echo $lang['NO'];?></option></select>
              </div>
              </div>

              <div role="tabpanel" class="tab-pane fade" id="registration">

              <div class="col-md-6">
              <label for="accountactivation"><?php echo $lang['ACC_ACTIVATION'];?>:</label><br />
              <label class="radio-inline"><input name="activation" value="4" class="radio" type="radio" <?php if($config['ACCOUNT_ACTIVATION'] == 4) { echo "checked='checked'"; } ?>> <?php echo $lang['ACC_DISABLE'];?></label><br>
              <label class="radio-inline"><input name="activation" value="1" class="radio" type="radio" <?php if($config['ACCOUNT_ACTIVATION'] == 1) { echo "checked='checked'"; } ?>> <?php echo $lang['ACC_NOACTIVATION'];?></label><br>
              <label class="radio-inline"><input name="activation" value="2" class="radio" type="radio" <?php if($config['ACCOUNT_ACTIVATION'] == 2) { echo "checked='checked'"; } ?>> <?php echo $lang['ACC_BYUSER'];?></label><br>
              <label class="radio-inline"><input name="activation" value="3" class="radio" type="radio" <?php if($config['ACCOUNT_ACTIVATION'] == 3) { echo "checked='checked'"; } ?>> <?php echo $lang['ACC_BYADMIN'];?></label>
             <div class="cleansmall"></div>
          	 <label for="send_welcome"><?php echo $lang['SEND_WELCOME'];?>:</label>
          	 <select class="form-control" name="send_welcome" id="send_welcome"><option value="1" <?php if ($config['EMAIL_WELCOME'] == 1) { echo "selected='selected'"; }?>><?php echo $lang['YES'];?></option><option value="0" <?php if ($config['EMAIL_WELCOME'] == 0) { echo "selected='selected'"; }?>><?php echo $lang['NO'];?></option></select>
<div class="cleansmall"></div>
          	 <label for="enable_capthca"><?php echo $lang['ENABLE_CAPTCHA'];?>:</label>
          	 <select class="form-control" name="enable_capthca" id="enable_capthca"><option value="1" <?php if ($config['ENABLE_CAPTCHA'] == 1) { echo "selected='selected'"; }?>><?php echo $lang['YES'];?></option><option value="0" <?php if ($config['ENABLE_CAPTCHA'] == 0) { echo "selected='selected'"; }?>><?php echo $lang['NO'];?></option></select>
<div class="cleansmall"></div>
          	 <label for="all_lowercase"><?php echo $lang['CONVERT_USERNAMES'];?>:</label>
          	 <select class="form-control" name="all_lowercase" id="all_lowercase"><option value="1" <?php if ($config['ALL_LOWERCASE'] == 1) { echo "selected='selected'"; }?>><?php echo $lang['YES'];?></option><option value="0" <?php if ($config['ALL_LOWERCASE'] == 0) { echo "selected='selected'"; }?>><?php echo $lang['NO'];?></option></select>


             </div><div class="col-md-6">

          	 <label for="min_name_chars"><?php echo $lang['USERNAME'];?> <?php echo $lang['LENGTH'];?></label>
             <div class="clearfix"></div>
             <div class="input-group input-group-sm col-md-5 pull-left">
              <span class="input-group-addon"><?php echo $lang['MIN'];?></span>
              <input class="form-control" name="min_user_chars" type="text" size="3" maxlength="5" value="<?php echo $config['min_user_chars']; ?>" />
            </div>

            <div class="input-group input-group-sm col-md-5 pull-right">
              <span class="input-group-addon"><?php echo $lang['MAX'];?></span>
              <input class="form-control" type="text" size="3" maxlength="5" name="max_user_chars" value="<?php echo $config['max_user_chars']; ?>" />
            </div>
            <?php echo $form->error("min_user_chars"); ?>
            <?php echo $form->error("max_user_chars"); ?>

             <div class="cleansmall"></div>
          	 <label for="min_pass_chars"><?php echo $lang['PASSWORD'];?> <?php echo $lang['LENGTH'];?></label>
             <div class="clearfix"></div>
            <div class="input-group input-group-sm col-md-5 pull-left">
              <span class="input-group-addon"><?php echo $lang['MIN'];?></span>
              <input class="form-control" id="min_pass_chars" size="3" maxlength="3" name="min_pass_chars" value="<?php echo $config['min_pass_chars']; ?>" type="text">
            </div>

            <div class="input-group input-group-sm col-md-5 pull-right">
              <span class="input-group-addon"><?php echo $lang['MAX'];?></span>
              <input class="form-control" id="max_pass_chars" size="3" maxlength="3" name="max_pass_chars" value="<?php echo $config['max_pass_chars']; ?>" type="text">
            </div>
            <?php echo $form->error("min_pass_chars"); ?>
            <?php echo $form->error("max_pass_chars"); ?>

            <div class="cleansmall"></div>
            <label for="user_timeout"><?php echo $lang['USER_INACTIVITY'];?>:</label>

            <div class="input-group input-group-sm col-md-12">
              <span class="input-group-addon"><?php echo $lang['MINUTES'];?></span>
              <input class="form-control" id="user_timeout" type="text" size="5" maxlength="10" name="user_timeout" value="<?php echo $config['USER_TIMEOUT']; ?>" />
            </div>
            <?php echo $form->error("user_timeout"); ?>

            <div class="cleansmall"></div>
            <label for="guest_timeout"><?php echo $lang['GUEST_TIMEOUT'];?>:</label>

            <div class="input-group input-group-sm col-md-12">
              <span class="input-group-addon"><?php echo $lang['MINUTES'];?></span>
              <input class="form-control" id="guest_timeout" type="text" size="5" maxlength="10" name="guest_timeout" value="<?php echo $config['GUEST_TIMEOUT']; ?>" />
            </div>
            <?php echo $form->error("guest_timeout"); ?>


            <div class="cleansmall"></div>
            <label for="cookie_expiry"><?php echo $lang['COOKIE_EXPIRE'];?>:</label>

            <div class="input-group input-group-sm col-md-12">
              <span class="input-group-addon"><?php echo $lang['DAYS'];?></span>
              <input class="form-control" id="cookie_expiry" type="text" size="5" maxlength="10" name="cookie_expiry" value="<?php echo $config['COOKIE_EXPIRE']; ?>" />
            </div>
            <?php echo $form->error("cookie_expiry"); ?>

            <div class="input-group input-group-sm col-md-12">
            <div class="cleansmall"></div>
            <label for="cookie_path"><?php echo $lang['COOKIE_PATH'];?></label>
          	 <input class="form-control" id="cookie_path" type="text" size="5" maxlength="50" name="cookie_path" value="<?php echo $config['COOKIE_PATH']; ?>" /><br><?php echo $form->error("cookie_path"); ?>
            </div>

              </div>
              </div>




          </div>

          <div class="col-md-12">
                <div class="cleansmall"></div>
          		<input type="submit" id="submit" name="submit" value="<?php echo $lang['SUBMIT'];?>" class="btn btn-success full"/>

          </div>
          <div class="clearfix"></div>
          </div>


          <input type="hidden" name="configedit" value="1">
          </form>
          </div>
</div>
</div>