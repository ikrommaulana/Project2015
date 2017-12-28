<?php
include_once('../etc/config.php');
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN');
$username = $_SESSION['username'];
$op=$_REQUEST['op'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	if($sid!=0){
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$stype=$row['level'];
		$level=$row['clevel'];
		$pref=$row['stuprefix'];
        mysql_free_result($res);					  
	}


		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
<!--
function process_form(op){
		if(document.myform.sid.value=="0"){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.file.value==""){
			alert("Please select file to upload");
			document.myform.file.focus();
			return;
		}
		if(document.myform.file.value!=""){
			fn=document.myform.file.value;
			len=fn.length;
			ext=fn.substr(len-3,3);
			if(ext.toLowerCase()!="txt"){
				alert("File rejected. Only TXT file allowed.");
				document.myform.file.focus();
				return;
			}
		}
		ret = confirm("Save the configuration??");
		if (ret == true){
			document.myform.submit();
		}
	
}

function info(){
	//alert("NamaPelajar&lt;tab&gt;KPPelajar&lt;tab&gt;Sex&lt;tab&gt;HP&lt;tab&gt;Tel&lt;tab&gt;Alamat&lt;tab&gt;NamaBapa&lt;tab&gt;KPBapa&lt;tab&gt;Pekerjaan&lt;tab&gt;NamaIbu&lt;tab&gt;KpIbu&lt;tab&gt;Pekerjaan");
	alert("Format File CSV\nFile type:\nTxt\nSeperator:\nTab\nParameter:\nNamaPelajar<tab>KPPelajar<tab>Sex<tab>HP<tab>Tel<tab>Alamat<tab>NamaBapa<tab>KPBapa<tab>Pekerjaan<tab>NamaIbu<tab>KpIbu<tab>Pekerjaan");
}

//-->
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>

<body>
<form name="myform" method="post" enctype="multipart/form-data">
	<input type="hidden" name="p" value="../stu/stu_csv_upload">
	<input type="hidden" name="op">
<div id="panelleft"> 
	<?php include('inc/mymenu.php');?>
</div><!--end pageNav-->

<div id="content2">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="javascript:href='p.php?p=../stu/stu_csv_upload'" id="mymenuitem"><img src="../img/new.png"><br>New</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="<?php echo $MYLIB;?>/inc/student-upload.xlsx" id="mymenuitem"><img src="../img/excel.png"><br>Download Format</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
</div> <!-- end mymenu -->
<div id="cpanel" align="right" style="padding:3px 3px 3px 3px ">

<select name="sid" id="sid">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- Pilih Sekolah -</option>";
			else
                echo "<option value=$sid>$sname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}					  
			
?>
  </select>
  <input type="file" name="file">
  <input type="button" onClick="process_form()" value="Upload">
  <a href="#" onClick="info()" title="Klik">CSV Format</a>
</div>
</div><!-- end of mypanel -->
<div id="story">
<div id="mytitle">STATUS UPLOAD</div>
<table width="100%"  border="0" cellpadding="0" id="mytable">
  <tr id="mytabletitle">
    <td width="5%">BIL</td>
    <td width="25%">NAMA</td>
    <td width="10%">MATRIK</td>
    <td width="15%">NO.KP</td>
    <td width="25%">PENJAGA</td>
    <td width="15%">KPPENJAGA</td>
	<td width="5%" align="center">STATUS</td>
  </tr>

<?php 
	$fn=basename( $_FILES['file']['name']);
	if($fn!=""){
		$tmp=$_FILES['file']['tmp_name'];
		$fp = fopen($tmp, 'r');
		while (!feof($fp)) {
			$uid="";
			$ret=1;
    		$str = stream_get_line($fp, 1000000, "\r\n");
		/*
			list($name,$ic,$sex,$bday,$bplace,$childno,$siblingno,
				$p1name,$p1ic,$p1wn,$p1age,$p1job,$p1sal,$p1com,$p1add,$p1tel,$p1hp,
				$p2name,$p2wn,$p2ic,$p2job,$p2com,$p2age,$p2add,$p2sal,$p2tel,$p2hp,
				$add,$tel)=explode("\t",$str);
		*/
			list($rdate,$name,$uid,$nisn,$ic,$sex,$race,$religion,$bday,$ill,$tel,$tel2,$fax,$hp,$email,$addr,$state,
				$p1name,$p1ic,$p1rel,$p1job,$p1sal,$p1com,$p1addr,$p1state,$p1tel,$p1tel2,$p1fax,$p1hp,$p1email,
				$p2name,$p2ic,$p2rel,$p2job,$p2sal,$p2com,$p2addr,$p2state,$p2tel,$p2tel2,$p2fax,$p2hp,$p2email,
				$citizenship,$statex,$postcode,$p1race,$p2race,$p1citizenship,$p2citizenship,$p1salx,$p2salx,$p1age,$p2age,
				$birthplace,$nick,$p1edu,$p2edu,$p3name,$p3ic,$p3hp,$p3tel,$p3tel2,$p3email,$p3add,$p3rel,$p3fax,$p3addr,
				$sponsor,$bstate,$intake,$pschool,$sttb,$stopdate,$yearinschool,$ussbn,$uan,$reasonleaving,
				$pschool2,$sttb2,$stopdate2,$yearinschool2,$bahasa,$anak,$kandung,$tiri,$angkat,$bersama,$yatim,$jarak,$bukuinduk)=explode("\t",$str);
				
			if(strlen($name)<1) 
				continue;
			
			//$sql="select count(*) from stu where ic='$ic' and sch_id=$sid";
			//$res=mysql_query($sql)or die("query failed:".mysql_error());
			//$row=mysql_fetch_row($res);
			//if($row[0]>0){
			//	$ret=0;
			//}
			//else{
				$name=addslashes($name);
				$p1name=addslashes($p1name);
				$p2name=addslashes($p2name);
				$p3name=addslashes($p3name);
				$addr=addslashes($addr);
				$p1addr=addslashes($p1addr);
				$p2addr=addslashes($p2addr);
				$p3add=addslashes($p3add);
				$p3addr=addslashes($p3addr);
				$p1job=addslashes($p1job);
				$p2job=addslashes($p2job);
				/**
				if(strcasecmp($sex,"Perempuan")==0)
					$sex="PEREMPUAN";
				if(strcasecmp($sex,"Lelaki")==0)
					$sex="LELAKI";
				**/
				$sql="insert into stureg(cdate,sch_id,rdate,name,uid,pass,ic,sex,race,religion,bday,ill,tel,tel2,fax,hp,mel,addr,state,p1name,p1ic,p1rel,p1job,p1sal,p1com,p1addr,p1state,p1tel,p1tel2,p1fax,p1hp,p1mel,p2name,p2ic,p2rel,p2job,p2sal,p2com,p2addr,p2state,p2tel,p2tel2,p2fax,p2hp,p2mel,citizen,bandar,poskod,p1race,p2race,p1wn,p2wn,p1salx,p2salx,p1age,p2age,birthplace,nick,p1edu,p2edu,p3name,p3ic,p3hp,p3tel,p3tel2,p3mel,p3add,p3rel,p3fax,p3addr,sponser,bstate,intake,pschool,sttb,stopdate,yearinschool,ussbn,uan,reasonleaving,pschool2,sttb2,stopdate2,yearinschool2,bahasa,anakke,jumkandung,jumtiri,jumangkat,tinggalbersama,yatim,jaraksekolah,nobukuinduk,nisn,status)values
				(now(),'$sid','$rdate','$name','$uid','123456','$ic','$sex','$race','$religion','$bday','$ill','$tel','$tel2','$fax'
				,'$hp','$email','$addr','$state','$p1name','$p1ic','$p1rel','$p1job','$p1sal','$p1com','$p1addr','$p1state','$p1tel','$p1tel2','$p1fax','$p1hp','$p1mel','$p2name','$p2ic','$p2rel','$p2job','$p2sal','$p2com','$p2addr','$p2state','$p2tel','$p2tel2','$p2fax','$p2hp','$p2mel','$citizenship','$bandar','$poskod','$p1race','$p2race','$p1citizenship','$p2citizenship','$p1salx','$p2salx','$p1age','$p2age','$birthplace','$nick','$p1edu','$p2edu','$p3name','$p3ic','$p3hp','$p3tel','$p3tel2','$p3mel','$p3add','$p3rel','$p3fax','$p3addr','$sponser','$bstate','$intake','$pschool','$sttb','$stopdate','$yearinschool','$ussbn','$uan','$reasonleaving','$pschool2','$sttb2','$stopdate2','$yearinschool2','$bahasa','$anak','$kandung','$tiri','$angkat','$bersama','$yatim','$jarak',
				'$nobukuinduk','$nisn',0)";
				mysql_query($sql)or die("$sql 6query failed:".mysql_error());
				/*$id=mysql_insert_id();
				if($si_student_global_id)
					$sql="update sch set stuid=stuid+1";
				else
					$sql="update sch set stuid=stuid+1 where id=$sid";
				mysql_query($sql)or die("$sql 7query failed:".mysql_error());
				$sql="select stuid from sch where id=$sid";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$c=$row['stuid'];
				$uid=sprintf("%s%04s",$pref,$c);
				$sql="update stu set uid='$uid' where id=$id";
				mysql_query($sql)or die("query failed:".mysql_error());*/
				$ret=1;
			
			//}
			if($q++%2==0)
				$bg="bgcolor=\"#FAFAFA\"";
			else
				$bg="";
?>

  <tr <?php echo "$bg";?>>
    <td><?php echo "$q";?></td>
    <td><?php echo "$name";?></td>
    <td><?php echo "$uid";?></td>
    <td><?php echo "$ic";?></td>
    <td><?php echo "$p1name";?></td>
    <td><?php echo "$p1ic";?></td>
	<td align="center"><?php echo "$ret";?></td>
  </tr>
<?php 
		} //end while
	}//end if
?>
</table>

</div></div>
</form>
</body>
</html>
