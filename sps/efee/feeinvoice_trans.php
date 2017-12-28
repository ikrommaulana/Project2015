<?php
$vmod="v5.0.0";
$vdate="110109";
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
			$sqlsid=" and invoice2.sch_id=$sid";
		}else{
			if($FEE_REPORT_ALL_SCHOOL)
				$sname= $lg_all." ". $lg_school;
			else
				$sqlsid=" and invoice2.sch_id=$sid";
		}
			
		$view=$_REQUEST['view'];
		if($view=="")
			$sqldelete=" and isdelete=0";
		elseif($view=="1")
			$sqldelete=" and isdelete=1";
		elseif($view=="2")
			$sqldelete="";
		else
			$sqldelete=" and isdelete=0";
		

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
 	<input type="hidden" name="p" value="../efee/feeinvoice_trans">

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
			if(($FEE_REPORT_ALL_SCHOOL)	&& ($sid=="0"))
            	echo "<option value=\"0\">- $lg_all $lg_school -</option>";
			elseif($sid=="0")
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
			}		
			if(($FEE_REPORT_ALL_SCHOOL)	&& ($sid>0))
					 echo "<option value=\"0\">- $lg_all -</option>"; 
?>
              </select>
				<input name="search" type="text" id="search" size="30" onMouseDown="document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "- $lg_name_matrik_ic -"; else echo "$search";?>">
				
                <input type="submit" name="Submit" value="View"><br>
				<label><input type="radio" name="view" value=""  onClick="document.myform.submit();" <?php if($view=="") echo "checked";?> >SHOW RECEIPT</label>&nbsp;
				<label><input type="radio" name="view" value="1" onClick="document.myform.submit();" <?php if($view=="1") echo "checked";?> >SHOW DELETE</label>&nbsp;
				<label><input type="radio" name="view" value="2" onClick="document.myform.submit();" <?php if($view=="2") echo "checked";?> >SHOW ALL</label>&nbsp;
	</div>               
</div><!-- end mypanel -->
<div id="story">
<div id="mytitlebg"><?php echo strtoupper("INVOICE LIST");?> : <?php echo strtoupper($sname);?></div>
	   
<table width="100%" cellspacing="0">
	<tr>
              <td id="mytabletitle" align="center" width="2%"><?php echo strtoupper($lg_no);?></td>
			  <td id="mytabletitle" align="center" width="11%"><a href="#" onClick="formsort('cdate','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_date);?></a></td>
              <td id="mytabletitle" align="center" width="4%"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php if($FEE_REPORT_USE_ACC)echo strtoupper($lg_account); else echo strtoupper($lg_matric);?></a></td>
              <td id="mytabletitle" width="30%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			  <td id="mytabletitle" width="10%"><a href="#" onClick="formsort('ic','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_ic_number);?></a></td>
			  <td id="mytabletitle" align="center" width="5%"><a href="#" onClick="formsort('id','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_receipt);?></a></td>
			  <td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_amount);?></td>
			  <td id="mytabletitle" width="15%"><?php echo strtoupper($lg_information);?></td>
			  <td id="mytabletitle" width="10%"><?php echo strtoupper($lg_reference);?></td>
            </tr>
	<?php	

		$sql=" SELECT count(*) FROM stu RIGHT JOIN invoice2 ON stu.uid=invoice2.stu_uid WHERE stu.id>0 $sqlsid $sqlsearch";
        $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
        $row=mysql_fetch_row($res);
        $total=$row[0];

	if(($curr+$MAXLINE)<=$total)
         $last=$curr+$MAXLINE;
    else
    	$last=$total;

	$sql="SELECT stu.uid,stu.name,stu.ic,invoice2.* FROM stu RIGHT JOIN invoice2 ON stu.uid=invoice2.stu_uid WHERE stu.id>0 $sqlsid $sqldelete $sqlsearch $sqlsort LIMIT $curr,$MAXLINE";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;$totalrm=0;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['stu_uid'];
		$acc=$row['acc'];
		$feeid=$row['id'];
		$dt=$row['cdate'];
		$ic=$row['ic'];
		$isdelete=$row['isdelete'];
		$pt=$row['paytype'];
		$resitno=$row['resitno'];
		$nc=$row['nocek'];
		$ruj=$row['rujukan'];
		$rm=$row['rm'];
		$totalrm=$totalrm+$rm;
		$name=stripslashes(strtoupper($row['name']));
		$q++;
		if($isdelete)
			$bg=$bglred;
		else
			$bg=$bglyellow;
?>
        <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" >
        <td id="myborder" align=center><?php echo $q;?></td>
		<td id="myborder" align=center><?php echo $dt;?></td>
		<td id="myborder" align=center><a href="#" onClick="newwindow('../efee/feeinvoice_v3.php?id=<?php echo $feeid;?>',0)"><?php if($FEE_REPORT_USE_ACC) echo $acc; else echo $uid?></a></td>
	   	<td id="myborder"><a href="#" onClick="newwindow('../efee/feeinvoice_v3.php?id=<?php echo $feeid;?>',0)"><?php echo $name;?></a></td>
		<td id="myborder"><?php echo $ic;?></td>
		<td id="myborder" align=center><?php echo $resitno;?></td>
		<td id="myborder" align="right"><?php echo number_format($rm,2,'.',',');?>&nbsp;</td>
		<td id="myborder"><?php echo "$pt $nc";?></td>
		<td id="myborder"><?php echo $ruj;?></td>
  		</tr>
<?php  } ?>

	<tr>
        <td id="mytabletitle">&nbsp;</td>
		<td id="mytabletitle" colspan="3"></td>
		<td id="mytabletitle" colspan="2" align=center><?php echo strtoupper("$lg_amount ($lg_rm)");?></td>
		<td id="mytabletitle" align=right><?php echo number_format($totalrm,2,'.',',');?>&nbsp;</td>
		<td id="mytabletitle" colspan="2"></td>
  	</tr>
		
</table>          

<?php include("../inc/paging.php");?>
						
	
</div></div>

</form>
</body>
</html>
