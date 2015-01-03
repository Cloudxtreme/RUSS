<?php
if (!defined('VERSIONCT')) {
    die;
}

                  if ($handle = opendir('admin/include/lang')) {
                      while (false !== ($entry = readdir($handle))) {
                          if ($entry != "." && $entry != "..") {
                            $entry = substr($entry, 0, -4);
                             ?>
                            <li <?php if ((isset($langid)) && ($langid == $entry)) { echo "class='active'"; } ?>><a href="index.php?lang=<?php echo $entry;?>"><?php echo $entry;?></a></li>
                        <?php  }
                      }
                      closedir($handle);
                  }
                  ?>