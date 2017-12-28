<?php
$vmod="v5.2.0";
$vdate="110306";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];
$p=$_REQUEST['p'];

		$sid=$_POST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$clslvl=$_POST['clslvl'];
		$clscode=$_POST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslvl=$row['level'];
		}
		if(($clslvl=="")||($clslvl=="0"))
			$clslvl=0;

		$sqlclslevel="and ses_stu.cls_level='$clslvl'";
	
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name_matrik_ic -")==0)
			$search="";
		if($search!=""){
			$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}
		$tahap = $lg_level;
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['sname'];
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
<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="op">
	<input type="hidden" name="uid" value="<?php echo $xuid;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
</div>
<div id="right" align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>

			  <select name="sid" id="sid" onChange="document.myform.clslvl[0].value='';document.myform.clscode[0].value='';document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
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
			  <select name="clslvl" onChange="document.myform.clscode[0].value='';document.myform.submit();">
                <?php    
					if($clslvl=="0")
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
			  <select name="clscode" id="clscode" onChange="document.myform.submit();">
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
                <input type="button"  value="View" onClick="document.myform.submit();">
</div>

</div><!-- end mypanel-->
<div id="story">

<table width="100%" id="mytitle">
  <tr>
    <td width="50%" align="left"><?php echo strtoupper($lg_hafazan);?> - <?php echo strtoupper($sname);?></td>
    <td width="50%" align="center"><?php echo strtoupper($lg_juz_and_page_number);?></td>
  </tr>
</table>
<table width="100%"  cellspacing="0" cellpadding="2">
 <tr> 
	<td id="mytabletitle" width="2%" align=center><?php echo strtoupper($lg_no);?></td>
    <td id="mytabletitle" width="3%" align=center><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?></a></td>
	<td id="mytabletitle" width="2%" align=center><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_mf);?></a></td>
    <td id="mytabletitle" width="25%" align="left"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
	<td id="mytabletitle" width="4%" align=center><a href="#" onClick="formsort('cls_name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_class);?> <?php echo $year;?></a></td>
<?php for($i=1;$i<30;$i++){?>
	<td id="mytabletitle" width="2%" align=center><?php echo "$i";?><div style="font-size:73% "><?php printf("%d<br>%d",($i-1)*20+2,$i*20+1);?></div></td>
<?php } ?>
    <td id="mytabletitle" width="2%" align=center><?php $i=30; echo "$i";?><div style="font-size:73% ">582<br>604</div></td>
	<td id="mytabletitle" width="2%" align=center><a href="#" onClick="formsort('hafazan_totalms','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_total_ms);?></a></td>
	<td id="mytabletitle" width="2%" align=center><a href="#" onClick="formsort('hafazan_totaljk','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_total_juz);?></a></td>
 </tr>
	<?php	

	$sql="select stu.*,ses_stu.cls_name,ses_stu.cls_code from stu LEFT JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid and status=6 $sqlclscode $sqlclslevel $sqlsearch and year='$year' $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$sex=$row['sex'];
		$sex=$lg_sexmf[$sex];
		$name=stripslashes(strtoupper($row['name']));
		
		$cname=$row['cls_code'];
		$totalms=$row['hafazan_totalms'];
		$totaljk=$row['hafazan_totaljk'];

		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
           <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" >
              	<td id="myborder" align="center"><?php echo "$q";?></td>
              	<td id="myborder" align="center"><?php echo "$uid";?></td>
			  	<td id="myborder" align="center"><?php echo "$sex";?></td>
				<td id="myborder"><a href="../ehaf/hafazan_stu_reg.php?uid=<?php echo "$uid";?>&sid=<?php echo "$sid";?>" 
                	onClick="return GB_showPage('<?php echo addslashes("Rekod Hafalan : $name");?>',this.href)"><?php echo "$name";?></a></td>
				<td id="myborder" align="center"><?php echo "$cname";?></td>
<?php
 		//process other juzuk
		for($i=1;$i<=30;$i++){
			$sp=$i*20-20+2;
			$ep=$i*20+1;
			if($i==30){
				$sp=581;
				$ep=604;
			}
			$ulang=0;
			$sql="select * from hafazan_rec where sid=$sid and uid='$uid' and (ms>=$sp and ms<=$ep) order by id desc, ms desc limit 1";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$cc=$row2['ms'];
				$ulang=$row2['ulang'];
			}
			else
				$cc=0;
				
			$sql="select count(*) from hafazan_rec where sid=$sid and uid='$uid'";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$jumms=$row2[0];
			if($jumms>603)
				$jumms=603;// mainten at 603..kes ulang
			$jumjk=sprintf("%d",$jumms/20);
				
			if($cc==$ep)
				$bgc="bgcolor=#66FF66";
			elseif($cc==0)
				$bgc="";
			else
				$bgc="bgcolor=#FFFF00";
				
			if($ulang)
				$bgc="bgcolor=#CCCC66";
				
			if(($cc-1)%20==0)
				$cc="";
			if($cc==0)
				$cc="";
			echo "<td id=myborder width=\"1%\" align=center $bgc style=\"font-size:90%\">$cc</td>";
		}
		echo "<td id=myborder width=\"2%\" align=center style=\"font-size:100%\"><b>$jumms</b></td>";//$jumms - totalms
		echo "<td id=myborder width=\"2%\" align=center style=\"font-size:100%\"><b>$jumjk</b></td>";//$jumjk - totaljk
		echo "</tr>";
  }
?>
  	
     </table>      
	 
      

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