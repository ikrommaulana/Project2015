<?php
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];
		$p=$_REQUEST['p'];
		$sid=$_POST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		
		$clslvl=0;
		$clslvl=$_POST['clslvl'];
		
		
		$clscode=$_POST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslvl=$row['level'];
		}
		
		$sqlclslevel="and ses_stu.cls_level='$clslvl'";
		
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
			
		$cyear=date('Y');
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name_matrik_ic -")==0)
			$search="";
		if($search!=""){
			$search=addslashes($search);
			$sqlsearch = "and (stu.uid='$search' or ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$simg=$row['img'];
			$tahap=$row['clevel'];
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
		$order="asc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by name $order";
	else
		$sqlsort="order by $sort $order, id desc";


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<?php include("$MYOBJ/calender/calender.htm")?>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="op">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="uid" value="<?php echo $xuid;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>
<div id="right" align="right">
<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>
			    <select name="year" id="year">
                  <?php
            echo "<option value=$year>$lg_session $year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">$lg_session $s</option>";
            }
            mysql_free_result($res);					  
?>
                </select>
			    <select name="sid" id="sid" onChange="document.myform.uid.value='';document.myform.clscode[0].value='';document.myform.search.value='';document.myform.submit();">
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
			    <select name="clslvl" onChange="document.myform.clscode[0].value='';document.myform.submit();">
                  <?php    
					if(($clslvl=="0")||($clslvl==""))
							echo "<option value=\"0\">- $lg_select $lg_level -</option>";
					else
						echo "<option value=$clslvl>$tahap $clslvl</option>";
						$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslvl' order by prm";
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									echo "<option value=$s>$tahap $s</option>";
						}
				?>
                </select>
			    <select name="clscode" id="clscode" onChange="document.myform.uid.value='';document.myform.search.value='';document.myform.submit();">
                  <?php	
      				if($clscode==""){
						echo "<option value=\"\">- $lg_all $lg_class -</option>";
						$sql="select * from cls where sch_id=$sid order by level";
					}
					else{
						echo "<option value=\"$clscode\">$clsname</option>";
						echo "<option value=\"\">- $lg_all $lg_class -</option>";
						mysql_free_result($res);
						$sql="select * from cls where sch_id=$sid and code!='$clscode' order by level";
					}
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['name'];
						$a=$row['code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
            		mysql_free_result($res);	
			?>
                </select>
				
				<input name="search" type="text" id="search" size="30" onMouseDown="document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "- $lg_name_matrik_ic -"; else echo "$search";?>">
				
                <input type="button"  value="View" onClick="document.myform.uid.value='';document.myform.submit();">

</div>

</div><!-- end mypanel-->
<div id="story">
<div id="mytitlebg">
<?php echo strtoupper($lg_hafazan );?> : <?php echo strtoupper($lg_monthly_report);?>  - <?php echo strtoupper($sname);?>
&nbsp;- <?php if($clscode!="") echo strtoupper("$clsname / $year"); else echo strtoupper("$tahap $clslvl / $year");?>
</div>

<table width="100%"  cellspacing="0" cellpadding="2">
 <tr> 
	<td id="mytabletitle" width="2%" align=center rowspan="2"><?php echo strtoupper($lg_no);?> </td>
    <td id="mytabletitle" width="3%" align=center rowspan="2"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?> </a></td>
	<td id="mytabletitle" width="2%" align=center rowspan="2"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_mf);?> </a></td>
    <td id="mytabletitle" width="20%" rowspan="2"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort">&nbsp;<?php echo strtoupper($lg_name);?> </a></td>
	<td id="mytabletitle" width="10%" rowspan="2"><a href="#" onClick="formsort('ses_stu.clsname','<?php echo "$nextdirection";?>')" title="Sort">&nbsp;<?php echo strtoupper($lg_class);?>  <?php echo $year;?></a></td>
<?php 
	$sql="select * from month where no<11 order by no";
    $res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
