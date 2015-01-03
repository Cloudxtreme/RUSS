<?php
if (!defined('VERSIONCT')) {
    die;
}?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
        <?php if(((isset($_GET['success'])) && ($_GET['success'] == 1))){
               echo "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button>".$lang['SAVE_SUCCESS']."</div>";}

            else if (($form->num_errors > 0) || ((isset($_GET['success'])) && ($_GET['success'] != 1))){
            echo "<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button>".$form->num_errors." ".$lang['ERRORS_FOUND']."</div>";}

            ?>
        </div>
        <div class="col-md-5">
            <div class="positioner"><?php echo $lang['PRODUCTS'];?></div>



            <form action="adminprocess.php" method="post" class="form-horizontal" role="form">

            <div class="form-group">
                <label for="prod" class="col-md-4 control-label"><?php echo $lang['NAME'];?></label>
                <div class="col-md-8">
                  <input type="text" name="prod" id="prod" class="form-control" value="<?php echo $form->value("prod"); ?>" required/>
                  <?php echo $form->error("prod"); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-md-4 control-label"><?php echo $lang['DESC'];?></label>
                <div class="col-md-8">
                  <input type="text" name="description" id="description" class="form-control" value="<?php echo $form->value("description"); ?>" required/>
                  <?php echo $form->error("description"); ?>
                </div>
            </div>
            <input type="hidden" name="prodadd" value="1" />
            <input type="submit" value="<?php echo $lang['PRODUCT_ADD'];?>" id="submit" class="btn btn-lg btn-success pull-right"/>

            </form>
            <div class="cleaner"></div>

        </div>

        <div class="col-md-7">

        <?php

        $prode = $database->showProd();

        if (!$prode) {
          echo $lang['PRODUCT_ERROR'];
        } else {

            echo "<ul class='paging'>";

        foreach ($prode as $value) {
            echo "<li class='category'>";

            echo "<form action='adminprocess.php' method='POST'>";
            echo "<input type='text' name='prod' class='prod' value='".$value['prod']."' disabled /><br /><input type='text' name='description' value='".$value['description']."' /><br />";
            echo "<input type='hidden' name='prodedit' value='1'>
                  <input type='hidden' name='id' value='".$value['id']."'>
                  <button class='btn btn-warning btn-xs righter' type='submit'>".$lang['SUBMIT']."</button>
                  </form>";
                        echo "<form action='adminprocess.php' method='POST'>
                              <input type='hidden' name='prod_delid' value='".$value['id']."'>
                              <input type='hidden' name='proddel' value='1'>
                              <button class='btn btn-danger btn-xs righter' type='submit'>".$lang['DELETE']."</button>
                          </form> ";
            echo "<div class='clearfix'></div></li>";
            }
            echo "</ul>";
            }
            ?>
        </div>
    </div>
</div>