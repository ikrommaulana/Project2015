<?php
//110610 - upgrade gui
//120808 - admin can configure direct
$vmod="v6.0.2";
$vdate="120808";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
ISACCESS("estaff",1);
$username = $_SESSION['username'];

$f=$_REQUEST['f'];
$uid=$_REQUEST['uid'];
$sid=$_REQUEST['sid'];
if($sid=="")
	$sid=$_SESSION['sid'];
$operation=$_REQUEST['operation'];
if($operation!=""){
	include_once('usrsave.php');
}

		
		if($uid!=""){
			$sql="select * from usr where uid='$uid'";
			$res=mysql_query($sql,$link)or die("$sql failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$id=$row['id'];
			$sid=$row['sch_id'];
			$uid=$row['uid'];
			$pass=$row['pass'];
			$name=stripslashes($row['name']);
			$ic=$row['ic'];
			$sex=$row['sex'];
			$race=$row['race'];
			$religion=$row['religion'];
			$citizen=$row['citizen'];
			$country=$row['country'];
			$edulevel=$row['edulevel'];
			$bday=$row['bday'];
			list($yy,$mm,$dd)=explode("-",$bday);
			$tel=$row['tel'];
			$tel2=$row['tel2'];
			$fax=$row['fax'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$addr=$row['addr'];
			$addr1=$row['addr1'];
			$addr2=$row['addr2'];
			$city=$row['city'];
			$pcode=$row['pcode'];
			$state=$row['state'];
			$spname=$row['spname'];
			$spic=$row['spic'];
			$spjob=$row['spjob'];
			$cutilama=$row['cuti_lama'];
	
			$job=stripslashes($row['job']);
			$jobdiv=stripslashes($row['jobdiv']);
			$jobsta=stripslashes($row['jobsta']);
			$joblevel=$row['joblvl'];
			$jobstart=$row['jobstart'];
			$jobend=$row['jobend'];
			$syslevel=$row['syslevel'];
			$file=$row['file'];
			$status=$row['status'];
			
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
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            mysql_free_result($res);					  
		}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php include("$MYOBJ/calender/calender.htm")?>
<script language="JavaScript">
function process_form(operation,usrid,autoid){

	if(operation=='delete'){
		if(document.myform.id.value==""){
			alert("<?php echo $lg_check_the_item;?>");
			return;
		}
		ret = confirm("<?php echo $lg_confirm_delete;?>");
		if (ret == true){
			document.myform.operation.value=operation;
			document.myform.submit();
		}
		return;
	
	}
	else{
		if(document.myform.syslevel.value==""){
			alert("Please select Access Level");
			document.myform.syslevel.focus();
			return;
		}
		if(document.myform.sid.value==""){
			alert("Please select..");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.uidx.value==""){
			alert("Please enter the user ID");
			document.myform.uidx.focus();
			return;
		}
		stringToTrim = document.myform.uidx.value;
		str=stringToTrim.replace(/^\s+|\s+$/g,"");
		if(str==""){
			document.myform.uidx.value='';
			alert("Please enter the user ID");
			document.myform.uidx.focus();
			return;
		}
		if(document.myform.name.value==""){
			alert("Please enter fullname");
			document.myform.name.focus();
			return;
		}
		if(document.myform.ic.value==""){
			alert("Please enter the IC number");
			document.myform.ic.focus();
			return;
		}
		if(document.myform.file.value!=""){
			fn=document.myform.file.value;
			len=fn.length;
			ext=fn.substr(len-3,3);
			if((ext.toLowerCase()!="gif")&&(ext.toLowerCase()!="jpg")){
				alert("Invalid file. Only GIF or JPG allowed");
				document.form1.uploadedfile.focus();
				return;
			}
		}
		ret = confirm("<?php echo $lg_confirm_save;?>");
		if (ret == true){
			document.myform.operation.value=operation;
			document.myform.submit();
		}
		return;

	}
}



</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo $lg_staff;?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>

<form action="" method="post" enctype="multipart/form-data" name="myform">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="p" value="usrreg">
<input type="hidden" name="operation" >
<input type="hidden" name="MAX_FILE_SIZE" value="1000000">


<div id="content">

<div id="mypanel"  class="printhidden">
		<div id="mymenu" align="center">
				<?php if($uid!=""){ ?>
				<a href="#" onClick="location.href='../estaff/usr_info.php?uid=<?php echo $uid;?>'"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/goback.png"><br><?php echo $lg_back;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php }?>
				<a href="#" onClick="process_form('<?php if($uid=="")echo "insert"; else echo "update";?>','<?php echo $id;?>','<?php echo $si_staff_auto_id;?>')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br><?php echo $lg_save;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php if($uid!=""){ ?>
				<a href="#" onClick="process_form('delete')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/delete.png"><br><?php echo $lg_delete;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php }?>
				<a href="usr_info.php?uid=<?php echo $uid;?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="usrreg.php?uid=<?php echo $uid;?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="window.close();top.$.fancybox.close();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br><?php echo $lg_close;?></a>
		</div> <!-- end mymenu -->
		<div align="right">
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
</div> <!-- end mypanel -->
<div id="story">
<div id="mytitlebg">A. <?php echo strtoupper($lg_system_information);?> <?php echo $f;?></div>
		  

<table width="100%" id="myborder">
<?php if(($id>0)||(!$si_staff_auto_id)){?>
			<tr>
                  <td width="15%">*<?php echo strtoupper($lg_staff_id);?></td>
				  <td width="1%">:</td>
                  <td width="85%"><input name="uidx" type="text"  value="<?php if($uid!="") echo "$uid";?>" <?php if($uid!="") echo "readonly"?> ></td>
			</tr>
			<tr>
                  <td>*<?php echo strtoupper($lg_password);?></td>
				  <td>:</td>
                  <td><input name="passx" type="password"><font color="blue">(Set / Reset Password)</font></td>
			</tr>
<?php } ?>
			<tr>
                  <td>*SYSTEM LEVEL</td>
				  <td>:</td>
                  <td><select name="syslevel" id="syslevel" >
					<?php	
					if(is_verify('ADMIN')) {
						if($syslevel==""){
							$sql="select * from type where grp='syslevel' order by val";
						}
						else{
							echo "<option value=\"$syslevel\">$syslevel</option>";
							$sql="select * from type where grp='syslevel' and prm!='$syslevel' order by val";
						}
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									echo "<option value=\"$s\">$s</option>";
						}
						mysql_free_result($res);					  
					}
					else{
						if($syslevel==""){
							$sql="select * from type where grp='syslevel' and prm!='ADMIN' order by val";
						}
						else{
							echo "<option value=\"$syslevel\">$syslevel</option>";
							$sql="select * from type where grp='syslevel' and prm!='ADMIN' and prm!='$syslevel' order by val";
						}
						$res=mysql_query($sql)or die("$sql failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									echo "<option value=\"$s\">$s</option>";
						}
						mysql_free_result($res);	
					}
?>
                  </select>
                  </td>
				</tr>
				 <tr>
                  <td >*SYSTEM ACCESS</td>
				  <td>:</td>
                  <td>
				  <select name="sid" id="sid" >
<?php	
			if($sid=="0")
            	echo "<option value=\"0\">- $lg_all -</option>";
			else
                echo "<option value=$sid>$sname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				if($sid!="0")
					echo "<option value=\"0\">- $lg_all -</option>";
			}					
