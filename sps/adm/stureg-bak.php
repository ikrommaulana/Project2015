<?php
//10/05/2010 - update slahses
//16/04/2010 - gaji parent
//26/05/2010 - acc code
$vmod="v5.3.1";
$vdate="110409";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU');
$username = $_SESSION['username'];
$st=$_REQUEST['st'];
$uid=$_REQUEST['uid'];
$sid=$_REQUEST['sid'];
if($sid=="")
	$sid=$_SESSION['sid'];
$operation=$_REQUEST['operation'];
if($operation!=""){
			include_once('stusave.php');
}

		if($uid!=""){
			$sql="select * from stu where uid='$uid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sch_id'];
			$uid=$row['uid'];
			$id=$row['id'];
			$pass=$row['pass'];
			$namapelajar=stripslashes($row['name']);
			$ic=$row['ic'];
			$sex=$row['sex'];
			$race=$row['race'];
			$religion=$row['religion'];
			$bday=$row['bday'];
			list($y,$m,$d)=split('[/.-]',$bday);
			$tel=$row['tel'];
			$tel2=$row['tel2'];
			$fax=$row['fax'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$addr=stripslashes($row['addr']);
			$bandar=stripslashes($row['bandar']);
			$poskod=$row['poskod'];
			$state=$row['state'];
			$mentor=$row['mentor'];
			$etc=$row['etc'];
			$acc=$row['acc'];
			$sponser=$row['sponser'];
			$nogiliran=$row['nogiliran'];
			$file=$row['file'];
			$clslevel=$row['clslevel'];
			$isislah=$row['isislah'];
			$ishostel=$row['ishostel'];
			$isstaff=$row['isstaff'];
			$isyatim=$row['isyatim'];
			$iskawasan=$row['iskawasan'];
			$isfeenew=$row['isfeenew'];
			$citizen=$row['citizen'];
			$isfeefree=$row['isfeefree'];
			$isfeeonanak=$row['isfeeonanak'];
			$isfakir=$row['isfakir'];
			$isspecial=$row['isspecial'];
			$feehutang=$row['feehutang'];
			$isinter=$row['isinter'];
			$isblock=$row['isblock'];
			$intake=$row['intake'];
			
			$transport=$row['transport'];
			$pschool=$row['pschool'];
			$onsms=$row['onsms'];
			$ill=$row['ill'];
			$status=$row['status'];
			$edate=$row['edate'];
			$rdate=$row['rdate'];
			$bstate=$row['bstate'];
			$p1name=stripslashes($row['p1name']);
			$p1ic=$row['p1ic'];
			$p1rel=$row['p1rel'];
			$p1job=stripslashes($row['p1job']);
			$p1sal=$row['p1sal'];
			$p1com=stripslashes($row['p1com']);
			$p1tel=$row['p1tel'];
			$p1tel2=$row['p1tel2'];
			$p1fax=$row['p1fax'];
			$p1addr=stripslashes($row['p1addr']);
			$p1mel=$row['p1mel'];
			$p1hp=$row['p1hp'];
			$p1wn=$row['p1wn'];
			$p1edu=$row['p1edu'];
			
			$p2name=stripslashes($row['p2name']);
			$p2ic=$row['p2ic'];
			$p2rel=$row['p2rel'];
			$p2job=stripslashes($row['p2job']);
			$p2sal=$row['p2sal'];
			$p2com=stripslashes($row['p2com']);
			$p2tel=$row['p2tel'];
			$p2tel2=$row['p2tel2'];
			$p2fax=$row['p2fax'];
			$p2addr=stripslashes($row['p2addr']);
			$p2mel=$row['p2mel'];
			$p2hp=$row['p2hp'];
			$p2wn=$row['p2wn'];
			$p2edu=$row['p2edu'];

			$p3name=stripslashes($row['p3name']);
			$p3ic=$row['p3ic'];
			$p3rel=$row['p3rel'];
			$p3tel=$row['p3tel'];
			$p3tel2=$row['p3tel2'];
			$p3fax=$row['p3fax'];
			$p3addr=stripslashes($row['p3addr']);
			$p3mel=$row['p3mel'];
			$p3hp=$row['p3hp'];
			
			$q0=$row['q0'];
			list($q01,$q02,$q03)=split('[/.|]',$q0);
			$q1=$row['q1'];
			list($q11,$q12,$q13)=split('[/.|]',$q1);
			$q2=$row['q2'];
			list($q21,$q22,$q23)=split('[/.|]',$q2);
			$q3=$row['q3'];
			list($q31,$q32,$q33)=split('[/.|]',$q3);
			$q4=$row['q4'];
			list($q41,$q42,$q43)=split('[/.|]',$q4);
			$q5=$row['q5'];
			list($q51,$q52,$q53)=split('[/.|]',$q5);
			$q6=$row['q6'];
			list($q61,$q62,$q63)=split('[/.|]',$q6);
			$q7=$row['q7'];
			list($q71,$q72,$q73)=split('[/.|]',$q7);
			$q8=$row['q8'];
			list($q81,$q82,$q83)=split('[/.|]',$q8);
			$q9=$row['q9'];
			list($q91,$q92,$q93)=split('[/.|]',$q9);
			mysql_free_result($res);
		}


		if($rdate=="")
			$rdate=date("Y-m-d");
		
		if($sid!="0"){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            $stype=$row['level'];
			$level=$row['clevel'];
            mysql_free_result($res);					  
		}
		else
			$level="Tahap";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php include("$MYOBJ/datepicker/dp.php")?>
<script language="JavaScript">
function process_form(op){
	if(op=='delete'){
		if(document.myform.id.value==""){
			alert('Please select the user to delete');
			return;
		}
		ret = confirm("Are you sure want to delete??");
		if (ret == true){
		    document.myform.operation.value=op;
			document.myform.submit();
		}
		return;
	
	}
	else{
		
		if((document.myform.sid.value=="")||(document.myform.sid.value=="0")){
			alert("Select school..");
			document.myform.sid.focus();
			return;
		}
		/**
		if(document.myform.clslevel.value==""){
			alert("Sila masukkan tahun/tingkatan");
			document.myform.clslevel.focus();
			return;
		}
		**/
		if(document.myform.name.value==""){
			alert("Please enter student fullname..");
			document.myform.name.focus();
			return;
		}
		if(document.myform.ic.value==""){
			alert("Please enter IC number..");
			document.myform.ic.focus();
			return;
		}
		if(document.myform.p1name.value==""){
			alert("Please enter parent name..");
			document.myform.p1name.focus();
			return;
		}
		if(document.myform.p1ic.value==""){
			alert("Please enter parent IC number..");
			document.myform.p1ic.focus();
			return;
		}
		
		if(document.myform.file.value!=""){
			fn=document.myform.file.value;
			len=fn.length;
			ext=fn.substr(len-3,3);
			if((ext.toLowerCase()!="gif")&&(ext.toLowerCase()!="jpg")){
				alert("Invalid file. Only GIF and JPG allowed");
				document.myform.uploadedfile.focus();
				return;
			}
		}
		ret = confirm("Are you sure want to save??");
		if (ret == true){
			document.myform.operation.value=op;
			document.myform.submit();
		}
		return;
	}
}



</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>EDIT PROFILE <?php echo strtoupper($namapelajar);?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>
<form method="post" enctype="multipart/form-data" name="myform" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="stureg">
<input type="hidden" name="operation" >
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000">

<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
				<?php if($uid==""){ ?>
				<a href="p.php?p=stureg" id="mymenuitem"><img src="../img/new.png"><br>New</a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php } ?>
				<a href="#" onClick="process_form('<?php if($uid=="")echo "insert"; else echo "update";?>')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php if(is_verify("ADMIN")&&($STU_ALLOW_DELETE==1)){ ?>
				<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php } ?>
				<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div> <!-- end mymenu -->
		<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div> <!-- end mypanel -->

