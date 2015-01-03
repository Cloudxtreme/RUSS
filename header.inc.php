<?php
if (!defined('VERSIONCT')) {
    die;
}?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>

    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="admin/img/favicon.png" />
        <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
        <link rel="icon" href="./favicon.ico" type="image/x-icon">

        <title>
        <?php echo $config['SITE_NAME']; ?>
        </title>

        <meta name="description" content="<?php echo $config['SITE_DESC']; ?>">
        <link href="admin/include/cache.php?css=frontcss" rel="stylesheet">
	    <script async src="admin/include/cache.php?js=library" charset="utf-8"></script>


        </head>
        <body>