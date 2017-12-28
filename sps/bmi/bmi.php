<?php
$vdate="110626";
$vmod="v6.1.0";

include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
	$adm = $_SESSION['username'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
	
	$curryear=date('Y');
	if($curryear==$year)
		$sqlstatuspelajar="and stu.status=6";
		
	$cls=$_REQUEST['clscode'];
	$mon=$_REQUEST['mon'];
	if($mon!=""){
			$sql="select * from month where no='$mon'";
            $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $monthname=$row['name'];
	}
	
	
	if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql  failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $schname=stripcslashes($row['name']);
			$sname=stripcslashes($row['sname']);
			$stype=$row['level'];
			$level=$row['clevel'];
			$schlevel=$row['lvl'];
            mysql_free_result($res);	  
	}

	if($cls!=""){
			$sql="select * from ses_cls where sch_id=$sid and cls_code='$cls'";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $lvl=$row['cls_level'];
			$clsname=stripslashes($row['cls_name']);
	}
	
	$xstu=$_REQUEST['uid'];
	$xcm=$_REQUEST['cm'];
	$xkg=$_REQUEST['kg'];
	$op=$_REQUEST['op'];
	if($op=='save'){
		
		for ($i=0; $i<count($xstu); $i++) {
			$uid=$xstu[$i];
			$cm=$xcm[$i];
			$kg=$xkg[$i];	
			$sql="delete from bmi where sid=$sid and year='$year' and mon=$mon and uid='$uid'";
			$res=mysql_query($sql)or die("$sql failed:".mysql_error());
			
			if($cm=="")
				$cm=0;
			if($kg=="")
				$kg=0;
				
			$mtr=$cm/100;
			if(($mtr*$mtr)>0)
				$bmi=round($kg/($mtr*$mtr),1);
		    else
				$bmi=0;
			$sql="insert into bmi(dt,uid,sid,cls,lvl,mon,year,cm,kg,bmi,adm,ts) values 
				(now(),'$uid','$sid','$cls','$lvl','$mon','$year','$cm','$kg','$bmi','$adm',now())";
			 $res=mysql_query($sql)or die("$sql failed:".mysql_error());
			 //echo "$sql<br>";
		}
		$mon="";
	}
	
	
	
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="asc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by sex desc, stu.name";
	else
		$sqlsort="order by $sort $order, name asc";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>ISIS</title>
<?php include("$MYLIB/inc/myheader_setting.php");?>
<SCRIPT LANGUAGE="JavaScript">
function process_myform(op){
	if(document.myform.mon.value==""){
    	alert("Please select month..");
        document.myform.mon.focus();
        return;
	}
	ret = confirm("Save the record ??");
    if (ret == true){
		document.myform.op.value=op;
        document.myform.submit();
    }
}
function process_edit(exam){
	document.myform.mon.value=exam;
	document.myform.submit();
}
</script>

</head>

<body>


<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
    <input type="hidden" name="op">
	<input name="year" type="hidden" value="<?php echo $year;?>">
	<input name="sid" type="hidden" value="<?php echo $sid;?>">
	<input name="clscode" type="hidden" value="<?php echo $cls;?>">
	<input name="subcode" type="hidden" value="<?php echo $subcode;?>">
	<input name="usr_uid" type="hidden" value="<?php echo $gid;?>">
	<input name="mon" type="hidden" value="<?php echo $mon;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
	
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="process_myform('save')" id="mymenuitem"><img src="../img/save.png"><br>SAVE</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.mon.value='';document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="../img/help22.png"><br>HowTo</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
	</div>
	<div align=right>
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>
		<br>
	</div>
</div>
<div id="story">

	<div id="mytitle2"><?php echo $lg_student_information;?> : <?php echo $lg_height_and_weight;?></div>
                         
<table width="100%" id="mytitle">
  <tr>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_school);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($schname);?></td>
		  </tr>
		</table>
	</td>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		 <tr>
			<td width="20%"><?php echo strtoupper($lg_class);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper("$clsname");?></td>
		  </tr>
		 <tr>
			<td width="20%"><?php echo strtoupper($lg_session);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper("$year");?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>
