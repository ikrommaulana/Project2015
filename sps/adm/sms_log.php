<?php
$vmod="v6.0.0";
$vdate="121207";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
	verify('ADMIN');
	$adm = $_SESSION['username'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=0;
	else
		$sqlsid="and sid=$sid";
	//$sqlsid="";
	
	$rdate=$_REQUEST['rdate'];
	$edate=$_REQUEST['edate'];
	if(($rdate!="")&& ($edate!="")){
		$sqldt=" and ts>='$rdate 00:00:00' and ts<='$edate 23:59:59'";
	}
	if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$simg=$row['img'];
            mysql_free_result($res);					  
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


<html>
<head>
<?php include("$MYOBJ/datepicker/dp.php")?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="sms_log">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
    	<div id="mymenu_space">&nbsp;&nbsp;</div>
        <div id="mymenu_seperator"></div>
        <div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
    	<div id="mymenu_space">&nbsp;&nbsp;</div>
        <div id="mymenu_seperator"></div>
        <div id="mymenu_space">&nbsp;&nbsp;</div>
</div> <!-- end mymenu -->
	<div align="right">
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>				
	</div>
</div>
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">
<select name="sid" id="sid" onChange="document.myform.submit()">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_all -</option>";
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
				if($sid!="0")
            		echo "<option value=\"0\">- $lg_all -</option>";
			}										  
			
?>
              </select>
                <?php echo $lg_from;?> <input name="rdate"  type="text" id="rdate" value="<?php echo "$rdate";?>" size="20" readonly onClick="displayDatePicker('rdate');">
                -<input name="edate" type="text" id="edate" value="<?php echo "$edate";?>" size="20" readonly onClick="displayDatePicker('edate');">
              <input type="submit" name="Submit" value="<?php echo $lg_view;?>"  >

</div>
<div id="story">
<div id="mytitle2"><?php echo $lg_transaction_log;?></div>
<table width="100%" cellspacing="0" cellpadding="3">
	<tr>
		<td id="mytabletitle" align="center" width="1%"><?php echo $lg_no;?></td>
        <td id="mytabletitle" align="center" width="10%"><?php echo $lg_date;?></td>
		<td id="mytabletitle" align="center" width="8%"><?php echo $lg_category;?></td>
		<td id="mytabletitle" align="center" width="8%"><?php echo $lg_to;?></td>
 		<td id="mytabletitle" width="60%"><?php echo "Message";?></td>
		<td id="mytabletitle" width="8%" align="center"><?php echo $lg_by;?></td> 
		<td id="mytabletitle" width="4%" align="center"><?php echo $lg_status;?></td> 
	</tr>
<?php
	if($total==""){
		$sql="select count(*) from sms_log where id>0 $sqlsid $sqldt $sqlsearch";
        $res=mysql_query($sql,$link)or die("query failed:".mysql_error());
        $row=mysql_fetch_row($res);
        $total=$row[0];
        if($total=="")
			$total=0;
    }
	if(($curr+$MAXLINE)<=$total)
		$last=$curr+$MAXLINE;
	else
		$last=$total;

	$sql02="select * from sms_log where id>0 $sqlsid $sqlsearch  $sqldt $sqlsort $sqlmaxline";
	$res02=mysql_query($sql02)or die("query failed:".mysql_error());
	$q=$curr;
	while($row=mysql_fetch_assoc($res02)){
			$id=$row['id'];
			$xdt=$row['ts'];
			$xtel=$row['tel'];
			$xmsg=$row['msg'];
			$xsta=$row['sta'];
			$xadm=$row['adm'];
			$xapp=$row['app'];
			$des=$row['des'];
			if(($q++%2)==0)
				$bg="";
			else
				$bg="";
?>
 <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td id="myborder" align="center" width="1%"><?php echo $q;?></td>
        <td id="myborder" align="center" width="10%"><?php echo $xdt;?></td>
		<td id="myborder" align="center" width="8%"><?php echo $xapp;?></td>
		<td id="myborder" align="center" width="8%"><?php echo $xtel;?></td>
 		<td id="myborder" width="60%"><?php echo $xmsg;?></td>
		<td id="myborder" width="8%" align="center"><?php echo $xadm;?></td> 
		<td id="myborder" width="2%" align="center"><?php echo $des;?></td> 
	</tr>
<?php } ?>
</table>  
<?php include_once('../inc/paging.php');?>        
</form>

</div></div>
</body>
</html>
