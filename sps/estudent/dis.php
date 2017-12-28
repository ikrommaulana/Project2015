<?php 
include_once('../etc/db.php');
include_once('session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify();

	$sql="select * from type where grp='openexam' and prm='EDISIPLIN'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['val'];
	if($sta!="1")
		echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";
		

	$schid=$_SESSION['sid'];
	$uid=$_SESSION['uid'];

			
			$sql="select count(*) from dis where stu_uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_row($res);
			$jumsalah=$row[0];
			
			$sql="select sum(val) from dis where stu_uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_row($res);
			$jumpoint=$row[0];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
		<input type="hidden" name="p" value="dis">
<div id="panelleft">
	<?php include('inc/lmenu.php');?>
</div>
<div id="content2"> 

<div align="center" style="font-size:14px; font-weight:bold; padding:2px; font-family:Arial; color:#FFFFFF; background-image:url(../img/bg_title_lite.jpg);border: 1px solid #99BBFF;">
		<?php echo strtoupper($name);?>
</div>

<div id="story">


<table width="100%" id="mytable" >
  <tr>
    <td id="mytabletitle" width="5%"><?php echo strtoupper($lg_no);?></td>
	<td id="mytabletitle" width="10%"><?php echo strtoupper($lg_date);?></td>
	<td id="mytabletitle" width="30%"><?php echo strtoupper($lg_case);?></td>
	<td id="mytabletitle" width="30%"><?php echo strtoupper($lg_action);?></td>
  </tr>


<?php 
	$q=0;
	$sql="select * from dis where stu_uid='$uid' order by id desc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$xid=$row['id'];
    	$dis=$row['dis'];
		$dt=$row['dt'];
		$act=$row['act'];
		$rep=$row['rep'];
		if(($q++%2)==0)
			$bgc="bgcolor=#FAFAFA";
		else
			$bgc="";
?>

  <tr <?php echo "$bgc";?>>
    <td id="myborder"><?php echo "$q";?></td>
	<td id="myborder"><?php echo "$dt";?></td>
	<td id="myborder"><a href="#" onClick="process_form('update',<?php echo "$xid";?>)"><?php echo "$dis";?></a></td>
    <td id="myborder"><?php echo "$act";?></td>
  </tr>


<?php } ?>
 </table> 
 

    </table>
	
	</td>

  </tr>
</table>


</div></div>
</form>


</body>
</html>