<div id="story">
<div id="mytitlebg"><?php echo strtoupper($lg_administration);?>
<?php 
 if($st=="1")
	echo "<font color=blue>&lt;successfully updated&gt;</font>";
 if($st=="2")
	echo "<font color=blue>&lt;successfully deleted&gt;</font>";
 if($st=="0")
	echo "<font color=red>&lt;update failed&gt;</font>";
?>
</div>

<table width="100%" bgcolor="#FFFFCC">
	<tr><td width="50%" valign="top" id="myborder">
	
			<table width="100%">
                <tr>
                  <td width="16%">*<?php echo $lg_school;?></td>
				  <td width="1%">:</td>
                  <td width="83%">
				  <select name="sid" id="sid" onChange="document.myform.submit()" <?php if($uid!="") echo "readonly"?>>
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- Pilih Sekolah -</option>";
			else
                echo "<option value=$sid>$sname</option>";
			if(($_SESSION['sid']==0)&&($uid=="")){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
			}						  
			
?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>*<?php echo $lg_matric;?></td>
				  <td>:</td>
                  <td><input name="uid" type="text" id="uid" value="<?php echo $uid;?>" <?php if($uid!="") echo "readonly"?> ></td>
                </tr>
                <tr>
                  <td><?php echo $lg_intake;?></td>
				  <td >:</td>
                  <td ><input name="intake" type="text" value="<?php echo $intake;?>"></td>
                </tr>
				<tr>
                  <td><?php echo $lg_school_account;?></td>
				  <td >:</td>
                  <td ><input name="acc" type="text" value="<?php echo $acc;?>"></td>
                </tr>
                
				<tr>
                  <td><?php echo $lg_register_no;?></td>
				  <td >:</td>
                  <td ><input name="nogiliran" type="text" value="<?php echo $nogiliran;?>"></td>
                </tr>
				<tr>
                  <td ><?php echo $lg_status;?></td>
				  <td >:</td>
                  <td >
				  <select name="status" id="status">
                    <?php	
      		if($status==""){
            	echo "<option value=\"\">- Pilih -</option>";
				$sql="select * from type where grp='stusta' order by val";
			}
			else{
			    $sql="select * from type where grp='stusta' and val=$status";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
            	$row=mysql_fetch_assoc($res);
                $a=$row['prm'];
				$b=$row['val'];
				mysql_free_result($res);	
                echo "<option value=\"$b\">$a</option>";				
				$sql="select * from type where grp='stusta' and val!=$status order by val";
			}
			
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$v\">$s</option>";
            }
            mysql_free_result($res);					  

