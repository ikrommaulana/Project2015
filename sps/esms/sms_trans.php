<?php
include_once('../etc/db.php');
include_once('etc/session_sms.php');
	verify('ADMIN');
	$username = $_SESSION['username'];

	$year=$_REQUEST['year'];
	if($year=="")
			$year=date('Y');
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=0;
	else
		$sqlsid="and sid=$sid";
	
	$rdate=$_REQUEST['rdate'];
	$edate=$_REQUEST['edate'];
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
	if($MAXLINE==0)
		$MAXLINE=30;
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
<?php include("$MYOBJ/calender/calender.htm")?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="sms_trans">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div> <!-- end mymenu -->
<div align="right">
			<select name="year" id="year" >
                  <?php
            echo "<option value=$year>$year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">$s</option>";
            }
            mysql_free_result($res);					  

			?>
              </select>
				<select name="sid" id="sid" onChange="document.myform.submit()">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- All School -</option>";
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
            		echo "<option value=\"0\">- All School -</option>";
			}										  
			
?>
              </select>
                <input name="rdate" type="text" id="rdate" value="<?php echo "$rdate";?>" size="12" readonly>
                <input name=" cal" type="button" id=" cal" value="-" onClick="c2.popup('rdate')">
                <input name="edate" type="text" id="edate" value="<?php echo "$edate";?>" size="12" readonly=>
                <input name=" cal2" type="button" id=" cal2" value="-" onClick="c2.popup('edate')">
              <input type="submit" name="Submit" value="View"  >

	</div>
</div>
<div id="story">

<?php if($simg!=""){?>
<div id="mytitle" align="center" style="border:none">
	<img src=<?php echo "$simg";?> >
</div>
<?php } ?>
<div id="mytitle" align="center" style="border:none ">SMS TRANSACTION LOG</div>
<table width="100%">
	<tr>
		<td id="mytabletitle" align="center" width="1%" >NO</td>
        <td id="mytabletitle" align="center" width="10%" >DATE</td>
		<td id="mytabletitle" align="center" width="8%" >APPLICATION</td>
		<td id="mytabletitle" align="center" width="8%" >TELEPHONE</td>
 		<td id="mytabletitle" width="60%">MESSAGE</td>
		<td id="mytabletitle" width="8%" align="center">By</td> 
		<td id="mytabletitle" width="2%" align="center">Sta</td> 
	</tr>
<?php
	if($total==""){
		$sql="select count(*) from sms_log where id>0 $sqlsid $sqlsearch";
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

	$sql02="select * from sms_log where id>0 $sqlsid $sqlsearch $sqlsort limit $curr,$MAXLINE";
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
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
	<tr bgcolor=<?php echo $bg;?>>
		<td align="center" width="1%"><?php echo $q;?></td>
        <td align="center" width="10%"><?php echo $xdt;?></td>
		<td align="center" width="8%"><?php echo $xapp;?></td>
		<td align="center" width="8%"><?php echo $xtel;?></td>
 		<td width="60%"><?php echo $xmsg;?></td>
		<td width="8%" align="center"><?php echo $xadm;?></td> 
		<td width="2%" align="center"><?php echo $xsta;?></td> 
	</tr>
<?php } ?>
</table>  
<?php include_once('../inc/paging.php');?>        
</form>

</div></div>
</body>
</html>
