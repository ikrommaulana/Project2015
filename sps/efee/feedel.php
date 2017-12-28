<?php
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];
		
		
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
           $sqlsid=" and feetrans_del.sch_id=$sid";
		}else{
			if($FEE_REPORT_ALL_SCHOOL)
				$sname= $lg_all." ". $lg_school;
			else
				$sqlsid=" and feetrans_del.sch_id=$sid";
		}
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name_matrik_ic -")==0)
			$search="";
		if($search!=""){
			$sqlsearch = "and (stu.uid='$search' or stu.ic='$search' or stu.name like '%$search%')";
			$search=stripslashes($search);
		}

/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
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

<html>
<head>

<script language="javascript">
	var myWind = ""
	function openchild(w,h,url) {
		if (myWind == "" || myWind.closed || myWind.name == undefined) {
    			myWind = window.open(url,"subWindow","HEIGHT=680,WIDTH=800,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar")
	  	} else{
    			myWind.focus();
  		}

	} 
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
 	<input type="hidden" name="p" value="../efee/feedel">

<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print();" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	</div>

	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
			<select name="sid" id="sid" onChange="document.myform.submit();">
<?php	
			if($sid=="0")
            	echo "<option value=\"0\">- $lg_school -</option>";
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
			if(($FEE_REPORT_ALL_SCHOOL)	&& ($sid>0))
					 echo "<option value=\"0\">- $lg_all -</option>"; 		  
?>
              </select>
				<input name="search" type="text" id="search" size="30" onMouseDown="document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "- $lg_name_matrik_ic -"; else echo "$search";?>">
				
                <input type="submit" name="Submit" value="View">
	</div>               

</div><!-- end mypanel -->
<div id="story">
       <div id="mytitlebg" style="color:#FF0000 "><?php echo strtoupper($lg_payment_cancel);?> : <?php echo strtoupper($sname);?></div>
<table width="100%"  cellspacing="0">
	<tr>
              <td id="mytabletitle" align="center" width="2%"><?php echo strtoupper($lg_no);?></td>
			  <td id="mytabletitle" align="center" width="11%"><a href="#" onClick="formsort('cdate','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_date);?></a></td>
              <td id="mytabletitle" align="center" width="4%"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php if($FEE_REPORT_USE_ACC)echo strtoupper($lg_account); else echo strtoupper($lg_matric);?></td>
              <td id="mytabletitle" width="30%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			  <td id="mytabletitle" width="10%"><a href="#" onClick="formsort('ic','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_ic_number);?></a></td>
			  <td id="mytabletitle" align="center" width="5%"><a href="#" onClick="formsort('id','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_receipt);?></a></td>
			  <td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_amount);?></td>
			  <td id="mytabletitle" width="15%"><?php echo strtoupper($lg_information);?></td>
			  <td id="mytabletitle" width="10%"><?php echo strtoupper($lg_reference);?></td>
			  <td id="mytabletitle" width="10%"><a href="#" onClick="formsort('ts','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_cancel_date);?></a></td>
			  <td id="mytabletitle" width="5%"><?php echo strtoupper($lg_by);?></td>
            </tr>
<?php	

	$sql=" SELECT count(*) FROM stu RIGHT JOIN feetrans_del ON stu.uid=feetrans_del.stu_uid WHERE feetrans_del.sch_id=$sid $sqlsearch";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
	$total=$row[0];

	if(($curr+$MAXLINE)<=$total)
         $last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	//$sql="select * from feetrans_del where sch_id=$sid $sqlsearch order by id desc limit $curr,$MAXLINE";
	$sql=" SELECT stu.uid,stu.name,stu.ic,feetrans_del.* FROM stu RIGHT JOIN feetrans_del ON stu.uid=feetrans_del.stu_uid WHERE feetrans_del.id>0 $sqlsid $sqlsearch $sqlsort LIMIT $curr,$MAXLINE";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;$totalrm=0;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['stu_uid'];
		$acc=$row['acc'];
		$feeid=$row['id'];
		$dt=$row['cdate'];
		$ic=$row['ic'];
		$pt=$row['paytype'];
		$nc=$row['nocek'];
		$ruj=$row['rujukan'];
		$rm=$row['rm'];
		$resitno=$row['resitno'];
		$adm=$row['adm'];
		$ts=strtok($row['ts']," ");
		$totalrm=$totalrm+$rm;
		$name=stripslashes(strtoupper($row['name']));
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
		$bg=$bglred;
?>
        <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
			<td id="myborder" align=center><?php echo $q;?></td>
			<td id="myborder" align=center><?php echo $dt;?></td>
			<td id="myborder" align=center><?php if($FEE_REPORT_USE_ACC) echo $acc; else echo $uid?></td>
			<td id="myborder"><a href="#" onClick="newwindow('../efee/<?php echo $FN_FEERESIT;?>.php?<?php echo "id=$feeid&op=viewdel";?>',0)"><?php echo $name;?></a></td>
			<td id="myborder"><?php echo $ic;?></td>
			<td id="myborder" align=center><?php echo $resitno;?></td>
			<td id="myborder" align="right"><?php echo number_format($rm,'2','.',',');?>&nbsp;</td>
			<td id="myborder"><?php echo "$pt $nc";?></td>
			<td id="myborder"><?php echo $ruj;?></td>
			<td id="myborder"><?php echo $ts;?></td>
			<td id="myborder"><?php echo $adm;?></td>
  		</tr>
<?php  } ?>

	<tr>
        <td id="mytabletitle">&nbsp;</td>
		<td id="mytabletitle" colspan="3"></td>
		<td id="mytabletitle" colspan="2" align=center><?php echo strtoupper("$lg_amount ($lg_rm)");?></td>
		<td id="mytabletitle" align=right><?php echo number_format($totalrm,'2','.',',');?>&nbsp;</td>
		<td id="mytabletitle" colspan="4">&nbsp;</td>
  	</tr>
		
</table>          

<?php include("../inc/paging.php");?>
						
	
</div></div>

</form>
</body>
</html>
