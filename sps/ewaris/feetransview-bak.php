<?php
include_once('../etc/db.php');
include_once('session.php');
verify();
//verify('ADMIN|AKADEMIK|KEWANGAN');
$username = $_SESSION['username'];
	
	$feeid=$_REQUEST['id'];
	$isprint=$_REQUEST['isprint'];
	
	$sql="select * from feeonlinetrans where id=$feeid";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$stu_id=$row['stu_id'];
	$stu_uid=$row['stu_uid'];
	$year=$row['year'];
	$total=$row['rm'];
	$paytype=$row['bank'];
	$nocek=$row['bankno'];
	$sch_id=$row['sch_id'];
	$dt=$row['cdate'];
	
	$sql="select * from sch where id='$sch_id'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$slevel=$row['level'];
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
		
	$sql="select * from ses_stu where stu_uid='$stu_uid' and year='$year'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	if($row=mysql_fetch_assoc($res))
		$clsname=$row['cls_name'];

?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<input type="hidden" name="p" value="fee">
<div id="panelleft" class="printhidden">
	<?php include('inc/lmenu.php');?>
</div> 
<div id="content2"> 
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	</div>
</div>
<?php
 	include('../inc/school_header.php');
?>
<div id="story">

 <?php
 		
	$sql="select * from stu where id='$stu_id'";
	$res=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_assoc($res);
	$stu_name=$row['name'];
	$stu_ic=$row['ic'];
	$stu_uid=$row['uid'];
	mysql_free_result($res);
	
	$sql="select * from sch where id='$sch_id'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
    $sch_name=$row['name'];
	mysql_free_result($res);
?>
<div id="mytitlebg" align="center">RESIT SEMENTARA</div>
<div id="area" style="padding:5px 5px 5px 5px ">
 <table width="100%" bgcolor="#FFFFFF">
   <tr bgcolor="#FFFFFF">

     <td><table width="100%">
       <tr>
         <td width="65%"><table width="100%" id="mytable">
             <tr>
               <td width="26%" valign="top"><?php echo strtoupper("$lg_sekolah"); ?></td>
               <td width="1%" valign="top">:</td>
               <td  valign="top"><?php echo strtoupper("$sch_name"); ?> </td>
             </tr>
             <tr>
               <td valign="top">SESI</td>
               <td valign="top">:</td>
               <td valign="top"><?php echo strtoupper("$year / $clsname");?></td>
             </tr>
             <tr>
               <td valign="top">MATRIK</td>
               <td valign="top">:</td>
               <td valign="top"><?php echo $stu_uid;?></td>
             </tr >
             <tr>
               <td valign="top">NAMA</td>
               <td valign="top">:</td>
               <td valign="top"><?php echo strtoupper("$stu_name");?> </td>
             </tr valign="top">
             <tr>
               <td valign="top">KAD PENGENALAN</td>
               <td valign="top">:</td>
               <td valign="top"><?php echo "$stu_ic";?> </td>
             </tr>
         </table></td>
         <td width="35%" valign="top"><table width="100%" id="mytable">
             <tr>
               <td width="45%" valign="top">NO. RESIT</td>
               <td width="1%" valign="top">:</td>
               <td width="54%" valign="top"><?php $ff=sprintf("%06s",$feeid); echo "$ff";?>
               </td>
             </tr>
             <tr>
               <td valign="top">TARIKH</td>
               <td valign="top">:</td>
               <td valign="top"><?php $dd=strtok($dt," "); list($y,$m,$d)=split('[/.-]',$dd); echo "$d-$m-$y";?>
               </td>
             </tr>
			 <tr>
               <td valign="top">BAYARAN</td>
               <td valign="top">:</td>
               <td valign="top"><?php echo "$paytype";?>
               </td valign="top">
			   <tr>
               <td valign="top">REF. BANK/CEK</td>
               <td valign="top">:</td>
               <td valign="top"><?php echo "$nocek";?>
               </td>
             </tr>
			 <!--
			 <tr>
               <td valign="top">Diterima Oleh </td>
               <td valign="top">:</td>
               <td valign="top"><?php echo "$username";?>
               </td>
             </tr>
			 -->
         </table></td>
       </tr>
     </table></td>
   </tr>
</table>
          <table width="100%"  id="mytabletitle">
            <tr >
              <td width="10%">BIL</td>
              <td width="60%">MAKLUMAT YURAN</td>
              <td width="30%">JUMLAH (RM)</td>
            </tr>
		</table>
	<?php	
	$sql="select * from feeonlinepay where tid=$feeid order by id";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$fee=strtoupper($row['fee']);
		$rm=$row['rm'];
		if(($q++%2)==0)
			echo "<table width=\"100%\"><tr >";
		else
			echo "<table width=\"100%\" bgcolor=#FAFAFA><tr >";

    	echo "<td width=\"10%\">$q</td>";
	   	echo "<td width=\"60%\">$fee</td>";
 		printf("<td width=\"30%%\">%.02f</td>",$rm);
  		echo "</tr></table>";
  }
  mysql_free_result($res);
  mysql_close($link);
  ?>

<table width="100%" id="mytabletitle">
  		<tr>
              <td width="10%">&nbsp;</td>
              <td width="60%">JUMLAH BAYARAN</td>
              <td width="30%"><?php printf("%.02f",$total);?></td>
        </tr>
</table> 

</div> <!-- end area -->
<table width="100%" style="font-size:70% ">
  <tr>
    <td align="center"><font face="Palatino Linotype"><?php echo "$footer_yuran";?></font></td>
  </tr>
</table> 
</div></div>
</form>
</body>
</html>
