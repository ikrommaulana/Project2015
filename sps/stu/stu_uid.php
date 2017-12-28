<?php
//10/05/2010 - update slahses
//16/04/2010 - gaji parent
//26/05/2010 - acc code
$vmod="v5.3.1";
$vdate="110409";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN|GURU');
$username = $_SESSION['username'];
$st=$_REQUEST['st'];
$id=$_REQUEST['id'];
$sid=$_REQUEST['sid'];
if($sid=="")
	$sid=$_SESSION['sid'];
$operation=$_REQUEST['operation'];
if($operation!=""){
			include_once('stu_uidsave.php');
}

		if($id!=""){
			$sql="select * from stu where id='$id'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sch_id'];
			$uid=$row['uid'];
			$namapelajar=$row['name'];
			$ic=$row['ic'];
			$tempuid=$row['tempuid'];
			$id=$row['id'];
			mysql_free_result($res);
		}


		if($rdate=="")
			$rdate=date("Y-m-d");
		
		if($sid!="0"){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            $stype=$row['level'];
			$level=$row['clevel'];
            mysql_free_result($res);					  
		}
		else
			$level="Tahap";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php include("$MYOBJ/datepicker/dp.php")?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include("$MYLIB/inc/myheader_setting.php");?>	

<script language="JavaScript">
function process_form(op){
	if(op=='delete'){
		if(document.myform.id.value==""){
			alert('Sila pilih pengguna untuk dihapus');
			return;
		}
		ret = confirm("Hapus data??");
		if (ret == true){
		    document.myform.operation.value=op;
			document.myform.submit();
		}
		return;
	
	}
	else{
		
		if((document.myform.sid.value=="")||(document.myform.sid.value=="0")){
			alert("Select school..");
			document.myform.sid.focus();
			return;
		}
		
		ret = confirm("Simpan data ?");
		if (ret == true){
			document.myform.operation.value=op;
			document.myform.submit();
		}
		return;
	}
}
</script>

</head>

<body>
<form method="post" enctype="multipart/form-data" name="myform" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="stu_uid">
<input type="hidden" name="operation" >
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000">

<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
				<?php if($id==""){ ?>
				<a href="p.php?p=stureg" id="mymenuitem"><img src="../img/new.png"><br>New</a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php } ?>
				<a href="#" onClick="process_form('<?php if($id=="")echo "insert"; else echo "update";?>')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php if(is_verify("ADMIN")&&($STU_ALLOW_DELETE==1)){ ?>
				<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php } ?>
				<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="window.close();top.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div> <!-- end mymenu -->
		<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div> <!-- end mypanel -->

<div id="story">
<div id="mytitlebg"><?php echo strtoupper($lg_administration);?>
<?php 
 if($st=="1")
	echo "<font color=blue>&lt;successfully updated&gt;</font>";
 if($st=="2")
	echo "<font color=blue>&lt;successfully deleted&gt;</font>";
 if($st=="0")
	echo "<font color=red>&lt;update failed&gt;</font>";
 if($st=="3")
	echo "<font color=red>&lt;update failed, NIS set already been used by others&gt;</font>";
?>
</div>

<table width="100%" bgcolor="#FFFFCC">
	<tr><td width="50%" valign="top" id="myborder">
	
			<table width="100%">
                <tr>
                  <td width="16%">*<?php echo $lg_school;?></td>
				  <td width="1%">:</td>
                  <td width="83%">
				  <select name="sid" id="sid" onChange="document.myform.submit()" <?php if($id!="") echo "readonly"?>>
<?php
$sname=stripcslashes($sname);
      		if($sid=="0")
            	echo "<option value=\"0\">- Pilih Sekolah -</option>";
			else
                echo "<option value=$sid>$sname</option>";
		
			if(($_SESSION['sid']==0)&&($id=="")){
				$sql="select * from sch where id>0 order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$t=$row['id'];
							$s=stripslashes($s);
							if($t==$sid){$selected="selected";}else{$selected="";}
							echo "<option value=\"$t\" $selected>$s</option>";
				}
			}						  
			
?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>*<?php echo "NIS";?></td>
				  <td>:</td>
                  <td><input name="uid" type="text" id="uid" value="<?php echo $uid;?>"><font color =red><b>** SILAHKAN MASUKKAN NIS, NIS TIDAK BOLEH SAMA ** </b></font></td>
                </tr>
				<?php if($uid!=""){$tempuid=$uid;}?>
				<input name="tempuid" type="hidden" id="tempuid" value=<?php echo $tempuid;?>>

	</table>
	
</td><td id="myborder" valign="top" width="50%">


		
				<table width="100%">
					  <tr>
						<td  width="25%">* <?php echo $lg_name;?> : <?php echo stripslashes($namapelajar);?><br><br>
							* <?php echo "No Akta Lahir";?> : <?php echo $ic;?></font>
							<br><br></td>
						
					  </tr>
				
					</table>
</td></tr></table>


</div>
</form>	
</body>
</html>
