<?php
//04/06/10 4.1.0 -  update gui
//28/06/10 4.1.0 -  bug classlevel repaired.
$vmod="v6.0.0";
$vdate="110729";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN');
$username = $_SESSION['username'];

			$op=$_REQUEST['op'];
			$id=$_REQUEST['id'];

			$sql="select * from ses_cls where id='$id'";
            $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sid=$row['sch_id'];
			$cls_name=stripslashes($row['cls_name']);
			$cls_level=$row['cls_level'];
			$usr_name=ucwords(strtolower(stripslashes($row['usr_name'])));
			$usr_uid=$row['usr_uid'];
			$usr_id=$row['usr_id'];
			$year=$row['year'];
            mysql_free_result($res);					  

$sesyear=$year;
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$stype=$row['level'];
		$level=$row['clevel'];
		$issemester=$row['issemester'];
		$startsemester=$row['startsemester'];
		/*if($issemester){
			$xx=$year+1;
			$sesyear="$year/$xx"; 
		}else{
			$sesyear="$year";
		}*/
        
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
		
if($op!=""){
	$sesid=$_POST['id'];
	$subcode=$_POST['subcode'];
	$usruid=$_POST['usruid'];
	
	//restore
	$lvl=$_POST['lvl'];
	$tid=$_POST['tid'];//teacher id
	$sid=$_POST['sid'];
	
	$del=$_POST['del'];
	$usr=$_POST['usr'];
	$op=$_POST['op'];

	if($op=="delete"){
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from ses_sub where id=$del[$i]";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		$f="<font color=blue>&lt;SUCCESSFULY DELETE&gt;</font>";
	}
	elseif($op=="updateteacher"){
		if (count($usr)>0) {
			for ($i=0; $i<count($usr); $i++) {
				$data=$usr[$i];
				$vid=strtok($data,"|");
				$uid=strtok("|");
				
				//get the teacher info
				$sql="select * from usr where uid='$uid'";
				$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		        $row=mysql_fetch_assoc($res);
				$usrname=$row['name'];
				$usrid=$row['id'];
		
				$usrname=addslashes($usrname);
				$sql="update ses_sub set usr_id='$usrid',usr_uid='$uid',usr_name='$usrname',adm='$username',ts=now() where id=$vid";
		      	mysql_query($sql)or die("$sql query failed:".mysql_error());
			}
		}
		$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
	}
	elseif($op=="update"){
		$sql="select sub_id from ses_sub where ses_id='$sesid' and sub_code='$subcode'";
		$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		$num=mysql_num_rows($res);
		if($num>0){
			echo "Sorry. Subject already exist in this class... ";
			echo "<a href=\"#\" onClick=\"history.back();\">GO BACK</a>";
			exit;
		}
		
		//get the teacher info
		$sql="select * from usr where uid='$usruid'";
		$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$usrname=$row['name'];
		$usrid=$row['id'];
		//get the cls info
		$sql="select * from ses_cls where id=$sesid";
		$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$clsname=$row['cls_name'];
		$clscode=$row['cls_code'];
		$clslevel=$row['cls_level'];
		$year=$row['year'];
			
		$sql="select * from sub where sch_id=$sid and code='$subcode' and level='$clslevel'";
		//$sql="select * from sub where sch_id=$sid and code='$subcode'";
		$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$v=$row['id'];
		$sub_name=$row['name'];
		$sub_code=$row['code'];
		$sub_level=$row['level'];
		$sub_grp=$row['grp'];
		$sub_grptype=$row['grptype'];
			
		$usrname=addslashes($usrname);
		$clsname=addslashes($clsname);
		$sub_name=addslashes($sub_name);

		$sql="insert into ses_sub (cdate,ses_id,sub_id,usr_id,year,cls_name,cls_level,usr_uid,usr_name,
					sub_name,sub_code,sub_level,sub_grp,sub_grptype,sch_id,cls_code,adm,ts)
					values(now(),'$sesid','$v','$usrid','$year','$clsname','$clslevel','$usruid','$usrname',
					'$sub_name','$sub_code','$sub_level','$sub_grp','$sub_grptype',$sid,'$clscode','$username',now())";
		mysql_query($sql)or die("$sql - query failed:".mysql_error());

		$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
function process_form(op){
	var ret="";
	var cflag=false;
	document.myform.action="";
	document.myform.target="";
	
	if(op=='delete'){
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
			alert("<?php echo $lg_check_the_item;?>");
			return;
		}
		ret = confirm("<?php echo "Hapus data?";?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	
	}
	else if(op=='updateteacher'){
		ret = confirm("<?php echo "simpan data?";?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}
	else if(op=='update'){
		if(document.myform.subcode.value==""){
			alert("<?php echo $lg_please_select;?>");
			document.getElementById('subcodeerr').style.display = "inline";
			document.myform.subcode.focus();
			return;
		}
		if(document.myform.usruid.value==""){
			alert("<?php echo $lg_please_select;?>");
			document.getElementById('usruiderr').style.display = "inline";
			document.myform.usruid.focus();
			return;
		}
		
		ret = confirm("<?php echo "simpan data?";?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}
}
var newwin = "";
function print_window(url) { 
	if (newwin == "" || newwin.closed || newwin.name == undefined) {
		document.myform.action=url;
		document.myform.target='newwindow';
    	newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
		var a = window.setTimeout("document.myform.submit();",500);
	} else{
    	newwin.focus();
  	}
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo strtoupper("$lg_subject_teacher");?> <?php echo strtoupper($cls_name);?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<form name=myform method=post action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="<?php echo $p;?>">
<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
<input type="hidden" name="op">
<input type="hidden" name="id" value=<?php echo "$id";?>>
<input type="hidden" name="lvl" value=<?php echo "$cls_level";?>>
<input type="hidden" name="sid" value=<?php echo "$sid";?>>
<input type="hidden" name="tid" value=<?php echo "$usruid";?>>


<div id="content">

<div id="mypanel">
<div id="mymenu" align="center">

<a href="#" onClick="show('panelform')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/new.png"><br><?php echo $lg_new;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="process_form('delete')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/delete.png"><br><?php echo $lg_delete;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="javascript: href='../ses/ses_tea_sub.php?id=<?php echo $id?>'" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/help22.png"><br><?php echo $lg_readme;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="window.close()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br><?php echo $lg_close;?></a>
</div>
		<div align="right">
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
</div><!-- end mypanel -->

<div id="story">
<div id="panelform" style="display:none">
<div id="mytitlebg">
	<div id="myclick" onClick="hide('panelform');"><img src="<?php echo $MYLIB;?>/img/icon_minimize.gif" id="mycontrolicon"><?php echo strtoupper("$lg_add $lg_new");?></div>
</div>
<table width="100%">
		<tr>
              <td width="6%"><?php echo $lg_subject;?></td>
			  <td width="1%">:</td>
              <td width="93%">
            <select name="subcode" id="subcode">
<?php	
      		echo "<option value=\"\">- $lg_select $lg_subject -</option>";
			$sql="select * from sub where sch_id=$sid and level='$cls_level' order by grp";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $name=$row['name'];
						$code=$row['code'];
						$grp=$row['grp'];
                        echo "<option value=\"$code\">$grp - $code - $name</option>";
            }
?>
                </select>
				<img src="<?php echo $MYLIB;?>/img/alert14.png" id="subcodeerr" style="display:none">
		 </td>
            </tr>
		<tr>
              <td><?php echo $lg_teacher;?></td>
			  <td>:</td>
              <td>
                <select name="usruid" id="usruid">
            <?php	
      		echo "<option value=\"\">- $lg_select $lg_teacher -</option>";
			$sql="select * from usr where (sch_id=$sid or sch_id=0) order by name";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                       $name=ucwords(strtolower(stripslashes($row['name'])));
						$uid=$row['uid'];
                        echo "<option value=\"$uid\">$uid-$name</option>";
            }	
			?>
                </select>
				<img src="<?php echo $MYLIB;?>/img/alert14.png" id="usruiderr" style="display:none">
			</td>
            </tr>
			
		 	<tr>
              <td></td>
			  <td></td>
              <td><input type="button" value="Save" onClick="process_form('update')"></td>
            </tr>
        
		 </table>

</div>
<div id="mytitle2"><?php echo "$lg_subject_teacher $sesyear $f";?></div>

<table width="100%" id="mytitlebg">		  	
            <tr>
              <td width="6%" ><?php echo $lg_school;?></td>
			  <td width="1%" >:</td>
              <td width="93%"><?php $sname=stripcslashes($sname);echo "$sname";?></td>
            </tr>
			<tr>
              <td><?php echo "$lg_class_teacher";?></td>
			  <td>:</td>
              <td><?php echo "$usr_name";?></td>
            </tr>
			<tr>
              <td><?php echo $lg_class;?></td>
			  <td>:</td>
              <td><?php echo "$cls_name";?></td>
            </tr>
</table>


<div id="tipsdiv" style="display:none ">
<?php if($LG=='BM'){?>
Tips:<br>
1. Penting! Sila pastikan hanya subjek yang perlu sahaja berada didalam kelas ini.<br>
2. Sila tambah subjek jika subjek tiada dalam senarai.Klik AddNew dan tekan SAVE. (pastikan subjek dimasukkan dahulu di menu DAFTAR SUBJEK, baru boleh ditambah disini)<br>
3. Sila DELETE jika subjek itu tidak diajar dalam kelas ini. Tick kan subjek yang hendak di delete, dan tekan butang delete<br>
3. Jika hendak tukar guru subjek, ubah nama guru dan klik butang "kemaskini guru".
<?php }else{?>
Tips:<br>
1. Important! Please make sure that the subject is being teach, other wise please DELETE<br>
2. Please add NEW if subject not exist here. Klik New, select the information and click SAVE. (if subject not exist in the dropdown list please add first at eSchool-Subject Setting)<br>
3. To DELETE, tick the subject first and then press DELETE<br>
3. To change the existing teacher, just choose a new one and click "Update Teacher".
<?php } ?>
</div>

        <table width="100%" cellspacing="0">
          <tr>
            <td class="mytableheader" style="border-right:none;" width="2%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
			<td class="mytableheader" style="border-right:none;" align="center" width="3%"><?php echo strtoupper($lg_no);?></td>
			<td class="mytableheader" style="border-right:none;" align="center" width="5%"><a href="#" onClick="formsort('sub_code','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_code);?></a></td>
            <td class="mytableheader" style="border-right:none;" width="25%"><a href="#" onClick="formsort('sub_name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_subject);?></a></td>
			<td class="mytableheader" style="border-right:none;" width="20%"><a href="#" onClick="formsort('sub_grp','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_group);?></a></td>
            <td class="mytableheader" style="border-right:none;" width="45%"><input type="button" name="Button" value="<?php echo "$lg_update $lg_teacher";?>" onClick="process_form('updateteacher')" <?php //if($cyear>$year) echo "disabled";?>></td>
			</tr>

<?php	
  		$sql="select * from ses_sub where ses_id=$id $sqlsort";
		$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$ses_sub_id=$row['id'];
			$ses_id=$row['ses_id'];
			$sub_id=$row['sub_id'];
			$sub_code=$row['sub_code'];
			$usr_id=$row['usr_id'];
			$usr_name=ucwords(strtolower(stripslashes($row['usr_name'])));
			$usr_uid=$row['usr_uid'];
			$sub_name=$row['sub_name'];
			$sub_grp=$row['sub_grp'];
			if(($q++%2)==0)
					$bg="#FAFAFA";
			else
					$bg="";
							
?>

<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
            <td class="myborder" style="border-right:none; border-top:none;" width="2%"><input type=checkbox name=del[] value="<?php echo "$ses_sub_id";?>" onClick="check(0)"></td>
			<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
			<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$sub_code";?></td>
            <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$sub_name";?></td>
			<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$sub_grp";?></td>
            <td class="myborder" style="border-right:none; border-top:none;">
			
			<?php
				echo "<select name=\"usr[]\">";
				echo "<option value=\"$ses_sub_id|$usr_uid\">$usr_uid $usr_name</option>";
						$sql="select * from usr where (sch_id=$sid or sch_id=0) and uid!='$usr_uid' and status=0 order by name";
						$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
						while($row2=mysql_fetch_assoc($res2)){
									$name=ucwords(strtolower(stripslashes($row2['name'])));
									$uid=$row2['uid'];
									$v=$row2['id'];
									echo "<option value=\"$ses_sub_id|$uid\">$uid $name</option>";
						}
				echo "</select>";
			?>
			
			</td>
			</tr>
		
<?php } ?>

</table>

 

 </div></div>
</form> 

</body>
</html>
