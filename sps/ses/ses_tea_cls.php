<?php
//04/06/10 4.1.0 -  update gui
$vmod="v6.0.0";
$vdate="110729";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN');
$username = $_SESSION['username'];

		$f=$_REQUEST['f'];
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$stype=$row['level'];
			$level=$row['clevel'];
			$issemester=$row['issemester'];
			$startsemester=$row['startsemester'];
            mysql_free_result($res);					  
		}else
			$level="Level";
			
		$year=$_REQUEST['year'];
		if($year==""){
			$year=date('Y');
			if(($issemester)&&(date('n')<$startsemester))
				$year=$year-1;
		
		}
		//echo $year;
		/*if($issemester){
			$xx=$year+1;
			$year="$year/$xx"; 
		}else{
			$year="$year";
		}*/
		
		
$op=$_REQUEST['op'];
if($op!=""){

	$id=$_POST['id'];
	$clscode=$_POST['clscode'];
	$usruid=$_POST['usr_id'];
	$del=$_POST['del'];
	$usr=$_POST['usr'];
	$op=$_POST['op'];

	if($op=="delete"){
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from ses_cls where id=$del[$i]";
		      	mysql_query($sql)or die("query failed:".mysql_error());
				$sql="delete from ses_sub where ses_id=$del[$i]";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		$f="<font color=blue>&lt;SUCCESSFULY DELETE&gt;</font>";
	}
	elseif($op=="updateteacher"){
		if (count($usr)>0) {
			for ($i=0; $i<count($usr); $i++) {
				$data=$usr[$i];
				$sesid=strtok($data,"|");
				$uid=strtok("|");
				
				$sql="select * from usr where uid='$uid'";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
        		$row=mysql_fetch_assoc($res);
				$usrname=$row['name'];
				$usrname=addslashes($usrname);
				$usrid=$row['id'];
				$sql="update ses_cls set usr_id='$usrid',usr_name='$usrname',usr_uid='$uid',adm='$username',ts=now() where id=$sesid";
		      	mysql_query($sql)or die("$sql - query failed:".mysql_error());
				
			}
		}

		$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
	}	
	else{
		$sql="select * from cls where sch_id=$sid and code='$clscode'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$clsname=$row['name'];
		$clsid=$row['id'];
		$clsname=addslashes($clsname);
		$clslevel=$row['level'];
		
		$sql="select cls_name from ses_cls where cls_name='$clsname' and sch_id=$sid and year='$year'";
		$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		$num=mysql_num_rows($res);
		if($num>0){
			echo "Sorry. Class '$clsname' already exist for sesion '$year'... ";
			echo "<a href=\"#\" onClick=\"history.back();\">GO BACK</a>";
			exit;
		}
		
		$sql="select cls_name from ses_cls where cls_code='$clscode' and sch_id=$sid and year='$year'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$num=mysql_num_rows($res);
		if($num>0){
			echo "Sorry. Class Code '$clscode' already exist for sesion '$year'... ";
			echo "<a href=\"#\" onClick=\"history.back();\">GO BACK</a>";
			exit;
		}
		
		$sql="select * from usr where uid='$usruid'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$usrname=addslashes($row['name']);
		$usrid=$row['id'];
		
		$sql="insert into ses_cls(cdate,sch_id,cls_id,usr_id,year,cls_name,cls_code,cls_level,usr_uid,usr_name,adm,ts) 
			values (now(),'$sid','$clsid','$usrid','$year','$clsname','$clscode','$clslevel','$usruid','$usrname','$username',now())";
		mysql_query($sql)or die("$sql - query failed:".mysql_error());
		$id=mysql_insert_id();
		$sql="select * from sub where sch_id='$sid' and level='$clslevel'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
				$v=$row['id'];
				$sub_name=$row['name'];
				$sub_name=addslashes($sub_name);
				$sub_code=$row['code'];
				$sub_level=$row['level'];
				$sub_grp=$row['grp'];
				$sub_grptype=$row['grptype'];
				$sql="insert into ses_sub (cdate,ses_id,sub_id,usr_id,
					year,cls_name,cls_level,cls_code,usr_uid,usr_name,sub_name,sub_code,sub_level,sub_grp,sub_grptype,sch_id,adm,ts)
					values(now(),'$id','$v','$usrid','$year','$clsname','$clslevel','$clscode','$usruid','$usrname',
					'$sub_name','$sub_code','$sub_level','$sub_grp','$sub_grptype',$sid,'$username',now())";
				//echo "$sql<br>";
				mysql_query($sql)or die("$sql - query failed:".mysql_error());
        }
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
		ret = confirm("<?php echo "Hapus Data ?";?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	
	}
	else if(op=='updateteacher'){
		ret = confirm("<?php echo "Simpan Data ?";?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}
	else if(op=='update'){
		if(document.myform.sid.value=="0"){
			alert("<?php echo $lg_please_select;?> <?php echo $lg_school;?>");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.year.value==""){
			alert("<?php echo $lg_please_select;?>");
			document.getElementById('yearerr').style.display = "inline";
			document.myform.year.focus();
			return;
		}
		if(document.myform.clscode.value==""){
			alert("<?php echo $lg_please_select;?>");
			document.getElementById('clscodeerr').style.display = "inline";
			document.myform.clscode.focus();
			return;
		}
		ret = confirm("<?php echo "Simpan data?";?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}else{
		document.myform.op.value='';
		document.myform.submit();
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
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body >
 
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
	<input type="hidden" name="op">

<div id="panelleft"> 
	<?php include('inc/mymenu.php');?>
</div><!--end pageNav-->
<div id="content2">
<div id="mypanel">
		<div id="mymenu" align="center">
				<a href="#" onClick="show('panelform');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/new.png"><br><?php echo $lg_new;?></a>
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
				<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
				<div id="mymenu_seperator"></div>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/help22.png"><br><?php echo $lg_readme;?></a>
		</div>

		<div align="right">
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
				<select name="sid" id="sid" onchange="document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
		//	else
                //echo "<option value=$sid>$sname</option>";
		//	if($_SESSION['sid']==0){
				$sql="select * from sch where id>0 order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$s=stripcslashes($s);
							$t=$row['id'];
							if($t==$sid){$selected="selected";}else{$selected="";}
							echo "<option value=$t $selected>$s</option>";
				}
				mysql_free_result($res);
		//	}									  
			
?>
        </select>

		<select name="year" id="year" onchange="document.myform.submit();">
<?php
            //echo "<option value=$year>$lg_session $sesyear</option>";
		//$sql="select * from type where grp='session' and prm!='$year' order by val desc";
		$sql="select * from type where grp='session' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
			$v=$row['val'];
                        /*$xx=$s+1;
						if($issemester)
								$xsesyear="$s/$xx";
						else
								$xsesyear=$s;
			*/
			if($s==$year){$selected="selected";}else{$selected="";}
			echo "<option value=\"$s\" $selected>$lg_session $s</option>";
			//}
            }
            mysql_free_result($res);					  
?>
		</select>
		<img src="<?php echo $MYLIB;?>/img/alert14.png" id="yearerr" style="display:none">
		
	</div>

</div> <!--end of mypanel-->

<div id="story">
<div id="panelform" style="display:none ">

<div id="mytitlebg">
	<div id="myclick" onClick="hide('panelform');"><img src="<?php echo $MYLIB;?>/img/icon_minimize.gif" id="mycontrolicon"><?php echo "$lg_add_newclass";?></div>&nbsp;
</div>
<table width="100%">
  <tr>
		<td width="10%"><?php echo $lg_class;?></td>
		<td>:</td>
		<td>
		<select name="clscode">
<?php	
      		echo "<option value=\"\">- $lg_select $lg_class $sesyear -</option>";
			$sql="select * from cls where sch_id=$sid order by level, name";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                $a=$row['name'];
				$b=$row['code'];
				echo "<option value=\"$b\">$a</option>";
            }
?>
		</select>
		<img src="<?php echo $MYLIB;?>/img/alert14.png" id="clscodeerr" style="display:none">
	</td>
  </tr>
  <tr>
    <td><?php echo "$lg_teacher $lg_class";?></td>
    <td>:</td>
    <td>
	<select name="usr_id" id="usr_id">
            <?php	
      		echo "<option value=\"\">- $lg_select_teacherforthisclass -</option>";
			$sql="select * from usr where (sch_id=$sid or sch_id=0) and status=0 order by name";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$name=ucwords(strtolower(stripslashes($row['name'])));
				$uid=$row['uid'];
                echo "<option value=\"$uid\">$uid $name</option>";
            }
            mysql_free_result($res);	
			?>
                </select>
			<img src="<?php echo $MYLIB;?>/img/alert14.png" id="usr_iderr" style="display:none">
	</td>
  </tr>
  <tr>
              <td></td>
			  <td></td>
              <td><input type="button" value="Save" onClick="process_form('update')"></td>
            </tr>