?>
                    </select>
                  </td>
                </tr>
			<tr>
            <td>*STATUS</td>
			<td>:</td>
            <td>
              <select name="status">
 <?php	
      		if($status=="")
				echo "<option value=\"0\">ACTIVATED</option>";

			else{
				$sql="select * from type where grp='usrstatus' and val='$status' order by val";
            	$res=mysql_query($sql)or die("query failed:".mysql_error());
            	$row=mysql_fetch_assoc($res);
                $s=$row['prm'];
				$v=$row['val'];
                 echo "<option value=\"$v\">$s</option>";
			}
			$sql="select * from type where grp='usrstatus' order by val";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$v\">$s</option>";
            }
			/**
				echo "<option value=\"1\">TERMINATED</option>";
			}
			else{
				echo "<option value=\"1\">TERMINATED</option>";
				echo "<option value=\"0\">ACTIVATED</option>";
            }
			**/
?>
        </select>
         <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=usrstatus',0)">
		<?php } ?>
			</td>
       </tr>
</table>
<div id="mytitlebg">B. <?php echo strtoupper($lg_personal_information);?></div>
<table width="100%">
	<tr>
		<td width="80%">
			 <table width="100%" id="myborder">
                <tr>
                  <td width="20%">*<?php echo strtoupper($lg_name);?></td>
				  <td width="1%">:</td>
                  <td width="80%"><input name="name" type="text" id="name" size="64" value="<?php echo $name;?>">
                  </td>
                </tr>
                <tr>
                  <td>*<?php echo strtoupper($lg_ic_number);?></td>
				  <td>:</td>
                  <td><input name="ic" type="text" id="ic" value="<?php echo $ic;?>"></td>
 		</tr>
        <tr>
                  <td>*<?php echo strtoupper($lg_race);?></td>
				  <td>:</td>
                  <td>
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
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$s</option>";
            }
