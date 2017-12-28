<?php
//10/05/2010 - tukar print_letter ke letter
//06/04/2010 - patch kira pelajar berhenti tgh jalan and masuk lambat
//12/04/2010 - petunjux X for tamat. repair parent.. update fee_process_xpaid.php
//12/04/2010 - boleh pilih status pelajar
//13/04/2010 - print surat warning
//31/08/2010 - lumsum
//120305 - fixed cut off advance
//120316 - fixed culculation by month
//120703 - fixed summary display
$vmod="v6.2.1";
$vdate="120703";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN');
		
		$cutoff=$_REQUEST['cutoff'];
		$view=$_REQUEST['view'];
		if($view=="")
			$view="show_summary";
			
		$allstudent=$_REQUEST['allstudent'];
		if($allstudent==1)
			$sqlstudent="";
		else
			$sqlstudent=" and stu.status=6";
			
		$unsponser=$_REQUEST['unsponser'];
		if($unsponser==1)
			$sqlunsponser=" and stu.isislah=0";
			
		$sponseronly=$_REQUEST['sponseronly'];
		if($sponseronly==1)
			$sqlsponseronly=" and stu.isislah=1";
			
		$isinstallment=$_REQUEST['isinstallment'];
		if($isinstallment==1)
			$sqlinstallment=" and stu.isfakir=1";
			
		$uninstall=$_REQUEST['uninstall'];
		if($uninstall==1)
			$sqluninstall=" and stu.isfakir=0";
			
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		$sqlyear="and year='$year'";
		
		
		
		$clslevel=$_POST['clslevel'];
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
		}
		if($clslevel!=""){
			$sqlclslevel="and cls_level='$clslevel'";
			$sqllevel="and level='$clslevel'";
		}
			
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name_matrik_ic -")==0)
			$search="";
		if($search!=""){
			$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
			$sqlclscode="";
			$clsname="";
			$clslevel="";
			$sqlclslevel="";
		}

	
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$ssname=stripslashes($row['sname']);
			$namatahap=$row['clevel'];
            mysql_free_result($res);					  
		}

		$month=$_POST['month'];
		if($month=="")
			$month=1;
			//$month=date('m');
			
		$xmonth=$month+1;
		$tillmonth=sprintf("$year-%02d-00",$xmonth);
		$maxout=$_POST['maxout'];
		
		
	$checker=$_POST['checker'];
	$op=$_POST['op'];
	if($op=="block"){
		if (count($checker)>0) {
			for ($i=0; $i<count($checker); $i++) {
		      		$sql="select isblock from stu where uid='$checker[$i]'";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$isb=$row['isblock'];
					if($isb){
						$sql="update stu set isblock=0 where uid='$checker[$i]'";
						mysql_query($sql)or die("query failed:".mysql_error());
					}else{
						$sql="update stu set isblock=1 where uid='$checker[$i]'";
						mysql_query($sql)or die("query failed:".mysql_error());
					}
			}
		}
	}
			
