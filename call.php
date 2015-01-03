<?php
    require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL

    include('header.inc.php');     // INCLUDE <HEAD> PART

    if (($session->userlevel) < 8) {
    include ('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)
    }
    include ('_call.php');

    if (($session->userlevel) < 8) {
    include('footer.inc.php');    // INCLUDE <FOOTER> PART (SKIN RELATED)
    }
?>