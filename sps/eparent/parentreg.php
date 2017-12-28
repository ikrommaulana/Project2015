<?php
//BASE ON STUDENT
//22/04/2010 - update view by STUDENT
$vmod="v5.0.0";
$vdate="21/07/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN|GURU');
		$username = $_SESSION['username'];
		$id=$_REQUEST['id'];
		$uid=$_REQUEST['uid'];
		$sid=$_REQUEST['sid'];
		
		if($sid=="") $sid=$_SESSION['sid'];
		$operation=$_REQUEST['operation'];
		$d=$_REQUEST['day'];
		$m=$_REQUEST['month'];
		$y=$_REQUEST['year'];
		$p1bday="$y1-$m1-$d1";
		$p2bday="$y2-$m2-$d2";
		if($operation!=""){
			include_once('parentsave.php');
		}

$id=$_REQUEST['id'];
$operation=$_REQUEST['operation'];
if(($id!="")&&($operation=="")){
			$sql="select * from stu where id='$id'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sid'];
			$acc=$row['acc'];
			
			$p1name=stripslashes($row['p1name']);
			$p1ic=$row['p1ic'];
			$pob1=$row['pob1'];
			$p1rel=$row['p1rel'];
			$p1job=stripslashes($row['p1job']);
			$p1sal=$row['p1sal'];
			$p1com=stripslashes($row['p1com']);
			$p1tel=$row['p1tel'];
			$p1tel2=$row['p1tel2'];
			$p1fax=$row['p1fax'];
			$p1state=$row['p1state'];
			$p1addr=stripslashes($row['p1addr']);
			$p1bandar=stripslashes($row['p1bandar']);
			$p1poskod=$row['p1poskod'];
			$p1mel=$row['p1mel'];
			$p1hp=$row['p1hp'];
			$p1wn=$row['p1wn'];
			$p1bday=$row['p1bday'];
			$p1edu=$row['p1edu'];
			
			
			$p2name=stripslashes($row['p2name']);
			$p2ic=$row['p2ic'];
			$pob2=$row['pob2'];
			$p2rel=$row['p2rel'];
			$p2job=stripslashes($row['p2job']);
			$p2sal=$row['p2sal'];
			$p2com=stripslashes($row['p2com']);
			$p2tel=$row['p2tel'];
			$p2tel2=$row['p2tel2'];
			$p2fax=$row['p2fax'];
			$p2state=$row['p2state'];
			$p2addr=stripslashes($row['p2addr']);
			$p2bandar=stripslashes($row['p2bandar']);
			$p2poskod=$row['p2poskod'];
			$p2mel=$row['p2mel'];
			$p2bday=$row['p2bday'];
			$p2hp=$row['p2hp'];
			$p2wn=$row['p2wn'];
			$p2edu=$row['p2edu'];
			
			
			$p3name=stripslashes($row['p3name']);
			$p3ic=$row['p3ic'];
			$p3rel=$row['p3rel'];
			$p3job=stripslashes($row['p3job']);
			$p3sal=$row['p3sal'];
			$p3com=stripslashes($row['p3com']);
			$p3tel=$row['p3tel'];
			$p3tel2=$row['p3tel2'];
			$p3fax=$row['p3fax'];
			$p3state=$row['p3state'];
			$p3addr=stripslashes($row['p3addr']);
			$p3mel=$row['p3mel'];
			$p3hp=$row['p3hp'];
			$p3wn=$row['p3wn'];
			$p3edu=$row['p3edu'];
			
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
			
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php include("$MYOBJ/calender/calender.htm")?>
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
		if(document.myform.p1name.value==""){
			alert("Sila masukkan name penjaga1");
			document.myform.p1name.focus();
			return;
		}
		if(document.myform.p1ic.value==""){
			alert("Sila masukkan kad pengenalan bapa / penjaga1");
			document.myform.p1ic.focus();
			return;
		}
		ret = confirm("Are you sure want to save??");
		if (ret == true){
			document.myform.operation.value=op;
			document.myform.submit();
		}
		return;
	}
}

