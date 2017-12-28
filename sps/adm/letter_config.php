<?php
//13/04/2010 - new loaded
$vmod="v5.3.0";
$vdate="110502";
include_once('../etc/db.php');
//include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php") ;
//verify('ADMIN');
//$username = $_SESSION['username'];


	  	$id=$_REQUEST['id'];
		$op=$_REQUEST['op'];
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
			

			
		$type=$_REQUEST['type'];
		$name=$_REQUEST['name'];
		$isheader=$_REQUEST['isheader'];
		if($isheader=="")
			$isheader=0;

		$isadvance=$_REQUEST['isadvance'];
		
		$content=$_REQUEST['FCKeditor1'];
		if($op=="save"){
				$content=addslashes($content);
				if($name!=""){
						$sql="insert into letter (type,content,isheader,sid,name) values ('$type','$content',$isheader,$sid,'$name')";
						$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
						$id=mysql_insert_id();
				}elseif($id!=""){
						$sql="update letter set content='$content', isheader='$isheader' where id='$id'";
						$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
				}else{
						$sql="insert into letter (type,content,isheader,sid,name) values ('$type','$content',$isheader,$sid,'$name')";
						$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
				}
				$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
		}elseif($op=='delete'){
				$sql="delete from letter where id='$id'";
				$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$f="<font color=red>&lt;SUCCESSFULY DELETE&gt;</font>";
				$id="";
		}
		if($id!=""){
				$sql="select * from letter where id='$id'";
				$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$content=stripslashes($row['content']);
				$raw=stripslashes($row['content']);
				$isheader=$row['isheader'];
				$type=$row['type'];
				$name=$row['name'];
				$sid=$row['sid'];
		}

				$sql="select * from letter where type='$type' and name='$name' and sid='$sid'";
				$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$content=stripslashes($row['content']);
				$raw=stripslashes($row['content']);
				$isheader=$row['isheader'];


		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sname=$row['sname'];					  
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
	if(op=='save'){
		if((document.myform.id.value=="")&&(document.myform.name.value=="")){
			alert("Please enter new template name");
			document.myform.name.focus();
			return;
		}
		ret = confirm("Are you sure want to save??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}else if(op=='delete'){
		ret = confirm("Are you sure want to delete??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}
	return;
	
}
//-->
</script>
</head>
<body>
<form name="myform" action="" method="post">
	<input type="hidden" name="id" value="<?php echo $id;?>" >
	<input type="hidden" name="type" value="<?php echo $type;?>" >
	<input type="hidden" name="op">

<div id="content">
	<div id="mypanel">
		<div id="mymenu" align="center">
			<a href="#" onClick="process_save('save')" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
				<div id="mymenu_seperator"></div>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
			<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
				<div id="mymenu_seperator"></div>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
			<a href="#" onClick="process_save('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
				<div id="mymenu_seperator"></div>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
			<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
				<div id="mymenu_seperator"></div>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
			<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
		<div align="right">
		<br>
		&nbsp;&nbsp;
	 	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
	</div>
<div id="story" style="font-size:120%; border:none ">
<div id="mytitlebg"><?php echo $f;?></div>
<div id="editor">

<font color="#0000FF">
<!-- TIPS KEMASKINI (PENTING):<br>
- Untuk 'enter' atau 'new line' sila tekan "Shift Enter"<br>
- {} merupakan "system variable". boleh dikeluarkan jika tidak diperlukan.<br>
- Untuk save atas nama yang lain, masukkan nama baru diruang yang di sediakan. Save terus untuk replace record lama.<br>
- DILARANG copy/paste dari word/web dan lain-lain. Jika nak copy/paste, pastekan dulu di notepad dan kemudian copy/paste dari notepad.<br>
--> </font>
<br>
<div id="mytabletitle">
Letter name:
<select name="id" onChange="document.myform.submit();">
<?php
		if($id=="")
			echo "<option value=\"\">- Compose new template - </option>";
		else
			echo "<option value=\"$id\">$name</option>";
			$sql="select * from letter where type='$type' and id!='$id'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $id=$row['id'];
						$name=$row['name'];
                        echo "<option value=\"$id\">$name</option>";
            }	  
			echo "<option value=\"\">- Compose new template - </option>";
?>
</select>
Save as new name:
<input type="text" name="name">

<select name="sid">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_all $lg_school -</option>";
			else
                echo "<option value=$sid>$sname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
			}
			if($sid!="0")
            	echo "<option value=\"0\">- $lg_all $lg_school -</option>";				  
?>
  </select>
  <br>
&nbsp;&nbsp;<label><input type="checkbox" name="isheader" value="1" <?php if($isheader) echo "checked";?>> SHOW HEADER</label>
&nbsp;&nbsp;<label><input type="checkbox" name="isadvance" value="1" <?php if($isadvance) echo "checked";?> onChange="document.myform.submit();"> Advance Mode</label>
</div>
<div style="width:100% ">
		<?php
		//$sBasePath = $_SERVER['PHP_SELF'] ;
		//$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
		
		$sBasePath="$MYOBJ/fckeditor/";//hardcode path set by apai
		$fck = new FCKeditor('FCKeditor1') ;
		$fck->BasePath = $sBasePath ;
		$fck->Height='600';
		//if ( isset($_GET['Toolbar']) )
			//$oFCKeditor->ToolbarSet = preg_replace("/[^a-z]/i", "", $_GET['Toolbar']);
		if($isadvance)
			$fck->ToolbarSet = preg_replace("/[^a-z]/i", "", "");//null = Default, Basic, Custom
		else
			$fck->ToolbarSet = preg_replace("/[^a-z]/i", "", "Custom");//null = Default, Basic, Custom
		
		$fck->Value = $raw;
		$fck->Create() ;
		?>
</div>

</div><!-- end editor -->
</div></div>
</form>
</body>
</html>
