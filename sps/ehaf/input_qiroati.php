<?php
//110610 - upgrade gui
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php");
verify("");
$adm=$_SESSION['username'];
$f=$_REQUEST['f'];
$p=$_REQUEST['p'];
$sid=$_GET['sid'];
$year=$_GET['year'];
$smt=$_GET['smt'];
$cls=$_GET['lvl'];
$uid=$_GET['uid'];
$exam=$_GET['exam'];

if($uid!=""){
	$cek=mysql_query("SELECT * FROM stu WHERE uid='$uid'") or die (mysql_error());
	$read=mysql_fetch_array($cek);
	$name=$read['name'];
}

if($sid==""){
		$sid=$_SESSION['sid'];	
}
$sql="select * from sch where id='$sid'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$sname=stripslashes($row['sname']);
$sqlsid="and sid='$sid'";

if($sid!=="")
		$sqlsid=" and sid='$sid'";

if($cls!=="")
		$sqlcls=" and lvl='$cls'";

if($smt!=="")
		$sqlsmt=" and sem='$smt'";
		
if($year!=="")
		$sqlyear=" and year='$year'";

if($exam!=="")
		$sqlexam=" and exam='$exam'";
if($uid!=="")
		$sqluid=" and uid='$uid'";
		
$id=$_REQUEST['id'];
$op=$_REQUEST['op'];
if($op=="delete"){
		$sql="DELETE FROM surah_stu_status where id='$id'";
		mysql_query($sql)or die("query failed:".mysql_error());		
		$f="<font color=blue>&lt;RECORD DELETED&gt;</font>"; 
}
if($op=="save"){
		$status=$_REQUEST['status'];
		$cls=$_REQUEST['cls'];
		$sid=$_REQUEST['sid'];
		$jilid=$_REQUEST['jilid'];
		$smt=$_REQUEST['smt'];
		$year=$_REQUEST['year'];
                $uid=$_REQUEST['uid'];
                $exam=$_REQUEST['exam'];
                $des=$_REQUEST['des'];
		if($id>0){
			$sql="update surah_stu_status set surahname='$jilid',adm='$adm',status='$status',ts=now(),des='$des' where id=$id";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			header ("location:input_qiroati.php?sid=$sid&year=$year&smt=$smt&cls=$cls&exam=$exam&uid=$uid");
		}else{
			$sql="insert into surah_stu_status (sid,lvl,surahname,status,grp,adm,ts,sem,year,exam,uid,des)values
				('$sid','$cls','$jilid','$status','qiroati','$adm',now(),'$smt','$year','$exam','$uid','$des')";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			header ("location:input_qiroati.php?sid=$sid&year=$year&smt=$smt&cls=$cls&exam=$exam&uid=$uid");
		}
		$id="";
		$f="<font color=blue>&lt;SUCCESSFULY SAVE&gt;</font>";		
}
if($id!=""){
			$sql="select * from surah_stu_status where id='$id'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
  			$row=mysql_fetch_assoc($res);
			$id=$row['id'];
			$sid=$row['sid'];
			$cls=$row['lvl'];
			$status=$row['status'];
			$smt=$row['sem'];
			$jilid=$row['surahname'];
			$year=$row['year'];
			$uid=$row['uid'];
                        $des=$row['des'];
                        $exam=$row['exam'];
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>

<script language="javascript">
function process_form(action,xid){
	var ret="";
	var cflag=false;
	
	if(action=='new'){
		document.myform.prm.readOnly=false;
		document.myform.id.value="";
		document.myform.item.value="";
		document.myform.idx.value="";
		return;
	}
	if(action=='save'){
		if(document.myform.jilid.value==""){
			alert("Tulis jilid");
			document.myform.jilid.focus();
			return;
		}
		if(document.myform.status.value==""){
			alert("Tulis status");
			document.myform.status.focus();
			return;
		}
                if(document.myform.des.value==""){
			alert("Tulis komentar");
			document.myform.des.focus();
			return;
		}
                if(document.myform.smt.value==""){
			alert("Pilih semester ");
			document.myform.smt.focus();
			return;
		}
                if(document.myform.exam.value==""){
			alert("Tulis ujian");
			document.myform.exam.focus();
			return;
		}

		document.myform.op.value=action;
		document.myform.submit();		
		return;
	}
	if(action=='delete'){
		ret = confirm("Are you sure want to DELETE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
}

</script>

</script>

</head>

<body >

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
 	<input type="hidden" name="p" value="<?php echo $p;?>">
 	<input type="hidden" name="op">
	<input type="hidden" name="xid">
	<input type="hidden" name="id" id="id" value="<?php echo $id;?>">

	
<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
		<a href="#" onClick="show('panelform');process_form('new');" id="mymenuitem"><img src="../img/new.png"><br>New</a>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
				<div id="mymenu_seperator"></div>
				<div id="mymenu_space">&nbsp;&nbsp;</div>        
		<a href="#" onClick="show('panelform');process_form('save');"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
				<div id="mymenu_seperator"></div>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="process_form('delete')"id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
				<div id="mymenu_seperator"></div>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.close();parent.$.fancybox.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
				<div id="mymenu_seperator"></div>
				<div id="mymenu_space">&nbsp;&nbsp;</div>       
		</div> <!-- end mymenu -->

		<div align="right"  >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
</div> <!-- end mypanel -->
<div id="story" style="min-height:none;">
		<strong><?php echo $f; ?></strong><br><br>
<div id="panelform" style="display:<?php if($id=="") echo "none";else echo "block";?>">
<table width="100%" id="mytitle" cellspacing="0" cellpadding="2px" style="font-size:11px">
   <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
	<td id="myborderbottom">Nama Jilid</td>
	<td><input type="text" name="jilid" value="<?php echo $jilid; ?>"></td>
    </tr>                 
    <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
	<td id="myborderbottom">Status</td>
	<td><input type="text" name="status" value="<?php echo $status; ?>"></td>
    </tr>
    <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
	<td id="myborderbottom">Komentar</td>
	<td><textarea name="des" cols="50" rows="5"><?php echo $des; ?></textarea></td>
	<input name="cls" type="hidden" value="<?php echo $cls;?>">
	<input name="a" type="hidden" value="<?php echo $id;?>">
	<input name="sid" type="hidden" value="<?php echo $sid;?>">
	<input name="year" type="hidden" value="<?php echo $year;?>">
	<input name="smt" type="hidden" value="<?php echo $smt;?>"></td>
        <input name="exam" type="hidden" value="<?php echo $exam;?>"></td>
        <input name="uid" type="hidden" value="<?php echo $uid;?>"></td>
    </tr>                   		
</table>
</div>

<div id="mytabletitle">
	  
</div>
<strong> <?php echo $name; ?></strong>
</br>

</br>
<table width="100%" cellspacing="0">
<tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td class="mytableheader" style="border-right:none;" width="10%" align="center">No</td>
		<td class="mytableheader" style="border-right:none;" width="25%" align="center">Nama Jilid</td>
		<td class="mytableheader" style="border-right:none;" width="20%" align="center">Status</td>
                <td class="mytableheader" style="border-right:none;" width="45%" align="center">Komentar</td>
</tr>
<?php	
            $sql1="select * from surah_stu_status where grp='qiroati' and sid='$sid' and lvl='$cls' and sem='$smt' and year='$year' and exam='$exam' and uid='$uid' order by dt asc";
            $read=mysql_query($sql1)or die("query failed:".mysql_error());
	    $b=1;
            while($show=mysql_fetch_array($read)){
                $id=$show['id'];
                $des=$show['des'];
                $surah=$show['surahname'];
                $status=$show['status'];
                echo "<tr>
			<td class=\"myborder\" style=\"border-right:none; border-top:none;\" align=\"center\"> $b</td>	
                    <td class=\"myborder\" style=\"border-right:none; border-top:none;\" align=\"left\"> <a href=\"#\" onClick=\"document.myform.id.value=$id;document.myform.submit();\" title=\"edit\" > $surah </a></td>
                <td class=\"myborder\" style=\"border-right:none; border-top:none;\" align=\"center\">$status</td>
                <td class=\"myborder\" style=\"border-right:none; border-top:none;\" align=\"center\">$des</td>
                </tr>";
            $b++;
        }
						
?>


</table>
</div><!-- story -->
</div><!-- content -->
</form>	
</body>
</html>
