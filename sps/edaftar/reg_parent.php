<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>
<body>
<div id="myborder" style="border-color:#333333; border-bottom:none;"></div>
<table width="100%" cellspacing="0"  style=" background-color:#FFF;">
  <tr><td width="50%" id="myborder"  style="border-right:none; ">
		<div id="mytitlebg" style="background-color:#FFFF00">D. <?php echo strtoupper($lg_father_information);?></div>
		<table width="100%" id="mytitle" style="font-size:10px;">
          	<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_name;?>*
              <td  width="70%"><input name="p1name" type="text" id="p1name" value="<?php echo $p1name;?>" size="38"></td>
  			</tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_ic;?>*
              <td><input name="p1ic" type="text" id="p1ic" value="<?php echo $p1ic;?>" size="12"></td>
            </tr>
<?php if($SHOW_ANAK_NEGERI){?>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_birth_place;?>*
				<td><select name="p1bstate" id="p1bstate" >
                  <?php	
						if($p1bstate=="")
							echo "<option value=\"\">- $lg_select -</option>";
						else
							echo "<option value=\"$p1bstate\">$p1bstate</option>";
						$sql="select * from state where name!='$p1bstate' order by name";
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['name'];
									echo "<option value=\"$s\">$s</option>";
						}
				?>
                </select>              
				</td>
            </tr>
<?php } ?>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_job;?>*
              <td ><input name="p1job" type="text" id="p1job" value="<?php echo $p1job;?>" size="38"></td>
            </tr>
			 <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_salary;?>* (<?php echo strtoupper($lg_rm);?>)
              <td><input name="p1sal" type="text" id="p1sal" value="<?php echo $p1sal;?>" size="12" onKeyUp="chkno(this);"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">Pendidikan Tertinggi</td>
              <td><input name="p1edu" type="text" value="<?php echo $p1edu;?>"></td>
            </tr>            
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_employer;?>*
				<td ><input name="p1com" type="text" id="p1com" value="<?php echo $p1com;?>" size="38"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_address;?>
				<td><textarea name="p1addr" cols="29" rows="3" id="p1addr"><?php echo $p1addr;?></textarea></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_tel_mobile;?>*
				<td><input name="p1hp" type="text" id="p1hp" value="<?php echo $p1hp;?>" size="14" onKeyUp="chkno(this);">
				(<?php echo $lg_no_space_and_dash;?>)</td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_tel_home;?>
				<td><input name="p1tel" type="text" id="p1tel" value="<?php echo $p1tel;?>" size="14" onKeyUp="chkno(this);"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_tel_office;?><td>
                <input name="p1tel2" type="text" id="p1tel2" value="<?php echo $p1tel2;?>" size="14" onKeyUp="chkno(this);"> 
                <?php echo $lg_fax;?>: <input name="p1fax" type="text" id="p1fax" value="<?php echo $p1fax;?>" size="14" onKeyUp="chkno(this);">
                </td>
            </tr>
            
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_email;?>
					<td>
                    	<input name="p1mel" type="text" id="p1mel" value="<?php echo $p1mel;?>" size="38"><br>
                        Contoh: andiyahoo.com
					</td>
  			</tr>