?>
                  </select></td>
				</tr>
				<tr>
                  <td><?php echo $lg_register;?></td>
				  <td>:</td>
                  <td><input name="rdate" type="text" id="rdate" value="<?php if($rdate=="0000-00-00") $rdate=""; echo "$rdate";?>" size="12" >
                    <input name=" cal" type="button" id=" cal" value="-" onClick="displayDatePicker('rdate');"> eg. 2009-12-31</td>
				</tr>
				<tr>
                  <td><?php echo $lg_end;?></td>
				  <td>:</td>
                  <td><input name="edate" type="text" id="edate" value="<?php if($edate=="0000-00-00") $edate=""; echo "$edate";?>" size="12">
                    <input name=" cal2" type="button" id=" cal2" value="-" onClick="displayDatePicker('edate');"> eg. 2009-12-31</td>
				</tr>
				<tr>
                  <td><?php echo $lg_other_information;?></td>
				  <td>:</td>
                  <td><input name="etc" type="text" id="uid" size="48" value="<?php echo $etc;?>"></td>
                </tr>

	</table>
	
</td><td id="myborder" valign="top" width="50%">


		
				<table width="100%">
					  <tr>
						<td id="myborder" width="25%"><input name="isinter" type="checkbox" id="isinter" value="1" <?php if($isinter) echo "checked"?> ><?php echo $lg_international;?></td>
						<td id="myborder" width="25%"><input name="isislah" type="checkbox" id="isislah" value="1" <?php if($isislah) echo "checked"?> ><?php echo $lg_xprimary;?></td>
						<td id="myborder" width="25%"><input name="iskawasan" type="checkbox" id="iskawasan" value="1" <?php if($iskawasan) echo "checked"?> ><?php echo $lg_kariah;?></td>
					    <td id="myborder" width="25%"><input name="ishostel" type="checkbox" id="ishostel" value="1" <?php if($ishostel) echo "checked"?> ><?php echo $lg_hostel;?></td>
					  </tr>
					  <tr>
						<td id="myborder" width="25%"><input name="isstaff" type="checkbox" id="isstaff" value="1" <?php if($isstaff) echo "checked"?> ><?php echo $lg_staff_child;?></td>
						<td id="myborder" width="25%"><input name="isyatim" type="checkbox" id="isyatim" value="1" <?php if($isyatim) echo "checked"?> ><?php echo $lg_orphan;?></td>
						<!-- 
							<td id="myborder" width="25%"><input name="isfakir" type="checkbox" id="isfakir" value="1" <?php if($isfakir) echo "checked"?> ><?php echo $lg_fakir;?></td>
						-->
						
					  </tr>
					 <?php if($si_fee_show_old_fee){?>
					  <tr>
					  	<td id="myborder">**Tunggakan Yuran Lama <input name="feehutang" type="text"  id="feehutang"  value="<?php echo "$feehutang";?>"></td>
					  </tr>
						<?php } ?>
						<tr>
					  	<td id="myborder">SPONSERED:
						
						<select name="sponser" id="sponser" >
					<?php	
						if($sponser==""){
							echo "<option value=\"\">- $lg_none -</option>";
							$sql="select * from client order by name";
						}
						else{
							echo "<option value=\"$sponser\">$sponser</option>";
							$sql="select * from client order by name";
						}
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['name'];
									echo "<option value=\"$s\">$s</option>";
						}
						echo "<option value=\"\">- $lg_none -</option>";
						?>
                  		</select></td>
					  </tr>
					</table>
