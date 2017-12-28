<?php
//160910 5.0.0 - update gui.. 
//110724 - update link.. 
$vmod="v6.0.0";
$vdate="110724";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR');
$username = $_SESSION['username'];
$searchall=$_REQUEST['searchall'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

	if($sid==0)
		$sql="select * from sch";
	else
		$sql="select * from sch where id=$sid";
	
	$res=mysql_query($sql)or die("query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$sid=$row['id'];
    $sname=$row['name'];
	$ssname=$row['sname'];
	$simg=$row['img'];
	$namatahap=$row['clevel'];	
	$issemester=$row['issemester'];
	$startsemester=$row['startsemester'];
	
	$year=$_POST['year'];
	if($year==""){
		$year=date('Y');
		if(($issemester)&&(date('n')<$startsemester))
			$year=$year-1;
	}
	$xx=$year+1;
	if($issemester)
		$sesyear="$year/$xx";	  
	else
		$sesyear="$year";
			
		$clslevel=$_REQUEST['clslevel'];
		if($clslevel!=""){
			$sqlclslevel="and cls_level='$clslevel'";
			$sqlsortcls=",cls_name asc";
		}
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from ses_cls where sch_id=$sid and cls_code='$clscode' and year=$year";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['cls_name']);
		}
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name_matrik_ic -")==0)
			$search="";
		if($search!=""){
			$search=addslashes($search);
			if($STU_SEARCH_MOTHER)
				$sqlsearch = "and (uid='$search' or ic='$search' or p1ic='$search' or p2ic='$search' or name like '%$search%' or p2name like '%$search%')";
			else
				$sqlsearch = "and (uid='$search' or ic='$search' or p1ic='$search' or p2ic='$search' or name like '%$search%')";
			
			
			$search=stripslashes($search);
		}

		
		
		$stustatus=$_REQUEST['stustatus'];
		if($stustatus==""){
			$stustatus = 6;
		}
		if($stustatus!="%"){
			$sqlstustatus="and status=$stustatus";
			$sql="select * from type where grp='stusta' and val='$stustatus'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$studentstatus=$row['prm'];
		}
		else
			$studentstatus="$lg_all $lg_student";
		
		$sponser=$_REQUEST['sponser'];
		if($sponser!=""){
				$sqlsponser="and sponser='$sponser'";
		}
		if($searchall)
			$sqlstustatus="";
			
		$isyatim=$_REQUEST['isyatim'];
		if($isyatim!=""){
			$sqlisyatim="and isyatim=1";
			$x1="- $lg_orphan";
		}
		
		$isinter=$_REQUEST['isinter'];
		if($isinter!=""){
			$sqlisinter="and isinter=1";
			$x1="- $lg_international";
		}
			
		$isstaff=$_REQUEST['isstaff'];
		if($isstaff!=""){
			$sqlisstaff="and isstaff=1";
			$x2="- $lg_staff_child";
		}
			
		$iskawasan=$_REQUEST['iskawasan'];
		if($iskawasan!=""){
			$sqliskawasan="and iskawasan=1";
			$x3="- $lg_kariah";
		}
		$ishostel=$_REQUEST['ishostel'];
		if($ishostel!=""){
			$sqlishostel="and ishostel=1";
			$x4="$lg_hostel";
		}

		$isfakir=$_REQUEST['isfakir'];
		if($isfakir!=""){
			$sqlisfakir="and isfakir=1";
			$x5="$lg_fakir";
		}
		$isblock=$_REQUEST['isblock'];
		if($isblock!=""){
			$sqlisblock="and isblock=1";
			$x5="- Blocked";
		}
		$isislah=$_REQUEST['isislah'];
		if($isislah!=""){
			$sqlisislah="and isislah=1";
			$x6="$lg_xprimary";
		}
		$view=$_POST['view'];
		
		$sql="select * from ses_cls where usr_uid='$username' and year='$year' and sch_id='$sid' and cls_code='$clscode'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$gurukelas=mysql_num_rows($res);

/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=30;
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
		$sqlsort="order by stu.id $order";
	else
		$sqlsort="order by $sort $order, name asc";


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

<SCRIPT LANGUAGE="JavaScript">
function clear_check(){
	for (var i=0;i<document.myform.elements.length;i++){
		var e=document.myform.elements[i];
        if ((e.type=='checkbox')&&(e.id=='view')){
			e.checked=false;
        }
	}
}
</script>
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="showhide('panelform');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/listview22.png"><br><?php echo $lg_option;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
	</div> <!-- right -->
</div><!-- end mypanel-->
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">

	<?php if((is_verify("ROOT|ADMIN|AKADEMIK|KEWANGAN|HR|CEO"))||($SHOW_EPELAJAR_TO_ALL_TEACHER)){?>
		  <select name="sid" id="sid" onchange="document.myform.clscode[0].value='';document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$ssname</option>";
				
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
			<select name="year" id="year" onchange="document.myform.submit();">
				<?php
 			echo "<option value=$year>$lg_session $sesyear</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        $xx=$s+1;
						if($issemester)
								$xsesyear="$s/$xx";
						else
								$xsesyear=$s;
						echo "<option value=\"$s\">$lg_session $xsesyear</option>";
			}			  
				?>
          </select>
<?php if(!$SYSTEM_ALLOW_STUDENT_MULTI_CLASS){?>
	<select name="clslevel" onChange="document.myform.clscode[0].value=''; document.myform.submit();">
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
		if($clslevel!="")
            echo "<option value=\"\">- $lg_all -</option>";

?>
      </select>
<?php } ?>
			 <select name="clscode" id="clscode" onchange="document.myform.submit();">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from ses_cls where sch_id=$sid and cls_code!='$clscode' and year=$year $sqlclslevel order by cls_level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=stripslashes($row['cls_name']);
						$a=$row['cls_code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
					if($clscode!="")
            			echo "<option value=\"\">- $lg_all -</option>";
			?>
                </select>
				<input name="search" type="text"  id="search" size="25" <?php if($search==""){?>onClick="document.myform.search.value='';"<?php } ?>
					value="<?php if($search=="") echo "- $lg_name_matrik_ic -"; else echo "$search";?>"> 
				
				<div style="display:inline; margin:0px 0px 0px -17px; padding:2px 2px 1px 1px; cursor:pointer" onClick="document.myform.search.value='';document.myform.search.focus();" 
					onMouseOver="showhide2('img6','img5');" onMouseOut="showhide2('img5','img6');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px" id="img5">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img6">
				</div>
                <input type="submit" name="Submit" value="<?php echo $lg_view;?> .." style="font-weight:bold;color:#0066CC;">
								
				<label><input type="checkbox" name="searchall" value="1" <?php if($searchall) echo "checked";?>><?php echo $lg_all_status;?></label>
			<?php } else {?>
					<input type="hidden" name="clscode" value="<?php echo $clscode;?>">
					<input type="hidden" name="year" value="<?php echo $year;?>">
					<input type="hidden" name="sid" value="<?php echo $sid;?>">
			<?php } ?>
			
</div> <!-- right -->
<div id="story">

<?php if($search!=""){?><div id="mytitlebg" style="color:#0066FF; font-size:12px"><?php echo $lg_search_for;?>... <?php echo $search;?></div><?php }?>

<div id="mytitle2">
<?php echo $sname;?> - 
<?php echo $lg_list;?>&nbsp;
<?php 
	echo "$studentstatus $sesyear $x1 $x2 $x3 $x4 $x5 $x6";
	if($clscode!="")
		echo  " - $clsname";
	elseif($clslevel!="")
		echo " - $namatahap $clslevel";
?>
</div>





<div id="panelform" style="display:none ">

<div id="mytitlebg">
	<div id="myclick" onClick="hide('panelform');" onMouseOver="showhide('img2','img1');" onMouseOut="showhide('img1','img2');">
		<img src="<?php echo $MYLIB;?>/img/icon_minimize.gif" id="img1" style="float:left;display:block;padding:0px 2px 0px 2px;">
		<img src="<?php echo $MYLIB;?>/img/icon_minimize_hover.gif" id="img2" style="float:left;display:none;padding:0px 2px 0px 2px;">
		SEARCH PARAMETER
	</div>&nbsp;&nbsp;&nbsp;
	[ <a href="#" onClick="document.myform.submit()"> VIEW </a> ]
</div>

<table width="100%" id="mytitlebg">
  <tr>
    <td> VIEW BY STATUS &nbsp;&nbsp;<select name="stustatus" id="stustatus">
<?php	
      		if($stustatus=="%"){
            	echo "<option value=\"%\">- $lg_all $lg_student -</option>";
			}
			else{
                echo "<option value=\"$stustatus\">$studentstatus</option>";
				echo "<option value=\"%\">- $lg_all $lg_student -</option>";
			}
			$sql="select * from type where grp='stusta' and val!='$stustatus'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=$row['prm'];
				$y=$row['val'];
                echo "<option value=\"$y\">$x</option>";
            }
