<?php
if (!defined('VERSIONCT')) {
    die;
}?>
<?php

if (isset($_GET['cc'])) {
$clearcc = $_GET['cc'];


      if (($clearcc == "1"))  {
      $files = glob('cache/*'); // get all file names
              foreach($files as $file){ // iterate files
                if(is_file($file))
                  unlink($file); // delete file
              } ?>
              <script>
                $(document).ready(function(){
                    $('#cacheclear').modal()
                });
                </script>
              <?php
      }
}
?>