<?php
//22/04/2010 - update view by tahap
$vmod="v5.0.0";
$vdate="12/11/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN|GURU');
$username = $_SESSION['username'];

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$ssname=stripslashes($row['sname']);
			$simg=$row['img'];
			$namatahap=$row['clevel'];
			$sqlsid="and ses_stu.sch_id=$sid";  
		}else{
			$namatahap=$lg_level;
			$sqlsid=="";
		}
		$clslevel=$_REQUEST['clslevel'];
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from ses_cls where sch_id=$sid and cls_code='$clscode' and year=$year";
			$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['cls_name']);
			$clslevel=$row['cls_level'];
		}
		if($clslevel!=""){
			$sqlclslevel="and cls_level='$clslevel'";
			$sqlsortcls=",cls_name asc";
		}
			
		$month=$_POST['month'];
		if($month!=""){
			$sql="select * from month  where no=$month";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$monthname=$row['name'];
		}
		$show_if_eldest_only=$_REQUEST['show_if_eldest_only'];
		if($clscode=="")
			$show_if_eldest_only="";
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name_matrik_ic -")==0)
			$search="";
		if($search!=""){
			$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or p1ic='$search' or p2ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}

/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=30;
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
<script language="javascript">
function clearwin(){
	document.myform.action="";
	document.myform.target="";
}
function process_form(action){
	var ret="";
	var cflag=false;
	
	if(action=='print'){
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
			alert('Please checked the item');
			return;
		}
		document.myform.target="newwindow";
		document.myform.action="../efee/feeinvoice_print.php";
		newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
		var a = window.setTimeout("document.myform.submit();",500);
		newwin.focus();
		return;
	}
	if(action=='process'){
		if(document.myform.sid.value==''){
			alert('Select shcool you wish to generate the invoice');
			document.myform.sid.focus();
			return;
		}
		if(document.myform.clslevel.value==''){
			alert('Select level class you wish to generate the invoice');
			document.myform.clslevel.focus();
			return;
		}
		if(document.myform.month.value==''){
			alert('Select month you wish to generate the invoice');
			document.myform.month.focus();
			return;
		}
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
			alert('Please checked the parent you wish to process');
			return;
		}
		
		document.myform.target="newwindow";
		document.myform.action="../efee/feeinvoice_gen.php";
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
			alert('Please checked the user');
			return;
		}
		document.myform.target="newwindow";
		document.myform.action="../parent/sms_send.php?id=feewarn&month="+month+"&year="+year;
		newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
		var a = window.setTimeout("document.myform.submit();",500);
		newwin.focus();
		return;
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
			alert('Please checked the user');
			return;
		}
		alert('Comming soon.TQ');
		/**
		ret = confirm("Are you sure want to send EMAIL REMINDER??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		**/
		return;
	}
}

</script>
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../efee/feeinvoice">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
        				<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="process_form('process')" id="mymenuitem"><img src="../img/option.png"><br>Generate</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="process_form('print')" id="mymenuitem"><img src="../img/pages.png"><br>Invoices</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="process_form('sms')" id="mymenuitem"><img src="../img/flash.png"><br>SMS</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="process_form('email')" id="mymenuitem"><img src="../img/email.png"><br>Email</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="clearwin();document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
	<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div><!-- end mypanel-->
<div id="story">


<div align="right" class="printhidden">
			
			<select name="year" id="year" onChange="clearwin();">
				<?php
					echo "<option value=$year>$lg_session $year</option>";
					$sql="select * from type where grp='session' and prm!='$year' order by val desc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						$v=$row['val'];
						echo "<option value=\"$s\">$lg_session $s</option>";
					}
					mysql_free_result($res);					  
				?>
          	</select>
			
			
			 <select name="sid" id="sid" onChange="clearwin();document.myform.clscode[0].value='';document.myform.submit();">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select -</option>";
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
		<select name="clslevel" onChange="clearwin();document.myform.clscode[0].value=''; document.myform.submit();">
        <?php    
		if($clslevel=="")
            echo "<option value=\"\">- $lg_level -</option>";
		else
			echo "<option value=$clslevel>$namatahap $clslevel</option>";
			$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=$s>$namatahap $s</option>";
            }
		if($clslevel!="")
            echo "<option value=\"\">- $lg_all -</option>";