?>
		</select>
 <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=race',0)">
<?php } ?>
                  </td>
         </tr>
        	<tr>
                  <td>*<?php echo strtoupper($lg_religion);?></td>
				  <td>:</td>
                  <td>
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
			$sql="select * from type where grp='religion' order by val";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$s</option>";
            }
?>
                  </select>
 <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=religion',0)">
<?php } ?>                  
                  </td>
            </tr>
			<tr>
                  <td>*<?php echo strtoupper($lg_gender);?></td>
				  <td>:</td>
                  <td>
                  <select name="sex" id="sex" >
                  <?php	
					if($sex==""){
						echo "<option value=\"\">- $lg_sex -</option>";
						$sql="select * from type where grp='sex' order by val";
					}
					else{	
						$sexname=$lg_malefemale[$sex];
						echo "<option value=\"$sex\">$sexname</option>";
						$sql="select * from type where grp='sex' and val!='$sex' order by val"; 	
					}
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$s=$row['prm'];
								$v=$row['val'];
								echo "<option value=\"$v\">$s</option>";
					}
				?>
				</select>
 <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=sex',0)">
<?php } ?>                  
			</td>
 		</tr>        
		<tr>
                  <td><?php echo strtoupper($lg_birth_date);?></td>
				  <td>:</td>
                  <td><select name="day" id="day">
                      <?php 
					if($dd=="")
							echo "<option value=\"\">- $lg_day -</option>";
					else
							echo "<option value=\"$dd\">$dd</option>";
					  for($i=1;$i<=31;$i++) 
					  		echo "<option value=\"$i\">$i</option>" 
					  ?>
                    </select>
                      <select name="month" id="month">
                       
                        <?php 
						if($mm=="")
							echo "<option value=\"\">- $lg_month -</option>";
						else
							echo "<option value=\"$mm\">$mm</option>";
						for($i=1;$i<=12;$i++) 
							echo "<option value=\"$i\">$i</option>" 
						?>
                      </select>
                      <select name="year" id="year">
                       
                        <?php 
							if($yy=="")
								echo "<option value=\"\">- $lg_year -</option>";
							else
								echo "<option value=\"$yy\">$yy</option>";
							for($i=2000;$i>1940;$i--) 
								echo "<option value=\"$i\">$i</option>" 
						?>
                      </select>
                  </td>
                </tr>
               	<tr>
					  <td><?php echo strtoupper($lg_citizen);?></td>
					  <td>:</td>
					  <td>
					  <select name="citizen">
					<?php	
							if($citizen=="")
								echo "<option value=\"$BASE_COUNTRY\">$BASE_COUNTRY</option>";
							else
								echo "<option value=\"$citizen\">$citizen</option>";
							$sql="select * from country where name!='$citizen' order by name";
							$res=mysql_query($sql)or die("$sql failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
										$s=$row['name'];
										echo "<option value=\"$s\">$s</option>";
							}							
					?>
					  </select>
					  </td>
                </tr>
                <tr>
					  <td ><?php echo strtoupper($lg_tel_home);?></td>
					  <td>:</td>
					  <td><input name="tel" type="text" id="tel" value="<?php echo $tel;?>"></td>
                </tr>
               	<tr>
					  <td><?php echo strtoupper($lg_tel_mobile);?></td>
					  <td>:</td>
					  <td><input name="hp" type="text" id="hp" value="<?php echo $hp;?>">
                </tr>
                <tr>
                  <td><?php echo strtoupper($lg_email);?></td>
				  <td>:</td>
                  <td><input name="mel" type="text" id="mel" value="<?php echo $mel;?>" size="38"></td>
                </tr>
				<tr>
					  <td valign="top"><?php echo strtoupper($lg_permanent_address);?></td>
					  <td valign="top">:</td>
					  <td><textarea name="addr" cols="30" rows="4" id="addr"><?php echo $addr;?></textarea></td>
				  </tr>
