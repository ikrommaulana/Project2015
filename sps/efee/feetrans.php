<?php
//$vdate="110109";
$vdate="110109";// download excell and select by date
$vmod="v6.0.0";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];
$isexcell = $_REQUEST['isexcell'];
if($isexcell){
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=report.xls");
	header ("Pragma: no-cache");
	header("Expires: 0");
}
		
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
			
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$sqlsid=" and feetrans.sch_id=$sid";
		}else{
			if($FEE_REPORT_ALL_SCHOOL)
				$sname= $lg_all." ". $lg_school;
			else
				$sqlsid=" and feetrans.sch_id=$sid";
		}
			
		$view=$_REQUEST['view'];
		if($view==""){
			$sqldelete=" and isdelete=0";
			$sqldelete2="and isdelete=0";
		}
		elseif($view=="1"){
			$sqldelete=" and isdelete=1";
			$sqldelete2="and isdelete=1";
		}
		elseif($view=="2"){
			$sqldelete="";
			$sqldelete2="and isdelete=0";
		}else{
			$sqldelete=" and isdelete=0";
		}

		$sdate=$_REQUEST['sdate'];
		if($sdate!="")
					$sqlsdate="and feetrans.cdate>='$sdate 00:00:00'";
					
		$edate=$_REQUEST['edate'];
		if($edate!="")
					$sqledate="and feetrans.cdate<='$edate 24:00:00'";		

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
	if($MAXLINE==0){
		//$MAXLINE=30;
		$sqlmaxline=" LIMIT $curr,30";
	}elseif($MAXLINE!='All' && $MAXLINE!=0){
		$sqlmaxline=" LIMIT $curr,$MAXLINE";
	}
	if($MAXLINE=='All'){
		$sqlmaxline="";		
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
<?php include("$MYOBJ/datepicker/dp.php")?>
<?php if($isexcell){?>
<style type="text/css">
#myborder{
	border: 1px solid #F1F1F1;/*F1F1F1*/
}
#mytabletitle{
	border: 1px solid #DDDDDD;
	background-color:#EEEEEE;
	font-weight:bold;
}
</style>
<?php }else{ ?>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<?php } ?>
</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
 	<input type="hidden" name="p" value="../efee/feetrans">
<?php $sql="SELECT stu.uid,stu.name,stu.ic,feetrans.* FROM stu RIGHT JOIN feetrans ON stu.uid=feetrans.stu_uid WHERE stu.id>0 $sqlsid  $sqlsdate $sqledate $sqldelete $sqlsearch $sqlsort";?>
<input type="hidden" name="sql" value="<?php echo $sql;?>">
<div id="content">
<?php if(!$isexcell){?>
<div id="mypanel">
	<div id="mymenu" align="center">
           <a href="../efee/feetrans.php?<?php echo "sid=$sid&sdate=$sdate&edate=$edate&view=$view&isexcell=1";?>" id="mymenuitem"><img src="../img/excel.png"><br>Excel</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.print();" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
	<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
	</div> <!-- end mypanel -->
	<div id="mytabletitle" class="printhidden" align="right" >
	<a href="#" title="<?php echo $vdate;?>"></a><br><br>
    
        FROM <input type="text" name="sdate" id="sdate" value="<?php echo $sdate;?>" onClick="displayDatePicker('sdate');" onKeyDown="displayDatePicker('sdate');">
<div style="display:inline; margin:0px 0px 0px -20px; padding:2px 2px 1px 1px; cursor:pointer" 
                	onClick="document.myform.sdate.value='';" 
					onMouseOver="showhide2('img61','img51');" onMouseOut="showhide2('img51','img61');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px;" id="img51">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img61">
				</div>
      &nbsp;&nbsp;          
UNTIL  <input type="text" name="edate" id="edate" value="<?php echo $edate;?>" onClick="displayDatePicker('edate');" onKeyDown="displayDatePicker('edate');">
<div style="display:inline; margin:0px 0px 0px -20px; padding:2px 2px 1px 1px; cursor:pointer" 
                	onClick="document.myform.edate.value='';" 
					onMouseOver="showhide2('img62','img52');" onMouseOut="showhide2('img52','img62');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px;" id="img52">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img62">
				</div>&nbsp;
                
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
							$s=stripcslashes($row['name']);
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
<?php } ?>
<div id="story">
<div id="mytitlebg"><?php echo "RESI BIAYA" ;?> : <?php echo strtoupper($sname);?></div>
	   
