<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
				
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            mysql_free_result($res);					  
		}
		
		$uid=$_REQUEST['uid'];
		if($uid!=""){
			$sql="select * from stu where sch_id=$sid and uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $xname=$row['name'];
			$xic=$row['ic'];
			$file=$row['file'];
			
			$cname="Tiada";
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$clsname=$row2['cls_name'];
				$clscode=$row2['cls_code'];
				$clslevel=$row2['cls_level'];
			}
			
		
			$dt=$_POST['dt'];
			if($dt=="")
				$dt=date("Y-m-d");
			
			list($y,$m,$d)=explode("-",$dt);
			
			
			$op=$_POST['op'];
			
			$ms=0;$juz=0;
			$sql="select * from hafazan where sid=$sid and uid='$uid' and curr_juzuk<30 order by id desc limit 1";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$xms=$row2['ms'];
			$xmss=$row2['mss'];
			$xno=$row2['no'];
			$xjk=$row2['juzuk'];
			
			$sql="select * from hafazan where sid=$sid and uid='$uid' and curr_juzuk=30 order by id desc limit 1";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$xms30=$row2['ms'];
			$xmss30=$row2['mss'];
			$xno30=$row2['no'];
			$xjk30=$row2['juzuk'];
		}
/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==0)
		$MAXLINE=30;
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by id $order";
	else
		$sqlsort="order by $sort $order, id desc";


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">

<?php include("$MYOBJ/calender/calender.htm")?>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

</head>

<body>
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="javascript: href='stu_info.php?p=../ehaf/hafazan_stu_rep&sid=<?php echo $sid;?>&uid=<?php echo $uid;?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>
<div id="right" align="right">v3.1.1</div>
</div><!-- end mypanel-->
<div id="story">

<table width="100%" id="mytitle">
  <tr>
    <td width="100%" align="left">HAFAZAN - <?php echo strtoupper($sname);?></td>
  </tr>
</table>
<table width="100%" border="0" id="mytitle" style="background:none">
  <tr>
<?php if($file!=""){?>
  	<td width="5%" align="center" valign="top">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="72" id="myborderfull" >
	</td>
<?php } ?>
  	<td width="50%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="14%">Nama</td>
        <td width="1%">:</td>
        <td width="85%"><?php echo "$xname";?></td>
      </tr>
      <tr>
        <td>Matrik</td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td>Mykad</td>
        <td>:</td>
        <td><?php echo "$xic";?> </td>
      </tr>
	  <tr>
        <td><?php echo "$lg_sekolah";?></td>
        <td>:</td>
        <td><?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td>Kelas</td>
        <td>:</td>
        <td><?php echo "$clsname/$year";?> </td>
      </tr>
    </table>


	</td>
    <td width="50%" valign="top">
	
	<table width="100%">
	  <tr>
        <td width="30%">Juzuk Semasa</td>
        <td width="1%">:</td>
        <td width="69%"><?php 
  if($xms>0){
  $jzk_semasa=($xms-1)/20+1;
  printf("%d",$jzk_semasa);
  }
  else{
  	$jzk_semasa=($xms30-1)/20+1;
  	printf("%d",$jzk_semasa);
  }
  ?></td>
      </tr>
	  <tr>
        <td>M/S&nbsp;Hafalan&nbsp;Terakhir</td>
        <td>:</td>
		<td><?php if($xms>0) echo "$xms"; else echo "$xms30";?></td>
      </tr>
	  <tr>
        <td>M/S&nbsp;Bacaan&nbsp;Semasa</td>
        <td>:</td>
		<td><?php if($xmss>0) echo "$xmss"; else echo "$xmss30";?></td>
      </tr>
	  <tr>
        <td>No&nbsp;Ayat</td>
        <td>:</td>
		<td><?php if($xmss>0) echo "$xno"; else echo "$xno30";?></td>
      </tr>
    </table>
 	</td>
  </tr>
</table>


<table width="100%">
	<tr>
		<td width="3%" id="mytabletitle">Juzuk&nbsp;1</td>