function samaalamat(){
	document.myform.p2addr.value=document.myform.p1addr.value;
	document.myform.p2bandar.value=document.myform.p1bandar.value;
	document.myform.p2poskod.value=document.myform.p1poskod.value;
	document.myform.p2tel.value=document.myform.p1tel.value;
	document.myform.p2state.value=document.myform.p1state.value;
}


</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>
<form method="post" enctype="multipart/form-data" name="myform" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="parentreg">
<input type="hidden" name="operation" >
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000">

<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
				<a href="#" onClick="process_form('save')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
				<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
				<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div> <!-- end mymenu -->
		<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div> <!-- end mypanel -->

<div id="story">
<div id="mytitlebg"><?php echo strtoupper($lg_parent_information); echo "&nbsp;$f";?></div>
<table width="100%" cellspacing="0">
	<tr>
	<td width="50%" id="myborder">
	
<div id="mytitlebg">B. <?php echo strtoupper($lg_father_information);?></div>
<table width="100%" cellspacing="0">
          	<tr bgcolor="#FAFAFA">
					<td width="30%"><?php echo $lg_name;?> *</td>
					<td width="70%"><input name="p1name" type="text" id="p1name" value="<?php echo $p1name;?>" size="49"></td>
          	</tr>
            <tr>
					<td><?php echo $lg_ic_number;?> *</td>
					<td><input name="p1ic" type="text" id="p1ic" value="<?php echo $p1ic;?>"><font color="#0000FF" size="1">(<?php echo $lg_no_space_and_dash;?>)</font></td>
            </tr>
	    <tr>
					<td><?php echo "Tempat Lahir ";?> </td>
					<td><input name="pob1" type="text" id="pob1" value="<?php echo $pob1;?>"></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td><?php echo $lg_citizen;?> *</td>
					<td> <select name="p1wn">
							<?php	
								if($p1wn==""){
									echo "<option value=\"Indonesia\">Indonesia</option>";
								}
								else{
									echo "<option value=\"$p1wn\">$p1wn</option>";
								}
								$sql="select * from country where name!='$p1wn' order by name";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
											$s=$row['name'];
											//if ($p1wn == $s ) {$selected = 'selected' ;}else {$selected ='';}
								
											echo "<option value= '$s'>$s</option>";
								                   }
							        
							?>
						</select>
					</td>
            </tr>
            
	    <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
				  <?php echo ($lg_birth_date);?>*
				  </td>
                  <td >
				  <select name="d1" id="d1">
					<?php 
					if($d1=="")
						echo "<option value=\"\">- $lg_day -</option>";
					else
						echo "<option value=\"$d1\">$d1</option>";
					for($i=1;$i<=31;$i++) 
							echo "<option value=\"$i\">$i</option>" 
					?>
                    </select>
                    <select name="m1" id="m1">
<?php 
						if($m1=="")
							echo "<option value=\"\">- $lg_month -</option>";
						else
							echo "<option value=\"$m1\">$m1</option>";
						for($i=1;$i<=12;$i++) 
							echo "<option value=\"$i\">$i</option>" 