</table>

</div>

<div id="tipsdiv" style="display:none ">
<?php if($LG=='BM'){?>
Tips:<br>
1. Penting! Setting kelas, guru kelas dan guru kelas perlu dibuat sekali setahun.<br>
2. Sila Klik NEW dan tekan SAVE untuk cipta kelas pada sesi pengajian ini<br>
3. Untuk DELETE  Tick kan kelas yang hendak di delete, dan tekan butang delete<br>
3. Jika hendak tukar guru kelas, ubah nama guru dan klik butang "kemaskini guru".
<?php }else{?>
Tips:<br>
1. Penting! Setting kelas, guru kelas dan wali kelas perlu dibuat sekali setahun. <br>
2. Silahkan klik NEW dan tekan SAVE untuk membuat kelas pada sesi tahun ajaran ini <br>
3. Untuk DELETE klik kelas yang hendak di delete, dan tekan tombol delete <br>
4. Jika ingin mengubah guru kelas, ubah nama guru dan klik  tombol "perbaruan wali kelas".
<?php }?>
</div>
<div id="mytitle2"><?php echo "$lg_class_teacher $lg_session";?> <?php echo $sesyear;?> <?php echo $f;?></div>
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td class="mytableheader" style="border-right:none;" width="2%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
			<td class="mytableheader" style="border-right:none;" align="center" width="2%"><?php echo strtoupper($lg_no);?></td>
            <td class="mytableheader" style="border-right:none;" align="center" width="5%"><?php echo strtoupper($lg_session);?></td>
            <td class="mytableheader" style="border-right:none;" width="20%">&nbsp;&nbsp;<?php echo strtoupper($lg_class);?></td>
            <td class="mytableheader" style="border-right:none;" width="45%">&nbsp;&nbsp;<?php echo strtoupper("$lg_class_teacher");?></td>
			<td class="mytableheader" style="border-right:none;" align="center" width="10%"><?php echo strtoupper("$lg_subject_teacher");?></td>
			<td class="mytableheader" style="border-right:none;" align="center" width="10%"><?php echo strtoupper($lg_student);?></td>
          </tr>

