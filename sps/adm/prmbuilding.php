<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN');
$username = $_SESSION['username'];
$grp=$_REQUEST['grp'];	
$id=$_REQUEST['id'];
$op=$_REQUEST['op'];

if($op==""){
	if($id!=""){
		$sql="select * from type where id=$id";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$prm=$row['prm'];
		$val=$row['val'];
		$idx=$row['idx'];
		$des=$row['des'];
		$code=$row['code'];
		$grp=$row['grp'];
		$lvl=$row['lvl'];
		$sid=$row['sid'];
		$etc=$row['etc'];
		$sql="select * from type where grp='dis_cat' and code=$etc order by idx"; 	
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$etcx=$row['prm'];
	}
	
	$sql="select * from type where grp='$grp'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$status=$row['sta'];
}
else{
	$del=$_POST['del'];
    $block=$_POST['block'];
	$name=$_POST['name'];
	$sex=$_POST['sex'];
	$level=$_POST['level'];
	$noroom=$_POST['noroom'];
	$nostu=$_POST['nostu'];
	$sid=$_POST['sid'];
	if($sid=="")
		$sid=0;
	$id=$_POST['id'];
	
	
				
	$op=$_POST['op'];
	if($op=='delete'){	
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from building where id=$del[$i]";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		echo "<script language=\"javascript\">location.href='prmbuilding.php?grp=$grp'</script>;";
	}
	else{
		if($id!="")
			$sql="update type set val=$val,idx=$idx,des='$des',code='$code',lvl=$lvl,sid=$sid,etc='$etc' where id=$id";
		else{
      		$sql="insert into building (name,block,level,noroom,nostu,sid,code,sex) values ('$name','$block','$level','$noroom','$nostu','$sid','$code','$sex')";
			mysql_query($sql)or die("query failed:".mysql_error());
			for($i=1;$i<=$noroom;$i++){
				for($j=1;$j<=$nostu;$j++){
					$sql="insert into building_room (name,block,level,roomno,stuno,sid,code,sex) values ('$name','$block','$level','$i','$j','$sid','$code','$sex')";
					mysql_query($sql)or die("query failed:".mysql_error());
				}
			}
		}
		echo "<script language=\"javascript\">location.href='prmbuilding.php?grp=$grp'</script>;";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="javascript">

function process_form(action){
	var ret="";
	var cflag=false;
	if(action=='new'){
		document.myform.id.value="";
		document.myform.name.value="";
		document.myform.block.value="";
		document.myform.level.value="";
		document.myform.noroom.value="";
		document.myform.nostu.value="";
		document.myform.sid.value="";
		return;
	}
	if(action=='update'){
		if(document.myform.name.value==""){
			alert("Sila masukkan nama blok");
			document.myform.name.focus();
			return;
		}
		if(document.myform.block.value==""){
			alert("Sila masukkan kod blok");
			document.myform.block.focus();
			return;
		}
		if(document.myform.level.value==""){
			alert("Sila masukkan no paras");
			document.myform.noroom.focus();
			return;
		}
		if(document.myform.noroom.value==""){
			alert("Sila masukkan bilangan bilik");
			document.myform.noroom.focus();
			return;
		}
		if(document.myform.nostu.value==""){
			alert("Sila masukkan bil pelajar setiap bilik");
			document.myform.nostu.focus();
			return;
		}
		if(document.myform.sid.value==""){
			alert("Sila masukkan is sekolah");
			document.myform.sid.focus();
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
		for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.type=='checkbox')&&(e.name!='checkall')){
                        if(e.checked==true)
                                cflag=true;
                        else
                                allflag=false;
                }
        }
		if(!cflag){
			alert('Please checked the item to delete');
			return;
		}
		ret = confirm("Are you sure want to DELETE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
}

</script>
<title>SPS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>
<body>
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<?php if(!$status){?>
<a href="#" onClick="process_form('new');show('panelform');" id="mymenuitem"><img src="../img/new.png"><br>New</a>
<a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
<a href="#" onClick="hide('panelform');process_form('new');document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
<?php } ?>
</div>
</div> <!-- end mypanel-->
<div id="story">
<form name=myform method=post >
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input name="op" type="hidden" value="">
<div id="panelform" style="display:<?php if($id=="") echo "none";else echo "block";?>">	
<div id="mytitle">
	<div id="myclick" onClick="hide('panelform');"><img src="../img/icon_minimize.gif" id="mycontrolicon">KONFIGURASI PARAMETER</div>&nbsp;
</div>
	<table width="100%"  border="0" id="mytable">
      <tr>
        <td width="12%">Kod Blok </td>
        <td width="1%">:</td>
        <td width="87%"><select name="block">
<?php			
		if($block=="")
			echo "<option value=\"\">- Pilih -</option>";
		else
			echo "<option value=\"$block\">$block</option>";
?>
		<option value="A">A</option>
		<option value="B">B</option>
		<option value="C">C</option>
		<option value="D">D</option>
		<option value="E">E</option>
		<option value="F">F</option>
        </select></td>
      </tr>
	  <tr>
        <td width="12%">Nama Blok </td>
        <td width="1%">:</td>
        <td width="87%"><input name="name" type="text"  maxlength=128 value="<?php echo $name;?>"></td>
      </tr>
      <tr>
        <td>Paras </td>
        <td>:</td>
        <td><input name="level" type="text" maxlength=32 value="<?php echo $level;?>" <?php if($status) echo "disabled";?>></td>
      </tr>
	  <tr>
        <td>Bil. Bilik/Paras </td>
        <td>:</td>
        <td><input name="noroom" type="text"  maxlength=32 value="<?php echo $noroom;?>"></td>
      </tr>
	  <tr>
        <td>Bil. Pelajar/Bilik</td>
        <td>:</td>
        <td><input name="nostu" type="text" maxlength=32 value="<?php echo $nostu;?>"></td>
      </tr>
	  <tr>
        <td>SID</td>
        <td>:</td>
        <td><input name="sid" type="text" id="sid"   maxlength=32 value="<?php echo $sid;?>"></td>
      </tr>

    </table>
</div>

	<div id="mytitle">PARAMETER SISTEM</div>
	<table width="100%" id="mytable">
      <tr id="mytabletitle">
      	<td width="5%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
		<td width="20%">Blok</td>
		<td width="10%" align="center">Paras</td>
		<td width="5%" align="center">Jumlah<br>Bilik</td>
		<td width="5%" align="center">Pelajar<br>Bilik</td>
		<td width="5%" align="center">Jantina</td>
		<td width="40%">Sekolah</td
		><td width="20%" align="center">Nama</td>
      </tr>
<?php
	$sql02="select * from building order by name, level";
	$res02=mysql_query($sql02)or die("query failed:".mysql_error());
	$q=0;
	while($row=mysql_fetch_assoc($res02)){
		$id=$row['id'];
		$name=$row['name'];
		$block=$row['block'];
		$level=$row['level'];
		$noroom=$row['noroom'];
		$nostu=$row['nostu'];
		$sex=$row['sex'];
		$sid=$row['sid'];
		if(($q++)%2==0)
            echo "<tr bgcolor=#FAFAFA>";
        else
        	echo "<tr >";
		echo "<td><input type=checkbox name=del[] value=\"$id\" onClick=\"check(0)\"></td>";
		echo "<td><a href=\"prmbuilding.php?id=$xid\" title=\"edit\" style=\"text-decoration:underline\">$block</a></td>";
		echo "<td align=\"center\">$level</td>";
		echo "<td align=\"center\">$noroom</td>";
		echo "<td align=\"center\">$nostu</td>";
		echo "<td align=\"center\">$sex</td>";
		echo "<td align=\"center\">$sid</td>";
		echo "<td align=\"center\">$name</td>";
		echo "</tr>";
	}
	mysql_free_result($res02);
?>
      </table>
</form>
</div></div>
</body>
</html>
