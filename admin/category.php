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
            <div class="positioner"><?php echo $lang['CATEGORIES'];?></div>



            <form action="adminprocess.php" method="post" class="form-horizontal" role="form">

            <div class="form-group">
                <label for="catprod" class="col-md-4 control-label"><?php echo $lang['PRODUCTS'];?></label>
                <div class="col-md-8">
                <select name="catprod" id="catprod" class="form-control" value="<?php echo $form->value("catprod"); ?>">
                <option value="All"><?php echo $lang['ALL'];?></option>
                <?php $prode = $database->showProd();
                    foreach ($prode as $value) {
                       echo "<option value='".$value['prod']."'>".$value['prod']."</option>";
                    }

                ?>
                </select>

                  <?php echo $form->error("catprod"); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="catname" class="col-md-4 control-label"><?php echo $lang['NAME'];?></label>
                <div class="col-md-8">
                  <input type="text" name="catname" id="catname" class="form-control" value="<?php echo $form->value("catname"); ?>" required/>
                  <?php echo $form->error("catname"); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="catdesc" class="col-md-4 control-label"><?php echo $lang['DESC'];?></label>
                <div class="col-md-8">
                  <input type="text" name="catdesc" id="catdesc" class="form-control" value="<?php echo $form->value("catdesc"); ?>" required/>
                  <?php echo $form->error("catdesc"); ?>
                </div>
            </div>
            <input type="hidden" name="catadd" value="1" />
            <input type="submit" value="<?php echo $lang['CATEGORY_ADD'];?>" id="submit" class="btn btn-lg btn-success pull-right"/>

            </form>
            <div class="cleaner"></div>

        </div>

        <div class="col-md-7">

        <?php

        $cate = $database->showCat('*');

        if (!$cate) {
          echo $lang['ERROR_CATNOTFOUND'];
        } else {

            echo "<ul class='paging'>";

        foreach ($cate as $value) {
            echo "<li class='category'>";

            echo "<form action='adminprocess.php' method='POST'>";
            echo "<input type='text' name='catname' class='catname' value='".$value['catname']."' />";
            echo "<br /><input type='text' name='catdesc' value='".$value['catdesc']."' /><br />";
            echo "

            <select name='catprod' class='productes' value='".$form->value("catprod")."'>
                <option value='All'>".$lang['ALL']."</option>";


                    foreach ($prode as $val) {
                       echo "<option value='".$val['prod']."'";
                       if ($val['prod'] == $value['catprod']) { echo "selected";}
                       echo ">".$val['prod']."</option>";
                    }

            echo "
                </select>


            ";


            echo "<input type='hidden' name='catedit' value='1'>
                  <input type='hidden' name='id' value='".$value['id']."'>
                  <button class='btn btn-warning btn-xs righter' type='submit'>".$lang['SUBMIT']."</button>
                  </form>";
                        echo "<form action='adminprocess.php' method='POST'>
                              <input type='hidden' name='cate_delid' value='".$value['id']."'>
                              <input type='hidden' name='catdel' value='1'>
                              <button class='btn btn-danger btn-xs righter' type='submit'>".$lang['DELETE']."</button>
                          </form> ";
            echo "<a href='index.php?id=12&category=".$value['id']."'class='btn btn-primary btn-xs righter'>".$lang['ARTICLES']."</a>";

            echo "<div class='clearfix'></div></li>";
            }
            echo "</ul>";
            }

            $prode = $database->showProd();
            ?>
        </div>
    </div>
</div>