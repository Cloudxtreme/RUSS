<?php

    require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL
    include('header.inc.php');     // INCLUDE <HEAD> PART (SKIN RELATED)
    include ('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)
    include ('messages.inc.php');
    $nocallshere = 1;
?>

<div class="container">
    <div class="row">
       <div class="col-md-12">
<?php
/**
 * UserEdit.php
 *
 * This page is for users to edit their account information such as their password,
 * email address, etc. Their usernames can not be edited. When changing their
 * password, they must first confirm their current password.
 */
/**
 * User has submitted form without errors and user's
 * account has been edited successfully.
 */
if(isset($_SESSION['useredit'])){
   unset($_SESSION['useredit']); ?>
   <h1><?php echo $lang['DETAILS_UPDATE'];?></h1><hr />
   <h3><?php echo "<strong>".$session->username."</strong> ".$lang['UPDATED'];?></h3>
   <?php  }
else{

/**
 * If user is not logged in, then do not display anything.
 * If user is logged in, then display the form to edit
 * account information, with the current email address
 * already in the field.
 */
if(!$session->logged_in){ ?>

	<script type="text/javascript">window.location.href = "<?php echo $config['WEB_ROOT'].$config['home_page'];?>"</script><meta http-equiv="refresh" content="0; url=<?php echo $config['WEB_ROOT'].$config['home_page'];?>"/>


<?php die; } else {
?>
<div class="cleaner"></div>
<h1><?php echo $lang['EDIT'];?> <?php echo $lang['ACCOUNT'];?>: <?php echo $session->username; ?></h1><hr />
            </div>
        </div>
        <div class="row">
           <div class="col-md-12">

              <div class="cleaner"></div>

  <div role="tabpanel" class="panelche">


        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#profile" role="tab" data-toggle="tab"><?php echo $lang['LOGIN'];?></a></li>
          <li role="presentation"><a href="#settings" role="tab" data-toggle="tab"><?php echo $lang['SETTINGS'];?></a></li>
          <li role="presentation"><a href="#personal" role="tab" data-toggle="tab"><?php echo $lang['PERSONAL'];?></a></li>
          <li role="presentation"><a href="#internet" role="tab" data-toggle="tab"><?php echo $lang['INTERNET'];?></a></li>
        </ul>


              <form action="admin/process.php" method="POST" class="form-horizontal" role="form">

        <div class="tab-content">

            <div role="tabpanel" class="tab-pane fade in active" id="profile">
            <div class="cleaner"></div>

              <div class="form-group">
                  <label for="password" class="col-md-4 control-label"><?php echo $lang['PASSWORD'];?></label>
                  <div class="col-md-8">
                    <input type="password" name="curpass" id="password" class="form-control" value="<?php echo $form->value("curpass"); ?>" />
                    <?php echo $form->error("curpass"); ?>
                  </div>
                </div>

              <div class="form-group">
                  <label for="newpass" class="col-md-4 control-label"><?php echo $lang['NEW_PASSWORD'];?></label>
                  <div class="col-md-8">
                    <input type="password" name="newpass" id="newpass" class="form-control" value="<?php echo $form->value("newpass"); ?>" />
                    <?php echo $form->error("newpass"); ?>
                  </div>
                </div>

              <div class="form-group">
                  <label for="conf_newpass" class="col-md-4 control-label"><?php echo $lang['CONF_PASSWORD'];?></label>
                  <div class="col-md-8">
                    <input type="password" name="conf_newpass" id="conf_newpass" class="form-control" value="<?php echo $form->value("newpass"); ?>" />
                    <?php echo $form->error("newpass"); ?>
                  </div>
                </div>

              <div class="form-group">
                  <label for="email" class="col-md-4 control-label"><?php echo $lang['EMAIL'];?></label>
                  <div class="col-md-8">
                    <input type="text" name="email" id="email" class="form-control" value="<?php if($form->value("email") == ""){ echo $session->userinfo['email'];}else{ echo $form->value("email");}?>">
                    <?php echo $form->error("email"); ?>
                  </div>
                </div>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="settings">

<div class="cleaner"></div>

              <div class="form-group">
                  <label for="privacy" class="col-md-4 control-label"><?php echo $lang['PRIVACY'];?></label>
                  <div class="col-md-8">

                        <select name="privacy" id="privacy" class="form-control">

                            <option value="Y" <?php if ($gen_info['privacy'] == "Y") { echo "selected='selected'"; }?>><?php echo $lang['YES'];?></option>
                            <option value="N" <?php if ($gen_info['privacy'] == "N") { echo "selected='selected'"; }?>><?php echo $lang['NO'];?></option>

                        </select>

                  </div>
                </div>

             <div class="form-group">
                  <label for="showonline" class="col-md-4 control-label"><?php echo $lang['SHOW_ONLINE'];?></label>
                  <div class="col-md-8">

                        <select name="showonline" id="showonline" class="form-control">

                            <option value="Y" <?php if ($gen_info['online'] == "Y") { echo "selected='selected'"; }?>><?php echo $lang['YES'];?></option>
                            <option value="N" <?php if ($gen_info['online'] == "N") { echo "selected='selected'"; }?>><?php echo $lang['NO'];?></option>

                        </select>


                  </div>
                </div>
        <?php if (($config['ENABLE_COMMENTS'] == 1)) { ?>
             <div class="form-group">
                  <label for="showcomments" class="col-md-4 control-label"><?php echo $lang['SHOW_COMMENTS'];?></label>
                  <div class="col-md-8">

                        <select name="showcomments" id="showcomments" class="form-control">

                            <option value="Y" <?php if ($gen_info['showcomments'] == "Y") { echo "selected='selected'"; }?>><?php echo $lang['YES'];?></option>
                            <option value="N" <?php if ($gen_info['showcomments'] == "N") { echo "selected='selected'"; }?>><?php echo $lang['NO'];?></option>

                        </select>


                  </div>
                </div>
        <?php } ?>

        <?php if (($config['ENABLE_CALLS'] == 1) && (($session->userlevel) > 7)) { ?>

              <div class="form-group">
                  <label for="allowcalls" class="col-md-4 control-label"><?php echo $lang['ALLOW_CALLS'];?></label>
                  <div class="col-md-8">

                        <select name="allowcalls" id="allowcalls" class="form-control">

                            <option value="Y" <?php if ($gen_info['allowcalls'] == "Y") { echo "selected='selected'"; }?>><?php echo $lang['YES'];?></option>
                            <option value="N" <?php if ($gen_info['allowcalls'] == "N") { echo "selected='selected'"; }?>><?php echo $lang['NO'];?></option>

                        </select>


                  </div>
                </div>
            <?php } ?>

        </div>
        <div role="tabpanel" class="tab-pane fade" id="personal">
<div class="cleaner"></div>


                <div class="form-group">
                  <label for="namer" class="col-md-4 control-label"><?php echo $lang['NAME'];?></label>
                  <div class="col-md-8">
                       <input type="text" name="namer" id="namer" class="form-control" value="<?php if($gen_info['name'] == ""){ echo $form->value("namer");}else{ echo $gen_info['name'];}?>">

                  </div>
                </div>

                <div class="form-group">
                  <label for="surname" class="col-md-4 control-label"><?php echo $lang['SURNAME'];?></label>
                  <div class="col-md-8">
                       <input type="text" name="surname" id="surname" class="form-control" value="<?php if($gen_info['surname'] == ""){ echo $form->value("surname");}else{ echo $gen_info['surname'];}?>">

                  </div>
                </div>

                <div class="form-group">
                  <label for="phone" class="col-md-4 control-label"><?php echo $lang['PHONE'];?></label>
                  <div class="col-md-8">
                       <input type="text" name="phone" id="phone" class="form-control" value="<?php if($gen_info['phone'] == ""){ echo $form->value("phone");}else{ echo $gen_info['phone'];}?>">

                  </div>
                </div>

        </div>
        <div role="tabpanel" class="tab-pane fade" id="internet">

<div class="cleaner"></div>

                <div class="form-group">
                  <label for="twitter" class="col-md-4 control-label"><?php echo $lang['TWITTER'];?></label>
                  <div class="col-md-8">
                       <input type="text" name="twitter" id="twitter" class="form-control" value="<?php if($gen_info['twitter'] == ""){ echo $form->value("twitter");}else{ echo $gen_info['twitter'];}?>">

                  </div>
                </div>

                <div class="form-group">
                  <label for="facebook" class="col-md-4 control-label"><?php echo $lang['FACEBOOK'];?></label>
                  <div class="col-md-8">
                       <input type="text" name="facebook" id="facebook" class="form-control" value="<?php if($gen_info['facebook'] == ""){ echo $form->value("facebook");}else{ echo $gen_info['facebook'];}?>">

                  </div>
                </div>

                <div class="form-group">
                  <label for="google" class="col-md-4 control-label"><?php echo $lang['GOOGLE'];?></label>
                  <div class="col-md-8">
                       <input type="text" name="google" id="google" class="form-control" value="<?php if($gen_info['google'] == ""){ echo $form->value("google");}else{ echo $gen_info['google'];}?>">

                  </div>
                </div>

                <div class="form-group">
                  <label for="linkedin" class="col-md-4 control-label"><?php echo $lang['LINKEDIN'];?></label>
                  <div class="col-md-8">
                       <input type="text" name="linkedin" id="linkedin" class="form-control" value="<?php if($gen_info['linkedin'] == ""){ echo $form->value("linkedin");}else{ echo $gen_info['linkedin'];}?>">

                  </div>
                </div>

                <div class="form-group">
                  <label for="website" class="col-md-4 control-label"><?php echo $lang['WEBSITE'];?></label>
                  <div class="col-md-8">
                       <input type="text" name="website" id="website" class="form-control" value="<?php if($gen_info['website'] == ""){ echo $form->value("website");}else{ echo $gen_info['website'];}?>">

                  </div>
                </div>

                <div class="form-group">
                  <label for="icq" class="col-md-4 control-label"><?php echo $lang['ICQ'];?></label>
                  <div class="col-md-8">
                       <input type="text" name="icq" id="icq" class="form-control" value="<?php if($gen_info['icq'] == ""){ echo $form->value("icq");}else{ echo $gen_info['icq'];}?>">

                  </div>
                </div>

                <div class="form-group">
                  <label for="skype" class="col-md-4 control-label"><?php echo $lang['SKYPE'];?></label>
                  <div class="col-md-8">
                       <input type="text" name="skype" id="skype" class="form-control" value="<?php if($gen_info['skype'] == ""){ echo $form->value("skype");}else{ echo $gen_info['skype'];}?>">

                  </div>
                </div>

                <div class="form-group">
                  <label for="gtalk" class="col-md-4 control-label"><?php echo $lang['GTALK'];?></label>
                  <div class="col-md-8">
                       <input type="text" name="gtalk" id="gtalk" class="form-control" value="<?php if($gen_info['gtalk'] == ""){ echo $form->value("gtalk");}else{ echo $gen_info['gtalk'];}?>">

                  </div>
                </div>



              </div>
        </div>
                      <div class="inputer">
              <input type="hidden" name="subedit" value="1">
              <a class="btn btn-lg btn-default" href="javascript:history.back();"><?php echo $lang['CANCEL'];?></a>
              <input type="submit" value="<?php echo $lang['EDIT'];?>" id="submit" class="btn btn-lg btn-success"/>
              </div>
        </form>
    </div>



    </div>
</div>
</div>
<?php
}
}


    include('footer.inc.php');    // INCLUDE <FOOTER> PART (SKIN RELATED)

?>