<table width="100%" cellspacing="0">
	<tr>
              <td id="mytabletitle" align="center" width="2%"><?php echo strtoupper($lg_no);?></td>
			  <td id="mytabletitle" align="center" width="11%"><a href="#" onClick="formsort('cdate','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_date);?></a></td>
              <td id="mytabletitle" align="center" width="4%"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php if($FEE_REPORT_USE_ACC)echo strtoupper($lg_account); else echo strtoupper($lg_matric);?></a></td>
              <td id="mytabletitle" width="15%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			  <td id="mytabletitle" width="10%"><a href="#" onClick="formsort('ic','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_ic_number);?></a></td>
			  <td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_class);?></td>
              <td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_session);?></td>
              <td id="mytabletitle" align="center" width="5%"><a href="#" onClick="formsort('id','<?php echo "$nextdirection";?>')" title="Sort"><?php echo "RESI";?></a></td>
			  <td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_amount);?></td>
			  <td id="mytabletitle" width="15%"><?php echo strtoupper($lg_information);?></td>
			  <td id="mytabletitle" width="10%"><?php echo strtoupper($lg_reference);?></td>
			  <td id="mytabletitle" width="25%"><?php echo strtoupper("Alasan Hapus");?></td>
            </tr>
	<?php	

		$sql=" SELECT count(*) FROM stu RIGHT JOIN feetrans ON stu.uid=feetrans.stu_uid WHERE stu.id>0 $sqlsid $sqlsdate $sqledate $sqldelete $sqlsearch";
        $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
        $row=mysql_fetch_row($res);
        $total=$row[0];

	if(($curr+$MAXLINE)<=$total)
         $last=$curr+$MAXLINE;
    else
    	$last=$total;

	$sql="SELECT stu.uid,stu.name,stu.ic,feetrans.* FROM stu RIGHT JOIN feetrans ON stu.uid=feetrans.stu_uid WHERE stu.id>0 $sqlsid $sqlsdate $sqledate $sqldelete $sqlsearch $sqlsort $sqlmaxline";
	

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
		$xyear=$row['year'];
		$deleterem=$row['deleterem'];
		$clsname="";
		$clscode="";
		$sql="select * from ses_stu where stu_uid='$uid' and year='$xyear'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$clscode=stripslashes($row2['cls_code']);
			$clsname=stripslashes($row2['cls_name']);
		}
		
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
		<td id="myborder" align=center><a href="#" onClick="newwindow('../efee/<?php echo $FN_FEERESIT;?>.php?id=<?php echo $feeid;?>',0)"><?php if($FEE_REPORT_USE_ACC) echo $acc; else echo $uid?></a></td>
	   	<td id="myborder"><a href="#" onClick="newwindow('../efee/<?php echo $FN_FEERESIT;?>.php?id=<?php echo $feeid;?>',0)"><?php echo $name;?></a></td>
		<td id="myborder"><?php echo $ic;?></td>
        <td id="myborder" align=center><a href="#" title="<?php echo $clsname;?>"><?php echo $clscode;?></a></td>
        <td id="myborder" align=center><?php echo $xyear;?></td>        
		<td id="myborder" align=center><?php echo $resitno;?></td>
		<td id="myborder" align="right"><?php echo number_format($rm,2,'.',',');?>&nbsp;</td>
		<td id="myborder"><?php echo "$pt ";?></td>
		<td id="myborder"><?php echo " $nc $ruj ";?></td>
		<td id="myborder"><?php echo $deleterem;?></td>
		
  		</tr>
<?php  }

/*$sql3="SELECT stu.uid,stu.name,stu.ic,feetrans.* FROM stu RIGHT JOIN feetrans ON stu.uid=feetrans.stu_uid WHERE stu.id>0 $sqlsid $sqlsdate $sqledate $sqldelete $sqlsearch $sqlsort $sqlmaxline";

	$res3=mysql_query($sql3)or die("$sql3:query failed:".mysql_error());
	while($row3=mysql_fetch_assoc($res3)){
		
		$rm2=$row3['rm'];
		$totalrm=$totalrm+$rm2;
		
		
	}*/

?>

	<tr>
        <td id="mytabletitle">&nbsp;</td>
		<td id="mytabletitle" colspan="5"></td>
		<td id="mytabletitle" colspan="2" align=center><?php echo strtoupper("$lg_amount ($lg_rm)");?></td>
		<td id="mytabletitle" align=right><?php echo number_format($totalrm,2,'.',',');?>&nbsp;</td>
		<td id="mytabletitle" ></td>
		<td id="mytabletitle" ></td>
		<td id="mytabletitle" ></td>
  	</tr>
		
</table>          

<?php include("../inc/paging.php");?>
						
	
</div></div>

</form>
</body>
</html>
