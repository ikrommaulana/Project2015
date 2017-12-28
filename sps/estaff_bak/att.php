<?php
//110610 - upgrade gui
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
ISACCESS("estaff",1);
$username = $_SESSION['username'];
$f=$_REQUEST['f'];
$p=$_REQUEST['p'];

		if($_SESSION['sid']>0)
			$sid=$_SESSION['sid'];
		else
			$sid=$_REQUEST['sid'];
	
		if($sid!="")
			$sqlsid=" and sch_id=$sid";
			
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_id_name_ic -")==0)
			$search="";
		if($search!="")
			$sqlsearch="and (uid='$search' or ic='$search' or name like '$search%')";
	
		$jobdiv=stripslashes($_REQUEST['jobdiv']);
		if($jobdiv!="")
			$sqljobdiv="and jobdiv='".addslashes($jobdiv)."'";
		
		$job=stripslashes($_REQUEST['job']);
		if($job!="")
			$sqljob="and job='".addslashes($job)."'";
		
			
		$jobsta=stripslashes($_REQUEST['jobsta']);
		if($jobsta!="")
			$sqljobsta="and jobsta='$jobsta'";
		
		$job=$_REQUEST['job'];
		if($div!="")
			$sqljob="and job='$job'";
			
		$jobsta=$_REQUEST['jobsta'];
		if($jobsta!="")
			$sqljobsta="and jobsta='$jobsta'";
		
		$tamat=$_REQUEST['tamat'];
		if($tamat=="")
			$tamat=0;
		
		$sqltamat="and status=$tamat";
		
			
			
		if($sid>0){
			$sql="select * from sch where id=$sid";
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
	if($m=="")
		$m=date('m');
		
	$chm=$_REQUEST['chm'];
	if($chm!="")
		$m=$m+$chm;
	
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

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>../inc/my.js" type="text/javascript"></script>

<script language="javascript">
function process_form(action){
		document.myform.p.value='../eatt/att_stu_reg';
		document.myform.dt.value=action;
		document.myform.submit();
}
function process_change(action){
	document.myform.p.value='../eatt/att_cls_rep';
	document.myform.chm.value=action;
	document.myform.dt.value='';
	document.myform.submit();
}


function xxx(action){
	for (i=0; i<document.getElementById('mytablex').rows.length; i++)
		document.getElementById('mytablex').rows[i].cells[0].style.display="none";
	
}

</script>

</head>

<body >

 <form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		 <input type="hidden" name="p" value="<?php echo $p;?>">
		 <input type="hidden" name="uid">
		 <input type="hidden" name="op">
		 <input type="hidden" name="xid" value="<?php echo $uid;?>">
		 <input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
		 <input name="order" type="hidden" id="order" value="<?php echo $order;?>">
	<input type="hidden" name="dt" value="<?php echo $dt;?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="month" value="<?php echo $m;?>">
	<input type="hidden" name="chm">

<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
				<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		</div> <!-- end mymenu -->
		<div align="right"  >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
</div> <!-- end mypanel -->
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">

			   <select name="sid" id="sid" onChange="document.myform.submit();">
                <?php
			if($sid==""){
            	echo "<option value=\"\">- $lg_all -</option>";
				echo "<option value=\"0\">- All Access -</option>";
      		}else if($sid=="0"){
            	echo "<option value=\"0\">- All Access -</option>";
				echo "<option value=\"\">- $lg_all -</option>";
			}else{
                echo "<option value=$sid>$ssname</option>";
			}
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				if($sid>0){
					echo "<option value=\"\">- $lg_all -</option>";
					echo "<option value=\"0\">- All Access -</option>";
				}
			}				
			  
			
?>
              </select>
			  <select name="jobdiv" id="jobdiv" onChange="document.myform.submit();">
<?php	
      		if($jobdiv=="")
            	echo "<option value=\"\">- $lg_select -</option>";
			else
                echo "<option value=\"$jobdiv\">$jobdiv</option>";
			$xjobdiv=addslashes($jobdiv);
			$sql="select * from type where grp='jobdiv' and prm!='$xjobdiv'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=stripslashes($row['prm']);
                echo "<option value=\"$x\">$x</option>";
            }
           echo "<option value=\"\">- $lg_all -</option>";			  
			
