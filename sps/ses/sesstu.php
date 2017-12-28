<?php
//160910 5.0.0 - update gui
$vmod="v6.0.0";
$vdate="110729";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU');
$adm = $_SESSION['username'];

			$sid=$_REQUEST['sid'];
			$year=$_REQUEST['year'];
			$cyear=date('Y');
			if($year==$cyear)
				$sqlstustatus="and status=6";
				
			$cls_code=$_REQUEST['clscode'];
			
			$allstatus=$_REQUEST['allstatus'];
			if($allstatus){
				$sqlstustatus="";
			}else{
				$sqlstustatus=" and stu.status='6'";
			}

			$sql="select * from ses_cls where sch_id=$sid and year='$year' and cls_code='$cls_code'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
			$cls_name=stripslashes($row['cls_name']);
			$cls_level=$row['cls_level'];
			$usr_name=$row['usr_name'];
            mysql_free_result($res);					  
		
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$stype=$row['level'];
			$level=$row['clevel'];
           	$issemester=$row['issemester'];
			$startsemester=$row['startsemester'];
			/*if($issemester){
				$xx=$year+1;
				$sesyear="$year/$xx"; 
			}else{
				$sesyear="$year";
			}*/				  
				
		//$sql="select count(*) from ses_stu where sch_id=$sid and year='$year' and cls_code='$cls_code'";
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and ses_stu.cls_code='$cls_code' and year='$year' $sqlstustatus";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_row($res);
		$total=$row[0];


/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort==""){
		//$sqlsort="order by sex desc, name asc";
		$sqlsort="order by name asc";
	}else{
		//$sqlsort="order by $sort $order, name asc";
		$sqlsort="order by $sort $order";
	}
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo strtoupper($lg_student);?> : <?php echo strtoupper($cls_name);?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

</head>
<body >
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<input type="hidden" name="year" value="<?php echo "$year";?>">
		<input type="hidden" name="sid" value="<?php echo "$sid";?>">
		<input type="hidden" name="clscode" value="<?php echo "$cls_code";?>">
		<input type="hidden" name="p" value="<?php echo "$p";?>">
		<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
		<input name="order" type="hidden" id="order" value="<?php echo $order;?>">

 

<div id="content">
<div id="mypanel" class="printhidden">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.close()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br><?php echo $lg_close;?></a>
	</div>
	<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div>
<div id="story">
<div id="mytitle2"><?php echo "$lg_class $cls_name";?></div>
		  
<table width="100%" id="mytitle">
  <tr>
    <td width="50%" valign="top">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo $lg_school;?></td>
			<td width="1%">:</td>
			<td><?php $sname=stripcslashes($sname);echo $sname;?></td>
		  </tr>
		  <tr>
			<td><?php echo $lg_session;?></td>
			<td>:</td>
			<td><?php echo "$year";?></td>
		  </tr>
		  <tr>
			<td><?php echo $lg_class;?></td>
			<td>:</td>
			<td><?php echo "$cls_name ";?></td>
		  </tr>
		  <tr>
			<td><?php echo $lg_teacher;?></td>
			<td>:</td>
			<td><?php echo $usr_name;?></td>
		  </tr>
		</table>
	</td>
    <td width="50%" valign="top">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo "$lg_total $lg_student";?></td>
			<td width="1%">:</td>
			<td><?php echo "$total";?></td>
		  </tr>
		  <tr>
			<td width="20%">&nbsp;</td>
			<td width="1%">&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td width="20%">&nbsp;</td>
			<td width="1%">&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr class="printhidden">
			<td width="20%"><?php echo "Papar Semua Status";?></td>
			<td width="1%">:</td>
			<td><input type="checkbox" name="allstatus" value="1" onchange="document.myform.submit();" <?php if($allstatus) echo "checked";?>></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>


<table width="100%" cellspacing="0" cellpadding="3">
		<tr>
              <td class="mytableheader" style="border-right:none;" align="center" width="3%"><?php echo strtoupper($lg_no);?></td>
              <td class="mytableheader" style="border-right:none;" align="center" width="7%"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?></a></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="2%"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_mf);?></a></td>
              <td class="mytableheader" style="border-right:none;" width="50%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			  <td class="mytableheader" style="border-right:none;" width="10%"><a href="#" onClick="formsort('ic','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_ic_number);?></a></td>
			  <td class="mytableheader" style="border-right:none;" width="50%"><a href="#" onClick="formsort('status','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_status);?></a></td>
        </tr>

<?php
		//$sql="select stu.* from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and ses_stu.cls_code='$cls_code' and year='$year' $sqlstustatus $sqlsort";
		$sql="select stu.* from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and ses_stu.cls_code='$cls_code' and year='$year' $sqlstustatus $sqlsort";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$name=strtoupper(stripslashes($row['name']));
			$uid=$row['uid'];
			$sex=$row['sex'];
			$kp=$row['ic'];
			$sta=$row['status'];
			$sql="select * from type where grp='stusta' and val='$sta'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$status=$row2['prm'];
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
              <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
              <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$uid";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $lg_sexmf[$sex]; ?></td>
              <td class="myborder" style="border-right:none; border-top:none;" ><?php echo "$name";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;" ><?php echo "$kp";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;" ><?php echo "$status";?></td>
	</tr>


<?php 
		}
		
?>

</table>
</div></div>
</form>
</body>
</html>
