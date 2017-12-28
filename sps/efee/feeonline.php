<?php
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];
		
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		$status=$_POST['status'];
		if($status!="")
			$sqlstatus="and feeonlinetrans.sta='$status'";
			
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name_matrik_ic -")==0)
			$search="";
		if($search!=""){
			$sqlsearch = "and (stu.uid='$search' or stu.ic='$search' or stu.name like '%$search%')";
			$search=stripslashes($search);
		}
			
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
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
		$sqlsort="order by $sort $order, id desc";	

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>
</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../efee/feeonline">

<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
	<a href="#" onClick="window.print();" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
	<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
	</div> <!-- end mypanel -->
	<div id="mytabletitle" class="printhidden" align="right" >
		<a href="#" title="<?php echo $vdate;?>"></a><br><br>
			<select name="sid" id="sid" onchange="document.myform.submit();">
<?php	
			if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
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
				mysql_free_result($res);
			}					  
?>
			</select>
			<select name="status" id="status" onchange="document.myform.submit();">
                <?php	
      		if($status==""){
            	echo "<option value=\"\">- $lg_all $lg_status -</option>";
				$sql="select * from type where grp='feesta' order by val";
			}
			else{
			    $sql="select * from type where grp='feesta' and val=$status";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
            	$row=mysql_fetch_assoc($res);
                $a=$row['prm'];
				$b=$row['val'];
				mysql_free_result($res);	
                echo "<option value=\"$b\">$a</option>";
				echo "<option value=\"\">- $lg_all $lg_status -</option>";			
				$sql="select * from type where grp='feesta' and val!=$status order by val";
			}
			
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$v\">$s</option>";
            }
            mysql_free_result($res);					  

?>
              </select>                
              <input name="search" type="text" id="search" size="30" onMouseDown="document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "- $lg_name_matrik_ic -"; else echo "$search";?>">
                <input type="submit" name="Submit" value="View"  >
</div>
<div id="story">
<div id="mytitlebg"><?php echo strtoupper($lg_online_payment);?> : <?php echo strtoupper($sname);?></div>
<table width="100%" cellspacing="0">
	<tr>
              <td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_no);?></td>
			  <td id="mytabletitle" width="10%" align="center"><a href="#" onClick="formsort('cdate','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_date);?></a></td>
              <td id="mytabletitle" width="8%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?></a></td>
			  <td id="mytabletitle" width="25%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			  <td id="mytabletitle" width="10%"><a href="#" onClick="formsort('bank','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("Jenis Pembayaran");?></a></td>
			  <td id="mytabletitle" width="20%"><a href="#" onClick="formsort('bankno','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_reference);?></a></td>
			  <td id="mytabletitle" width="20%"><a href="#" ><?php echo strtoupper($lg_bayar);?></a></td>
			  <td id="mytabletitle" width="6%"><a href="#" onClick="formsort('paydate','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper(" $lg_date $lg_payment");?></a></td>
			  <td id="mytabletitle" width="6%" align="center"><a href="#" onClick="formsort('rm','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_amount);?></a></td>
			  <td id="mytabletitle" width="6%" align="center"><a href="#" onClick="formsort('sta','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_status);?></a></td>
            </tr>
	<?php
		$sql=" SELECT count(*) FROM stu RIGHT JOIN feeonlinetrans ON stu.uid=feeonlinetrans.stu_uid WHERE feeonlinetrans.sch_id=$sid $sqlstatus $sqlsearch";
        $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
        $row=mysql_fetch_row($res);
        $total=$row[0];
	if(($curr+$MAXLINE)<=$total)
         $last=$curr+$MAXLINE;
    else
    	$last=$total;
		
	//$sql="select * from feeonlinetrans where sch_id=$sid $sqlstatus $sqlsearch $sqlsort limit $curr,$MAXLINE";
	$sql=" SELECT stu.uid,stu.name,stu.ic,feeonlinetrans.* FROM stu RIGHT JOIN feeonlinetrans ON stu.uid=feeonlinetrans.stu_uid WHERE feeonlinetrans.sch_id=$sid $sqlstatus $sqlsearch $sqlsort LIMIT $curr,$MAXLINE";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
			$id=$row['id'];
			$dt=$row['cdate'];
			$name=$row['name'];
			$stu_uid=$row['stu_uid'];
			$stu_id=$row['stu_id'];
			$sta=$row['sta'];
			$bank=$row['bank'];
			$bankno=$row['bankno'];
			$image=$row['image'];
			$rm=$row['rm'];
			$paydate=$row['paydate'];
			$sql2="select * from type where grp='feesta' and val=$sta";
			$res2=mysql_query($sql2)or die("query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$a=$row2['prm'];
			if($sta==1)
				$bg=$bglgreen;
			elseif($sta==2)
				$bg=$bglred;
			else
				$bg=$bglyellow;
			
		$q++;
?>
        <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
			<td id="myborder" align=center><?php echo $q;?></td>
			<td id="myborder" align=center><?php echo $dt;?></td>
			<td id="myborder" align=center><?php echo $stu_uid;?></td>
			<td id="myborder"><a href="../efee/feeonlinecheck.php?id=<?php echo $id;?>" onClick="return GB_showPage('<?php echo addslashes($name);?>',this.href)"><?php echo $name;?></a></td>
			<td id="myborder"><?php echo $bank;?></td>
			<td id="myborder"><?php echo $bankno;?></td>
			<td id="myborder"><a href="../usr_images/ewaris/<?php echo $image; ?>" target="_blank"><?php echo $image;?></a></td>
			<td id="myborder"><?php echo $paydate;?></td>
			<td id="myborder" align=center><?php echo number_format($rm,2,'.',',');?></td>
			<td id="myborder"><a href="../efee/feeonlinecheck.php?id=<?php echo $id;?>" class="fbbig"><img src="../img/edit12.png"><?php echo $a;?></a></td>
  		</tr>
<?php  }  ?>
</table>          

	<?php include("../inc/paging.php");?>
						
</div></div>
</form>
</body>
</html>
