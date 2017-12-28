<?php 
	include_once('../etc/db.php');
	include_once("$MYLIB/inc/language_$LG.php");
	$LG="INDO";

	$statusmohon="";
	$xic=$_POST['xic'];
	$xid=$_POST['xid'];
	$ic=$_REQUEST['ic'];
	$id=$_REQUEST['id'];
	if($xic!="")
		$ic=$xic;
	if($xid!="")
		$id=$xid;
		
	$fn=basename($_FILES['file1']['name']);
	if($fn!=""){
		$ext=strtok($fn,".");$ext=strtok(".");
		$fnx="i$id.$ext";
		$target_path ="../usr_images/job_img/$fnx";
		if(move_uploaded_file($_FILES['file1']['tmp_name'], $target_path)) {
			$sql="update stureg set picture='$fnx' where id='$id' and ic='$ic'";
			mysql_query($sql)or die("query failed:".mysql_error());
		}
	}
	$fn=basename($_FILES['file2']['name']);
	if($fn!=""){
		$ext=strtok($fn,".");$ext=strtok(".");
		$fnx="a$id.$ext";
		$target_path ="../usr_images/job_att/$fnx";
		if(move_uploaded_file($_FILES['file2']['tmp_name'], $target_path)) {
			$sql="update stureg set resume='$fnx' where id='$id' and ic='$ic'";
			mysql_query($sql)or die("query failed:".mysql_error());
		}
	}
	
	if(($ic!="")&&($id!="")){
		$xic=$ic;
	}
	if($xic!=""){
		//$sql="select * from stureg where ic='$xic' and id='$id' and confirm=1";
		$sql="select * from stureg where ic='$xic' and isdel=0 order by id desc";
		$res=mysql_query($sql) or die("$sql query failed:".mysql_error());
		$num=mysql_num_rows($res);
		if($num>0){	
			$row=mysql_fetch_assoc($res);
			$xname=$row['name'];
			$ic=$row['ic'];
			$id=$row['id'];
			$transid=$row['transid'];
			$xic=$row['ic'];
			$xid=$row['id'];
			$name=stripslashes($row['name']);
			$status=$row['status'];
			$job=$row['job'];
			$xdt=$row['cdate'];
			$img=$row['picture'];
			$att=$row['resume'];
			$sid=$row['sch_id'];
			
			$clssession=$row['clssession'];
			$interview_center=$row['pt'];
			$interview_date=$row['tarikhtemuduga'];
			$letter=$row['letter'];

		
			$sql="select * from type where grp='statusmohon' and val=$status";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$statusmohon=$row['prm'];
			$remark=$row['des'];
			$FOUND=1;
			
			$sql="select * from sch where id='$sid'";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$progname=stripslashes($row['name']); //school name
		}
	}	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
function process_form(op){
		if(document.myform.xic.value==""){
				alert("Masukkan KTP Siswa");
				document.myform.xic.focus();
				return;
		}
/**
		var str=document.myform.xic.value;
		if(str.search(" ")>=0){
					alert("Nomor tidak sah. Tidak boleh gunakan spasi dan strip '-'");
					document.myform.xic.focus();
					return;
		}
		if(isNaN(str)){
					alert("No tidak sah '"+str+"'. Tidak boleh gunakan spasi dan strip '-'");
					document.myform.xic.focus();
					return;
		}
**/
		document.myform.submit();
}


function chkic(){
		var str=document.myform.xic.value;

}

