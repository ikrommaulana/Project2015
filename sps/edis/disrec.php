<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];

	$sid=$_REQUEST['schid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	$search=$_REQUEST['search'];
	if(strcasecmp($search,"- ID, NAME -")==0)
		$search="";
	if($search!=""){
		$sqlsearch = "and (stu_uid='$search' or stu_name like '$search%')";
		$search=stripslashes($search);
	}
	if($sid!=0){
			$sql="select * from sch where id=$sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
			$sqlsid="and sch_id=$sid";  
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
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

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
function process_form(action,id){
	if(action=='search'){
		document.myform.submit();
		return;
	}
}

</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
</head>

<body>
<form name="myform" method="post" action="">
	<input type="hidden" name="p" value="disrec">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
</div> <!-- end mymenu -->
	<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
	</div> <!-- end mypanel -->
	<div id="mytabletitle" class="printhidden" align="right" >
		<a href="#" title="<?php echo $vdate;?>"></a>
<br>

		<select name="schid" id="schid" onChange="document.myform.submit()">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_school -</option>";
			else
                echo "<option value=\"$sid\">$ssname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				if($sid!="0")
				echo "<option value=\"0\">- $lg_all $lg_school -</option>";
			}							  			
?>
        </select>
      	<input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- ID, NAME -"; else echo "$search";?>"> 
		<input type="button" name="Submit3" value="View" onClick="process_form('search')">
</div>
<div id="story">
<div id="mytitle"><?php echo strtoupper("Catatan Kedisiplinan");?></div>
<table width="100%" cellspacing="0">
  <tr>
    <td id="mytabletitle" align="center" width="3%"><?php echo strtoupper("$lg_no");?></td>
	<td id="mytabletitle" align="center" width="7%"><a href="#" onClick="formsort('dt','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_date");?></a></td>
	<td id="mytabletitle" align="center" width="7%"><a href="#" onClick="formsort('stu_uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_matric");?></a></td>
	<td id="mytabletitle" width="20%"><a href="#" onClick="formsort('stu_name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_name");?></a></td>
	<td id="mytabletitle" width="15%"><a href="#" onClick="formsort('cls_name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_class");?></a></td>
	<td id="mytabletitle" width="30%"><a href="#" onClick="formsort('dis','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_discipline_case");?></a></td>
	<td id="mytabletitle" width="20%"><a href="#" onClick="formsort('act','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_action");?></a></td>
  </tr>


<?php 
		$sql=" SELECT count(*) FROM dis WHERE id>0 $sqlsid $sqlsearch";
        $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
        $row=mysql_fetch_row($res);
        $total=$row[0];
	if(($curr+$MAXLINE)<=$total)
         $last=$curr+$MAXLINE;
    else
    	$last=$total;
   
	$sql="select * from dis where id>0 $sqlsid $sqlsearch $sqlsort limit $curr,$MAXLINE";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res);
	$q=$curr;
	while($row=mysql_fetch_assoc($res)){
		$xid=$row['id'];
    	$dis=$row['dis'];
		$dt=$row['dt'];
		$dt=strtok($dt," ");
		$name=$row['stu_name'];
		$uid=$row['stu_uid'];
		$cls=$row['cls_name'];
		$xsid=$row['sid'];
		$act=$row['act'];
		$rep=$row['rep'];
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>

	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
    <td id="myborder" align="center"><?php echo "$q";?></td>
	<td id="myborder" align="center"><?php echo "$dt";?></td>
	<td id="myborder" align="center"><?php echo "$uid";?></td>
	<td id="myborder"><a href="dis_stu_rec.php?schid=<?php echo "$xsid";?>&uid=<?php echo "$uid";?>" onClick="return GB_showPage('<?php echo addslashes("$lg_discipline_case:$name");?>',this.href)"><?php echo "$name";?></a></td>
	<td id="myborder"><?php echo "$cls";?></td>
	<td id="myborder"><?php echo "$dis";?></td>
	<td id="myborder"><?php echo "$act";?></td>
  </tr>


<?php } ?>
 </table> 
 
	<?php include("../inc/paging.php");?>
</div></div>


	<input name="curr" type="hidden" >
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
</form>
	
</body>
</html>