?>
      	</select>
		<select name="clscode" id="clscode" onChange="clearwin();document.myform.submit();">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from ses_cls where sch_id=$sid and cls_code!='$clscode' and year=$year order by cls_level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=stripslashes($row['cls_name']);
						$a=$row['cls_code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
					if($clscode!="")
            			echo "<option value=\"\">- $lg_all $lg_class -</option>";
			?>
		</select>
				
		<select name="month">
				<?php
					if($month=="")
						echo "<option value=\"\">- $lg_select $lg_month -</option>";
					else
						echo "<option value=$month>$monthname</option>";
					$sql="select * from month order by no";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['name'];
						$n=$row['no'];
						echo "<option value=\"$n\">$s</option>";
					}
					mysql_free_result($res);					  
				?>
          	</select>
			
				<input name="search" type="text" id="search" size="30" onMouseDown="clearwin();document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "- $lg_name_matrik_ic -"; else echo "$search";?>">  
				
               <input type="button" name="bview" value="View" onClick="clearwin();document.myform.submit()">
			   <!-- 
			   <input type="checkbox" name="show_if_eldest_only" value="1"  <?php if($clscode=="") echo "disabled";?> <?php if(($show_if_eldest_only)&&($clscode!="")) echo "checked";?>> Show Print For Eldest Only
				 -->
	</div> <!-- right -->
			
			
<div id="mytitlebg"><?php echo strtoupper($lg_fee_invoice);?></div>
<table width="100%" style="font-size:9px" cellspacing="0">
		<tr>
				<td id="mytabletitle" width="1%" class="printhidden"><input type=checkbox name=checkall value="0" onClick="checkbox_checkall(1,'acc')"></td>
				<td id="mytabletitle" width="2%" align="center"><?php echo strtoupper($lg_no);?></td>
				<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_matric);?></td>
				<td id="mytabletitle" width="15%"><?php echo strtoupper($lg_name);?></td>
				<?php 
					$sql="select * from month order by no";
					$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=stripslashes($row['sname']);
				?>
					<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($s);?></td>
				<?php } ?>
		</tr>
 <?php


$sql="select * from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id='$sid' and stu.status=6 $sqlclscode $sqlclslevel $sqlsearch and year='$year'";	
$res4=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($row4=mysql_fetch_assoc($res4)){
		if($USE_PARENT_PAYMENT)
			$acc=$row4['acc'];
		else
			$acc=$row4['uid'];//invoice by parent
		$name=strtoupper(stripslashes($row4['name']));
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
/**
		if($show_if_eldest_only){
				$sql="select * from ses_stu where stu_uid='$xuid' and year='$year' order by sch_id asc, cls_level desc";
				$sql="select cls_code from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.acc='$acc' and stu.status=6 and year='$year' order by stu.sch_id asc, cls_level desc";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$cc=$row2['cls_code'];
				if($cc!=$clscode)
					continue;
		}
**/
 ?>

		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
			    <td id=myborder class="printhidden">
                	<input id="acc" type=checkbox name=acc[] value="<?php echo "$acc";?>" onClick="checkbox_checkall(0,'acc')"></td>
				<td id=myborder align="center"><?php echo "$q";?></td>
				<td id=myborder align="center"><?php echo "$acc";?></td>
				<td id=myborder><?php echo "$name";?></td>
				</td>
				<?php 
					$rm="";
					$sql="select * from month order by no";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					while($row2=mysql_fetch_assoc($res2)){
						$no=$row2['no'];
						$status="-";
						$sql="select * from invoice where acc='$acc' and year='$year' and month='$no'";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						if($row3=mysql_fetch_assoc($res3)){
							$sta=$row3['sta'];
							$xid=$row3['id'];
							$rm=$row3['rm'];
						}else
							$rm="";
				?>
				<td id="myborder" align="center">
                	<a href="../efee/feeinvoice_print.php?xacc=<?php echo "$acc&year=$year&month=$no&sid=$sid";?>" target="_blank"><?php echo "$rm";?></a>
                </td>
				
				<?php } ?>
				
		</tr>
		
<?php } ?>
</table>


</div></div>

</form> <!-- end myform -->



</body>
</html>
