<?php
$vdate="110626";
$vmod="v6.1.0";

include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
	$adm = $_SESSION['username'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
	
	$curryear=date('Y');
	if($curryear==$year)
		$sqlstatuspelajar="and stu.status=6";
		
		
	
	if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql  failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $schname=$row['name'];
			$sname=$row['sname'];
			$stype=$row['level'];
			$level=$row['clevel'];
			$schlevel=$row['lvl'];
            mysql_free_result($res);	  
	}
	$cls=$_REQUEST['clscode'];
	if($cls!=""){
			$sql="select * from ses_cls where sch_id=$sid and cls_code='$cls'";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $lvl=$row['cls_level'];
			$clsname=stripslashes($row['cls_name']);
	}
	
	$sub=$_REQUEST['subcode'];
	$sql="select * from ses_sub where sub_code='$sub' and cls_level='$lvl' and sch_id=$sid and year='$year' and sch_id=$sid";
    $res=mysql_query($sql)or die("$sql failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$subname=stripslashes($row['sub_name']);
	
	//$newbook=$_REQUEST['newbook'];
	//$type=$_REQUEST['type'];
	$dt=$_REQUEST['dt'];
	if($dt=="")
		$dt=date('Y-m-d');
	//$due=$_REQUEST['due'];
	//if($due=="")
		//$due=date('Y-m-d');
	$title=$_REQUEST['title'];
	$obj=$_REQUEST['obj'];
	$skill=$_REQUEST['skill'];
	$xid=$_REQUEST['xid'];
	$bahanbantu=$_REQUEST['bahanbantu'];
	$nilai=$_REQUEST['nilai'];
	
	
	$op=$_REQUEST['op'];
	/*if($op=='savebook'){
		$sql="insert into hwork_book(dt,uid,sid,book,subcode,adm,ts)values(now(),'$adm','$sid','$newbook','$sub','$adm',now())";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$book=$newbook;
	}*/
	if($op=='save'){
		$subname=addslashes($subname);
		if($xid>0){
				$sql="update elesson set dt='$dt', title='$title', obj='$obj', skill='$skill', nilai='$nilai', bahanbantu='$bahanbantu' where id=$xid";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		}else{
				$sql="insert into elesson(dt,title,cls,level,sub,subname,obj,uid,sid,skill,nilai,bahanbantu,year)
								values('$dt','$title','$cls','$lvl','$sub','$subname','$obj','$adm','$sid','$skill','$nilai','$bahanbantu','$year')";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				$xid=mysql_insert_id($link);
				
				
				/*for ($i=0; $i<count($stulist); $i++) {
						$xuid=$stulist[$i];
						$sql="insert into hwork_stu(dt,uid,sid,xid,adm,ts)values(now(),'$xuid','$sid','$xid','$adm',now())";
						$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				}*/
		}
		
		$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
	}
	
if($xid>0){
	$sql="select * from elesson where id=$xid";
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$year=$row['year'];
	$sub=$row['sub'];
	$cls=$row['cls'];
	$sid=$row['sid'];
	$title=$row['title'];
	$obj=$row['obj'];
	$skill=$row['skill'];
	$nilai=$row['nilai'];
	$bahanbantu=$row['bahanbantu'];
	
	$sql="select * from ses_sub where sub_code='$sub' and cls_code='$cls' and sch_id=$sid and year='$year' and sch_id=$sid";
    $res=mysql_query($sql)or die("$sql failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$subname=stripslashes($row['sub_name']);
}
	
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="asc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by sex desc, stu.name";
	else
		$sqlsort="order by $sort $order, name asc";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<SCRIPT LANGUAGE="JavaScript">

function process_myform(op,xid){
	var cflag=false;
	if(document.myform.title.value==""){
    	alert("Please Insert The Title");
        document.myform.title.focus();
        return;
	}
	if(document.myform.obj.value==""){
    	alert("Please Insert The Objective");
        document.myform.obj.focus();
        return;
	}
	

	ret = confirm("Save the record ??");
    if (ret == true){
		document.myform.op.value=op;
        document.myform.submit();
    }
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php include("$MYOBJ/datepicker/dp.php")?>
<title>ISIS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>


<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
    <input type="hidden" name="op">
	<input name="year" type="hidden" value="<?php echo $year;?>">
	<input name="sid" type="hidden" value="<?php echo $sid;?>">
	<input name="clscode" type="hidden" value="<?php echo $cls;?>">
	<input name="subcode" type="hidden" value="<?php echo $sub;?>">
    <input name="xid" type="hidden" value="<?php echo $xid;?>">
	


<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="<?php echo "../elesson/elesson.php?year=$year&sid=$sid&clscode=$cls&subcode=$sub";?>" id="mymenuitem"><img src="../img/goback.png"><br>Back</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>    
		<a href="#" onClick="process_myform('save','<?php echo $xid;?>')" id="mymenuitem"><img src="../img/save.png"><br><?php echo $lg_save;?></a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br><?php echo $lg_print;?></a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br><?php echo $lg_refresh;?></a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="../img/help22.png"><br><?php echo $lg_readme;?></a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div> 
	</div>
	<div align=right>
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>
<br>

	 
	</div>
</div>
<div id="story">

	<div id="mytitle2"><?php echo $lg_teaching_lesson;?> : <?php echo $subname;?> <?php echo $f;?></div>
    
<table width="50%" id="mytitlebg" style="font-size:11px" cellpadding="2" cellspacing="0">
 	<tr>
    	<td><?php echo $lg_date;?></td>
        <td>:</td>
        <td width="75%"><input type="text" name="dt" size="25" readonly onClick="displayDatePicker('dt');" value="<?php echo $dt;?>"></td>
    </tr>
    <tr>
    	<td width="24%"><?php echo "Judul"?></td>
        <td width="1%">:</td>
        <td><input type="text" name="title" size="50" value="<?php echo $title; ?>"></td>
    </tr>
    <tr>
    	<td><?php echo "Objektif";?></td>
        <td>:</td>
        <td><textarea name="obj" cols="35" ><?php echo $obj; ?></textarea></td>
    </tr>    
   
    <tr>
    	<td><?php echo $lg_skill;?></td>
        <td>:</td>
        <td><input type="text" name="skill" size="50" value="<?php echo $skill; ?>"></td>
    </tr>    
    <tr>
    	<td><?php echo $lg_nilai;?></td>
        <td>:</td>
        <td><input type="text" name="nilai" size="50" value="<?php echo $nilai; ?>"></td>
    </tr> 
    <tr>
    	<td><?php echo $lg_bahanbantu;?></td>
        <td>:</td>
        <td><input type="text" name="bahanbantu" size="30" value="<?php echo $bahanbantu; ?>"></td>
    </tr>
<!--    
	<tr>
    	<td>Marks</td>
        <td>:</td>
        <td><input type="text" name="mark" size="30"></td>
    </tr>
-->    
	<!--<tr>
    	<td colspan="3"><?php echo $lg_assign_to;?> : </td>
    </tr>-->
</table>



<!--<table width="100%" cellspacing="0"style="font-size:10px">
<tr>
<?php if($xid==""){?>
		<td class="mytableheader" style="border-right:none;" width="1%" class="printhidden"><input type=checkbox name=checkall value="0" onClick="checkbox_checkall(1,'cblist')"></td>
<?php } ?>        
		<td id="mytabletitle" width="2%" align="center"><?php echo strtoupper($lg_no);?></td>
		<td id="mytabletitle" width="4%" align="center"><a href="#" onClick="formsort('stu.uid','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_matric);?></a></td>
		<td id="mytabletitle" width="4%" align="center"><a href="#" onClick="formsort('stu.sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>	
		<td id="mytabletitle" width="90%"><a href="#" onClick="formsort('stu.name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></strong></td>
</tr>

<?php
		$q=0;
		if($xid>0)
			$sql="select hwork_stu.*,stu.uid,stu.sex,stu.name,stu.status from hwork_stu INNER JOIN stu ON hwork_stu.uid=stu.uid where stu.sch_id=$sid and hwork_stu.xid='$xid' $sqlstatuspelajar $sqlsort";
		else
			$sql="select ses_stu.*,stu.uid,stu.sex,stu.name,stu.status from ses_stu INNER JOIN stu ON ses_stu.stu_uid=stu.uid where stu.sch_id=$sid and ses_stu.cls_code='$cls' and ses_stu.year='$year' $sqlstatuspelajar $sqlsort";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$stuname=strtoupper(stripslashes($row['name']));
			$uid=$row['uid'];
			$sex=$row['sex'];

			if($q++%2==0)
				$bg="";
			else
				$bg="#FAFAFA";
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
<?php if($xid==""){?>        
			<td class="myborder" style="border-right:none; border-top:none;" align="center">
            	<input type="checkbox" name="cblist[]" id="cblist" value="<?php echo "$uid";?>" onClick="checkbox_checkall(0,'cblist')"></td>  
<?php } ?>                      
    		<td id="myborder" align="center"><?php echo "$q";?></td>
			<td id="myborder" align="center"><?php echo "$uid";?></td>
			<td id="myborder" align="center"><?php echo $lg_sexmf[$sex];?></td>
			<td id="myborder"><?php echo "$stuname";?></td>
        </tr>
        
<?php } ?>


</table>-->

</div> <!-- story -->
</div> <!-- content -->

</form>

</body>
</html>