var newwin = "";
function newwind() 
{ 
	newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
	var a = window.setTimeout("document.myform.submit();",500);
	newwin.focus();
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
    <link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
    <script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
    
    <!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />

</head>
<body style="background:none;">

<form name="myform" method="post"  enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>" >
	<input type="hidden" name="ic" value="<?php echo $ic;?>">
	<input type="hidden" name="id" value="<?php echo $id;?>">
  
<div id="content">
<div id="mypanel" align="center">
	<div id="mymenu">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="../edaftar/cetak_pdf.php?ic=<?php echo $xic; ?>&sid=<?php echo $sid; ?>" target="_blank" id="mymenuitem"><img src="../img/printer.png"><br>Download PDF</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		
	</div>
</div>
	<br>
	<br>
	<br>
<?php
include("../inc/header.php");
?>


<div id="story">
<div id="mytitle2" align="center"> <?php echo strtoupper("$lg_registration_information");?></div>
<?php  if(!$FOUND){ ?>
<table width="100%" style="font-size:14px; ">
	<tr>
		<td valign="top" >
				<table width="100%"  border="0">
				  	<tr>
						<td valign="top">
								<table  border="0" width="100%" >
										<tr>
												<td width="100%" align="center">
												<div align="center" style="color:#FF0000; font-size:16px">
												<?php 
																if((!$FOUND)&&($xic!="")){
																	echo "Mohon Maaf, Pencarian untuk '$xic' tidak terimpan dikarenakan umur anak <br>tidak sesuai dengan batas minimal<br>";
																}
												?>
												</div>
												</td>
										</tr>
										
										<tr>
												<td align="center">
												<strong><font color="#0000FF">
                                               Masukkan No Akte Lahir / Passport untuk memastikan data telah tersimpan
                                                </strong><br>
                                                <input name="xic" type="text"  style="font-size:14px; color:#696; font-weight:bold">
                                                <input type="submit" name="Submit" value="Lanjut" onClick="process_form();return false">
                                                </td>
										</tr>
										<tr>
												<td align="center"><font color="#FF0000" size="1">IP&nbsp;Address 
                                                <a style="text-decoration:blink; color:#FF0000"><?php echo $_SERVER['REMOTE_ADDR'];?></a></font></td>
										</tr>
								</table> 

						</td>
				  	</tr>
				</table>
			</td>
	</tr>
	
</table>

<?php  } if($FOUND){ ?>
<input type="hidden" name="xic" value="<?php echo $ic;?>">
<table width="100%" style="font-size:12px; background-color:#FAFAFA;" cellpadding="3" cellspacing="2">

  <tr>
        <td width="30%"><?php echo $lg_date;?> Daftar</td>
        <td width="1%">:</td>
        <td width="70%"><?php echo strtok($xdt," ");?></font></td>
  </tr>
  <tr>
        <td><?php echo $lg_student_name;?></td>
        <td>:</td>
        <td><?php echo strtoupper("$name");?></td>
  </tr>
  <tr>
        <td>No Akte Lahir / Passport</td>
        <td>:</td>
        <td ><?php echo strtoupper("$ic");?></td>
  </tr>
  <tr>
        <td ><?php echo $lg_name_school;?></td>
        <td >:</td>
        <td ><?php echo strtoupper("$progname");?></td>
  </tr>
  <tr>
        <td width="30%" valign="top"><?php echo $lg_status;?></td>
        <td width="1%" valign="top">:</td>
        <td width="70%" valign="top"><?php echo strtoupper("$statusmohon");?>.<br><?php echo $remark;?></td>
  </tr>
<?php if($status==0){?>
 	<tr>
        <td width="30%"><?php echo $lg_profile;?> Siswa</td>
        <td width="1%">:</td>
        <td width="70%"><a href="#" onClick="document.myform.action='<?php echo $REG_FILE;?>';document.myform.target='newwindow';newwind();"> Edit Profil Siswa</a</td>
  	</tr>
<?php } ?>
<?php if($status==3){?><!-- temuduga/wawancara -->	  
	<tr>
        <td width="30%">No Registrasi</td>
        <td width="1%">:</td>
        <td width="70%"><?php echo "$transid";?></td>
	</tr>
<?php } ?>

</table>
<div style="background-color: #FAFAFA">
<?php
		if($letter!=""){
				$sql="select * from letter where name='$letter'";
				$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$content=$row['content'];
				$isheader=$row['isheader'];
				$content=stripslashes($content);
				echo $content;
		}
?>
</div>

<div id="mytitlebg" align="center" style="font-size:14px"><?php echo strtoupper("$lg_thank_you");?></div>

<?php  } // IF FOUND ?>

</div> <!--end story--> 
		<div align="center">
				<font color="#999999" size="1"><strong>Best view MOZILLA FIREFOX with 1280 by 800 pixels</strong></font>
		</div>
	
<br>
<br>
<br>
</div>
</div></div>
</form>
</body>
</html>
