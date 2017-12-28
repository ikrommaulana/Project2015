<?php
//10/05/2010 - update slahses
//16/04/2010 - gaji parent
$vmod="v4.1.0";
$vdate="10/05/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEUANGAN|GURU');
		$username = $_SESSION['username'];
		$id=$_REQUEST['id'];
		$uid=$_REQUEST['uid'];
		$sid=$_REQUEST['sid'];
		if($sid=="") $sid=$_SESSION['sid'];
		$operation=$_REQUEST['operation'];
		if($operation!=""){
			include_once('parentsave.php');
		}

$id=$_REQUEST['id'];
$operation=$_REQUEST['operation'];
if(($id!="")&&($operation=="")){
			$sql="select * from parent where id='$id'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sid'];
			$acc=$row['acc'];
			
			$p1name=stripslashes($row['p1name']);
			$p1ic=$row['p1ic'];
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
			$p2state=$row['p2state'];
			$p2addr=stripslashes($row['p2addr']);
			$p2bandar=stripslashes($row['p2bandar']);
			$p2poskod=$row['p2poskod'];
			$p2mel=$row['p2mel'];
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

		
		if($sid!="0"){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            $stype=$row['level'];
			$level=$row['clevel'];
            mysql_free_result($res);					  
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
		if((document.myform.sid.value=="")||(document.myform.sid.value=="0")){
			alert("Sila pilih sekolah");
			document.myform.sid.focus();
			return;
		}
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
				<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div> <!-- end mymenu -->
		<div align="right">
				<br><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
</div> <!-- end mypanel -->

<div id="story">
<div id="mytitlebg">A. PENTADBIRAN <?php echo $f;?></div>

<table width="100%" cellspacing="0">
		<tr bgcolor="#FAFAFA">
                  <td width="16%">*<?php echo strtoupper("$lg_sekolah");?></td>
                  <td width="84%">
				  <select name="sid" <?php if($uid!="") echo "readonly"?>>
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
								mysql_free_result($res);
							}						  
							?>
                    </select>
                  </td>
		</tr>
		<tr>
					<td>AKAUN PENJAGA</td>
					<td><input name="acc" type="text" value="<?php echo $acc;?>"></td>
        </tr>
</table>
<table width="100%" cellspacing="0">
	<tr>
	<td width="50%" id="myborder">
	
<div id="mytitlebg">B. MAKLUMAT BAPA</div>
<table width="100%" cellspacing="0">
          	<tr bgcolor="#FAFAFA">
					<td width="30%">*NAMA BAPA</td>
					<td width="70%"><input name="p1name" type="text" id="p1name" value="<?php echo $p1name;?>" size="49"></td>
          	</tr>
			<tr>
					<td>*HUBUNGAN</td>
					<td>
							<select name="p1rel" id="p1rel" >
							<?php	
							if($p1rel==""){
								echo "<option value=\"BAPA\">BAPA</option>";
								$sql="select * from type where grp='parent' order by val";
							}
							else{
								echo "<option value=\"$p1rel\">$p1rel</option>";
								$sql="select * from type where grp='parent' and prm!='$p1rel' order by val"; 	
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
					<td>*KAD PENGENALAN</td>
					<td>
						<font color="#0000FF" size="1">(KP tiada '-' atau 'space' Cth 760103075251)</font><br>
						<input name="p1ic" type="text" id="p1ic" value="<?php echo $p1ic;?>">
						WARGANEGARA <select name="p1wn">
							<?php	
								if($p1wn=="")
									echo "<option value=\"Malaysia\">Malaysia</option>";
								else
									echo "<option value=\"$p1wn\">$p1wn</option>";
								$sql="select * from type where grp='country' and prm!='$p1wn' order by idx,prm";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
											$s=$row['prm'];
											echo "<option value=\"$s\">$s</option>";
								}			
							?>
						</select>
					</td>
            </tr>
            <tr>
					<td valign="top">ALAMAT<br><font color="#0000FF" size="1">Pisahkan dengan koma ","<br>Contoh<br>no rumah,<br> nama jalan,<br> taman</font><br></td>
					<td><textarea name="p1addr" cols="37" rows="4" id="p1addr"><?php echo $p1addr;?></textarea></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td>BANDAR</td>
					<td><input name="p1bandar" type="text" value="<?php echo $p1bandar;?>" size="49"></td>
            </tr>
            <tr>
					<td>POSKOD</td>
					<td><input name="p1poskod" type="text" value="<?php echo $p1poskod;?>" size="27">
					<select name="p1state" id="p1state" >
						<?php	
							if($p1state=="")
								echo "<option value=\"\">- Negeri -</option>";
							else
								echo "<option value=\"$p1state\">$p1state</option>";
							$sql="select * from state where name!='$p1state' order by name";
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
										$s=$row['name'];
										echo "<option value=\"$s\">$s</option>";
							}			
						?>
						 </select></td>
            </tr>
            <tr bgcolor="#FAFAFA">
					<td>TEL RUMAH</td>
					<td><input name="p1tel" type="text" id="p1tel" value="<?php echo $p1tel;?>"> H/P <input name="p1hp" type="text" id="p1hp" value="<?php echo $p1hp;?>"></td> 
            </tr>
			<tr>
					<td>EMAIL</td>
					<td><input name="p1mel" type="text" id="p1mel" value="<?php echo $p1mel;?>" size="49"></td>
  			</tr>
            <tr bgcolor="#FAFAFA">
					<td>PEKERJAAN</td>
					<td><input name="p1job" type="text" id="p1job" value="<?php echo $p1job;?>" size="49"></td>
            </tr>
			<tr>
					<td>MAJIKAN</td>
					<td><input name="p1com" type="text" id="p1com" value="<?php echo $p1com;?>" size="49"></td>
            </tr>
            <tr bgcolor="#FAFAFA">
					<td>TEL PEJABAT</td>
					<td><input name="p1tel2" type="text" id="p1tel2" value="<?php echo $p1tel2;?>"> FAX <input name="p1fax" type="text" id="p1fax" value="<?php echo $p1fax;?>"></td>
            </tr>
			<tr>
					<td>PENDAPATAN (RM)</td>
					<td><input name="p1sal" type="text" value="<?php echo $p1sal;?>"> <font color="#0000FF" size="1">Contoh 5500. Abaikan tanda RM</font></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td>TAHAP AKADEMIK</td>
					<td>
					<select name="p1edu">
							<?php	
								if($p1edu=="")
									echo "<option value=\"\">- Pilih -</option>";
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
<td width="50%" id="myborder">
	
