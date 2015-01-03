<?php
    require_once('_engine_banned.php');

    include('header.inc.php');              // INCLUDE <HEAD> PART
    include ('mainmenu.inc.php');           // INCLUDE MAIN MENU
    include ('messages.inc.php');
?>

<div class="container">
    <div class="row">
       <div class="col-md-12">
       <div class="cleaner"></div>
        <h1><?php echo $lang['YOURE_BANNED'];?></h1><hr />
        </div>
    </div>

</div>
<?php include('footer.inc.php');    // INCLUDE <FOOTER> PART ?>