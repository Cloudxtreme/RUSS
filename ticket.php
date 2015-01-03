<?php
    require_once('_engine.php');    // IMPORTANT - MUST BE INCLUDED FIRST, ABOVE ALL
    include('header.inc.php');     // INCLUDE <HEAD> PART (SKIN RELATED)
    include ('mainmenu.inc.php');  // INCLUDE MAIN MENU (SKIN RELATED)
    if(!$session->logged_in){ ?>
         <script type="text/javascript">window.location.href = "<?php echo $config['WEB_ROOT'].$config['home_page'];?>"</script><meta http-equiv="refresh" content="0; url=<?php echo $config['WEB_ROOT'].$config['home_page'];?>"/>
    <?php }

    $nocallshere = 1;
     ?>

        <?php include ('messages.inc.php'); ?>






<div class="container tickets">
    <div class="row">
       <div class="col-md-12">


       <?php if (isset($_GET['ID'])) {
           echo "<h1 class='pull-right dotter'>".$lang['VIEW_TICKET'].": ".$_GET['ID']."</h1><br /><hr />";
           $tickID = $_GET['ID'];
       } else if (isset($_POST['ID'])) {
           echo "<h1 class='pull-right dotter'>".$lang['VIEW_TICKET'].": ".$_POST['ID']."</h1><br /><hr />";
           $tickID = $_POST['ID'];
       }
       else {       ?>
       </h1><div class="cleaner"></div>

       <?php if(($session->userlevel) > 7) { ?>

       <form action="ticket.php" method="post" class="form-horizontal" role="form">

                <div class="input-group">
                  <input type="text" name="ID" id="ID" class="form-control" value="<?php echo $form->value("ID"); ?>" placeholder="<?php echo $lang['TICKET_ID'];?>" required/>
                  <span class="input-group-btn">
                    <input type="submit" value="<?php echo $lang['SEARCH'];?>" id="submit" class="btn btn-warning"/>
                  </span>
                </div>

       </form>


       <div class="cleaner"></div>
       <a href="ticket.php?statuser=<?php echo $session->username;?>" class="btn btn-xs btn-default"><?php echo $lang['ASSIGNED_LEVEL'];?> <?php echo $session->username;?></a>
       <a href="ticket.php?statuser=1" class="btn btn-xs btn-warning"><?php echo $lang['STATUS_1'];?></a>
       <a href="ticket.php?statuser=2" class="btn btn-xs btn-default"><?php echo $lang['STATUS_2'];?></a>
       <a href="ticket.php?statuser=3" class="btn btn-xs btn-danger"><?php echo $lang['STATUS_3'];?></a>
       <a href="ticket.php?statuser=4" class="btn btn-xs btn-success"><?php echo $lang['STATUS_4'];?></a>
       <a href="ticket.php?statuser=5" class="btn btn-xs btn-primary"><?php echo $lang['STATUS_5'];?></a>
       <a href="ticket.php?statuser=0" class="btn btn-xs btn-default"><?php echo $lang['STATUS_0'];?></a>

       <a href="ticket.php" class="btn btn-xs btn-danger"><?php echo $lang['CLEAR'];?></a>
        <?php } ?>

       <?php

            $info = $database->showMeta('*');

            ?>
            <div class="cleaner"></div>
            <h1 class="pull-right dotter"><?php if (($session->userlevel) < 8) { echo $lang['YOUR_TICKET'];} else {echo $lang['TICKETS'];}?></h1>
            <div class="clearfix"></div>

            <div class="theader">
                <div class="tread-2 first-tread"><?php echo $lang['TICKET_ID'];?></div>
                <div class="tread-3"><?php echo $lang['LAST_QUESTION'];?></div>

                <div class="tread-<?php if(($session->userlevel) == 9) { echo "2";} else if(($session->userlevel) == 8) { echo "2";} else { echo "3";}?>"><?php echo $lang['STATUS'];?></div>

                <div class="tread-<?php if(($session->userlevel) == 9) { echo "2";} else if(($session->userlevel) == 8) { echo "3";} else { echo "4";}?> text-right"><?php echo $lang['ASSIGNED_LEVEL'];?></div>

                <?php if (($session->userlevel) > 7) { ?>
                <div class="tread-2 text-right last-tread"><?php echo $lang['OWNER'];?></div>
                <?php } ?>

                <?php if(($session->userlevel) > 8) { echo "<div class='tread-1 text-right last-tread'>&nbsp;</div>";} ?>

                <p class="clearfix"></p>
            </div>

            <ul class="paging tabler">
            <?php $counter = 0;

            if(!isset($info[0]['id'])) {
                echo "<h3>".$lang['TICKET_ERROR']."</h3>";
            } else {

            foreach ($info as $info) {

            $entry = $database->showTicket($info['tid']);

                if( ($info['owner'] == ($session->username)) || ( (($session->userlevel) > 7) && (isset($_GET['statuser'])) && ($_GET['statuser'] == $info['status'])) || ( (($session->userlevel) > 7) && (isset($_GET['statuser'])) && ($_GET['statuser'] == $info['tech'])) || ( (($session->userlevel) > 7) && (isset($_GET['statuser'])) && ($_GET['statuser'] == $info['owner']))  || ( (($session->userlevel) > 7) && (!isset($_GET['statuser']))) ) {

                $counter = $counter + 1;

                ?>


                <li>
                        <a href="ticket.php?ID=<?php echo $info['tid'];?>">
                        <div class="tread-2 first-tread"><?php echo $info['tid'];?></div>
                        <div class="tread-3"><?php echo $entry[0]['subject'];?></div>
                        <div class="tread-<?php if(($session->userlevel) == 9) { echo "2";} else if(($session->userlevel) == 8) { echo "2";} else { echo "3";}?>">
                            <?php
                                 if ($info['status'] == 0) { echo "<strong class='text-default'>".$lang['STATUS_0']."</strong>";}
                            else if ($info['status'] == 1) { echo "<strong class='text-primary'>".$lang['STATUS_1']."</strong>";}
                            else if ($info['status'] == 2) { echo "<strong class='text-warning'>".$lang['STATUS_2']."</strong>";}
                            else if ($info['status'] == 3) { echo "<strong class='text-danger'>".$lang['STATUS_3']."</strong>";}
                            else if ($info['status'] == 4) { echo "<strong class='text-success'>".$lang['STATUS_4']."</strong>";}
                            else if ($info['status'] == 5) {  echo "<strong class='text-danger'>".$lang['STATUS_5']."</strong>";}
                            ?>
                        </div>



                        <div class="tread-<?php if(($session->userlevel) == 9) { echo "2";} else if(($session->userlevel) == 8) { echo "3";} else { echo "4";}?> text-right">
                        <?php
                                if ($info['assigned'] == 1) { echo $info['tech']." / ".$lang['LEVEL_1'];}
                                else if ($info['assigned']  == 2) { echo $info['tech']." / ".$lang['LEVEL_3'];}
                                else {echo "<span class='text-warning'>".$lang['LEVEL_0']."</span>";}
                                ?>
                        </div>
                        </a>

                        <?php if(($session->userlevel) > 7) { ?><div class="tread-2 text-right"><a href="ticket.php?statuser=<?php echo $info['owner'];?>" class="btn btn-xs btn-primary"><?php echo $info['owner'];?></a>&nbsp;<a class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="<?php echo $entry[0]['IP'];?>">IP</a>


</div>

<?php  } ?>



                        <?php if(($session->userlevel) > 8) { ?>
                        <div class="tread-1 text-right last-tread">
                        <form action="admin/process.php" method="post" class="form-horizontal" role="form">

                        <input type="hidden" name="tid_delid" value="<?php echo $info['tid'];?>"/>
                        <input type="hidden" name="optid" value="1" />

                          <input type="submit" class="btn btn-danger btn-xs" value="<?php echo $lang['DELETE'] ;?>">
                        </form>
                        </div>

                        <?php } ?>


                        <p class="clearfix"></p>
                </li>

            <?php   }

            }



       ?></ul>  <?php if ($counter < 1) { echo "<div class='cleaner'></div><strong>".$lang['NO_TICKET']."</strong>"; }
            else { echo "<div class='cleaner'></div><strong>".$counter." ".$lang['TICKETS']."</strong>";}

         }
       }



       ?>

       </div>
