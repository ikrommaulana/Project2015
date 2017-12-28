<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];
		
	$year=$_POST['year'];
	if($year=="")
		$year=date('Y');
	$cyear=date('Y');
		
	$lvl=$_REQUEST['lvl'];
	if($lvl=="") 
		$lvl=1;
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	if($sid!=0){
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$stype=$row['level'];
		$level=$row['clevel'];
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
        mysql_free_result($res);					  
	}
	else
		$level="Tahun / Tingkatan";
	
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
<script language="javascript">
	var myWind = ""
	function openchild(w,h,url) {
		if (myWind == "" || myWind.closed || myWind.name == undefined) {
    			myWind = window.open(url,"subWindow","HEIGHT=680,WIDTH=850,scrollbars=yes,status=yes,resizable=yes,left=0,top=0,toolbar")
				//myWind.resizeTo(screen.availWidth,screen.availHeight);
	  	} else{
    			myWind.focus();
  		}

	}
	function processform(operation){
		if(document.myform.sid.value==""){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		document.myform.submit();
		
} 
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body >
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../ekoq/koq_rep_bil">


<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div> <!-- end mymenu -->


<table width="100%"  border="0" >
  <tr>
      <td align="right">
	    <select name="sid" id="sid" onchange="document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- Pilih Sekolah -</option>";
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

    <input type="button" name="Submit" value="View" onClick="processform()" >
    
	</td>
  </tr>
</table>

</div> <!-- end mypanel -->
<div id="story">
<?php if($isprint) include ('../inc/school_header.php'); ?>
<div id="mytitle" align="center">LAPORAN PERANGKAAN PELAJAR - <?php echo strtoupper($sname);?> SESI <?php echo $year;?></div>

<table width="100%" id="mytable">
  <tr>
    <td id="mytabletitle" width="2%" align="center">No</td>
    <td id="mytabletitle" width="20%" align="center">Aktiviti</td>
<?php 
	$sql="select * from type where grp='classlevel' and sid='$sid' order by prm";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
		 $s=$row['prm'];
?>
    <td id="mytabletitle" width="10%" align="center"><?php echo "$level $s";?>
	<div style="float:left; width:33% " align="center">L</div>
	<div style="float:left; width:33% " align="center">P</div>
	<div style="float:left; width:33% " align="center">JUM</div>
	</td>
<?php } ?>
	<td id="mytabletitle" width="8%" align="center">Jumlah
	<div style="float:left; width:33% " align="center">L</div>
	<div style="float:left; width:33% " align="center">P</div>
	<div style="float:left; width:33% " align="center">JUM</div>
	</td>
  </tr>
<?php 
	$sql="select * from type where grp='koq' and prm!='$kelab' and (sid=0 or sid=$sid) order by idx,prm"; 
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
		 $koq=$row['prm'];
		 if(($q++)%2==0)
            $bg="bgcolor=#FAFAFA";
        else
        	$bg="";
?>
  <tr <?php echo $bg;?> >
    <td align="center"><?php echo $q;?></td>
    <td><?php echo $koq;?></td>
<?php 
	$sql="select * from type where grp='classlevel' and sid='$sid' order by prm";
    $res2=mysql_query($sql)or die("query failed:".mysql_error());
    while($row2=mysql_fetch_assoc($res2)){
		 $s=$row2['prm'];
		 $sql="select count(*) from stu INNER JOIN (ses_stu,koq_stu) ON stu.uid=ses_stu.stu_uid and stu.uid=koq_stu.uid where stu.sch_id=$sid and stu.status=6 and stu.sex='Lelaki' and koq_stu.koq_name='$koq' and koq_stu.sta=0 and ses_stu.cls_level=$s  and year='$year'";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $numl=$row3[0];
		 $sql="select count(*) from stu INNER JOIN (ses_stu,koq_stu) ON stu.uid=ses_stu.stu_uid and stu.uid=koq_stu.uid where stu.sch_id=$sid and stu.status=6  and stu.sex='Perempuan' and koq_stu.koq_name='$koq' and koq_stu.sta=0 and ses_stu.cls_level=$s  and year='$year'";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $nump=$row3[0];
		 $sql="select count(*) from stu INNER JOIN (ses_stu,koq_stu) ON stu.uid=ses_stu.stu_uid and stu.uid=koq_stu.uid where stu.sch_id=$sid and stu.status=6 and koq_stu.koq_name='$koq' and koq_stu.sta=0 and ses_stu.cls_level=$s  and year='$year'";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $num=$row3[0];
?>
    <td align="center">
	<div style="float:left; width:33% " align="center"><?php echo "$numl";?></div>
	<div style="float:left; width:33% " align="center"><?php echo "$nump";?></div>
	<div style="float:left; width:33%; background-color:#F1f1f1 " align="center"><?php echo $num;?></div>
	</td>
<?php } ?>
<?php 
		 $sql="select count(*) from stu INNER JOIN koq_stu ON stu.uid=koq_stu.uid where stu.sch_id=$sid and stu.status=6 and stu.sex='Lelaki' and koq_stu.koq_name='$koq' and koq_stu.sta=0";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $numl=$row3[0];
		 
		 $sql="select count(*) from stu INNER JOIN koq_stu ON stu.uid=koq_stu.uid where stu.sch_id=$sid and stu.status=6 and stu.sex='Perempuan' and koq_stu.koq_name='$koq' and koq_stu.sta=0";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $nump=$row3[0];
		 
 		 $sql="select count(*) from stu INNER JOIN koq_stu ON stu.uid=koq_stu.uid where stu.sch_id=$sid and stu.status=6 and koq_stu.koq_name='$koq' and koq_stu.sta=0";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $num=$row3[0];
?>
	<td align="center">
	<div style="float:left; width:33% " align="center"><?php echo "$numl";?></div>
	<div style="float:left; width:33% " align="center"><?php echo "$nump";?></div>
	<div style="float:left; width:33%; background-color:#F1f1f1" align="center"><?php echo $num;?></div>
	</td>
  </tr>
 <?php } ?>
</table>


</div></div>
</form>
<form name="formwindow" method="post" action="px.php" target="newwindow">
	<input type="hidden" name="isprint" value="1">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="p" value="koq_rep_bil">
</form>
</body>
</html>