?>
  </select>
	<br>
	<br>
	VIEW BY SPONSER
	<select name="sponser" id="sponser" >
					<?php	
						if($sponser==""){
							echo "<option value=\"\">- Sponsered By -</option>";
							$sql="select * from type where grp='sponser' order by val";
						}
						else{
							echo "<option value=\"$sponser\">$sponser</option>";
							$sql="select * from type where grp='sponser' and prm!='$sponser' order by val"; 	
						}
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									$v=$row['val'];
									echo "<option value=\"$s\">$s</option>";
						}
						echo "<option value=\"\">- $lg_all $lg_student -</option>";
						?>
</select>
	</td>
	<td valign="top">
	LIST BY :<br>
	<label style="cursor:pointer"><input type="checkbox" name="isyatim" value="1" <?php if($isyatim) echo "checked";?>><?php echo $lg_orphan;?></label>&nbsp;
	<label style="cursor:pointer"><input type="checkbox" name="isstaff" value="1" <?php if($isstaff) echo "checked";?>><?php echo $lg_staff_child;?></label>&nbsp;
	<label style="cursor:pointer"><input type="checkbox" name="iskawasan" value="1" <?php if($iskawasan) echo "checked";?>><?php echo $lg_kariah;?></label>&nbsp;
	<label style="cursor:pointer"><input type="checkbox" name="isinter" value="1" <?php if($isinter) echo "checked";?>><?php echo $lg_international;?></label>&nbsp;
	<label style="cursor:pointer"><input type="checkbox" name="ishostel" value="1" <?php if($ishostel) echo "checked";?>><?php echo $lg_hostel;?></label>&nbsp;
	<label style="cursor:pointer"><input type="checkbox" name="isislah" value="1" <?php if($isislah) echo "checked";?>><?php echo $lg_xprimary;?></label>&nbsp;
	<label style="cursor:pointer"><input type="checkbox" name="isfakir" value="1" <?php if($isfakir) echo "checked";?>><?php echo $lg_fakir;?></label>&nbsp;
	<label style="cursor:pointer"><input type="checkbox" name="isblock" value="1" <?php if($isblock) echo "checked";?>>Blocked Student</label>&nbsp;
	
	</td>
  </tr>
