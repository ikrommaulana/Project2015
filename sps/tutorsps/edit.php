<?php 
//100909 - new fckeditor
//111110 - update gui
$vmod="v5.0.1";
$vdate="111110";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php");
verify('ADMIN|AKADEMIK|KEWANGAN');
$username = $_SESSION['username'];
$F=$_REQUEST['f'];
$op=$_REQUEST['op'];
$id=$_REQUEST['id'];

$sid=$_REQUEST['sid'];
if($sid=="")
	$sid=$_SESSION['sid'];


			
if($op=="save"){
	$tit=$_POST['tit'];
	$msg=addslashes($_POST['FCKeditor1']);
	$sid=$_POST['sid'];
	if($sid=="")
		$sid=0;
	$u1=$_POST['u1'];
	$u2=$_POST['u2'];
	$u3=$_POST['u3'];
	$cat=$_POST['cat'];
	$idx=$_POST['idx'];
	$gid=$_POST['gid'];
	$newtopic=$_POST['newtopic'];
	$linkk=$_POST['link'];
	$status=$_POST['status'];
	$status=1;
	$userview="staff|$u2|$u3";
	$userview=trim($userview," ");
	$author=addslashes($_SESSION['name']);
	if($id!=""){
		$sql="update tutorial_sys set tit='$tit',idx='$idx',gid='$gid',link='$linkk',msg='$msg',access='$userview',sta=$status,cat='$cat',sid=$sid,adm='$username',ts=now() where id=$id";
		mysql_query($sql)or die("$sql query failed:".mysql_error());
		$f="<font color=blue>&lt;Successfully updated&gt;</font>";
	}
	else{
		$sql="insert into tutorial_sys(dt,tm,ts,tit,link,msg,uid,author,access,sta,sid,cat,app,idx,gid) values 
		(now(),now(),now(),'$tit','$linkk','$msg','$username','$author','$userview',$status,$sid,'$cat','TUTORSPS','$idx','$gid')";
		mysql_query($sql)or die("$sql query failed:".mysql_error());
		$id=mysql_insert_id();
		$f="<font color=blue>&lt;Successfully saved&gt;</font>";
	}
	$fn=basename($_FILES['file1']['name']);
	if($fn!=""){
		$ext=strtok($fn,".");$ext=strtok(".");
		$fnx="f1_".$id.".".$ext;
		$target_path ="../tutorial_sys/news_attachment/$fnx";
		if(move_uploaded_file($_FILES['file1']['tmp_name'], $target_path)) {
			$sql="update tutorial_sys set file1='$fnx|$fn' where id='$id'";
			mysql_query($sql)or die("query failed:".mysql_error());
			echo "$sql;$fn";
		}
		echo $target_path;
	}
	$fn=basename($_FILES['file2']['name']);
	if($fn!=""){
		$ext=strtok($fn,".");$ext=strtok(".");
		$fnx="f2_".$id.".".$ext;
		$target_path ="../content/news_attachment/$fnx";
		if(move_uploaded_file($_FILES['file2']['tmp_name'], $target_path)) {
			$sql="update tutorial_sys set file2='$fnx|$fn' where id='$id'";
			mysql_query($sql)or die("query failed:".mysql_error());
		}
	}
	$fn=basename($_FILES['file3']['name']);
	if($fn!=""){
		$ext=strtok($fn,".");$ext=strtok(".");
		$fnx="f3_".$id.".".$ext;
		$target_path ="../content/news_attachment/$fnx";
		if(move_uploaded_file($_FILES['file3']['tmp_name'], $target_path)) {
			$sql="update tutorial_sys set file3='$fnx|$fn' where id='$id'";
			mysql_query($sql)or die("query failed:".mysql_error());
		}
	}
}
elseif($op=="delete"){
			$sql="delete from tutorial_sys where id='$id'";
			mysql_query($sql)or die("$sql query failed:".mysql_error());
			$id="";
			$f="<font color=blue>&lt;Successfully Deleted&gt;</font>";
}
if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$namatahap=$row['clevel'];
            mysql_free_result($res);					  
}

	if($id!=""){
		$sql="select * from tutorial_sys where id='$id'";
		$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res)){
			$tit=stripslashes($row['tit']);
			$msg=stripslashes($row['msg']);
			$cat=$row['cat'];
			$status=$row['sta'];
			$access=$row['access'];
			$sid=$row['sid'];
			$linkk=$row['link'];
			$f1=$row['file1'];list($af1,$xf1)=split('[|]',$f1);
			$f2=$row['file2'];list($af2,$xf2)=split('[|]',$f2);
			$f3=$row['file3'];list($af3,$xf3)=split('[|]',$f3);
			list($u1,$u2,$u3,$u4)=split('[/.|]',$access);
		}
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
	var ret="";
	var cflag=false;

	if(op=='new'){
		document.myform.id.value="";
		document.myform.tit.value="";
		return;
	}
	else if(op=='save'){
		if(document.myform.cat.value==""){
			alert("Enter the news topic");
			document.myform.cat.focus();
			return;
		}
		if(document.myform.tit.value==""){
			alert("Enter the news title");
			document.myform.tit.focus();
			return;
		}
		ret = confirm("Save the news??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}
	else if(op=='delete'){
		if(document.myform.id.value==""){
			alert('Please checked the item to delete');
			return;
		}
		ret = confirm("Are you sure want to delete??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;
	}
}
//-->
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="myform">
 	<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
 	<input type="hidden" name="p" value="news_edit">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="op">
	
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a style="cursor:pointer" onClick="process_form('new');" id="mymenuitem"><img src="../img/new.png"><br>New</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a style="cursor:pointer" onClick="process_form('save');"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a style="cursor:pointer" onClick="process_form('delete')"id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a style="cursor:pointer" onClick="document.myform.submit()"id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a style="cursor:pointer" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="../img/help22.png"><br>HowTo</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a style="cursor:pointer" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();parent.$.fancybox.close();" id="mymenuitem">
        <img src="../img/close.png"><br>Close</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
            
	</div> <!-- end mymenu -->
	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>
</div> <!-- end cpanel -->
<div id="story">
<div id="tipsdiv" style="display:none ">
Tips:<br>
- Click "new" to add new news<br>
- Click "Shift Enter" to go to new line. "Enter" will go down 2 lines<br>
- DO no copy/paste directly from msword or from web. If need copy past to Notepad first then copy it copy and paste to here.<br>
</div>
<div id="mytitlebg">SETTING <?php echo $f;?></div>
<table width="100%">
	<tr id="mytitle">
		<td  width="10%" ></td>
		<td>: 
        			<select name="topic" onChange="document.myform.cat.value=document.myform.topic.value">
                                                <?php 
													if($cat!="")
														 echo "<option value=\"$cat\">$cat</option>";
													else
														 echo "<option value=\"\">- $lg_select  -</option>";
                                                    $sql="select distinct(cat) from tutorial_sys order by idx;";
                                                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                                                    while($row=mysql_fetch_assoc($res)){
                                                                $s=$row['cat'];
                                                                $v=stripslashes($row['name']);
                                                                echo "<option value=\"$s\">$s</option>";
                                                    }
                                                ?>
                     </select>
	</tr>
    <tr id="mytitle">
		<td  width="10%">Topic</td>
		<td>:  <input name="cat" type="text" size="92" value="<?php echo $cat;?>"></td>
	</tr>
	<tr id="mytitle">
		<td width="5%">Title</td>
		<td>: <input name="tit" type="text" value="<?php echo $tit;?>" size="92"></td>
	</tr>
	<tr id="mytitle">
		<td width="5%">Link</td>
		<td>: <input name="link" type="text" value="<?php echo $linkk;?>" size="92"></td>
	</tr>
    <tr id="mytitle">
		<td width="5%">Group ID</td>
		<td>: <input name="gid" type="text" value="<?php echo $gid;?>"></td>
	</tr>
    <tr id="mytitle">
		<td width="5%">Index</td>
		<td>: <input name="idx" type="text" value="<?php echo $idx;?>"></td>
	</tr>
</table>



<div style="height:300px; width:100%">
<?php
		$sBasePath="$MYOBJ/fckeditor/";//hardcode path set by apai
		$fck = new FCKeditor('FCKeditor1') ;
		$fck->BasePath = $sBasePath ;
		$fck->Height='300';
		$fck->ToolbarSet = preg_replace("/[^a-z]/i", "", "Custom");//null = Default
		$fck->Value = $msg;
		$fck->Create() ;
?>
</div>
<div id="mytitlebg">
		Add Attachment
</div>       
		1. <input type="file" name="file1" size="48">&nbsp;File 1 : <?php echo $xf1;?><br>
		2. <input type="file" name="file2" size="48">&nbsp;File 2 : <?php echo $xf2;?><br>
		3. <input type="file" name="file3" size="48">&nbsp;File 3 : <?php echo $xf3;?><br>
	
</div></div>
</body>
</html>