<div id="mytitlebg">C. MAKLUMAT IBU</div>
<table width="100%" cellspacing="0">
          	<tr bgcolor="#FAFAFA">
					<td width="30%">NAMA</td>
					<td width="70%"><input name="p2name" type="text" id="p2name" value="<?php echo $p2name;?>" size="49"></td>
          	</tr>
			<tr>
					<td>HUBUNGAN</td>
					<td>
							<select name="p2rel" id="p2rel" >
							<?php	
							if($p2rel==""){
								echo "<option value=\"EMAK\">EMAK</option>";
								$sql="select * from type where grp='parent' order by val";
							}
							else{
								echo "<option value=\"$p2rel\">$p2rel</option>";
								$sql="select * from type where grp='parent' and prm!='$p2rel' order by val"; 	
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
					<td>*KAD PENGENALAN</td>
					<td>
						<font color="#0000FF" size="1">(KP tiada '-' atau 'space' Cth 760103075251)</font><br>
						<input name="p2ic" type="text" id="p2ic" value="<?php echo $p2ic;?>">
						WARGANEGARA <select name="p2wn" id="p2wn" >
							<?php	
								if($p2wn=="")
									echo "<option value=\"Malaysia\">Malaysia</option>";
								else
									echo "<option value=\"$p2wn\">$p2wn</option>";
								$sql="select * from type where grp='country' and prm!='$p2wn' order by idx,prm";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
											$s=$row['prm'];
											echo "<option value=\"$s\">$s</option>";
								}			
							?>
						</select>
					</td>
            </tr>
            <tr>
					<td valign="top">ALAMAT<br><a href="#" title="klik" onClick="samaalamat()"><font color="#33CC66">Jika sama seperti sebelah..klik!</font></a><br><font color="#0000FF" size="1">Pisahkan dengan koma ","<br>Contoh<br>no rumah,<br> nama jalan,<br> taman</font><br></td></td>
					<td><textarea name="p2addr" cols="37" rows="4" id="p2addr"><?php echo $p2addr;?></textarea></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td>BANDAR</td>
					<td><input name="p2bandar" type="text" value="<?php echo $p2bandar;?>" size="49"></td>
            </tr>
            <tr>
					<td>POSKOD</td>
					<td><input name="p2poskod" type="text" value="<?php echo $p2poskod;?>" size="27">
					<select name="p2state">
						<?php	
							if($p2state=="")
								echo "<option value=\"\">- Negeri -</option>";
							else
								echo "<option value=\"$p2state\">$p2state</option>";
							$sql="select * from state where name!='$p2state' order by name";
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
										$s=$row['name'];
										echo "<option value=\"$s\">$s</option>";
							}			
						?>
						 </select></td>
            </tr>
            <tr bgcolor="#FAFAFA">
					<td>TEL RUMAH</td>
					<td><input name="p2tel" type="text" id="p2tel" value="<?php echo $p2tel;?>"> H/P <input name="p2hp" type="text" id="p2hp" value="<?php echo $p2hp;?>"></td>
            </tr>
			<tr>
					<td>EMAIL</td>
					<td><input name="p2mel" type="text" id="p2mel" value="<?php echo $p2mel;?>" size="49"></td>
  			</tr>
            <tr bgcolor="#FAFAFA">
					<td>PEKERJAAN</td>
					<td><input name="p2job" type="text" id="p2job" value="<?php echo $p2job;?>" size="49"></td>
            </tr>
            <tr>
					<td>MAJIKAN</td>
					<td><input name="p2com" type="text" id="p2com" value="<?php echo $p2com;?>" size="49"></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td>TEL PEJABAT</td>
					<td><input name="p2tel2" type="text" id="p2tel2" value="<?php echo $p2tel2;?>"> FAX <input name="p2fax" type="text" id="p2fax" value="<?php echo $p2fax;?>"></td>
            </tr>
			<tr>
					<td>PENDAPATAN (RM)</td>
					<td><input name="p2sal" type="text" value="<?php echo $p2sal;?>"> <font color="#0000FF" size="1">Contoh 5500. Abaikan tanda RM</font></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td>TAHAP AKADEMIK</td>
					<td>
					<select name="p2edu">
							<?php	
								if($p2edu=="")
									echo "<option value=\"\">- Pilih -</option>";
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