/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr='0';
    $total=$_POST['total'];
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
		$sqlsort="order by stu.id $order";
	else
		$sqlsort="order by $sort $order, stu.id desc";


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="javascript">
function clearwin(){
	document.myform.action="";
	document.myform.target="";
}
function process_form(action,month,year,viewtype){
	var ret="";
	var cflag=false;
	
	if(action=='block'){
		for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.id=='checker')&&(e.name!='checkall')){
                        if(e.checked==true)
                        	cflag=true;
                        else
                            allflag=false;
                }
        }
		if(!cflag){
			alert('Please checked student to BLOCK/UNBLOCK');
			return;
		}
		ret = confirm("Are you sure want to BLOCK/UNBLOCK??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
	if(action=='surat'){
		for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.type=='checkbox')&&(e.name!='checkall')){
                        if(e.checked==true)
                                cflag=true;
                        else
                                allflag=false;
                }
        }
		if(!cflag){
			alert('Please checked the student to send Warning Letter');
			return;
		}
		if(document.myform.letterid.value==''){
			alert('Please select reminder letter');
			document.myform.letterid.focus();
			return;
		}
		document.myform.target="newwindow";
		document.myform.op.value=action;
		if(viewtype=='show_parent')
			document.myform.action="../efee/warning_letter_parent.php?month="+month+"&year="+year;
		else
			document.myform.action="../efee/warning_letter.php?month="+month+"&year="+year;
			
		newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
		var a = window.setTimeout("document.myform.submit();",500);
		newwin.focus();
		return;
	}
	if(action=='sms'){
		for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.type=='checkbox')&&(e.name!='checkall')){
                        if(e.checked==true)
                                cflag=true;
                        else
                                allflag=false;
                }
        }
		if(!cflag){
			alert('Please checked the student to send SMS reminder');
			return;
		}
		//ret = confirm("Are you sure want to send SMS reminder??");
		//if (ret == true){
			document.myform.target="newwindow";
			document.myform.op.value=action;
			if(viewtype=='show_parent')
				document.myform.action="../efee/sms_reminder.php?month="+month+"&year="+year;
			else
				document.myform.action="../efee/sms_reminder.php?month="+month+"&year="+year;
			newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
			var a = window.setTimeout("document.myform.submit();",500);
			newwin.focus();
			return;
		//}
	}
	if(action=='email'){
		for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.type=='checkbox')&&(e.name!='checkall')){
                        if(e.checked==true)
                                cflag=true;
                        else
                                allflag=false;
                }
        }
		if(!cflag){
			alert('Please checked the student to send EMAIL reminder');
			return;
		}
		if(document.myform.letterid.value==''){
			alert('Please select reminder letter');
			document.myform.letterid.focus();
			return;
		}
		ret = confirm("Are you sure want to send EMAIL reminder??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.target="newwindow";
			document.myform.op.value=action;
			if(viewtype=='show_parent')
				document.myform.action="../efee/warning_letter_parent.php?month="+month+"&year="+year;
			else
				document.myform.action="../efee/warning_letter.php?month="+month+"&year="+year;
			newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
			var a = window.setTimeout("document.myform.submit();",500);
			newwin.focus();
			return;
		}
		return;
	}
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<?php include("$MYOBJ/datepicker/dp.php")?>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../efee/feeout">
	<input type="hidden" name="op">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
	

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="clearwin();document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="process_form('block')" id="mymenuitem"><img src="../img/lock.png"><br>Block/UnBlock</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="process_form('surat','<?php echo $month;?>','<?php echo $year;?>','<?php echo $view;?>')" id="mymenuitem"><img src="../img/pages.png"><br>Letter Reminder</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="process_form('sms','<?php echo $month;?>','<?php echo $year;?>','<?php echo $view;?>')" id="mymenuitem"><img src="../img/flash.png"><br>SMS Reminder</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="process_form('email','<?php echo $month;?>','<?php echo $year;?>','<?php echo $view;?>')" id="mymenuitem"><img src="../img/email.png"><br>Email Reminder</a>
	
</div>

<div align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>				
</div>   <!-- end right -->            


</div>

<div id="story" style="border:none ">
 
<div id="mytabletitle" style="padding:5px 5px 5px 5px "  class="printhidden">

		<select name="month">
                <?php
							$sql="select * from month where no='$month'";
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							$row=mysql_fetch_assoc($res);
							$monthname=$row['name']; 
							$v=$row['no'];
							echo "<option value=$v>$lg_until $monthname</option>";
						
							$sql="select * from month where no!='$month'";
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
								$p=$row['name'];
								$v=$row['no'];
								echo "<option value=$v>$lg_until $p</option>";
							}
				?>
		</select>
                
		   <select name="sid" onchange="document.myform.clscode.value='';clearwin();document.myform.submit();">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$ssname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['sname']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}									
?>
          </select>
			<select name="year">
<?php
            echo "<option value=$year>$lg_session $year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">$lg_session  $s</option>";
            }
            mysql_free_result($res);					  
?>
          </select>
                <select name="clslevel">
                  <?php    
							if($clslevel=="")
									echo "<option value=\"\">- $lg_all $lg_level -</option>";
							else
								echo "<option value=$clslevel>$namatahap $clslevel</option>";
							$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
								$s=$row['prm'];
								echo "<option value=$s>$namatahap $s</option>";
							}
							if($clslevel!="")
            					echo "<option value=\"\">- $lg_all $lg_level -</option>";
					?>
                </select>
                <select name="clscode">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- $lg_all $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					//$sql="select * from cls where sch_id=$sid and code!='$clscode' $sqltahap order by level";
					$sql="select * from cls where sch_id=$sid and code!='$clscode' order by level";
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
				<input name="search" type="text" id="search" size="30" onMouseDown="document.myform.search.value='';document.myform.search.focus();"
                 value="<?php if($search=="") echo "- $lg_name_matrik_ic -"; else echo "$search";?>">
               <input type="button" name="bview" value="View Report .." onClick="clearwin();document.myform.submit()" style="font-weight:bold; color:#0066CC; width:150px">
