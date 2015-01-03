<?php
if (!defined('VERSIONCT')) {
    die;
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>

    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>
        <?php
        if (!(isset($config['SITE_NAME']))) {
          echo SCRIPTAUTHOR;
        } else {
        echo $config['SITE_NAME'];
        } ?>
        - Administration
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="img/favicon.png" />
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="../admin/include/cache.php?css=styles" type="text/css" media="screen" />
	    <script src="../admin/include/cache.php?js=library" charset="utf-8"></script>
        <script type="text/javascript">
$(function(){
$(".search").keyup(function()
{
var searchid = $(this).val();
var dataString = 'search='+ searchid;
if(searchid!='')
{
	$.ajax({
	type: "POST",
	url: "autocomplete.php",
	data: dataString,
	cache: false,
	success: function(html)
	{
	$("#result").html(html).show();
	}
	});
}return false;
});

jQuery("#result").live("click",function(e){
	var $clicked = $(e.target);
	var $name = $clicked.find('.name').html();
	var decoded = $("<div/>").html($name).text();
	$('#searchid').val(decoded);
});
jQuery(document).live("click", function(e) {
	var $clicked = $(e.target);
	if (! $clicked.hasClass("search")){
	jQuery("#result").fadeOut();
	}
});
$('#searchid').click(function(){
	jQuery("#result").fadeIn();
});
});
</script>
</head>