<table width="100%">
	<tr>
	<td width="50%" id="myborder">
	
<div id="mytitlebg">D. MAKLUMAT KELUARGA </div>
<table width="70%" cellspacing="0">
                <tr>
                  <td width="22%"><strong>Nama</strong></td>
                  <td width="57%"><strong>Sekolah/IPT/Pekerjaan</strong></td>
                  <td width="18%"><strong>Hubungan</strong></td>
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
	
<div id="mytitlebg">E. MAKLUMAT WARIS (BOLEH DIHUBUNGI KETIKA KECEMASAN)</div>
<table width="100%" cellspacing="0">
          	<tr bgcolor="#FAFAFA">
					<td width="30%">NAMA</td>
					<td width="70%"><input name="p3name" type="text" id="p3name" value="<?php echo $p3name;?>" size="49"></td>
          	</tr>
			<tr>
					<td>HUBUNGAN</td>
					<td>
							<select name="p3rel" id="p3rel" >
							<?php	
							if($p3rel==""){
								echo "<option value=\"\">-Pilih-</option>";
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
					<td>KAD PENGENALAN</td>
					<td><input name="p3ic" type="text" id="p3ic" value="<?php echo $p3ic;?>"><font color="#0000FF" size="1">Cth 760103075251 (tiada '-' atau 'space')</font></td>
            </tr>
			<tr>
					<td valign="top">ALAMAT PENUH<br><font color="#0000FF" size="1">Pisahkan dengan koma ","<br>Contoh<br>no rumah,<br> nama jalan,<br>...</font><br></td>
					<td><textarea name="p3addr" cols="37" rows="4" id="p3addr"><?php echo $p3addr;?></textarea></td>
            </tr>
            <tr bgcolor="#FAFAFA">
					<td>TEL RUMAH</td>
					<td><input name="p3tel" type="text" id="p3tel" value="<?php echo $p3tel;?>"> H/P <input name="p3hp" type="text" id="p3hp" value="<?php echo $p3hp;?>"></td>
            </tr>
			<tr>
					<td>TEL PEJABAT</td>
					<td><input name="p3tel2" type="text" id="p3tel2" value="<?php echo $p3tel2;?>"> FAX <input name="p3fax" type="text" id="p3fax" value="<?php echo $p3fax;?>"></td>
            </tr>
			<tr bgcolor="#FAFAFA">
					<td>EMAIL</td>
					<td><input name="p3mel" type="text" id="p3mel" value="<?php echo $p3mel;?>" size="49"></td>
  			</tr>
</table>

</td></tr>
</table>

</div>
</form>	
</body>
</html>