</table>


<div id="mytitlebg">
	<div id="myclick" onClick="hide('panelform');" onMouseOver="showhide('img4','img3');" onMouseOut="showhide('img3','img4');">
		<img src="<?php echo $MYLIB;?>/img/icon_minimize.gif" id="img3" style="float:left;display:block;padding:0px 2px 0px 2px;">
		<img src="<?php echo $MYLIB;?>/img/icon_minimize_hover.gif" id="img4" style="float:left;display:none;padding:0px 2px 0px 2px;">
		VIEW SETTING
	</div>&nbsp;&nbsp;&nbsp;
	[ <a href="#" onClick="clear_check()"> CLEAR </a> |
	<a href="#" onClick="document.myform.submit()"> VIEW </a> ]
</div>

<table id="mytitlebg" width="100%"><tr><td width="20%">
<table width="100%" border="0" >
	<tr>
			<td colspan="2" id="mytabletitle"><?php echo $lg_student_information;?></td>
	  </tr>
  <tr>
    <td width="1%"><input type="checkbox" id="view" name=view[0] value="<?php echo $lg_matric;?>" <?php if($view[0]) echo "checked";?>>
    <td><?php echo $lg_matric;?></td>
  </tr>
   <tr>
  	<td><input type="checkbox" id="view"  name=view[1] value="<?php echo $lg_class;?>" <?php if($view[1]) echo "checked";?>>
    <td><?php echo $lg_class;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[2] value="<?php echo $lg_ic;?>" <?php if($view[2]) echo "checked";?>>
    <td><?php echo $lg_mykad;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[3] value="<?php echo $lg_sex;?>" <?php if($view[3]) echo "checked";?>>
    <td><?php echo $lg_sex;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[4] value="<?php echo $lg_telephone;?>" <?php if($view[4]) echo "checked";?>>
    <td><?php echo $lg_telephone;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[5] value="<?php echo $lg_tel_mobile;?>" <?php if($view[5]) echo "checked";?>>
    <td><?php echo $lg_tel_mobile;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[6] value="<?php echo $lg_email;?>" <?php if($view[6]) echo "checked";?>>
    <td><?php echo $lg_email;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[7] value="<?php echo $lg_address;?>" <?php if($view[7]) echo "checked";?>>
    <td><?php echo $lg_address;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[8] value="<?php echo $lg_city;?>" <?php if($view[8]) echo "checked";?>>
    <td><?php echo $lg_city;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[9] value="<?php echo $lg_postcode;?>" <?php if($view[9]) echo "checked";?>>
    <td><?php echo $lg_postcode;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[10] value="<?php echo $lg_state;?>" <?php if($view[10]) echo "checked";?>>
    <td><?php echo $lg_state;?></td>
  </tr>
