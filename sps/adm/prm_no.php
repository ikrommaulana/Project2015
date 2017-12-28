<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN');
$username = $_SESSION['username'];
$id=$_REQUEST['id'];
$op=$_REQUEST['op'];
$grp=$_REQUEST['grp'];
if($op==""){
	if($id!=""){
		$sql="select * from number where id=$id";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$digit=$row['digit'];
		$word=$row['word'];
	}
}
else{
	$del=$_POST['del'];
	$digit=$_POST['digit'];
	$word=$_POST['word'];
	$id=$_POST['id'];
	$op=$_POST['op'];
	if($op=='delete'){	
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from number where id=$del[$i]";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		echo "<script language=\"javascript\">location.href='prm_no.php?grp=$grp'</script>;";
	}
	else{
		if($id!=""){
			$sql="update number set digit='$digit',word='$word' where id=$id";
			mysql_query($sql)or die("query failed:".mysql_error());
		}
		else{
      		$sql="insert into number (digit,word) values ('$digit','$word')";
		mysql_query($sql)or die("query failed:".mysql_error());
		}
		echo "<script language=\"javascript\">location.href='prm_no.php?grp=$xgrp'</script>;";
	}
}

/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=30;
		$sqlmaxline="limit $curr,$MAXLINE";
	}
	elseif($MAXLINE=="All"){
		$sqlmaxline="";
	}
	else{
		$sqlmaxline="limit $curr,$MAXLINE";
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
		document.myform.digit.readOnly=false;
		document.myform.id.value="";
		document.myform.digit.value="";
		document.myform.word.value="";
		return;
	}
	if(action=='update'){
		if(document.myform.digit.value==""){
			alert("Silah masukkan nomor");
			document.myform.digit.focus();
			return;
		}
		if(document.myform.word.value==""){
			alert("Silah masukkan huruf angka");
			document.myform.word.focus();
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
<a href="#" onClick="process_form('new');show('panelform');" id="mymenuitem"><img src="../img/new.png"><br>New</a>
<a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
<a href="#" onClick="hide('panelform');process_form('new');document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
<?php } ?>
</div>

</div> <!-- end mypanel-->
<div id="story">

<div id="panelform" style="display:<?php if($id=="") echo "none";else echo "block";?>">	
<div id="mytitle">
	<div id="myclick" onClick="hide('panelform');"><img src="../img/icon_minimize.gif" id="mycontrolicon">KONFIGURASI PARAMETER</div>&nbsp;
</div>
	<table width="100%"  border="0" id="mytable">
      <tr>
        <td width="12%">Angka</td>
        <td width="1%">:</td>
        <td width="87%"><input name="digit" type="text" id="digit"  maxlength="5" size="10" value="<?php echo $digit;?>"></td>
      </tr>
	  	<tr>
        <td>Huruf</td>
        <td>:</td>
        <td><input name="word" type="text" id="word" size="70" maxlength="255" value="<?php echo $word;?>"></td>
      </tr>
    </table>
</div>

	<div id="mytitle">PARAMETER SISTEM</div>
	<table width="100%" id="mytable">
      <tr id="mytabletitle">
      	<td width="5%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
		<td width="10%">Angka</td>
		<td width="90%">huruf</td>
      </tr>
<?php
	$sql="select count(*) from number order by id";
$res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
	
	$sql="select * from number order by id $sqlmaxline";
	//$sql="select * from dis_case order by id";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$q=$curr;
	while($row=mysql_fetch_assoc($res)){
		$digit=$row['digit'];
		$word=$row['word'];
		$xid=$row['id'];
		if(($q++)%2==0)
            echo "<tr bgcolor=#FAFAFA>";
        else
        	echo "<tr >";
		echo "<td><input type=checkbox name=del[] value=\"$xid\" onClick=\"check(0)\"></td>";
		echo "<td><a href=\"prm_no.php?id=$xid\" title=\"edit\" style=\"text-decoration:underline\">$digit</a></td>";
		echo "<td>$word</td>";
		echo "</tr>";
	}
?>
      </table>
	<?php include("../inc/paging.php");?>
</form>
</div></div>
</body>
</html>
