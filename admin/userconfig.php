<?php
if (!defined('VERSIONCT')) {
    die;
}?>

<div class="container-fluid">




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
if (isset($_POST['orderby'])){
	$orderby=$_POST['orderby'];
} else {
	$orderby = 'username';
}
$result = $pagination->paginatePage("10","".TBL_USERS."","$orderby");
echo "<div class='positioner'>".$lang['MEMBERS']."</div>";
echo "<table class='tabler'>";
echo "<tr><th class='sortable'>".$lang['USERNAME']."</th><th class='sortable'>".$lang['LEVEL']."</th><th class='sortable'>".$lang['EMAIL']."</th><th class='sortable'>".$lang['REGISTERED']."</th><th class='sortable'>".$lang['LAST_LOGIN']."</th></tr>";
		
		while ($row = $result->fetch()) {
		$timestamp = $row['timestamp'];
		$lastlogin  = date("j M, y, g:i a", $timestamp);
		$regdate = $row['regdate'];
		$reg  = date("j M, y, g:i a", $regdate);
		echo "<tr><td><a href='".$config['WEB_ROOT']."admin/index.php?id=6&usertoedit=".$row['username']."'>".$row['username']."</a></td><td>".$row['userlevel']."</td>"
		."<td><a href='mailto:".$row['email']."'>".$row['email']."</a></td><td>"
		.$reg."</td><td>".$lastlogin."</td></tr>";		
		}
echo "</table>";
?>
</div>
</div>
<div class="cleaner"></div>
<div class="row">
<div class="col-md-12">
<?php
$orderby = 'regdate';
$result = displayAdminActivation($orderby);
?>

<h4><?php echo $lang['ACC_AWAITING'];?></h4>
<form name='myForm' action='adminprocess.php' method='POST'>
<table id='left' class='tabler'>
<tr>
<th class='sortable'><?php echo $lang['USERNAME'];?></th><th class='sortable'><?php echo $lang['LEVEL'];?></th>
<th class='sortable'><?php echo $lang['EMAIL'];?></th><th class='sortable'><?php echo $lang['REGISTERED'];?></th><th>
<input type="checkbox" id="checkL" onclick="jqCheckAll3(this.id, 'left');"/></th>
</tr>

<?php
while($row = $result->fetch())
{
	$regdate = $row['regdate'];
	$reg  = date("j M, y, g:i a", $regdate);
	echo "<tr><td>".$row['username']."</td><td>".$row['userlevel']."</td><td>"
	."<a href='mailto:".$row['email']."'>".$row['email']."</a></td><td>".$reg
	."</td><td><input name='user_name[]' type='checkbox' value='".$row['username']."' />";
}
?>

</table>
<br />
<input type="hidden" name="activateusers" value="1">
<input type="submit" value="Activate Selected Users" class="btn btn-success pull-right">
<br>
</form>
</div>
</div>

<script type="text/javascript" src="js/tablesort.js"></script>
</div>

