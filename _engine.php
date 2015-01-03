<?php
    /* THIS FILE MUST BE INCLUDED ON TOP OF ALL PAGES THAT YOU WANT TO PROTECT,
    LIKE THIS: require_once('_engine.php'); */
    if (file_exists('install')) {
        header("Location: install/index.php");
    }
    // START SESSION
    include("admin/include/session.php");



    // CHECK IF VISITOR'S IP IS BANNED
    $sql = $database->connection->prepare("SELECT ip FROM ".TBL_BANNED_IP);
        $sql->execute();
        $deny = $sql->fetchAll(PDO::FETCH_COLUMN, 0);
        if (in_array ($_SERVER['REMOTE_ADDR'], $deny)) {
           header("Location: ".$config['WEB_ROOT']."404.html");
           exit();
            }

    // IF NOT BANNED, INCLUDE LANGUAGE FILE
    include_once ('admin/include/language.php');

    // CHECK IF USERNAME IS BANNED
    global $database;
    $config = $database->getConfigs();
    if($session->isUserlevel(7)){
       header("Location: ".$config['WEB_ROOT']."banned.php");
    }

    // NOT BANNED, GET USEFUL INFO ABOUT USER (ONLY IF LOGGED IN)
    if($session->logged_in){
      $gen_info = $database->getUserInfo($session->username);
      $cmy = $database->showMessage($session->username);
      $ctm = $database->showMessageTM($session->username);
      }
?>