<?php
    require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL

    include('header.inc.php');     // INCLUDE <HEAD> PART (SKIN RELATED)
    include ('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)
    include ('messages.inc.php');


  if (!isset($_GET['user'])) {  ?><script type="text/javascript">window.location.href = "<?php echo $config['WEB_ROOT'].$config['home_page'];?>"</script><meta http-equiv="refresh" content="0; url=<?php echo $config['WEB_ROOT'].$config['home_page'];?>"/> <?php }

    /* Requested Username error checking */
    $req_user = trim($_GET['user']);

    if(!$req_user || strlen($req_user) == 0 ||
       !preg_match("/^[a-z0-9]([0-9a-z_-\s])+$/i", $req_user) ||
       !$database->usernameTaken($req_user)){ ?>
       <script type="text/javascript">window.location.href = "<?php echo $config['WEB_ROOT'].$config['home_page'];?>"</script><meta http-equiv="refresh" content="0; url=<?php echo $config['WEB_ROOT'].$config['home_page'];?>"/>
    <?php }

    /* ALL OK Continue output */
    $req_user_info = $database->getUserInfo($req_user);

?>

    <div class="jumbotron imager">
      <div class="container">
      <div class="col-md-3">
        <img src="<?php echo 'https://www.gravatar.com/avatar/'.md5( strtolower( trim( $req_user_info["email"] ) ) ).'?s=250';?>" class="gimage" alt="Gravatar"/>
        </div>
        <div class="col-md-9">
<?php

      /* Logged in user viewing own account */
      if(strcmp($session->username,$req_user) == 0){
         echo "<h1>".$lang['MY_ACCOUNT']."</h1>";
      }
      /* Visitor (not viewing own account) */
      else{
         echo "<h1><strong>".$req_user_info['username']."</strong> ".$lang['INFO']."</h1>";
      }
        /* Username */
        echo "<h4>".$lang['USERNAME'].": <strong>".$req_user_info['username']."</strong></h4>";

        /* Email */
        echo "<h4>".$lang['EMAIL'].": <strong>".$req_user_info['email']."</strong></h4>";

        /* Regged since */
        echo "<h4>".$lang['REGGED'].": <strong>".date('d-m-Y', $req_user_info['regdate'])."</strong></h4><br />";


        /* Level */
        echo "<img src='http://".$_SERVER['HTTP_HOST'].strstr($_SERVER['REQUEST_URI'], 'userinfo.php?', true)."admin/img/".$req_user_info['userlevel'].".png' alt='' class='userlevel'/>";

        /* If logged in user viewing own account, give link to edit */
        if(strcmp($session->username,$req_user) == 0){
           echo "<a href='useredit.php' class='btn btn-primary'>".$lang['EDIT']."</a> ";
        }

        /* If online indicator is set to ON */

            if($req_user_info['online'] == "Y") {

            $forwho = $req_user_info["username"];
            $stmt = $database->connection->query("SELECT username FROM ".TBL_ACTIVE_USERS." WHERE username LIKE '%$forwho%'");
            $num_rows = $stmt->rowCount();

            if(!$stmt || ($num_rows == 0)){
                echo "<a href='#' class='btn btn-danger'>".$lang['OFFLINE']."</a>";
            }
            else {
                echo "<a href='#' class='btn btn-success'>".$lang['ONLINE']."</a>";
            }
            }
        /**
         * Note: when you add your own fields to the users table
         * to hold more information, like homepage, location, etc.
         * they can be easily accessed by the user info array.
         *
         * $session->user_info['location']; (for logged in users)
         *
         * $req_user_info['location']; (for any user)
         */


?>
        </div>

      </div>
     <img src="<?php echo 'https://www.gravatar.com/avatar/'.md5( strtolower( trim( $req_user_info["email"] ) ) ).'?s=250';?>" class="blur" alt="backimage"/>

    </div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
<?php
/* If privacy indicator is set to ON */
if (($req_user_info['privacy'] == "Y")){

        if (($req_user_info['name']) || ($req_user_info['surname'])) { ?>

            <h2><?php echo $req_user_info['name']." ".$req_user_info['surname'];?></h2>

        <?php }

        if ($req_user_info['phone']) { ?>

            <h5><?php echo $lang['PHONE'].": <strong>".$req_user_info['phone'];?></strong></h5>

        <?php } ?>

        <br />

        <?php

        if ($req_user_info['twitter']) { ?>

            <a href="https://twitter.com/<?php echo $req_user_info['twitter'];?>" target="_blank"><img src="admin/img/twitter.png" alt="Twitter"  width="20px" height="20px"/></a>

        <?php }

        if ($req_user_info['facebook']) { ?>

            <a href="<?php echo $req_user_info['facebook'];?>" target="_blank"><img src="admin/img/facebook.png" alt="Facebook"  width="20px" height="20px"/></a>

        <?php }

        if ($req_user_info['google']) { ?>

            <a href="<?php echo $req_user_info['google'];?>" target="_blank"><img src="admin/img/google-plus.png" alt="Google Plus"  width="20px" height="20px"/></a>

        <?php }

        if ($req_user_info['linkedin']) { ?>

            <a href="<?php echo $req_user_info['linkedin'];?>" target="_blank"><img src="admin/img/linkedin.png" alt="LinkedIn"  width="20px" height="20px"/></a>

        <?php }

        if ($req_user_info['website']) { ?>

            <a href="<?php echo $req_user_info['website'];?>" target="_blank"><img src="admin/img/website.png" alt="Website"  width="20px" height="20px"/></a>

        <?php }

        if ($req_user_info['icq']) { ?>

            <a href="icq:send_message?uin=<?php echo $req_user_info['icq'];?>" target="_blank"><img src="admin/img/icq.png" alt="ICQ"  width="20px" height="20px"/></a>

        <?php }

        if ($req_user_info['skype']) { ?>

            <a href="skype:<?php echo $req_user_info['skype'];?>?chat" target="_blank"><img src="admin/img/skype.png" alt="Skype"  width="20px" height="20px"/></a>

        <?php }

        if ($req_user_info['gtalk']) { ?>

            <a href="gtalk:chat?jid=<?php echo $req_user_info['gtalk'];?>" target="_blank"><img src="admin/img/gtalk.png" alt="Google Talk"  width="20px" height="20px"/></a>

        <?php }

}
?>
        </div>
    </div>
</div>
<br />
<?php
/* If comments on profile page are set to ON */

$gen_info = $database->getUserInfo($req_user);

if ($gen_info['showcomments'] == "Y") { ?>
    <div class="container">

      <div class="row">
        <div class="col-md-12">


       <?php include ('_comments.php'); ?>

                 </div>

        </div>

</div>
<?php }

    include('footer.inc.php');    // INCLUDE <FOOTER> PART (SKIN RELATED)
?>