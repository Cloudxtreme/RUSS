<?php
if (!defined('VERSIONCT')) {
    die;
}?>
<div class="container-fluid">


            <div class="positioner"><?php echo $lang['COMMENT_REPORTED'];?></div>




    <div class="row">
        <div class="col-md-12">
        <div class="cleaner"></div>

<?php
        if (!$cmt) {
          echo $lang['COMMENT_EMPTY'];
        } else {

            echo "<ul class='paging'>";

        foreach ($cmt as $value) {
            $cmt_info = $database->getUserInfo($value['user']);
            echo "<li id='comment".$value['id']."'><div class='media'>";
            echo "<a class='media-left' target='_blank' href='../userinfo.php?user=".$cmt_info["username"]."'>";
            echo "<img src='https://www.gravatar.com/avatar/".md5( strtolower( trim($cmt_info["email"] ) ) )."?s=80' class='thumbnail' alt='Gravatar'/></a>";
            echo "<div class='media-body'>";
            echo "<h4 class='media-heading'><a target='_blank' class='media-left' href='../userinfo.php?user=".$cmt_info["username"]."'>".$value['user']."</a></h4>";
            echo "<small>".$value['ctime']."</small>";
            echo "<p>".$value['msg']."</p>";
            echo "<div class='tools'>";
            echo "<a href='".$value['url']."' class='btn btn-primary btn-xs' target='_blank'>URL</a>";


            $cmt_ider = $value['id'];

                  if(($session->isAdmin()) || (($session->username) == $cmt_info["username"] )){

                      echo "

                          <form action='adminprocess.php' method='POST'>
                              <input type='hidden' name='cmt_delid' value='".$cmt_ider."'>
                              <input type='hidden' name='subcomment' value='2'>
                              <button class='btn btn-danger btn-xs' type='submit'>".$lang['DELETE']."</button>
                          </form>

                      ";
                  }

                  if(($value['status'] == 2) && ($session->isAdmin()) ) {

                  echo "

                          <form action='adminprocess.php' method='POST'>
                              <input type='hidden' name='cmt_notid' value='".$cmt_ider."'>
                              <input type='hidden' name='cmt_status' value='1'>
                              <input type='hidden' name='subcomment' value='3'>
                              <button class='btn btn-warning btn-xs' type='submit'>".$lang['COMMENT_OK']." (".$value['reporter'].")</button>
                          </form>

                      ";

                  }

            echo "</div></div></div></li>";
         }

        }
        ?>
        </ul>
        <script>$("ul.paging").quickPager();</script> 
        </div>
    </div>

</div>