<br>		
</div>

<table width="100%" id="mytabletitle" style="border-top:none;" class="printhidden">
<tr><td width="50%">
				<input type="checkbox" name="allstudent" value="1" <?php if($allstudent) echo "checked";?>><?php echo $lg_all_student_include_resigned;?>
              	<input type="checkbox" name="sponseronly" value="1" <?php if($sponseronly) echo "checked";?>>Show Sponsored
				<input type="checkbox" name="unsponser" value="1" <?php if($unsponser) echo "checked";?>>Show Unsponsored
				<input type="checkbox" name="isinstallment" value="1" <?php if($isinstallment) echo "checked";?>>Show Installment
                <input type="checkbox" name="uninstall" value="1" <?php if($uninstall) echo "checked";?>>Show Uninstallment
               <br><br>
				 <select name="maxout" onChange="clearwin();document.myform.submit()">
                  <?php
				if($maxout=="")
					echo "<option value=\"\">$lg_all $lg_amount</option>";
				else
					echo "<option value=\"$maxout\">$lg_more_then $lg_rm$maxout</option>";
				$sql="select * from type where grp='fee_outlevel' and  prm!='$maxout' order by idx";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
					$p=$row['prm'];
					echo "<option value=$p>$lg_more_then $lg_rm$p</option>";
				}
				if($maxout!="")
					echo "<option value=\"\">$lg_all $lg_amount</option>";
				
?>
                </select>
				&nbsp;&nbsp;&nbsp;<?php echo strtoupper($lg_cutoff_date);?>: 
                <input type="text" name="cutoff" id="cutoff" size="10" readonly value="<?php echo $cutoff;?>" onClick="displayDatePicker('cutoff');" 
                	onKeyDown="displayDatePicker('cutoff');">
				<a href="#" onClick="document.myform.cutoff.value='';">(Clear)</a>
                
                <br>

				<!-- 
				<a href="#" onClick="newwindow('../efee/feeprocess_xpaid.php?sid=<?php echo "$sid&year=$year";?>',0)" >xxx</a>
				 -->

				
</td><td width="50%" align="right">
				
<label style="cursor:pointer"><input type="radio" name="view" value="show_summary" <?php if($view=="show_summary") echo "checked";?>><?php echo strtoupper($lg_summary);?></label>
<label style="cursor:pointer"><input type="radio" name="view" value="show_student" <?php if($view=="show_student") echo "checked";?>><?php echo strtoupper($lg_student);?></label>
<label style="cursor:pointer"><input type="radio" name="view" value="show_parent" <?php if($view=="show_parent") echo "checked";?>><?php echo strtoupper($lg_parent);?></label>

&nbsp;&nbsp;&nbsp;&nbsp;				
<select name="letterid">
<?php
			echo "<option value=\"\">- Select Reminder Template -</option>";
			$sql="select * from letter where type='feewarn' and (sid=0 or sid=$sid) order by id";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $id=$row['id'];
						$name=$row['name'];
                        echo "<option value=\"$id\">$name</option>";
            }	  
?>
</select>
<?php if(is_verify('ADMIN')){?>
        			<input type="button" value="+" onClick="newwindow('../adm/letter_config.php?sid=<?php echo $sid;?>&type=feewarn',0)">
<?php } ?>
</td></tr></table>


<table width="100%" id="mytitlebg">
<tr><td width="50%"> 
<?php echo strtoupper("$lg_outstanding_report");?> : <?php echo strtoupper("$sname");?> 
-
<?php 
	if($clsname!="")
		echo strtoupper("$lg_class $clsname");
	elseif($clslevel!="")
		echo strtoupper("$namatahap $clslevel");
?>
<br>
<?php echo strtoupper("$lg_until");?> <?php echo strtoupper("$monthname $year");?>
</td>
<td align="right">
<?php echo strtoupper("$lg_cutoff_date");?> : <?php if($cutoff=="")echo date('Y-m-d'); else echo " $cutoff";?>
<br>Amount (RP) : <?php if($maxout>0) echo strtoupper("$lg_more_then $lg_rm$maxout"); else echo $lg_any_amount;?>


</td></tr></table>