</div>


       <?php if (isset($tickID) )  {

           $ticket = $database->showTicket($tickID);
           $meta = $database->showMeta($tickID);

           if (!isset($ticket)) {echo "<center><h2><strong>".$lang['TICKET_ERROR']."</strong></h2><br /><a href='index.php' class='btn btn-warning'>".$lang['BACK_TO_HOME']."</a></center>";}

           else {

           ?>

           <div class="row">
                <div class="col-md-12">
                <div class="cleaner"></div>

                <form action="admin/process.php" method="post" class="form-horizontal" role="form">

                        <div class="form-group">
                            <div class="col-md-12">
                              <input type="text" name="subject" id="subject" class="form-control" value="<?php echo $form->value("subject"); ?>" required placeholder="<?php echo $lang['QUESTION'];?>"/>
                              <?php echo $form->error("subject"); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">

                              <textarea name="report" id="report" class="form-control cleditor" required placeholder="<?php echo $lang['DESC'];?>"><?php echo $form->value("report"); ?></textarea>

                              <?php echo $form->error("report"); ?>
                            </div>
                        </div>

                        <?php

                        include ('_captcha.php');
                        ?>

                        <div class="clearfix"></div>

                        <input type="hidden" name="category" value="<?php echo $ticket[0]['category'];?>"/>
                        <input type="hidden" name="ownermail" value="<?php echo $ticket[0]['ownermail'];?>"/>
                        <input type="hidden" name="status" value="<?php if (($session->userlevel) < 8 ) { echo "1"; } else { echo "2";}?>"/>

                        <input type="hidden" name="assigned" value="<?php if (($session->userlevel) == 8 ) { echo "1"; } else if (($session->userlevel) == 9 ) { echo "2";} else { echo $meta[0]['assigned']; }?>"/>

                        <input type="hidden" name="tech" value="<?php if (($session->userlevel) == 8 ) { echo $session->username; } else if (($session->userlevel) == 9 ) { echo $session->username;} else { echo $meta[0]['tech']; }?>"/>

                         <input type="hidden" name="techmail" value="<?php if (($session->userlevel) > 7 ) { echo $session->userinfo['email']; } else { echo $ticket[0]['techmail']; }?>"/>
                        <input type="hidden" name="notes" value="<?php echo $meta[0]['notes'];?>"/>
                        <!-- <input type="hidden" name="owner" value="<?php echo $meta[0]['owner'];?>"/> -->
                        <input type="hidden" name="rating" value="<?php echo $meta[0]['rating'];?>"/>
                        <input type="hidden" name="tid" value="<?php echo $tickID;?>"/>
                        <input type="hidden" name="optic" value="1" />
                        <input type="submit" value="<?php echo $lang['ADD_RESPONSE'];?>" id="submit" class="btn btn-lg btn-success btn-block"/>

                        </form>

                </div>
           </div>


    <?php  foreach ($ticket as $ticket) {

    if ((($session->username) == ($meta[0]['owner'])) || (($session->userlevel) > 7 )) {

    ?>
     <div class="row">
     <div class="separator"></div>


            <div class="sidebar fadeInUp animated">

            <div class="col-md-<?php if(($session->userlevel) > 7) { echo "4";} else {echo "5";}?>">

                <p><?php echo $lang['STATUS'];?>:

                       <?php
                        if ($meta[0]['status'] == 0) { echo "<strong class='pull-right label-default label'>".$lang['STATUS_0']."</strong>";}
                        else if ($meta[0]['status'] == 1) { echo "<strong class='pull-right label-primary label'>".$lang['STATUS_1']."</strong>";}
                        else if ($meta[0]['status'] == 2) { echo "<strong class='pull-right label-warning label'>".$lang['STATUS_2']."</strong>";}
                        else if ($meta[0]['status'] == 3) { echo "<strong class='pull-right label-danger label'>".$lang['STATUS_3']."</strong>";}
                        else if ($meta[0]['status'] == 4) { echo "<strong class='pull-right label-success label'>".$lang['STATUS_4']."</strong>";}
                        else if ($meta[0]['status'] == 5) {  echo "<strong class='pull-right label-default label'>".$lang['STATUS_5']."</strong>";}
                        ?>

                    </p>


                <?php

                 $cate = $database->showCat('*');

                    foreach ($cate as $value) {
                       if ($value['id'] == $ticket['category']) { ?>

                        <p><?php echo $lang['PRODUCTS'];?> -> <?php echo $lang['CATEGORY'];?>:<strong class="pull-right"><?php echo $value['catprod'];?> -> <?php echo $value['catname'];?></strong></p>


                     <?php      }
                    }

                ?>

                </div>



                <div class="col-md-4">
                <?php if(($session->userlevel) == 9) { ?>
                        <center>
                        <form action="admin/process.php" method="post" class="form-horizontal" role="form">

                        <input type="hidden" name="tid_delid" value="<?php echo $meta[0]['tid'];?>"/>
                        <input type="hidden" name="optid" value="1" />

                          <input type="submit" class="btn btn-danger btn-xs btn-block" value="<?php echo $lang['DELETE'] ;?>">
                        </form></center> <?php } else if(($session->userlevel) == 8) { echo "<p>&nbsp;</p>";}

                if(($session->userlevel) > 7) { ?>

                        <p class="text-warning">&nbsp;</p>
                <?php } ?>
                </div>


                <div class="col-md-<?php if(($session->userlevel) > 7) { echo "4";} else {echo "5 col-md-offset-2";}?>">

                <p><?php echo $lang['OWNER'];?>:<strong class="pull-right"><?php echo $meta[0]['owner'];?></strong></p>

                    <p><?php echo $lang['ASSIGNED_LEVEL'];?>:
                        <strong class="pull-right">
                            <?php
                                if ($meta[0]['assigned'] == 1) { echo $lang['LEVEL_1'];}
                                else if ($meta[0]['assigned'] == 2) { echo $lang['LEVEL_3'];}
                                else {echo $lang['LEVEL_0'];}
                                ?>
                        | <?php echo $lang['TECH'];?>: <?php echo $meta[0]['tech'];?></strong></p>

                </div>

            <?php if(($session->userlevel) < 8) {  ?>
                <form action="admin/process.php" method="post" class="form-horizontal" role="form">
                <div class="col-md-<?php if(($session->userlevel) > 7) { echo "4";} else {echo "5";}?>">

                <select name="rating" class="form-control" value="<?php $form->value("rating");?>" <?php if ($meta[0]['rating'] != 0) {echo "disabled";}?> onchange='if(this.value != 0) { this.form.submit(); }'>
                    <option value="0" <?php if ($meta[0]['rating'] == 0) {echo "selected";}?>><?php echo $lang['RATE_SUPPORT'];?></option>
                    <option value="1" <?php if ($meta[0]['rating'] == 1) {echo "selected";}?>><?php echo $lang['RATING_1'];?></option>
                    <option value="2" <?php if ($meta[0]['rating'] == 2) {echo "selected";}?>><?php echo $lang['RATING_2'];?></option>
                    <option value="3" <?php if ($meta[0]['rating'] == 3) {echo "selected";}?>><?php echo $lang['RATING_3'];?></option>
                    <option value="4" <?php if ($meta[0]['rating'] == 4) {echo "selected";}?>><?php echo $lang['RATING_4'];?></option>
                    <option value="5" <?php if ($meta[0]['rating'] == 5) {echo "selected";}?>><?php echo $lang['RATING_5'];?></option>
                </select>
                    <input type="hidden" name="hitcap" value="0"/>
                    <input type="hidden" name="tech" value="<?php echo $meta[0]['tech'];?>"/>
                    <input type="hidden" name="assigned" value="<?php echo $meta[0]['assigned'];?>"/>
                    <input type="hidden" name="notes" value="<?php echo $meta[0]['notes'];?>"/>
                    <input type="hidden" name="tid" value="<?php echo $tickID;?>"/>
                    <input type="hidden" name="status" value="<?php echo $meta[0]['status'];?>"/>
                    <input type="hidden" name="optix" value="1" />
                </div>

                </form>

            <?php } else { ?>
            <div class="col-md-4">
                <button class="btn btn-block btn-primary" data-toggle="modal" data-target="#myModal"><?php echo $lang['NOTES'];?></button>
            </div>
            <?php } ?>

                <div class="col-md-4">
                <?php if(($session->userlevel) > 7) { ?>
                        <form action="admin/process.php" method="post" class="form" role="form">

                        <select name="status" class="form-control" value="<?php $form->value("status");?>" onchange='if(this.value != 0) { this.form.submit(); }'>

                            <option value="1" <?php if ($meta[0]['status'] == 1) {echo "selected";}?>><?php echo $lang['STATUS_1'];?></option>
                            <option value="2" <?php if ($meta[0]['status'] == 2) {echo "selected";}?>><?php echo $lang['STATUS_2'];?></option>
                            <option value="3" <?php if ($meta[0]['status'] == 3) {echo "selected";}?>><?php echo $lang['STATUS_3'];?></option>
                            <option value="4" <?php if ($meta[0]['status'] == 4) {echo "selected";}?>><?php echo $lang['STATUS_4'];?></option>
                            <option value="5" <?php if ($meta[0]['status'] == 5) {echo "selected";}?>><?php echo $lang['STATUS_5'];?></option>
                        </select>
                            <input type="hidden" name="hitcap" value="0"/>
                            <input type="hidden" name="tech" value="<?php echo $session->username;?>"/>

                            <?php
                            if(($session->userlevel) == 8) { ?><input type="hidden" name="assigned" value="1"/><?php }
                            else if(($session->userlevel) == 9) { ?><input type="hidden" name="assigned" value="2"/><?php }
                            ?>

                            <input type="hidden" name="notes" value="<?php echo $meta[0]['notes'];?>"/>
                            <input type="hidden" name="rating" value="<?php echo $meta[0]['rating'];?>"/>

                            <input type="hidden" name="tid" value="<?php echo $tickID;?>"/>
                            <input type="hidden" name="optix" value="1" />

                        </form>
                <?php } ?>

                </div>

                <div class="col-md-<?php if(($session->userlevel) > 7) { echo "4";} else {echo "5 col-md-offset-2";}?>">

                <?php if ($meta[0]['status'] != 0) { ?>

                <form action="admin/process.php" method="post" class="form-horizontal" role="form">

                    <input type="hidden" name="hitcap" value="0"/>
                    <input type="hidden" name="tech" value="<?php echo $meta[0]['tech'];?>"/>
                    <input type="hidden" name="assigned" value="<?php echo $meta[0]['assigned'];?>"/>
                    <input type="hidden" name="rating" value="<?php echo $meta[0]['rating'];?>"/>
                    <input type="hidden" name="notes" value="<?php echo $meta[0]['notes'];?>"/>
                    <input type="hidden" name="status" value="0"/>
                    <input type="hidden" name="tid" value="<?php echo $tickID;?>"/>
                    <input type="hidden" name="optix" value="1" />
                    <input type="submit" value="<?php echo $lang['MARK_AS'];?> <?php echo $lang['STATUS_0'];?>" id="submit" class="btn btn-warning btn-block"/>

                </form>
                <?php } ?>

                </div>

            </div>




       <div class="col-md-2">
            <a href="userinfo.php?user=<?php echo $ticket['user'];?>" target="_blank"><img src="https://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( $ticket['email'] ) ) );?>?s=200" class="tickgravimg" alt="Gravatar"/></a>

        <?php /* If online indicator is set to ON */
            $req_user_info = $database->getUserInfo($ticket['user']);
            if($req_user_info['online'] == "Y") {

                $forwho = $req_user_info['username'];
                $stmt = $database->connection->query("SELECT username FROM ".TBL_ACTIVE_USERS." WHERE username LIKE '%$forwho%'");
                $num_rows = $stmt->rowCount();

            if(!$stmt || ($num_rows == 0)){
                echo "<a class='btn btn-danger btn-block btn-xs'>".$lang['OFFLINE']."</a>";
            }
            else {
                echo "<a class='btn btn-success btn-block btn-xs'>".$lang['ONLINE']."</a>";
                if ((($req_user_info['userlevel']) > 7) && (($req_user_info['allowcalls']) == "Y") && (($config['ENABLE_CALLS']) == 1) && (($session->userlevel) < 8)) { ?><a href="call.php?tech=<?php echo $ticket['user'];?>" class="btn btn-block btn-xs btn-warning"><?php echo $lang['VIDEO_CALL'];?></a><?php }
            }

            } ?>

       </div>
       <div class="col-md-10">

           <h2 class="left-border"><?php echo $ticket['subject'];?></h2>
           <div class="clearfix"></div>
           <hr />

           <div class="infoblock">

               <p><?php echo $lang['USERNAME'];?>:<a href="userinfo.php?user=<?php echo $ticket['user'];?>" target="_blank"><strong class="pull-right"><?php echo $ticket['user'];?></strong></a></p>
               <p><?php echo $lang['TIME_POSTED'];?>:<strong class="pull-right"><?php echo $ticket['timedate'];?></strong></p>

           </div>

           <p><?php echo $ticket['report'];?></p>

       </div>
       </div>
       <?php if(($session->userlevel) > 7) { ?>
            <div class="modal fade" id="myModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <form action="admin/process.php" method="post" class="form-horizontal" role="form">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php echo $lang['NOTES'];?></h4>
                  </div>
                  <div class="modal-body">


                            <textarea name="notes" class="form-control cleditor"><?php echo $meta[0]['notes'];?></textarea>
                            <div class="clearfix"></div>
                                <input type="hidden" name="hitcap" value="0"/>
                                <input type="hidden" name="tech" value="<?php echo $meta[0]['tech'];?>"/>
                                <input type="hidden" name="assigned" value="<?php echo $meta[0]['assigned'];?>"/>
                                <input type="hidden" name="rating" value="<?php echo $meta[0]['rating'];?>"/>
                                <input type="hidden" name="tid" value="<?php echo $tickID;?>"/>
                                <input type="hidden" name="status" value="<?php echo $meta[0]['status'];?>"/>
                                <input type="hidden" name="optix" value="1" />


                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" value="<?php echo $lang['ADD_RESPONSE'];?>" id="submit" class="btn btn-success"/>
                  </div>
                </div>
                </form>
              </div>
            </div>
        <?php } ?>

       <?php

       if ($meta[0]['status'] == 0) {  ?>

            <div class="pomracina">
                <p><?php echo $lang['TICKET']." ".$lang['STATUS_0'];?></p>
                <hr />
                <center><a href="index.php" class="btn btn-warning"><?php echo $lang['BACK_TO_HOME'];?></a></center>
            </div>

       <?php break; }

       if ($meta[0]['status'] == 5) {  ?>

            <div class="pomracina">
                <p><?php echo $lang['TICKET']." ".$lang['STATUS_5'];?></p>
                <hr />
                <center><a href="index.php" class="btn btn-warning"><?php echo $lang['BACK_TO_HOME'];?></a></center>
            </div>
            <?php  break;  }
                 }
            else { ?>
            <div class="pomracina">
                <p><?php echo $lang['NO_PERMISSION'];?></p>
                <hr />
                <center><a href="index.php" class="btn btn-warning"><?php echo $lang['BACK_TO_HOME'];?></a></center>
            </div>

    <?php    break;         }

            }


     }

 ?>

<?php } ?>


</div>

<?php include('footer.inc.php');    // INCLUDE <FOOTER> PART (SKIN RELATED)?>
<div class="bottomer"></div>