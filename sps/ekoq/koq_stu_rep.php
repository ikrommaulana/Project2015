<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
$username = $_SESSION['username'];
		
	$year=$_POST['year'];
	if($year==""){
			$sql="select * from type where grp='session' order by val desc limit 1";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			while($row=mysql_fetch_assoc($res)){
				    $y=$row['prm'];
			
				$year= $y ;    
			}
		}
		
	$grp=$_REQUEST['grp'];
	if($grp!="")
		$sqlgrp="and des='$grp'";
		
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	if($sid!=0){
		$sql="select * from sch where id='$sid'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
			$sname=stripcslashes($row['name']);
				$stype=$row['level'];
				$level=$row['clevel'];
				$addr=$row['addr'];
				$state=$row['state'];
				$tel=$row['tel'];
				$fax=$row['fax'];
				$web=$row['url'];
				$school_img=$row['img'];
			mysql_free_result($res);					  
		} else{
			$level=$lg_level;
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
	<input type="hidden" name="p" value="<?php echo $p;?>">


<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
</div> <!-- end mymenu -->
<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
</div> <!-- end mypanel -->
<div id="mytabletitle" class="printhidden" align="right" >
<table width="100%"  border="0" >
  <tr>
      <td align="right">
	    <select name="sid" id="sid" onchange="document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"\">- $lg_select $lg_school -</option>";
	
				$sql="select * from sch where id>0 order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$s=stripcslashes($s);
							$t=$row['id'];
							if($t==$sid){$selected="selected";}else{$selected="";}
							echo "<option value=$t $selected>$s</option>";
				}
				mysql_free_result($res);
				
				echo "<option value=\"\">-Papar Semua-</option>";
											  
			
?>
        </select>  
		<select name="grp" onchange="document.myform.submit();">
<?php	
      		if($grp=="")
            	echo "<option value=\"\">- $lg_all $lg_group -</option>";
			else
                echo "<option value=\"$grp\">$grp</option>";
			//$sql="select * from type where grp='koq_grp' and prm!='$grp' order by val";
			$sql="select distinct(des) from koq where des!='$grp' order by val";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			while($row=mysql_fetch_assoc($res)){
							$g=$row['des'];
							echo "<option value=\"$g\">$g</option>";
			}
			if($grp!="")
            	echo "<option value=\"\">- $lg_all $lg_group -</option>";

?>
  		</select>

    <input type="button" name="Submit" value="View" onClick="processform()" >
    
	</td>
  </tr>
</table>

</div> <!-- end mypanel -->
<div id="story">
<div id="mytitlebg"><?php echo strtoupper("$lg_cocurriculum");?> - <?php echo strtoupper($sname);?> <?php
                        
			echo "Tahun Ajaran ".$y; ?></div>

<table width="100%" cellspacing="0">
  <tr>
    <td id="mytabletitle" rowspan="2" width="2%" align="center"><?php echo strtoupper("$lg_no");?></td>
    <td id="mytabletitle" rowspan="2" width="15%" align="center"><?php echo strtoupper("$lg_activity");?></td>
<?php 
	$sql="select * from type where grp='classlevel' and sid='$sid' order by prm";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
		 $s=$row['prm'];
?>
    <td id="mytabletitle" colspan="3" width="10%" align="center"><?php echo "$level $s";?></td>
<?php } ?>
	<td id="mytabletitle" colspan="3" width="10%" align="center"><?php echo strtoupper("$lg_total");?></td>
  </tr>
  <tr>
<?php 
	$sql="select * from type where grp='classlevel' and sid='$sid' order by prm";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$s=$row['prm'];
?>
	<td id="mytabletitle" width="3%" align="center"><?php echo $s=$lg_sexmf[1];strtoupper("$s");?></td>
	<td id="mytabletitle" width="3%" align="center"><?php echo $s=$lg_sexmf[0];strtoupper("$s");?></td>
	<td id="mytabletitle" width="3%" align="center"><?php echo strtoupper("$lg_total");?></td>
