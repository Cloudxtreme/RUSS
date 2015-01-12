<?php
require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL
include_once('header.inc.php');     // INCLUDE <HEAD> PART (SKIN RELATED)
include_once ('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)
include ('messages.inc.php');
?>



    <div class="jumbotron">
      <div class="container">
      <?php if(!$session->logged_in){ ?>
        <h1><?php echo $config['SITE_NAME'];?></h1>
        <h3><?php echo $config['SITE_DESC'];?></h3>
        <p><?php echo $lang['NOT_LOGGED_IN_1'];?> <a href="registration.php"><?php echo $lang['REGISTER'];?></a> <?php echo $lang['OR'];?> <a href="login.php"><?php echo $lang['LOGIN'];?></a> <?php echo $lang['NOT_LOGGED_IN_2'];?></p>
      <br />
      <a href="kdb.php" class="btn btn-lg btn-warning"><?php echo $lang['OR'];?> <?php echo $lang['SEARCH']." ".$lang['KB'];?></a>

      <?php } else { ?>

        <h1>Hello, <?php echo $session->username;?>!</h1>
        <h3><?php echo $lang['WHAT_LIKE'];?></h3>
        <div class="cleaner"></div>
        <h3 class="pull-left">&nbsp;</h3>
        <div class="pull-right">

            <a href="kdb.php" class="btn btn-warning"><?php echo $lang['SEARCH']." ".$lang['KB'];?></a>
            <a href="ticket.php" class="btn btn-info"><?php echo $lang['VIEW'];?> <?php echo $lang['YOUR_TICKET'];?></a>
        </div>


        <div id="openticket" >


            <form action="index.php" method="post" class="form-horizontal" role="form">

            <div class="form-group">
                <div class="col-md-12">

                <?php

                $prods = $database->showProd('*');

                echo "<select name='product' class='form-control' value='";

                if (isset($_POST['product'])) { echo $_POST['product']; } else { echo '';}

                echo "' onchange='if(this.value != 0) { this.form.submit(); }'>";

                echo "<option value=''>".$lang['PLEASE_SELECT']."</option>";

                    foreach ($prods as $prods) {
                       echo "<option value='".$prods['prod']."'";

                       if ((isset($_POST['product'])) && (($prods['prod']) == ($_POST['product']))) { echo "selected";}

                       echo ">".$lang['PRODUCTS'].": ".$prods['prod']." (".$prods['description'].")</option>";
                    }
                echo "</select>";
                ?>
                </div>
            </div>

            </form>
            </div>

            <form action="admin/process.php" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    if (isset($_POST['product'])) {
                    $cate = $database->showCat($_POST['product']);

                    echo "<select name='category' class='form-control' value='".$form->value("category")."'>";

                        foreach ($cate as $value) {
                           echo "<option value='".$value['id']."'>".$lang['CATEGORY'].": ".$value['catname']."</option>";
                        }
                    echo "</select>";
                    }
                    ?>

                </div>
            </div>

            <?php if (isset($_POST['product'])) {?>

            <div class="form-group">
                <div class="col-md-12">
                  <input type="text" name="subject" id="subject" class="form-control" value="<?php echo $form->value("subject"); ?>" required placeholder="<?php echo $lang['QUESTION'];?>"/>
                  <?php echo $form->error("subject"); ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                  <textarea name="report" id="report" class="form-control cleditor text-left" required placeholder="<?php echo $lang['DESC'];?>"><?php echo $form->value("report"); ?></textarea>
                    <script>$('.cleditor').trumbowyg();</script>
                  <?php echo $form->error("report"); ?>
                </div>
            </div>

            <?php


            ?>

            <?php include ('_captcha.php');?>

            <div class="clearfix"></div>

            <input type="hidden" name="tid" value="<?php echo substr(sha1($session->username), 0, 6).'-'.substr(time(), -6);?>"/>
            <input type="hidden" name="status" value="<?php if (($session->userlevel) < 8 ) { echo "1"; } else { echo "2";}?>"/>
            <input type="hidden" name="assigned" value="0"/>
            <input type="hidden" name="tech" value="<?php echo $lang['LEVEL_0'];?>"/>
            <input type="hidden" name="techmail" value="<?php echo $config['EMAIL_FROM_ADDR'];?>"/>
            <input type="hidden" name="notes" value="none"/>
            <input type="hidden" name="owner" value="<?php echo $session->username;?>"/>
            <input type="hidden" name="ownermail" value="<?php echo $session->userinfo['email'];?>"/>
            <input type="hidden" name="rating" value="0"/>
            <input type="hidden" name="optic" value="1" />
            <input type="submit" value="<?php echo $lang['OPEN_TICKET'];?>" id="submit" class="btn btn-lg btn-success btn-block"/>


            </form>

        </div>

      <?php } } ?>

      </div>
    </div>

<?php include('footer.inc.php');    // INCLUDE <FOOTER> PART (SKIN RELATED) ?>