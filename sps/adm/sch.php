<?php
$vmod="v6.0.0";
$vdate="110729";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
	verify('ADMIN');
	if(is_verify("ROOT"))
		$LOCK=0;
	else 
		$LOCK=1;
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	if($sid>0)
		$sqlsch="where id=$sid";
			
    $username = $_SESSION['username'];		
	$f=$_REQUEST['f'];
	$id=$_REQUEST['id'];
	$op=$_POST['op'];
	if($op==""){
		if($id!=""){
			$sql="select * from sch where id='$id'";
			$res=mysql_query($sql,$link)or die("$sql failed:".mysql_error());
			if($row=mysql_fetch_assoc($res)){
				$name=stripslashes($row['name']);
				$level=$row['level']; //menengah rendah
				$clevel=$row['clevel']; //darjah tingkatan
				$tel=$row['tel'];
				$fax=$row['fax'];
				$addr=$row['addr'];
				$addr1=$row['addr1'];
				$country=$row['country'];
				$sname=stripslashes($row['sname']);
				$scode=$row['scode'];
				$resitno=$row['resitno'];
				$state=$row['state'];
				$poskod=$row['poskod'];
				$daerah=$row['daerah'];
				$pref=$row['stuprefix'];
				$counter=$row['stuid'];
				$schcat=$row['schcat'];
				$web=$row['url'];
				$img=$row['img'];
			}
		}
	}
	else{
		$id=$_POST['id'];
		$xid=$_POST['xid'];
		$name=addslashes($_POST['name']);
		$level=$_POST['level'];
		$tel=$_POST['tel'];
		$fax=$_POST['fax'];
		$addr=$_POST['addr'];
		$addr1=$_POST['addr1'];
		$country=$_POST['country'];
		$state=$_POST['state'];
		$pref=$_POST['pref'];
		$web=$_POST['web'];
		$sname=addslashes($_POST['sname']);
		$scode=$_POST['scode'];
		$schcat=$_POST['schcat'];
		$resitno=$_POST['resitno'];
		$poskod=$_POST['poskod'];
		$daerah=$_POST['daerah'];
		$stuid=$_POST['stuid'];
		$img=$_POST['img'];
		$op=$_POST['op'];
	
		$sql="select * from type where grp='schtype' and prm='$level' order by prm";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $clevel=$row['code'];
		$lvl=$row['val'];
		
		$clevel=$_POST['clevel'];
						
		if($op=="delete"){
			$sql="delete from sch where id='$id'";
			mysql_query($sql)or die("query failed:".mysql_error());
			$f="<font color=blue>&lt;Successfully Delete&gt;</font>";
			$id=0;
		}		
		else{
			if($id!=""){
				$sql="update sch set name='$name',level='$level',clevel='$clevel',schcat='$schcat',daerah='$daerah',poskod='$poskod',lvl=$lvl,sname='$sname',tel='$tel',fax='$fax',scode='$scode',resitno='$resitno',
				addr='$addr',addr1='$addr1',state='$state',country='$country',stuprefix='$pref',url='$web',stuid=$stuid,id=$xid,img='$img',adm='$username',ts=now() where id=$id";
				mysql_query($sql)or die("$sql failed:".mysql_error());
				$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
				$id=0;
			}
			else{
				$sql="insert into sch(cdate,name,level,tel,fax,addr,addr1,country,state,clevel,schcat,daerah,poskod,sname,lvl,stuprefix,id,stuid,url,img,scode,resitno,adm,ts) values 
				(now(),'$name','$level','$tel','$fax','$addr','$addr1','$country','$state','$clevel','schcat','$daerah','$poskod','$sname',$lvl,'$pref',$xid,$stuid,'$web','$img','$scode','$resitno','$username',now())";
				mysql_query($sql)or die("$sql failed:".mysql_error());
				$id=mysql_insert_id();
				$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
				$id=0;
			}
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
	if(op=='new'){
		document.myform.name.readOnly=false;
		document.myform.id.value="";
		document.myform.xid.value="";
		document.myform.name.value="";
		document.myform.tel.value="";
		document.myform.fax.value="";
		document.myform.addr.value="";
		document.myform.stuid.value="";
		document.myform.pref.value="";
		return;
	}
	else if(op=='delete'){
		if(document.myform.id.value==""){
			alert("<?php echo $lg_please_select;?> <?php echo $lg_school;?>");
			return;
		}
		ret = confirm("<?php echo $lg_confirm_delete;?>");
		if (ret == true){
			document.myform.op.value="delete";
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;
	}
	else{
		if(document.myform.name.value==""){
			alert("<?php echo "Silahkan Masukkan Informasi";?>");
			document.getElementById('nameerr').style.display = "inline";
			document.myform.name.focus();
			return;
		}
		if(document.myform.level.value==""){
			alert("<?php echo "Silahkan Masukkan Informasi";?>");
			document.getElementById('levelerr').style.display = "inline";
			document.myform.level.focus();
			return;
		}
		if(document.myform.tel.value==""){
			alert("<?php echo "Silahkan Masukkan Informasi";?>");
			document.getElementById('telerr').style.display = "inline";
			document.myform.tel.focus();
			return;
		}
		if(document.myform.fax.value==""){
			alert("<?php echo "Silahkan Masukkan Informasi";?>");
			document.getElementById('faxerr').style.display = "inline";
			document.myform.fax.focus();
			return;
		}
		if(document.myform.addr.value==""){
			alert("<?php echo "Silahkan Masukkan Informasi";?>");
			document.getElementById('addrerr').style.display = "inline";
			document.myform.addr.focus();
			return;
		}
		if(document.myform.state.value==""){
			alert("<?php echo "Silahkan Masukkan Informasi";?>");
			document.getElementById('stateerr').style.display = "inline";
			document.myform.state.focus();
			return;
		}
		if(document.myform.xid.value==""){
			alert("<?php echo "Silahkan Masukkan Informasi";?>");
			document.getElementById('xiderr').style.display = "inline";
			document.myform.xid.focus();
			return;
		}
		if(document.myform.pref.value==""){
			alert("<?php echo "Silahkan Masukkan Informasi";?>");
			document.getElementById('preferr').style.display = "inline";
			document.myform.pref.focus();
			return;
		}
		if(document.myform.stuid.value==""){
			alert("<?php echo "Silahkan Masukkan Informasi";?>");
			document.getElementById('stuiderr').style.display = "inline";
			document.myform.stuid.focus();
			return;
		}

		ret = confirm("<?php echo "Simpan Data";?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}
}



</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body>
<form name="myform" method="post">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="op">
<div id="panelleft"> 
	<?php include('inc/mymenu.php');?>
</div><!--end panelleft-->
<div id="content2">
<div id="mypanel">
	<div id="mymenu" align="center">
		<?php if(is_verify("ROOT")){?>
		<a href="#" onClick="process_form('new');show('panelform')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/new.png"><br><?php echo $lg_new;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
		<?php } ?>
		<a href="#" onClick="process_form('update')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br><?php echo $lg_save;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
		<?php if(is_verify("ROOT")){?>
		<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/delete.png"><br><?php echo $lg_delete;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
		<?php } ?>
		<a href="#" onClick="javascript: href='p.php?p=sch'" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
	</div>
	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>
</div>
<div id="story">

<div id="panelform" style="display:<?php if($id=="") echo "none"; else echo "block";?>">
<div id="mytitlebg">
	<div id="myclick" onClick="hide('panelform');"><img src="<?php echo $MYLIB;?>/img/icon_minimize.gif" id="mycontrolicon"><?php echo $lg_configuration;?></div>&nbsp;
</div><!-- end mytitle -->

          <table width="100%">
            <tr>
              <td width="10%"><?php echo $lg_school;?></td>
			  <td width="1%">:</td>
              <td width="90%"><input name="name" type="text" id="name" value="<?php echo $name;?>" size="53" >
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="nameerr" style="display:none"></td>
            </tr>
			 <tr>
              <td><?php echo $lg_shortname;?></td>
			  <td>:</td>
              <td><input name="sname" type="text"  value="<?php echo $sname;?>">
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="snameerr" style="display:none"></td>
            </tr>
			<tr>
              <td><?php echo $lg_category;?></td>
			  <td>:</td>
              <td>
			<select name="schcat">
<?php	
      		if($schcat!="")
                echo "<option value=$schcat>$schcat</option>";
			$sql="select * from type where grp='schcat' and prm!='$schcat' order by val";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=$s>$s</option>";
            }
?>              
  			</select> 
				<?php if(is_verify('ROOT')){?>
        			<input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=schcat',0)">
        		<?php } ?>
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="schcaterr" style="display:none">
			  
			  </td>
            </tr>
			<tr>
              <td><?php echo $lg_level;?></td>
			  <td>:</td>
              <td>
			<select name="level" id="level" >
<?php	
      		if($level!="")
                echo "<option value=$level>$level</option>";
			$sql="select * from type where grp='schtype' and prm!='$level' order by val";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=$s>$s</option>";
            }
?>              
  			</select>			
            <?php if(is_verify('ROOT')){?>
        			<input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=schtype',0)">
        		<?php } ?>
			<img src="<?php echo $MYLIB;?>/img/alert14.png" id="levelerr" style="display:none">  
			  </td>

            </tr>
			<tr>
              <td><?php echo $lg_class;?></td>
			  <td>:</td>
              <td>
			  			<select name="clevel" id="level" >
<?php	
      		if($clevel!="")
                echo "<option value=$clevel>$clevel</option>";
			$sql="select * from type where grp='schlevel' and prm!='$clevel' order by val";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=$s>$s</option>";
            }
?>              
  			</select>
            <?php if(is_verify('ROOT')){?>
        			<input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=schlevel',0)">
        		<?php } ?>
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="clevelerr" style="display:none">
			  </td>
            </tr>
            <tr>
              <td><?php echo $lg_telephone;?></td>
			  <td>:</td>
              <td><input name="tel" type="text" value="<?php echo $tel;?>">
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="telerr" style="display:none"></td>

            </tr>
            <tr>
              <td><?php echo $lg_fax;?></td>
			  <td>:</td>
              <td><input name="fax" type="text" value="<?php echo $fax;?>">
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="faxerr" style="display:none"></td>
            </tr>
			<tr>
              <td><?php echo $lg_web;?></td>
			  <td>:</td>
              <td><input name="web" type="text"  value="<?php echo $web;?>" size="38">
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="weberr" style="display:none"></td>
            </tr>
            <tr>
              <td valign="top"><?php echo $lg_address;?> <?php echo $lg_line_1;?></td>
			  <td>:</td>
              <td><input type="text" name="addr" id="addr" size="38" value="<?php echo $addr;?>">
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="addrerr" style="display:none"></td>
            </tr>
            <tr>
              <td valign="top"><?php echo $lg_address;?> <?php echo $lg_line_2;?></td>
			  <td>:</td>
              <td><input type="text" name="addr1" id="addr1" size="38" value="<?php echo $addr1;?>">
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="addrerr1" style="display:none"></td>
            </tr>
			<tr>
              <td><?php echo $lg_district;?></td>
			  <td>:</td>
              <td><input name="daerah" type="text" value="<?php echo $daerah;?>">
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="a_clslevel" style="display:none">
			  	 <?php echo $lg_postcode;?> <input name="poskod" type="text" value="<?php echo $poskod;?>">
				 <img src="<?php echo $MYLIB;?>/img/alert14.png" id="daeraherr" style="display:none">
			  </td>
            </tr>
			<tr>
              <td><?php echo $lg_state;?></td>
			  <td>:</td>
              <td>
			  <select name="state">
<?php	
      		if($state!="")
                echo "<option value=\"$state\">$state</option>";
			else
				echo "<option value=\"\">- $lg_select -</option>";
			$sql="select * from daerah where name!='$state' order by name";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['name'];
                        echo "<option value=\"$s\">$s</option>";
            }