<?php if(($view=="show_student")||($view=="show_summary")){ ?>

<div id="show_student" <?php if($view=="show_summary"){?>style="display:none "<?php }?>>

<table width="100%" cellspacing="0" cellpadding="0"  style="font-size:9px; font-family:Tahoma" >
<tr>
	<td class="mytableheader" style="border-right:none;" rowspan="2" width="1%" class="printhidden"><input type=checkbox name=checkall value="0" onClick="checkbox_checkall(1,'checker')"></td>
	<td class="mytableheader" style="border-right:none;" rowspan="2" width="1%" align="center"><?php echo $lg_no;?></td>
    <td class="mytableheader" style="border-right:none;" rowspan="2" width="3%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_matric;?></a></td>
	<td class="mytableheader" style="border-right:none;" rowspan="2" width="1%" align="center"><?php echo $lg_class;?></td>
    <td class="mytableheader" style="border-right:none; padding-left:2px;" rowspan="2" width="10%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_name;?></a></td>
<?php
		$sql="select * from type where grp='feetype' and (sid=0 or sid=$sid) order by idx";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xf=$row['prm'];
			$xt=$row['val'];
			$FEEGROUP[]=$xf;
			$sql="select feeset.name,type.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$xt and type.code<=$month";
			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			$colspan=mysql_num_rows($res2);
			
			if($colspan==0) 
				continue;
?>
			<td class="mytableheader" style="border-right:none;" align=center width="1%" colspan="<?php echo $colspan;?>"><?php echo $xf;?></td>
<?php }  ?>
		<td class="mytableheader" style="border-right:none;" rowspan="2" width="3%" align="center"><?php echo $lg_total;?></td>
		<td class="mytableheader" style="border-right:none;" width="2%" align="center" rowspan="2">Advance</td>
		<td class="mytableheader" width="2%" align="center" rowspan="2">Balance</td>
  </tr>
    
  <tr>
<?php
		$sql="select * from type where grp='feetype' and (sid=0 or sid=$sid) order by idx";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$idxfee=0;$gidx=0;
		while($row=mysql_fetch_assoc($res)){
			$xf=$row['prm'];
			$xt=$row['val'];
			$sql="select feeset.name,type.* from feeset INNER JOIN type ON feeset.name=type.prm where 
					feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$xt and type.code<=$month order by feeset.id";

			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			$num=mysql_num_rows($res2);
			if($num==0) continue;
			while($row2=mysql_fetch_assoc($res2)){
				$xx=$row2['etc'];
				$xz=$row2['prm'];
				$FEEITEM[$idxfee]=$xz;
				$feesetcode[$idxfee]=$xx;
				$feesetout[$idxfee]=0;
				$idxfee++;
				
?>

				<td id="mytabletitle" style="border-right:none; border-top:none;font-weight:normal;" align="center" width="2%"><a href="#" title="<?php echo "$xz";?>"><?php echo "$xx";?></a></td>
<?php } } ?>

  </tr>
  
    
  
 <?php
if($clslevel=="")
		$sql="select * from stu where sch_id=$sid and stu.sch_id=$sid $sqlstudent $sqlunsponser $sqlsponseronly $sqlinstallment $sqluninstall and stu.rdate<'$tillmonth' $sqlsearch $sqlsort";
else
		$sql="select distinct(stu_uid),stu.status,stu.uid,stu.name from ses_stu INNER JOIN stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlstudent $sqlunsponser $sqlsponseronly $sqlinstallment $sqluninstall and stu.rdate<'$tillmonth' $sqlclslevel $sqlclscode $sqlsearch $sqlyear $sqlsort";

$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		if($clslevel=="")
				$uid=$row['uid'];
		else
				$uid=$row['stu_uid'];
		$uid=$row['uid'];
		$status=$row['status'];
		$name=ucwords(strtolower(stripslashes($row['name'])));
		
		$sql="select * from ses_stu where stu_uid='$uid' and sch_id=$sid and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$cname=$row2['cls_name'];
			$ccode=$row2['cls_code'];
		}else
			continue;//$cls=$lg_none;
			
		if($cutoff=="")
			$sql="select * from feestu where uid='$uid' and ses='$year' and mon<=$month and val>0 and sta=0";
		else
			$sql="select * from feestu where uid='$uid' and ses='$year' and mon<=$month and val>0 and pdt<='$cutoff'";
		$res9=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$foundout=mysql_num_rows($res9);
		if($foundout==0)
			continue;

		if(($q++%2)==0)
			$bg="";
		else
			$bg="#FAFAFA";
			
		if($isblock)
			$bg="$bglred";
			
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
			  <td class="myborder" style="border-right:none; border-top:none;" class="printhidden" >
              	<input id="checker" type=checkbox name=checker[] value="<?php echo "$uid";?>" onClick="checkbox_checkall(0,'checker')" ></td>
              <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
              <td class="myborder" style="border-right:none; border-top:none;" align="center">
              		<a href="<?php echo "../efee/$FN_FEEPAY.php?uid=$uid&year=$year&sid=$sid";?>" 
                    target="_blank" title="payment" <?php if($status!=6){ echo "style=\"text-decoration:line-through\""; } ?> > 
					<?php echo "$uid";?></a>
              </td>
			  <td class="myborder" style="border-right:none; border-top:none;" align="center"><a href="#" title="<?php echo $cname;?>"><?php echo $ccode;?></a></td>
			  <td class="myborder" style="border-right:none; border-top:none; padding-left:2px;"><?php echo $name;?></td>
			  