</table>
	
	</td>
    <td width="50%" id="myborder" >
	
		<div id="mytitlebg" style="background-color:#FFFF00 ">E. <?php echo strtoupper($lg_mother_information);?></div>
        <table width="100%" id="mytitle" style="font-size:10px;">
          	<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_name;?>*</td>
            	<td width="70%" ><input name="p2name" type="text" id="p2name" value="<?php echo $p2name;?>" size="38"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_ic;?>*</td>
              	<td><input name="p2ic" type="text" id="p2ic" value="<?php echo $p2ic;?>" size="12"  onKeyUp="chkno(this);"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_job;?>*</td>
              <td><input name="p2job" type="text" id="p2job" value="<?php echo $p2job;?>" size="20">
              <a style="cursor:pointer" onClick="document.myform.p2sal.value=0;document.myform.p2job.value='<?php echo $lg_house_wife;?>';">Klik jika pekerjaan <?php echo $lg_house_wife;?> (<?php echo $lg_click;?>)</a>
			  </td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_salary;?>* (<?php echo strtoupper($lg_rm);?>)</td>
              <td><input name="p2sal" type="text" id="p2sal" value="<?php echo $p2sal;?>" size="12" onKeyUp="chkno(this);"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">Pendidikan Tertinggi</td>
              <td><input name="p2edu" type="text" value="<?php echo $p2edu;?>"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_employer;?></td>
              <td><input name="p2com" type="text" id="p2com" value="<?php echo $p2com;?>" size="38"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_address;?></td>
              <td><textarea name="p2addr" cols="29" rows="3" id="p2addr"><?php echo $p2addr;?></textarea></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_tel_mobile;?>*</td>
              <td> <input name="p2hp" type="text" id="p2hp" value="<?php echo $p2hp;?>" size="14" onKeyUp="chkno(this);">
			  (<?php echo $lg_no_space_and_dash;?>)</td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_tel_home;?></td>
              <td><input name="p2tel" type="text" id="p2tel" value="<?php echo $p2tel;?>" size="14" onKeyUp="chkno(this);"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_tel_office;?></td>
              <td><input name="p2tel2" type="text" id="p2tel2" value="<?php echo $p2tel2;?>" size="14" onKeyUp="chkno(this);"> <?php echo $lg_fax;?> :
                	<input name="p2fax" type="text" id="p2fax" value="<?php echo $p2fax;?>" size="14" onKeyUp="chkno(this);"></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_email;?></td>
			  <td><input name="p2mel" type="text" id="p2mel" value="<?php echo $p2mel;?>" size="38"><br>Contoh: andi@yahoo.com</td>
		  </tr>
</table>
	
	</td>
  </tr>
