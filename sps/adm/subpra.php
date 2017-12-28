<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN');
$username = $_SESSION['username'];
$xcod=$_REQUEST['xcod'];
$xsid=$_REQUEST['xsid'];
$xlvl=$_REQUEST['xlvl'];
	
	

		$sql="select * from sch where id=$xsid";
        $res=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
	  

		$sql="select * from sub where sch_id=$xsid and level='$xlvl' and code='$xcod'";
        $res=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $xname=$row['name'];	  

	
$presub=$_REQUEST['presub'];
$del=$_POST['del'];
$op=$_REQUEST['op'];

if($op!=""){
	if($op=='delete'){	
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from prasyarat where id=$del[$i]";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
		$id="";
	}
	else{
		$sql="select * from sub where id='$presub'";
        $res=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $pname=$row['name'];
		$pcode=$row['code'];
		
		$sql="insert into prasyarat (sid,code,name,pcode,pname,adm,ts) values ('$xsid','$xcod','$xname','$pcode','$pname','$username',now())";
		 mysql_query($sql)or die("query failed:".mysql_error());
		$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
		$id="";
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
	
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
			$sqlsort="order by id $order";
	else
			$sqlsort="order by $sort $order,idx asc";


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
	if(action=='update'){
		if(document.myform.presub.value==""){
			alert("Please select the subject");
			document.myform.presub.focus();
			return;
		}

		document.myform.op.value=action;
		document.myform.submit();
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
<body >
<form name=myform method="post" action="">
	<input type="hidden" name="xsid" value="<?php echo $xsid;?>">
    <input type="hidden" name="xcod" value="<?php echo $xcod;?>">
    <input type="hidden" name="xlvl" value="<?php echo $xlvl;?>">
	<input type="hidden" name="op"  value="">
    
<div id="content">
<div id="mypanel">
    <div id="mymenu" align="center">
            <a href="#" onClick="show('panelform');" id="mymenuitem"><img src="../img/new.png"><br>New</a>
            <a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
            <a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
            <a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
            <a href="#" onClick="hide('panelform');document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
            <a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
    </div>
</div> <!-- end mypanel-->
<div id="story">
<div id="mytitle2">Prerequisite - <?php echo "$xcod:$xname - Level $xlvl";?> <?php echo $f;?></div>

<div id="panelform" style="display:<?php if($id=="") echo "none";else echo "block";?>">	
<div id="mytitle">
	<div id="myclick" onClick="hide('panelform');"><img src="../img/icon_minimize.gif" id="mycontrolicon">PREREQUISITE</div>&nbsp;
</div>
<br>
<select name="presub">
                                                <?php 
													echo "<option value=\"\">- Select Subject -</option>";
                                                    $sql="select * from sub where sch_id=$xsid order by level,name";
                                                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                                                    while($row=mysql_fetch_assoc($res)){
                                                                $id=$row['id'];
																$v=$row['code'];
																$l=$row['level'];
                                                                $s=stripslashes($row['name']);
                                                                echo "<option value=\"$id\">Level $l - $v - $s</option>";
                                                    }
                                                ?>
</select> 
                     

</div>

	<table width="100%" cellspacing="0">
      <tr>
	    <td id="mytabletitle" width="2%" align="center">BIL</td>
      	<td id="mytabletitle" width="1%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
        <td id="mytabletitle" width="10%" align="center"><a href="#" onClick="formsort('code','<?php echo "$nextdirection";?>')" title="sort">CODE</a></td>
		<td id="mytabletitle" width="90%"><a href="#" onClick="formsort('prm','<?php echo "$nextdirection";?>')" title="sort">SUBJECT</a></td>
      </tr>
<?php
	$sql="select * from prasyarat where sid='$xsid' and code='$xcod' $sqlsort";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$pname=stripslashes($row['pname']);
		$pcode=$row['pcode'];
		$xid=$row['id'];
		if(($q++%2)==0)
			$bg="";
		else
			$bg="#FAFAFA";
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">

<?php
		echo "<td id=myborder align=\"center\">$q</td>";
		echo "<td id=myborder align=\"center\"><input type=checkbox name=del[] value=\"$xid\" onClick=\"check(0)\"></td>";
		echo "<td id=myborder align=\"center\">$pcode</td>";
		echo "<td id=myborder>$pname</td>";
		echo "</tr>";
	}
?>
      </table>

</form>
</div></div>
</body>
</html>