<?php
		$totalhutang=0;
		for($j=0;$j<$idxfee;$j++){
				$fn=$FEEITEM[$j];
				
				$sql="select * from feestu where uid='$uid' and ses='$year' and mon<=$month and uid='$uid' and fee='$fn'";
				$res9=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				$row9=mysql_fetch_assoc($res9);
				$val=$row9['val'];
				$sta=$row9['sta'];
				$pdt=$row9['pdt'];
				if(($sta!=1)&&($val>0)){
						//MAKE A SECOND CEK AT FEEPAY -- check where there is a record paid
						$alreadypaid=0;
						$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$fn' and isdelete=0";
						$res9=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$alreadypaid=mysql_num_rows($res9);
						if($alreadypaid==0){
								$totalhutang=$totalhutang+$val;
								$totalallhutang=$totalallhutang+$val;
								$feesetout[$j]=$feesetout[$j]+$val;
								$bgc=$bglred;
						}
				}else{
						$xval=$val;
						$bgc="";
						$val="";
				}
				
				if($cutoff!=""){
								if(($xval>0)&&($sta==1)&&($pdt>$cutoff)){	
										$val=$xval;								
										$totalhutang=$totalhutang+$val;
										$totalallhutang=$totalallhutang+$val;
										$feesetout[$j]=$feesetout[$j]+$val;
										$bgc=$bglred;							
								}
				}
?>

				<td class="myborder" style="border-right:none; border-top:none;" align="center" bgcolor="<?php echo $bgc;?>"><?php echo $val;?></td>
<?php } ?>
		
<?php
$bgc="";
if($cutoff!="")
		$sqlcutoff="and cdate<='$cutoff 60:00:00'";				
else
		$sqlcutoff="";
		
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='ADVANCE' and isdelete=0 $sqlcutoff";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$advance=$row2[0];
		else
			$advance=0;
		
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='RECLAIM' and isdelete=0  $sqlcutoff";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$reclaim=$row2[0];
		else
			$reclaim=0;
		
		
		$advance=$advance+$reclaim; //statebus negative so kena +
		$totaladvance=$advance+$totaladvance;
		$finalhutang=$totalhutang-$advance;
?>

			<td id=myborder style="border-right:none; border-top:none;" align="right"><?php echo number_format($totalhutang,2,'.',',');?></td>
			<td id=myborder style="border-right:none; border-top:none;" align="right"><?php echo number_format($advance,2,'.',',');?></td>
			<td id=myborder style="border-top:none;" align="right"><?php echo number_format($finalhutang,2,'.',',');?></td>
			<input name="studentout[]" type="hidden"  value="<?php echo "$uid|$finalhutang";?>">
	</tr>
	