?>
	<td id="mytabletitle" width="2%" align=center colspan="2"><?php echo strtoupper($row['sname']);?></td>
<?php } ?>
	<td id="mytabletitle" width="2%" align=center rowspan="2"><a href="#" onClick="formsort('totaljk','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_total_juz);?></a></td>
 </tr>
 <tr>
<?php 
	$sql="select * from month where no<11 order by no";
    $res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
?>
	<td id="mytabletitle"  width="2%" align="center" style="font-weight:normal"><?php echo $lg_pages;?></td>
	<td id="mytabletitle"  width="2%" align="center" style="font-weight:normal"><?php echo $lg_grade;?></td>
	<!-- 
	<td id="mytabletitle"  width="2%" align="center">M</td>
	 -->
<?php } ?>
 </tr>
	<?php	

	$sql="select count(*) from stu LEFT JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid  and status=6 $sqlclscode $sqlclslevel $sqlsearch and year='$cyear'";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];

	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	//$sql="select stu.*,ses_stu.cls_name,ses_stu.cls_code from stu LEFT JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid and status=6 $sqlclscode $sqlclslevel $sqlsearch and year='$cyear' $sqlsort";
	$sql="select stu.*,ses_stu.cls_name,ses_stu.cls_code,hafazan_sem.totaljk from stu LEFT JOIN (ses_stu,hafazan_sem) ON (stu.uid=ses_stu.stu_uid and stu.uid=hafazan_sem.uid) where stu.sch_id=$sid and status=6 $sqlclscode $sqlclslevel $sqlsearch and ses_stu.year='$cyear' and hafazan_sem.year='$cyear' $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$sex=$row['sex'];
		$sex=$lg_sexmf[$sex];
		$name=strtoupper($row['name']);
		$name=stripslashes($name);
		$name=substr($name,0,30);
		$cname=$row['cls_name'];
		$ccode=$row['cls_code'];
		

		if(($q++%2)==0)
			$bg="bgcolor=#FAFAFA";
		else
			$bg="bgcolor=#FFFFFF";
		
		echo "<tr $bg>";
		echo "<td id=myborder align=center>$q</td>";
	   	echo "<td id=myborder align=center>$uid</td>";
		echo "<td id=myborder align=\"center\">$sex</td>";
		echo "<td id=myborder><a href=\"#\" onClick=\"newwindow('../ehaf/hafazan_stu_reg.php?uid=$uid&sid=$sid&isprint=1',0)\">$name</a></td>";
		echo "<td id=myborder>&nbsp;$cname</td>";
		$sql="select * from month  where no<11 order by no";
		$res2=mysql_query($sql)or die("$sql-query failed:".mysql_error());
		$jj="";
		while($row2=mysql_fetch_assoc($res2)){
			$month=$row2['no'];
			$sql="select * from hafazan_rep where uid='$uid' and year=$cyear and month=$month";
			$res3=mysql_query($sql)or die("$sql - query failed:".mysql_error());
			$ms="";$gred=0;$mar=0;
			if($row3=mysql_fetch_assoc($res3)){
				$ms=$row3['totalms'];
				$gred=$row3['mg'];
				$mar=$row3['mp'];
				$jj=$row3['totaljk'];
			}
			
?>
	<td id=myborder align=center><?php if($ms=="") echo "&nbsp;"; else echo "$ms";?></td>
	<td id=myborder align=center><?php if($gred=="") echo "&nbsp;"; else echo "$gred";?></td>
	<!-- <td id=myborder align=center><?php if($mar=="") echo "&nbsp;"; else echo "$mar";?></td> -->
<?php } 
	echo "<td id=myborder align=center>$jj</td>";
	echo "</tr>";
}
?>
  	
</table>       
<?php //include("inc/paging.php");?>
</div></div>

	<input name="curr" type="hidden">
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
</form> <!-- end myform -->

</body>
</html>
<!-- 
V.1

Author: razali212@yahoo.com
 -->