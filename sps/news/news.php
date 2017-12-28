<?php 
//13/04/2010 - new fckeditor
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php") ;
verify("");

$adm = $_SESSION['username'];
$F=$_REQUEST['f'];
$op=$_REQUEST['op'];
$id=$_REQUEST['id'];

$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
			
if($op==""){
	if($id!=""){
		$sql="select * from content where id='$id'";
		$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res)){
			$tit=stripslashes($row['tit']);
			$msg=stripslashes($row['msg']);
			$cat=$row['cat'];
			$status=$row['sta'];
			$access=$row['access'];
			$sid=$row['sid'];
			$sesyear=$row['year'];
			$clscode=$row['cls_code'];
			$f1=$row['file1'];list($af1,$xf1)=split('[|]',$f1);
			$f2=$row['file2'];list($af2,$xf2)=split('[|]',$f2);
			$f3=$row['file3'];list($af3,$xf3)=split('[|]',$f3);
			list($u1,$u2,$u3,$u4)=split('[/.|]',$access);
		}
		mysql_free_result($res);
	}
}
elseif($op=="save"){
	$tit=$_POST['tit'];
	$msg=addslashes($_POST['FCKeditor1']);
	$sid=$_POST['sid'];
	$clscode=$_POST['clscode'];
	$u1=$_POST['u1'];
	$u2=$_POST['u2'];
	$u3=$_POST['u3'];
	$cat=$_POST['cat'];
	$sesyear=$_POST['sesyear'];
	$status=$_POST['status'];
	$status=1;
	$userview="$u1|$u2|$u3";
	$userview=trim($userview," ");
	$author=addslashes($_SESSION['name']);
	
	if($id!=""){
		$sql="update content set tit='$tit',msg='$msg',access='$userview',sta=$status,sid=$sid,adm='$adm',cls_code='$clscode',year='$sesyear',ts=now() where id=$id";
		mysql_query($sql)or die("$sql query failed:".mysql_error());
	}
	else{
		$sql="insert into content(dt,tm,ts,tit,msg,uid,author,access,sta,sid,cat,app,cls_code,year) values (now(),now(),now(),'$tit','$msg','$adm','$author','$userview',$status,$sid,'EWARIS','NEWS','$clscode','$sesyear')";
		mysql_query($sql)or die("$sql query failed:".mysql_error());
		$id=mysql_insert_id();
	}

	$fn=basename($_FILES['file1']['name']);
	if($fn!=""){
		$ext=strtok($fn,".");$ext=strtok(".");
		$fnx="f1_".$id.".".$ext;
		$target_path ="../content/news_attachment/$fnx";
		if(move_uploaded_file($_FILES['file1']['tmp_name'], $target_path)) {
			$sql="update content set file1='$fnx|$fn' where id='$id'";
			mysql_query($sql)or die("query failed:".mysql_error());
		}
	}
	$fn=basename($_FILES['file2']['name']);
	if($fn!=""){
		$ext=strtok($fn,".");$ext=strtok(".");
		$fnx="f2_".$id.".".$ext;
		$target_path ="../content/news_attachment/$fnx";
		if(move_uploaded_file($_FILES['file2']['tmp_name'], $target_path)) {
			$sql="update content set file2='$fnx|$fn' where id='$id'";
			mysql_query($sql)or die("query failed:".mysql_error());
		}
	}
	$fn=basename($_FILES['file3']['name']);
	if($fn!=""){
		$ext=strtok($fn,".");$ext=strtok(".");
		$fnx="f3_".$id.".".$ext;
		$target_path ="../content/news_attachment/$fnx";
		if(move_uploaded_file($_FILES['file3']['tmp_name'], $target_path)) {
			$sql="update content set file3='$fnx|$fn' where id='$id'";
			mysql_query($sql)or die("query failed:".mysql_error());
		}
	}
	$id=0;
}
elseif($op=="delete"){
			$sql="delete from content where id='$id'";
			mysql_query($sql)or die("$sql query failed:".mysql_error());
			$id=0;
}
if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$namatahap=$row['clevel'];
            mysql_free_result($res);					  
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
<!--
function processform(operation){
		if(document.myform.sid.value==""){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		document.myform.submit();
}
function process_sort(fsort,forder){
		if(document.myform.sid.value=="0"){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.clscode.value==""){
			alert("Please select class");
			document.myform.clscode.focus();
			return;
		}
		document.myform.sort.value=fsort;
		document.myform.order.value=forder;
		document.myform.submit();
}

function process_form(op){
	var ret="";
	var cflag=false;

	if(op=='new'){
		document.myform.id.value="";
		document.myform.tit.value="";
		document.myform.u1.checked=false;
		document.myform.u2.checked=false;
		document.myform.u3.checked=false;
		return;
	}
	else if(op=='save'){
		if(document.myform.tit.value==""){
			alert("Please enter message");
			document.myform.tit.focus();
			return;
		}
		ret = confirm("Save the configuration??");
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
 	<input type="hidden" name="MAX_FILE_SIZE" value="6000000">
 	<input type="hidden" name="p" value="../news/news">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="op">
	
<div id="panelleft"> 
	<?php include('inc/mymenu.php');?>
</div>
<div id="content2">
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
		<a href="#" onClick="document.myform.id.value='';document.myform.submit()"id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="../img/help22.png"><br>HowTo</a>
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
TIPS KEMASKINI (PENTING):<br>
- Klik "new" untuk memasukkan berita baru<br>
- Klik pada berita untuk delete atau edit berita<br>
Panduan menulis berita<br>
- Untuk 'enter' atau 'new line' sila tekan "Shift Enter"<br>
- DILARANG copy/paste dari word/web dan lain-lain. Jika ingin melakukan copy/paste, paste terlebih dahulu pada notepad lalu copy/paste dari notepad.<br>
</div>
<div id="panelform"  <?php if($id=="") echo "style=display:none;"?> >
	<div id="mytitle">
	<div id="myclick" onClick="hide('panelform');"><img src="../img/icon_minimize.gif" id="mycontrolicon">SETTING</div>&nbsp;
</div>
<table width="100%">
	<tr>
		<td width="5%">TITLE</td>
		<td>: <input name="tit" type="text" id="key"  value="<?php echo $tit;?>" size="92"  ></td>
	</tr>
	<tr>
		<td>CATEGORY</td>
		<td>: EWARIS</td>
	</tr>
	<tr>
		<td>VIEW BY</td>
		<td>: 
		<select name="sid" id="sid" onchange="document.myform.clscode[0].value='';document.myform.submit();">
            <?php	
				
				$sql="select * from sch order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				
				echo "<option value=\"0\">- $lg_all $lg_sekolah -</option>";
				
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							if($t==$sid){$selected="selected";}else{$selected="";}
							echo "<option value=$t $selected>$s</option>";
				}
									  
			
?>
              </select>
		<select name="clscode" id="clscode">
			<?php
				$select=mysql_query("SELECT distinct(level) FROM cls WHERE sch_id='$sid' order by id") or die (mysql_error());
				echo "<option value=\"0\">Semua Kelas</option>";
				while($read=mysql_fetch_array($select)){
					$class=$read['name'];
					$id_cls=$read['level'];
					if($id_cls==$clscode){$selected="selected";}else{$selected="";}
						echo "<option value=$id_cls $selected>Kelas $id_cls </option>";
						
				}
				
			?>
		</select>
		<select name="sesyear" id="sesyear">
			<?php
				$open=mysql_query("select * from type where grp='session' order by val desc") or die (mysql_error());
				echo "<option value=\"\">$lg_year_session</option>";
				while($read=mysql_fetch_array($open)){
					$s=$read['prm'];
					if($s==$sesyear){$selected="selected";}else{$selected="";}
						echo "<option value=$s $selected>$lg_year_session $s </option>";
						
				}
				
			?>
		</select>	
                  	
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td> <input name="u3" type="checkbox" id="u3" value="PARENT" <?php echo "checked";?>>
            		PARENT </td>
	</tr>
</table>



<div style="height:300px; width:800px ">
	<?php
	//$sBasePath = $_SERVER['PHP_SELF'] ;
		//$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
		
		$sBasePath="$MYOBJ/fckeditor/";//hardcode path set by apai
		$fck = new FCKeditor('FCKeditor1') ;
		$fck->BasePath = $sBasePath ;
		$fck->Height='300';
		//if ( isset($_GET['Toolbar']) )
			//$oFCKeditor->ToolbarSet = preg_replace("/[^a-z]/i", "", $_GET['Toolbar']);
		$fck->ToolbarSet = preg_replace("/[^a-z]/i", "", "Custom");//null = Default
		
		$fck->Value = $msg;
		$fck->Create() ;
?>
</div>
		ATTACHMENT:<br>
		1. <input type="file" name="file1" size="48">File1 : <?php echo $xf1;?><br>
		2. <input type="file" name="file2" size="48">File2 : <?php echo $xf2;?><br>
		3. <input type="file" name="file3" size="48">File3 : <?php echo $xf3;?><br>
		<h2>max. file upload 5.0 MB</h2><br><br>

</div><!-- end panelform -->

<div id="mytitle">Ewaris Announcement <?php if($F)echo "&lt;successfully update&gt;";?></div>
          <table width="100%" cellspacing="0">
            <tr>
					<td id="mytabletitle" width="5%" align="center">No</td>
					<td id="mytabletitle" width=40%>Title</td>
					<td id="mytabletitle" width=10%>Category</td>
					<td id="mytabletitle" width=10%>View By</td>
					<td id="mytabletitle" width=20%>Upload By</td>
					<td id="mytabletitle" width=20%>Class</td>
					<td id="mytabletitle" width=5% align="center">Status</td>
            </tr>
	<?php
	if($_SESSION['sid']==0)
  		$sql="select * from content where app='NEWS' and cat='ewaris' order by id desc";
	else
		$sql="select * from content where app='NEWS' and cat='ewaris' and sid=".$_SESSION['sid']." order by id desc";

		//echo $sql;
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$id=$row['id'];
	        $nam=$row['tit'];
			$access=$row['access'];
			$uid=$row['uid'];
			$cat=$row['cat'];
			$sta=$row['sta'];
			$xsid=$row['sid'];
			$cls=$row['cls_code'];
		if(($q++%2)==0)
			$bg="";
		else
			$bg="#FAFAFA";
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
				<td id=myborder align=center><?php echo "$q";?></td>
<?php
		if($_SESSION['sid']==0)
   			echo "<td id=myborder><a href=\"p.php?p=../news/news&id=$id\" title=\"edit\" >$nam</a></td>";
		elseif($uid==$_SESSION['username'])
			echo "<td id=myborder><a href=\"p.php?p=../news/news&id=$id\" title=\"edit\" >$nam</a></td>";
		else
			echo "<td>$nam</td>";
		echo "<td id=myborder>$cat</td>";
		echo "<td id=myborder>$access</td>";
		echo "<td id=myborder>$uid</td>";
		echo "<td id=myborder align=center> $cls</td>";
		echo "<td id=myborder align=center>$sta</td>";
		echo "</tr>";
  }

  ?>
  </table>          
	
</div></div>
</body>
</html>