<?php if($q%40==0){?>
</table>
<div style="page-break-after:always"></div>
<table width="100%" cellspacing="0" cellpadding="0"  style="font-size:9px; font-family:Tahoma" >
<tr>
	<td id="mytabletitle" style="border-right:none;" rowspan="2" width="1%" class="printhidden">&nbsp;</td>
	<td id="mytabletitle" style="border-right:none;" rowspan="2" width="1%" align="center"><?php echo $lg_no;?></td>
    <td id="mytabletitle" style="border-right:none;" rowspan="2" width="3%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_matric;?></a></td>
	<td id="mytabletitle" style="border-right:none;" rowspan="2" width="1%" align="center"><?php echo $lg_class;?></td>
    <td id="mytabletitle" style="border-right:none; padding-left:2px;" rowspan="2" width="10%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_name;?></a></td>
<?php
		$sql="select * from type where grp='feetype' and (sid=0 or sid=$sid) order by idx";
		$res8=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row8=mysql_fetch_assoc($res8)){
			$xf=$row8['prm'];
			$xt=$row8['val'];
					
			$sql="select feeset.name,type.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$xt and type.code<=$month";
			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			$colspan=mysql_num_rows($res2);
			
			if($colspan==0) 
				continue;
?>
			<td id="mytabletitle" style="border-right:none;" align=center width="1%" colspan="<?php echo $colspan;?>"><?php echo $xf;?></td>
<?php }  ?>
		<td id="mytabletitle" style="border-right:none;" rowspan="2" width="3%" align="center"><?php echo $lg_total;?></td>
		<td id="mytabletitle" style="border-right:none;" width="2%" align="center" rowspan="2">Advance</td>
		<td id="mytabletitle" width="2%" align="center" rowspan="2">Balance</td>
  </tr>
    
  <tr>
<?php
		$sql="select * from type where grp='feetype' and (sid=0 or sid=$sid) order by idx";
		$res8=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row8=mysql_fetch_assoc($res8)){
			$xf=$row8['prm'];
			$xt=$row8['val'];
			$sql="select feeset.name,type.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$xt and type.code<=$month order by feeset.id";

			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			$num=mysql_num_rows($res2);
			if($num==0) continue;
			while($row2=mysql_fetch_assoc($res2)){
				$xx=$row2['etc'];
				$xz=$row2['prm'];
				
?>

				<td id="mytabletitle" align="center" width="2%"  style="border-top:none;border-right:none;font-weight:normal;">
                	<a href="#" title="<?php echo "$xz";?>"><?php echo "$xx";?></a></td>
<?php } } ?>

  </tr>

<?php } ?>
	
<?php $totalstudent=$q; }?>

	<tr>
		<td id="mytabletitle" style="border-right:none;font-weight:normal;" class="printhidden">&nbsp;</td>
		<td id="mytabletitle" style="border-right:none;" colspan="4" align="center"><?php echo $lg_total_amount;?></td>
		
<?php
		$q=0;$grpfeeout=0;
		for($j=0;$j<$idxfee;$j++){
?>
    <td  id="mytabletitle" style="border-right:none;font-weight:normal;"align="center"><?php echo $feesetout[$j];?></td>
<?php } ?>
    <td id="mytabletitle" style="border-right:none;font-weight:normal;" align=right><?php echo number_format($totalallhutang,2,'.',',');?></td>
	<td id="mytabletitle" style="border-right:none;font-weight:normal;" align=right><?php echo number_format($totaladvance,2,'.',',');?></td>
	<td id="mytabletitle" style="border-top:none;font-weight:normal;" align=right><?php echo number_format($totalallhutang-$totaladvance,2,'.',',');?></td>
	</tr>
</table>

<table width="20%" style="font-size:80% " cellspacing="0">
<tr>
	<td id="mytabletitle" align="center" width="20%" bgcolor="<?php echo "$bglgreen";?>"><?php echo strtoupper("$lg_legend");?></td>
</tr>
<tr>
	<td class="myborder" align="center" width="20%" bgcolor="<?php echo "$bglred";?>">RP</td>
	<td class="myborder">UNPAID</td>
</tr>
<tr>
	<td class="myborder" align="center">-</td>
	<td class="myborder">NOT INCLUDED</td>
</tr>
<tr>
	<td class="myborder" align="center">x</td>
	<td class="myborder">PAID</td>
</tr>
<tr>
	<td class="myborder" align="center">R</td>
	<td class="myborder">WIDTHDRAW</td>
</tr>
<tr>
	<td class="myborder" align="center"><a href="#" style="text-decoration:line-through">Matrik</a></td>
	<td class="myborder">WIDTHDRAW</td>
</tr>
</table>
</div><!-- div student -->

<div id="show_summary" <?php if($view=="show_student"){?>style="display:none "<?php }?>>