?>              
  			</select>
			<img src="<?php echo $MYLIB;?>/img/alert14.png" id="stateerr" style="display:none">
			</td>
            </tr>
            <tr>
              <td><?php echo $lg_country;?></td>
			  <td>:</td>
              <td>
              		<select name="country">
						  <?php	
                            if($country=="")
                                echo "<option value=\"$BASE_COUNTRY\">$BASE_COUNTRY</option>";
                            else
                                echo "<option value=\"$country\">$country</option>";	
                            $sql="select * from country where name!='$country' order by name";
                            $res=mysql_query($sql)or die("query failed:".mysql_error());
                            while($row=mysql_fetch_assoc($res)){
                                        $s=$row['name'];
                                        echo "<option value=\"$s\">$s</option>";
                            }				
                            echo "<option value=\"$lg_others\">$lg_others</option>";  
                          ?>
                    </select>
              </td>
            </tr>
			<tr>
              <td><?php echo $lg_logo;?></td>
			  <td>:</td>
              <td><input name="img" type="text" value="<?php echo $img;?>"  size="53">
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="imgerr" style="display:none"></td>
            </tr>
			<tr>
              <td><?php echo $lg_school;?> <?php echo $lg_id;?></td>
			  <td>:</td>
              <td><input name="xid" type="text" value="<?php echo $id;?>" size="5" <?php if($LOCK) echo "readonly";?>>
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="xiderr" style="display:none">
			  <?php echo $lg_code;?>:<input name="scode" type="text"  value="<?php echo $scode;?>" size="5" <?php if($LOCK) echo "readonly";?>>
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="scodeerr" style="display:none"></td>
            </tr>
			<tr>
              <td><?php echo $lg_student;?> <?php echo $lg_id;?></td>
			  <td>:</td>
              <td><input name="stuid" type="text" value="<?php echo $counter;?>" size="5" <?php if($LOCK) echo "readonly";?> >
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="stuiderr" style="display:none">
			  <?php echo $lg_code;?>:<input name="pref" type="text" value="<?php echo $pref;?>" size="5" <?php if($LOCK) echo "readonly";?>>
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="preferr" style="display:none"></td>
            </tr>
			<tr>
              <td> <?php echo $lg_receipt;?></td>
			  <td>:</td>
              <td><input name="resitno" type="text" value="<?php echo $resitno;?>" size="5" <?php if($LOCK) echo "readonly";?>>
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="resitnoerr" style="display:none"></td>
            </tr>
			
          </table>
		  	

