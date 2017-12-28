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
		$clscode=$_REQUEST['clscode'];
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
		if($year==""){
				$y=date('Y');
				$sql="select * from type where grp='session' and prm like '%$y%'";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$year=$row['prm'];	
		}
		
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
            $sname=stripslashes($row['name']);
			$tahap=$row['clevel'];
            mysql_free_result($res);					  
		}
		/*$stano=$_POST['stano'];
		if($stano=="")
			$stano=1;
		$endno=$_POST['endno'];
		if($endno=="")
			$endno=10;
		$chgno=$_POST['chgno'];
		if($chgno=="") 
			$chgno=0;
		$stano=$stano+$chgno;
		$endno=$endno+$chgno;
		$isfirst=$_POST['isfirst'];
		if($isfirst){
			$stano=1;
			$endno=10;
		} 
		$islast=$_POST['islast'];
		if($islast){
			$stano=110;
			$endno=120;
		} 
		
		*/
		$stano=$_POST['stano'];
		if($stano==""){
			$stano=0;//$stano=78;//$stano=1;
			$sqllimit=" limit 0,10";
		}
		$isfirst=$_POST['isfirst'];
		if($isfirst){
			$sqllimit=" limit 0,10";
			$stano=0;
		} 
		//$endno=$_POST['endno'];
		//if($endno=="")
		//	$endno=114;
		$chgno=$_POST['chgno'];
		if($chgno==""){
			$chgno=0;
			$sqllimit=" limit 0,10";
		}else{
			$curno=$stano+$chgno;
			$sqllimit=" limit $curno,10";
			$stano=$curno;
		}
		
		//$sqlcountbil="select count(*) as bil from type where grp='surah_pilihan' and lvl='1' and sid='$sid' order by idx asc";
		$sqlcountbil="select count(*) as bil from type where grp='surahhafazan' and ((sid=0)||(sid='$sid'))&&((lvl=0)||(lvl='$clslevel')) $sqlsem order by idx";
		$rescountbil=mysql_query($sqlcountbil)or die("$sqlcountbil query failed:".mysql_error());
		$rowcountbil=mysql_fetch_assoc($rescountbil);
		$bil=$rowcountbil['bil'];
		$bilno=($bil/10);

		$bilno=(floor($bilno)*10);
		
		$islast=$_POST['islast'];
		if($islast){
			$sqllimit=" limit $bilno,10";
			$stano=$bilno;
		} 

		$semester=$_POST['semester'];
		if($semester!="") 
			$sqlsem=" and code='$semester'";
		
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	
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
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br><?php echo $lg_refresh;?></a>
        	<div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br><?php echo $lg_print;?></a>
        	<div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
<?php if(is_verify('ADMIN')){?>
        <a href="#" onClick="newwindow('../adm/prm.php?grp=surahhafazan',0)" id="mymenuitem"><img src="../img/tool.png"><br><?php echo $lg_setting;?></a>
        	<div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
<?php } ?>
	</div>

	<div id="right" align="right">
				<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>

				  
					
	</div>
	
	</div><!-- end mypanel-->
<div id="story">

<div id="mytabletitle">
	
	<select name="sid" id="sid" onChange="document.myform.clslevel[0].value='';document.myform.clscode[0].value='';document.myform.submit();">
					<?php	
				if($sid=="0")
					echo "<option value=\"0\">- ".$lg_school." -</option>";
				else
					echo "<option value=$sid>$sname</option>";
					
				if($_SESSION['sid']==0){
					$sql="select * from sch where id!='$sid' and id>0 order by name";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$s=stripslashes($row['name']);
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
				  
				  <select name="semester" onChange="document.myform.clscode[0].value='';document.myform.submit();">
					<?php    
						if($semester=="1"){
								echo "<option value=1>Semester 1</option>";
								echo "<option value=2>Semester 2</option>";
						}elseif($semester=="2"){
								echo "<option value=2>Semester 2</option>";
								echo "<option value=1>Semester 1</option>";
						}else{
								echo "<option value=\"\">- Semester -</option>";
								echo "<option value=1>Semester 1</option>";
								echo "<option value=2>Semester 2</option>";
						}
							
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

<div id="mytitlebg" >
	SURAH HAFALAN - 
<?php 
	echo strtoupper($sname);  
if($clscode!="") 
	echo strtoupper(" / $clsname"); 
elseif($clslevel!="") 
	echo strtoupper(" / $tahap $clslevel"); ?>
</div>
<div align="right" id="mytitlebg" >
<!--<input type="button" value="first" onClick="document.myform.isfirst.value='1'; document.myform.submit();" <?php if($stano<=10) echo "disabled";?>>
<input type="button" value="prev" onClick="document.myform.chgno.value='-10'; document.myform.submit();" <?php if($stano<=10) echo "disabled";?>>
<input type="button" value="next" onClick="document.myform.chgno.value='+10'; document.myform.submit();" <?php if($endno>110) echo "disabled";?>>
<input type="button" value="last" onClick="document.myform.islast.value='1'; document.myform.submit();" <?php if($endno>110) echo "disabled";?>>-->
<input type="button" value="first" onClick="document.myform.isfirst.value='1'; document.myform.submit();" <?php if($stano==0) echo "disabled";?>>
<input type="button" value="prev" onClick="document.myform.chgno.value='-10'; document.myform.submit();" <?php if($stano<10) echo "disabled";?>>
<input type="button" value="next" onClick="document.myform.chgno.value='+10'; document.myform.submit();" <?php if($stano==$bilno) echo "disabled";?>>
<input type="button" value="last" onClick="document.myform.islast.value='1'; document.myform.submit();" <?php if($stano==$bilno) echo "disabled";?>>
</div>
<table width="100%" cellspacing="0" cellpadding="0">
 <tr> 
	<td id="mytabletitle" width="1%" align=center><?php echo strtoupper($lg_no);?></td>
    <td id="mytabletitle" width="3%" align=center><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?></a></td>
	<td id="mytabletitle" width="1%" align=center><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_mf);?></a></td>
    <td id="mytabletitle" width="20%" align="left"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
