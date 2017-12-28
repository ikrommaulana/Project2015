<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$block=$_REQUEST['block'];
		if($block!=""){
			$sqlblock="and hos_room.block='$block'";
			$sql="select * from hos where block='$block' and (sid=$sid or sid=0) order by block desc";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $blockname=$row['name'];
		}
		
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
			
		$isyatim=$_REQUEST['isyatim'];
		if($isyatim!=""){
			$sqlisyatim="and isyatim=1";
			$x1="Anak Yatim;";
		}
			
		$isstaff=$_REQUEST['isstaff'];
		if($isstaff!=""){
			$sqlisstaff="and isstaff=1";
			$x2="Anak Staff;";
		}
			
		$iskawasan=$_REQUEST['iskawasan'];
		if($iskawasan!=""){
			$sqliskawasan="and iskawasan=1";
			$x3="Pelajar Setempat;";
		}
		$ishostel=$_REQUEST['ishostel'];
		if($ishostel!=""){
			$sqlishostel="and ishostel=1";
			$x4="Pelajar Asrama";
		}
		$isfakir=$_REQUEST['isfakir'];
		if($isfakir!=""){
			$sqlisfakir="and isfakir=1";
			$x5="Pelajar Miskin";
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
		$sqlsort="order by $sort $order, name asc";


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
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
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="hos_stu_list">
	<input type="hidden" name="isprint" value="<?php echo $isprint;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>
<div align="right">
<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>

			  <select name="sid" id="sid" onChange="document.myform.block[0].value='';document.myform.clscode[0].value='';document.myform.tahap[0].value='';document.myform.submit();">
            <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_school -</option>";
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
			}							  
?>
              </select>
			  
			   <select name="tahap" id="tahap" onChange="document.myform.clscode[0].value='';document.myform.block[0].value='';document.myform.submit();">
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
			 
			 <select name="clscode" id="clscode" onChange="document.myform.block[0].value='';document.myform.submit();">
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
			 
      			<select name="block" id="block" onChange="document.myform.submit();">
				<?php
					if($block=="")
						echo "<option value=\"\">- $lg_all $lg_block -</option>";
					else
						echo "<option value=\"$block\">$blockname - $block</option>";
					$sql="select distinct(block),name from hos where block!='$block' and (sid=$sid or sid=0) order by block desc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['name'];
						$v=$row['block'];
						echo "<option value=\"$v\">$s - $v</option>";
					}
					if($block!="")
            			echo "<option value=\"\">- $lg_all $lg_block -</option>";				  
				?>
          </select>
				
				<input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- ID, IC, Name -"; else echo "$search";?>"> 
				
                <input type="submit" name="Submit" value="View"  >
				
</div>
</div><!-- end mypanel-->
<div id="story">

<div id="mytitle"> <?php echo strtoupper($lg_hostel);?> - <?php echo strtoupper($sname);?></div>
<table width="100%" cellspacing="0">
  <tr >
			  <td id="mytabletitle" width="3%" align="center"><?php echo strtoupper($lg_no);?></td>
			  <td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?></a></td>
			  <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>
              <td id="mytabletitle" width="32%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			  <td id="mytabletitle" width="10%"><?php echo strtoupper($lg_class);?> <?php echo $year;?></td>
			  <td id="mytabletitle" width="7%" align="center"><?php echo strtoupper($lg_room_no);?></td>
			  <td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_status);?></td>
			  <td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_form);?></td> 
            </tr>
	<?php
	if($block!=""){
		if(($clscode=="")&&($tahap==""))
			$sql="select count(*) from stu INNER JOIN hos_room ON stu.uid=hos_room.uid where stu.sch_id=$sid and ishostel=1 $sqlblock $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch order by hos_room.level,hos_room.roomno,hos_room.stuno";
		else
			$sql="select count(*) from stu INNER JOIN (ses_stu,hos_room) ON stu.uid=ses_stu.stu_uid and stu.uid=hos_room.uid where stu.sch_id=$sid and ishostel=1 $sqlblock $sqlclslevel $sqlclscode $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch and year='$year' ";
	}else if(($clscode=="")&&($tahap==""))
    	$sql="select count(*) from stu where sch_id=$sid and ishostel=1 $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch";
	else
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid and ishostel=1 $sqlclslevel $sqlclscode $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch and year='$year'";	
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];

	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	if($block!=""){
		if(($clscode=="")&&($tahap==""))
			$sql="select stu.* from stu INNER JOIN hos_room ON stu.uid=hos_room.uid where stu.sch_id=$sid and ishostel=1 $sqlblock $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch order by hos_room.level,hos_room.roomno,hos_room.stuno limit $curr,$MAXLINE";
		else
			$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN (ses_stu,hos_room) ON stu.uid=ses_stu.stu_uid and stu.uid=hos_room.uid where stu.sch_id=$sid and ishostel=1 $sqlblock $sqlclslevel $sqlclscode $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch and year='$year' $sqlsort limit $curr,$MAXLINE";
	}else if(($clscode=="")&&($tahap==""))
		$sql="select * from stu where sch_id=$sid and ishostel=1 $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch $sqlsort limit $curr,$MAXLINE";
	else
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid and ishostel=1 $sqlclslevel $sqlclscode $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch and year='$year' $sqlsort limit $curr,$MAXLINE";

	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$ic=$row['ic'];
		$name=strtoupper($row['name']);
		$sex=$row['sex'];
		$sex=$lg_sexmf[$sex];
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
		$bilik=$lg_none;
		$sql="select * from hos_room where uid='$uid'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$b=$row2['block'];
			$l=$row2['level'];
			$r=$row2['roomno'];
			$s=$row2['stuno'];
			$bilik="$b-$l-$r-$s";
		}
			
		$sql="select * from type where grp='stusta' and val='$status'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$sta=$row2['prm'];
			

			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td id="myborder" align=center><?php echo $q;?></td>
	   	<td id="myborder" align=center><?php echo $uid;?></td>
		<td id="myborder" align=center><?php echo $sex;?></td>
 		<td id="myborder"><a href="hos_stu_reg.php?<?php echo "uid=$uid&sid=$sid";?>" title="<?php echo "$lg_register";?>" rel="gb_page_center[1000, 480]" target="_blank"><?php echo "$name";?></a></td>
		<td id="myborder"><?php echo $cname;?></td>
		<td id="myborder" align=center><?php echo $bilik;?></td>
		<td id="myborder" align=center><?php echo $sta;?></td>
		<td id="myborder" align="center">
			<a href="letter.php?uid=<?php echo "$uid&st=ASRAMA_AKUAN";?>" title="SURAT AKUAN" rel="gb_page_center[1000, 480]" target="_blank">AKUAN</a>
			/
			<a href="letter.php?uid=<?php echo "$uid&st=ASRAMA_DAFTAR";?>" title="SLIP PENDAFTARAN" rel="gb_page_center[1000, 480]" target="_blank">SLIP DAFTAR</a>
		</td>
		</tr>
<?php }  ?>
     </table>          
	 
	<?php include("../inc/paging.php");?>
</div></div>

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