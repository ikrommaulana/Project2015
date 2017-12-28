<?php
//22/04/2010 - update view by STUDENT
$vmod="v5.0.1";
$vdate="12/09/2012";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
	verify("");
	$username=$_SESSION['username'];
	$p=$_REQUEST['p'];
	$sid=$_REQUEST['sid'];//sch id
	
	//search
	if($sid=="")
		$sid=$_SESSION['sid'];
	if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
            mysql_free_result($res);
          					  
	}
	//$y=$_POST['year'];
	//if($y=="")
		$y=date('Y');
		
	$m=$_REQUEST['month'];
	if($m==""){//default
	$m=date('m');
	}
	//else{ $m=$m;}
	
		
	$chm=$_REQUEST['chm'];
	if($chm!="")
		$m=$m+$chm;
		
	
	$d=date("d");     // Finds today's date
		
	$nd = date('t',mktime(0,0,0,$m,1,$y)); // This is to calculate number of days in a month
	//$mn=date('M',mktime(0,0,0,$m,1,$y)); // get JAN-DIS
	$dt=$_REQUEST['dt'];
	
	list($yy,$mm,$dd)=explode('-',$dt);
		if($dd>0)
		$day = date('l',mktime(0,0,0,$mm,$dd,$yy)); // get SUNDAY-SATURDAY
	
	if($mm!=""){
		$mc=$mm;
	}else{
		if($m!=""){
			$mc=$m;
		}else{
			$mc=date('m');
		}
	}
	$mn=date('F',mktime(0,0,0,$mc,1,$y)); // get JANUARY-DICEMBER
	$yn=date('Y',mktime(0,0,0,$m,1,$y)); // Year is calculated to display at the top of the calendar
	$j= date('w',mktime(0,0,0,$m,1,$y)); // This will calculate the week day of the first day of the month

	$op=$_POST['op'];
	
/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=10;
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
<!-- form -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>KEDATANGAN <?php echo strtoupper($clsname);?></title>
<script language="javascript">
/*function process_form(action){
		document.myform.p.value='../eatt/att_stu_reg';
		document.myform.dt.value=action;
		document.myform.submit();
}*/
function process_change(action){
	document.myform.p.value='../elesson/elesson_rep';
	document.myform.chm.value=action;
	document.myform.dt.value='';
	document.myform.submit();
}


