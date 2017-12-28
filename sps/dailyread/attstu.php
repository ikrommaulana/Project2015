<?php
//121101 - new
$vmod="v6.0.0";
$vdate="121101";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");

	$adm=$_SESSION['username'];
	$p=$_REQUEST['p'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
            mysql_free_result($res);					  
	}
	$cls=$_REQUEST['cls'];
	if($cls!=""){
			$sqlcls="and ses_stu.cls_code='$cls'";
			$sql="select * from cls where sch_id=$sid and code='$cls'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['name']);
	}
	$sub=$_REQUEST['sub'];
	if($sub!=""){
			$sql="select * from sub where sch_id=$sid and code='$sub'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $subname=stripslashes($row['name']);
	}
	
	
		
	
		
	$y=$_REQUEST['year'];
	if($y=="")
		$y=date('Y');
		
	$sql="select * from ses_sub where sch_id='$sid' and sub_code='$sub' and cls_code='$cls' and year='$y'";
	//$sql="select * from usr where uid='$adm' order by name";
    $res=mysql_query($sql)or die("$sql failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $teaname=stripslashes($row['usr_name']);
		
	$m=$_REQUEST['month'];
	if($m=="")
		$m=date('m');
		
	$chm=$_REQUEST['chm'];
	if($chm!="")
		$m=$m+$chm;
	
	$op=$_REQUEST['op'];
	if($op=="save"){
		
			$targetdt=$_POST['targetdt'];
			$targetms=$_POST['targetms'];
			
			for ($i=0; $i<count($targetms); $i++) {
				$xdt=$targetdt[$i];
				$xms=$targetms[$i];
				if($xms=="")
					$xms=0;
					
				$sql="delete from dailyread_target where sid=$sid and dt='$xdt' and cls='$cls' and sub='$sub'";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				$sql="insert into dailyread_target(year,dt,sid,cls,sub,ms) values ('$y','$xdt',$sid,'$cls','$sub','$xms')";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
			}
			$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
	}
	
	$d=date("d");     // Finds today's date
		
	$nd = date('t',mktime(0,0,0,$m,1,$y)); // This is to calculate number of days in a month
	//$mn=date('M',mktime(0,0,0,$m,1,$y)); // get JAN-DIS
	$mn=date('F',mktime(0,0,0,$m,1,$y)); // get JANUARY-DICEMBER
	$yn=date('Y',mktime(0,0,0,$m,1,$y)); // Year is calculated to display at the top of the calendar
	$j= date('w',mktime(0,0,0,$m,1,$y)); // This will calculate the week day of the first day of the month

	$op=$_POST['op'];
	$dt=$_POST['dt'];
	
	$dd=strtok($dt,"-");
	$dd=strtok("-");
	$dd=strtok("-");
	if($dd>0)
		$day = date('l',mktime(0,0,0,$m,$dd,$y)); // get SUNDAY-SATURDAY


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
		$sqlsort="order by name asc";
	else
		$sqlsort="order by $sort $order, name";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Rekod Bacaan</title>
<script language="javascript">
function process_form(action){
		document.myform.op.value='save';
		document.myform.submit();
}
function process_change(action){
	document.myform.chm.value=action;
	document.myform.dt.value='';
	document.myform.submit();
}
</script>


<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body >
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="op">
    <input type="hidden" name="sub" value="<?php echo $sub;?>">    
	<input type="hidden" name="dt" value="<?php echo $dt;?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="month" value="<?php echo $m;?>">
	<input type="hidden" name="chm">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="process_form();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br>Save</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br>Print</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>    
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br>Refresh</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
          
	<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/help22.png"><br>HowTo</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>    
<?php if($p==""){?>
	<a href="#" onClick="window.close()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br>Close</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>    
<?php } ?>
</div>
<div align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>
<?php if(is_verify("ADMIN|AKADEMIK")){?>
      <select name="sid" id="sid" onchange="document.myform.cls[0].value='';document.myform.submit();">
        <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
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
				mysql_free_result($res);
			}							  
			
?>
      </select>
      <select name="cls" id="cls" onchange="document.myform.submit();">
        <?php	
      				if($cls=="")
						echo "<option value=\"\">- $lg_class -</option>";
					else
						echo "<option value=\"$cls\">$clsname</option>";

					$sql="select * from ses_cls where sch_id=$sid and cls_code!='$cls'  and year='$y' order by cls_level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=stripslashes($row['cls_name']);
						$a=$row['cls_code'];
						$cls_level=$row['cls_level'];
                        echo "<option value=\"$a\">$b</option>";
            		}	
			?>
      </select>
      <select name="sub" onChange="document.myform.submit();">
