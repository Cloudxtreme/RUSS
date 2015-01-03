<?php

// MOD OF _engine.php TO PREVENT LOOP FOR BANNED USERS

include("admin/include/session.php");
$sql = $database->connection->prepare("SELECT ip FROM ".TBL_BANNED_IP);
    $sql->execute();
    $deny = $sql->fetchAll(PDO::FETCH_COLUMN, 0);
    if (in_array ($_SERVER['REMOTE_ADDR'], $deny)) {
       header("Location: ".$config['WEB_ROOT'].$config['home_page']."404.html");
       exit();
        }
  include_once ('admin/include/language.php');

?>