<!--                  
                  <tr>
					  <td colspan="3" id="mytitlebg"></td>

				  </tr>
                   <tr>
					  <td><?php echo strtoupper($lg_mailing_address);?> Line 1</td>
					  <td>:</td>
					  <td>
                      		<input name="addr1" type="text" value="<?php echo $addr1;?>" size="38"><br>
                      </td>
				  </tr>
                  <tr>
					  <td><?php echo strtoupper($lg_mailing_address);?> Line 2</td>
					  <td>:</td>
					  <td>
                      		<input name="addr2" type="text" value="<?php echo $addr2;?>" size="38">
                      </td>
				  </tr>
                  <tr>
					  <td><?php echo strtoupper($lg_city);?></td>
					  <td>:</td>
					  <td>
                      		 <input name="city" type="text" value="<?php echo $city;?>">
							<?php echo strtoupper($lg_postcode);?> 
                            <input name="pcode" type="text" value="<?php echo $pcode;?>" size="5">
                      </td>
				  </tr>
				  <tr>
					<td><?php echo strtoupper($lg_state);?></td>
					<td>:</td>
					<td><select name="state" id="state" >
					<?php	
							if($state=="")
								echo "<option value=\"\">- $lg_select -</option>";
							else
								echo "<option value=\"$state\">$state</option>";
							$sql="select * from state where name!='$state' order by name";
							$res=mysql_query($sql)or die("$sql failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
										$s=$row['name'];
										echo "<option value=\"$s\">$s</option>";
							}
							echo "<option value=\"\">-none-</option>";			
					?>
					  </select>
					  <select name="country">
					<?php	
							if($country=="")
								echo "<option value=\"$BASE_COUNTRY\">$BASE_COUNTRY</option>";
							else
								echo "<option value=\"$country\">$country</option>";
							$sql="select * from country where name!='$country' order by name";
							$res=mysql_query($sql)or die("$sql failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
										$s=$row['name'];
										echo "<option value=\"$s\">$s</option>";
							}							
					?>
					  </select>
					  </td>
                </tr>
-->
              </table>
	</td>
     <td width="20%" valign="top" align="center">
			<table width="150" height="150" id="myborder" bgcolor="#F1F1F1">
			  <tr>
				<td  bgcolor="#ccccff">
				<?php if($file!=""){?>
				<img name="picture" alt="Picture" src="<?php if($file!="") echo "$dir_image_user$file"; ?>"  width="150" height="150" >
				<?php } ?>
				</td>
			  </tr>
			</table>
              <input type="file" name="file" size="8">
			  </td>
            </tr>
</table>
<?php	
	if(is_verify('ADMIN')) { 
?>
<div id="mytitlebg">C. <?php echo strtoupper($lg_job_information);?></div>
<table width="100%"  border="0" cellpadding="0" cellspacing="3">
			<tr>
              <td width="15%"><?php echo strtoupper($lg_job);?></td>
			  <td width="1%">:</td>
              <td width="85%">
			  <select name="job" id="job" >
				<?php	
							if($job==""){
								echo "<option value=\"\">- $lg_select -</option>";
								$sql="select * from type where grp='job' order by val";
							}
							else{
								echo "<option value=\"$job\">$job</option>";
								$job=addslashes($job);
								$sql="select * from type where grp='job' and prm!='$job' order by val"; 	
							}
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
										$s=stripslashes($row['prm']);
										echo "<option value=\"$s\">$s</option>";
							}
				?>
                  </select> 
 <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=job',0)">
<?php } ?>                  
              </td>
            </tr>
			<tr>
              <td><?php echo strtoupper($lg_grade);?></td>
			  <td>:</td>
              <td>
				<select name="joblevel" id="joblevel" >
				<?php	
							if($joblevel==""){
								echo "<option value=\"\">- $lg_select -</option>";
								$sql="select * from type where grp='joblevel' order by val";
							}
							else{
								echo "<option value=\"$joblevel\">$joblevel</option>";
								$sql="select * from type where grp='joblevel' and prm!='$joblevel' order by val"; 	
							}
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
										$s=stripslashes($row['prm']);
										echo "<option value=\"$s\">$s</option>";
							}
				?>
				</select> 
 <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=jonlevel',0)">