<?php
	//$i=$stano-1;
	//echo $stano;
	$i=$stano;
	//$sql="select * from type where grp='surahhafazan' and idx<=$endno and ((sid=0)||(sid='$sid'))&&((lvl=0)||(lvl='$clslevel')) $sqlsem order by idx";
	$sql="select * from type where grp='surahhafazan' and ((sid=0)||(sid='$sid'))&&((lvl=0)||(lvl='$clslevel')) $sqlsem order by idx $sqllimit";
	//echo $sql;
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$surahname=stripslashes($row['prm']);
		$i++;
?>
	<td id="mytabletitle" width="5%" align=center style="font-size:8px"><?php echo "$i. $surahname";?></td>
<?php } ?>
	<td id="mytabletitle" width="5%" align=center style="font-weight:normal ">HAFALAN<br><?php echo $lg_current;?><br>(<?php echo $lg_latest;?>)</td>
    <!--
	<td id="mytabletitle" width="5%" align=center style="font-weight:normal ">HAFALAN<br><?php echo $lg_repeat;?><br>(<?php echo $lg_latest;?>)</td>
    -->
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
           <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" onClick="document.myform.uid.value='<?php echo "$uid";?>';newwindow('../ehaf/hafazan_stu_reg.php?uid=<?php echo "$uid";?>&sid=<?php echo "$sid";?>&isprint=1',0)">
              	<td id="myborder" align="center"><?php echo "$q";?></td>
              	<td id="myborder" align="center"><?php echo "$uid";?></td>
			  	<td id="myborder" align="center"><?php echo "$sex";?></td>
				<td id="myborder">
					<a href="../ehaf/surah_stu_rep.php?uid=<?php echo "$uid&sid=$sid&year=$year";?>" class="fbbig"><?php echo "$name";?></a>
				</td>
				<?php 
				$i=$stano-1;
				
				//$sql="select * from type where grp='surahhafazan' and idx<=$endno and ((sid=0)||(sid='$sid'))&&((lvl=0)||(lvl='$clslevel')) $sqlsem order by idx";
				$sql="select * from type where grp='surahhafazan' and ((sid=0)||(sid='$sid'))&&((lvl=0)||(lvl='$clslevel')) $sqlsem order by idx $sqllimit";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				while($row2=mysql_fetch_assoc($res2)){
						$surahname=stripslashes($row2['prm']);
						$surah=addslashes($row2['prm']);
						$i++;
						$j++;
						
						$sql="select * from surah_stu_status where uid='$uid' and surahname='$surah'";
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
						<a href="../ehaf/surah_stu_reg.php?uid=<?php echo "$uid&sid=$sid&surahname=$surahname&year=$year";?>" title="Surah <?php echo $surahname;?>" class="fbbig"><div style="width:100%">&nbsp;<?php echo "$reading";?></div></a>
					</td>
				<?php } ?>
				
				<?php
						$sql="select * from surah_stu_status where uid='$uid' and reading=0 order by id desc ";
						$res4=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row4=mysql_fetch_assoc($res4);
						$status=$row4['status'];
						$reading=$row4['reading'];
						$surahname=stripslashes($row4['surahname']);
						$surahno=$row4['surahno'];
						if($reading=="")
							$bgtd="";
						elseif($reading=="0")
							$bgtd="bgcolor=\"#00FF66\"";
						else
							$bgtd="bgcolor=\"#FFCC99\"";
						if($status=="0")
							$bgtd="bgcolor=\"#FFFF00\"";
						
				?>

  			<td <?php echo "$bgtd";?> id="myborder" bgcolor="" id="myborder" align="center"  onMouseOver="myin(this,1)" onMouseOut="myout(this,1)">
				<a href="../ehaf/surah_stu_reg.php?uid=<?php echo "$uid&sid=$sid&surahname=$surahname";?>" title="Surah <?php echo addslashes($surahname);?>" class="fbbig"><div style="width:100%">&nbsp;<?php echo "$surahname";?></div></a>
			</td>
			<?php
						$sql="select * from surah_stu_status where uid='$uid' and reading>0 order by id desc ";
						$res4=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row4=mysql_fetch_assoc($res4);
						$status=$row4['status'];
						$reading=$row4['reading'];
						$surahname=stripslashes($row4['surahname']);
						$surahno=$row4['surahno'];
						if($reading=="")
							$bgtd="";
						elseif($reading=="0")
							$bgtd="bgcolor=\"#00FF66\"";
						else
							$bgtd="bgcolor=\"#FFCC99\"";
						if($status=="0")
							$bgtd="bgcolor=\"#FFFF00\"";
			?>
            <!--
			<td <?php echo "$bgtd";?> id="myborder"id="myborder" align="center"  onMouseOver="myin(this,1)" onMouseOut="myout(this,1)">
				<a href="../ehaf/surah_stu_reg.php?uid=<?php echo "$uid&sid=$sid&surahname=$surahname";?>" class="fbbig" title="Surah <?php echo addslashes($surahname);?>" >
					<div style="width:100%">&nbsp;<?php echo "$surahname";?></div>
				</a>
			</td>
            -->
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