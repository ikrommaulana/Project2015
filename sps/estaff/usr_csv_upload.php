<?php
include_once('../etc/config.php');
include_once('../etc/db.php');
include_once('../etc/session.php');

$username = $_SESSION['username'];
$op=$_REQUEST['op'];
$p=$_REQUEST['p'];

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
	alert("Format File CSV\nFile type:\nTxt\nSeperator:\nTab\nParameter:\nNama<tab>KP<tab>HP<tab>Tel<tab>Email<tab>Alamat<tab>Jawatan<tab>Status<tab>Bahagian<tab>T.Mula");
}
//-->
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
</head>

<body>
<form name="myform" method="post" enctype="multipart/form-data">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="op">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="javascript:href='p.php?p=../estaff/usr_csv_upload'" id="mymenuitem"><img src="../img/new.png"><br>New</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="<?php echo $MYLIB;?>/inc/staff-upload.xlsx" id="mymenuitem"><img src="../img/excel.png"><br>Download Format</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
</div> <!-- end mymenu -->
<div id="cpanel" align="right" style="padding:3px 3px 3px 3px ">

<select name="sid" id="sid">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- Pilih $lg_sekolah -</option>";
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
  <input type="button" onClick="process_form()" value="Upload"><br>
  <a href="#" onClick="info()" title="Klik">CSV Format</a>
  
</div>
</div><!-- end of mypanel -->
<div id="story">
<div id="mytitle">STATUS UPLOAD</div>
<table width="100%"  border="0" cellpadding="0" id="mytable">
  <tr id="mytabletitle">
    <td width="5%">Nomor</td>
    <td width="25%">Nama</td>
    <td width="10%">IDPekerja</td>
    <td width="15%">Nomor KTP</td>
    <td width="15%">Telepon HP</td>
    <td width="15%">Telepon Rumah</td>
	<td width="15%">Email</td>
	<td width="5%" align="center">Sta</td>
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
			//list($uid,$name,$ic,$hp,$jobstart,$x1,$addr)=explode("\t",$str);
			
			list($name,$nick,$nuptk,$ic,$sex,$race,$religion,$bday,$job,$jobstart,$jobend,$tel,$tel2,$fax,$hp,$mel,$addr,$stajabatan,$karpeg,$place1,$place2,$height,$weight,$face,$hair,$bloodtype,$sick1,$sick2,$differ,$sibtotal,$sibboy,$sibgirl,$sibno,$staffstatus,$langua,$piagam1,$piagam2,$indukno)=explode("\t",$str);
			if(strlen($name)<1)
				continue;
			/** auto generate uid **/
			
			$sql="select * from type where grp='staffid' and sid='$sid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$count=$row['val']+1;
			$pref=$row['code'];
			$uid=sprintf("%s%03d",$pref,$count);
			
			$name=trim($name,'\"');
			$xname=addslashes($name);
			$xnick=addslashes($nick);
			$xjob=addslashes($job);
			$xaddr=addslashes($addr);
			$xplace1=addslashes($place1);
			$xplace2=addslashes($place2);
			$xsick1=addslashes($sick1);
			$xsick2=addslashes($sick2);
			$xdiffer=addslashes($differ);
			$xpiagam1=addslashes($piagam1);
			$xpiagam2=addslashes($piagam2);
			

			$sql="insert into usr(cdate,sch_id,uid,name,nick,ic,sex,race,religion,bday,job,jobstart,jobend,tel,tel2,fax,hp,mel,addr,stajabatan,karpeg,place1,place2,height,weight,face,hair,bloodtype,sick1,sick2,differ,sibtotal,sibboy,sibgirl,sibno,staffstatus,langua,piagam1,piagam2,indukno,nuptk,status) 
			values (now(),$sid,'$uid','$xname','$xnick','$ic','$sex','$race','$religion','$bday','$xjob','$jobstart','$jobend','$tel','$tel2','$fax','$hp','$mel','$xaddr','$stajabatan','$karpeg','$xplace1','$xplace2','$height','$weight','$face','$hair','$bloodtype','$xsick1','$xsick2','$xdiffer','$sibtotal','$sibboy','$sibgirl','$sibno','$staffstatus','$langua','$xpiagam1','$xpiagam2','$indukno','$nuptk','0')";
			mysql_query($sql)or die("$sql 6query failed:".mysql_error());
			$sql="update type set val=val+1 where grp='staffid' and sid='$sid'";
			mysql_query($sql)or die("query failed:".mysql_error());
			$ret=1;
			
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
    <td><?php echo "$hp";?></td>
    <td><?php echo "$tel";?></td>
	<td><?php echo "$mel";?></td>
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