</div>

 
<div id="mytitle2"><?php echo $lg_school;?> &nbsp;<?php echo $f;?></div>
    <table width="100%" cellpadding="3" cellspacing="0">
            <tr>
              <td  class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo $lg_id;?></td>
              <td  class="mytableheader" style="border-right:none;" width="40%"><?php echo $lg_name;?></td>
              <td  class="mytableheader" style="border-right:none;" width="10%"><?php echo $lg_category;?></td>
			  <td  class="mytableheader" style="border-right:none;" width="15%"><?php echo $lg_level;?></td>
              <td  class="mytableheader" style="border-right:none;" width="15%"><?php echo $lg_telephone;?></td>
              <td  class="mytableheader" style="border-right:none;" width="15%"><?php echo $lg_fax;?></td>
            </tr>
	<?php	
		$sql="select * from sch $sqlsch order by id,name";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$id=$row['id'];
			$name=stripslashes($row['name']);
			$lvl=$row['level'];
			$tel=$row['tel'];
			$fax=$row['fax'];
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>

 <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  				<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$id";?></td>
              <td class="myborder" style="border-right:none; border-top:none;"><a href="<?php echo "p.php?p=sch&id=$id";?>"><?php echo "$name";?></a></td>
              <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$lvl";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;">
			  <?php
			  	$j=0;
			  	$sql="select * from type where grp='classlevel' and sid='$id' order by prm";
				$res2=mysql_query($sql)or die("query failed:".mysql_error());
				while($row2=mysql_fetch_assoc($res2)){
							if($j++>0)
								echo ",";
							$s=$row2['prm'];
							echo "$s ";
				}
			  	
			  ?>
			  </td>
              <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$tel";?></td>
              <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$fax";?></td>
            </tr>
		
<?php 	} ?>
		</table>

</body>
</div></div>
</form>
</html>
