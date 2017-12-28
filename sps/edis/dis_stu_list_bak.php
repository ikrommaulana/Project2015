<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];


		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
		}
		
		$tahap=$_POST['tahap'];
		if($tahap!=""){
			$sqltahap="and level='$tahap'";
			$sqlclslevel="and ses_stu.cls_level='$tahap'";
		}

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- ID, IC, Name -")==0)
			$search="";
		if($search!=""){
			//$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}
		$namatahap="Tahap";
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			 $ssname=$row['sname'];
			$namatahap=$row['clevel'];
            mysql_free_result($res);					  
		}
		
		$stustatus=$_REQUEST['stustatus'];
		if($stustatus==""){
			$stustatus = 6;
		}
		if($stustatus!="%"){
			$sqlstustatus="and status=$stustatus";
			$sql="select * from type where grp='stusta' and val='$stustatus'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$studentstatus=$row['prm'];
		}
		else
			$studentstatus="Semua Pelajar";

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
		$sqlsort="order by $sort $order, name asc";


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
<!-- apai remark
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.css" rel="stylesheet" type="text/css" media="all" />
-->
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="dis_stu_list">
	<input type="hidden" name="isprint" value="<?php echo $isprint;?>">
<div id="pagecell">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>
<div align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>

			  <select name="sid" id="sid" onChange="document.myform.clscode[0].value='';document.myform.tahap[0].value='';document.myform.submit();">
            <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_school -</option>";
			else
                echo "<option value=$sid>$ssname</option>";
				
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
			  
			   <select name="tahap" id="tahap" onChange="document.myform.clscode[0].value='';document.myform.submit();">
               <?php    
		if($tahap=="")
            	echo "<option value=\"\">- $lg_all $lg_level -</option>";
		else
			echo "<option value=$tahap>$namatahap $tahap</option>";
		$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$tahap' order by prm";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
        	$s=$row['prm'];
            echo "<option value=$s>$namatahap $s</option>";
        }
		if($tahap!="")
        	echo "<option value=\"\">- $lg_all $lg_level -</option>";
?>
			 </select>
			 
			 <select name="clscode" id="clscode" onChange="document.myform.submit();">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- $lg_all $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from cls where sch_id=$sid and code!='$clscode' $sqltahap order by level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['name'];
						$a=$row['code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
					if($clscode!="")
            			echo "<option value=\"\">- $lg_all $lg_class -</option>";

			?>
                </select>
			 
      			
				
				<input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- ID, IC, Name -"; else echo "$search";?>"> 
				
                <input type="submit" name="Submit" value="View"  >
				
</div>
</div><!-- end mypanel-->
<div id="story">

<div id="mytitle"> <?php echo strtoupper($lg_discipline_case);?> - <?php echo strtoupper($sname);?> </div>
<table width="100%" cellspacing="0">
  <tr >
			  <td id="mytabletitle" width="3%" align="center"><?php echo strtoupper($lg_no);?></td>
			  <td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?></a></td>
			  <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>
              <td id="mytabletitle" width="25%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			  <td id="mytabletitle" width="10%"><?php echo strtoupper($lg_class);?> <?php echo $year;?></td>
			  <td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_total_case);?></td>
			  <td id="mytabletitle" width="1%" align="center">&nbsp;</td>
			  <td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_dimerit_point);?></td>
			  <td id="mytabletitle" width="1%" align="center">&nbsp;</td>
			  <td id="mytabletitle" width="10%" align="center">MATA MERIT</td>
			  <td id="mytabletitle" width="10%" align="center">JUMLAH MATA</td>
			  <td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_status);?></td> 
            </tr>
	<?php
	if(($clscode=="")&&($tahap==""))
    	$sql="select count(*) from stu where sch_id=$sid $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch";
	else
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlclslevel $sqlclscode $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch and year='$year'";	
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];

	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	if(($clscode=="")&&($tahap==""))
		$sql="select * from stu where sch_id=$sid $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch $sqlsort limit $curr,$MAXLINE";
	else
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlclslevel $sqlclscode $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch and year='$year' $sqlsort limit $curr,$MAXLINE";

	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$ic=$row['ic'];
		$name=stripslashes(strtoupper($row['name']));
		$sex=$row['sex'];
		$bday=$row['bday'];
		$p1ic=$row['p1ic'];
		$p1hp=$row['p1hp'];
		$p1tel=$row['p1tel'];
		$status=$row['status'];
		
		
		$cname=$lg_none;
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$cname=$row2['cls_name'];
		}
		
		
		$sql="select * from type where grp='stusta' and val='$status'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$sta=$row2['prm'];
			
			$sql="select count(*) from dis where stu_uid='$uid'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$jumsalah=$row2[0];
			
			$sql="select sum(val) from dis where stu_uid='$uid'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$demerit=$row2[0];
		
		$totalpoint=100;
		$totalpoint=$totalpoint+$merit-$demerit; 
		
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>

	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td id="myborder" align=center><?php echo $q;?></td>
	   	<td id="myborder" align=center><?php echo $uid;?></td>
		<td id="myborder" align="center"><?php $s=$lg_sexmf[$sex]; echo $s;?></td>
 		<td id="myborder"><?php echo $name;?></td>
		<td id="myborder"><?php echo $cname;?></td>
		<td id="myborder" align=center><?php echo $jumsalah;?></td>
		<td id="myborder" align=center><a href="p.php?<?php echo "p=disreg&uid=$uid&sid=$sid";?>" target="_blank"><img src="../img/edit12.png"></a></td>
		<td id="myborder" align=center><a href="p.php?<?php echo "p=disreg&uid=$uid&sid=$sid";?>"><?php echo $demerit;?></a></td>
		<td id="myborder" align=center><a href="merit_reg.php?<?php echo "uid=$uid&sid=$sid";?>"  target="_blank" onClick="return GB_showCenter('Merit Point',this.href,480,1000)"><img src="../img/edit12.png"></a></td>
		<td id="myborder" align=center><a href="p.php?<?php echo "p=dis_stu_rec&search=$uid&schid=$sid";?>" title="Add Demerit Point"><?php echo $merit;?></a></td>
		<td id="myborder" align=center><a href="p.php?<?php echo "p=dis_stu_rec&search=$uid&schid=$sid";?>"><?php echo $totalpoint;?></a></td>
		<td id="myborder" align="center"><?php echo $sta;?></td>
	</tr>
  <?php }  ?>
     </table>          
	 
	<?php include("../inc/paging.php");?>
</div></div></div>

	<input name="curr" type="hidden">
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
</form> <!-- end myform -->


</body>
</html>
<!-- 
v2.7
22/11/2008	: update sesi listing
Author		: razali212@yahoo.com
 -->