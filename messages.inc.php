        <div>
        <?php if(((isset($_GET['success'])) && ($_GET['success'] == 1))){
               echo "<div class='alert alert-success alert-dismissible fadeInDown animated' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button>".$lang['SAVE_SUCCESS']."</div>";}

            else if (($form->num_errors > 0) || ((isset($_GET['success'])) && ($_GET['success'] != 1))){
            echo "<div class='alert alert-warning alert-dismissible fadeInDown animated' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button>".$form->num_errors." ".$lang['ERRORS_FOUND']."</div>";}

            ?>
        </div>

<?php

$proerror = $database->showProd();
$caterror = $database->showCat('*');



     if ((($session->isAdmin()) && (!isset($proerror[0]['prod']))) || (($session->isAdmin()) && (!isset($caterror[0]['catname'])))) { ?>
        <div class="pomracina">
                <p><?php echo $lang['NOT_READY_ADMIN'];?></p>
                <hr />
                <center><a href="admin/index.php?id=15" class="btn btn-warning"><?php echo $lang['ADMIN_CENTER'];?></a></center>
            </div>
     <?php die; }
     else if (( (!$proerror[0]['prod']) || (!$caterror[0]['catname'])) && (strpos($_SERVER['REQUEST_URI'],'registration') === false)){ ?>
          <div class="pomracina">
                <p><?php echo $lang['NOT_READY'];?></p>
            </div>
     <?php die; } ?>