<?php } ?>                
              </td>
            </tr>
			<tr>
                <td><?php echo strtoupper($lg_division);?></td>
				<td>:</td>
                <td><select name="jobdiv" id="jobdiv" >
					<?php	
					if($jobdiv==""){
						echo "<option value=\"\">- $lg_select -</option>";
						$sql="select * from type where grp='jobdiv' order by prm";
					}
					else{
						echo "<option value=\"$jobdiv\">$jobdiv</option>";
						$jobdiv=addslashes($jobdiv);
						$sql="select * from type where grp='jobdiv' and prm!='$jobdiv' order by prm"; 	
					}
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$s=stripslashes($row['prm']);
								echo "<option value=\"$s\">$s</option>";
					}
					?>
				</select> 
 <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=jobdiv',0)">
<?php } ?>                
              </td>
            </tr>
			<tr>
              <td ><?php echo strtoupper($lg_status);?></td>
			  <td>:</td>
              <td><select name="jobsta" id="jobsta" >
                <?php	
					if($jobsta==""){
						echo "<option value=\"\">- $lg_select -</option>";
						$sql="select * from type where grp='jobsta' order by val";
					}
					else{
						echo "<option value=\"$jobsta\">$jobsta</option>";
						$sql="select * from type where grp='jobsta' and prm!='$jobsta' order by val"; 	
					}
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$s=stripslashes($row['prm']);
								$v=$row['val'];
								echo "<option value=\"$s\">$s</option>";
					}
				   ?>
                  </select> 
 <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=jobsta',0)">
<?php } ?>                  
              </td>
            </tr>

			<tr>
					<td><?php echo strtoupper($lg_start_date);?></td>
					<td>:</td>
					<td><input name="jobstart" type="text" id="jobstart" value="<?php echo $jobstart;?>">
					<input name=" cal" type="button" id=" cal" value="-" onClick="c2.popup('jobstart')"></td>
          </tr>
		 <tr>
            <td ><?php echo strtoupper($lg_end_date);?></td>
			<td>:</td>
            <td>
              <input name="jobend" type="text" id="jobend" value="<?php echo $jobend;?>">
			  <input name=" cal" type="button" id=" cal" value="-" onClick="c2.popup('jobend')"></td>
		 </tr>	
		 <tr>
            <td ><?php echo strtoupper($lg_qualification);?></td>
			<td>:</td>
			<td>
	<select name="edulevel">
		<?php	
      		if($edulevel==""){
				echo "<option value=\"\">- $lg_select -</option>";
				$sql="select * from type where grp='kelulusan' order by val";
			}
			else{
				echo "<option value=\"$edulevel\">$edulevel</option>";
				$sql="select * from type where grp='kelulusan' and prm!='$edulevel' order by val"; 	
			}
			$sql="select * from type where grp='kelulusan' order by val";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$s</option>";
            }
		?>
                  </select>
 <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=kelulusan',0)">
<?php } ?>                  
            <td>
			</td>
		 </tr>	
</table>
<?php } ?>
<div id="mytitlebg">D. <?php echo strtoupper($lg_academic_information);?></strong></div>
			  <table width="100%"  border="0">
                <tr id="mytitle">
                  <td width="10%"><?php echo $lg_academic_level;?></td>
                  <td width="20%"><?php echo $lg_institution;?></td>
                  <td width="70%"><?php echo $lg_year;?></td>
                </tr>
                <tr>
                  <td><input name="q1[]" type="text" id="q11" value="<?php echo "$q1[0]";?>"></td>
                  <td><input name="q1[]" type="text" id="q12" value="<?php echo "$q1[1]";?>" size="60"></td>
                  <td><input name="q1[]" type="text" id="q13" value="<?php echo "$q1[2]";?>" size="8"></td>
                </tr>
                <tr>
                  <td><input name="q2[]" type="text" id="q21" value="<?php echo "$q2[0]";?>"></td>
                  <td><input name="q2[]" type="text" id="q22" value="<?php echo "$q2[1]";?>" size="60"></td>
                  <td><input name="q2[]" type="text" id="q23" value="<?php echo "$q2[2]";?>" size="8"></td>
                 
                </tr>
                <tr>
                  <td><input name="q3[]" type="text" id="q31" value="<?php echo "$q3[0]";?>"></td>
                  <td><input name="q3[]" type="text" id="q32" value="<?php echo "$q3[1]";?>" size="60"></td>
                  <td><input name="q3[]" type="text" id="q33" value="<?php echo "$q3[2]";?>" size="8"></td>
                </tr>
				<tr>
                  <td><input name="q4[]" type="text" id="q41" value="<?php echo "$q4[0]";?>"></td>
                  <td><input name="q4[]" type="text" id="q42" value="<?php echo "$q4[1]";?>" size="60"></td>
                  <td><input name="q4[]" type="text" id="q43" value="<?php echo "$q4[2]";?>" size="8"></td>
                </tr>
				<tr>
                  <td><input name="q5[]" type="text" id="q51" value="<?php echo "$q5[0]";?>"></td>
                  <td><input name="q5[]" type="text" id="q52" value="<?php echo "$q5[1]";?>" size="60"></td>
                  <td><input name="q5[]" type="text" id="q53" value="<?php echo "$q5[2]";?>" size="8"></td>
                </tr>
              </table>