?>
                    </select>
                      <select name="y1" id="y1">
                       
                        <?php 
							if($y1=="")
								echo "<option value=\"\">- $lg_year -</option>";
							else
								echo "<option value=\"$y1\">$y1</option>";
							$yy=date("Y");
							$my=$yy-80;
							for($i=$yy;$i>$my;$i--) 
								echo "<option value=\"$i\">$i</option>" 
						?>
                      </select>     
		  		</td>
		  </tr>
	    
	    <tr>
					<td><?php echo $lg_handphone;?></td>
					<td><input name="p1hp" type="text" id="p1hp" value="<?php echo $p1hp;?>"></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td><?php echo $lg_email;?></td>
					<td><input name="p1mel" type="text" id="p1mel" value="<?php echo $p1mel;?>" size="49"></td>
  			</tr>
            <tr>
					<td><?php echo $lg_job;?></td>
					<td><input name="p1job" type="text" id="p1job" value="<?php echo $p1job;?>" size="49"></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td><?php echo $lg_employer;?></td>
					<td><input name="p1com" type="text" id="p1com" value="<?php echo $p1com;?>" size="49"></td>
            </tr>
			<tr>
					<td valign="top"><?php echo $lg_address;?><br><font color="#0000FF" size="1"><?php echo $lg_seperate_by_coma;?></font><br></td>
					<td><textarea name="p1addr" cols="37" rows="4" id="p1addr"><?php echo $p1addr;?></textarea></td>
            </tr>
            <tr bgcolor="#FAFAFA">
					<td><?php echo "Telepon Kantor";?></td>
					<td><input name="p1tel2" type="text" id="p1tel2" value="<?php echo $p1tel2;?>"> <?php echo $lg_fax;?> <input name="p1fax" type="text" id="p1fax" value="<?php echo $p1fax;?>"></td>
            </tr>
			<tr>
					<td><?php echo $lg_salary;?> (Rp)</td>
					<td><input name="p1sal" type="text" value="<?php echo $p1sal;?>"> <font color="#0000FF" size="1"><?php echo $lg_example;?> 100000 </font></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td><?php echo $lg_academic_status;?></td>
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
			<!-- 
			<tr>
					<td><?php echo $lg_school_account;?></td>
					<td><input name="acc" type="text" value="<?php echo $acc;?>"></td>
        	</tr>
			 -->
</table>

</td>
<td width="50%" id="myborder" valign="top">
	
<div id="mytitlebg">C. <?php echo strtoupper($lg_mother_information);?></div>
<table width="100%" cellspacing="0">
          	<tr bgcolor="#FAFAFA">
					<td width="30%"><?php echo $lg_name;?></td>
					<td width="70%"><input name="p2name" type="text" id="p2name" value="<?php echo $p2name;?>" size="49"></td>
          	</tr>
			 <tr>
					<td><?php echo $lg_ic_number;?></td>
					<td><input name="p2ic" type="text" id="p2ic" value="<?php echo $p2ic;?>"><font color="#0000FF" size="1">(<?php echo $lg_no_space_and_dash;?>)</font></td>
            </tr>
			  <tr>
					<td><?php echo "Tempat Lahir ";?> </td>
					<td><input name="pob2" type="text" id="pob2" value="<?php echo $pob2;?>"></td>
            </tr>
            <tr bgcolor="#FAFAFA">
					<td><?php echo $lg_citizen;?></td>
					<td> <select name="p2wn" id="p2wn" >
							<?php	
								if($p1wn==""){
									echo "<option value=\"Indonesia\">Indonesia</option>";
								}
								else{
									echo "<option value=\"$p1wn\">$p1wn</option>";
								}
								$sql="select * from country where name!='$p1wn' order by name";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
											$s=$row['name'];
											//if ($p1wn == $s ) {$selected = 'selected' ;}else {$selected ='';}
								
											echo "<option value= '$s'>$s</option>";
								                   }
							        
							?>
						</select>
					</td>
            </tr>
         
	                   <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
				  <?php echo ($lg_birth_date);?>*
				  </td>
                  <td >
				  <select name="d2" id="d2">
					<?php 
					if($d2=="")
						echo "<option value=\"\">- $lg_day -</option>";
					else
						echo "<option value=\"$d2\">$d2</option>";
					for($i=1;$i<=31;$i++) 
							echo "<option value=\"$i\">$i</option>" 
					?>
                    </select>
                    <select name="m2" id="m2">
<?php 
						if($m2=="")
							echo "<option value=\"\">- $lg_month -</option>";
						else
							echo "<option value=\"$m2\">$m2</option>";
						for($i=1;$i<=12;$i++) 
							echo "<option value=\"$i\">$i</option>" 