?>
  </select>
				<input name="search" type="text"  id="search" size="25" <?php if($search==""){?>onClick="document.myform.search.value='';"<?php } ?>
					value="<?php if($search=="") echo "- $lg_id_name_ic -"; else echo "$search";?>">                
				
				<div style="display:inline; margin:0px 0px 0px -20px; padding:2px 2px 1px 1px; cursor:pointer" 
                	onClick="document.myform.search.value='';document.myform.search.focus();" 
					onMouseOver="showhide2('img6','img5');" onMouseOut="showhide2('img5','img6');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px" id="img5">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img6">
				</div>
				<input type="submit" name="Submit" value="<?php echo $lg_search;?> ..">

				<input type="checkbox" name="tamat" value="1" onClick="document.myform.submit();" <?php if($tamat) echo "checked";?>>
				<?php echo "$lg_staff $lg_resign $lg_only";?>
</div>
<div id="story">
<?php if($search!=""){?><div id="mytitlebg" style="color:#0066FF; font-size:12px">SEARCH FOR : '<?php echo $search;?>'</div><?php }?>
<div id="mytitle2"><?php echo "$lg_attendance";?></div>

<table width="100%" border="0" id="mytitle">
	  <tr>
			<td width="5%"><a href="#" onClick="process_change('-1')"><strong>PREV MONTH</strong></a></td>
			<td align="center" style="font-size:16px; font-family:'Comic Sans MS'"><?php echo strtoupper("$mn $y");?></td>
			<td width="5%" align="right"><a href="#" onClick="process_change('1')"><strong>NEXT MONTH</strong></a></td>
	  </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="2">
	<tr>
         	<td class="mytableheader" style="border-right:none;" width="1%" align="center"><?php echo strtoupper($lg_no);?></td>
			<td class="mytableheader" style="border-right:none;" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_staff);?></a></td>
			<td class="mytableheader" style="border-right:none;" width="20%">&nbsp;<a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
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
			echo "<td width=\"$size%\" bgcolor=$bg align=\"center\" style=\"border:1px solid #DDDDDD\">$i<br>$dn</td>";
		}
?>            
	</tr>
	<?php	
	$sql="select * from usr where id>0 $sqlsid $sqljobdiv $sqljob $sqljobsta $sqltamat $sqlsearch $sqlsort";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$xuid=$row['uid'];
			$name=strtoupper($row['name']);
			$name=stripslashes($name);
			$tel=$row['tel'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			 
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
			<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
              <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$xuid";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$name";?></td>
<?php
		
		for($i=1;$i<=$nd;$i++){
			$dn=date('D',mktime(0,0,0,$m,$i,$y)); // get days MON, TUE etc 
			$dt=date('Y-m-d',mktime(0,0,0,$m,$i,$y)); // get date 2008-12-31 
			$bg="";
			$sql="select * from ses_cal where d='$dt'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
            $row2=mysql_fetch_assoc($res2);
            $sta=$row2['sta'];
			
				$xdes="";
				$bg="";
				$sql="select * from stuatt where d='$dt' and stu_uid='$uid'";
				$res2=mysql_query($sql)or die("query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$sta=$row2['sta'];
				$des=$row2['des'];
				if($sta=="") 
					$bg=""; //belum set
				else if($sta=="0"){ 
					$bg=$bgmerah; //sekolah - red
					if($des!="")
							$xdes="<a href=\"#\" title=\"$des\"><font color=#FFFFFF><strong>??</strong></font></a>";
				}
				else 
					$bg=$bghijau; //hijau - hadir
			//}
			echo "<td id=myborder width=\"$size%\" bgcolor=\"$bg\" align=\"center\">$xdes</td>";
			
			
		}
?>              
            </tr>

<?php }  ?>
 </table>

</div><!-- story -->
</div><!-- content -->
</form>	
</body>
</html>
