<?php
if (!defined('VERSIONCT')) {
    die;
}?>
<?php
if (isset($_GET['cl'])) {
$clearbck = $_GET['cl'];
if (($clearbck == "1"))  {

    $files = glob('backup/*'); // get all file names
        foreach($files as $file){ // iterate files
          if(is_file($file))
            unlink($file); // delete file
        }
        $clearbck = 0;
        ?>
        <script>
        $(document).ready(function(){
            $('#backclear').modal()
        });
        </script>

<?php
} else if ($clearbck == "2") { ?>
        <script>
        $(document).ready(function(){
            $('#backupdone').modal()
        });
        </script>
<?php
$clearbck = 0;}
}
?>


<div class="container-fluid">
<div class="positioner"><?php echo $lang['BACKUP']; ?></div>
<div class="col-sm-12">
<br />
<?php
if (isset($_SESSION['backuperror'])){
echo $_SESSION['backuperror'];
}
?>
<div class="backups">
<?php

$texts = glob("backup/" . "*");

foreach($texts as $text)
{ ?>

<div>
  <i class="fa fa-folder-open fa-5x"></i>
  <p><?php echo substr($text, 20);?></p>
  <a class="btn btn-warning" href="<?php echo $text;?>"><?php echo $lang['DOWNLOAD']; ?></a>
</div>


<?php

}
?>
</div>
<div class="cleansmall"></div>
<div class="pull-right"><a class="btn btn-info" href="include/backup.php"><?php echo $lang['BACKUP']; ?></a> <a href="index.php?id=7&cl=1" class="btn btn-danger"><?php echo $lang['CLEAR']; ?> <?php echo $lang['ALL']; ?></a></div>
</div>
</div>