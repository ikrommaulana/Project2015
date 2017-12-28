<?php
//06/04/2010 - patch kira pelajar berhenti tgh jalan and masuk lambat
//12/04/2010 - boleh pilih status pelajar
$vmod="v5.0.0";
$vdate="12/11/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN');

		$showheader=$_REQUEST['showheader'];
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$allstudent=$_REQUEST['allstudent'];
		if($allstudent==1)
			$sqlstudent="";
		else
			$sqlstudent=" and stu.status=6";
			
			
		$tahap=$_POST['tahap'];
		if($tahap!=""){
			$sqltahap="and level='$tahap'";
		}
		
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
		}
		if($clslevel=="")
			$clslevel=0;
		if($tahap=="")
			$clslevel=0;
		else
			$clslevel=$tahap;

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		$sqlyear="and year='$year'";
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$ssname=stripslashes($row['sname']);
			$simg=$row['img'];
			$namatahap=$row['clevel'];
            mysql_free_result($res);					  
		}

		$month=$_POST['month'];
		if($month=="")
			$month=1;
			//$month=date('m');
		
		$xmonth=$month+1;
		$tillmonth=sprintf("$year-%02d-00",$xmonth);

			
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
		$sqlsort="order by id $order";
	else
		$sqlsort="order by $sort $order, id desc";


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="javascript">

function process_form(action){
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
			alert('Please checked student to BLOCK');
			return;
		}
		ret = confirm("Are you sure want to BLOCK??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
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
			alert('Please checked to send SMS REMINDER');
			return;
		}
		alert('Comming soon.TQ');
		/**
		ret = confirm("Are you sure want to send SMS REMINDER??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		**/
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
			alert('Please checked to send EMAIL REMINDER');
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

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../efee/fee_rep_out">
	<input type="hidden" name="op">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>

<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		<select name="year">
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
		   <select name="sid" onchange="document.myform.submit();">
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
			 
                       
            <select name="month" id="month" onChange="document.myform.submit()">
                  <?php
				  

					$sql="select * from month where no='$month'";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$monthname=$row['name']; 
					$v=$row['no'];
					echo "<option value=$v>$lg_outstanding $lg_until - $monthname</option>";
				
				$sql="select * from month where no!='$month'";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
					$p=$row['name'];
					$v=$row['no'];
					echo "<option value=$v>$lg_outstanding $lg_until - $p</option>";
				}
				mysql_free_result($res);
				
?>
                </select>
				
				
                <input type="button" name="Submit" value="View" onClick="document.myform.submit()" ><br>
				<input type="checkbox" name="allstudent" value="1" onClick="document.myform.submit()" <?php if($allstudent) echo "checked";?>><?php echo $lg_all_student_include_resigned;?>
				&nbsp;&nbsp;&nbsp;
				<input type="checkbox" name="showheader" value="1"  onClick="showhide('showheader','')" <?php if($showheader) echo "checked";?>>Show Header
</div>   <!-- end right -->            


</div>

<div id="story">
<div id="showheader" <?php if(!$showheader) echo "style=\"display:none\"";?> >
	<?php  include('../inc/school_header.php')?>
</div>
<div id="mytitlebg"><?php echo strtoupper("$lg_outstanding_report : $lg_until $monthname $year - $sname");?></div>

<table width="100%" cellspacing="0">
<tr>
	<td id="mytabletitle" width="1%" align="center" ><?php echo strtoupper("$lg_no");?></td>
	<td id="mytabletitle" width="20%" >&nbsp;<?php echo strtoupper("$lg_fee");?></td>
	<td id="mytabletitle" width="10%" align="center"><?php echo strtoupper("$lg_outstanding");?> (<?php echo strtoupper("$lg_rm");?>)</td>
	<td id="mytabletitle" width="70%" align="center" ></td>
</tr>
<?php 
	$sql="select * from type where grp='feetype' and (sid=0 or sid=$sid)";
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$feename=$row['prm'];
		$feetype=$row['val'];
		$q++;
?>
<tr>
	<td id="mytabletitle" style="background-color:#FAFAFA;" width="1%" align="center" ><?php echo $q;?></td>
	<td id="mytabletitle" style="background-color:#FAFAFA;" width="20%" colspan="3">&nbsp;<?php echo $feename;?></td>
</tr>

<?php 
	$totalrm=0;
	if($feetype>0)
				$sql="select feeset.name,type.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$feetype and type.code<=$month order by feeset.id";
	else
				$sql="select feeset.name,type.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$feetype order by feeset.id";
	//echo "$sql<br>";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	while($row3=mysql_fetch_assoc($res3)){
		$fee=$row3['prm'];
		$feemonth=$row3['code'];
		$strmonth=sprintf("%02d",$feemonth);
		$rm=0;
		$sql="select feestu.*, stu.edate,stu.rdate from feestu INNER JOIN stu ON stu.uid=feestu.uid where fee='$fee' and feestu.ses='$year' and stu.rdate<'$tillmonth' and feestu.sta=0 and feestu.val>0 and sid=$sid";
		$sql="select feestu.*, stu.edate,stu.rdate from feestu,ses_stu,stu where stu.uid=feestu.uid $sqlstudent and stu.uid=ses_stu.stu_uid and fee='$fee' and feestu.ses='$year' and feestu.ses=ses_stu.year and stu.rdate<'$tillmonth' and feestu.sta=0 and feestu.val>0 and sid=$sid";
	    $res4=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		//echo "$sql : $year-$strmonth-00<br>";
		while($row4=mysql_fetch_assoc($res4)){
				$feeval=$row4['val'];
				$edate=$row4['edate'];
				$rdate=$row4['rdate'];

				if($edate>"0000-00-00"){
					if($edate < "$year-$strmonth-00"){
						$feeval=0;
					}
				}

				$rm=$rm+$feeval;
		}
		$totalrm=$totalrm+$rm;
		$totalsum=$totalsum+$rm;
?>
<tr>
	<td id="myborder" width="1%" align="center" >-</td>
	<td id="myborder" width="20%" >&nbsp;<?php echo $fee;?></td>
	<td id="myborder" width="10%" align="right">&nbsp;<?php echo  number_format($rm,2,'.',',');?></td>
	<td id="myborder" align="center" >&nbsp;</td>
</tr>
<?php } ?>
<tr>
	<td id="myborder" align="center" ></td>
	<td id="myborder" align="right"><strong><?php echo strtoupper("$lg_total");?></strong></td>
	<td id="myborder" align="right"><strong>&nbsp;<?php  echo number_format($totalrm,2,'.',',');?></strong></td>
	<td id="myborder" align="center" >&nbsp;</td>
</tr>
<?php } ?>
<tr>
	<td id="mytabletitle" align="center" ></td>
	<td id="mytabletitle" align="right">&nbsp;<?php echo strtoupper("$lg_total_amount");?></td>
	<td id="mytabletitle" align="right">&nbsp;<?php echo number_format($totalsum,2,'.',',');?></td>
	<td id="myborder" width="1%" align="center" >&nbsp;</td>
</tr>
</table>


</div></div>

</form>	

</body>
</html>
