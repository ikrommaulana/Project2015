<?php
//110610 - upgrade gui
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
$adm=$_SESSION['username'];
$f=$_REQUEST['f'];
$p=$_REQUEST['p'];
$edit=$_REQUEST['edit'];
$ic=$_REQUEST['ic'];

$sid=$_REQUEST['sid'];
if($sid=="")
		$sid=$_SESSION['sid'];
		
$refreshparent=$_REQUEST['refreshparent'];
				
		$sql="select * from sch where id='$sid'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sname=$row['name'];
		$stype=$row['level'];
		$level=$row['clevel'];
		mysql_free_result($res);					  
		
		$op=$_REQUEST['op'];

if($op=="delete"){
		$sql="update stu_summary set isdel=1,delby='$adm',delts=now() where id='$edit'";
		mysql_query($sql)or die("query failed:".mysql_error());		
		$f="<font color=blue>&lt;RECORD DELETED&gt;</font>"; 
		$edit=0; 
}
if($op=="save"){
		$edit=$_REQUEST['edit'];
		$type=$_REQUEST['type'];
		$ic=$_REQUEST['ic'];
		$sid=$_REQUEST['sid'];
		
		$cls=$_REQUEST['cls'];
		$lvl=$_REQUEST['lvl'];
		$sem=$_REQUEST['sem'];
		$year=$_REQUEST['year'];
		
		$jumnilai=$_REQUEST['jumnilai'];
		$nilairata=$_REQUEST['nilairata'];
		$ratakelas=$_REQUEST['ratakelas'];
		$peringkat=$_REQUEST['peringkat'];

			
		if($edit>0){
			$sql="update stu_summary set year='$year',lvl='$lvl',sem='$sem',cls='$cls',
				rem1='$jumnilai',rem2='$nilairata',rem3='$ratakelas',rem4='$preingkat',adm='$adm',ts=now() where id=$edit";
		}else{
			$sql="insert into stu_summary (type,sid,ic,year,lvl,sem,cls,rem1,rem2,rem3,rem4,adm,ts)
				values('EXAM','$sid','$ic','$year','$lvl','$sem','$cls','$jumnilai','$nilairata','$ratakelas','$peringkat','$adm',now())";
		}
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		$edit=mysql_insert_id($res);
		$f="<font color=blue>&lt;SUCCESSFULY SAVE&gt;</font>";		
}
if($edit>0){
			$sql="select * from stu_summary where id='$edit'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
  			$row=mysql_fetch_assoc($res);
			$xid=$row['id'];
			$year=$row['year'];
			$cls=$row['cls'];
			$lvl=$row['lvl'];
			$sem=$row['sem'];
			$jumnilai=$row['rem1'];
			$nilairata=$row['rem2'];
			$ratakelas=$row['rem3'];
			$peringkat=$row['rem4'];
}

/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=25;
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
		$sqlsort="order by $sort $order, id desc";
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php include("$MYOBJ/datepicker/dp.php")?>
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

<script language="javascript">
function process_new(){
	document.myform.code.value="";
	document.myform.item.value="";
	document.myform.val.value="";
	document.myform.edit.value="";
}
function process_form(action){
	var ret="";
	var cflag=false;
	
	if(action=='save'){
		document.myform.op.value=action;
		document.myform.submit();		
		return;
	}
	if(action=='delete'){
		ret = confirm("Are you sure want to DELETE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
}

</script>

</script>

</head>

<body >

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
 	<input type="hidden" name="p" value="<?php echo $p;?>">
 	<input type="hidden" name="op">
	<input type="hidden" name="xid">
	<input type="hidden" name="edit" value="<?php echo $edit;?>">
    <input type="hidden" name="ic" value="<?php echo $ic;?>">
    <input type="hidden" name="sid" value="<?php echo $sid;?>">


<div id="content" style="min-height:100px">
<div id="mypanel">
		<div id="mymenu" align="center">
        		<?php if($edit!=""){?>
				<a href="#" onClick="process_form('delete');" id="mymenuitem" ><img src="<?php echo $MYLIB;?>/img/delete.png"><br>Delete</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
                <?php } ?>
				<a href="#" onClick="process_form('save')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br>Save</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="<?php if($f!=""){?>parent.document.myform.submit();<?php }?>window.close();parent.$.fancybox.close();"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br>Close</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>                    
		</div> <!-- end mymenu -->

		<div align="right"  >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
</div> <!-- end mypanel -->
<div id="story" style="min-height:none;">
<div id="mytitle2">Kehadiran <?php echo $f;?></div>

				<table width="100%" id="mytitle" cellspacing="0" cellpadding="2px" style="font-size:11px">
                	<tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom" width="30%">Tahun</td>
                        <td width="80%"><input name="year" type="text" value="<?php echo $year;?>"></td>
				  	</tr>
                    <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom">Level</td>
                        <td width="80%"><input name="lvl" type="text" value="<?php echo $lvl;?>"></td>
				  	</tr>
                    <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom">Kelas</td>
                        <td width="80%"><input name="cls" type="text" value="<?php echo $cls;?>"></td>
				  	</tr>
                    <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom">Semester</td>
                        <td width="80%"><input name="sem" type="text" value="<?php echo $sem;?>"></td>
				  	</tr>
                    <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom">Jumlah Nilai</td>
                        <td width="80%"><input name="jumnilai" type="text" value="<?php echo $jumnilai;?>"></td>
				  	</tr>
 
                	 <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom">Nilai Rata-Rata</td>
                        <td><input name="nilairata" type="text" value="<?php echo $nilairata;?>"></td>
				  	</tr> 
				    <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom">Rata-Rata Kelas</td>
                        <td><input name="ratakelas" type="text" value="<?php echo $ratakelas;?>"></td>
				  	</tr>                                    
				    <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom">Peringkat</td>
                        <td><input name="peringkat" type="text" value="<?php echo $peringkat;?>"></td>
				  	</tr>               
		</table>
</div><!-- story -->
</div><!-- content -->
</form>	
</body>
</html>