</table>
</td>
<td width="20%" valign="top">
<table width="100%" border="0" >
<tr>
			<td colspan="2" id="mytabletitle"><?php echo $lg_student_information;?></td>
	  </tr>
  <tr>
  	<td  width="1%"><input type="checkbox" id="view"  name=view[11] value="<?php echo $lg_register_date;?>" <?php if($view[11]) echo "checked";?>>
    <td><?php echo $lg_register_date;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[12] value="<?php echo $lg_end_date;?>" <?php if($view[12]) echo "checked";?>>
    <td><?php echo $lg_end_date;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[13] value="<?php echo $lg_citizen;?>" <?php if($view[13]) echo "checked";?>>
    <td><?php echo $lg_citizen;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[14] value="<?php echo $lg_mentor;?>" <?php if($view[14]) echo "checked";?>>
    <td><?php echo $lg_mentor;?></td>
  </tr>
    <tr>
  	<td><input type="checkbox" id="view"  name=view[15] value="<?php echo $lg_registration_no;?>" <?php if($view[15]) echo "checked";?>>
    <td><?php echo $lg_registration_no;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[16] value="<?php echo $lg_extra_information;?>" <?php if($view[16]) echo "checked";?>>
    <td><?php echo $lg_extra_information;?></td>
  </tr>
    <tr>
  	<td><input type="checkbox" id="view"  name=view[17] value="Sponser" <?php if($view[17]) echo "checked";?>>
    <td>Sponser</td>
  </tr>
   <tr>
  	<td><input type="checkbox" id="view"  name=view[18] value="<?php echo $lg_birth_place;?>" <?php if($view[18]) echo "checked";?>>
    <td><?php echo $lg_birth_place;?></td>
  </tr>
</table>
</td>
<td width="20%" valign="top">

<table width="100%" border="0" >
	<tr>
			<td colspan="2" id="mytabletitle"><?php echo $lg_father_information;?></td>
	  </tr>
  <tr>
    <td width="1%"><input type="checkbox" id="view" name=view[20] value="<?php echo $lg_name;?>" <?php if($view[20]) echo "checked";?>>
    <td><?php echo $lg_name;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[21] value="<?php echo $lg_mykad;?>" <?php if($view[21]) echo "checked";?>>
    <td><?php echo $lg_mykad;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[22] value="<?php echo $lg_tel_mobile;?>" <?php if($view[22]) echo "checked";?>>
    <td><?php echo $lg_tel_mobile;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[23] value="<?php echo $lg_tel_office;?>" <?php if($view[23]) echo "checked";?>>
    <td><?php echo $lg_tel_office;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[24] value="<?php echo $lg_email;?>" <?php if($view[24]) echo "checked";?>>
    <td><?php echo $lg_email;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[25] value="<?php echo $lg_office_address;?>" <?php if($view[25]) echo "checked";?>>
    <td><?php echo $lg_office_address;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[26] value="<?php echo $lg_occupation;?>" <?php if($view[26]) echo "checked";?>>
    <td><?php echo $lg_occupation;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[27] value="<?php echo $lg_employer;?>" <?php if($view[27]) echo "checked";?>>
    <td><?php echo $lg_employer;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view"  name=view[28] value="<?php echo $lg_salary;?>" <?php if($view[28]) echo "checked";?>>
    <td><?php echo $lg_salary;?></td>
  </tr>
 </table>
</td>
<td width="33%" valign="top">

