<?php
	
	include_once('../etc/db.php');

	
    $sql="select * from type where grp='openexam' and prm='EPENDAFTARAN'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['val'];
	if($sta!="1")
		echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";
	
	$id=$_REQUEST['id'];
	$ic=$_REQUEST['ic'];

	$sql="select * from stureg where id='$id' and ic='$ic'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sid=$row['sch_id'];
	$name=$row['name'];
	$transid=$row['transid'];
	$cdate=$row['cdate'];
			
	$sql="select * from sch where id=$sid";
    $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $progname=$row['name']; //school name
	$slevel=$row['level'];
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>


<div id="content"> 
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Cetak</a>
</div> <!-- end mymenu -->
</div>

<div id="story" >

<?php include('../inc/school_header.php')?>

<div id="mytitlebg" style="font-size:120%; color:#FF0000 ">
	MAAF! PERMOHONAN TELAH WUJUD<br>
	SILA HUBUNGI <?php echo strtoupper($organization_name);?> SEPERTI ALAMAT DI ATAS JIKA TERDAPAT SEBARANG MASALAH. TERIMA KASIH
</div>
<table width="100%" id="mytitle">
  <tr>
    <td width="20%">RUJUKAN</td>
    <td width="1%">:</td>
    <td width="80%"><?php echo date('Y');echo "/$transid";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TARIKH MOHON : <?php echo strtok($cdate," ");?></font></td>
  </tr>
  <tr>
    <td width="16%" >NAMA PELAJAR </td>
    <td width="1%" >:</td>
    <td width="83%" ><font color="#0000FF"><?php echo strtoupper("$name");?></font></td>
  </tr>
  <tr>
    <td >KAD PENGENALAN</td>
    <td >:</td>
    <td ><font color="#0000FF"><?php echo strtoupper("$ic");?></font></td>
  </tr>
  <tr>
    <td >PERMOHOHAN UNTUK <?php echo strtoupper("$lg_sekolah"); ?></td>
    <td >:</td>
    <td ><font color="#0000FF"><?php echo strtoupper("$progname");?></font></td>
  </tr>
</table>

</div></div>

</form>	
</body>
</html>