<?php	
			if($sub=="")
						echo "<option value=\"\">- $lg_subject -</option>";
					else
						echo "<option value=\"$sub\">$subname</option>";
			$sql="select * from sub where sch_id=$sid and level='$cls_level' order by grp";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $name=$row['name'];
						$code=$row['code'];
						$grp=$row['grp'];
                        echo "<option value=\"$code\">$name</option>";
            }
?>
       </select>
	  <input type="submit" value="View">
	  </a>
	 <?php } else {?>
				<input type="hidden" name="cls" value="<?php echo $cls;?>">
				<input type="hidden" name="sid" value="<?php echo $sid;?>">
                <input type="hidden" name="sub" value="<?php echo $sub;?>">   
	<?php } ?>
</div>
</div><!-- end mypanel -->
<div id="story">
<div id="mytitle2"> <?php echo $lg_daily_reading;?> <?php echo $f;?></div>
<table width="100%" id="mytitle">
  <tr>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_school);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($sname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_class);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$clsname / $y");?></td>
		  </tr>
		</table>
	</td>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td><?php echo strtoupper($lg_subject);?></td>
			<td>:</td>
			<td><?php echo strtoupper($subname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_teacher);?></td>
			<td>:</td>
			<td><?php echo strtoupper($teaname);?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>
<table width="100%" border="0" id="mytitle">
	  <tr>
			<td width="5%"><a href="#" onClick="process_change('-1')"><strong>PREV MONTH</strong></a></td>
			<td align="center" style="font-size:18px;"><?php echo "$mn $y";?></td>
			<td width="5%" align="right"><a href="#" onClick="process_change('1')"><strong>NEXT MONTH</strong></a></td>
	  </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="2">
  <tr>
  	<td id="mytabletitle" width="2%" align="center" id="myborder" style="border-color:#DDD;border-right:none;"><?php echo strtoupper($lg_no);?></td>
	<td id="mytabletitle" width="2%" align="center" id="myborder" style="border-color:#DDD;border-right:none;"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_mf);?></a></td>
    <td id="mytabletitle" width="17%" id="myborder" style="border-color:#DDD;border-right:none;"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
    
<?php
		for($i=1;$i<=$nd;$i++){
			$dn=date('D',mktime(0,0,0,$m,$i,$y)); // get days MON, TUE etc 
			$dt=date('Y-m-d',mktime(0,0,0,$m,$i,$y)); // get date 2008-12-31 
			$bg="";
			$sql="select * from ses_cal where d='$dt'";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sta=$row['sta'];
			if($sta=="") $bg=$bgkuning; //belum tanda 
			if($sta=="0") $bg=$bgkuning; //sekolah - hijau
			if($sta=="1") $bg=$bgbiru; //cuti biasa - kuning
			if($sta=="2") $bg=$bgpink; //cuti penggal -biru
			if($sta=="3") $bg=$bgoren; //public holiday - pink
			if($sta=="4") $bg=$bglgray; //public holiday - pink
			$size=80/31;
			$thisdate=date('l', strtotime($dt));
			$xdt=explode("-",$dt);
			echo "<td width=\"$size%\"  bgcolor=$bg align=\"center\" style=\"border:1px solid #DDD;border-right:none;\">
				<a href=\"../dailyread/attreg.php?sid=$sid&cls=$cls&sub=$sub&dt=$dt\" title=\"$lg_daily_reading\" 
				onClick=\"return GB_showPage('Rekod Bacaan ($thisdate $xdt[0]/$xdt[1]/$xdt[2])',this.href)\" target=\"_blank\">$i<br>$dn</a></td>";
		}
