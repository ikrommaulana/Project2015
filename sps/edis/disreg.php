<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$adm=$_SESSION['username'];

	

	$uid=$_REQUEST['uid'];
	$year=date('Y');

		$sql="select * from stu where uid='$uid'";
		$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res)){
			$schid=$row['sch_id'];
			$uid=$row['uid'];
			$pass=$row['pass'];
			$name=stripslashes($row['name']);
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
	
			$sql="select * from ses_stu where stu_uid='$uid' and sch_id=$schid and year='$year'";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$clsname=$row['cls_name'];
			$clscode=$row['cls_code'];
			$clslvl=$row['cls_level'];
			if($clslvl=="")
				$clslvl=0;
			
			$sql="select * from sch where id='$schid'";
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

	else{
		$FOUND=0;
	}
	
	
	if($schid!=0){
			$sql="select * from sch where id=$schid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            mysql_free_result($res);					  
		}
		
	$op=$_REQUEST['op'];
	$id=$_REQUEST['id'];

if($op=="save"){
	$kes=$_POST['kes'];
	$des=$_POST['des'];
	$rep=$_POST['rep'];
	$actlist=$_POST['actlist'];
	
	$v=0;
	$x=strtok($kes,",");
	while($x!=NULL){
		$x=trim($x);
		$sql="select * from dis_case where prm='$x'";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$v=$v+$row['val'];
		$x=strtok(",");
	}
	if($id!=""){
		$sql="update dis set des='$des',rep='$rep',dis='$kes',act='$actlist',val=$v where id=$id";
		mysql_query($sql)or die("query failed:".mysql_error());
		echo "<script language=\"javascript\">location.href='p.php?p=dis_stu_rec&schid=$schid&uid=$uid'</script>";
	}
	else{
		$name=addslashes($name);
		$sql="insert into dis(dt,sch_id,year,stu_uid,cls_code,cls_level,dis,des,act,rep,val,cls_name,stu_name,adm,ts,cat) values (now(),$schid,'$year','$uid','$clscode',$clslvl,'$kes','$des','$actlist','$rep',$v,'$clsname','$name','$adm',now(),'demerit')";
		mysql_query($sql)or die("$sql query failed:".mysql_error());
		echo "<script language=\"javascript\">location.href='p.php?p=dis_stu_rec&schid=$schid&uid=$uid'</script>";
	}
}
elseif($op=="delete"){
	$sql="delete from dis where id=$id";
	mysql_query($sql)or die("$sql query failed:".mysql_error());
	$id="";
	echo "<script language=\"javascript\">location.href='p.php?p=dis_stu_rec&schid=$schid&uid=$uid'</script>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<?php include("$MYOBJ/calender/calender.htm")?>
<script language="JavaScript">
function process_form(action,id){
	var ret="";
	var disflag=false;
	if(action=='new'){
		document.myform.id.value="";
		document.myform.des.value="";
		document.myform.rep.value="";
		document.myform.kes.value="";
		document.myform.actlist.value="";
		return;
	}
	if(action=='update'){
		document.myform.id.value=id;
		document.myform.submit();
		return;
	}
	if(action=='refresh'){
		document.myform.submit();
		return;
	}
	if(action=='search'){
		document.myform.id.value="";
		document.myform.p.value="dis_stu_rec";
		document.myform.submit();
		return;
	}
	if(action=='save'){
		
		if(document.myform.uid.value==""){
			alert("Sorry. No student record..");
			document.myform.search.value="";
			document.myform.search.focus();
			return;
		}
		if(document.myform.kes.value==""){
			alert("Please enter student case..");
			document.myform.kes.focus();
			return;
		}
		if(document.myform.actlist.value==""){
			alert("Please enter action taken..");
			document.myform.actlist.focus();
			return;
		}
		
		if(document.myform.des.value==""){
			alert("Please enter case report..");
			document.myform.des.focus();
			return;
		}
		
		ret = confirm("Are you sure want to SAVE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
	if(action=='delete'){
		if(document.myform.id.value==""){
			alert('Please click the item to delete');
			return;
		}
		ret = confirm("Are you sure want to delete??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
}


</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>

<body>
<form name="myform" method="post" action="">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="op" value="">
	<input type="hidden" name="p" value="disreg">
	

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="process_form('new')" id="mymenuitem"><img src="../img/new.png"><br>New</a>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
                <div id="mymenu_seperator"></div>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="process_form('save')" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
                <div id="mymenu_seperator"></div>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
                <div id="mymenu_seperator"></div>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
</div> <!-- end mymenu -->
<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div><!-- end mypanel -->
<div id="story">
<div id="mytitle"><?php echo strtoupper("$lg_discipline_case");?></div>
<table width="100%">
  <tr>

<?php if($file!=""){?>
  	<td width="5%" align="center" valign="top">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="72" id="myborderfull" >
	</td>
<?php } ?>
 
    <td width="50%" valign="top">	
	<table width="100%" id="mytable">
      <tr>
        <td width="102" ><?php echo strtoupper("$lg_name");?></td>
        <td width="2">:</td>
        <td >&nbsp;<?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper("$lg_matric");?></strong></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo strtoupper("$lg_ic_number");?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$ic";?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("$lg_school");?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("$lg_class");?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$clsname";?></td>
      </tr>
    </table>


	</td>
    <td width="40%" valign="top">
	
	<table width="100%">
      
      <tr>
        <td width="110"><?php echo strtoupper("$lg_parent");?></td>
        <td width="2">:</td>
        <td >&nbsp;<?php echo "$p1name";?></td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("$lg_tel_mobile");?></strong></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$hp";?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("$lg_tel_home");?></strong></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$tel";?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("$lg_total_case");?></strong></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$jumsalah";?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("POIN PELANGGARAN");?></strong></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$jumpoint";?> </td>
      </tr>
    </table>
 	</td>
  </tr>
</table>


<?php 
if($id>0){
	$sql="select * from dis where id='$id' order by id desc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
   	$xdis=$row['dis'];
	$xdes=$row['des'];
	$xact=$row['act'];
	$xrep=$row['rep'];
}
?>

<table width="100%">
  <tr>
 <td width="100%" valign="top">
 	<table width="100%"  border="0" cellpadding="0" >
	  <tr>
		<td width="50%" id="mytitle">
		<a href="#" onClick="newwindow('dis_case_list.php?cs=demerit',0)"><img src="../img/edit12.png"><?php echo strtoupper("$lg_case");?>&gt;&gt;</a><br>
 	  	<textarea name="kes" rows="2" style="width:95% " readonly><?php echo "$xdis";?></textarea>
		</td>
		<td width="50%" id="mytitle"><a href="#" onClick="newwindow('dis_act_list.php',0)"><img src="../img/edit12.png"><?php echo strtoupper("$lg_action");?>&gt;&gt;</a><br>
	  	<textarea name="actlist" rows="2" style="width:90% " readonly><?php echo "$xact";?></textarea>
	  </td>
	  </tr>
	  <tr>
		<td width="50%">
			<div id="mytitle"><?php echo strtoupper("$lg_case_report");?></div>
  			<textarea name="des" rows="10" style="width:95% "><?php echo "$xdes";?></textarea>
		</td>
		<td>
			<div id="mytitle"><?php echo strtoupper("$lg_action_report");?></div>
			<textarea name="rep" rows="10" style="width:90% "><?php echo "$xrep";?></textarea>
	  </td>
	  </tr>
	</table>

 	
		
	
  	
	

</td>


  </tr>
</table>

</div></div>
</form>

</body>
</html>
