<?php
//THIS IS CMS VERSION
//22/04/2010 - update view by tahap
//02/07/2010 - update interface
//22/07/2010 - only root can edit
//120611 - add prasayarat option $SUBJECT_PRASYARAT_ENABLE
$vmod="v6.2.0";
$vdate="120611";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN');
$username = $_SESSION['username'];
$f=$_REQUEST['f'];
$op=$_REQUEST['op'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
			
		$namatahap=$lg_level;
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $schname=$row['name'];
			$stype=$row['level'];
			$namatahap=$row['clevel'];
            mysql_free_result($res);					  
		}
		$clslevel=$_REQUEST['clslevel'];
		if($clslevel==""){
			$sql="select * from type where grp='classlevel' and sid='$sid' order by prm";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clslevel=$row['prm'];			  
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
		
if($op!=""){
	$addclslevel=$_POST['addclslevel'];
	$sub=$_POST['sub'];
	$del=$_POST['del'];
	$cod=$_POST['cod'];
	$sname=$_POST['sname'];
	$credit=$_POST['credit'];
	if($credit=="")
		$credit=0;
	$grade=$_POST['grade'];
	$grading=$_POST['grading']; //update grading
	$data=$_POST['grp'];
	$grp=strtok($data,"|");
	$gtype=strtok("|");
	$op=$_POST['op'];

	if($op=="delete"){
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
				$id=$del[$i];
				$sql="select * from sub where id='$id'";
	            $res=mysql_query($sql)or die("query failed:".mysql_error());
    	        $row=mysql_fetch_assoc($res);
        	    $sid=$row['sch_id'];
				$clslevel=$row['level'];
				
		      	$sql="delete from sub where id=$id";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
	}
	elseif($op=="updategrading"){
		if (count($grading)>0) {
			for ($i=0; $i<count($grading); $i++) {
				$data=$grading[$i];
				$id=strtok($data,"|");
				$x=strtok("|");
				$sql="select * from sub where id='$id'";
	            $res=mysql_query($sql)or die("query failed:".mysql_error());
    	        $row=mysql_fetch_assoc($res);
        	    $sid=$row['sch_id'];
				$clslevel=$row['level'];
				
		      	$sql="update sub set grading='$x',adm='$username',ts=now() where id=$id";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
	}		
	elseif($op=="update"){
		$sql="select name from sub where code='$cod' and level='$addclslevel' and sch_id=$sid";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$num=mysql_num_rows($res);
		if($num>0){
			echo "Sorry. Subject code '$cod' level='$clslevel' already exist";
			exit;
		}
		$sql="insert into sub(name,level,sch_id,code,grp,grptype,grading,sname,credit,adm,ts) values ('$sub','$addclslevel',$sid,'$cod','$grp',$gtype,'$grade','$sname',$credit,'$username',now())";
		mysql_query($sql)or die("query failed:".mysql_error());
		$id=mysql_insert_id();
		$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
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
	if(op=='updategrading'){
		ret = confirm("Save the grading set??");
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
		if(document.myform.addclslevel.value==""){
			alert("<?php echo $lg_please_select;?>");
			document.getElementById('addclslevelerr').style.display = "inline";
			document.myform.addclslevel.focus();
			return;
		}
		if(document.myform.grp.value==""){
			alert("<?php echo $lg_please_select;?>");
			document.getElementById('grperr').style.display = "inline";
			document.myform.grp.focus();
			return;
		}
		if(document.myform.sub.value==""){
			alert("<?php echo $lg_please_enter_the_information;?>");
			document.getElementById('suberr').style.display = "inline";
			document.myform.sub.focus();
			return;
		}
		if(document.myform.cod.value==""){
			alert("<?php echo $lg_please_enter_the_information;?>");
			document.getElementById('coderr').style.display = "inline";
			document.myform.cod.focus();
			return;
		}
		if(document.myform.grade.value==""){
			alert("<?php echo $lg_please_select;?>");
			document.getElementById('gradeerr').style.display = "inline";
			document.myform.grade.focus();
			return;
		}
		ret = confirm("<?php echo $lg_confirm_save;?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}
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
		ret = confirm("<?php echo $lg_confirm_delete;?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;
	}
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />

</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="<?php echo $p;?>">
<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
<input type="hidden" name="op" value="">

<div id="panelleft"> 
	<?php include('inc/mymenu.php');?>
</div>
<div id="content2">
<div id="mypanel">
<div id="mymenu" align="center">
<?php if((is_verify('ROOT'))||($SUBJECT_EDIT_ENABLE)){?>
<a href="javascript:show('panelform');" onClick="process_form('new')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/new.png"><br><?php echo $lg_new;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="process_form('update')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br><?php echo $lg_save;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="process_form('delete')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/delete.png"><br><?php echo $lg_delete;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<?php } ?>
<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/help22.png"><br><?php echo $lg_readme;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="javascript: href='p.php?p=sub&sid=<?php echo $sid?>&clslevel=<?php echo $clslevel?>'" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
</div>
<div align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
	<select name="sid" id="sid" onchange="document.myform.clslevel[0].value='';document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$schname</option>";
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
			
?>
  </select>

    <select name="clslevel" id="clslevel" onchange="document.myform.submit();">
<?php    
		if($clslevel=="")
            	echo "<option value=\"\">- $lg_level -</option>";
		else
			echo "<option value=$clslevel>$namatahap $clslevel</option>";
			$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=$s>$namatahap $s</option>";
            }

?>		
  </select>
	</div>
</div>
<div id="story">
<div id="tipsdiv" style="display:none ">
Tips:<br>
<?php if($LG=="BM"){?>
1. Sila hubungi Awfatech Support untuk mengubah bahagian ini.<br>
<?php }else{?>
1. Please call Awfatech Support to edit this section.<br>
<?php } ?>
</div>
<div id="panelform" style="display:none ">
<div id="mytitlebg">
	<div id="myclick" onClick="hide('panelform');"><img src="<?php echo $MYLIB;?>/img/icon_minimize.gif" id="mycontrolicon"><?php echo $lg_configuration;?></div>&nbsp;
</div>
<table width="100%" id="mytitle">   
	<tr>
		<td width="10%"><?php echo $lg_level;?></td>
		<td width="1%" >:</td>
		<td>
		<select name="addclslevel">
<?php    
		if($clslevel=="")
            	echo "<option value=\"\">- $lg_select -</option>";
		else
			echo "<option value=$clslevel>$namatahap $clslevel</option>";
			$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=$s>$namatahap $s</option>";
            }

?>	
        	</select> <img src="<?php echo $MYLIB;?>/img/alert14.png" id="addclslevelerr" style="display:none">
			</td>
	</tr>
			<td width="10%"><?php echo $lg_group;?></td>
			<td>:</td>
			<td><select name="grp" id="grp" >
<?php	
      		echo "<option value=\"\">- $lg_select -</option>";
			$sql="select * from type where grp='subtype' order by prm";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$s=$row['prm'];
				$v=$row['val'];
                echo "<option value=\"$s|$v\">$s</option>";
            }
?>
			</select>  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="grperr" style="display:none">
			</td>
	</tr>
	<tr>
			<td><?php echo $lg_subject;?></td>
			<td>:</td>
            <td><input name="sub" type="text" id="sub"> <img src="<?php echo $MYLIB;?>/img/alert14.png" id="suberr" style="display:none"></td>
	</tr>
	<tr>
			<td><?php echo $lg_code;?></td>
			<td>:</td>
            <td><input name="cod" type="text" id="cod" size="6"> <img src="<?php echo $MYLIB;?>/img/alert14.png" id="coderr" style="display:none"></td>
	</tr>
	<tr>
			<td><?php echo $lg_credit;?></td>
			<td width="1%" >:</td>
            <td>
			  <select name="credit">
			  		<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					<option value="0">0</option>
			  </select>
			  </td>
	</tr>
	<!-- 
	<tr>
			<td ><?php echo $lg_shortname;?></td>
			<td width="1%" >:</td>
            <td><input name="sname" type="text" id="sname" size="6" maxlength="6"></td>
	</tr>
	 -->
	<tr>
			<td><?php echo $lg_grade;?></td>
			<td width="1%" >:</td>
            <td>
		    <select name="grade" id="grade">
			<option value="">- <?php echo $lg_select;?> -</option>
<?php
		$sql="select prm from type where code='EXAM' and (sid=0 or sid=$sid)";
		$res2=mysql_query($sql)or die("4 failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$x=$row2['prm'];
			echo "<option value=\"$x\">$x</option>";
		}
?>
	</select> <img src="<?php echo $MYLIB;?>/img/alert14.png" id="gradeerr" style="display:none">
			  </td>

            </tr>
			
   </table>
   <br>

</div>
<div id="mytitle2"><?php echo "$lg_subject - $schname - $namatahap $clslevel";?> <?php echo $f;?></div>
<table width="100%" cellspacing="0">
  <tr>
  	<td class="mytableheader" style="border-right:none;" width="1%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
    <td class="mytableheader" style="border-right:none;" width="3%" align="center"><?php echo strtoupper($lg_no);?></td>
    <td class="mytableheader" style="border-right:none;" width="7%" align="center"><a href="#" onClick="formsort('code','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_code);?></a></td>
    <td class="mytableheader" style="border-right:none;" width="25%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_subject);?></a></td>
    <td class="mytableheader" style="border-right:none;" width="20%"><a href="#" onClick="formsort('grp','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_group);?></a></td>
	<td class="mytableheader" style="border-right:none;" width="5%" align="center"><a href="#" onClick="formsort('credit','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_credit);?></a></td>
	<td class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo strtoupper($namatahap);?></td>
	<td class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo strtoupper($lg_shortname);?></td>
	<td class="mytableheader" style="border-right:none;" width="20%">
	<?php if((is_verify('ROOT'))||($SUBJECT_EDIT_ENABLE)){?>
	<input type="button" name="Button" value="<?php echo $lg_update;?>" onClick="return process_form('updategrading')">
	<?php } ?>
	<a href="#" onClick="newwindow('prmgrading.php',0)" style="font-weight:normal ">(<?php echo strtoupper($lg_view);?> GRADING)</a>
	</td>
    <?php if($SUBJECT_PRASYARAT_ENABLE){?>
    <td class="mytableheader" style="border-right:none;" width="10%" align="center">PREREQUSITE</td>
    <td class="mytableheader" style="border-right:none;" width="5%" align="center">&nbsp;</td>
    <?php } ?>
  </tr>

<?php 
	if($clslevel=="")
		$xclslevel=-1;
	else
		$xclslevel=$clslevel;
	$sql="select * from sub where sch_id=$sid and level=$xclslevel $sqlsort";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
						$id=$row['id'];
                        $name=$row['name'];
						$sname=$row['sname'];
						$code=$row['code'];
						$lvl=$row['level'];
						$xsid=$row['sch_id'];
						$cre=$row['credit'];
						$grp=$row['grp'];
						$grading=$row['grading'];
				 if($SUBJECT_PRASYARAT_ENABLE){
						$subpra="";
						$sql="select * from prasyarat where sid='$xsid' and code='$code'";
						$res2=mysql_query($sql)or die("4 failed:".mysql_error());
						while($row2=mysql_fetch_assoc($res2)){
							if($subpra!="")
								$subpra=$subpra.", ";
							
							$subpra=$subpra.$row2['pcode'];
						}
				 }
						if(($q++%2)==0)
							$bg="#FAFAFA";
						else
							$bg="";
?>

  <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td class="myborder" style="border-right:none; border-top:none;"><input type=checkbox name=del[] value="<?php echo "$id";?>" onClick="check(0)"></td>
    <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$code";?></td>
    <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$name";?></td>
    <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$grp";?></td>
	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$cre";?></td>
	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$clslevel";?></td>
	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$sname";?></td>
	<td class="myborder" style="border-right:none; border-top:none;" >
	<select name="grading[]" id="grading[]">
		<option value="<?php echo "$id|$grading";?>" selected><?php echo "$grading";?></option>
<?php
		$sql="select * from type where prm!='$grading' and grp='grading' and code='EXAM' and (sid=0 or sid=$sid)";
		$res2=mysql_query($sql)or die("4 failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$x=$row2['prm'];
			echo "<option value=\"$id|$x\">$x</option>";
		}
?>
	</select>
	</td >
     <?php if($SUBJECT_PRASYARAT_ENABLE){?>
     <td class="myborder" align="center"><?php echo $subpra;?></td>
     <td class="myborder" align="center">
     	<a href="subpra.php?xcod=<?php echo $code;?>&xsid=<?php echo $xsid;?>&xlvl=<?php echo $lvl;?>" 
        	target="_blank" onClick="return GB_showCenter('Prerequisite',this.href,300,800)">Set</a>
     </td>
     <?php } ?>
  </tr>


<?php } ?>

</table>
</div></div>
</form>
</body>
</html>