<div id="mytitlebg">E. <?php echo strtoupper($lg_working_experience);?> </div>
			  <table width="100%"  border="0">
                <tr id="mytitle">
                  <td width="10%"><?php echo $lg_position;?></td>
                  <td width="20%"><?php echo $lg_employer;?></td>
                  <td width="70%"><?php echo $lg_year;?></td>
                </tr>
                <tr>
                  <td><input name="x1[]" type="text" id="x11" value="<?php echo "$x1[0]";?>"></td>
                  <td><input name="x1[]" type="text" id="x12" value="<?php echo "$x1[1]";?>" size="60"></td>
                  <td><input name="x1[]" type="text" id="x13" value="<?php echo "$x1[2]";?>" size="8"></td>
                </tr>
                <tr>
                  <td><input name="x2[]" type="text" id="x21" value="<?php echo "$x2[0]";?>"></td>
                  <td><input name="x2[]" type="text" id="x22" value="<?php echo "$x2[1]";?>" size="60"></td>
                  <td><input name="x2[]" type="text" id="x23" value="<?php echo "$x2[2]";?>" size="8"></td>
                </tr>
                <tr>
                  <td><input name="x3[]" type="text" id="x31" value="<?php echo "$x3[0]";?>"></td>
                  <td><input name="x3[]" type="text" id="x32" value="<?php echo "$x3[1]";?>" size="60"></td>
                  <td><input name="x3[]" type="text" id="x33" value="<?php echo "$x3[2]";?>" size="8"></td>
                </tr>
                <tr>
                  <td><input name="x4[]" type="text" id="x41" value="<?php echo "$x4[0]";?>"></td>
                  <td><input name="x4[]" type="text" id="x42" value="<?php echo "$x4[1]";?>" size="60"></td>
                  <td><input name="x4[]" type="text" id="x43" value="<?php echo "$x4[2]";?>" size="8"></td>
                </tr>
	            <tr>
                  <td><input name="x5[]" type="text" id="x51" value="<?php echo "$x5[0]";?>"></td>
                  <td><input name="x5[]" type="text" id="x52" value="<?php echo "$x5[1]";?>" size="60"></td>
                  <td><input name="x5[]" type="text" id="x53" value="<?php echo "$x5[2]";?>" size="8"></td>
                </tr>
              </table>