<div align="right">
<table width="50%" style="font-weight:bold">
	<tr>
    	<td id=myborder width="10%" align="center" bgcolor="<?php echo $bgkuning;?>">KURUS</td>
        <td id=myborder width="10%" align="center" bgcolor="<?php echo $bghijau;?>">IDEAL</td>
        <td id=myborder width="10%" align="center" bgcolor="<?php echo $bgoren;?>">KELEBIHAN BERAT </td>
        <td id=myborder width="10%" align="center" bgcolor="<?php echo $bglred;?>">OBESITAS LEVEL 1</td>
        <td id=myborder width="10%" align="center" bgcolor="<?php echo $bgpink;?>">OBESITAS LEVEL 2</td>
        <td id=myborder width="10%" align="center" bgcolor="<?php echo $bgmerah;?>">SANGAT OBESITAS</td>
    </tr>
</table>
</div>
<table width="100%" cellspacing="0" cellpadding="2">
<tr>
              <td id="mytabletitle" width="3%" align="center" rowspan="2" ><?php echo strtoupper($lg_no);?></td>
              <td id="mytabletitle" width="5%" align="center" rowspan="2"><a href="#" onClick="formsort('stu.uid','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_matric);?></a></td>
			  <td id="mytabletitle" width="2%" align="center" rowspan="2"><a href="#" onClick="formsort('stu.sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>	
			  <td id="mytabletitle" width="20%" rowspan="2"><a href="#" onClick="formsort('stu.name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></strong></td>
<?php
$sql="select * from month order by no";
$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
	$monthname=$row['sname'];
	$monthno=$row['no'];
	
	if(($offdate<$nowdate)&&($offdate!=""))
			$locking=1;

?>
		<td id="mytabletitle" width="10%" colspan="2" align="center">
			<?php if($locking){?>
				<img src="../img/lock8.png">&nbsp;<?php echo $monthname;?>
			<?php } else{?>
				<a href="#" onClick="process_edit('<?php echo $monthno;?>')"><img src="../img/edit12.png">&nbsp;<?php echo $monthname;?></a>
			<?php } ?>
		</td>
<?php } ?>
</tr>
<tr>
<?php for($i=1;$i<=12;$i++){ ?>
			  <td id="mytabletitle" width="3%" align="center">Cm</td>
              <td id="mytabletitle" width="3%" align="center">Kg</td>
<?php } ?>
</tr>

<?php
		$q=0;
		$sql="select ses_stu.*,stu.sex,stu.name,stu.status from ses_stu INNER JOIN stu ON ses_stu.stu_uid=stu.uid where stu.sch_id=$sid and ses_stu.cls_code='$cls' and ses_stu.year='$year' $sqlstatuspelajar $sqlsort";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$stuname=strtoupper(stripslashes($row['name']));
			$uid=$row['stu_uid'];
			$sex=$row['sex'];
			$status=$row['status'];
		

			if($q++%2==0)
				$bg="";
			else
				$bg="#FAFAFA";
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
    		<td id=myborder align=center><?php echo "$q";?></td>
			<td id=myborder align=center><?php echo "$uid";?></td>
			<td id=myborder align=center><?php echo $lg_sexmf[$sex];?></td>
			<td id=myborder><?php echo "$stuname";?></td>
            <?php for($i=1;$i<=12;$i++){ 
				$bc="";
				$cm="";
				$kg="";
				$bm="";
				$sql="select * from bmi where sid=$sid and uid='$uid' and mon=$i and year='$year'";
				$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
				if($row2=mysql_fetch_assoc($res2)){
						$cm=$row2['cm'];
						$kg=$row2['kg'];
						$bm=$row2['bmi'];
						if($bm<=0)//unset
							$bc="";
						elseif($bm<18.5)//under weight
							$bc=$bgkuning;
						elseif($bm<22.9)//idea
							$bc=$bghijau;
						elseif($bm<27.4)//over weight
							$bc=$bgoren;
						elseif($bm<34.9)//obesity class 1
							$bc=$bglred;
						elseif($bm<39.9)//obesity class 3
							$bc=$bgpink;
						else//Morbib obesity
							$bc=$bgmerah;
				}
					
			?>
            	<?php if($mon=="$i"){?>
                	<input type="hidden" name="uid[]" size="3" value="<?php echo $uid;?>">
                    <td id=myborder><input type="text" name="cm[]" size="3" value="<?php echo $cm;?>"></td>
                	<td id=myborder><input type="text" name="kg[]" size="3" value="<?php echo $kg;?>"></td>
				<?php }else{?>
                	<td id=myborder bgcolor="<?php echo $bc;?>" align="center"><?php echo "$cm";?></td>
                    <td id=myborder bgcolor="<?php echo $bc;?>" align="center"><?php echo "$kg";?></td>
                <?php } ?>
            <?php } ?>
		</tr>
        
<?php } ?>


</table>


</div> <!-- story -->
</div> <!-- content -->

</form>

</body>
</html>