<?php	
  		$sql="select * from ses_cls where sch_id=$sid and year='$year' order by cls_level, cls_name";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$ses_id=$row['id'];
			$yr=$row['year'];
			$sch_id=$row['sch_id'];
			$cls_id=$row['cls_id'];
			$usr_id=$row['usr_id'];
			$usr_name=ucwords(strtolower(stripslashes($row['usr_name'])));
			$usr_uid=$row['usr_uid'];
			$cls_name=stripslashes($row['cls_name']);
			$cls_code=$row['cls_code'];
			$cls_level=$row['cls_level'];
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
            <td class="myborder" style="border-right:none; border-top:none;" width="1%"><input type=checkbox name=del[] value="<?php echo "$ses_id";?>" onClick="check(0)"></td>
			<td class="myborder" style="border-right:none; border-top:none;" width="5%" align="center"><?php echo "$q";?></td>
            <td class="myborder" style="border-right:none; border-top:none;" width="5%" align="center"><?php echo "$year";?></td>
            <td class="myborder" style="border-right:none; border-top:none;" width="20%"><?php echo "$cls_name";?></td>
            <td class="myborder" style="border-right:none; border-top:none;" width="45%">	
<?php
				echo "<select name=\"usr[]\">";
				echo "<option value=\"$ses_id|$usr_uid\">$usr_uid $usr_name</option>";
				$sql="select * from usr where status=0 and id!='$usr_id' and (sch_id=$sid or sch_id=0) order by name";
				$res2=mysql_query($sql)or die("query failed:".mysql_error());
				while($row2=mysql_fetch_assoc($res2)){
							$name=ucwords(strtolower(stripslashes($row2['name'])));
							$uid=$row2['uid'];
							echo "<option value=\"$ses_id|$uid\">$uid $name</option>";
				}
				echo "</select>";
?>
			</td>
			<td class="myborder" style="border-right:none; border-top:none;" width="10%" align="center"><?php echo "<a href=\"../ses/ses_tea_sub.php?id=$ses_id\" target=_blank>$lg_view</a>";?></td>
			<td class="myborder" style="border-right:none; border-top:none;" width="10%" align="center"><?php echo "<a href=\"../ses/sesstu.php?year=$year&clscode=$cls_code&sid=$sid\" target=_blnk>$lg_view</a>";?></td>
          </tr>
<?php } ?>


	<tr>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td> 
	<input type="button" name="Button" value="<?php echo "$lg_update $lg_class_teacher";?> <?php echo $year?>" onClick="process_form('updateteacher')" <?php //if($cyear>$year) echo "disabled";?>>
	</td>
	<td></td>
	<td></td>
	</tr>



	
    </table>     


</div></div>
</form>
</body>
</html>
