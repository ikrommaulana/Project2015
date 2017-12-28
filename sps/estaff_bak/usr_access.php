<?php
//110610 - upgrade gui
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
ISACCESS("KEY",1);

$adm = $_SESSION['username'];
$uid=$_REQUEST['uid'];	
$op=$_REQUEST['op'];

if($op=="save"){
	$acc=$_POST['access'];
	for($i=0;$i<count($acc);$i++){
		if($access!="")
			$access=$access."|".$acc[$i];
		else
			$access=$acc[$i];
	}
	$sql="update usr set sysaccess='$access' where uid='$uid'";
	mysql_query($sql);
	$f="<font color=blue>&lt;SUCCESSFULLY UPDATE&gt</font>";
}

$sql="select * from usr where uid='$uid'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$access=$row['sysaccess'];
$dat=explode("|",$access);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="javascript">

function process_form(action){
		ret = confirm("<?php echo $lg_confirm_save;?>");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
}

</script>
<title>SPS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body style="background:none " onLoad="<?php if($f!=""){?>top.document.myform.submit();<?php }?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="process_form('save')" id="mymenuitem"><img src="../img/save.png"><br><?php echo $lg_save;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br><?php echo $lg_refresh;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();top.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br><?php echo $lg_close;?></a>
	</div>
	<div id="viewpanel" align="right"  >
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>	
</div> <!-- end mypanel-->
<div id="story">
<form name=myform action="" method=post >
	<input name="op" type="hidden">
	<input name="uid" type="hidden" value="<?php echo $uid;?>">
<div id="mytitle2"><?php echo $lg_staff;?>: <?php echo $uid;?> <?php echo $f;?></div>

<table width="100%" cellspacing="0" cellpadding="2">
  <tr>
     <td class="mytableheader" width="5%" align="center"><?php echo $lg_tick;?></td>
	 <td class="mytableheader" width="30%">&nbsp;<?php echo $lg_access;?></td>
	 <td class="mytableheader" width="65%">&nbsp;<?php echo $lg_description;?></td>
  </tr>
<?php
	$sql="select * from sys_prm where grp='accesskey'  order by etc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while ($row=mysql_fetch_assoc($res)){
			$prm=$row['prm'];
			$des=$row['des'];
			$cod=$row['code'];
			$checked="";
			for($i=0;$i<count($dat);$i++){
				if($dat[$i]==$cod) $checked="checked";
			}
?>
	 <tr>
	 	<td class="myborder" align="center"><input type="checkbox" name="access[]" value="<?php echo $cod;?>" <?php echo "$checked";?>></td>
	 	<td class="myborder"><?php echo $prm;?></td>
		<td class="myborder"><?php echo $des;?></td>
	</tr>
<?php } ?>
</table>
</form>
</div></div>
</body>
</html>
