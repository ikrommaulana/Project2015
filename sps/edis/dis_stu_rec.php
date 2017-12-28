<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];

	$sid=$_REQUEST['schid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	$uid=$_REQUEST['uid'];

	$year=date('Y');
	if($uid!=""){
		$sql="select * from stu where uid='$uid'";
		$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res)){
			$sid=$row['sch_id'];
			$uid=$row['uid'];
			$pass=$row['pass'];
			$name=$row['name'];
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
	
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
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

if($op=="save"){
	$adis=$_POST['dis'];
	$des=$_POST['des'];
	$aact=$_POST['act'];
	$rep=$_POST['rep'];
	
	$v=0;
	for ($i=0; $i<count($adis); $i++) {
		if($i>0)
		 	$dis=$dis.",";
			
		$x=$adis[$i];
		$dis=$dis.$x;
		$sql="select * from type where grp='discipline' and prm='$x'";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$v=$v+$row['val'];
	}
	for ($i=0; $i<count($aact); $i++) {
		 if($i>0)
		 	$act=$act.",";
		 $act=$act.$aact[$i];
	}
	if($id!=""){
		$sql="update dis set des='$des',rep='$rep',dis='$dis',act='$act',val=$v where id=$id";
		mysql_query($sql)or die("query failed:".mysql_error());
	}
	else{
		$sql="insert into dis(dt,sch_id,year,stu_uid,cls_code,cls_level,dis,des,act,rep,val,cls_name,stu_name) values (now(),$sid,'$year','$uid','$clscode',$clslvl,'$dis','$des','$act','$rep',$v,'$clsname','$name')";
		mysql_query($sql)or die("$sql query failed:".mysql_error());
	}
}
elseif($op=="delete"){
	$sql="delete from dis where id=$id";
	mysql_query($sql)or die("$sql query failed:".mysql_error());
	$id="";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

<?php include("$MYOBJ/calender/calender.htm")?>
<script language="JavaScript">
function process_form(action,id){
	var ret="";
	var disflag=false;
	if(action=='new'){
		document.myform.id.value="";
		document.myform.submit();
		return;
	}
	
}

</script>


</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="process_form('surat','<?php echo $month;?>','<?php echo $year;?>')" id="mymenuitem"><img src="../img/pages.png"><br>Letters</a>
	<a href="#" onClick="process_form('sms','<?php echo $month;?>','<?php echo $year;?>')" id="mymenuitem"><img src="../img/flash.png"><br>SMS</a>
	<a href="#" onClick="process_form('email','<?php echo $month;?>','<?php echo $year;?>')" id="mymenuitem"><img src="../img/email.png"><br>Email</a>
</div> <!-- end mymenu -->
<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div><!-- end mypanel -->

<div id="story">
<div id="mytitle"><?php echo strtoupper("$lg_discipline_case");?></div>
<table width="100%">
  <tr>
 
  <?php if($file!=""){?>
  	<td width="5%" align="center" id="myborder">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="72" id="myborderfull" >
	</td>
<?php } ?>
 
    <td width="50%" valign="top">	
	<table width="100%">
      <tr>
        <td width="102" ><?php echo strtoupper("$lg_name");?></td>
        <td width="2">:</td>
        <td >&nbsp;<?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper("$lg_matric");?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo strtoupper("$lg_ic_number");?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$ic";?> </td>
      </tr>
	  <tr>
        <td ><?php echo strtoupper("$lg_school");?></td>
        <td >:</td>
        <td >&nbsp;<?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("$lg_class");?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$clsname";?></td>
      </tr>
    </table>


	</td>
    <td width="40%" valign="top">
	
	<table width="100%" id="mytable">
      
      <tr>
        <td width="110"><?php echo strtoupper("$lg_parent");?></td>
        <td width="2">:</td>
        <td >&nbsp;<?php echo "$p1name";?></td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("$lg_tel_mobile");?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$hp";?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("$lg_tel_home");?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$tel";?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("$lg_total_case");?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$jumsalah";?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("Poin Pelanggaran");?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$jumpoint";?> </td>
      </tr>
    </table>
 	</td>
  </tr>
</table>

<div id="mytitlebg"><?php echo strtoupper("$lg_case_list");?></div>
<table width="100%" cellspacing="0">
  <tr id="mytabletitle">
    <td id="myborder" width="2%" align="center"><?php echo strtoupper("$lg_no");?></td>
	<td id="myborder" width="30%"><?php echo strtoupper("$lg_case_report");?></td>
  </tr>


<?php 
	$q=0;
	$sql="select * from dis where stu_uid='$uid' order by id desc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$xid=$row['id'];
    	$dis=$row['dis'];
		$dt=$row['dt'];
		$dt = strtok($dt," ");
		$act=$row['act'];
		$des=$row['des'];
		$val=$row['val'];
		$rep=$row['rep'];
		$adm=$row['adm'];
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
   <tr bgcolor="<?php echo $bg;?>" style="cursor:pointer" onMouseOver="this.bgColor='#F1F1F1';" onMouseOut="this.bgColor='<?php echo $bg?>';" onClick="javascript: href='p.php?p=disreg&schid=<?php echo "$sid&id=$xid&uid=$uid";?>'" >
    <td id="myborder" align="center" style="font-size:18px "><?php echo "$q";?></td>
	<td id="myborder" style="font-size:14px">
		<a href="disreg.php?schid=<?php echo "$sid";?>&id=<?php echo "$xid";?>&uid=<?php echo "$uid";?>">
		<?php echo "<strong>$dis</strong><br>$lg_date: $dt&nbsp;&nbsp;$lg_dimerit_point: $val<br><u>$lg_case</u><br>$des";?>
		<br><br>
		<?php echo "<u>$lg_action</u><br>$act<br>$rep";?>
		<br><br>
		<?php echo "<i>$lg_report_by:$adm";?>
		</a>
	</td>

  </tr>


<?php } ?>
 </table> 

</div></div>
</form>

</body>
</html>
