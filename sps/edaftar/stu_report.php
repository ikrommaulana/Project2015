<?php
$vmod="v5.0.0";
$vdate="09/07/10";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
ISACCESS("eregister",1);
$username = $_SESSION['username'];
$p=$_REQUEST['p'];

		$year=$_POST['year'];
		if($year==""){
				$sql="select * from type where grp='session' and prm!='$year' order by prm desc";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$year=$row['prm'];
		}
		$sqlyear="and sesyear='$year'";
			
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
			
		$status=$_POST['status'];
		if($status!="")
			$sqlstatus="and status=$status";

		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
            $sqlsid="and id=$sid";
			$sqlschid="and sch_id=$sid";			
			/**
			$sql="select * from type where grp='edaftarprogram' and sid='$sid'";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sname=$row['prm']; //school name
			**/
		}

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
	<input type="hidden" name="p" value="<?php echo $p;?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br><?php echo $lg_print;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br><?php echo $lg_refresh;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
   	 
	 <div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
        <select name="sid" onChange="document.myform.submit();">
		<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_all $lg_school -</option>";
			else
                echo "<option value=$sid>$sname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['name']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				echo "<option value=\"0\">- $lg_all -</option>";
			}					  	  
			
		?>
        </select>
		<select name="year" onChange="document.myform.submit();">
		<?php
			echo "<option value=\"$year\">$year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			while($row=mysql_fetch_assoc($res)){
					$s=$row['prm'];
					echo "<option value=\"$s\">$s</option>";
			}		
		?>
      </select>
	  
	  </div>
</div><!-- end mypanel-->
<div id="story">
<div id="mytitle2"><?php echo "$lg_registration_report - $sname $year";?></div>
	
<table width="100%" cellspacing="0" cellpadding="5">
	<tr>

		<td id="mytabletitle" rowspan="2" align="center" width="10%">STATUS</td>
   <?php if($sid==0){?>
		<td id="mytabletitle" colspan="3" align="center" width="10%"><?php echo strtoupper("$lg_all $lg_school");?> </td>
      
<?php 
}
			$sql="select * from sch where id>0 $sqlsid order by id";
			//$sql="select * from type where grp='edaftarprogram' order by sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while ($row=mysql_fetch_assoc($res)){
				$sname=stripslashes($row['sname']);		  
				//$sname=$row['prm'];
?>
		<td id="mytabletitle" colspan="3" align="center" width="5%"><?php echo strtoupper($sname);?></td> 
<?php } ?>       
	</tr> 		
	<tr>
		<?php 
			$sql="select * from sch where id>0 $sqlsid";
			$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
			$numsch=mysql_num_rows($res);
			if($sid==0)
			$numsch++;//tambah satu utk jumlah
			for($i=0;$i<$numsch;$i++){
		?>
		<td id="mytabletitle" align="center"><?php echo strtoupper($lg_malefemale[1]);?></td>
		<td id="mytabletitle" align="center"><?php echo strtoupper($lg_malefemale[0]);?></td>
		<td id="mytabletitle" align="center"><?php echo strtoupper($lg_total);?> </td>
		<?php } ?>
		
	</tr>
	
	
<?php
$sql="select * from type where grp='statusmohon' order by idx";
$res3=mysql_query($sql)or die("query failed:".mysql_error());
while($row3=mysql_fetch_assoc($res3)){
	$statusmohon=$row3[prm];
	$statusval=$row3[val];
			if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
	<tr bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td  id="mytabletitle"><?php echo $statusmohon;?></td>
<?php 
if($sid==0){
$sql="select count(*) from stureg where isdel=0 $sqlyear and status=$statusval";
$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
$row=mysql_fetch_row($res);
$xx=$row[0];
$sql="select count(*) from stureg where isdel=0 $sqlyear and status=$statusval and sex='1'";
$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
$row=mysql_fetch_row($res);
$lel=$row[0];	
$per=$xx-$lel;
?>
		<td id="myborder" align="center"><?php echo "$lel";?></td>
		<td id="myborder" align="center"><?php echo "$per";?></td>
		<td id="myborder" align="center"><?php echo "$xx";?></td>
<?php 
}
			$sql="select * from sch where id>0 $sqlsid order by id";
			//$sql="select * from type where grp='edaftarprogram' order by sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while ($row=mysql_fetch_assoc($res)){
				$xid=$row['id'];	
				$sql="select count(*) from stureg where isdel=0 $sqlyear and status=$statusval and sch_id=$xid";
				$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row2=mysql_fetch_row($res2);
				$xx=$row2[0];	  
				$sql="select count(*) from stureg where isdel=0 $sqlyear and status=$statusval and sex='1' and sch_id=$xid";
				$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row2=mysql_fetch_row($res2);
				$lel=$row2[0];	
				$per=$xx-$lel;
?>
		<td id="myborder" align="center"><?php echo "$lel";?></td>
		<td id="myborder" align="center"><?php echo "$per";?></td>
		<td id="myborder" align="center"><?php echo "$xx";?></td>
<?php }  ?>
    </tr>
<?php } ?>
<tr id="mytabletitle">
		<td  id="mytabletitle"><?php echo strtoupper($lg_total);?> <?php echo strtoupper($lg_registration);?></td>
<?php 
if($sid==0){
$sql="select count(*) from stureg where isdel=0 $sqlyear";
$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
$row=mysql_fetch_row($res);
$xx=$row[0];
$sql="select count(*) from stureg where isdel=0 $sqlyear and sex='1'";
$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
$row=mysql_fetch_row($res);
$lel=$row[0];	
$per=$xx-$lel;
?>
		<td id="myborder" align="center"><?php echo "$lel";?></td>
		<td id="myborder" align="center"><?php echo "$per";?></td>
		<td id="myborder" align="center"><?php echo "$xx";?></td>

<?php 
}
			$sql="select * from sch where id>0 $sqlsid order by id";
		   // $sql="select * from type where grp='edaftarprogram' order by sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while ($row=mysql_fetch_assoc($res)){
				$xid=$row['id'];	
				$sql="select count(*) from stureg where isdel=0 $sqlyear and sch_id=$xid";
				$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row2=mysql_fetch_row($res2);
				$xx=$row2[0];
				$sql="select count(*) from stureg where isdel=0 $sqlyear and sex='1' and sch_id=$xid";
				$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row2=mysql_fetch_row($res2);
				$lel=$row2[0];	
				$per=$xx-$lel;
?>
		<td id="myborder" align="center"><?php echo "$lel";?></td>
		<td id="myborder" align="center"><?php echo "$per";?></td>
		<td align="center"><?php echo "$xx";?></td>
<?php } ?>
    </tr>
    </table>



</div></div> 

</form> <!-- end myform -->
</body>
</html>
<!-- 
v2.7
22/11/2008	: update sesi listing
Author		: razali212@yahoo.com
 -->