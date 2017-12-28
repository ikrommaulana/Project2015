<?php
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];

	$uid=$_REQUEST['uid'];


	$year=$_REQUEST['year'];
	if($year=="")
	    $year=date('Y');
	    
	if($uid!=""){
		$sql="select * from stu where uid='$uid'";
		$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res)){
			$sid=$row['sch_id'];
			$uid=$row['uid'];
			$pass=$row['pass'];
			$name=$row['name'];
			$sid=$row['sch_id'];
			$ic=$row['ic'];
			$sex=$row['sex'];
			$race=$row['race'];
			$religion=$row['religion'];
			$bday=$row['bday'];
			list($y,$m,$d)=split('[/.-]',$bday);
			$tel=$row['tel'];
			$tel2=$row['tel2'];
			$fax=$row['fax'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$addr=$row['addr'];
			$state=$row['state'];
			$file=$row['file'];
			$rdate=$row['rdate'];
				
			$p1name=$row['p1name'];
			$p1ic=$row['p1ic'];
			$p1tel=$row['p1tel'];
			$p1tel2=$row['p1tel2'];
			$p1hp=$row['p1hp'];
			$p2name=$row['p2name'];
			$p2ic=$row['p2ic'];
			$p2tel=$row['p2tel'];
			$p2tel2=$row['p2tel2'];
			$p2hp=$row['p2hp'];
	
		        $sql="select * from ses_stu where stu_uid='$uid' and sch_id=$sid and year='$year'";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$clsname=$row['cls_name'];
		        $clscode=$row['cls_code'];
			$clslvl=$row['cls_level'];
			
			$sql="select * from sch where id='$sid'";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sname=$row['name'];
			$stype=$row['level'];
			$level=$row['clevel'];
			mysql_free_result($res);	
			$FOUND=1;
			
			$sql="select count(*) from dis where stu_uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_row($res);
			$jumsalah=$row[0];
			
			$sql="select sum(val) from dis where stu_uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_row($res);
			$jumpoint=$row[0];
		}
	}
	else{
		$FOUND=0;
	}
	
	
	if($sid!=0){
			$sql="select * from sch where id=$sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            mysql_free_result($res);					  
		}
		
	$op=$_REQUEST['op'];
	$id=$_REQUEST['id'];



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<input type="hidden" name="uid" value="<?php echo "$uid";?>">
		<input type="hidden" name="sid" value="<?php echo "$sid";?>">
		<input type="hidden" name="year" value="<?php echo "$year";?>">
		<input type="hidden" name="p" value="../edis/dis_stu_rep">
</form>
<div id="content">
<div id="mypanel" class="printhidden">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>
</div>
<div id="story">
<div id="mytitle"><?php echo strtoupper("Catatan Kedisiplinan");?></div>
<table width="100%">
  <tr>
  	<?php if($file!=""){?>
  	<td width="5%" align="center" id="myborder">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="72" id="myborderfull" >
	</td>
<?php } ?>
    <td valign="top">	
	<table width="100%" >
      <tr>
        <td width="102" ><?php echo strtoupper($lg_name);?></td>
        <td width="2">:</td>
        <td >&nbsp;<?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_matric);?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_ic_number);?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$ic";?> </td>
      </tr>
	  <tr>
        <td ><?php echo strtoupper($lg_school);?></td>
        <td >:</td>
        <td >&nbsp;<?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td><?php echo strtoupper($lg_class);?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$clsname / $year";?></td>
      </tr>
    </table>


	</td>
    <td valign="top">
	
	<table width="100%">
	  <tr>
        <td width="50%"><?php echo strtoupper($lg_total_case);?></strong></td>
        <td width="1%">:</td>
        <td><?php echo "$jumsalah";?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("Poin pelanggaran");?></strong></td>
        <td>:</td>
        <td><?php echo "$jumpoint";?> </td>
      </tr>
    </table>
 	</td>
  </tr>
</table>

<div id="mytitlebg"><?php echo strtoupper("Catatan Kedisiplinan");?></div>
<table width="100%" id="mytable" cellpadding="2px">
  <tr id="mytabletitle">
    <td width="2%" align="center" valign="top"><?php echo strtoupper($lg_no);?></td>
	<td width="30%"><?php echo strtoupper($lg_case_report);?></td>
	<td width="30%"><?php echo strtoupper($lg_action_report);?></td>
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
		$des=$row['des'];
		$rep=$row['rep'];
		if(($q++%2)==0)
			$bgc="";
		else
			$bgc="bgcolor=#FAFAFA";
?>

  <tr <?php echo "$bgc";?>>
    <td align="center" valign="top"><?php echo "$q";?></td>
	<td valign="top"><?php echo "$dt<br>$dis<br><br>$des";?></td>
    <td valign="top"><?php echo "$act<br><br>$rep";?></td>
  </tr>


<?php } ?>
 </table> 


</div></div>

</body>
</html>
