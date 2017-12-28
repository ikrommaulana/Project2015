<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['sname'];
           
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
			
		$uid=$_REQUEST['uid'];
		if($uid!=""){
			$sql="select * from stu where sch_id=$sid and uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $xname=$row['name'];
			$xic=$row['ic'];
			$file=$row['file'];
			
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2))
				$cname=$row2['cls_name'];
		}
		$lvl=$_REQUEST['lvl'];
		$blk=$_REQUEST['blk'];
		$rom=$_REQUEST['rom'];
		$bed=$_REQUEST['bed'];
		$op=$_REQUEST['op'];

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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
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

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="hos_rep_bil">
	<input type="hidden" name="isprint" value="<?php echo $isprint;?>">
	<input type="hidden" name="op">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="lvl" value="<?php echo $lvl;?>">
	<input type="hidden" name="blk" value="<?php echo $blk;?>">
	<input type="hidden" name="rom" value="<?php echo $rom;?>">
	<input type="hidden" name="bed">
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
</div>
<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
</div> <!-- end mypanel -->
<div id="mytabletitle" class="printhidden" align="right" >
<a href="#" title="<?php echo $vdate;?>"></a><br>

	    <select name="sid" id="sid" onchange="document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_school -</option>";
			else
                echo "<option value=$sid>$sname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
			}									  
			
?>
  </select>

    <input type="button" name="Submit" value="View" onClick="document.myform.submit();" >
</div>
<div id="story">

<div id="story_title"><?php echo strtoupper($lg_hostel);?> : <?php echo strtoupper($sname);?></div>


<?php
	$sql="select distinct(block),name from hos where (sid=0 or sid=$sid) order by block";
	$res3=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	while($row3=mysql_fetch_assoc($res3)){
		$block=$row3['block'];
		$name=$row3['name'];
		$nobilik=0;
?>
<div id="story_title"><?php echo strtoupper($lg_block);?> - &nbsp;<?php echo "$block - $name";?></div>
<table width="100%"  id="mytable" style="font-size:150% ">
<?php
	$sql="select * from hos where (sid=0 or sid=$sid) and block='$block' order by block, level";
	$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$bk=$row['block'];
		$noroom=$row['noroom'];
		$nostu=$row['nostu'];
		$level=$row['level'];
		$usedroom=0;
		$totalroom=$noroom*$nostu;
?>
	<tr>
		<td width="10%" id="mytabletitle" align="center" style="border:1px solid #666666 "><?php echo $lg_level;?> - &nbsp;<?php echo $level?></td>
<?php for($i=1;$i<=$noroom;$i++){
        $nobilik++;
		$sql="select count(*) from hos_room where (sid=0 or sid=$sid) and block='$bk' and level=$level and roomno=$nobilik and sta>0 order by block, level";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$ret=$row2[0];
		if($ret==0)
			$bg="";
		elseif($ret<$nostu)
			$bg=$bgkuning;
		else
			$bg=$bglred;
		$usedroom=$usedroom+$ret;
?>
		<td width="50" id="myborder" style="border:1px solid #333333 " bgcolor="<?php echo $bg;?>" align="center">
			<a href="hos_stu_view.php?<?php echo "sid=$sid&uid=$uid&rom=$nobilik&lvl=$level&blk=$bk"; ?>"  title="STATUS BILIK" rel="gb_page_center[1000, 480]" target="_blank">
				<?php echo "$nobilik<br>($ret/$nostu)"?>
			</a>
		</td>
<?php }//level?>
	<td width="50" id="mytabletitle" align="center" style="border:1px solid #666666 "><?php echo "$usedroom/$totalroom";?></td>
	</tr>
<?php }//block?>
</table>
<?php } //whole block ?>
</div></div>

</form> <!-- end myform -->


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->