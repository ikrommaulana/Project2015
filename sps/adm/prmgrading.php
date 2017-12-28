<?php
//24/06/10 4.1.0 - set type manually
$vmod="v4.1.0";
$vdate="24/06/10";

include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN');
$username = $_SESSION['username'];
$set=$_REQUEST['set'];
$id=$_REQUEST['id'];
$op=$_REQUEST['op'];
if($op==""){
	if($id!=""){
		$sql="select * from grading where id=$id";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$set=$row['name'];
		$typ=$row['type'];
		$p=$row['point'];
		$g=$row['grade'];
		$v=$row['val'];
		$gp=$row['gp'];
		$sta=$row['sta'];
		$des=$row['des'];
	}
}
else{
	$del=$_POST['del'];
	$xfee=$_POST['set'];
	$xp=$_POST['point'];
	$xg=$_POST['grade'];
	$xv=$_POST['val'];
	$xd=$_POST['des'];
	$xgp=$_POST['gp'];
	$sta=$_POST['sta'];
	$typ=$_POST['typ'];
	if($sta=="")
		$sta=0;
	$sql="select * from type where prm='$xfee' and grp='grading'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$xt=$row['val'];
	
	if($op=='delete'){	
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from grading where id=$del[$i]";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		echo "<script language=\"javascript\">location.href='prmgrading.php?set=$xfee'</script>;";
	}
	else{
		if($id!="")
			$sql="update grading set grade='$xg',val=$xv,point='$xp',des='$xd',sta=$sta,gp=$xgp,type=$typ,adm='$username',ts=now() where id=$id";
		else
      		$sql="insert into grading (name,type,point,grade,val,des,sta,gp,adm,ts) values ('$xfee',$typ,'$xp','$xg',$xv,'$xd',$sta,$xgp,'$username',now())";
		
		mysql_query($sql)or die("Failed: $sql >>".mysql_error());
		echo "<script language=\"javascript\">location.href='prmgrading.php?set=$xfee'</script>;";
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
		document.myform.point.value="";
		document.myform.id.value="";
		document.myform.op.value="";
		document.myform.val.value="";
		document.myform.grade.value="";
		document.myform.point.value="";
		document.myform.gp.value="";
		document.myform.sta.value="";
		return;
	}
	if(action=='reset'){
		//document.myform.id.value="";
		//document.myform.point.value="";
		document.myform.op.value="";
		document.myform.val.value="";
		document.myform.grade.value="";
		document.myform.gp.value="";
		document.myform.sta.value="";
		return;
	}
	else if(action=='update'){
		if(document.myform.set.value==""){
			alert("Sila pilih grading set");
			document.myform.set.focus();
			return;
		}
		if(document.myform.point.value==""){
			alert("Sila masukkan markah peperiksaan");
			document.myform.point.focus();
			return;
		}
		if(document.myform.val.value==""){
			alert("Sila masukkan point markah");
			document.myform.val.focus();
			return;
		}
		if(document.myform.grade.value==""){
			alert("Sila masukkan gred peperiksaan");
			document.myform.grade.focus();
			return;
		}
		if(document.myform.gp.value==""){
			alert("Sila masukkan Gred Point");
			document.myform.gp.focus();
			return;
		}
		ret = confirm("Are you sure want to SAVE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
	else if(action=='delete'){
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

<title>Parameter</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>
<body>
<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center" <?php if (!is_verify('ROOT')) echo "style=\"visibility:hidden\"";?>>
		<a href="#" onClick="process_form('new');show('panelform');" id="mymenuitem"><img src="../img/new.png"><br>New</a>
		<a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
		<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
		<a href="#" onClick="hide('panelform');process_form('new');document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
	<form name=myform action="<?php echo $_SERVER['PHP_SELF'];?>" method=post >
	<input name="id" type="hidden" value="<?php echo $id;?>">
	<input name="op" type="hidden" >
	<table width="100%"><tr><td align="right">
	<select name="set" id="set" onchange="process_form('new');document.myform.submit();">
      <?php	
      		if($set=="")
            	echo "<option value=\"\">- Select Grading -</option>";
			else
                echo "<option value=\"$set\">$set</option>";
				$sql="select * from type where prm!='$set' and grp='grading' order by prm";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['prm'];
							$t=$row['id'];
							echo "<option value=\"$s\">$s</option>";
				}
				mysql_free_result($res);
						  
			
?>
    </select>
	<br>
&nbsp;&nbsp;
	 <a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
</td></tr></table>
</div> <!-- end mypanel-->
<div id="story">
<div id="panelform" style="display:<?php if($id=="") echo "none";else echo "block";?> ">
<div id="mytitle">
	<div id="myclick" onClick="hide('panelform');"><img src="../img/icon_minimize.gif" id="mycontrolicon">KONFIGURASI GRADING</div>&nbsp;
</div>
   <table width="100%" border="0" id="mytable">
      <tr>
        <td width="5%">Min Point</td>
        <td width="1%">:</td>
        <td width="94%"><input name="point" type="text" id="point" size="12"  maxlength=32 value="<?php echo $p;?>" ></td>
      </tr>
      <tr>
        <td>Max Point</td>
        <td>:</td>
        <td><input name="val" type="text" id="val" size="5"  maxlength=3 value="<?php echo $v;?>"></td>
      </tr>
	  <tr>
        <td>Grade</td>
        <td>:</td>
        <td><input name="grade" type="text" id="grade" size="12"  maxlength=32 value="<?php echo $g;?>"></td>
      </tr>
	  <tr>
        <td>Gred Point</td>
        <td>:</td>
        <td><input name="gp" type="text" id="gp" size="12"  maxlength=32 value="<?php echo $gp;?>"></td>
      </tr>
	  <tr>
        <td>Status</td>
        <td>:</td>
        <td><input name="sta" type="text" id="sta" size="3"  maxlength=2 value="<?php echo $sta;?>"></td>
      </tr>
      <tr>
        <td>Description</td>
        <td>:</td>
        <td><input name="des" type="text"  value="<?php echo $des;?>"></td>
      </tr>
	  <tr>
        <td>Grade Type</td>
        <td>:</td>
        <td><input name="typ" type="text" id="typ" size="3"  maxlength=2 value="<?php echo $typ;?>"></td>
      </tr>
    </table>
</div>
	<div id="mytitle">PARAMETER GRADING</div>
	<table width="100%" cellspacing="0">
      <tr id="mytabletitle">
      	<td width="5%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
		<td width="20%">Grading</td>
		<td width="5%" align="center">Min Point</td>
		<td width="5%" align="center">Max Point</td>
		<td width="5%" align="center">Grade</td>
		<td width="5%" align="center">Grade Point</td>
		<td width="5%" align="center">Type</td>
		<td width="5%" align="center">Status</td>
		<td >Description</td>
      </tr>
<?php
	$sql="select * from grading where name='$set' order by val desc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$q=0;
	while($row=mysql_fetch_assoc($res)){
		$n=$row['name'];
		$t=$row['type'];
		$p=$row['point'];
		$g=$row['grade'];
		$v=$row['val'];
		$gp=$row['gp'];
		$d=$row['des'];
		$xid=$row['id'];
		$sta=$row['sta'];
		if(($q++)%2==0)
            $bg="#FAFAFA";
        else
        	$bg="";
	?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
<?php
		echo "<td><input type=checkbox name=del[] value=\"$xid\" onClick=\"check(0)\"></td>";
		echo "<td><a href=\"prmgrading.php?id=$xid\" title=\"edit\" style=\"text-decoration:underline\">$n</a></td>";
		
		echo "<td align=\"center\">$p</td>";
		echo "<td align=\"center\">$v</td>";
		echo "<td align=\"center\">$g</td>";
		echo "<td align=\"center\">$gp</td>";
		echo "<td align=\"center\">$t</td>";
		echo "<td align=\"center\">$sta</td>";
		echo "<td>$d</td>";
		echo "</tr>";
	}
	mysql_free_result($res);
?>
      </table>
	    </form>
</div></div>
</body>
</html>