<table width="100%" cellspacing="0" cellpadding="3px">
<?php
		$j=0;
		$sql="select * from type where grp='feetype' and (sid=0 or sid=$sid) order by idx";
		$res8=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row8=mysql_fetch_assoc($res8)){
			$xf=$row8['prm'];
			$xt=$row8['val'];
?>
	<tr>
		<td class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo $lg_no;?></td>
		<td class="mytableheader" style="border-right:none;" width="30%"><?php echo $xf;?></td>
		<td class="mytableheader" style="border-right:none;" width="10%" align="right"><?php echo $lg_amount;?> (<?php echo $lg_rm;?>)</td>
		<td class="mytableheader" width="50%" align="right">&nbsp;</td>
	</tr>
<?php
			$sql="select feeset.name,type.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$xt and type.code<=$month order by feeset.id";
			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			$totalfee=0;
			while($row2=mysql_fetch_assoc($res2)){
				$prm=$row2['prm'];	
				$total=$feesetout[$j++];
				$totalfee=$totalfee+$total;
				$q++;
?>				
	<tr>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $q;?></td>
		<td class="myborder" style="border-right:none; border-top:none;" ><?php echo $prm;?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="right"><?php echo number_format($total,2,'.',',');?></td>
		<td class="myborder" style="border-top:none;" align="right">&nbsp;</td>
	</tr>
<?php }?>
	<tr bgcolor="#FAFAFA">
				<td class="myborder" style="border-right:none; border-top:none;" align="center">&nbsp;</td>
				<td class="myborder" style="border-right:none; border-top:none;" align="left"><?php echo $lg_total;?></td>
				<td class="myborder" style="border-right:none; border-top:none;" align="right" ><?php echo number_format($totalfee,2,'.',',');?></td>
				<td class="myborder" style="border-top:none;" align="right">&nbsp;</td>
	</tr>
<?php
		}
?>
							<tr bgcolor="#FAFAFA">
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="center"></td>
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="right"><?php echo $lg_total_amount;?></td>
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="right"><?php echo number_format($totalallhutang,2,'.',',');?></td>
								<td id="mytabletitle" style="border-top:none;" align="right">&nbsp;</td>
							</tr>
							<tr bgcolor="#FAFAFA">
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="center"></td>
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="right">Total Advance</td>
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="right"><?php echo number_format($totaladvance,2,'.',',');?></td>
								<td id="mytabletitle" style="border-top:none;" align="right">&nbsp;</td>
							</tr>
							<tr bgcolor="#FAFAFA">
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="center"></td>
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="right">Total Outstanding</td>
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="right"><?php echo number_format($totalallhutang-$totaladvance,2,'.',',');?></td>
								<td id="mytabletitle" style="border-top:none;" align="right">&nbsp;</td>
							</tr>
							<tr bgcolor="#FAFAFA">
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="center"></td>
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="right"><?php echo $lg_total_student;?></td>
								<td id="mytabletitle" style="border-right:none; border-top:none;" align="right"><?php echo $totalstudent;?></td>
								<td id="mytabletitle" style="border-top:none;" align="right">&nbsp;</td>
							</tr>