<table width="100%">
	<tr>
			<td colspan="2" id="mytabletitle"><?php echo $lg_mother_information;?></td>
	  </tr>
  <tr>
    <td width="1%"><input type="checkbox" id="view" name=view[40] value="<?php echo $lg_name;?>" <?php if($view[40]) echo "checked";?>>
    <td><?php echo $lg_name;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[41] value="<?php echo $lg_mykad;?>" <?php if($view[41]) echo "checked";?>>
    <td><?php echo $lg_mykad;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[42] value="<?php echo $lg_tel_mobile;?>" <?php if($view[42]) echo "checked";?>>
    <td><?php echo $lg_tel_mobile;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[43] value="<?php echo $lg_tel_office;?>" <?php if($view[43]) echo "checked";?>>
    <td><?php echo $lg_tel_office;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[44] value="<?php echo $lg_email;?>" <?php if($view[44]) echo "checked";?>>
    <td><?php echo $lg_email;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[45] value="<?php echo $lg_office_address;?>" <?php if($view[45]) echo "checked";?>>
    <td><?php echo $lg_office_address;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[46] value="<?php echo $lg_occupation;?>" <?php if($view[46]) echo "checked";?>>
    <td><?php echo $lg_occupation;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[47] value="<?php echo $lg_employer;?>" <?php if($view[47]) echo "checked";?>>
    <td><?php echo $lg_employer;?></td>
  </tr>
  <tr>
  	<td><input type="checkbox" id="view" name=view[48] value="<?php echo $lg_salary;?>" <?php if($view[48]) echo "checked";?>>
    <td><?php echo $lg_salary;?></td>
  </tr>
 </table>
</td>



</tr>
</table>
</div> <!-- end panelform -->


