<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ROOT|ADMIN');
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];

if(is_verify('ROOT'))
	$sqlhideroot="where id>0";
else
	$sqlhideroot="where uid!='root'";
	
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
		if($clscode=="0"){
			$clsname="- Tiada Kelas -";
		}
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- ID, KP, Nama -")==0)
			$search="";
		if($search!=""){
			//$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}

		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
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
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="sys_log">
<div id="content">
<div id="mypanel">

<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="showhide('detail_info','main_info');" id="mymenuitem"><img src="../img/zoom.png"><br>Detail</a>
</div>
<div id="panel" align="right">
		  
			  <select name="sid" id="sid" onchange="document.myform.clscode[0].value='';document.myform.submit();">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- Semua Sekolah -</option>";
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
			
				<input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- ID, KP, Nama -"; else echo "$search";?>"> 
				
                <input type="submit" name="Submit" value="View"  >
</div> <!-- end panel -->

</div><!-- end mypanel-->
<div id="story">


<div id="mytitle">
<?php echo strtoupper($sname);?></div>

<div id="main_info">
	<table width="100%">
  			<tr >
			  <td id="mytabletitle" width="3%" align="center">NO</td>
			  <td id="mytabletitle" width="3%" align="center">DATE</td>
			  <td id="mytabletitle" width="3%" align="center">TIME</td>
			  <td id="mytabletitle" width="5%" align="center">IP&nbsp;ADDRESS</td>
			  <td id="mytabletitle" width="3%" align="center">UID</td>
			  <td id="mytabletitle" width="3%" align="center">SID</td>
			  <td id="mytabletitle" width="5%" align="center">LEVEL</td>
			  <td id="mytabletitle" width="3%" align="center">APP</td>
			  <td id="mytabletitle" width="10%" align="center">ACT</td>
			  <td id="mytabletitle" width="60%" align="center">DESCRIPTION</td>
            </tr>
	<?php	

	$sql="select count(*) from sys_log $sqlhideroot $sqlsearch";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	$sql="select * from sys_log $sqlhideroot $sqlsearch $sqlsort limit $curr,$MAXLINE";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$xuid=$row['uid'];
		$xsid=$row['sid'];
		$xlvl=$row['lvl'];
		$xapp=$row['app'];
		$xact=$row['act'];
		$xip=$row['ip'];
		$xdt=$row['dt'];
		$xtm=$row['tm'];
		$des=$row['des'];
		$det=$row['det'];
		$des=stripslashes($des);
		$det=stripslashes($det);
		
			
		if(($q++%2)==0)
			echo "<tr bgcolor=#fafafa>";
		else
			echo "<tr bgcolor=#FFFFFF>";
		
		echo "<td align=center>$q</td>";
	   	echo "<td align=center>$xdt</td>";
 		echo "<td align=center>$xtm</td>";
		echo "<td>$xip</td>";
		echo "<td>$xuid</td>";
		echo "<td align=center>$xsid</td>";
		echo "<td>$xlvl</td>";
		echo "<td>$xapp</td>";
		echo "<td>$xact</td>";
		echo "<td>$des</td>";
		echo "</tr>";
  }
  mysql_free_result($res);
  ?>
</table>          
</div><!-- end of main info -->	 
<div id="detail_info" style="display:none ">

 
 <table width="100%">
  			<tr >
			  <td id="mytabletitle" width="3%" align="center">NO</td>
			  <td id="mytabletitle" width="3%" align="center">DATE</td>
			  <td id="mytabletitle" width="3%" align="center">TIME</td>
			  <td id="mytabletitle" width="5%" align="center">IP&nbsp;ADDRESS</td>
			  <td id="mytabletitle" width="3%" align="center">UID</td>
			  <td id="mytabletitle" width="3%" align="center">SID</td>
			  <td id="mytabletitle" width="5%" align="center">LEVEL</td>
			  <td id="mytabletitle" width="3%" align="center">APP</td>
			  <td id="mytabletitle" width="10%" align="center">ACT</td>
			  <td id="mytabletitle" width="30%" align="center">DESCRIPTION</td>
			  <td id="mytabletitle" width="40%" align="center">DETAIL</td>
            </tr>
	<?php	

	$sql="select count(*) from sys_log $sqlsearch";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	$sql="select * from sys_log $sqlsearch $sqlsort limit $curr,$MAXLINE";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$xuid=$row['uid'];
		$xsid=$row['sid'];
		$xlvl=$row['lvl'];
		$xapp=$row['app'];
		$xact=$row['act'];
		$xip=$row['ip'];
		$xdt=$row['dt'];
		$xtm=$row['tm'];
		$des=$row['des'];
		$det=$row['det'];
		$des=stripslashes($des);
		$det=stripslashes($det);
		
			
		if(($q++%2)==0)
			echo "<tr bgcolor=#fafafa>";
		else
			echo "<tr bgcolor=#FFFFFF>";
		
		echo "<td align=center>$q</td>";
	   	echo "<td align=center>$xdt</td>";
 		echo "<td align=center>$xtm</td>";
		echo "<td>$xip</td>";
		echo "<td>$xuid</td>";
		echo "<td align=center>$xsid</td>";
		echo "<td>$xlvl</td>";
		echo "<td>$xapp</td>";
		echo "<td>$xact</td>";
		echo "<td>$des</td>";
		echo "<td>$det</td>";
		echo "</tr>";
  }
  mysql_free_result($res);
  ?>
</table>          


</div><!-- end detail_info -->

<?php include("inc/paging.php");?>

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