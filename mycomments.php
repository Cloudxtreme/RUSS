<?php
    require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL

    include('header.inc.php');     // INCLUDE <HEAD> PART
    include ('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)

if(!$session->logged_in){ ?>

	<script type="text/javascript">window.location.href = "<?php echo $config['WEB_ROOT'].$config['home_page'];?>"</script><meta http-equiv="refresh" content="0; url=<?php echo $config['WEB_ROOT'].$config['home_page'];?>"/>


<?php die; }






                        $cmt = $database->showCommentUser($session->username);

                          if (!$cmt) {  ?>

                          <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1><?php echo $lang['COMMENT_TITLE'];?></h1>
                                    <hr />
                                    <p><?php echo $lang['COMMENT_EMPTY'];?></p>
                                </div>
                            </div>
                          </div>
                          <?php
                          } else { ?>

                          <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1><?php echo $lang['COMMENT_TITLE'];?></h1>
                                    <hr />
                              <?php

                              echo "<ul class='paging'>";

                                  foreach ($cmt as $value) {
                                      $cmt_info = $database->getUserInfo($value['user']);
                                      echo "<li id='comment".$value['id']."'><div class='media'>";
                                      echo "<a class='media-left' href='userinfo.php?user=".$cmt_info["username"]."'>";
                                      echo "<img src='https://www.gravatar.com/avatar/".md5( strtolower( trim($cmt_info["email"] ) ) )."?s=80' class='thumbnail' alt='Gravatar'/></a>";
                                      echo "<div class='media-body'>";
                                      echo "<h4 class='media-heading'><a class='media-left' href='userinfo.php?user=".$cmt_info["username"]."'>".$value['user']."</a></h4>";
                                      echo "<small>".$value['ctime']."</small>";
                                      echo "<p>".$value['msg']."</p>";
                                      echo "<div class='tools'>";
                                      echo "<a href='".$value['url']."' class='btn btn-primary btn-xs' target='_blank'>URL</a>&nbsp;";

                                       $cmt_ider = $value['id'];

             /*USER CAN FLAG / UNFLAG OWN COMMENTS

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

                  }  */
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
                                </div>
                            </div>
                          </div>

<?php
    include('footer.inc.php');    // INCLUDE <FOOTER> PART (SKIN RELATED)

?>