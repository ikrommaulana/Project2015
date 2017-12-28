<?php 
include_once('../etc/db.php');
include_once('session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify();

	$sql="select * from type where grp='openexam' and prm='EDISIPLIN'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['val'];
	if($sta!="1")
		echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";
		

	$schid=$_SESSION['sid'];
	$uid=$_SESSION['uid'];


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	

</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
		<input type="hidden" name="p" value="<?php echo $p;?>">

<div id="panelleft">
	<?php include('inc/lmenu.php');?>
</div>
<div id="content2"> 
<div id="masthead_title" style="border-right:none; border-top:none;" >
		<?php echo strtoupper($name);?>
</div>
<div style="font-size:11px; font-weight:bold; color:#333333; border-bottom:2px solid #666;"></div>


<div id="mypanel">
<div id="mymenu" align="center">
	<a href="outing-app.php" class="fbmedium" target="_blank" id="mymenuitem"><img src="../img/new.png"><br>Outing Application</a>
	<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>
</div>

<div id="story">



<div id="mytitle2">Rekod Keluar / Balik Bermalam &nbsp;<?php if($f==1) echo "<font color=\"#0066FF\">&lt;successfully&gt;</font>";?></div>
    <table width="100%" cellspacing="0">
            <tr>
              <td id="mytabletitle" width="1%"><?php echo $lg_no;?></td>
              <td id="mytabletitle" width="8%" align="center">
              	<a href="#" onClick="formsort('date','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_date;?></a></td>
			  <td id="mytabletitle" width="20%" align="center"><?php echo $lg_reason;?></td>
              <td id="mytabletitle" width="9%" align="center"><?php echo $lg_date;?> <?php echo $lg_start;?></td>
			  <td id="mytabletitle" width="9%" align="center"><?php echo $lg_date;?> <?php echo $lg_end;?></td>
              <td id="mytabletitle" width="5%" align="center"><?php echo $lg_total;?> <?php echo $lg_day;?></td>
              <td id="mytabletitle" width="10%" align="center"><?php echo $lg_verify;?></td>
              <td id="mytabletitle" width="10%" align="center"><?php echo $lg_approval;?></td>
            </tr>
            
            <?php	
			$q=0;
		$sql="select * from outing where uid='$uid' $sqlsort";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$id=$row['id'];
			$uid=$row['uid'];
			$date=$row['date'];
			$sdate=$row['sdate']; 
			$edate=$row['edate'];
			$sum=$row['days'];
			$typecuti=$row['type'];
			$rson=$row['rson'];
			$cttn=$row['cttn'];
			$sta=$row['status'];
			$vsta=$row['verify'];
			$confirm=$row['confirm'];		
			$q++;
			if($sta==1)
				$bg="$bglgreen";
			elseif($sta==2)
				$bg="$bglred";
			else{
				if($vsta==1)
					$bg="$bglyellow";
				elseif($vsta==2)
					$bg="$bglred";
				else
					$bg="";
				
			}
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
              <td id="myborder"><?php echo "$q";?></td>
              <td id="myborder" align="center"><?php echo "$date";?></td>
<?php
	if(($sta==0)&&($vsta==0)){
?>
             <td id="myborder">
			  	<a href="outing-app.php?id=<?php echo $id;?>" <?php if(!$confirm){?>style="text-decoration:line-through "<?php } ?> title="Edit" class="fbmedium" target="_blank"">
					<img src="../img/edit12.png"><?php echo "$rson"; if($f1!="") echo "&nbsp;"?>
				</a>
			</td>
<?php }else{ ?>
			  <td id="myborder"><?php echo "$rson"; if($f1!=""){?><img src="../img/pin.png"><?php }?> </td>
<?php } ?>

              <td id="myborder" align="center"><?php list($yyyy,$mm,$dd)=explode("-",$sdate); echo "$dd/$mm/$yyyy";?></td>
              <td id="myborder" align="center"><?php list($yyyy,$mm,$dd)=explode("-",$edate); echo "$dd/$mm/$yyyy";?></td>
              <td id="myborder" align="center"><?php echo "$sum";?></td>
			  
                
	<?php 
		$sql2="select * from sys_prm where grp='verify' and val=$vsta";
		$res2=mysql_query($sql2)or die("$sql query failed:".mysql_error());
        $row2=mysql_fetch_assoc($res2);
        $x=$row2['prm'];

		echo "<td id=myborder align=\"center\">$x</td>";
	
		$sql2="select * from sys_prm where grp='approval' and val=$sta";
		$res2=mysql_query($sql2)or die("$sql query failed:".mysql_error());
        $row2=mysql_fetch_assoc($res2);
        $a=$row2['prm'];
		echo "<td  id=myborder align=\"center\">$a</td>";
	?>
	
<tr>
<?php } ?>

</table>


</div></div>
</form>


</body>
</html>
