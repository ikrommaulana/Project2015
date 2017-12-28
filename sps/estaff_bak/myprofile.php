<?php
//10/05/2010 - interface
$vmod="v6.0.0";
$vdate="110701";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
$username = $_SESSION['username'];
$f=$_REQUEST['f'];
$uid=$_SESSION['username'];
$sid=$_SESSION['sid'];
$operation=$_REQUEST['operation'];
if($operation!=""){
	include_once('../estaff/usrsave.php');
}
if($uid!=""){
			$sql="select * from usr where uid='$uid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sch_id'];
			$uid=$row['uid'];
			$pass=$row['pass'];
			$name=stripslashes($row['name']);
			$ic=$row['ic'];
			$sex=$row['sex'];
			$race=$row['race'];
			$religion=$row['religion'];
			$bday=$row['bday'];
			list($yy,$mm,$dd)=split('[/.-]',$bday);
			$tel=$row['tel'];
			$tel2=$row['tel2'];
			$fax=$row['fax'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$addr=$row['addr'];
			$state=$row['state'];
			$status=$row['status'];
			$job=$row['job'];
			$jobdiv=$row['jobdiv'];
			$joblevel=$row['joblvl'];
			$jobsta=$row['jobsta'];
			$jobstart=$row['jobstart'];
			$jobend=$row['jobend'];
			$syslevel=$row['syslevel'];
			$file=$row['file'];
			
			$q1=explode("|",$row['q1']);
			$q2=explode("|",$row['q2']);
			$q3=explode("|",$row['q3']);
			$q4=explode("|",$row['q4']);
			$q5=explode("|",$row['q5']);
			
			$x1=explode("|",$row['x1']);
			$x2=explode("|",$row['x2']);
			$x3=explode("|",$row['x3']);
			$x4=explode("|",$row['x4']);
			$x5=explode("|",$row['x5']);
			
			$a1=explode("|",$row['a1']);
			$a2=explode("|",$row['a2']);
			$a3=explode("|",$row['a3']);
			$a4=explode("|",$row['a4']);
			$a5=explode("|",$row['a5']);
			$a6=explode("|",$row['a6']);
			$a7=explode("|",$row['a7']);
			$a8=explode("|",$row['a8']);
			$a9=explode("|",$row['a9']);
			$a10=explode("|",$row['a10']);
			$a11=explode("|",$row['a11']);
			
			mysql_free_result($res);
		}
		if($sid>0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            mysql_free_result($res);					  
		}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
function chcolor(el,color){
	document.getElementById('tab1').style.background='#FAFAFA';
	document.getElementById('tab2').style.background='#FAFAFA';
	document.getElementById('tab3').style.background='#FAFAFA';
	document.getElementById('tab4').style.background='#FAFAFA';
	document.getElementById('tab5').style.background='#FAFAFA';
	el.style.background=color;
}
function process_form(operation){
	if(operation=='delete'){
		ret = confirm("Are you sure want to delete??");
		if (ret == true){
			document.myform.operation.value=operation;
			document.myform.submit();
		}
		return;
	
	}
	else{
		if(document.myform.uid.value==""){
			alert("Enter the staff ID");
			document.myform.uid.focus();
			return;
		}
		if(document.myform.syslevel.value==""){
			alert("Select system access level");
			document.myform.syslevel.focus();
			return;
		}
		if(document.myform.sid.value==""){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.name.value==""){
			alert("Enter the fullname");
			document.myform.name.focus();
			return;
		}
		if(document.myform.ic.value==""){
			alert("Enter the IC number");
			document.myform.ic.focus();
			return;
		}
		if(document.myform.file.value!=""){
			fn=document.myform.file.value;
			len=fn.length;
			ext=fn.substr(len-3,3);
			if((ext.toLowerCase()!="gif")&&(ext.toLowerCase()!="jpg")){
				alert("Invalid format. Only GIF and JPG allowed");
				document.form1.uploadedfile.focus();
				return;
			}
		}
		ret = confirm("Are you sure want to save??");
		if (ret == true){
			document.myform.operation.value=operation;
			document.myform.submit();
		}
		return;
		
	}
}



</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="myform">
<input type="hidden" name="p" value="../estaff/myprofile">
<input type="hidden" name="operation" >
<input type="hidden" name="status" value="<?php echo $status;?>">


<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
		<a href="#" onClick="process_form('update')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br><?php echo $lg_save;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.submit()"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>	
		<a href="#" onClick="window.close();parent.$.fancybox.close();"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br>Close</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>                       	
        
        </div> <!-- end mymenu -->
		<div align="right">
				<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
</div> <!-- end mypanel -->
<div id="story">
<div id="mytitle2"><?php echo $lg_personal_information;?> <?php echo $f;?></div>
<div id="mytabdiv">
	<div id="tab1" class="mytab" onClick="chcolor(this,'#DDEEEE');show('peribadi');hide('jawatan');hide('akademik');hide('keluarga');hide('password')" style="background-color:#DDEEEE;"><a href="#"><?php echo $lg_profile;?></a></div>
	<div id="tab2" class="mytab" onClick="chcolor(this,'#DDEEEE');hide('peribadi');show('jawatan');hide('akademik');hide('keluarga');hide('password')"><a href="#"><?php echo $lg_job;?></a></div>
	<div id="tab3" class="mytab" onClick="chcolor(this,'#DDEEEE');hide('peribadi');hide('jawatan');show('akademik');hide('keluarga');hide('password')"><a href="#"><?php echo $lg_academic;?></a></div>
	<div id="tab4" class="mytab" onClick="chcolor(this,'#DDEEEE');hide('peribadi');hide('jawatan');hide('akademik');show('keluarga');hide('password')"><a href="#"><?php echo $lg_family;?></a></div>
	<div id="tab5" class="mytab" onClick="chcolor(this,'#DDEEEE');hide('peribadi');hide('jawatan');hide('akademik');hide('keluarga');show('password')"><a href="#">Password</a></div>
	<br><br>
</div>
<div id="myborder"></div>
<div id="peribadi" style="border:1px solid #CCCCFF ">
 	<div id="mytabletitle" style=" font-size:120%; border:none; padding:3px 3px 3px 3px">A. <?php echo $lg_personal_information;?></div>
		  
	<table width="100%" style="font-size:12px;">
            <tr>
			<td width="10%" valign="top" bgcolor="#FAFAFA">
						  <table width="150px" height="150px" bgcolor="#CCCCCC">
						  <tr>
							<td bgcolor="#FFFFFF">
							<?php if($file!=""){?>
							<img name="picture" src="<?php if($file!="") echo "../adm/imgresize.php?w=88&h=90&img=$dir_image_user$file"; ?>"  width="145" height="150" >
							<?php } ?>
							</td>
						  </tr>
						</table>
						<input type="file" name="file" size="7">
		</td>
              <td width="90%"><table width="100%" >
                <tr>
                  <td><?php echo $lg_staff_id;?></td>
				  <td width="1%">:</td>
                  <td ><input name="uidx" type="text" id="uid" value="<?php echo $uid;?>" readonly>
                  </td>
                </tr>
              
				<tr>
                  <td>System Level</td>
				  <td>:</td>
                  <td><input name="syslevel" type="text" value="<?php echo $syslevel;?>" readonly></td>
				</tr>
				 <tr>
                  <td width="20%" >System Access</td>
				  <td>:</td>
                  <td width="80%" >
<?php	
					if($sid=="0")
						$access="All School Access";
					else
						$access=$sname;
?>
                    <input name="access" type="text" size="48"  value="<?php echo $access;?>" readonly>
					<input type="hidden" name="sid" value="<?php echo $sid;?>">
                  </td>
                </tr>
                <tr>
                <tr>
                  <td width="20%" ><?php echo $lg_name;?></td>
				  <td>:</td>
                  <td width="80%"><input name="name"  size="48"  type="text" id="name" value="<?php echo $name;?>" readonly>
                  </td>
                </tr>
                <tr>
                  <td><?php echo $lg_ic_number;?></td>
				  <td>:</td>
                  <td><input name="ic" type="text" id="ic" value="<?php echo $ic;?>" readonly>
					<select name="sex" id="sex" readonly>
               <?php	
			   $sexname=$lg_malefemale[$sex];
      		if($sex==""){
				echo "<option value=\"\">- $lg_sex -</option>";
				$sql="select * from type where grp='sex' order by val";
			}
			else{
				echo "<option value=\"$sex\">$sexname</option>";
				$sql="select * from type where grp='sex' and val!='$sex' order by val"; 	
			}
			/*
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$v\">$s</option>";
            }
            mysql_free_result($res);	
			*/				  

?>
                  </select>
				  <select name="race" id="race" >
                      <?php	
      		
			if($race==""){
				echo "<option value=\"\">- $lg_race -</option>";
				$sql="select * from type where grp='race' order by val";
			}
			else{
				echo "<option value=\"$race\">$race</option>";
				$sql="select * from type where grp='race' and prm!='$race' order by val"; 	
			}
			/*
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$s</option>";
            }
            mysql_free_result($res);					  
	*/
?>
                  </select>
				  <select name="religion" id="religion" >
                      <?php	
      		if($religion==""){
				echo "<option value=\"\">- $lg_religion -</option>";
				$sql="select * from type where grp='religion' order by val";
			}
			else{
				echo "<option value=\"$religion\">$religion</option>";
				$sql="select * from type where grp='religion' and prm!='$religion' order by val"; 	
			}
			/*
			$sql="select * from type where grp='religion' order by val";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$s</option>";
            }  
	*/
?>
                  </select></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_birth_date;?></td>
				  <td>:</td>
                  <td><select name="day" id="day">
                      <?php 
					//if($dd=="")
						//	echo "<option value=\"\">- $lg_day -</option>";
					//else
							echo "<option value=\"$dd\">$dd</option>";
					 // for($i=1;$i<=31;$i++) 
					  	//	echo "<option value=\"$i\">$i</option>" 
					  ?>
                    </select>
                      <select name="month" id="month">
                       
                        <?php 
						//if($mm=="")
						//	echo "<option value=\"\">- $lg_month -</option>";
						//else
							echo "<option value=\"$mm\">$mm</option>";
						//for($i=1;$i<=12;$i++) 
						//	echo "<option value=\"$i\">$i</option>" 
						?>
                      </select>
                      <select name="year" id="year">
                       
                        <?php 
						//	if($yy=="")
							//	echo "<option value=\"\">- $lg_year -</option>";
							//else
								echo "<option value=\"$yy\">$yy</option>";
							//for($i=2000;$i>1940;$i--) 
							//	echo "<option value=\"$i\">$i</option>" 
						?>
                      </select>
                  </td>
                </tr>
                <tr>
                  <td ><?php echo $lg_tel_home;?></td>
				  <td>:</td>
                  <td><input name="tel" type="text" id="tel" size="10" value="<?php echo $tel;?>"></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_tel_mobile;?></td>
				  <td>:</td>
                  <td><input name="hp" type="text" id="hp" size="10" value="<?php echo $hp;?>">(<?php echo $lg_no_space_and_dash;?>)</td>
                </tr>
                <tr>
                  <td ><?php echo $lg_email;?></td>
				  <td>:</td>
                  <td><input name="mel" type="text" id="mel" value="<?php echo $mel;?>" size="38"></td>
                </tr>
				<tr>
					<td ><?php echo $lg_address;?></td>
					<td>:</td>
					<td><textarea name="addr" cols="37" rows="3" id="addr"><?php echo $addr;?></textarea></td>
				  </tr>
				  <tr>
					<td ><?php echo $lg_state;?></td>
					<td>:</td>
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
 		</table>
	</td>
    
	</tr>
	</table>
</div><!-- end peribadi -->
<div id="jawatan" style="display:none;border:1px solid #CCCCFF ">
 	<div id="mytabletitle" style=" font-size:120%; border:none; padding:3px 3px 3px 3px">B. <?php echo $lg_job_information;?></div>
<table width="100%"  border="0" cellpadding="0" cellspacing="3" style="font-size:12px;">
			<tr>
            	<td width="15%"><?php echo $lg_division;?></td>
				<td width="1%">:</td>
                <td width="85%"><input type="text" name="jobdiv" id="jobdiv" value="<?php echo $jobdiv;?>" readonly size="60"></td>
            </tr>
			<tr>
              <td><?php echo $lg_job;?></td>
			  <td>:</td>
              <td><input type="text" name="job" id="job" value="<?php echo $job;?>" readonly  size="60"></td>
            </tr>
			<tr>
              <td><?php echo $lg_grade;?></td>
			  <td>:</td>
              <td><input type="text" name="joblevel" size="3" value="<?php echo $joblevel;?>" readonly  size="60"></td>
            </tr>
			<tr>
              <td><?php echo $lg_status;?></td>
			  <td>:</td>
              <td><input type="text" name="jobsta" id="jobsta" value="<?php echo $jobsta;?>" readonly></td>
            </tr>
			          <tr>
            <td ><?php echo $lg_start;?></td>
			<td>:</td>
            <td><input name="jobstart" type="text" id="jobstart" value="<?php echo $jobstart;?>" readonly>
</td>
          </tr>
		  			          <tr>
            <td ><?php echo $lg_end;?></td>
			<td>:</td>
            <td>
              <input name="jobend" type="text" id="jobend" value="<?php echo $jobend;?>" readonly></td>
          </tr>
</table>
</div>
<div id="akademik" style="display:none;border:1px solid #CCCCFF ">
 	<div id="mytabletitle" style=" font-size:120%; border:none; padding:3px 3px 3px 3px">C. <?php echo $lg_academic_information;?></div>
			  <table width="100%"  border="0" style="font-size:12px;">
                <tr>
                  <td width="10%"><?php echo $lg_academic_level;?></td>
                  <td width="20%"><?php echo $lg_institution;?></td>
                  <td width="70%"><?php echo $lg_year;?></td>
                 
                </tr>
               <tr>
                  <td><input name="q1[]" type="text" id="q11" value="<?php echo "$q1[0]";?>" readonly></td>
                  <td><input name="q1[]" type="text" id="q12" value="<?php echo "$q1[1]";?>" readonly size="60"></td>
                  <td><input name="q1[]" type="text" id="q13" value="<?php echo "$q1[2]";?>" readonly size="8"></td>
                </tr>
                <tr>
                  <td><input name="q2[]" type="text" id="q21" value="<?php echo "$q2[0]";?>" readonly></td>
                  <td><input name="q2[]" type="text" id="q22" value="<?php echo "$q2[1]";?>" readonly size="60"></td>
                  <td><input name="q2[]" type="text" id="q23" value="<?php echo "$q2[2]";?>" readonly size="8"></td>
                 
                </tr>
                <tr>
                  <td><input name="q3[]" type="text" id="q31" value="<?php echo "$q3[0]";?>" readonly></td>
                  <td><input name="q3[]" type="text" id="q32" value="<?php echo "$q3[1]";?>" readonly size="60"></td>
                  <td><input name="q3[]" type="text" id="q33" value="<?php echo "$q3[2]";?>" readonly size="8"></td>
                </tr>
				<tr>
                  <td><input name="q4[]" type="text" id="q41" value="<?php echo "$q4[0]";?>" readonly></td>
                  <td><input name="q4[]" type="text" id="q42" value="<?php echo "$q4[1]";?>" readonly size="60"></td>
                  <td><input name="q4[]" type="text" id="q43" value="<?php echo "$q4[2]";?>" readonly size="8"></td>
                </tr>
				<tr>
                  <td><input name="q5[]" type="text" id="q51" value="<?php echo "$q5[0]";?>" readonly></td>
                  <td><input name="q5[]" type="text" id="q52" value="<?php echo "$q5[1]";?>" readonly size="60"></td>
                  <td><input name="q5[]" type="text" id="q53" value="<?php echo "$q5[2]";?>" readonly size="8"></td>
                </tr>              </table>


<div id="mytitlebg"><?php echo $lg_working_experience;?></div>
			  <table width="100%"  border="0" style="font-size:12px;">
                <tr>
                  <td width="10%"><?php echo $lg_position;?></td>
                  <td width="20%"><?php echo $lg_employer;?></td>
                  <td width="70%"><?php echo $lg_year;?></td>

                </tr>
                <tr>
                  <td><input name="x1[]" type="text" id="x11" value="<?php echo "$x1[0]";?>" readonly></td>
                  <td><input name="x1[]" type="text" id="x12" value="<?php echo "$x1[1]";?>" readonly size="60"></td>
                  <td><input name="x1[]" type="text" id="x13" value="<?php echo "$x1[2]";?>" readonly size="8"></td>
                </tr>
                <tr>
                  <td><input name="x2[]" type="text" id="x21" value="<?php echo "$x2[0]";?>" readonly></td>
                  <td><input name="x2[]" type="text" id="x22" value="<?php echo "$x2[1]";?>" readonly size="60"></td>
                  <td><input name="x2[]" type="text" id="x23" value="<?php echo "$x2[2]";?>" size="8" readonly></td>
                </tr>
                <tr>
                  <td><input name="x3[]" type="text" id="x31" value="<?php echo "$x3[0]";?>" readonly></td>
                  <td><input name="x3[]" type="text" id="x32" value="<?php echo "$x3[1]";?>" size="60" readonly></td>
                  <td><input name="x3[]" type="text" id="x33" value="<?php echo "$x3[2]";?>" size="8" readonly></td>
                </tr>
                <tr>
                  <td><input name="x4[]" type="text" id="x41" value="<?php echo "$x4[0]";?>" readonly></td>
                  <td><input name="x4[]" type="text" id="x42" value="<?php echo "$x4[1]";?>" size="60" readonly></td>
                  <td><input name="x4[]" type="text" id="x43" value="<?php echo "$x4[2]";?>" size="8" readonly></td>
                </tr>
	            <tr>
                  <td><input name="x5[]" type="text" id="x51" value="<?php echo "$x5[0]";?>" readonly></td>
                  <td><input name="x5[]" type="text" id="x52" value="<?php echo "$x5[1]";?>" size="60" readonly></td>
                  <td><input name="x5[]" type="text" id="x53" value="<?php echo "$x5[2]";?>" size="8" readonly></td>
                </tr>
              </table>
</div>
<div id="keluarga" style="display:none;border:1px solid #CCCCFF ">
 	<div id="mytabletitle" style=" font-size:120%; border:none; padding:3px 3px 3px 3px">D. <?php echo $lg_family_information;?></div>
			  <table width="100%"  border="0" style="font-size:12px;">
                <tr>
                  <td width="10%"><?php echo $lg_name;?></td>
                  <td width="20%"><?php echo $lg_school_ipt_job;?></td>
                  <td width="70%"><?php echo $lg_birth_year;?></td>

                </tr>
                <tr>
                  <td><input name="a1[]" type="text" id="a11" value="<?php echo "$a1[0]";?>" readonly></td>
                  <td><input name="a1[]" type="text" id="x12" value="<?php echo "$a1[1]";?>" size="60" readonly></td>
                  <td><input name="a1[]" type="text" id="x13" value="<?php echo "$a1[2]";?>" size="8" readonly></td>
                </tr>
                <tr>
                  <td><input name="a2[]" type="text" id="x21" value="<?php echo "$a2[0]";?>" readonly></td>
                  <td><input name="a2[]" type="text" id="x22" value="<?php echo "$a2[1]";?>" size="60" readonly></td>
                  <td><input name="a2[]" type="text" id="x23" value="<?php echo "$a2[2]";?>" size="8" readonly></td>
                </tr>
                <tr>
                  <td><input name="a3[]" type="text" id="x31" value="<?php echo "$a3[0]";?>" readonly></td>
                  <td><input name="a3[]" type="text" id="x32" value="<?php echo "$a3[1]";?>" size="60" readonly></td>
                  <td><input name="a3[]" type="text" id="x33" value="<?php echo "$a3[2]";?>" size="8" readonly></td>
                </tr>
				<tr>
                  <td><input name="a4[]" type="text" id="x31" value="<?php echo "$a4[0]";?>" readonly></td>
                  <td><input name="a4[]" type="text" id="x32" value="<?php echo "$a4[1]";?>" size="60" readonly></td>
                  <td><input name="a4[]" type="text" id="x33" value="<?php echo "$a4[2]";?>" size="8" readonly></td>
                </tr>
				<tr>
                  <td><input name="a5[]" type="text" id="x31" value="<?php echo "$a5[0]";?>" readonly></td>
                  <td><input name="a5[]" type="text" id="x32" value="<?php echo "$a5[1]";?>" size="60" readonly></td>
                  <td><input name="a5[]" type="text" id="x33" value="<?php echo "$a5[2]";?>" size="8" readonly></td>
                </tr>
				<tr>
                  <td><input name="a6[]" type="text" id="x31" value="<?php echo "$a6[0]";?>" readonly></td>
                  <td><input name="a6[]" type="text" id="x32" value="<?php echo "$a6[1]";?>" size="60" readonly></td>
                  <td><input name="a6[]" type="text" id="x33" value="<?php echo "$a6[2]";?>" size="8" readonly></td>
                </tr>
				<tr>
                  <td><input name="a7[]" type="text" id="x31" value="<?php echo "$a7[0]";?>" readonly></td>
                  <td><input name="a7[]" type="text" id="x32" value="<?php echo "$a7[1]";?>" size="60" readonly></td>
                  <td><input name="a7[]" type="text" id="x33" value="<?php echo "$a7[2]";?>" size="8" readonly></td>
                </tr>
				<tr>
                  <td><input name="a8[]" type="text" id="x31" value="<?php echo "$a8[0]";?>" readonly></td>
                  <td><input name="a8[]" type="text" id="x32" value="<?php echo "$a8[1]";?>" size="60" readonly></td>
                  <td><input name="a8[]" type="text" id="x33" value="<?php echo "$a8[2]";?>" size="8" readonly></td>
                </tr>
				<tr>
                  <td><input name="a9[]" type="text" id="x31" value="<?php echo "$a9[0]";?>" readonly></td>
                  <td><input name="a9[]" type="text" id="x32" value="<?php echo "$a9[1]";?>" size="60" readonly></td>
                  <td><input name="a9[]" type="text" id="x33" value="<?php echo "$a9[2]";?>" size="8" readonly></td>
                </tr>
				<tr>
                  <td><input name="a10[]" type="text" id="x31" value="<?php echo "$a10[0]";?>" readonly></td>
                  <td><input name="a10[]" type="text" id="x32" value="<?php echo "$a10[1]";?>" size="60" readonly></td>
                  <td><input name="a10[]" type="text" id="x33" value="<?php echo "$a10[2]";?>" size="8" readonly></td>
                </tr>
				<tr>
                  <td><input name="a11[]" type="text" id="x31" value="<?php echo "$a11[0]";?>" readonly></td>
                  <td><input name="a11[]" type="text" id="x32" value="<?php echo "$a11[1]";?>" size="60" readonly></td>
                  <td><input name="a11[]" type="text" id="x33" value="<?php echo "$a11[2]";?>" size="8" readonly></td>
                </tr>
              </table>
</div>
<div id="password" style="display:none;border:1px solid #CCCCFF ">
 	<div id="mytabletitle" style=" font-size:120%; border:none; padding:3px 3px 3px 3px">* <?php if($LG=="BM") echo "Kemaskini Password Anda"; else echo "Update Your Password";?></div>
	<table style="font-size:12px;">
		 <tr>
                  <td width="15%">* Password </td>
				  <td width="1%">:</td>
                  <td ><input name="passx" type="password"></td>
                </tr>
	</table>

</div>
</div></div>
</form>	
</body>
</html>