<?php } ?> 
	<td id="mytabletitle" width="3%" align="center"><?php echo $s=$lg_sexmf[1];strtoupper("$s");?></td>
	<td id="mytabletitle" width="3%" align="center"><?php echo $s=$lg_sexmf[0];strtoupper("$s");?></td>
	<td id="mytabletitle" width="3%" align="center"><?php echo strtoupper("$lg_total");?></td>
  </tr>
<?php 
	$sql="select * from koq where grp='koq' and prm!='$kelab' $sqlgrp and (sid=0 or sid=$sid) order by idx,prm"; 
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
		 $koq=$row['prm'];
		 if(($q++)%2==0)
            $bg="#FAFAFA";
        else
        	$bg="";
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
    <td id="myborder" align="center"><?php echo $q;?></td>
    <td id="myborder"><?php echo strtoupper($koq);?></td>
<?php 
	$sql="select * from type where grp='classlevel' and sid='$sid' order by prm";
    $res2=mysql_query($sql)or die("query failed:".mysql_error());
    while($row2=mysql_fetch_assoc($res2)){
		 $s=$row2['prm'];
		 $sql="select count(*) from stu INNER JOIN (ses_stu,koq_stu) ON stu.uid=ses_stu.stu_uid and stu.uid=koq_stu.uid where stu.sch_id='$sid' and stu.status=6 and stu.sex='1' and koq_stu.koq_name='$koq' and koq_stu.sta=0 and ses_stu.cls_level='$s'  and koq_stu.year='$year'";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $numl=$row3[0];
		 
		 $sql="select count(*) from stu INNER JOIN (ses_stu,koq_stu) ON stu.uid=ses_stu.stu_uid and stu.uid=koq_stu.uid where stu.sch_id='$sid' and stu.status=6  and stu.sex='0' and koq_stu.koq_name='$koq' and koq_stu.sta=0 and ses_stu.cls_level='$s'  and koq_stu.year='$year'";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $nump=$row3[0];
		 
		 $sql="select count(*) from stu INNER JOIN (ses_stu,koq_stu) ON stu.uid=ses_stu.stu_uid and stu.uid=koq_stu.uid where stu.sch_id='$sid' and stu.status=6 and koq_stu.koq_name='$koq' and koq_stu.sta=0 and ses_stu.cls_level='$s'  and koq_stu.year='$year'";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $num=$row3[0];
?>
    	<td id="myborder" align="center"><?php echo "$numl";?></td>
		<td id="myborder" align="center"><?php echo "$nump";?></td>
		<td id="myborder" align="center"><?php echo $num;?></td>
<?php } ?>
<?php 
		 $sql="select count(*) from stu INNER JOIN koq_stu ON stu.uid=koq_stu.uid where stu.sch_id=$sid and stu.status=6 and stu.sex='1' and koq_stu.koq_name='$koq' and koq_stu.sta=0";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $numl=$row3[0];
		 
		 $sql="select count(*) from stu INNER JOIN koq_stu ON stu.uid=koq_stu.uid where stu.sch_id=$sid and stu.status=6 and stu.sex='0' and koq_stu.koq_name='$koq' and koq_stu.sta=0";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $nump=$row3[0];
		 
		 $sql="select count(*) from stu INNER JOIN koq_stu ON stu.uid=koq_stu.uid where stu.sch_id=$sid and stu.status=6 and koq_stu.koq_name='$koq' and koq_stu.sta=0";
		 $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
    	 $row3=mysql_fetch_row($res3);
		 $num=$row3[0];
?>
    	<td id="myborder" align="center"><?php echo "$numl";?></td>
		<td id="myborder" align="center"><?php echo "$nump";?></td>
		<td id="myborder" align="center"><?php echo $num;?></td>
  </tr>
 <?php } ?>
</table>


</div></div>
</form>

</body>
</html>