?>
                    </select>
                      <select name="y2" id="y2">
                       
                        <?php 
							if($y2=="")
								echo "<option value=\"\">- $lg_year -</option>";
							else
								echo "<option value=\"$y2\">$y2</option>";
							$yy=date("Y");
							$my=$yy-80;
							for($i=$yy;$i>$my;$i--) 
								echo "<option value=\"$i\">$i</option>" 
						?>
                      </select>     
		  		</td>
		  </tr>
	    <tr>
					<td><?php echo $lg_handphone;?></td>
					<td><input name="p2hp" type="text" id="p2hp" value="<?php echo $p2hp;?>"></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td><?php echo $lg_email;?></td>
					<td><input name="p2mel" type="text" id="p2mel" value="<?php echo $p2mel;?>" size="49"></td>
  			</tr>
            <tr>
					<td><?php echo $lg_job;?></td>
					<td><input name="p2job" type="text" id="p2job" value="<?php echo $p2job;?>" size="49"></td>
            </tr>
            <tr bgcolor="#FAFAFA">
					<td><?php echo $lg_employer;?></td>
					<td><input name="p2com" type="text" id="p2com" value="<?php echo $p2com;?>" size="49"></td>
            </tr>
			<tr>
					<td valign="top"><?php echo $lg_address;?><br><font color="#0000FF" size="1"><?php echo $lg_seperate_by_coma;?></font><br></td></td>
					<td><textarea name="p2addr" cols="37" rows="4" id="p2addr"><?php echo $p2addr;?></textarea></td>
            </tr>
			
	    <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">

			
			
			
			
			
			
			
			
			
			
			<tr bgcolor="#FAFAFA">
					<td><?php echo "Telepon Kantor" ;?></td>
					<td><input name="p2tel2" type="text" id="p2tel2" value="<?php echo $p2tel2;?>"> <?php echo $lg_fax;?> <input name="p2fax" type="text" id="p2fax" value="<?php echo $p2fax;?>"></td>
            </tr>
			<tr>
					<td><?php echo $lg_salary;?> (Rp)</td>
					<td><input name="p2sal" type="text" value="<?php echo $p2sal;?>"> <font color="#0000FF" size="1"><?php echo $lg_example;?> 100000</font></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td><?php echo $lg_academic_status;?></td>
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

<table width="100%" cellspacing="0">
	<tr>
	<td width="50%" id="myborder">
	
<div id="mytitlebg">D. <?php echo strtoupper($lg_family_information);?> </div>
<table width="70%" cellspacing="0">
                <tr>
                  <td width="22%"><?php echo $lg_name;?></td>
                  <td width="57%"><?php echo $lg_school_ipt_job;?></td>
                  <td width="18%"><?php echo $lg_birth_year;?></td>
                  <td width="3%">&nbsp;</td>
                </tr>
				<tr>
                  <td><input name="q01" type="text" id="q01" value="<?php echo "$q01";?>"></td>
                  <td><input name="q02" type="text" id="q02" value="<?php echo "$q02";?>" size="40"></td>
                  <td><input name="q03" type="text" id="q03" value="<?php echo "$q03";?>"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><input name="q11" type="text" id="q11" value="<?php echo "$q11";?>"></td>
                  <td><input name="q12" type="text" id="q12" value="<?php echo "$q12";?>" size="40"></td>
                  <td><input name="q13" type="text" id="q13" value="<?php echo "$q13";?>"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><input name="q21" type="text" id="q21" value="<?php echo "$q21";?>"></td>
                  <td><input name="q22" type="text" id="q22" value="<?php echo "$q22";?>" size="40"></td>
                  <td><input name="q23" type="text" id="q23" value="<?php echo "$q23";?>"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><input name="q31" type="text" id="q31" value="<?php echo "$q31";?>"></td>
                  <td><input name="q32" type="text" id="q32" value="<?php echo "$q32";?>" size="40"></td>
                  <td><input name="q33" type="text" id="q33" value="<?php echo "$q33";?>"></td>
                  <td>&nbsp;</td>
                </tr>
				<tr>
                  <td><input name="q41" type="text" id="q41" value="<?php echo "$q41";?>"></td>
                  <td><input name="q42" type="text" id="q42" value="<?php echo "$q42";?>" size="40"></td>
                  <td><input name="q43" type="text" id="q43" value="<?php echo "$q43";?>"></td>
                  <td>&nbsp;</td>
                </tr>
				<tr>
                  <td><input name="q51" type="text" id="q51" value="<?php echo "$q51";?>"></td>
                  <td><input name="q52" type="text" id="q52" value="<?php echo "$q52";?>" size="40"></td>
                  <td><input name="q53" type="text" id="q53" value="<?php echo "$q53";?>"></td>
                  <td>&nbsp;</td>
                </tr>
				<tr>
                  <td><input name="q61" type="text" id="q61" value="<?php echo "$q61";?>"></td>
                  <td><input name="q62" type="text" id="q62" value="<?php echo "$q62";?>" size="40"></td>
                  <td><input name="q63" type="text" id="q63" value="<?php echo "$q63";?>"></td>
                  <td>&nbsp;</td>
                </tr>
				<tr>
                  <td><input name="q71" type="text" id="q71" value="<?php echo "$q71";?>"></td>
                  <td><input name="q72" type="text" id="q72" value="<?php echo "$q72";?>" size="40"></td>
                  <td><input name="q73" type="text" id="q73" value="<?php echo "$q73";?>"></td>
                  <td>&nbsp;</td>
                </tr>
				<tr>
                  <td><input name="q81" type="text" id="q81" value="<?php echo "$q81";?>"></td>
                  <td><input name="q82" type="text" id="q82" value="<?php echo "$q82";?>" size="40"></td>
                  <td><input name="q83" type="text" id="q83" value="<?php echo "$q83";?>"></td>
                  <td>&nbsp;</td>
                </tr>
				<tr>
                  <td><input name="q91" type="text" id="q91" value="<?php echo "$q91";?>"></td>
                  <td><input name="q92" type="text" id="q92" value="<?php echo "$q92";?>" size="40"></td>
                  <td><input name="q93" type="text" id="q93" value="<?php echo "$q93";?>"></td>
                  <td>&nbsp;</td>
                </tr>
			</table>

