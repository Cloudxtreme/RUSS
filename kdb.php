<?php
    require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL
    include('header.inc.php');     // INCLUDE <HEAD> PART (SKIN RELATED)
    include ('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)
    include ('messages.inc.php');
?>








<div class="container tickets">
    <div class="row">
       <div class="col-md-12">


       <?php if (isset($_GET['ID'])) {
           echo "<h1 class='pull-right dotter'>".$lang['ARTICLE'].": ".$_GET['ID']."</h1><br /><hr />";
           $kdbID = $_GET['ID'];
       } else if (isset($_POST['ID'])) {
           echo "<h1 class='pull-right dotter'>".$lang['ARTICLE'].": ".$_POST['ID']."</h1><br /><hr />";
           $kdbID = $_POST['ID'];
       }
       else {       ?>
       </h1><div class="cleaner"></div>

             <!-- SEARCH START -->
            <form action="kdb.php" method="post" class="form-horizontal" role="form">

                <div class="input-group">
                  <input type="text" name="srch" id="srch" class="form-control" value="<?php echo $form->value("srch"); ?>" />
                  <span class="input-group-btn">
                    <input type="submit" value="<?php echo $lang['SEARCH']." ".$lang['KB'];?>" id="submit" class="btn btn-warning"/>
                  </span>
                </div>

                <div class="cleansmall"></div>
            </form>

            <?php if (isset($_POST['srch'])) {?>



                        <?php

                        $srcher = $database->searcher($_POST['srch']);

                            ?> <h4><?php echo $lang['SEARCH_RETURN']." ".$_SESSION['search']." ".$lang['SEARCH_RESULTS'].": <strong>".$_POST['srch']."</strong>";?></h4>




                <?php
                if ($_SESSION['search'] > 0) {

                ?><ul class="paging articlesshow"><?php
                foreach ($srcher as $srcher) { ?>

                        <li class="animated fadeInRight">
                            <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#sshow<?php echo $srcher['id'];?>" aria-expanded="false">
                            <?php

                            $sctg = $database->showCat("*");
                            foreach ($sctg as $sctg) {
                                if (($srcher['category'] == $sctg['id'])) {
                                echo "<span class='text-default'><strong>".$srcher['question']."</strong></span><span class='pull-right text-warning'>".$sctg['catprod']." / ".$sctg['catname']."</span>";
                            }
                            }
                            ?>
                            </button>

                            <div id="sshow<?php echo $srcher['id'];?>" class="collapse faq" aria-expanded="false">
                                <?php echo $srcher['answer'];?>

                            </div>
                        </li>


                <?php
                 }
                }  else { echo "<h3>".$lang['ARTICLE_ERROR']."</h3>";}
                ?><hr /></ul><?php
                }
                ?>




            <div class="clearfix"></div>
            <!-- SEARCH END -->


       <div class="cleaner"></div>

       <?php } ?>
       </div>
    </div>

    <div class="row">


            <?php

            $ctg = $database->showCat("*");

            if (isset($_GET['category'])) { $category = $_GET['category'];}

            else { $category = "*";}

            $kbarticle = $database->showKB($category);

            if (!isset($kbarticle[0]['id'])) { echo "<div class='col-md-12'><center><h3>".$lang['ARTICLE_ERROR']."</h3><div class='cleaner'></div><a href='kdb.php' class='btn btn-warning'>".$lang['KB']."</a></center></div><div class='cleaner'></div>"; include('footer.inc.php'); die; }?>

            <div class="col-md-4">

            <h3><?php echo $lang['CATEGORY'];?></h3>
            <div class="cleansmall"></div>

                <ul class="paging articlesshow">
                        <li class="noline animated fadeInLeft"><a class='btn btn-primary btn-block btn-xs' href="kdb.php"><?php echo $lang['ALL'];?></a></li>

                        <?php foreach ($ctg as $ctg) {
                                       echo "<li class='noline animated fadeInLeft'><a class='btn ";
                                       if ((isset($_GET['category'])) && ($_GET['category'] == $ctg['id'])) { echo "btn-warning"; } else { echo "btn-default";}
                                       echo " btn-block btn-xs text-left' href='kdb.php?category=".$ctg['id']."'>".$ctg['catprod']." / ".$ctg['catname']."</a></li>";
                    if ((isset($_GET['category'])) && ($_GET['category'] == $ctg['id'])) { $h3prod = $ctg['catprod']; $h3cat = $ctg['catname']; }
                    }
                    ?>

                </ul>
        </div>

            <div class="col-md-8">
            <h3 class="pull-right"><?php echo $lang['ARTICLES']; if (isset($_GET['category'])) { echo ": ".$h3prod." / ".$h3cat; }?></h3>
            <div class="cleansmall"></div>
            <ul class="paging articlesshow">
                <?php

                foreach ($kbarticle as $kbarticle) { ?>

                <li class="animated fadeInRight">
                    <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#show<?php echo $kbarticle['id'];?>" aria-expanded="false">
                      <?php echo $kbarticle['question'];?>
                    </button>

                    <div id="show<?php echo $kbarticle['id'];?>" class="collapse faq" aria-expanded="false">
                        <?php echo $kbarticle['answer'];?>

                    </div>
                </li>


                <?php
                }
                ?>
                </ul>
                <script>$("ul.paging").quickPager();</script>
            </div>
        </div>
        <hr />
        <?php include ('_comments.php');
              include('footer.inc.php');
        ?>
</div>