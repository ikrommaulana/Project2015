<?php
//500 - 01/08/2010 - language set
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

		$clslevel=$_POST['clslevel'];
		$clscode=$_POST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
		}
		/**
		if(($clslevel=="")||($clslevel=="0"))
			$clslevel=0;
		$sqlclslevel="and ses_stu.cls_level='$clslevel'";
		**/
		if($clslevel!="")
				$sqlclslevel="and ses_stu.cls_level='$clslevel'";
	
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		
		$search=$_POST['search'];
		if(strcasecmp($search,"- ID, IC, Name -")==0)
			$search="";
		if($search!=""){
			//$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}
		$tahap = $lg_level;
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$tahap=$row['clevel'];
            mysql_free_result($res);					  
		}
		$stano=$_POST['stano'];
		if($stano=="")
			$stano=78;//$stano=1;
		$endno=$_POST['endno'];
		if($endno=="")
			$endno=88;
		$chgno=$_POST['chgno'];
		if($chgno=="") 
			$chgno=0;
		$stano=$stano+$chgno;
		$endno=$endno+$chgno;
		$isfirst=$_POST['isfirst'];
		if($isfirst){
			$stano=78;
			$endno=88;
		} 
		$islast=$_POST['islast'];
		if($islast){
			$stano=110;
			$endno=120;
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
		$sqlsort="order by $sort $order, name asc";
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
<!-- apai remark
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.css" rel="stylesheet" type="text/css" media="all" />
-->
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
function myin(id,sta){
		/**id.style.backgroundColor='#66FF66';**/
		id.style.cursor='pointer';
		id.style.border='1px solid #333333';
		
}
function myout(id,sta){
		/**id.style.backgroundColor='';**/
		id.style.cursor='default';
		id.style.border='1px solid #F1F1F1';
}
</script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="op">
	<input type="hidden" name="stano" value="<?php echo $stano;?>">
	<input type="hidden" name="endno" value="<?php echo $endno;?>">
	<input type="hidden" name="chgno">
	<input type="hidden" name="isfirst">
	<input type="hidden" name="islast">
<div id="content">
	<div id="mypanel">
		<div id="mymenu" align="center">
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		</div>
	<div id="right" align="right">
				<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>

				  <select name="sid" id="sid" onChange="document.myform.clslevel[0].value='';document.myform.clscode[0].value='';document.myform.submit();">
					<?php	
				if($sid=="0")
					echo "<option value=\"0\">- ".$lg_school." -</option>";
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
						<select name="year" id="year" onChange="document.myform.submit();">
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
				  <select name="clslevel" onChange="document.myform.clscode[0].value='';document.myform.submit();">
					<?php    
						if($clslevel=="")
								echo "<option value=\"\">- ".$lg_level." -</option>";
						else
							echo "<option value=$clslevel>$tahap $clslevel</option>";
							$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
										$s=$row['prm'];
										echo "<option value=$s>$tahap $s</option>";
							}
						if($clslevel!="")
								echo "<option value=\"\">- ".$lg_all." -</option>";
					?>
				  </select>
				  <select name="clscode" id="clscode" onChange="document.myform.submit();">
					  <?php	
						if($clscode==""){
							echo "<option value=\"\">- ".$lg_class." -</option>";
							$sql="select * from cls where sch_id=$sid order by level";
						}
						else{
							echo "<option value=\"$clscode\">$clsname</option>";
							$sql="select * from cls where sch_id=$sid and code!='$clscode' order by level";
						}
						$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
							$b=$row['name'];
							$a=$row['code'];
							echo "<option value=\"$a\">$b</option>";
						}
						if($clscode!="")
							echo "<option value=\"\">- ".$lg_all." -</option>";
	
				?>
					</select>
					
					<input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- ID, IC, Name -"; else echo "$search";?>"> 		
					<input type="button"  value="View" onClick="document.myform.submit();">
					
	</div>
	
	</div><!-- end mypanel-->
<div id="story">


<div id="mytitle2">
	SURAH AL BAQARAH - 
<?php 
	echo strtoupper($sname);  
if($clscode!="") 
	echo strtoupper(" / $clsname"); 
elseif($clslevel!="") 
	echo strtoupper(" / $tahap $clslevel"); ?>
</div>

<table width="100%"  cellspacing="0" cellpadding="2">
 <tr> 
	<td id="mytabletitle" width="1%" align=center><?php echo strtoupper($lg_no);?></td>
    <td id="mytabletitle" width="3%" align=center>
    <a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?></a></td>
	<td id="mytabletitle" width="1%" align=center>
    <a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_mf);?></a></td>
    <td id="mytabletitle" width="20%" align="left">
    <a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
    <td id="mytabletitle" width="5%" align=center style="font-size:8px"><?php echo "2. Al Baqarah";?></td>
 </tr>
	<?php	
	$sql="select count(*) from stu LEFT JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid and status=6 $sqlclscode $sqlclslevel $sqlsearch and year='$year'";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
	$q=$curr;
	
	$sql="select stu.*,ses_stu.cls_name from stu LEFT JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid and status=6 $sqlclscode $sqlclslevel $sqlsearch and year='$year' $sqlsort $sqlmaxline";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$sex=$row['sex'];
		$sex=$lg_sexmf[$sex];
		$name=strtoupper(stripslashes($row['name']));
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
           <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" 
           			onClick="document.myform.uid.value='<?php echo "$uid";?>';
                    newwindow('../ehaf/hafazan_stu_reg.php?uid=<?php echo "$uid";?>&sid=<?php echo "$sid";?>&isprint=1',0)">
              	<td id="myborder" align="center"><?php echo "$q";?></td>
              	<td id="myborder" align="center"><?php echo "$uid";?></td>
			  	<td id="myborder" align="center"><?php echo "$sex";?></td>
				<td id="myborder">
					<a href="../ehaf/amma_sturep.php?uid=<?php echo "$uid&sid=$sid";?>" onClick="return GB_showPage('<?php echo addslashes($name);?>',this.href)" >
						<?php echo "$name";?></a>
				</td>
                
                <?php
 					$sql="select * from surah_stu_status where uid='$uid' and surahno=2";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$status=$row3['status'];
						$reading=$row3['reading'];
						if($reading=="")
							$bgtd="";
						elseif($reading=="0")
							$bgtd="bgcolor=\"#00FF66\"";
						else
							$bgtd="bgcolor=\"#FFCC99\"";
						if($status=="0")
							$bgtd="bgcolor=\"#FFFF00\"";
				?>
					<td <?php echo "$bgtd";?>  id="myborder" align="center" onMouseOver="myin(this,1)" onMouseOut="myout(this,1)">
						<a href="../ehaf/surah_stu_reg.php?uid=<?php echo "$uid&sid=$sid&surah=2";?>" 
                        	onClick="return GB_showPage('Surah Al-Baqarah',this.href)" >
                            <div style="width:100%">&nbsp;<?php echo "$reading";?></div>
                        </a>
					</td>                      
      		</tr>
<?php  }  ?>
     </table>       

</div>
<?php include("../inc/paging.php");?>
</div>

<!-- 
	<input name="curr" type="hidden">
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
 -->
</form> <!-- end myform -->

</body>
</html>
<!-- 
V.1

Author: razali212@yahoo.com
 -->