<?php if(count($view)>0){
//seting for hedder first
//get bil row makl pelajar
for($i=0;$i<70;$i++){ 
	if($view[$i]){
		if($i<20)
			$colstu++;
		elseif($i<30)
			$colpak++;
		else
			$colmak++;
	}
}
$colstu++;//tambak satu utk nama pelajar
$sz=75/count($view);
?>
<table width="100%" cellspacing="0" cellpadding="3">
  	<tr>

<?php 	if($colstu>0){ ?>
		<td class="mytableheader" style="border-right:none;" align="center" style="border-right:none;" colspan="<?php echo $colstu+1;?>"><?php echo strtoupper($lg_student_information);?></td>
<?php } if($colpak>0){ ?>
		<td class="mytableheader" style="border-right:none;" align="center" style="border-right:none;" colspan="<?php echo $colpak;?>"><?php echo strtoupper($lg_father_information);?></td>
<?php } if($colmak>0){ ?>
		<td class="mytableheader" style="border-right:none;" align="center" style="border-right:none;" colspan="<?php echo $colmak;?>"><?php echo strtoupper($lg_mother_information);?></td>
<?php } ?>
	<tr>
		<td class="mytableheader" style="border-right:none; border-top:none;" align="center" width="1%"><?php echo $lg_no;?></td>
		<td class="mytableheader" style="border-right:none; border-top:none;" width="25%"  align="center"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_name;?></a></td>
<?php 	
		for($i=0;$i<70;$i++){ 
			if($view[$i]){ ?>
				<td class="mytableheader" style="border-right:none; border-top:none;" width="<?php echo $sz;?>%" bgcolor="#FAFAFA"  align="center"><?php echo $view[$i];?></td>
<?php }}?>
	</tr>
<?php	
	if(($clscode=="")&&($clslevel==""))
    	$sql="select count(*) from stu where sch_id=$sid $sqlstustatus $sqlisyatim $sqlisinter $sqlisstaff $sqliskawasan $sqlisislah $sqlishostel $sqlisfakir $sqlsearch";
	else
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlclscode $sqlclslevel $sqlstustatus $sqlisyatim $sqlisinter $sqlisstaff $sqliskawasan $sqlisislah $sqlishostel $sqlisfakir $sqlsearch and year='$year'";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	if(($clscode=="")&&($clslevel==""))
		$sql="select * from stu where sch_id=$sid $sqlstustatus $sqlisyatim $sqlisblock $sqlisstaff $sqlisinter $sqliskawasan $sqlisislah $sqlisfakir $sqlishostel $sqlsearch $sqlsort $sqlmaxline";
	else
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlstustatus $sqlclscode $sqlclslevel $sqlisyatim $sqlisinter $sqlisblock $sqlisstaff $sqliskawasan $sqlisislah $sqlisfakir $sqlishostel $sqlsearch and year='$year' $sqlsort $sqlmaxline";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$ic=$row['ic'];
		$name=ucwords(strtolower(stripslashes($row['name'])));
		$sex=$lg_sexmf[$row['sex']];
		
		$rdate=$row['rdate'];
		$edate=$row['edate'];
		$sponsered=$row['sponser'];
		$bstate=$row['bstate'];
		$addr=stripslashes($row['addr']);
		//if($addr!="")
		//	$addr=$addr.",";
		$bandar=$row['bandar'];
		//if($bandar!="")
			//$bandar=$bandar.",";
		$poskod=$row['poskod'];
		$state=$row['state'];
		$tel=$row['tel'];
		$hp=$row['hp'];
		$mel=$row['mel'];
		$citizen=ucwords(strtolower(stripslashes($row['citizen'])));
		$mentor=$row['mentor'];
		$nogiliran=$row['nogiliran'];
		$etc=$row['etc'];
		$status=$row['status'];
		
		
		$p1ic=$row['p1ic'];
		$p1hp=$row['p1hp'];
		$p1tel=$row['p1tel'];
		$p1name=ucwords(strtolower(stripslashes($row['p1name'])));
		$p1mel=$row['p1mel'];
		$p1addr=ucwords(strtolower(stripslashes($row['p1addr'])));
		$p1job=ucwords(strtolower(stripslashes($row['p1job'])));
		$p1com=ucwords(strtolower(stripslashes($row['p1com'])));
		$p1sal=$row['p1sal'];
		
		
		$p2ic=$row['p2ic'];
		$p2hp=$row['p2hp'];
		$p2tel=$row['p2tel'];
		$p2name=ucwords(strtolower(stripslashes($row['p2name'])));
		$p2mel=$row['p2mel'];
		$p2addr=ucwords(strtolower(stripslashes($row['p2addr'])));
		$p2job=ucwords(strtolower(stripslashes($row['p2job'])));
		$p2com=ucwords(strtolower(stripslashes($row['p2com'])));
		$p2sal=$row['p2sal'];
		
		$cname="- $lg_none -";
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$namakelas=$row2['cls_name'];
	
		$kk=0;
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$cn=stripslashes($row2['cls_name']);
			$clslevel=$row2['cls_level'];
			$y=$row2['year'];
			$xid=$row2['id'];
			if($kk>0)
				$namakelas=$namakelas.",".$cn;
			else
				$namakelas=$cn;
			$kk++;
		}
			
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="#FFFFFF";
		
		echo "<tr bgcolor=$bg style=\"cursor:pointer\" onMouseOver=\"this.bgColor='#CCCCFF';\" onMouseOut=\"this.bgColor='$bg'\" onClick=\"newwindow('../adm/stu_info.php?p=stu_profile&uid=$uid&sid=$sid',0)\">";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\">$q</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\">$name</td>";
		if($view[0]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$uid</td>";
		if($view[1]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$namakelas</td>";
		if($view[2]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$ic</td>";
		if($view[3]) echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=center>$sex</td>";
		if($view[4]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$tel</td>";
		if($view[5]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$hp</td>";
		if($view[6]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$mel</td>";
		if($view[7]) echo "<td class=myborder style=\"border-right:none; border-top:none;\" width=20%>$addr</td>";
		if($view[8]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$bandar</td>";
		if($view[9]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$poskod</td>";
		if($view[10]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$state</td>";
		if($view[11]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$rdate</td>";
		if($view[12]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$edate</td>";
		if($view[13]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$citizen</td>";
		if($view[14]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$mentor</td>";
		if($view[15]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$etc</td>";
		if($view[16]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$nogiliran</td>";
		if($view[17]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$sponsered</td>";
		if($view[18]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$bstate</td>";
		
		
		if($view[20]) echo "<td class=myborder style=\"border-right:none; border-top:none;\" width=20%>$p1name</td>";
		if($view[21]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p1ic</td>";
		if($view[22]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p1hp</td>";
		if($view[23]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p1tel</td>";
		if($view[24]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p1mel</td>";
		if($view[25]) echo "<td class=myborder style=\"border-right:none; border-top:none;\" width=20%>$p1addr</td>";
		if($view[26]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p1job</td>";
		if($view[27]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p1com</td>";
		if($view[28]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p1sal</td>";

		if($view[40]) echo "<td class=myborder style=\"border-right:none; border-top:none;\" width=20%>$p2name</td>";
		if($view[41]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p2ic</td>";
		if($view[42]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p2hp</td>";
		if($view[43]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p2tel</td>";
		if($view[44]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p2mel</td>";
		if($view[45]) echo "<td class=myborder style=\"border-right:none; border-top:none;\" width=20%>$p2addr</td>";
		if($view[46]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p2job</td>";
		if($view[47]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p2com</td>";
		if($view[48]) echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p2sal</td>";
		
	echo "</tr>";
} ?>
</table>

<?php }?>


<?php if(count($view)==0){?>
<table width="100%" cellspacing="0" cellpadding="2">
	<tr >
			  <td class="mytableheader" style="border-right:none;" width="2%" align="center"><?php echo strtoupper($lg_no);?></td>
			  <td class="mytableheader" style="border-right:none;" width="5%" align="center" class="printhidden">OPERATION</td>
			<?php if($ISPARENT_ACC){?>
			  <td class="mytableheader" style="border-right:none;" width="5%" align="center"><a href="#" onClick="formsort('acc','<?php echo "$nextdirection";?>')" title="Sort">ACC</a></td>
			<?php }?>
              <td class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo strtoupper($lg_intake);?></td>
			  <td class="mytableheader" style="border-right:none;" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?></a></td>
			  <td class="mytableheader" style="border-right:none;" width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>
              <td class="mytableheader" style="border-right:none;" width="25%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			  <td class="mytableheader" style="border-right:none;" width="15%"><?php echo strtoupper($lg_class);?> <?php echo $sesyear;?></td>
			  <td class="mytableheader" style="border-right:none;" width="10%"><?php echo strtoupper($lg_ic_number);?></td>
			  <td class="mytableheader" style="border-right:none;" width="7%" align="center"><?php echo strtoupper($lg_birth_date);?></td>
			  <td class="mytableheader" style="border-right:none;" width="7%" align="center"><?php echo strtoupper($lg_register);?></td>
			  <td class="mytableheader" style="border-right:none;" width="7%" align="center"><?php echo strtoupper($lg_end);?></td>
			  <td class="mytableheader" style="border-right:none;" width="10%" align="center">IC <?php echo strtoupper($lg_parent);?></td>
			  <td class="mytableheader"  width="7%" align="center">STATUS</td> 
              <td class="mytableheader"  width="7%" align="center">BUKU INDUK</td> 
	</tr>
<?php	
	if(($clscode=="")&&($clslevel==""))
    	$sql="select count(*) from stu where sch_id=$sid $sqlstustatus $sqlisyatim $sqlisinter $sqlisstaff $sqliskawasan $sqlisislah $sqlishostel $sqlisfakir $sqlsponser $sqlsearch";
	else
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlclscode $sqlclslevel $sqlstustatus $sqlisyatim $sqlisinter $sqlisstaff $sqliskawasan $sqlisislah $sqlishostel $sqlisfakir $sqlsponser $sqlsearch and year='$year'";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	if(($clscode=="")&&($clslevel==""))
		$sql="select * from stu where sch_id=$sid $sqlstustatus $sqlisyatim $sqlisblock $sqlisstaff $sqliskawasan $sqlisislah $sqlisinter $sqlisfakir $sqlishostel $sqlsponser $sqlsearch $sqlsort $sqlmaxline";
	else
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlstustatus $sqlclscode $sqlclslevel $sqlisyatim $sqlisinter $sqlisblock $sqlisstaff $sqliskawasan $sqlisislah $sqlisfakir $sqlishostel $sqlsponser $sqlsearch and year='$year' $sqlsort $sqlmaxline";

	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$sql="select * from stu where uid='$uid' and sch_id=$sid";
		$res4=mysql_query($sql)or die("query failed:".mysql_error());
		$row4=mysql_fetch_assoc($res4);
		$acc=$row4['acc'];
		$ic=$row4['ic'];
		$name=strtoupper(stripslashes($row4['name']));
		$sex=$lg_sexmf[$row4['sex']];
		$bday=$row4['bday'];
		$rdate=$row4['rdate'];
		$edate=$row4['edate'];
		$p1ic=$row4['p1ic'];
		$p1hp=$row4['p1hp'];
		$p1tel=$row4['p1tel'];
		$status=$row4['status'];
		$intake=$row4['intake'];

			$cname="- $lg_none -";
			$kk=0;
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
				$cn=stripslashes($row2['cls_name']);
				$clslevel=$row2['cls_level'];
				$y=$row2['year'];
				$xid=$row2['id'];
				if($kk>0)
					$cname=$cname.",".$cn;
				else
					$cname=$cn;
				$kk++;
			}
		
		$sql="select * from type where grp='stusta' and val='$status'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$sta=$row2['prm'];
			
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="#FFFFFF";
		$xxname=addslashes($name);
?>
		<tr bgcolor=<?php echo $bg;?> style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg;?>'">
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $q;?></td>
		<td id="myborder" style="border-right:none; border-top:none;" align="center" class="printhidden">
<?php
    	 if(is_verify("ROOT|ADMIN")){
				echo "<a href=\"../adm/stu_info.php?p=stu_profile&uid=$uid&sid=$sid\" title=Profile target=_blank onClick=\"return GB_showPage('Profile : $xxname ',this.href)\"><img src=\" $MYLIB/img/user12.png\"></a>
				<a href=\"../adm/stureg.php?uid=$uid&sid=$sid\" title=Edit target=_blank title='Edit Profile : $xxname '\"><img src=\" $MYLIB/img/edit12.png\"></a>
				<a href=\"../efee/$FN_FEEPAY.php?uid=$uid&year=$year&sid=$sid\" title=Payment target=_blank onClick=\"return GB_showPage('Fee : $xxname',this.href)\"><img src= $MYLIB/img/dollar12.png></a> 
				<a href=\"../exam/slip_exam.php?uid=$uid&year=$year&sid=$sid\" title=\"Result\" target=_blank onClick=\"return GB_showPage('Result : $xxname',this.href)\"><img src=\" $MYLIB/img/graph12.png\"></a>
				";
		}elseif(is_verify("KEWANGAN")){
				echo "<a href=\"../adm/stu_info.php?p=stu_profile&uid=$uid&sid=$sid\" title=Profile target=_blank onClick=\"return GB_showPage('Profile : $xxname ',this.href)\"><img src=\" $MYLIB/img/user12.png\"></a>
				<a href=\"../efee/$FN_FEEPAY.php?uid=$uid&year=$year&sid=$sid\" title=Payment target=_blank onClick=\"return GB_showPage('Fee : $xxname',this.href)\"><img src= $MYLIB/img/dollar12.png></a> 
				";
		}
		elseif(is_verify("AKADEMIK|HR")){
				echo "<a href=\"../adm/stu_info.php?p=stu_profile&uid=$uid&sid=$sid\" title=Profile target=_blank onClick=\"return GB_showPage('Profile : $xxname ',this.href)\"><img src=\" $MYLIB/img/user12.png\"></a>
				<a href=\"../exam/slip_exam.php?uid=$uid&year=$year&sid=$sid\" title=\"Result\" target=_blank onClick=\"return GB_showPage('Result : $xxname',this.href)\"><img src=\" $MYLIB/img/graph12.png\"></a>
				";
		}
		if($gurukelas>0){
				echo "<a href=\"stureg.php?uid=$uid&sid=$sid\" title=Edit target=_blank title='Edit Profile : $xxname '\"><img src=\" $MYLIB/img/edit12.png\"></a>
			";
		}
		echo "</td>";
		if($ISPARENT_ACC)
			echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=center>$acc</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=center>$intake</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=center>$uid</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=\"center\">$sex</td>";
 		echo "<td class=myborder style=\"border-right:none; border-top:none;\"><a href=\"../adm/stu_info.php?p=stu_profile&uid=$uid&sid=$sid\" title=Profile target=_blank onClick=\"return GB_showPage('Profile : $xxname ',this.href)\">$name</a></td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\">$cname</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\">$ic</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=center>$bday</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=center>$rdate</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=center>$edate</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=center>$p1ic</td>";
		echo "<td class=myborder style=\"border-top:none;\" align=\"center\">$sta</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\"><a href=\"../edaftar/bukuinduk.php?sid=$sid&ic=$ic\" title=Profile target=_blank>$lg_view</a></td>";
		echo "</tr>";
  }
  mysql_free_result($res);
  ?>
</table>          

<?php }?>


<?php include("../inc/paging.php");?>

</div></div>

</form> <!-- end myform -->



</body>
</html>
<!-- 
v2.7
22/11/2008	: update sesi listing
Author		: razali212@yahoo.com
 -->