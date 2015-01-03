<?php
if (!defined('VERSIONCT')) {
    die;
}?>

<div class="sidebar">
              <div class="userinfo">

                <?php echo "<img src='http://www.gravatar.com/avatar/".md5( strtolower( trim( $req_user_info['email'] ) ) )."?s=80' class='img-rounded' alt='' /> ";?>

                <h3><?php echo $session->username;?></h3>
                <p><?php echo $lang['REGISTERED'].":<strong> ".date("j M Y", $session->userinfo['regdate']);?></strong></p>
                <p><?php echo $lang['LEVEL'].":<strong> ".$session->userlevel;?></strong></p>

              </div>

              <div class="search-group">
                <input type="text" class="search form-control" id="searchid" placeholder="<?php echo $lang['SEARCH']; ?> <?php echo $lang['USERS']; ?>">
                <div id="result"></div>
              </div>

              <ul class="quicklinks">
                              <?php if ((countFolder('cache/') > 3) || (countFolder('backup/') > 3) || ($_SESSION['cmt_count'] > 0) || ($_SESSION['acc_count'] > 0)) { ?>

                        <?php if(countFolder('cache/') > 3) { ?>
                             <li><a href="index.php?id=1&cc=1"><span class="glyphicon glyphicon-fire text-warning flash animated"></span> <?php echo countFolder('cache/')." ".$lang['WARN_CACHE'];?> </a></li>
                        <?php } ?>
                        <?php if(countFolder('backup/') > 3) { ?>
                             <li><a href="index.php?id=7"><span class="glyphicon glyphicon-fire text-warning flash animated"></span> <?php echo countFolder('backup/')." ".$lang['WARN_BACKUP'];?> </a></li>
                        <?php } ?>
                        <?php if($_SESSION['cmt_count'] > 0) { ?>
                             <li><a href="index.php?id=10"><span class="glyphicon glyphicon-fire text-warning flash animated"></span> <?php echo $_SESSION['cmt_count']." ".$lang['WARN_COMMENT'];?> </a></li>
                        <?php } ?>
                        <?php if($_SESSION['rep_count'] > 0) { ?>
                             <li><a href="index.php?id=8"><span class="glyphicon glyphicon-fire text-warning flash animated"></span> <?php echo $_SESSION['rep_count']." ".$lang['MSG_REPORTED'];?> </a></li>
                        <?php } ?>
                        <?php if($_SESSION['acc_count'] > 0) { ?>
                             <li><a href="index.php?id=3"><span class="glyphicon glyphicon-fire text-warning flash animated"></span> <?php echo $_SESSION['acc_count']." ".$lang['WARN_USERS'];?> </a></li>
                        <?php } ?>



                <?php } ?>

                <li><a href="index.php?id=6&usertoedit=<?php echo $session->username;?>" class="text-info"><span class="glyphicon glyphicon-list"></span> <?php echo $lang['EDIT']; ?> <?php echo $lang['ACCOUNT']; ?></a></li>
                <li><a href="../admin/process.php" class="text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> <?php echo $lang['LOGOUT']; ?></a></li>
               </ul>


</div>