</td></tr></table>
<div id="mytitlebg"><?php echo strtoupper($lg_student_information);?></div>
		  
<table width="100%" bgcolor="#FFFFCC">
 	<tr>
	<td width="50%" id="myborder" valign="top">
			  
			  <table width="100%" >
                <tr>
					  <td width="22%" id="myborder"  bgcolor="#FAFAFA">
					  	<div style="height:130px">
					  	<?php if($file!=""){?> <img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="100%" height="100%" >
							<?php } else echo $lg_picture;?>
						</div>
					  </td>
					  <td width="76%" valign="top">
					  		* <?php echo $lg_name;?> <input name="namapelajar" type="text" value="<?php echo stripslashes($namapelajar);?>" size="50"><br><br>
							* <?php echo $lg_ic;?> <input name="ic" type="text" id="ic" value="<?php echo $ic;?>"><font color="#0000FF" size="1"> (<?php echo $lg_no_space_and_dash;?>)</font>
							<br><br>
							* <?php echo $lg_sex;?>&nbsp;&nbsp;
							<select name="sex" id="sex" >
					<?php	
						if($sex==""){
							echo "<option value=\"\">- $lg_select -</option>";
							$sql="select * from type where grp='sex' order by val";
						}
						else{
							$sexname=$lg_malefemale[$sex];
							echo "<option value=\"$sex\">$sexname</option>";
							$sql="select * from type where grp='sex' and prm!='$sex' order by val"; 	
						}
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									$v=$row['val'];
									echo "<option value=\"$v\">$s</option>";
						}
						?>
                  		</select>
						<select name="race" id="race" >
						<?php	
									if($race==""){
										echo "<option value=\"\">- $lg_select -</option>";
										$sql="select * from type where grp='race' order by val";
									}
									else{
										echo "<option value=\"$race\">$race</option>";
										$sql="select * from type where grp='race' and prm!='$race' order by val"; 	
									}
									$res=mysql_query($sql)or die("query failed:".mysql_error());
									while($row=mysql_fetch_assoc($res)){
												$s=$row['prm'];
												echo "<option value=\"$s\">$s</option>";
									}
						?>
                  </select>
				  <select name="religion" id="religion" >
                      <?php	
								if($religion=="")
									echo "<option value=\"\">- $lg_select -</option>";
								else
									echo "<option value=\"$religion\">$religion</option>";
								$sql="select * from type where grp='religion' and prm!='$religion' order by val"; 	
								$sql="select * from type where grp='religion' order by val";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
											$s=$row['prm'];
											echo "<option value=\"$s\">$s</option>";
								}
					?>
                  </select>
				  <br><br>
					* <?php echo $lg_birth_date;?>
					<select name="day" id="day">
							  <?php 
								if($d=="")
									echo "<option value=\"\">- $lg_day -</option>";
								else
									echo "<option value=\"$d\">$d</option>";
							  	for($i=1;$i<=31;$i++) 
									echo "<option value=\"$i\">$i</option>" 
							  ?>
							</select>
							  <select name="month" id="month">
							   
								<?php 
								if($m=="")
									echo "<option value=\"\">- $lg_month -</option>";
								else
									echo "<option value=\"$m\">$m</option>";
								for($i=1;$i<=12;$i++) 
									echo "<option value=\"$i\">$i</option>" 
								?>
							  </select>
							  <select name="year" id="year">
								<?php 
									if($y=="")
										echo "<option value=\"\">- $lg_year -</option>";
									else
										echo "<option value=\"$y\">$y</option>";
									$yy=date("Y");
									$my=$yy-30;
									for($i=$yy;$i>$my;$i--) 
										echo "<option value=\"$i\">$i</option>" 
								?>
							  </select>
							  
							  <select name="citizen">
							<?php	
								if($citizen=="")
									echo "<option value=\"\">- $lg_citizen -</option>";
								else
									echo "<option value=\"$citizen\">$citizen</option>";
								$sql="select * from country order by name";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
											$s=$row['name'];
											echo "<option value=\"$s\">$s</option>";
								}			
							?>
						</select>
						<br><br>
						<?php echo $lg_birth_place;?>
						<select name="bstate" id="bstate" >
                      <?php	
						if($state=="")
							echo "<option value=\"\">- $lg_birth_place -</option>";
						else
							echo "<option value=\"$bstate\">$bstate</option>";
						$sql="select * from state where name!='$bstate' order by name";
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['name'];
									echo "<option value=\"$s\">$s</option>";
						}
					?>
                    </select>  
					   </td>
                </tr>
				<tr>
						<td colspan="2"><input type="file" name="file" size="5">Image Not More Then 100k. Please resize.</td>
				</tr>
  			 	<tr>
                  <td><?php echo $lg_previous_school;?></td>
                  <td><input name="pschool" type="text"  id="pschool" size="50" value="<?php echo "$pschool";?>"> </td>
                </tr>
				<tr>
                  <td><?php echo $lg_illness;?></td>
                  <td> <input name="ill" type="text"  id="ill" size="50" value="<?php echo "$ill";?>"></td>
                </tr>
				<tr>
                  <td><?php echo $lg_transport;?></td>
                  <td>
				  <select name="transport" id="transport" >
						<?php	
						if($transport=="")
							echo "<option value=\"\">- $lg_select -</option>";
						else
							echo "<option value=\"$transport\">$transport</option>";
						$sql="select * from type where grp='transport' order by val";
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
							$s=$row['prm'];
							echo "<option value=\"$s\">$s</option>";
						}
						?>
                  </select> 
                  </td>
                </tr>
              </table>
			  
			  
	</td>
    <td width="50%" id="myborder" valign="top">
			 
			 
			 
			 <table width="100%" >
			 		<tr>
					  	<td id="myborder" valign="top"><?php echo $lg_home_address;?>
						<br>
							<font color="#0000FF" size="1">
								<?php echo $lg_example;?>: <br>
								No 5-1<br>
								Jalan USJ 1/1A,<br>
								Taman Subang,<br>
								(<?php echo $lg_seperate_by_coma;?>)
							</font>
						</td>
					  	<td><textarea name="addr" cols="30" rows="3" id="addr"><?php echo $addr;?></textarea></td>
				</tr>
				<tr>
								  <td id="myborder" ><?php echo $lg_city;?></td>
								  <td><input name="bandar" type="text" value="<?php echo $bandar;?>"></td>
				</tr>
				<tr>
								  <td id="myborder" ><?php echo $lg_postcode;?></td>
								  <td><input name="poskod" type="text" value="<?php echo $poskod;?>"></td>
				</tr>
				  <tr>
						<td id="myborder" ><?php echo $lg_state;?></td>
						<td><select name="state" id="state" >
							<?php	
								if($state=="")
									echo "<option value=\"\">- $lg_select -</option>";
								else
									echo "<option value=\"$state\">$state</option>";
								$sql="select * from state where name!='$state' order by name";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
											$s=$row['name'];
											echo "<option value=\"$s\">$s</option>";
								}
						?>
						  </select>
						</td>
					  </tr>
					   <tr>
                  <td id="myborder" ><?php echo $lg_house_telephone;?></td>
                  <td><input name="tel" type="text" id="tel" value="<?php echo $tel;?>">
                </tr>
				<tr>
                  <td id="myborder" ><?php echo "$lg_handphone ($lg_parent)";?></td>
                  <td><input name="hp" type="text" id="hp" value="<?php echo $hp;?>"> 
                  <font color="#0000FF" size="1">Eg: 60120000000 (start with 6)</font></td>
                </tr>
                <tr>
                  <td id="myborder" ><?php echo "$lg_email ($lg_parent)";?></td>
                  <td><input name="mel" type="text" id="mel" value="<?php echo $mel;?>" size="38"></td>
                </tr>

              </table>
	</td>
	</tr>
