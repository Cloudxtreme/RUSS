<?php
if (!defined('VERSIONCT')) {
    die;
}


                  if ($config['ENABLE_CAPTCHA'] == 1){

                  if(file_exists("admin/include/fisher.txt")){
                      //Read the file
                      $f_contents = file("admin/include/fisher.txt");
                      //Extract a word and replace CrLf by empty
                      $word = str_replace("%0D%0A", "", trim($f_contents[array_rand($f_contents)]));
                      //Length of the word?
                      $len = strlen( utf8_decode( $word ) );
                      //Random number
                      $nb = rand(1, $len);
                      //Take the letter
                      //(-1 because function substr start with 0)
                      $reply = substr($word, $nb-1, 1);
                      //Place it in a session
                      $_SESSION["security"] = $reply;
                      } ?>

                      <div class="form-group">
                      <label for="security" class="col-sm-10 control-label"><?php echo $lang['CAPTCHA_Q1'];?> <strong><?php echo $nb;?>.</strong> <?php echo $lang['CAPTCHA_Q2'];?>: <strong><?php echo $word;?></strong></label>
                      <div class="col-sm-2">
                        <input type="text" name="security" id="security" class="form-control" value="" required/>
                        <div style='display:none; visibility:hidden;'><input type='text' name='killbill' maxlength='50' /><input type='hidden' name='hitcap' value='1' /></div>
                      </div>
                    </div>


<?php }?>