function xxx(action){
	for (i=0; i<document.getElementById('mytablex').rows.length; i++)
		document.getElementById('mytablex').rows[i].cells[0].style.display="none";
	
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
<!-- apai remark
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.css" rel="stylesheet" type="text/css" media="all" />
-->
</head>
<body >
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="op">
	<input type="hidden" name="dt" value="<?php echo $dt;?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="month" value="<?php echo $mc;?>">
	<input type="hidden" name="chm" value="<?php echo $chm;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<!-- 
	<a href="#" onClick="" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
 -->
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="../img/help22.png"><br>HowTo</a>
<?php if($p==""){?>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
<?php } ?>
<!-- 
	<a href="#" onClick="show('divshowhide')" id="mymenuitem"><img src="../img/warning.png"><br>Reminder</a>
 -->
</div>
<div align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>
<?php if(is_verify("ADMIN|AKADEMIK")){?>
      <select name="sid" id="sid" onchange="document.myform.submit();">
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
      <input type="submit" value="View">
	  </a>
	 <?php } else {?>
				
				<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<?php } ?>
</div>
</div><!-- end mypanel -->
<div id="story">
<!--<div id="story_title"><?php echo strtoupper($lg_teaching_lesson);?>&nbsp;:&nbsp;<?php echo strtoupper("$sname");?></div>-->
<div id="tipsdiv" style="display:none">
<?php if($LG=="BM"){?>
	Tips&raquo;<br>
	1. Pilih Sekolah yang berkenaan.<br>
	2. Senarai pengajaran seluruh guru dipamerkan.<br>
	3. Klik pada Tarikh untuk melihat senarai pada hari-hari tertentu.<br>
	4. *Jadual taqwim sekolah perlulah di siapkan dahulu untuk mengguna servis ini.<br>
<?php }else{?>
	Tips&raquo;<br>
	1. Select the particular school.<br>
	2. List of Lesson Plan For all teachers appeared.<br>
	3. Click the date to get specific list.<br>
	4. TAQWIN need to be set first before can use this module.
<?php } ?>
	<br>
</div>
<table width="100%" border="0" id="mytitle">
	  <tr>
			<!--<td width="5%"><a href="#" onClick="process_change('-1')"><strong>PREV MONTH</strong></a></td>-->
			<td align="center"><?php echo strtoupper("$mn $y");?></td>
			<!--<td width="5%" align="right"><a href="#" onClick="process_change('1')"><strong>NEXT MONTH</strong></a></td>-->
	  </tr>
</table>
<!-- date list -->
<table width="100%" cellpadding="0" cellspacing="1">
 	   <tr>
			<td id="myborder" align="center"><a href="#" onClick="process_change('-1')"><strong>PREV MONTH</strong></a></td>

	<?php
		for($i=1;$i<=$nd;$i++){
			$dn=date('D',mktime(0,0,0,$m,$i,$y)); // get days MON, TUE etc 
			$dt=date('Y-m-d',mktime(0,0,0,$m,$i,$y)); // get date 2008-12-31 
			$bg="";
			$sql="select * from ses_cal where d='$dt'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sta=$row['sta'];
			if($sta=="") $bg=$bgkuning; //belum tanda 
			if($sta=="0") $bg=$bgkuning; //sekolah - hijau
			if($sta=="1") $bg=$bgbiru; //cuti biasa - kuning
			if($sta=="2") $bg=$bgpink; //cuti penggal -biru
			if($sta=="3") $bg=$bgoren; //public holiday - pink
			$size=80/31;
			echo "<td width=\"$size%\" bgcolor=$bg align=\"center\" style=\"border:1px solid #DDDDDD\"><a href=\"p.php?p=../elesson/elesson_rep&sid=$sid&dt=$dt\">$i<br>$dn</a></td>";	
			//echo "<td width=\"$size%\" bgcolor=$bg align=\"center\" style=\"border:1px solid #DDDDDD\"><a href=\"p.php?p=../elesson/elesson_rep&sid=$sid&dt=$dt&m=$m&chm=$chm\">$i<br>$dn</a></td>";
		}
?>
	<td id="myborder" align="center"><a href="#" onClick="process_change('1')"><strong>NEXT MONTH</strong></a></td>
</tr>
</table>
<div id="story_title"><?php echo strtoupper($lg_list);?>&nbsp;<?php echo strtoupper($lg_teaching_lesson);?>&nbsp;:&nbsp;<?php echo strtoupper("$sname");?></div>

<table width="100%" cellspacing="0" cellpadding="2">
  <tr>
  	<td id="mytabletitle" align="center" id="myborder" width="5%"><?php echo strtoupper($lg_no); ?></td>
    <td id="mytabletitle" align="center" id="myborder" width="7%"><?php echo strtoupper($lg_date); ?></td>
    <td id="mytabletitle"  align="center" id="myborder" width="15%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
    	<td id="mytabletitle" align="center" id="myborder" width="5%"><?php echo strtoupper($lg_subject); ?> </td>
		<td id="mytabletitle" align="center" id="myborder" width="20%"><?php echo strtoupper($lg_title); ?> </td>
		<td id="mytabletitle" align="center" id="myborder" width="20%"><?php echo strtoupper($lg_objective);?> </td>
        <td id="mytabletitle" align="center" id="myborder" width="10%"><?php echo strtoupper($lg_skill);?> </td>
        <td id="mytabletitle" align="center" id="myborder" width="3%"><?php echo strtoupper($lg_nilai);?> </td>
        <td id="mytabletitle" align="center" id="myborder" width="15%"><?php echo strtoupper($lg_bahanbantu);?> </td>
  </tr>
<?php 
//if($sid!=""){
//echo 'sid:'.$sid;
	$dt=$_REQUEST['dt'];
       
	
   if($sid==0){ 
   $sqlcnt="select count(*) from elesson";
   $sql="select elesson.*,usr.name from elesson INNER JOIN usr ON elesson.uid=usr.uid order by dt desc $sqlmaxline"; }
   else if($sid !=0 && $dt !=0){
   $sqlcnt="select count(*) from elesson where sid=$sid and dt='$dt'";
   $sql="select elesson.*,usr.name from elesson INNER JOIN usr ON elesson.uid=usr.uid where elesson.sid=$sid and elesson.dt='$dt' order by dt desc $sqlmaxline";}
   else if($sid !=0){ 
   	$sqlcnt="select count(*) from elesson where sid=$sid";
    $sql="select elesson.*,usr.name from elesson INNER JOIN usr ON elesson.uid=usr.uid where elesson.sid=$sid order by dt desc $sqlmaxline  "; }
   //if($sid !=0 && $dt !=0){$sql="select elesson.*,usr.name from elesson INNER JOIN usr ON elesson.uid=usr.uid where elesson.sid=$sid and dt='$dt'";}
	
	 $rescnt=mysql_query($sqlcnt,$link)or die("$sql:query failed:".mysql_error());
        $rowcnt=mysql_fetch_row($rescnt);
        $total=$rowcnt[0];
		if(($curr+$MAXLINE)<=$total)
			 $last=$curr+$MAXLINE;
		else
			$last=$total;
		
		$q=$curr;
		
		
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res);
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$subject=$row['subname'];
		$title=$row['title'];
		$obj=$row['obj'];
		$skill=$row['skill'];
		$nilai=$row['nilai'];
		$bahanbantu=$row['bahanbantu'];
		$date=$row['dt'];
		$name=ucwords(strtolower(stripslashes($row['name'])));
		if($q++%2==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
  <tr bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td align="center" id="myborder"><?php echo $q ; ?></td>
    <td  align="center" id="myborder"><?php echo $date ; ?></td>
	<td  id="myborder"><?php echo $name ; ?></td>
    <td  align="center" id="myborder"><?php echo $subject ; ?></td>
  	<td  id="myborder"><?php echo $title ; ?></td>
    <td  id="myborder"><?php echo $obj ;?></td>
    <td  id="myborder"><?php echo $skill ;?></td>
    <td  id="myborder"><?php echo $nilai; ?></td>
    <td  id="myborder"><?php echo $bahanbantu ;?></td>

  </tr>
<?php } ?>
</table>
<?php include("../inc/paging.php");?>
<div width="100%" align="right">

      <table width="50%" border="0" bgcolor="#666666">
        <tr>
          <td bgcolor="<?php echo $bgkuning;?>" align="center"><?php echo $lg_school_day;?></td> <!-- putih -->
          <td bgcolor="<?php echo $bgbiru;?>" align="center"><?php echo $lg_weekend;?></td> <!-- kuning -->
          <td bgcolor="<?php echo $bgpink;?>" align="center"><?php echo $lg_semester_break;?></td> <!-- orange -->
          <td bgcolor="<?php echo $bgoren;?>" align="center"><?php echo $lg_public_holiday;?></td> <!-- pink -->
		  <!-- <td bgcolor="<?php echo $bghijau;?>" align="center"><?php echo $lg_attend;?></td> 
		  <td bgcolor="<?php echo $bgmerah;?>" align="center"><?php echo $lg_absence;?></td> -->
        </tr>
	</table>
    
    
</div>
 </div></div>
</form>



</body>
</html>