</table>

<table width="100%" bgcolor="#FFFFCC">
	<tr>
	<td width="50%" id="myborder">
	
<div id="mytitlebg">B. <?php echo strtoupper($lg_father_information);?></div>
<table width="100%">
          	<tr>
					<td id="myborder" width="30%"><?php echo $lg_name;?> *</td>
					<td width="70%"><input name="p1name" type="text" id="p1name" value="<?php echo $p1name;?>" size="49"></td>
          	</tr>
            <tr>
					<td id="myborder"><?php echo $lg_ic_number;?> *</td>
					<td><input name="p1ic" type="text" id="p1ic" value="<?php echo $p1ic;?>"><font color="#0000FF" size="1">(<?php echo $lg_no_space_and_dash;?>)</font></td>
            </tr>
			<tr>
					<td id="myborder"><?php echo $lg_citizen;?> *</td>
					<td> <select name="p1wn">
							<?php	
								if($p1wn=="")
									echo "<option value=\"Malaysia\">Malaysia</option>";
								else
									echo "<option value=\"$p1wn\">$p1wn</option>";
								$sql="select * from country order by name";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
											$s=$row['name'];
											echo "<option value=\"$s\">$s</option>";
								}			
							?>
						</select>
					</td>
            </tr>
            <tr>
					<td id="myborder"><?php echo $lg_handphone;?></td>
					<td><input name="p1hp" type="text" id="p1hp" value="<?php echo $p1hp;?>"></td>
            </tr>
			<tr>
					<td id="myborder"><?php echo $lg_email;?></td>
					<td><input name="p1mel" type="text" id="p1mel" value="<?php echo $p1mel;?>" size="49"></td>
  			</tr>
            <tr>
					<td id="myborder"><?php echo $lg_job;?></td>
					<td><input name="p1job" type="text" id="p1job" value="<?php echo $p1job;?>" size="49"></td>
            </tr>
			<tr>
					<td id="myborder"><?php echo $lg_employer;?></td>
					<td><input name="p1com" type="text" id="p1com" value="<?php echo $p1com;?>" size="49"></td>
            </tr>
			<tr>
					<td id="myborder" valign="top"><?php echo $lg_office_address;?><br><font color="#0000FF" size="1"><?php echo $lg_seperate_by_coma;?></font><br></td>
					<td><textarea name="p1addr" cols="37" rows="4" id="p1addr"><?php echo $p1addr;?></textarea></td>
            </tr>
            <tr>
					<td id="myborder"><?php echo $lg_office_telephone;?></td>
					<td><input name="p1tel2" type="text" id="p1tel2" value="<?php echo $p1tel2;?>"> <?php echo $lg_fax;?> <input name="p1fax" type="text" id="p1fax" value="<?php echo $p1fax;?>"></td>
            </tr>
			<tr>
					<td id="myborder"><?php echo $lg_salary;?> (RM)</td>
					<td><input name="p1sal" type="text" value="<?php echo $p1sal;?>"> <font color="#0000FF" size="1"><?php echo $lg_example;?> 5500</font></td>
            </tr>
			<tr>
					<td id="myborder"><?php echo $lg_academic_status;?></td>
					<td>
					<select name="p1edu">
							<?php	
								if($p1edu=="")
									echo "<option value=\"\">- $lg_select -</option>";
								else
									echo "<option value=\"$p1edu\">$p1edu</option>";
								$sql="select * from type where grp='kelulusan' and prm!='$p1edu' order by idx";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
											$s=$row['prm'];
											echo "<option value=\"$s\">$s</option>";
								}			
							?>
						</select>
					</td>
            </tr>
