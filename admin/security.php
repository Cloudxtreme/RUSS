<?php
if (!defined('VERSIONCT')) {
    die;
}?>
<div class="container-fluid">
<div class="positioner"><?php echo $lang['SECURITY'];?></div>
    <h4><?php echo $lang['BE_CAREFUL'];?></h4>
<div class="cleaner"></div>


<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function jqCheckAll3( id, pID )
{
   $( "#" + pID + " :checkbox").attr('checked', $('#' + id).is(':checked'));
}
//  End -->
</script>
<?php include('include/pagination.php'); ?>
<div class="row">
          <div class="col-md-12">
          <?php

          if(isset($_SESSION['banusererror']))  {

          if($_SESSION['banusererror'] == 1) {
          echo "<br /><div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button>".$lang['NO_USER']."</div>";
            $_SESSION['banusererror'] = 0;}

          if($_SESSION['banusererror'] == 2) {
          echo "<br /><div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button>".$lang['USER_BANNED']."</div>";

          $_SESSION['banusererror'] = 0;}

          if ($_SESSION['banusererror'] == 3) {
          echo "<br /><div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button>".$lang['DEL_INACTIVE'].": ".$lang['SUCCESS']."</div>";

          $_SESSION['banusererror'] = 0;}

          }
          if(isset($_SESSION['baniperror']))  {


          if ($_SESSION['baniperror'] == 1) {
          echo "<br /><div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button>".$lang['NO_IP']."</div>";

              $_SESSION['baniperror'] = 0;}

          if ($_SESSION['baniperror'] == 2) {
          echo "<br /><div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span>&times;</span></button>".$lang['IP']." ".$lang['PERM_BAN']."</div>";

              $_SESSION['baniperror'] = 0;}



              }



          ?>
          </div>
    <div class="col-md-6">

    <h4><?php echo $lang['BAN'];?> <?php echo $lang['MEMBER'];?></h4>


    <form action="adminprocess.php" method="POST">
        <div class="input-group">

        <input type="text" name="banuser" class="form-control" maxlength="30" value="<?php echo $form->value("banuser"); ?>">
          <span class="input-group-btn">
            <input type="submit" value="<?php echo $lang['BAN'];?> <?php echo $lang['MEMBER'];?>" class="btn btn-danger"/>
            <input type="hidden" name="subbanuser" value="1">
          </span>

        </div>

    </form>
    </div>


     <div class="col-md-6">
      <h4><?php echo $lang['PERM_BAN'];?></h4>
      <?php
      if (isset($_POST['orderby'])){
      	$orderby=$_POST['orderby'];
      } else {
      	$orderby = 'username';
      }
      $result = $pagination->paginatePage("10","".TBL_BANNED_USERS."","$orderby");
      ?>
      <table class='tabler'>
      <tr>
      <th class='sortable'><?php echo $lang['MEMBERS'];?></th>
      <th class='sortable'><?php echo $lang['WHEN'];?></th>
      </tr>

      <?php
      while ($row = $result->fetch()) {
      $timestamp = $row['timestamp'];
      $lastlogin  = date("j M, y, g:i a", $timestamp);
      echo "<tr><td>".$row['username']."</td>"
      ."<td>".$lastlogin."</td></tr>";
      }
      ?>

      </table>

    </div>


</div>

<div class="row"><div class="cleaner"></div></div>

<div class="row">
    <div class="col-md-6">
    <h4><?php echo $lang['BAN'];?> <?php echo $lang['IP'];?></h4>


    <form action="adminprocess.php" method="POST">
        <div class="input-group">

        <input type="text" name="banip" class="form-control" maxlength="15" value="<?php echo $form->value("banip"); ?>">
          <span class="input-group-btn">
            <input type="submit" value="<?php echo $lang['BAN'];?> <?php echo $lang['IP'];?>" class="btn btn-danger"/>
            <input type="hidden" name="subbanip" value="1">
          </span>

        </div>

    </form>
    </div>


     <div class="col-md-6">
      <h4><?php echo $lang['PERM_BAN'];?> <?php echo $lang['IP'];?></h4>
      <?php
      if (isset($_POST['orderby'])){
      	$orderby=$_POST['orderby'];
      } else {
      	$orderby = 'ip';
      }
      $result = $pagination->paginatePage("10","".TBL_BANNED_IP."","$orderby");
      ?>
      <table class='tabler'>
      <tr>
      <th class='sortable'><?php echo $lang['IP'];?></th>
      <th class='sortable'>When</th>
      </tr>

      <?php
      while ($row = $result->fetch()) {
      $timestamp = $row['timestamp'];
      $lastlogin  = date("j M, y, g:i a", $timestamp);
      echo "<tr><td>".$row['ip']."</td>"
      ."<td>".$lastlogin."</td></tr>";
      }
      ?>

      </table>

    </div>


</div>

<div class="row"><div class="cleaner"></div></div>

<div class="row">
  <div class="col-md-12">




    <h4><?php echo $lang['DEL_INACTIVE'];?> (<?php echo $lang['DAYS'];?>)</h4>
    <form action="adminprocess.php" method="POST">

        <div class="input-group">
        <select name="inactdays" class="form-control">
            <option value="3">3</option>
            <option value="7">7</option>
            <option value="14">14</option>
            <option value="30">30</option>
            <option value="100">100</option>
            <option value="365">365</option>
            </select>
          <span class="input-group-btn">
                <input type="hidden" name="subdelinact" value="1">
                <input type="submit" value="<?php echo $lang['DELETE'];?>" class="btn btn-danger" onclick="return confirm ('<?php echo $lang['SURE_DELETE'];?>')">
          </span>

        </div>

    </form>



  </div>


</div>
<script type="text/javascript" src="js/tablesort.js"></script>
</div>

