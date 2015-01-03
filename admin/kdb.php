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
        <div class="col-md-12">
            <div class="positioner"><?php echo $lang['ARTICLES'];?></div>

                <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#addarticle" aria-expanded="false">
                  <?php echo $lang['ARTICLE_ADD'];?>
                </button>

<div id="addarticle" class="collapse" aria-expanded="false">
            <div class="cleansmall"></div>
            <form action="adminprocess.php" method="post" class="form-horizontal" role="form">

            <div class="form-group">
                <label for="category" class="col-md-2 control-label"><?php echo $lang['CATEGORY'];?></label>
                <div class="col-md-10">
                <select name="category" id="category" class="form-control" value="<?php echo $form->value("category"); ?>">

                <?php $cate = $database->showCat('*');
                    foreach ($cate as $value) {
                       echo "<option value='".$value['id']."'>".$value['catprod'].": ".$value['catname']."</option>";
                    }

                ?>
                </select>

                  <?php echo $form->error("category"); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="question" class="col-md-2 control-label"><?php echo $lang['QUESTION'];?></label>
                <div class="col-md-10">
                  <input type="text" name="question" id="question" class="form-control" value="<?php echo $form->value("question"); ?>" required/>
                  <?php echo $form->error("question"); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="answer" class="col-md-2 control-label"><?php echo $lang['ANSWER'];?></label>
                <div class="col-md-10">
                  <textarea name="answer" class="form-control cleditor" required><?php echo $form->value("answer"); ?></textarea>
                  <script>$('.cleditor').trumbowyg();</script>
                  <?php echo $form->error("answer"); ?>
                </div>
            </div>
            <input type="hidden" name="kbadd" value="1" />
            <input type="submit" value="<?php echo $lang['ARTICLE_ADD'];?>" id="submit" class="btn btn-lg btn-success pull-right"/>
            <div class="clearfix"></div>
            <hr />
            </form>
    </div>
        <div class="cleansmall"></div>

        </div>
</div>

<div class="row">

        <div class="col-md-4">

        <?php

          $cats = $database->showCat("*");

                if (isset($_GET['category'])) { $category = $_GET['category'];}
                    else { $category = "*";}

                $kate = $database->showKB($category);

        ?>

        <h3><?php echo $lang['CATEGORY'];?></h3>
            <div class="cleansmall"></div>
                <ul class="paging">
                <li class="noline"><a class='btn btn-primary btn-block btn-xs' href="index.php?id=12"><?php echo $lang['ALL'];?></a></li>

                <?php foreach ($cats as $cats) {
                               echo "<li class='noline'><a class='btn btn-default btn-block btn-xs' href='index.php?id=12&category=".$cats['id']."'>".$cats['catprod']." -> ".$cats['catname']."</a></li>";
                               $categoryname[$cats['id']] = $cats['catname'];
                               $productname[$cats['id']] = $cats['catprod'];
                            } ?>

                </ul>

        </div>

        <div class="col-md-8">

        <?php
           if (!isset($categoryname[$category])) {
               $categoryname[$category] =  $lang['ALL'];
           }
           if (!isset($productname[$category])) {
               $productname[$category] =  $lang['ALL'];
           }
        ?>

        <h3 class="pull-right"><?php echo $lang['ARTICLES'];?>: <?php echo $productname[$category]." -> ".$categoryname[$category];?></h3>
            <div class="cleansmall"></div>

        <?php

        if (!$kate) {
          echo $lang['ARTICLE_ERROR'];
        } else {

            echo "<ul class='paging'>";

        foreach ($kate as $val) {
            echo "<li class='category'>";

            echo "<form action='adminprocess.php' method='POST'>";
            echo "<input type='text' name='question' class='question' value='".$val['question']."' />";

            echo "<select name='category' class='productes' value='".$form->value("category")."'>";

                    foreach ($cate as $value) {
                       echo "<option value='".$value['id']."'";
                       if ($value['id'] == $val['category']) { echo "selected";}
                       echo ">".$value['catprod']." -> ".$value['catname']."</option>";
                    }

            echo "</select>";

            echo "<input type='hidden' name='kbedit' value='1'>
                  <input type='hidden' name='id' value='".$val['id']."'>
                  <button class='btn btn-warning btn-xs righter' type='submit'>".$lang['SUBMIT']."</button>
                  ";
            ?>


                <button type="button" class="btn btn-primary btn-xs righter" data-toggle="collapse" data-target="#edit_<?php echo $val['id'];?>" aria-expanded="true">
                  <?php echo $lang['EDIT'];?>
                </button>

                <div id="edit_<?php echo $val['id'];?>" class="collapse" aria-expanded="false">
                <div class="cleansmall"></div>
                <textarea name="answer" class="cleditor" ><?php echo $val['answer'];?></textarea>
                <script>$('.cleditor').trumbowyg();</script>
                </div>



            <?php
                            echo "</form>";
                            echo "<form action='adminprocess.php' method='POST'>
                              <input type='hidden' name='kb_delid' value='".$val['id']."'>
                              <input type='hidden' name='kbdel' value='1'>
                              <button class='btn btn-danger btn-xs righter' type='submit'>".$lang['DELETE']."</button>
                          </form> ";

            echo "<div class='clearfix'></div></li>";
            }
            echo "</ul><script>$('ul.paging').quickPager();</script>";
            }

            ?>
        </div>
    </div>

</div>