</table>
<?php } ?>
<?php if($view=="show_parent"){?>
<table width="100%" cellspacing="0" cellpadding="2">
<tr>
	<td class="mytableheader" style="border-right:none;" width="2%" align="center"><?php echo strtoupper("$lg_no");?></td>
	<td class="mytableheader" style="border-right:none;" width="18%" align="center"><?php echo strtoupper("$lg_parent");?></td>
    <td class="mytableheader" style="border-right:none;" width="30%" align="center"><?php echo strtoupper("$lg_student_name");?></td>
	<td class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo strtoupper("$lg_tel_mobile ($lg_father)");?></td>
	<td class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo strtoupper("$lg_tel_mobile ($lg_mother)");?></td>
	<td class="mytableheader" style="border-right:none;" width="30%" align="center"><?php echo strtoupper("$lg_address");?></td>
	<td class="mytableheader" width="5%" align="center"><?php echo strtoupper("$lg_total ($lg_rm)");?></td>
  </tr>
  <tr>
 <?php

$sql="select distinct(p1ic) from stu where stu.sch_id=$sid";
$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($rowx=mysql_fetch_assoc($resx)){
		$p1ic=$rowx['p1ic'];
		$ff=0;
		$ALLPAID=1;
		$totalhutang=0;
		$hh=0;
		
		$sql="select stu.* from stu where stu.sch_id=$sid $sqlstudent $sqlunsponser $sqlsponseronly $sqlinstallment $sqluninstall and p1ic='$p1ic' and stu.rdate<'$tillmonth'";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
				$uid=$row['uid'];
				$edate=$row['edate'];
				$status=$row['status'];
				$name=ucwords(strtolower(stripslashes($row['name'])));

				$sql="select * from ses_stu where stu_uid='$uid' and sch_id=$sid and year='$year'";
				$res2=mysql_query($sql)or die("query failed:".mysql_error());
				if($row2=mysql_fetch_assoc($res2)){
					$clsname=$row2['cls_name'];
					$ccode=$row2['cls_code'];
				}else
					continue;//$cls=$lg_none;
			
				
				$p1name=ucwords(strtolower(stripslashes($row['p1name'])));
				$p1ic=$row['p1ic'];
				$p1hp=$row['p1hp'];
				$p2hp=$row['p2hp'];
				$p1job=$row['p1job'];
				$p2job=$row['p2job'];
				$addr=ucwords(strtolower(stripslashes($row['addr'])));
				$sex=$row['sex'];
				
				$THISPAID=1;
				$sql="select * from feestu where uid='$uid' and ses='$year' and mon<=$month";
				$res9=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				while($row9=mysql_fetch_assoc($res9)){
						$feename=$row9['fee'];
						$feeval=$row9['val'];
						$feesta=$row9['sta'];
						$paydate=$row9['pdt'];
						
						if(($feeval>0)&&($feesta!=1)){
								//MAKE A SECOND CEK AT FEEPAY -- check where there is a record paid
								$alreadypaid=0;
								$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$feename' and isdelete=0";
								$res9x=mysql_query($sql)or die("$sql:query failed:".mysql_error());
								$alreadypaid=mysql_num_rows($res9x);
								if($alreadypaid==0){
											$ALLPAID=0;
											$THISPAID=0;
											$stuoutfee[$feename]=$feeval;
											$totalhutang=$totalhutang+$feeval;
											$stuhutang=$stuhutang+$feeval;
											$stupaysta[$feename]=2;//hutang
								}
						}
						if($cutoff!=""){
								if(($feeval>0)&&($feesta==1)&&($paydate>$cutoff)){
										$ALLPAID=0;
										$THISPAID=0;
										$stuoutfee[$feename]=$feeval;
										$totalhutang=$totalhutang+$feeval;
										$stuhutang=$stuhutang+$feeval;
										$stupaysta[$feename]=2;//hutang
								}
						}
				}
			
				if($THISPAID)
					continue;
					
				$hh++;
				if($ff==0)
					$names="$hh.$uid:". $name;
				else
					$names=$names."<br>$hh.$uid:".$name;
				
				$cname=ucwords(strtolower($clsname));
				$names=$names." - ". $cname;
				$ff++;
				
				$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='ADVANCE' and isdelete=0";
				$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				if($row2=mysql_fetch_row($res2))
					$advance=$row2[0];
				else
					$advance=0;
				
				$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='RECLAIM' and isdelete=0";
				$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				if($row2=mysql_fetch_row($res2))
					$reclaim=$row2[0];
				else
					$reclaim=0;
				$advance=$advance+$reclaim; //statebus negative so kena +
				$stuhutang=$stuhutang-$advance;
				$totalhutang=$totalhutang-$advance;
				$names=$names." (RP".$stuhutang.")" ;

				$stuhutang=0;
		}
		
		
		
		
		if($ALLPAID)
			continue;
		
		
		
		if($maxout!=""){
			if($totalhutang<$maxout)
				continue;
		}
		$totalallhutang=$totalallhutang+$totalhutang;
		for($j=0;$j<$idxfee;$j++){
				$feename=$FEEITEM[$j];
				$outfee[$feename]=$outfee[$feename]+$stuoutfee[$feename];
		}
		if(($q++%2)==0)
			$bg="";
		else
			$bg="#FAFAFA";
 ?>

		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
				<td id="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
				<td id="myborder" style="border-right:none; border-top:none;"><?php echo "$p1name";?></td>
				<td id="myborder" style="border-right:none; border-top:none;"><?php echo "$names";?></td>
				<td id="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$p1hp";?></td>
				<td id="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$p2hp";?></td>
				<td id="myborder" style="border-right:none; border-top:none;" ><?php echo "$addr";?></td>
				<td id="myborder" style="border-top:none;" align="right"><?php echo number_format($totalhutang,2,'.',',');?></td>
		</tr>
		
<?php } ?>

	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
        <td id="mytabletitle" align="center"><?php echo strtoupper("$lg_total_amount");?></td>
     	<td id="mytabletitle" align="right"><?php echo number_format($totalallhutang,2,'.',',');?></td>
	</tr>
</table>
<?php } ?>



</div></div>

</form>	

</body>
</html>
