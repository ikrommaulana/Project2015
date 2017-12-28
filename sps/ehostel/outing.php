<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");

		$p=$_REQUEST['p'];
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		$sqldate="and sdate like '$year%'";
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- ID, IC, Name -")==0)
			$search="";
		if($search!=""){
			$sqlsearch = "and (usr.uid='$search' or ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}

	if($sid!=0){
		$sql="select * from sch where id='$sid'";
     	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['sname'];
		//$sqlsch="and stu.sch_id=$sid";  
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
	elseif($MAXLINE=="All")
		$sqlmaxline="";
	else
		$sqlmaxline="limit $curr,$MAXLINE";
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
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>


<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/obj/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/obj/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/obj/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/obj/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/obj/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="op" >
<input type="hidden" name="id">
<input type="hidden" name="p" value="<?php echo $p;?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="document.myform.id.value='';document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>	
    </div>
	<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
	</div> <!-- end mypanel -->
	<div id="mytabletitle" class="printhidden" align="right" >
				<select name="year" id="year" onchange="document.myform.submit();">
				<?php
					echo "<option value=$year>SESI $year</option>";
					$sql="select * from type where grp='session' and prm!='$year' order by val desc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						$v=$row['val'];
						echo "<option value=\"$s\">SESI $s</option>";
					}
					mysql_free_result($res);					  
				?>
          </select>
		  
			  <select name="sid" id="sid" onChange="document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- Semua $lg_sekolah -</option>";
			else
                echo "<option value=$sid>$sname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
			}
			if(($_SESSION['sid']==0)&&($sid>0))
				echo "<option value=\"0\">- Semua $lg_sekolah -</option>";  	  
			
?>
              </select>
			 <input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- ID, IC, Name -"; else echo "$search";?>"> 
              <input type="submit" name="Submit" value="View"  >
	 </div>
<div id="story" style="border:none ">
<div id="mytitle2">Keluar Bermalam : <?php echo $sname;?> <?php echo $f;?></div>

<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td class="mytableheader" style="border-right:none;border-top:none;" width="1%" align="center"><?php echo $lg_no;?></td>
      <td class="mytableheader" style="border-right:none;border-top:none;" width="5%" align="center"><?php echo $lg_matric;?></td>
      <td class="mytableheader" style="border-right:none;border-top:none;" width="7%" align="center"><a href="#" onClick="formsort('date','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_date;?></a></td>
      <td class="mytableheader" style="border-right:none;border-top:none;" width="20%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_name;?></a></td>
	  <td class="mytableheader" style="border-right:none;border-top:none;" width="20%" ><?php echo $lg_reason;?></td>
      <td class="mytableheader" style="border-right:none;border-top:none;" width="7%" align="center"><?php echo $lg_start_date;?></td>
      <td class="mytableheader" style="border-right:none;border-top:none;" width="7%" align="center"><?php echo $lg_end_date;?></td>
      <td class="mytableheader" style="border-right:none;border-top:none;" width="3%" align="center"><?php echo $lg_total;?> <?php echo $lg_day;?></td>
      <td class="mytableheader" style="border-right:none;border-top:none;" width="5%" align="center"><a href="#" onClick="formsort('outing.verify','<?php echo "$nextdirection";?>')" title="Uncomplete Task"><?php echo $lg_verify;?></a></td>
      <td class="mytableheader" style="border-top:none;" width="5%" align="center"><a href="#" onClick="formsort('outing.status','<?php echo "$nextdirection";?>')" title="Uncomplete Task"><?php echo $lg_approval;?></a></td>
    </tr>
	<?php
	
		$sql=" SELECT count(*) FROM outing where id>0 $sqlsch $sqldate $sqlsearch ";
        $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
        $row=mysql_fetch_row($res);
        $total=$row[0];
		if(($curr+$MAXLINE)<=$total)
			 $last=$curr+$MAXLINE;
		else
			$last=$total;
	
		$q=$curr;$arr=0;
		$sql="SELECT * from outing where id>0 $sqlsch $sqldate $sqlsearch $sqlsort $sqlmaxline";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error()); 
		while($row=mysql_fetch_assoc($res)){
			$idx=$row['id'];
			$uidx=$row['uid'];
			$date=$row['date'];
			$name=stripslashes($row['name']);
			if(strlen($name)>35)
				$name=substr($name,0,35);
			$sex=$row['sex'];
			if($sex=="Lelaki"){
			  $sex="L";
			}else{
			  $sex="P";
			}
	
			$sdate=$row['sdate'];
			$edate=$row['edate'];
			$sum=$row['days'];
			$rson=$row['rson'];
			$cttn=$row['cttn'];
			$addr=$row['addr'];
			$sta=$row['status'];
			$ver=$row['verify'];
			$confirm=$row['confirm'];
			
			$y=date('Y');
			
			$q++;
			if($confirm==0)
				$bg="#FAFAFA";//cancel
			elseif($confirm==1)
				$bg=$bglyellow;//confirm
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
    	<td id="myborder" align=center><?php echo $q;?></td>
		<td id="myborder" align=center><?php echo $uidx;?></td>
	   	<td id="myborder" align=center><?php echo $date;?></td>
		<td id="myborder">
			<a href="../ecuti/leave_verify.php?id=<?php echo $idx;?>" title="Proses Cuti" rel="gb_page_center[800, 480]" target="_blank" <?php if(!$confirm){?>style="text-decoration:line-through "<?php } ?>>
			<?php echo $name;?></a>
		</td>
		<td id="myborder"><?php echo $rson;?></td>
		<td id="myborder" align=center><?php echo $sdate;?></td>
		<td id="myborder" align=center><?php echo $edate;?></td>
		<td id="myborder" align=center><?php echo $sum;?></td>
<?php
		$sql2="select * from sys_prm where grp='approval' and val=$sta";
		$res2=mysql_query($sql2)or die("query failed:".mysql_error());
        $row2=mysql_fetch_assoc($res2);
        $approval=$row2['prm'];
		
		$sql3="select * from sys_prm where grp='verify' and val=$ver";
		$res3=mysql_query($sql3)or die("$sql3 query failed:".mysql_error());
        $row3=mysql_fetch_assoc($res3);
        $verify=$row3['prm'];
		
		if($ver==1)
			$c="bgcolor=\"$bglgreen\"";
		elseif($ver==2)
			$c="bgcolor=\"$bglred\"";
		else
			$c="";
		
		if($sta==1)
			$b="bgcolor=\"$bglgreen\"";
		elseif($sta==2)
			$b="bgcolor=\"$bglred\"";
		else
			$b="";
?>
			<td id="myborder" <?php echo $c; ?> align="center">
				<?php if($confirm){?>
					<a href="../ecuti/leave_verify.php?id=<?php echo $idx;?>" title="Proses Cuti" rel="gb_page_center[800, 480]" target="_blank"><?php echo $verify; ?></a>
				<?php }else{ ?>
					<a href="#" style="text-decoration:line-through "><?php echo $verify; ?></a>
				<?php } ?>
			</td>
			<td id="myborder" <?php echo $b; ?> align="center">
				<?php if($confirm){?>
					<a href="../ecuti/leave_verify.php?id=<?php echo $idx;?>" title="Proses Cuti" rel="gb_page_center[800, 480]" target="_blank"><?php echo $approval; ?></a>
				<?php }else{ ?>
					<a href="#" style="text-decoration:line-through "><?php echo $approval; ?></a>
				<?php } ?>
			</td>
		</tr>
<?php } ?>
</table>          

	<?php include("../inc/paging.php");?>
						
</div></div>
</form>
</body>
</html>