</table>

</td>
<td width="50%" id="myborder" valign="top">
	
<div id="mytitlebg">C. <?php echo strtoupper($lg_mother_information);?></div>
<table width="100%" >
          	<tr>
					<td id="myborder" width="30%"><?php echo $lg_name;?></td>
					<td width="70%"><input name="p2name" type="text" id="p2name" value="<?php echo $p2name;?>" size="49"></td>
          	</tr>
			 <tr>
					<td id="myborder"><?php echo $lg_ic_number;?></td>
					<td><input name="p2ic" type="text" id="p2ic" value="<?php echo $p2ic;?>"><font color="#0000FF" size="1">(<?php echo $lg_no_space_and_dash;?>)</font></td>
            </tr>
            <tr>
					<td id="myborder"><?php echo $lg_citizen;?></td>
					<td> <select name="p2wn" id="p2wn" >
							<?php	
								if($p2wn=="")
									echo "<option value=\"Malaysia\">Malaysia</option>";
								else
									echo "<option value=\"$p2wn\">$p2wn</option>";
								$sql="select * from country order by name";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
											$s=$row['name'];
											echo "<option value=\"$s\">$s</option>";
								}			
							?>
						</select>
					</td>
            </tr>
            <tr>
					<td id="myborder"><?php echo $lg_handphone;?></td>
					<td><input name="p2hp" type="text" id="p2hp" value="<?php echo $p2hp;?>"></td>
            </tr>
			<tr>
					<td id="myborder"><?php echo $lg_email;?></td>
					<td><input name="p2mel" type="text" id="p2mel" value="<?php echo $p2mel;?>" size="49"></td>
  			</tr>
            <tr>
					<td id="myborder"><?php echo $lg_job;?></td>
					<td><input name="p2job" type="text" id="p2job" value="<?php echo $p2job;?>" size="49"></td>
            </tr>
            <tr>
					<td id="myborder"><?php echo $lg_employer;?></td>
					<td><input name="p2com" type="text" id="p2com" value="<?php echo $p2com;?>" size="49"></td>
            </tr>
			<tr>
					<td id="myborder" valign="top"><?php echo $lg_office_address;?><br><font color="#0000FF" size="1"><?php echo $lg_seperate_by_coma;?></font><br></td></td>
					<td><textarea name="p2addr" cols="37" rows="4" id="p2addr"><?php echo $p2addr;?></textarea></td>
            </tr>
			<tr>
					<td id="myborder"><?php echo $lg_office_telephone;?></td>
					<td><input name="p2tel2" type="text" id="p2tel2" value="<?php echo $p2tel2;?>"> <?php echo $lg_fax;?> <input name="p2fax" type="text" id="p2fax" value="<?php echo $p2fax;?>"></td>
            </tr>
			<tr>
					<td id="myborder"><?php echo $lg_salary;?> (RM)</td>
					<td><input name="p2sal" type="text" value="<?php echo $p2sal;?>"> <font color="#0000FF" size="1"><?php echo $lg_example;?> 5500</font></td>
            </tr>
			<tr>
					<td id="myborder"><?php echo $lg_academic_status;?></td>
					<td>
					<select name="p2edu">
							<?php	
								if($p2edu=="")
									echo "<option value=\"\">- $lg_select -</option>";
								else
									echo "<option value=\"$p2edu\">$p2edu</option>";
								$sql="select * from type where grp='kelulusan' and prm!='$p2edu' order by idx,prm";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
											$s=$row['prm'];
											echo "<option value=\"$s\">$s</option>";
								}			
							?>
						</select>
					</td>
            </tr>