<?php 	for($i=1,$j=2,$k=1;$i<581;$i++,$j++){ ?>
<?php if($j<=$xms){ ?>
			<td width="3%" id="myborder" bgcolor="#66FF66" align="center">
				<div style="float:left;width:50%;" align="right"><?php echo $j;?></div>
				<div style="float:left;width:50%;">&nbsp;</div>
			</td>
<?php 	} elseif($j<=$xmss) { ?>
			<td align="center" width="3%" id="myborder" bgcolor="#FFFF00">
				<div id="xxx<?php echo $i;?>" style="float:left;width:50%;" onMouseOver="myin(<?php echo $i;?>,0);" onMouseOut="myout(<?php echo $i;?>,0);" onClick="process_mouse(<?php echo $j;?>,0)" align="right"><?php echo $j;?></div>
				<div id="yyy<?php echo $i;?>" style="float:left;width:50%;" onMouseOver="myin(<?php echo $i;?>,1);" onMouseOut="myout(<?php echo $i;?>,1);" onClick="process_mouse(<?php echo $j;?>,1)">&nbsp;</div>
			</td>
<?php 	} else { ?>
			<td align="center" width="3%" id="myborder">
				<div id="xxx<?php echo $i;?>" style="float:left;width:50%;" onMouseOver="myin(<?php echo $i;?>,0);" onMouseOut="myout(<?php echo $i;?>,0);" onClick="process_mouse(<?php echo $j;?>,0)" align="right"><?php echo $j;?></div>
				<div id="yyy<?php echo $i;?>" style="float:left;width:50%;" onMouseOver="myin(<?php echo $i;?>,1);" onMouseOut="myout(<?php echo $i;?>,1);" onClick="process_mouse(<?php echo $j;?>,1)">&nbsp;</div>
			</td>
<?php } ?>

<?php 
	if($i%20==0){
		echo"</tr><tr>";
		$k++;
		echo "<td width=\"3%\" id=\"mytabletitle\">Juzuk&nbsp;$k</td>";
	}
} 
?>


<?php 	for(;$i<604;$i++,$j++){ ?>
<?php if($j<=$xms30){ ?>
			<td width="3%" id="myborder" bgcolor="#66FF66" align="center">
				<div style="float:left;width:50%;" align="right"><?php echo $j;?></div>
				<div style="float:left;width:50%;">&nbsp;</div>
			</td>
<?php 	} elseif($j<=$xmss30) { ?>
			<td align="center" width="3%" id="myborder" bgcolor="#FFFF00">
				<div id="xxx<?php echo $i;?>" style="float:left;width:50%;" onMouseOver="myin(<?php echo $i;?>,0);" onMouseOut="myout(<?php echo $i;?>,0);" onClick="process_mouse(<?php echo $j;?>,0)" align="right"><?php echo $j;?></div>
				<div id="yyy<?php echo $i;?>" style="float:left;width:50%;" onMouseOver="myin(<?php echo $i;?>,1);" onMouseOut="myout(<?php echo $i;?>,1);" onClick="process_mouse(<?php echo $j;?>,1)">&nbsp;</div>
			</td>
<?php 	} else { ?>
			<td align="center" width="3%" id="myborder">
				<div id="xxx<?php echo $i;?>" style="float:left;width:50%;" onMouseOver="myin(<?php echo $i;?>,0);" onMouseOut="myout(<?php echo $i;?>,0);" onClick="process_mouse(<?php echo $j;?>,0)" align="right"><?php echo $j;?></div>
				<div id="yyy<?php echo $i;?>" style="float:left;width:50%;" onMouseOver="myin(<?php echo $i;?>,1);" onMouseOut="myout(<?php echo $i;?>,1);" onClick="process_mouse(<?php echo $j;?>,1)">&nbsp;</div>
			</td>
<?php } ?>

<?php 
	if($i%20==0){
		echo"</tr><tr>";
		//$k++;
		echo "<td width=\"3%\" id=\"mytabletitle\">Juzuk&nbsp;$k</td>";
	}
} 
?>
  </tr>
</table>

</div></div>


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->