<div id="mytitlebg">F. <?php echo strtoupper($lg_spouse_information);?> </div>
	<table width="100%"  border="0" cellpadding="0" cellspacing="3">
			<tr>
                  <td width="15%"><?php echo $lg_name;?></td>
                  <td width="1%">:</td>
                  <td width="85%"><input type="text" name="spname" value="<?php echo "$spname";?>"></td>
            </tr>
            <tr>
                  <td width="15%"><?php echo $lg_ic;?></td>
                  <td width="1%">:</td>
                  <td width="85%"><input type="text" name="spic" value="<?php echo "$spic";?>"></td>
            </tr>
            <tr>
                  <td width="15%"><?php echo $lg_job;?></td>
                  <td width="1%">:</td>
                  <td width="85%"><input type="text" name="spjob"  value="<?php echo "$spjob";?>"></td>
            </tr>
     </table>    
 <div id="mytitlebg">G. <?php echo strtoupper($lg_family_information);?> </div>
			  <table width="100%"  border="0">
                <tr id="mytitlebg">
                  <td width="10%"><?php echo $lg_child_name;?></td>
                  <td width="20%"><?php echo $lg_school_ipt_job;?></td>
                  <td width="70%"><?php echo $lg_birth_year;?></td>

                </tr>
                <tr>
                  <td><input name="a1[]" type="text" id="a11" value="<?php echo "$a1[0]";?>"></td>
                  <td><input name="a1[]" type="text" id="x12" value="<?php echo "$a1[1]";?>" size="60"></td>
                  <td><input name="a1[]" type="text" id="x13" value="<?php echo "$a1[2]";?>" size="8"></td>
                </tr>
                <tr>
                  <td><input name="a2[]" type="text" id="x21" value="<?php echo "$a2[0]";?>"></td>
                  <td><input name="a2[]" type="text" id="x22" value="<?php echo "$a2[1]";?>" size="60"></td>
                  <td><input name="a2[]" type="text" id="x23" value="<?php echo "$a2[2]";?>" size="8"></td>
                </tr>
                <tr>
                  <td><input name="a3[]" type="text" id="x31" value="<?php echo "$a3[0]";?>"></td>
                  <td><input name="a3[]" type="text" id="x32" value="<?php echo "$a3[1]";?>" size="60"></td>
                  <td><input name="a3[]" type="text" id="x33" value="<?php echo "$a3[2]";?>" size="8"></td>
                </tr>
				<tr>
                  <td><input name="a4[]" type="text" id="x31" value="<?php echo "$a4[0]";?>"></td>
                  <td><input name="a4[]" type="text" id="x32" value="<?php echo "$a4[1]";?>" size="60"></td>
                  <td><input name="a4[]" type="text" id="x33" value="<?php echo "$a4[2]";?>" size="8"></td>
                </tr>
				<tr>
                  <td><input name="a5[]" type="text" id="x31" value="<?php echo "$a5[0]";?>"></td>
                  <td><input name="a5[]" type="text" id="x32" value="<?php echo "$a5[1]";?>" size="60"></td>
                  <td><input name="a5[]" type="text" id="x33" value="<?php echo "$a5[2]";?>" size="8"></td>
                </tr>
				<tr>
                  <td><input name="a6[]" type="text" id="x31" value="<?php echo "$a6[0]";?>"></td>
                  <td><input name="a6[]" type="text" id="x32" value="<?php echo "$a6[1]";?>" size="60"></td>
                  <td><input name="a6[]" type="text" id="x33" value="<?php echo "$a6[2]";?>" size="8"></td>
                </tr>
				<tr>
                  <td><input name="a7[]" type="text" id="x31" value="<?php echo "$a7[0]";?>"></td>
                  <td><input name="a7[]" type="text" id="x32" value="<?php echo "$a7[1]";?>" size="60"></td>
                  <td><input name="a7[]" type="text" id="x33" value="<?php echo "$a7[2]";?>" size="8"></td>
                </tr>
				<tr>
                  <td><input name="a8[]" type="text" id="x31" value="<?php echo "$a8[0]";?>"></td>
                  <td><input name="a8[]" type="text" id="x32" value="<?php echo "$a8[1]";?>" size="60"></td>
                  <td><input name="a8[]" type="text" id="x33" value="<?php echo "$a8[2]";?>" size="8"></td>
                </tr>
				<tr>
                  <td><input name="a9[]" type="text" id="x31" value="<?php echo "$a9[0]";?>"></td>
                  <td><input name="a9[]" type="text" id="x32" value="<?php echo "$a9[1]";?>" size="60"></td>
                  <td><input name="a9[]" type="text" id="x33" value="<?php echo "$a9[2]";?>" size="8"></td>
                </tr>
				<tr>
                  <td><input name="a10[]" type="text" id="x31" value="<?php echo "$a10[0]";?>"></td>
                  <td><input name="a10[]" type="text" id="x32" value="<?php echo "$a10[1]";?>" size="60"></td>
                  <td><input name="a10[]" type="text" id="x33" value="<?php echo "$a10[2]";?>" size="8"></td>
                </tr>
				<tr>
                  <td><input name="a11[]" type="text" id="x31" value="<?php echo "$a11[0]";?>"></td>
                  <td><input name="a11[]" type="text" id="x32" value="<?php echo "$a11[1]";?>" size="60"></td>
                  <td><input name="a11[]" type="text" id="x33" value="<?php echo "$a11[2]";?>" size="8"></td>
                </tr>
              </table>


</div></div>
</form>
</body>
</html>