</table>

</td></tr>
</table>

<table width="100%" bgcolor="#FFFFCC">
	<tr>
	<td width="50%" id="myborder">
	
<div id="mytitlebg">D. <?php echo strtoupper($lg_family_information);?> </div>
<table width="100%" cellspacing="0" >
                <tr>
                  <td width="35%"  bgcolor="#FAFAFA"><?php echo $lg_name;?></td>
                  <td width="60%" bgcolor="#FAFAFA"><?php echo $lg_school_ipt_job;?></td>
                  <td width="5%" bgcolor="#FAFAFA"><?php echo $lg_birth_year;?></td>
                </tr>
				<tr>
                  <td><input name="q01" type="text" id="q01" value="<?php echo "$q01";?>" size="40"></td>
                  <td><input name="q02" type="text" id="q02" value="<?php echo "$q02";?>" size="40"></td>
                  <td><input name="q03" type="text" id="q03" value="<?php echo "$q03";?>" size="5"></td>
                </tr>
                <tr>
                  <td><input name="q11" type="text" id="q11" value="<?php echo "$q11";?>" size="40"></td>
                  <td><input name="q12" type="text" id="q12" value="<?php echo "$q12";?>" size="40"></td>
                  <td><input name="q13" type="text" id="q13" value="<?php echo "$q13";?>" size="5"></td>
                </tr>
                <tr>
                  <td><input name="q21" type="text" id="q21" value="<?php echo "$q21";?>" size="40"></td>
                  <td><input name="q22" type="text" id="q22" value="<?php echo "$q22";?>" size="40"></td>
                  <td><input name="q23" type="text" id="q23" value="<?php echo "$q23";?>" size="5"></td>
                </tr>
                <tr>
                  <td><input name="q31" type="text" id="q31" value="<?php echo "$q31";?>" size="40"></td>
                  <td><input name="q32" type="text" id="q32" value="<?php echo "$q32";?>" size="40"></td>
                  <td><input name="q33" type="text" id="q33" value="<?php echo "$q33";?>" size="5"></td>
                </tr>
				<tr>
                  <td><input name="q41" type="text" id="q41" value="<?php echo "$q41";?>" size="40"></td>
                  <td><input name="q42" type="text" id="q42" value="<?php echo "$q42";?>" size="40"></td>
                  <td><input name="q43" type="text" id="q43" value="<?php echo "$q43";?>" size="5"></td>
                </tr>
				<tr>
                  <td><input name="q51" type="text" id="q51" value="<?php echo "$q51";?>" size="40"></td>
                  <td><input name="q52" type="text" id="q52" value="<?php echo "$q52";?>" size="40"></td>
                  <td><input name="q53" type="text" id="q53" value="<?php echo "$q53";?>" size="5"></td>
                </tr>
				<tr>
                  <td><input name="q61" type="text" id="q61" value="<?php echo "$q61";?>" size="40"></td>
                  <td><input name="q62" type="text" id="q62" value="<?php echo "$q62";?>" size="40"></td>
                  <td><input name="q63" type="text" id="q63" value="<?php echo "$q63";?>" size="5"></td>
                </tr>
				<tr>
                  <td><input name="q71" type="text" id="q71" value="<?php echo "$q71";?>" size="40"></td>
                  <td><input name="q72" type="text" id="q72" value="<?php echo "$q72";?>" size="40"></td>
                  <td><input name="q73" type="text" id="q73" value="<?php echo "$q73";?>" size="5"></td>
                </tr>
				<tr>
                  <td><input name="q81" type="text" id="q81" value="<?php echo "$q81";?>" size="40"></td>
                  <td><input name="q82" type="text" id="q82" value="<?php echo "$q82";?>" size="40"></td>
                  <td><input name="q83" type="text" id="q83" value="<?php echo "$q83";?>" size="5"></td>
                </tr>
				<tr>
                  <td><input name="q91" type="text" id="q91" value="<?php echo "$q91";?>" size="40"></td>
                  <td><input name="q92" type="text" id="q92" value="<?php echo "$q92";?>" size="40"></td>
                  <td><input name="q93" type="text" id="q93" value="<?php echo "$q93";?>" size="5"></td>
                </tr>
			</table>

