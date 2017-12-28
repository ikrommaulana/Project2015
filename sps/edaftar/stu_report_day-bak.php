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
		}

		$month=date('m');
		$xyear=date('Y');
		$no_of_days_this_month = date('t',mktime(0,0,0,$month,1,$xyear));
		$xsdt=sprintf("$xyear-%02d-01",$month);
		$xedt=sprintf("$xyear-%02d-$no_of_days_this_month",$month);
		
		$sdt=$_REQUEST['sdt'];
		if($sdt=="")
			$sdt=$xsdt;
		$edt=$_REQUEST['edt'];
		if($edt=="")
			$edt=$xedt;
/**
		$startTime = strtotime($sdt);
		$endTime = strtotime($edt);
		for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
				$thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
				echo $thisDate."<br>";
		}
**/
		
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php include("$MYLIB/inc/myheader_setting.php");?>

<script type="text/javascript">
		$(function(){
					//$("#edate").datepicker();	
					$('#sdt,#edt').datepicker({dateFormat: 'yy-mm-dd'});
					$('#sdt,#edt').datepicker( "option", "showAnim", "show");
		});
</script
	 
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
		<input type="text" name="sdt" id="sdt" size="15" value="<?php echo $sdt;?>" >
		<div style="display:inline; margin:0px 0px 0px -20px; padding:2px 2px 1px 1px; cursor:pointer" 
                	onClick="document.myform.sdt.value='';" 
					onMouseOver="showhide2('img61','img51');" onMouseOut="showhide2('img51','img61');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px;" id="img51">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img61">
		</div>
                         
                -  <input type="text" name="edt" id="edt" size="15" value="<?php echo $edt;?>" >
                <div style="display:inline; margin:0px 0px 0px -20px; padding:2px 2px 1px 1px; cursor:pointer" 
                	onClick="document.myform.edt.value='';" 
					onMouseOver="showhide2('img62','img52');" onMouseOut="showhide2('img52','img62');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px;" id="img52">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img62">
		</div>
                &nbsp;
		<input type="submit" name="submit" value="Lihat">
	  </div>
</div><!-- end mypanel-->
<div id="story">
<div id="mytitle2"><?php echo "$lg_registration_report - $sname $year";?></div>
	
<table width="100%" cellspacing="0" cellpadding="5" style="font-size:11px">
	<tr>

		<td id="mytabletitle" rowspan="2" align="center" width="10%">TANGGAL</td>
<?php if($sid==0){?>
		<td id="mytabletitle" colspan="3" align="center" width="10%"><?php echo strtoupper("$lg_all $lg_school");?> </td> 
<?php 
}
		$sql="select * from sch where id>0 $sqlsid order by id";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while ($row=mysql_fetch_assoc($res)){
				$sname=stripslashes($row['sname']);		  
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
		<td id="mytabletitle" align="center"><?php echo strtoupper("L");?></td>
		<td id="mytabletitle" align="center"><?php echo strtoupper("P");?></td>
		<td id="mytabletitle" align="center"><?php echo strtoupper($lg_total);?> </td>
		<?php } ?>
		
	</tr>

	
	
<?php
$startTime = strtotime($sdt);
$endTime = strtotime($edt);
// Loop between timestamps, 24 hours at a time
for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
		$thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
		

				$sql="select count(*) from stureg where isdel=0 and rdate='$thisDate' $sqlschid $sqlyear";
				$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row=mysql_fetch_row($res);
		$xx=$row[0];
		//echo $sql;
		$sql="select count(*) from stureg where isdel=0  and rdate='$thisDate' $sqlschid $sqlyear and sex='1'";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		$row=mysql_fetch_row($res);
		$lel=$row[0];	
		$per=$xx-$lel;
		if($xx>0)
				$bg="$bglyellow";
			else
				$bg="";
?>

<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo strtoupper($thisDate);?></td>
				<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$lel";?></td>
				<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$per";?></td>
				<td class="myborder" style="border-right:none; border-top:none;" align="center"><strong><?php echo "$xx";?></strong></td>

<?php 

		$sql="select * from sch where id>0 $sqlsid order by id";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while ($row=mysql_fetch_assoc($res)){
				$xid=$row['id'];	
				$sql="select count(*) from stureg where isdel=0 and rdate='$thisDate' $sqlyear  and sch_id=$xid";
				$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row2=mysql_fetch_row($res2);
				$xx=$row2[0];
				$sql="select count(*) from stureg where isdel=0 and rdate='$thisDate' $sqlyear and sex='1' and sch_id=$xid";
				$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row2=mysql_fetch_row($res2);
				$lel=$row2[0];	
				$per=$xx-$lel;
?>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$lel";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$per";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><strong><?php echo "$xx";?></strong></td>
<?php } ?>
    </tr>

<?php } ?><!-- thisdate-->
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