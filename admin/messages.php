<?php
if (!defined('VERSIONCT')) {
    die;
}?>
<div class="container-fluid">

            <div class="positioner"><?php echo $lang['MSG_TITLE'];?></div>



<?php if (($session->logged_in)) {

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
              <?php echo $lang['ERROR'];?>
            </div>
           </div>


      <?php }

     


      ?>




                            <div class="row">
                                <div class="col-md-12">


  <div role="tabpanel" class="panelche">


        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">

          <li role="presentation" class="active"><a href="#inbox" role="tab" data-toggle="tab"><?php echo $lang['MSG_REPORTED'];?>&nbsp;&nbsp;&nbsp;&nbsp;
          <?php if ($_SESSION['rep_count'] > 0) { ?>
          <span class="badge"><?php echo $_SESSION['rep_count'];?></span>
          <?php } ?></a></li>

          <li role="presentation"><a href="#outbox" role="tab" data-toggle="tab"><?php echo $lang['OUTBOX'];?>&nbsp;&nbsp;&nbsp;&nbsp;</a></li>

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
                                      echo"><div class='media'>";
                                      echo "<a class='media-left'>";
                                      echo "<img src='https://www.gravatar.com/avatar/".md5( strtolower( trim($cmt_info["email"] ) ) )."?s=30' class='thumbnail' alt='Gravatar'/></a>";
                                      echo "<div class='media-body'>";

                                      echo "<h4 class='media-heading'><span>".$lang['FROM'].": <a target='_blank' href='../userinfo.php?user=".$value['user']."'>".$value['user']."</a> ".$lang['TO'].": <a target='_blank' href='../userinfo.php?user=".$value['reporter']."'>".$value['reporter']."</a></h4>";
                                        }
                                      echo "<small>".$value['ctime']."</small>";
                                      if(($value['status'] == 2) && ($session->logged_in) ){

                                      echo "<p>".$value['msg']."</p>";

                                      echo "<div class='tools'>";

                                      $cmt_ider = $value['id'];




                  if(($session->isAdmin())){
                      echo "

                          <form action='adminprocess.php' method='POST'>
                              <input type='hidden' name='msg_delid' value='".$cmt_ider."'>
                              <input type='hidden' name='submessage' value='2'>
                              <button class='btn btn-danger btn-xs' type='submit'><span class='glyphicon glyphicon-ban-circle'></span>".$lang['DELETE']."</button>
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

                                      echo "</div></div></div></li>";
                                   }

                                  }
                                  ?>
                                  </ul>

                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="newmsg">

                                                <form class="form-horizontal" role="form" action="adminprocess.php" method="POST">

                                                    <div class="form-group">
                                                                <label for="msg" class="col-md-12 control-label text-left"><?php echo $lang['MSG_MESSAGE']; ?></label>
                                                                <div class="cleansmall"></div>
                                                                <div class="col-md-12">
                                                                  <textarea name="msg" id="msg" class="form-control" maxlength="250" onkeyup="countChar(this)" required></textarea>
                                                                  <div id="charNum">&nbsp;</div>
                                                                  <?php echo $form->error("msg"); ?>

                                                                  <input type="hidden" name="submessage" value="1">
                                                                  <input type="hidden" name="user" id="user" value="<?php echo $config['SITE_NAME'];?>" />
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


<?php } ?>
        <script>$("ul.paging").quickPager();</script>
