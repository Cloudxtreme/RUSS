<?php
    require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL

    include('header.inc.php');     // INCLUDE <HEAD> PART
    include ('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)

if(!$session->logged_in){ ?>

	<script type="text/javascript">window.location.href = "<?php echo $config['WEB_ROOT'].$config['home_page'];?>"</script><meta http-equiv="refresh" content="0; url=<?php echo $config['WEB_ROOT'].$config['home_page'];?>"/>


<?php die; }


if (($config['ENABLE_MESSAGES'] == 1) && ($session->logged_in)) {

        if((isset($_GET['message'])) && ($_GET['message']) == 1) { ?>

       <div class="errno">
           <div class="alert alert-success alert-dismissible fadeInRight animated" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
              <?php echo $lang['MSG_POSTED'];?>
            </div>
           </div>

      <?php } else if((isset($_GET['message'])) && ($_GET['message']) == 2) {  ?>

      <div class="errno">
           <div class="alert alert-danger alert-dismissible fadeInRight animated" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
              <?php echo $lang['ERROR'];?>
            </div>
           </div>


      <?php } else if((isset($_GET['message'])) && ($_GET['message']) == 3) {  ?>

      <div class="errno">
           <div class="alert alert-warning alert-dismissible fadeInRight animated" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
              <?php echo $lang['MSG_DELETED'];?>
            </div>
           </div>


      <?php } else if((isset($_GET['message'])) && ($_GET['message']) == 4) {  ?>

      <div class="errno">
           <div class="alert alert-success alert-dismissible fadeInRight animated" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
              <?php echo $lang['SUCCESS'];?>
            </div>
           </div>

      <?php } else if((isset($_GET['message'])) && ($_GET['message']) == 5) {  ?>

      <div class="errno">
           <div class="alert alert-danger alert-dismissible fadeInRight animated" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
              <?php echo $lang['NO_USER'];?>
            </div>
           </div>


      <?php }




      ?>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1><?php echo $lang['MSG_TITLE'];?></h1>
                                    <div class="cleaner"></div>
                                 </div>
                                </div>
                            </div>



                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">


  <div role="tabpanel" class="panelche">


        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#inbox" role="tab" data-toggle="tab"><?php echo $lang['INBOX'];?>&nbsp;&nbsp;&nbsp;&nbsp;
          <?php if ( (isset($_SESSION['unread'])) && ( $_SESSION['unread'] > 0) ) { ?>
          <span class="badge"><?php echo $_SESSION['unread'];?></span>  /
          <?php } ?>
          <?php if (isset($_SESSION['mtm_count'])) { echo $_SESSION['mtm_count'];}?></a></li>
          <li role="presentation"><a href="#outbox" role="tab" data-toggle="tab"><?php echo $lang['OUTBOX'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php if (isset($_SESSION['mym_count'])) { echo $_SESSION['mym_count'];}?></a></li>
          <li role="presentation"><a href="#newmsg" role="tab" data-toggle="tab"><?php echo $lang['MSG_MESSAGE'];?></a></li>
        </ul>

        <div class="tab-content">

            <div role="tabpanel" class="tab-pane fade in active" id="inbox">
            <div class="cleaner"></div>
            <?php

                          if (!$ctm) { ?>
                          <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                          <?php echo $lang['MSG_EMPTY']; ?>
                                </div>
                            </div>
                          </div>

                          <?php } else { ?>

                              <?php

                              echo "<ul class='paging'>";

                                  foreach ($ctm as $value) {
                                      $cmt_info = $database->getUserInfo($value['user']);

                                      if (($value['status'] != 3) && ($value['status'] != 4)) {

                                      echo "<li id='msg".$value['id']."'";
                                      if(($value['status'] == 0)){ echo "class='unread'";}
                                      if(($value['status'] == 10)){ echo "class='unread'";}
                                      echo"><div class='media'>";
                                      echo "<a class='media-left'>";
                                      echo "<img src='https://www.gravatar.com/avatar/".md5( strtolower( trim($cmt_info["email"] ) ) )."?s=30' class='thumbnail' alt='Gravatar'/></a>";
                                      echo "<div class='media-body'>";
                                      echo "<h4 class='media-heading'><span>".$lang['FROM'].":</span> ".$value['user']."</h4>";
                                      echo "<small>".$value['ctime']."</small>";
                                      if(($value['status'] == 1) || ($value['status'] > 9)){
                                      echo "<p>".$value['msg']."</p>";
                                      }
                                      echo "<div class='tools'>";

                                      $cmt_ider = $value['id'];




                  if(($value['status'] == 0) && ($session->logged_in) && ($value['status'] != 10) ) {
                  echo "

                          <form action='admin/process.php' method='POST'>
                              <input type='hidden' name='msg_notid' value='".$cmt_ider."'>
                              <input type='hidden' name='msg_status' value='1'>
                              <input type='hidden' name='submessage' value='3'>
                              <button class='btn btn-primary' type='submit'>".$lang['MSG_READ']." </button>
                          </form>

                      ";
                  }
               /*   if(($value['status'] == 10) && ($session->logged_in) ) {
                  echo "

                          <form action='admin/process.php' method='POST'>
                              <input type='hidden' name='msg_notid' value='".$cmt_ider."'>
                              <input type='hidden' name='msg_status' value='11'>
                              <input type='hidden' name='submessage' value='3'>
                              <button class='btn btn-primary' type='submit'>".$lang['MSG_READ']." </button>
                          </form>

                      ";
                  } */
                  if(($value['status'] == 1) && ($session->logged_in) && ($value['status'] != 10)){
                      echo "

                          <form action='admin/process.php' method='POST'>
                              <input type='hidden' name='msg_notid' value='".$cmt_ider."'>
                              <input type='hidden' name='msg_status' value='2'>
                              <input type='hidden' name='submessage' value='3'>
                              <button class='btn btn-info' type='submit'>".$lang['MSG_REPORT']."</button>
                          </form>

                          <form action='admin/process.php' method='POST'>
                          <input type='hidden' name='replyto' value='".$value['user']."'>
                          <input type='hidden' name='submessage' value='4'><a href='#newmsg' class='reply'></a>
                          <button class='btn btn-primary' type='submit'>".$lang['REPLY']."</button>
                          </form>



                      ";
                  }
                  if(($value['status'] == 2) && ($session->logged_in) && ($value['status'] != 10)){
                            echo "<span class='btn btn-warning'>".$lang['MSG_REPORTED']."</span>&nbsp;";
                  }

                  if(($value['status'] == 0) && ($session->logged_in) ){
                      echo "

                          <form action='admin/process.php' method='POST'>
                              <input type='hidden' name='msg_notid' value='".$cmt_ider."'>
                              <input type='hidden' name='msg_status' value='3'>
                              <input type='hidden' name='submessage' value='3'>
                              <button class='btn btn-danger' type='submit'>".$lang['DELETE']."</button>
                          </form>

                      ";
                  }

                  if(($value['status'] == 1) && ($session->logged_in) && ($value['status'] != 10)){
                      echo "

                          <form action='admin/process.php' method='POST'>
                              <input type='hidden' name='msg_notid' value='".$cmt_ider."'>
                              <input type='hidden' name='msg_status' value='4'>
                              <input type='hidden' name='submessage' value='3'>
                              <button class='btn btn-danger' type='submit'>".$lang['DELETE']."</button>
                          </form>

                      ";
                  }




                                      echo "</div></div></div></li>";
                                   }
                                   }
                                  }
                                  ?>
                                  </ul>
                            </div>




                            <div role="tabpanel" class="tab-pane fade" id="outbox">
                            <div class="cleaner"></div>
                            <?php



                          if (!$cmy) { ?>
                          <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                          <?php echo $lang['MSG_EMPTY']; ?>
                                </div>
                            </div>
                          </div>

                          <?php } else { ?>

                              <?php

                              echo "<ul class='paging'>";


                                  foreach ($cmy as $value) {
                                      $cmt_info = $database->getUserInfo($value['user']);


                                      echo "<li id='msg".$value['id']."'><div class='media'>";
                                      echo "<a class='media-left' target='_blank' href='userinfo.php?user=".$value['towho']."'>";
                                      $to_info = $database->getUserInfo($value['towho']);
                                      echo "<img src='https://www.gravatar.com/avatar/".md5( strtolower( trim($to_info['email'] ) ) )."?s=40' class='thumbnail' alt='Gravatar'/></a>";
                                      echo "<div class='media-body'>";
                                      echo "<h4 class='media-heading'>".$lang['TO'].": ".$value['towho']."</h4>";
                                      echo "<small>".$value['ctime']."</small>";
                                      echo "<p>".$value['msg']."</p>";
                                      echo "<div class='tools'>";

                                      $cmt_ider = $value['id'];



                  if(($value['status'] == 0) && ($session->logged_in) ) {
                  echo "

                          <span class='btn btn-info btn-xs'><span class='glyphicon glyphicon-info-sign'></span> ".$lang['MSG_POSTED']."</span>&nbsp;

                      ";
                  }
                  if(($value['status'] == 1) && ($session->logged_in) ){
                      echo "

                          <span class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok-sign'></span> ".$lang['MSG_IS_READ']."</span>&nbsp;

                      ";
                  }
                  if(($value['status'] == 2) && ($session->logged_in) ){
                            echo "<span class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-exclamation-sign'></span> ".$lang['MSG_REPORTED']."</span>&nbsp;";
                  }

                  if(($value['status'] == 3) && ($session->logged_in) ){
                            echo "<span class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-remove-sign'></span> ".$lang['MSG_DEL_UNREAD']."</span>&nbsp;";
                  }

                  if(($value['status'] == 4) && ($session->logged_in) ){
                            echo "<span class='btn btn-info btn-xs'><span class='glyphicon glyphicon-minus-sign'></span> ".$lang['MSG_DEL_READ']."</span>&nbsp;";
                  }




                                      echo "</div></div></div></li>";
                                   }

                                  }
                                  ?>
                                  </ul>

                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="newmsg">
                                        <div class="cleaner"></div>
                                                <form class="form-horizontal" role="form" action="admin/process.php" method="POST">

                                                      <div class="form-group">
                                                                <label for="towho" class="col-md-12 control-label text-left"><?php echo $lang['MSG_TO']; ?></label>
                                                                <div class="col-md-12">
                                                                  <input type="text" value="<?php if (isset($_SESSION['replyto'])) {echo $_SESSION['replyto'];} ?>"name="towho" required id="towho" class="form-control" maxlength="250" />
                                                                </div>
                                                    </div>
                                                    <div class="form-group">
                                                                <label for="msg" class="col-md-12 control-label text-left"><?php echo $lang['MSG_MESSAGE']; ?></label>
                                                                <div class="col-md-12">
                                                                  <textarea name="msg" id="msg" required class="form-control" maxlength="250" onkeyup="countChar(this)"></textarea>
                                                                  <div id="charNum">&nbsp;</div>
                                                                  <?php echo $form->error("msg"); ?>
                                                                  <input type="hidden" name="status" value="0">
                                                                  <input type="hidden" name="submessage" value="1">

                                                                  <?php include ('_captcha.php');?>

                                                                  <button class="btn btn-success col-md-12" type="submit"><?php echo $lang['MSG_SEND']; ?></button>
                                                                </div>
                                                    </div>




                                                </form>
                                              </div>
                                          </div>
                                        </div>
                                      </div>
                                  </div>
                                </div>


<?php }

    include('footer.inc.php');    // INCLUDE <FOOTER> PART (SKIN RELATED)

?>