</table>
<div id="myborder" style="border-color:#333333; border-bottom:none;"></div>
<table width="100%" cellspacing="0"  style="background-color:#FFF;">
  <tr>
    <td width="50%" valign="top" id="myborder" style="border-right:none; ">
		<div id="mytitlebg" style="background-color:#FFFF00">F. <?php echo strtoupper($lg_family_information);?></div>
			  <table width="100%"  id="mytitle" style="font-size:10px;">
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td width="1%">&nbsp;</td>
				  <td width="30%"><?php echo $lg_name;?></td>
                  <td width="30%"><?php echo $lg_school_ipt_job;?></td>
                  <td width="40%"><?php echo $lg_birth_year;?></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td align="right">1.</td>
                  <td><input name="q01" type="text" id="q01" value="<?php echo "$q01";?>" size="30"></td>
                  <td><input name="q02" type="text" id="q02" value="<?php echo "$q02";?>" size="30"></td>
                  <td><input name="q03" type="text" id="q03" value="<?php echo "$q03";?>" size="5"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td align="right">2.</td>
                  <td><input name="q11" type="text" id="q11" value="<?php echo "$q11";?>" size="30"></td>
                  <td><input name="q12" type="text" id="q12" value="<?php echo "$q12";?>" size="30"></td>
                  <td><input name="q13" type="text" id="q13" value="<?php echo "$q13";?>" size="5"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td align="right">3.</td>
                  <td><input name="q21" type="text" id="q21" value="<?php echo "$q21";?>" size="30"></td>
                  <td><input name="q22" type="text" id="q22" value="<?php echo "$q22";?>" size="30"></td>
                  <td><input name="q23" type="text" id="q23" value="<?php echo "$q23";?>" size="5"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td align="right">4.</td>
                  <td><input name="q31" type="text" id="q31" value="<?php echo "$q31";?>" size="30"></td>
                  <td><input name="q32" type="text" id="q32" value="<?php echo "$q32";?>" size="30"></td>
                  <td><input name="q33" type="text" id="q33" value="<?php echo "$q33";?>" size="5"></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td align="right">5.</td>
                  <td><input name="q41" type="text" id="q41" value="<?php echo "$q41";?>" size="30"></td>
                  <td><input name="q42" type="text" id="q42" value="<?php echo "$q42";?>" size="30"></td>
                  <td><input name="q43" type="text" id="q43" value="<?php echo "$q43";?>" size="5"></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td align="right">6.</td>
                  <td><input name="q51" type="text" id="q51" value="<?php echo "$q51";?>" size="30"></td>
                  <td><input name="q52" type="text" id="q52" value="<?php echo "$q52";?>" size="30"></td>
                  <td><input name="q53" type="text" id="q53" value="<?php echo "$q53";?>" size="5"></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td align="right">7.</td>
                  <td><input name="q61" type="text" id="q61" value="<?php echo "$q61";?>" size="30"></td>
                  <td><input name="q62" type="text" id="q62" value="<?php echo "$q62";?>" size="30"></td>
                  <td><input name="q63" type="text" id="q63" value="<?php echo "$q63";?>" size="5"></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td align="right">8.</td>
                  <td><input name="q71" type="text" id="q71" value="<?php echo "$q71";?>" size="30"></td>
                  <td><input name="q72" type="text" id="q72" value="<?php echo "$q72";?>" size="30"></td>
                  <td><input name="q73" type="text" id="q73" value="<?php echo "$q73";?>" size="5"></td>
                </tr>
	<!-- 
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td align="right">9.</td>
                  <td><input name="q81" type="text" id="q81" value="<?php echo "$q81";?>" size="30"></td>
                  <td><input name="q82" type="text" id="q82" value="<?php echo "$q82";?>" size="30"></td>
                  <td><input name="q83" type="text" id="q83" value="<?php echo "$q83";?>" size="5"></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td align="right">10.</td>
                  <td><input name="q91" type="text" id="q91" value="<?php echo "$q91";?>" size="30"></td>
                  <td><input name="q92" type="text" id="q92" value="<?php echo "$q92";?>" size="30"></td>
                  <td><input name="q93" type="text" id="q93" value="<?php echo "$q93";?>" size="5"></td>
                </tr>
	 -->
				
              </table>
	
	</td>
    <td width="50%" valign="top" id="myborder">
			<div id="mytitlebg" style="background-color:#FFFF00 ">G. <?php echo strtoupper($lg_for_emergency_call);?> (<?php echo $lg_not_parent;?>)</div>
            <table width="100%"  id="mytitle" style="font-size:10px;">
          	<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td width="30%" id="myborder" style="border-top:none;border-left:none;border-right:none;">&nbsp;<?php echo $lg_name;?>*</td>
              <td width="70%"><input name="p3name" type="text" id="p3name" value="<?php echo $p3name;?>" size="38"></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td id="myborder" style="border-top:none;border-left:none;border-right:none;">&nbsp;<?php echo $lg_relation;?>dengan Murid*</td>
              <td> <input type="text" name="p3rel" id="p3rel" value="<?php echo $p3rel;?>" size="38">
			  </td>
            </tr>
			 <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td id="myborder" style="border-top:none;border-left:none;border-right:none;">&nbsp;<?php echo $lg_tel_mobile;?>*
			  </td>
              <td ><input name="p3hp" type="text" id="p3hp" value="<?php echo $p3hp;?>" onKeyUp="chkno(this);"> (<?php echo $lg_no_space_and_dash;?>)</td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td id="myborder" style="border-top:none;border-left:none;border-right:none;">&nbsp;<?php echo $lg_tel_home;?></td>
              <td ><input name="p3tel" type="text" id="p3tel" value="<?php echo $p3tel;?>" onKeyUp="chkno(this);"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td id="myborder" style="border-top:none;border-left:none;border-right:none;">&nbsp;<?php echo $lg_tel_office;?></td>
              <td><input name="p3tel2" type="text" id="p3tel2" value="<?php echo $p3tel2;?>" onKeyUp="chkno(this);"> </td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td id="myborder" style="border-top:none;border-left:none;border-right:none;">&nbsp;<?php echo $lg_fax;?></td>
              <td ><input name="p3fax" type="text" id="p3fax" value="<?php echo $p3fax;?>" onKeyUp="chkno(this);"></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td id="myborder" style="border-top:none;border-left:none;border-right:none;border-bottom:none;">&nbsp;<?php echo $lg_email;?></td>
			  <td ><input name="p3mel" type="text" id="p3mel" value="<?php echo $p3mel;?>" size="38">
              <br>          		Contoh: andi@yahoo.com
              </td>
		  	</tr>
          </table>
	
	</td>
  </tr>
</table>



		  

		  
</body>
</html>
