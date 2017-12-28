<?php
$vdate="110412";
$vmod="v5.3.0";

include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN');
$adm = $_SESSION['username'];
$grp=$_REQUEST['grp'];	
$id=$_REQUEST['id'];
$op=$_REQUEST['op'];

$sid=$_REQUEST['sid'];
if($sid=="")
		$sid=$_SESSION['sid'];

if($sid!=""){
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$sqlsid="and sid=$sid";	  
}
$year=$_REQUEST['year'];
if($year=="")
		$year=date('Y');

	$del=$_POST['del'];
    $prm=addslashes($_POST['prm']);
	$val=$_POST['val'];
	if($val=="")
		$val=0;
	$grp=$_POST['grp'];
	$des=$_POST['des'];
	$etc=$_POST['etc'];
	$idx=$_POST['idx'];
	$code=$_POST['code'];
	$idx=$_POST['idx'];
	if($idx=="")
		$idx=0;
	$lvl=$_POST['lvl'];
	if($lvl=="")
		$lvl=0;

	$id=$_POST['id'];
	$op=$_POST['op'];
	$lockval=$_POST['locking'];
	if($op=='update'){	
		for ($j=1,$i=0; $i<count($lockval); $i++,$j++) {
				$cutoff=$_POST["cutoff$j"];
				list($xsid,$xlvl,$xcod,$xyr,$xsta)=explode("|",$lockval[$i]);
		      	$sql="delete from examlock where sid=$xsid and lvl=$xlvl and exam='$xcod' and year='$xyr'";
				mysql_query($sql)or die("$sql:".mysql_error());
				$sql="insert into examlock (sid,lvl,exam,year,off,adm,ts) values ('$xsid','$xlvl','$xcod','$xyr','$cutoff','$adm',now())";
				mysql_query($sql)or die("$sql:".mysql_error());
		}
		$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
		$id="";
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
		$sqlsort="order by $sort $order";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php include("$MYOBJ/datepicker/dp.php")?>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="javascript">

function process_form(action){
	var ret="";
	var cflag=false;

	ret = confirm("Are you sure want to SAVE??");
	if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
	}
	return;
}

</script>
<title>SPS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>
<body >
<form name=myform method="post" action="">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input name="grp" type="hidden" value="<?php echo $grp;?>">
	<input name="op" type="hidden" value="">
<div id="content">
<div id="mypanel">
        <div id="mymenu" align="center">
        <a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
        <a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
        </div>

        <div align="right">
        <a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
        
        <select name="sid" onchange="document.myform.submit();">
        <?php	
                    if($sid=="")
                        echo "<option value=\"\">- All School -</option>";
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
                        echo "<option value=\"\">- All School -</option>";
                    }							  
        ?>
        </select>
        <select name="year" id="year" onchange="document.myform.submit();">
        <?php
                    echo "<option value=$year>$lg_session $year</option>";
                    $sql="select * from type where grp='session' and prm!='$year' order by val desc";
                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                    while($row=mysql_fetch_assoc($res)){
                                $s=$row['prm'];
                                $v=$row['val'];
                                echo "<option value=\"$s\">$lg_session $s</option>";
                    }		  
        ?>
        </select>
        </div>
</div> <!-- end mypanel-->
<div id="story">

	<div id="mytitle">PARAMETER SISTEM <?php echo $f;?></div>
	<table width="100%" cellspacing="0">
      <tr>
	    <td id="mytabletitle" width="2%" align="center">BIL</td>
		<td id="mytabletitle" width="20%"><a href="#" onClick="formsort('prm','<?php echo "$nextdirection";?>')" title="sort">EXAMINATION</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('val','<?php echo "$nextdirection";?>')" title="sort">CUTOFF DATE</a></td>
		<td id="mytabletitle" width="10%" align="center"><a href="#" onClick="formsort('code','<?php echo "$nextdirection";?>')" title="sort">CODE</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('sid','<?php echo "$nextdirection";?>')" title="sort">SID</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('lvl','<?php echo "$nextdirection";?>')" title="sort">LEVEL</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('adm','<?php echo "$nextdirection";?>')" title="sort">adm</a></td>
      </tr>
<?php
	$sql="select * from type where grp='exam'  $sqlsid $sqlsort";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$prm=stripslashes(strtoupper($row['prm']));
		$val=$row['val'];
		$xid=$row['id'];
		$idx=$row['idx'];
		$cod=$row['code'];
		$lvl=$row['lvl'];
		$sid=$row['sid'];
		$sql="select * from examlock where year='$year' and sid=$sid and lvl=$lvl and exam='$cod'";
		$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$locking=$row2['sta'];
		$xadm=$row2['adm'];
		$offdate=$row2['off'];
		$nowdate=date('Y-m-d');
		
		if(($q++%2)==0)
			$bg="";
		else
			$bg="#FAFAFA";
			
		if(($offdate<$nowdate)&&($offdate!=""))
			$bgc="$bglred";
		else
			$bgc="";
			
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
<?php
		echo "<td id=myborder align=\"center\">$q</td>";
		echo "<td id=myborder>$prm</td>";
		echo "<td bgcolor='$bgc' id=myborder align=\"center\">";
?>
		   <input type="hidden" name="locking[]" value="<?php echo "$sid|$lvl|$cod|$year";?>">
		   <input type="text" value="<?php echo $offdate;?>" readonly name="cutoff<?php echo $q;?>" onClick="displayDatePicker('cutoff<?php echo $q;?>');">
<?php 
		echo "</td>";
		echo "<td id=myborder align=\"center\">$cod</td>";
		echo "<td id=myborder align=\"center\">$sid</td>";
		echo "<td id=myborder align=\"center\">$lvl</td>";
		echo "<td id=myborder align=\"center\">$xadm</td>";
		echo "</tr>";
	}
?>
      </table>
<?php include("../inc/paging.php");?>

</div></div>
</form>
</body>
</html>
