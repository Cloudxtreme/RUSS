<?php
require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL
if((!$session->logged_in) || (!isset($_GET['event'])) || ($config['ENABLE_CALLS'] != 1) ){ ?>

	<script type="text/javascript">window.location.href = "<?php echo $config['WEB_ROOT'].$config['home_page'];?>"</script><meta http-equiv="refresh" content="0; url=<?php echo $config['WEB_ROOT'].$config['home_page'];?>"/>

<?php die; }


include_once('header.inc.php');     // INCLUDE <HEAD> PART (SKIN RELATED)
include_once ('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)
?>
<div class="container">
<div class="row">
<div class="col-md-12 text-center">
<div class="cleaner"></div>
<?php


    if (($_GET['event']) == "off") {
        echo "<h1>".$lang['CALL_MESSAGE_3']."</h1>";
        echo "<hr /><a href='".$config['WEB_ROOT'].$config['home_page']."' class='btn btn-primary'>".$lang['BACK_TO_HOME']."</a><div class='cleaner'></div>";
    }

    else if (($_GET['event']) == "offline") {
        echo "<h1>".$lang['CALL_MESSAGE_2']."</h1>";
        echo "<hr /><a href='".$config['WEB_ROOT'].$config['home_page']."' class='btn btn-primary'>".$lang['BACK_TO_HOME']."</a><div class='cleaner'></div>";
    }

    else if (($_GET['event']) == "busy") {
        echo "<h1>".$lang['CALL_MESSAGE_6']."</h1>";
        echo "<hr /><a class='btn btn-primary' onclick='history.go(-1);'>".$lang['TRY_AGAIN']."</a><div class='cleaner'></div>";
    }

    else if (($_GET['event']) == "end") {
        echo "<h1>".$lang['CALL_MESSAGE_1']."</h1>";
        if (($session->userlevel) > 7) { $_SESSION['hangup'] = 1;
            echo "<hr /><a href='line.php?event=enablecalls' class='btn btn-primary'>".$lang['CALL_MESSAGE_4']."</a><div class='cleaner'></div>";
        } else {
            echo "<hr /><a href='".$config['WEB_ROOT'].$config['home_page']."' class='btn btn-primary'>".$lang['BACK_TO_HOME']."</a><div class='cleaner'></div>";
        }
    }
    else if (($_GET['event']) == "enablecalls") {
        echo "<h1>".$lang['CALLS_ENABLED']."</h1>";
        $_SESSION['hangup'] = 0;
        echo "<hr /><a href='".$config['WEB_ROOT'].$config['home_page']."' class='btn btn-primary'>".$lang['BACK_TO_HOME']."</a><div class='cleaner'></div>";
    }

    else {
        echo "<h1>".$lang['CALL_MESSAGE_5']."</h1>";
        echo "<hr /><a href='".$config['WEB_ROOT'].$config['home_page']."' class='btn btn-primary'>".$lang['BACK_TO_HOME']."</a><div class='cleaner'></div>";
    }
?> </div></div></div> <?php
    include('footer.inc.php');
?>