?>		
		<td id="mytabletitle" width="2%" align="center" id="myborder" style="border-color:#DDD;"><?php echo $lg_total_mark;?></td>
  </tr>
  
  <tr>
  	<td id="mytabletitle" width="2%" align="center" id="myborder" style="border-color:#DDD;border-right:none;"></td>
	<td id="mytabletitle" width="2%" align="center" id="myborder" style="border-color:#DDD;border-right:none;"></td>
    <td id="mytabletitle" width="17%" id="myborder" style="border-color:#DDD;border-right:none;" align="left"> 
    	<a href="#" onClick="document.myform.op.value='edit';document.myform.submit();">
        	<img src="<?php echo $MYLIB;?>/img/edit12.png">&nbsp<?php echo strtoupper($lg_target_page);?>
        </a>
    </td>
    
<?php
		for($i=1;$i<=$nd;$i++){
			$dn=date('D',mktime(0,0,0,$m,$i,$y)); // get days MON, TUE etc 
			$dt=date('Y-m-d',mktime(0,0,0,$m,$i,$y)); // get date 2008-12-31 
			$sql="select * from dailyread_target where sid=$sid and dt='$dt' and cls='$cls' and sub='$sub'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
			$xms=$row['ms'];
			if($op=="edit"){			
?>			
			
			<td align="center" id="mytabletitle" style="border-color:#DDD;border-right:none;">
            	<input name="targetms[]" type="text" size="2" value="<?php echo $xms;?>">
                <input name="targetdt[]" type="hidden" value="<?php echo $dt?>"></td>
<?php }else{ ?>		
		<td id="mytabletitle" style="border-color:#DDD;border-right:none;" align="center"><?php echo $xms;?></td>
<?php } } ?>        
        <td id="mytabletitle" style="border-color:#DDD;" align="center">&nbsp;</td>
  </tr>
  
<?php 
if($cls!=""){
	$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid and status=6 $sqlisyatim $sqlisstaff $sqliskawasan $sqlcls $sqlsearch and year='$y' $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res);
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=ucwords(strtoupper(stripslashes($row['name'])));
		$sex=$row['sex'];
		if($q++%2==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
  <tr bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<?php
	
	 //echo "<td ><input type=checkbox name=del[] value=\"$xid\" onClick=\"check(0)\"></td>";
	
	?>
  	<td align="center" id="myborder" style="border-color:#DDD;border-top:none; border-right:none;"><?php echo $q?></td>
	<td align="center" id="myborder" style="border-color:#DDD;border-top:none; border-right:none;"><?php echo $lg_sexmf[$sex];?></td>
    <td id="myborder" style="border-color:#DDD;border-top:none; border-right:none;"><?php echo strtoupper($name);?></td>
<?php
		
		for($i=1;$i<=$nd;$i++){
			$dn=date('D',mktime(0,0,0,$m,$i,$y)); // get days MON, TUE etc 
			$dt=date('Y-m-d',mktime(0,0,0,$m,$i,$y)); // get date 2008-12-31 
			$bg="";
			$sql="select * from ses_cal where d='$dt'";
			$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
            $row2=mysql_fetch_assoc($res2);
            $sta=$row2['sta'];
			
				$xdes="";
				$bg="";
				$sql="select * from dailyread where dt='$dt' and stu='$uid' and cls='$cls' and sub='$sub'";
				$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$sta=$row2['sta'];
				$des=$row2['des'];
				$ms=$row2['ms'];
				if($ms>0)
					$bg=$bghijau;
				if($ms==0)
					$ms="";
		
			echo "<td id=myborder style=\"border-color:#DDD;border-top:none; border-right:none;\" width=\"$size%\" bgcolor=\"$bg\" align=\"center\">$ms</td>";
			
			
		}
		//kira jum tak hadir
			$thyear="";
			$thmonth="";
			$thy=date('Y',mktime(0,0,0,$m,1,$y)); // get date 2008-12-31 
			$thm=date('Y-m',mktime(0,0,0,$m,1,$y)); // get date 2008-12-31 
			$sql="select count(*) from dailyread where stu='$uid' and cls='$cls' and sub='$sub' and sta=0 and dt like '$thy%' ";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$thyear=$row2[0];
			
			$sql="select count(*) from dailyread where stu='$uid' and cls='$cls' and sub='$sub' and sta=0 and dt like '$thm%' ";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$thmonth=$row2[0];
?>
		<td align="center" id="myborder" style="border-color:#DDD;border-top:none;"><?php echo $thyear;?></td>
  </tr>
 <?php }}?>
</table>


 </div></div>
</form>



</body>
</html>