</td>
<td width="50%" id="myborder" valign="top">
	
<div id="mytitlebg">E. <?php echo strtoupper($lg_contact);?> (<?php echo $lg_for_emergency_call;?>)</div>
<table width="100%">
          	<tr>
					<td id="myborder" width="30%"><?php echo $lg_name;?></td>
					<td width="70%"><input name="p3name" type="text" id="p3name" value="<?php echo $p3name;?>" size="49"></td>
          	</tr>
			<tr>
					<td id="myborder"><?php echo $lg_relation;?></td>
					<td>
							<select name="p3rel" id="p3rel" >
							<?php	
							if($p3rel==""){
								echo "<option value=\"\">-$lg_select-</option>";
								$sql="select * from type where grp='parent' order by val";
							}
							else{
								echo "<option value=\"$p3rel\">$p3rel</option>";
								$sql="select * from type where grp='parent' and prm!='$p3rel' order by val"; 	
							}
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
										$s=$row['prm'];
										echo "<option value=\"$s\">$s</option>";
							}
							mysql_free_result($res);					  
				
							?>
							 </select>
					</td>
          	</tr>
            <tr>
					<td id="myborder"><?php echo $lg_ic_number;?></td>
					<td><input name="p3ic" type="text" id="p3ic" value="<?php echo $p3ic;?>"><font color="#0000FF" size="1">(<?php echo $lg_no_space_and_dash;?>)</font></td>
            </tr>
			<tr>
					<td id="myborder" valign="top"><?php echo $lg_address;?><br><font color="#0000FF" size="1"><?php echo $lg_seperate_by_coma;?></font><br></td>
					<td><textarea name="p3addr" cols="37" rows="4" id="p3addr"><?php echo $p3addr;?></textarea></td>
            </tr>
            <tr>
					<td id="myborder"><?php echo $lg_house_telephone;?></td>
					<td><input name="p3tel" type="text" id="p3tel" value="<?php echo $p3tel;?>"> H/P <input name="p3hp" type="text" id="p3hp" value="<?php echo $p3hp;?>"></td>
            </tr>
			<tr>
					<td id="myborder"><?php echo $lg_office_telephone;?></td>
					<td><input name="p3tel2" type="text" id="p3tel2" value="<?php echo $p3tel2;?>"> <?php echo $lg_fax;?> <input name="p3fax" type="text" id="p3fax" value="<?php echo $p3fax;?>"></td>
            </tr>
			<tr>
					<td id="myborder"><?php echo $lg_email;?></td>
					<td><input name="p3mel" type="text" id="p3mel" value="<?php echo $p3mel;?>" size="49"></td>
  			</tr>
</table>

</td></tr>
</table>

</div>
</form>	
</body>
</html>
