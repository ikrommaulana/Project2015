<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN');
$username = $_SESSION['username'];
$id=$_REQUEST['id'];
$op=$_REQUEST['op'];
$grp=$_REQUEST['grp'];
$fr=$_REQUEST['fr'];
$etc=$_REQUEST['cs'];
if($etc!=""){
	$cs=$etc;
	if($grp==""){
	$sqlcase="where category='$cs'";
	}else{
		$sqlcase=" and category='$cs'";
	}
}else{
	$sqlcase="";
	$cs="demerit";
}

if(($grp!="")&&($grp!=NULL)){
	$sqlgrp="where grp='$grp'";
	$sql="select * from type where grp='dis_cat' and code='$grp'"; 	
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$grpname=$row['prm'];
}
if($op==""){
	if($id!=""){
		$sql="select * from dis_case where id=$id";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$prm=$row['prm'];
		$val=$row['val'];
		$code=$row['code'];
		$xgrp=$row['grp'];
		$sid=$row['sid'];
		$sid=0;
		$sql="select * from type where grp='dis_cat' and code='$xgrp'"; 	
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$xgrpname=$row['prm'];
	}
}
else{
	$del=$_POST['del'];
    $prm=$_POST['prm'];
	$val=$_POST['val'];
	$xgrp=$_POST['xgrp'];
	$des=$_POST['des'];
	$idx=$_POST['idx'];
	$code=$_POST['code'];
	$cs=$_POST['cs'];
	$idx=$_POST['idx'];
	if($idx=="")
		$idx=0;
	$lvl=$_POST['lvl'];
	if($lvl=="")
		$lvl=0;
	$sid=$_POST['sid'];
	if($sid=="")
		$sid=0;
	$id=$_POST['id'];
	$op=$_POST['op'];
	if($op=='delete'){	
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from dis_case where id=$del[$i]";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		echo "<script language=\"javascript\">location.href='prm_dis.php?grp=$grp'</script>;";
	}
	else{
		if($id!="")
			$sql="update dis_case set prm='$prm',val=$val,code='$code',grp='$xgrp',val=$val where id=$id";
		else
      		$sql="insert into dis_case (prm,val,grp,code,category) values ('$prm',$val,'$xgrp','$code','$cs')";
		mysql_query($sql)or die("query failed:".mysql_error());
		echo "<script language=\"javascript\">location.href='prm_dis.php?grp=$xgrp'</script>;";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>

<script language="javascript">

function process_form(action){
	var ret="";
	var cflag=false;
	if(action=='new'){
		document.myform.prm.readOnly=false;
		document.myform.id.value="";
		document.myform.prm.value="";
		document.myform.val.value="";
		document.myform.code.value="";
		return;
	}
	if(action=='update'){
		if(document.myform.prm.value==""){
			alert("Sila masukkan kesalahan");
			document.myform.prm.focus();
			return;
		}
		if(document.myform.val.value==""){
			alert("Sila masukkan mata demerit");
			document.myform.val.focus();
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
<form name=myform action="" method=post >
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input name="op" type="hidden" value="">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<?php if(!$status){?>
<a href="../edis/dis_case_list.php?cs=<?php echo $cs;?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/goback.png"><br>Back</a>
<a href="#" onClick="process_form('new');show('panelform');" id="mymenuitem"><img src="../img/new.png"><br>New</a>
<a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
<a href="#" onClick="hide('panelform');process_form('new');document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
<?php } ?>
</div>
<div id="right" align="right">
<select name="grp" onChange="document.myform.submit();">
          <?php 
					if($grp=="")
						echo "<option value=\"\">- Semua Kategory -</option>";
					else
						echo "<option value=\"$grp\">$grpname</option>";
                  	$sql="select * from type where grp='dis_cat' and code!='$grp' order by prm"; 	
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						$v=$row['code'];
						echo "<option value=\"$v\">$s</option>";
					}
					if($grp!="")
						echo "<option value=\"\">- Semua Kategori -</option>";
		?>
        </select> 
</div>
</div> <!-- end mypanel-->
<div id="story">

<div id="panelform" style="display:<?php if($id=="") echo "none";else echo "block";?>">	
<div id="mytitle">
	<div id="myclick" onClick="hide('panelform');"><img src="../img/icon_minimize.gif" id="mycontrolicon">KONFIGURASI PARAMETER</div>&nbsp;
</div>
	<table width="100%"  border="0" id="mytable">
	  <tr>
        <td width="12%">Category</td>
        <td width="1%">:</td>
        <td width="87%">
		<select name="xgrp" >
        <?php 
			if($xgrp=="")
				echo "<option value=\"\">- Pilih Kategory -</option>";
			else
				echo "<option value=\"$xgrp\">$xgrpname</option>";
            $sql="select * from type where grp='dis_cat' and etc='$cs' and code!='$xgrp' order by prm"; 	
			$res=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($row=mysql_fetch_assoc($res)){
				$s=$row['prm'];
				$v=$row['code'];
				echo "<option value=\"$v\">$s</option>";
			}
		?>
        </select><?php if(is_verify('ADMIN')){?>
        <input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=dis_cat',0)">
        <?php } ?>
		</td>
      </tr>
      <tr>
	  <?php if($cs='demerit'){?>
        <td width="12%">Kesalahan</td>
		<?php }else{?>
		<td width="12%">Prestasi</td>
		<?php }?>
        <td width="1%">:</td>
        <td width="87%"><input name="prm" type="text" id="prm"  maxlength=255 size="70" value="<?php echo $prm;?>"></td>
      </tr>
	  	<tr>
        <td>Kod</td>
        <td>:</td>
        <td><input name="code" type="text" id="code"   maxlength=32 value="<?php echo $code;?>">
	<input name="cs" type="hidden" id="cs"   maxlength=32 value="<?php echo $cs;?>"></td>
      </tr>
      <tr>
		<?php if($css='demerit'){?>
        <td>Demerit</td>
		<?php }else{?>
		<td>Merit</td>
		<?php }?>
        <td>:</td>
        <td><input name="val" type="text" id="val"  maxlength=32 value="<?php echo $val;?>" <?php if($status) echo "disabled";?>></td>
      </tr>
	  <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
</div>

	<div id="mytitle">PARAMETER SISTEM</div>
	<table width="100%" id="mytable">
      <tr id="mytabletitle">
      	<td width="5%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
		<?php if($cs="demerit"){?>
		<td width="80%">Kasus</td>
		<td width="5%" align="center">Demerit</td>
		<?php }else{?>
		<td width="80%">Prestasi</td>
		<td width="5%" align="center">Merit</td>
		<?php }?>
		
		<td width="10%" align="center">Code</td>
      </tr>
<?php
	$sql="select * from dis_case $sqlgrp $sqlcase order by id";
	//$sql="select * from dis_case order by id";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$q=0;
	while($row=mysql_fetch_assoc($res)){
		$prm=$row['prm'];
		$val=$row['val'];
		$xid=$row['id'];
		$cod=$row['code'];
		if(($q++)%2==0)
            echo "<tr bgcolor=#FAFAFA>";
        else
        	echo "<tr >";
		echo "<td><input type=checkbox name=del[] value=\"$xid\" onClick=\"check(0)\"></td>";
		echo "<td><a href=\"prm_dis.php?id=$xid\" title=\"edit\" style=\"text-decoration:underline\">$prm</a></td>";
		echo "<td align=\"center\">$val</td>";
		echo "<td align=\"center\">$cod</td>";
		echo "</tr>";
	}
?>
      </table>
</form>
</div></div>
</body>
</html>