</td>
<td width="50%" id="myborder" valign="top">
	
<div id="mytitlebg">E. <?php echo strtoupper($lg_contact);?> (<?php echo $lg_for_emergency_call;?>)</div>
<table width="100%" cellspacing="0">
          	<tr bgcolor="#FAFAFA">
					<td width="30%"><?php echo $lg_name;?></td>
					<td width="70%"><input name="p3name" type="text" id="p3name" value="<?php echo $p3name;?>" size="49"></td>
          	</tr>
			<tr>
					<td><?php echo $lg_relation;?></td>
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
            <tr bgcolor="#FAFAFA">
					<td><?php echo $lg_ic_number;?></td>
					<td><input name="p3ic" type="text" id="p3ic" value="<?php echo $p3ic;?>"><font color="#0000FF" size="1">(<?php echo $lg_no_space_and_dash;?>)</font></td>
            </tr>
			<tr>
					<td valign="top"><?php echo $lg_address;?><br><font color="#0000FF" size="1"><?php echo $lg_seperate_by_coma;?></font><br></td>
					<td><textarea name="p3addr" cols="37" rows="4" id="p3addr"><?php echo $p3addr;?></textarea></td>
            </tr>
            <tr bgcolor="#FAFAFA">
					<td><?php echo $lg_telephone;?></td>
					<td><input name="p3tel" type="text" id="p3tel" value="<?php echo $p3tel;?>"> H/P <input name="p3hp" type="text" id="p3hp" value="<?php echo $p3hp;?>"></td>
            </tr>
			<tr>
					<td><?php echo "Telepon Kantor";?></td>
					<td><input name="p3tel2" type="text" id="p3tel2" value="<?php echo $p3tel2;?>"> <?php echo $lg_fax;?> <input name="p3fax" type="text" id="p3fax" value="<?php echo $p3fax;?>"></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td><?php echo $lg_email;?></td>
					<td><input name="p3mel" type="text" id="p3mel" value="<?php echo $p3mel;?>" size="49"></td>
  			</tr>
</table>

</td></tr>
</table>

</div>
</form>	
</body>
</html>
