<?php
if (!defined('VERSIONCT')) {
    die;
}


if (($config['ENABLE_COMMENTS'] == 1) && ($session->logged_in)) {

$_SESSION['cururl'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

?>

    <div class="row">
        <div class="col-md-12">
<!-- COMMENTS START -->
<h2><?php echo $lang['COMMENT_TITLE']; if (isset($_GET['category'])) { echo ": ".$h3prod." / ".$h3cat; }?></h2>

      <?php if( (isset($_SESSION['comresponse']) && ($_SESSION['comresponse']) == 1) ) { ?>

       <div class="errno">
           <div class="alert alert-success alert-dismissible fadeInRight animated" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
              <?php echo $lang['COMMENT_POSTED'];?>
            </div>
           </div>

      <?php $_SESSION['comresponse'] = 0;}

      else if( (isset($_SESSION['comresponse']) && ($_SESSION['comresponse']) == 2) ) {  ?>

      <div class="errno">
           <div class="alert alert-danger alert-dismissible fadeInRight animated" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
              <?php echo $lang['ERROR'];?>
            </div>
           </div>


      <?php $_SESSION['comresponse'] = 0;}

      else if( (isset($_SESSION['comresponse']) && ($_SESSION['comresponse']) == 3) ) {  ?>

      <div class="errno">
           <div class="alert alert-warning alert-dismissible fadeInRight animated" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
              <?php echo $lang['COMMENT_DELETED'];?>
            </div>
           </div>


      <?php $_SESSION['comresponse'] = 0; }
      else if( (isset($_SESSION['comresponse']) && ($_SESSION['comresponse']) == 4) ) {  ?>

      <div class="errno">
           <div class="alert alert-success alert-dismissible fadeInRight animated" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
              <?php echo $lang['SUCCESS'];?>
            </div>
           </div>


      <?php $_SESSION['comresponse'] = 0; } ?>


      <form class="form-horizontal" role="form" action="admin/process.php" method="POST">
          <div class="form-group">
                      <label for="msg" class="col-md-12 control-label text-left"><?php echo $lang['COMMENT_MESSAGE']; ?></label>
                      <div class="col-md-12">
                        <textarea name="msg" id="msg" class="form-control" maxlength="250" onkeyup="countChar(this)" placeholder="<?php echo $lang['COMMENT_INSTRUCTION']; ?>"></textarea>
                        <div id="charNum">&nbsp;</div>
                        <?php echo $form->error("msg"); ?>
                        <input type="hidden" name="status" value="1">
                        <input type="hidden" name="subcomment" value="1">

                        <?php include ('_captcha.php');?>

                        <button class="btn btn-success col-md-12" type="submit"><?php echo $lang['COMMENT_SEND']; ?></button>
                      </div>
          </div>




      </form>

      <h3><?php echo $lang['COMMENT_LATEST']; ?></h3>
      <hr />


       <?php
        $cmt = $database->showComment($_SESSION['cururl']);

        if (!$cmt) {
          echo $lang['COMMENT_EMPTY'];
        } else {

            echo "<ul class='paging'>";

        foreach ($cmt as $value) {
            $cmt_info = $database->getUserInfo($value['user']);
            echo "<li id='comment".$value['id']."'><div class='media'>";
            echo "<a class='media-left' href='userinfo.php?user=".$cmt_info["username"]."'>";
            echo "<img src='https://www.gravatar.com/avatar/".md5( strtolower( trim($cmt_info["email"] ) ) )."?s=80' class='thumbnail' alt='Gravatar'/></a>";
            echo "<div class='media-body'>";
            echo "<h4 class='media-heading'><a class='media-left' href='userinfo.php?user=".$cmt_info["username"]."'>".$value['user']."</a></h4>";
            echo "<small>".$value['ctime']."</small><p class='note'>";
            /* CONVERTER */
            $stringer = explode(" ", $value['msg']);

            if($stringer[0]) {
                foreach ($stringer as $stringer) {
                    /* YOUTUBE */
                    if (strpos($stringer,"youtube.com") !== false) {
                        $link = explode('=', $stringer);
                        $watch = end($link);
                        echo "<i class='fa fa-video-camera fa-1x'></i><a href='#' data-featherlight='#".$watch."' class='announcer'><img src='//img.youtube.com/vi/".$watch."/hqdefault.jpg' alt='YouTube' title='YouTube' /></a>";
                        echo "<iframe width='720' height='480' src='//www.youtube.com/embed/".$watch."' frameborder='0' id='".$watch."' class='hider' allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>";
                        $replace = $link[0]."=".$link[1];
                        $value['msg'] = str_replace($replace, "",$value['msg'] );
                    }
                    /* END YOUTUBE */
                    /* VIMEO */
                    if (strpos($stringer,"vimeo.com") !== false) {
                        $link = explode('/', $stringer);
                        $watch = end($link);
                        $xml=simplexml_load_file("http://vimeo.com/api/v2/video/".$watch.".xml") or die("Error: Cannot create object");
                        echo "<i class='fa fa-video-camera fa-1x'></i><a href='#' data-featherlight='#".$watch."' class='announcer'><img src='".$xml->video->thumbnail_large."' alt='Vimeo' title='Vimeo' /></a>";
                        echo "<iframe width='720' height='480' src='//player.vimeo.com/video/".$watch."' frameborder='0' id='".$watch."' class='hider' allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>";
                        $replace = $link[0]."/".$link[1]."/".$link[2]."/".$link[3];
                        $value['msg'] = str_replace($replace, "",$value['msg'] );

                    }
                    /* END VIMEO */
                    /* IMAGES (WITH ~ TILDA) */
                    if (strpos($stringer,"~") !== false) {
                        $link = explode('~', $stringer);
                        $watch = end($link);
                        $file_headers = @get_headers($watch);
                        //print_r($file_headers);

                            if($file_headers[0] != 'HTTP/1.1 200 OK') {
                                echo "File does not exist";
                            }
                            else if (!getimagesize($watch)) {
                                echo "File is not a valid image";
                              }

                        else {
                        $replace = "~".$link[1];
                        $value['msg'] = str_replace($replace, "",$value['msg'] );
                        echo "<i class='fa fa-camera-retro fa-1x'></i><a href='".$watch."' data-featherlight='image' class='announcer' title='Image'><img src='".$watch."' width='auto' height='auto' id='".$watch."'/></a>";
                        }
                    }
                    /* END IMAGES */


                }

            }
           echo "<span>".$value['msg']."</span></p>";

           /* END CONVERTER */
            echo "<div class='tools'>";


            $cmt_ider = $value['id'];

                  if(($value['status'] == 1) && ($session->logged_in) ){
                      echo "

                          <form action='admin/process.php' method='POST'>
                              <input type='hidden' name='cmt_notid' value='".$cmt_ider."'>
                              <input type='hidden' name='cmt_status' value='2'>
                              <input type='hidden' name='subcomment' value='3'>
                              <button class='btn btn-info btn-xs' type='submit'>".$lang['COMMENT_REPORT']."</button>
                          </form>

                      ";
                  }


                  if(($value['status'] == 2) && ($session->logged_in) && (!$session->isAdmin())) {
                    echo "<span class='btn btn-primary btn-xs'>".$lang['COMMENT_REPORTED']."</span>";
                    }

                  // If you want to allow users to unflag comments, comment line below
                  if(($value['status'] == 2) && ($session->isAdmin()) ) {
                  echo "

                          <form action='admin/process.php' method='POST'>
                              <input type='hidden' name='cmt_notid' value='".$cmt_ider."'>
                              <input type='hidden' name='cmt_status' value='1'>
                              <input type='hidden' name='subcomment' value='3'>
                              <button class='btn btn-warning btn-xs' type='submit'>".$lang['COMMENT_REPORTED'].": ".$value['reporter']." </button>
                          </form>

                      ";

                  }
              // If you want to allow users to delete their own comments, uncomment line below
              //    if(($session->isAdmin()) || (($session->username) == $cmt_info["username"] )){

              // If you want to allow users to delete their own comments, comment line below
              if(($session->isAdmin())){
                      echo "

                          <form action='admin/process.php' method='POST'>
                              <input type='hidden' name='cmt_delid' value='".$cmt_ider."'>
                              <input type='hidden' name='subcomment' value='2'>
                              <button class='btn btn-danger btn-xs' type='submit'>".$lang['DELETE']."</button>
                          </form>

                      ";
                  }

            echo "</div></div></div></li>";
         }

        }
        ?>
        </ul>

<?php } ?>
</div>
</div>
<!-- COMMENTS END -->