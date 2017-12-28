<?php
$vmod="v5.5.0";
$vdate="110505";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
	verify('ADMIN');
	$adm = $_SESSION['username'];

	$sid=$_REQUEST['sid'];
	if(($sid=="")||($sid=="0"))
		$sid=0;
	else
		$sqlsid="and sch_id=$sid";
	
	$rdate=$_REQUEST['rdate'];
	$edate=$_REQUEST['edate'];
	if(($rdate!="")&& ($edate!="")){
		$sqldt=" and cdate>='$rdate 00:00:00' and cdate<='$edate 23:59:59'";
	}
	if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$simg=$row['img'];
            mysql_free_result($res);					  
	}

	$item=$_REQUEST['item'];
	if($item!="")
		$sqlitem="and item_name='$item'";
		
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
<input type="hidden" name="p" value="<?php echo $p;?>">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>	
	<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
</div> <!-- end mymenu -->
	<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
	</div> <!-- end mypanel -->
	<div id="mytabletitle" class="printhidden" align="right" >
<a href="#" title="<?php echo $vdate;?>"></a><br>
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
		  <select name="item"  onChange="document.myform.submit()">
                <?php	
      		if($item=="")
            	echo "<option value=\"\">- All Item -</option>";
			else
                echo "<option value=\"$item\">$item</option>";
			if($_SESSION['sid']==0){
				$sql="select * from type where grp='saleitem' and prm!='$item' order by prm";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['prm'];
							$t=$row['id'];
							echo "<option value=\"$s\">$s</option>";
				}
				if($item!="")
            		echo "<option value=\"\">- All Item -</option>";
			}										  
			
?>
          </select>
                <input name="rdate"  type="text" id="rdate" value="<?php echo "$rdate";?>" size="20" readonly onClick="displayDatePicker('rdate');">
                <input name="edate" type="text" id="edate" value="<?php echo "$edate";?>" size="20" readonly onClick="displayDatePicker('edate');">
              <input type="submit" name="Submit" value="View"  >
</div>
<div id="story">
<div id="mytitlebg">SALES ITEM : <?php echo $sname;?></div>
<table width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td id="mytabletitle" align="center" width="1%" >NO</td>
        <td id="mytabletitle" align="center" width="10%" >DATE</td>
		<td id="mytabletitle" align="center" width="25%" >NAME</td>
		<td id="mytabletitle" align="center" width="10%" >TYPE</td>
		<td id="mytabletitle" align="center" width="10%" >ITEM</td>
 		<td id="mytabletitle" align="center"  width="7%">QUANTITY</td>
		<td id="mytabletitle" width="7%" align="center">UNIT PRICE</td> 
 		<td id="mytabletitle" width="7%" align="center">TOTAL PRICE</td> 
	</tr>
<?php
	if($total==""){
		$sql="select count(*) from feepay where type='sale' $sqlsid $sqldt $sqlitem";
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

	$sql02="select * from feepay where type='sale' $sqlsid $sqlitem $sqldt $sqlsort $sqlmaxline";
	$res02=mysql_query($sql02)or die("query failed:".mysql_error());
	$q=$curr;
	while($row=mysql_fetch_assoc($res02)){
			$id=$row['id'];
			$dt=$row['cdate'];
			$uid=$row['stu_uid'];
			
			$sql="select * from stu where uid='$uid'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$name=$row2['name'];
			
			$resit=$row['resitno'];
			$item=$row['item_name'];
			$type=$row['item_type'];
			$rm=$row['rm'];
			$unitprice=$row['item_price'];
			$code=$row['item_code'];
			$qtt=$row['unit'];
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
	<tr bgcolor=<?php echo $bg;?>>
			<td id="myborder" align="center"><?php echo $q;?></td>
			<td id="myborder" align="center"><?php echo $dt;?></td>
			<td id="myborder"><?php echo "$uid $name";?></td>
			<td id="myborder" align="center"><?php echo $type;?></td>
			<td id="myborder" align="center"><?php echo $item;?></td>
			<td id="myborder" align="center"><?php echo $qtt;?></td>
			<td id="myborder"align="center"><?php echo number_format($unitprice,2,'.',',');?></td>  
			<td id="myborder"align="center"><?php echo number_format($rm,2,'.',',');?></td>  
	</tr>
<?php } ?>
</table>  
<?php include_once('../inc/paging.php');?>        
</form>

</div></div>
</body>
</html>
