<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
include("$MYOBJ/fckeditor/fckeditor.php") ;
verify('ADMIN|AKADEMIK|HEP|HEP-OPERATOR|HR');
$username = $_SESSION['username'];


	  	$uid=$_REQUEST['uid'];
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		$op=$_REQUEST['op'];
		$xx=$_REQUEST['FCKeditor1'];
		
		$st=$_REQUEST['st'];
		if($op=="save"){
				$xxx=addslashes($xx);
				$sql="delete from letter where type='$st'";
				$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$sql="insert into letter (type,content) values ('$st','$xxx')";
				$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
		}
		$sql="select * from letter where type='$st'";
		$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$content=stripslashes($row['content']);
		$raw=stripslashes($row['content']);
		

		if($uid!=""){
			$sql="select * from stu where uid='$uid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sch_id'];
			$matrik=$row['uid'];
			$nama=$row['name'];
			$kp=$row['ic'];
			$date=date("d/m/Y");
			mysql_free_result($res);
		}
		if($sid!=""){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$stype=$row['level'];
			$simg=$row['img'];
            mysql_free_result($res);					  
		}
		$nobilik="";
		$sql="select * from hos_room where uid='$uid'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$b=$row2['block'];
			$l=$row2['level'];
			$r=$row2['roomno'];
			$s=$row2['stuno'];
			$nobilik="$b-$l-$r-$s";
		}
		
		$year=date('Y');
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
				$clsname=$row2['cls_name'];
				$clslevel=$row2['cls_level'];
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
<script language="JavaScript">
<!--
function process_save(op){
		ret = confirm("Are you sure want to save??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;
}
//-->
</script>
</head>
<body>
<form name="myform" action="" method="post">
	<input type="hidden" name="id" value="<?php echo $id;?>" >
	<input type="hidden" name="st" value="<?php echo $st;?>" >
	<input type="hidden" name="op" value="">

<div id="content">
	<div id="mypanel">
		<div id="mymenu" align="center">
			<a href="#" onClick="showhide('letter','editor')" id="mymenuitem"><img src="../img/tool.png"><br>Edit</a>
			<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
			<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
			<a href="#" onClick="window.close();parent.parent.GB_hide();top.document.myform.submit();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
	</div>
<div id="story" style="font-size:120%; border:none ">
<div id="letter" style="display:block; border:none; padding:10px 10px 10px 10px">
	
		<?php include("../inc/school_header.php")?>

<?php 
	
	$content=str_replace("\"","",$content);
	eval("\$content = \"$content\";"); //evaluate the input string as PHP;
	echo "$content"; 
?>
</div>
<div id="editor" style="display:none ">
<div id="mytitle">
	<div id="myclick" onClick="showhide('letter','editor');"><img src="../img/icon_minimize.gif" id="mycontrolicon">CLOSE EDITOR</div>
</div>
<br>
<br>
<font color="#0000FF">
TIPS KEMASKINI:<br>
- *Untuk 'enter' atau 'new line' sila tekan "Shift Enter"<br>
- *Perkataan yang bermula dengan tanda '$' merupakan "system variable". Dilarang ubah. Sila hubungi Awfatech.<br>
- *Perubahan config akan 'effect' ke semua sekolah<br>
</font><br>
<br>

<input type="button" value="Kemaskini Surat" onClick="process_save('save')">
<table width="100%"  border="0" cellpadding="0">
  <tr>
    <td>
		<?php
		//$sBasePath = $_SERVER['PHP_SELF'] ;
		//$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
		
		$sBasePath="$MYOBJ/fckeditor/";//hardcode path set by apai
		$fck = new FCKeditor('FCKeditor1') ;
		$fck->BasePath = $sBasePath ;
		$fck->Height='600';
		//if ( isset($_GET['Toolbar']) )
			//$oFCKeditor->ToolbarSet = preg_replace("/[^a-z]/i", "", $_GET['Toolbar']);
		$fck->ToolbarSet = preg_replace("/[^a-z]/i", "", "Basic");//null = Default
		//$fck->ToolbarSet = preg_replace("/[^a-z]/i", "", "");//null = Default
		
		$fck->Value = $raw;
		$fck->Create() ;
		?>
    </td>
  </tr>
</table>

</div><!-- end editor -->
</div></div>
</form>
</body>
</html>
