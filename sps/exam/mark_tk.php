<?php
//110610 - upgrade gui
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php");
verify("");
$adm=$_SESSION['username'];
$f=$_REQUEST['f'];
$p=$_REQUEST['p'];
$uid=$_REQUEST['uid'];
$lvl=$_REQUEST['lvl'];
$cls=$_REQUEST['cls'];
$year=$_REQUEST['year'];
$arrmarkah=$_POST['markah'];
$arrkomentar=$_POST['tk_komentar'];
$arrsub_type=$_POST['sub_type'];
$op=$_REQUEST['op'];
$sid=$_REQUEST['sid'];
if($sid=="")
		$sid=$_SESSION['sid'];

$edit=$_REQUEST['edit'];

$sql="select * from sch where id='$sid'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$sname=stripslashes($row['sname']);
$sqlsid="and sid='$sid'";
		

$exam=$_REQUEST['exam'];
$sqlexam="and exam='$exam'";
$sql="select * from type where grp='exam' and code='$exam'";
$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$examname=$row['prm'];

//echo $msg."<BR>";
//$fn=$_FILES['fileupload']['tmp_name'];
$fn=basename( $_FILES['fileupload']['name']);
if($fn!=""){
	$ext=substr(
		    $fn,-3,3);
	$msg=$_REQUEST['pic_des'];
	$sql="insert into exam_stu_summary(uid,sid,msg,cls,lvl,year,exam,ts,adm,type)values('$uid',$sid,'$msg','$cls','$lvl','$year','$exam',now(),'$adm','exampicture')";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$xxid=mysql_insert_id();
	
	$fn="exampicture_$xxid.$ext";
	$target_path ="$dir_image_student/student_activity/$fn";
	echo $target_path;
	if(move_uploaded_file($_FILES['fileupload']['tmp_name'], $target_path)) { 	
				$sql="update exam_stu_summary set file='$fn' where id='$xxid'";
	    	  	mysql_query($sql)or die("query failed:".mysql_error());
				$f="<font color=blue>&lt;SUCCESSFULY UPLOAD&gt;</font>";
	}
	else{
				$f="<font color=red>&lt;FAIL TO UPLOAD&gt;</font>";
	}
	//$fn=sprintf("%s%s",$uid,$ext);
	//$sql="insert into exam_stu_summary(uid,sid,msg,cls,lvl,year,exam,ts,adm,sakit,izin,noreason,type)values('$uid',$sid,'$msg','$cls','$lvl','$year','$exam',now(),'$adm','$sakit','$izin','$alpa','comment1')";
	//$res=mysql_query($sql)or die("query failed:".mysql_error());
}
if($op=="savecomment1"){
			$msg=addslashes($_POST['FCKeditor1']);
			$sql="delete from exam_stu_summary where uid='$uid' and year='$year' and exam='$exam' and sid='$sid' and type='comment1'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$sql="insert into exam_stu_summary(uid,sid,msg,cls,lvl,year,exam,ts,adm,sakit,izin,noreason,type)values('$uid',$sid,'$msg','$cls','$lvl','$year','$exam',now(),'$adm','$sakit','$izin','$alpa','comment1')";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
}
if($op=="savecomment2"){
			$msg=addslashes($_POST['FCKeditor2']);
			$sql="delete from exam_stu_summary where uid='$uid' and year='$year' and exam='$exam' and sid='$sid'  and type='comment2'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$sql="insert into exam_stu_summary(uid,sid,msg,cls,lvl,year,exam,ts,adm,sakit,izin,noreason,type)values('$uid',$sid,'$msg','$cls','$lvl','$year','$exam',now(),'$adm','$sakit','$izin','$alpa','comment2')";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
}
	if($op=="save" or $op=="save1"){
		for ($a=0; $a<count($arrsub_type); $a++) {
		$sub_type=$arrsub_type[$a];
		$komentar=$arrkomentar[$a];
	
		$sqlcekkomentar="select * from sub_stu_summary where sid='$sid' and exam='$exam' and uid='$uid' and year='$year' and cls='$cls' and sub_type='$sub_type'";
		$rescekkomentar=mysql_query($sqlcekkomentar)or die("$sqlcekkomentar query failed:".mysql_error());
		if(mysql_num_rows($rescekkomentar)==0){
			$simpan="insert into sub_stu_summary (uid,sid,exam,year,cls,ts,sub_msg,sub_type) values ('$uid','$sid','$exam','$year','$cls',now(),'$komentar','$sub_type')";		
			mysql_query($simpan)or die("$simpan query failed:".mysql_error());
		}
		else {
			$simpan="update sub_stu_summary set sub_msg='$komentar', ts=now() where sid='$sid' and exam='$exam' and uid='$uid' and year='$year' and cls='$cls' and sub_type='$sub_type'";
			mysql_query($simpan)or die("$simpan query failed:".mysql_error());
		}
		//echo "$simpan<br>";
	} // end for comment category
	
		for ($i=0; $i<count($arrmarkah); $i++)
		{
					$data=$arrmarkah[$i];				
					list($markah,$gred,$sub)=explode("|",$data);
					if($sub=="")
						continue;
					$sql="select * from stu where uid='$uid'";
            		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
            		$row=mysql_fetch_assoc($res);
					$stu_ic=$row['ic'];
					$stu_uid=$row['uid'];
					$stu_name=$row['name'];
					mysql_free_result($res);
				
					$sql="delete from exam where stu_uid='$uid' and sub_code='$sub' and cls_level='$lvl' and examtype='$exam' and sch_id=$sid and year='$year'";
					$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
					$sqldelkomentar="delete from sub_stu_summary where sid='$sid' and sub_code='$sub' and exam='$exam' and uid='$uid'";
					$resdelkomentar=mysql_query($sqldelkomentar)or die("$sqldelkomentar query failed:".mysql_error());
					
					$astu_name=addslashes($stu_name);
					$asubname=addslashes($subname);
					$aclsname=addslashes($clsname);
					$ausrname=addslashes($usrname);
					$val=0;
					if(is_numeric($markah))
						$val=$markah;

					$grading="SET_TK";	
					$gp=0;
					$isfail=0;
					if($gradingtype==1)
						$sql="select * from grading where name='$grading' and grade='$markah' order by val desc limit 1";
					elseif(!is_numeric($markah))
						$sql="select * from grading where name='$grading' and grade='$markah' order by val desc limit 1";
					else
						$sql="select * from grading where name='$grading' and point<=$val order by val desc limit 1";
					$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
					if($row3=mysql_fetch_assoc($res3)){
						$gp=$row3['gp'];
						$gred=$row3['grade'];
						$isfail=$row3['sta'];
					}
						$credit=0;
						$sql="insert into exam(dt,sch_id,year,cls_name,cls_level,cls_code,
								stu_uid,stu_name,usr_uid,sub_code,sub_name,sub_grp,sub_type,grading,gradingtype,
								point,grade,examtype,adm,ts,val,gp,credit,isfail) values 
								(now(),'$sid','$year','$aclsname','$lvl','$cls',
								'$uid','$astu_name','$usruid','$sub','$asubname','$grp','$grptype','$grading','$gradingtype',
								'$markah','$gred','$exam','$username',now(),$val,$gp,$credit,$isfail)";
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());



					//echo $simpan."<BR>";
					$f="<font color=blue>&lt;SUCCESSFULY SAVE&gt;</font>";
		}//for
		
		
		
		

		
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>

<script language="javascript">
function process_new(){
	document.myform.code.value="";
	document.myform.item.value="";
	document.myform.val.value="";
	document.myform.edit.value="";
}
function process_form(action,xid){
	var ret="";
	var cflag=false;
	
		document.myform.op.value=action;
		document.myform.submit();		
		return;
}

</script>

</script>

</head>

<body >

<form name="myform" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
 	<input type="hidden" name="p" value="<?php echo $p;?>">
 	<input type="hidden" name="op">
	<input type="hidden" name="xid">
    <input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="cls" value="<?php echo $cls;?>">
	<input type="hidden" name="lvl" value="<?php echo $lvl;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="exam" value="<?php echo $exam;?>">
	<input type="hidden" name="year" value="<?php echo $year;?>">


<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
        <!--
				<a href="#" onClick="process_form('save')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br>Save</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
         -->
                <a href="#" onClick="document.myform.submit();"  id="mymenuitem" ><img src="<?php echo $MYLIB;?>/img/reload.png"><br>Refresh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="process_form('save1');"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		</div> <!-- end mymenu -->

		<div align="right"  >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
</div> <!-- end mypanel -->
<div id="story" style="min-height:none;">
<div id="mytitle2">FORM INPUT NILAI RAPORT INTERNAL SEKOLAH ALAM <?php echo $f;?></div>
<?php
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res=mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_assoc($res);
		$ccode=$row['cls_code'];
		$cname=stripslashes($row['cls_name']);
		
		$sql="select * from stu where uid='$uid' and sch_id=$sid";
		$res=mysql_query($sql) or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$name=stripslashes($row['name']);
		$ic=$row['ic'];
		$sex=$row['sex'];
		$mentor=$row['mentor'];
		$rdate=$row['rdate'];
		$file=$row['file'];
		$totaljk=$row['hafazan_totaljk'];

		$sql="select * from usr where uid='$mentor'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$mentorname=$row['name'];
?>
<table width="100%" id="mytitlebg" style="font-size: 12px">
  <tr>
  <?php if(($file!="")&&($CONFIG['SHOW_PICTURE']['val'])){?>
  	<td width="8%" align="center">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="75" id="myborderfull" style="padding:3px 3px 3px 3px ">
	</td>
 <?php } ?>
    <td width="50%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="30%" valign="top"><?php echo strtoupper($lg_student_name);?></td>
        <td width="1%" valign="top">:</td>
        <td width="70%"><?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper("Nomor Induk");?></td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_class);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$cname");?> </td>
      </tr>
	<tr>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>


	</td>
    <td width="50%" valign="top">
	
	<table width="100%" >
	  <tr>
        <td><?php echo strtoupper($lg_exam);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$examname");?></td>
      </tr>
	<tr>
		<td><?php echo "SEMESTER";?></td>
		<td>:</td>
		<td>
			<?php if($exam=='UN'||$exam=='UAS'){$sem="-";}if($exam=='PS1'||$exam=='UTSGA'){$sem="1 (SATU)";}if($exam=='PS2'||$exam=='UTSGE'){$sem="2 (DUA)";} echo $sem;?> </td>
	</tr>
	<tr>
        <td><?php echo strtoupper($lg_year);?></td>
        <td>:</td>
        <td><?php echo $year;?> </td>
      </tr>

    </table>
 	</td>
  </tr>
</table>



<div id="mytabletitle">
<select name="sid" onchange="document.myform.submit();">
					<?php
						if($sid>0)
								echo "<option value=\"$sid\">$sname</option>";
						else
								echo "<option value=\"0\">- Pilih -</option>";
                        if($_SESSION['sid']==0){
								$sql="select * from sch where id!='$sid' and id>0 order by name";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
										$s=$row['sname'];
											$t=$row['id'];
											echo "<option value=$t>$s</option>";
								}
                        }
                    ?>
</select>
<select name="lvl" onchange="document.myform.submit();">
						<?php    
                        if($lvl=="")
                                echo "<option value=\"\">- $lg_level -</option>";
                        else
                                echo "<option value=$lvl>$lvl</option>";
						$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$lvl'order by prm";
				        $res=mysql_query($sql)or die("query failed:".mysql_error());
                        while($row=mysql_fetch_assoc($res)){
                                $s=$row['prm'];
                                echo "<option value=\"$s\">$s</option>";
                        }  
                        ?>		
</select>

<select name="exam" onchange="document.myform.submit();">
        <?php	
      				if($exam=="")
						echo "<option value=\"\">- $lg_exam -</option>";
					else
						echo "<option value=\"$exam\">$examname</option>";
					$sql="select * from type where grp='exam' and code!='$exam' order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}	
			?>
</select>
	  
</div> 

<div id="mytitlebg"><a href="#" onClick="showhide('div1')">URAIAN PELAJARAN >> </a></div>
<?php

		$sql="select * from exam_stu_summary where sid='$sid' and uid='$uid' and exam='$exam' and year='$year' and type='comment1'";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row3=mysql_fetch_assoc($res3);
		$msg=$row3['msg'];
		echo $msg;
?>
<div id="div1" style="width:60%; display:none;">
<?php
		$sBasePath="$MYOBJ/fckeditor/";//hardcode path set by apai
		$fck = new FCKeditor('FCKeditor1') ;
		$fck->BasePath = $sBasePath ;
		$fck->Height='220';
		$fck->ToolbarSet = preg_replace("/[^a-z]/i", "", "Custom");//null = Default
		$fck->Value = $msg;
		$fck->Create() ;
?>
<input type="button" value="SAVE" size="50" onClick="process_form('savecomment1')">
</div>

<br>
<br>
<div id="mytitlebg"><a href="#" onClick="showhide('div2')">TUJUAN PELAJARAN >> </a></div>
<?php

		$sql="select * from exam_stu_summary where sid='$sid' and uid='$uid' and exam='$exam' and year='$year' and type='comment2'";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row3=mysql_fetch_assoc($res3);
		$msg=$row3['msg'];
		echo $msg;
?>
<div id="div2" style="width:60%; display:none;">
<?php		
		$sBasePath="$MYOBJ/fckeditor/";//hardcode path set by apai
		$fck2 = new FCKeditor('FCKeditor2') ;
		$fck2->BasePath = $sBasePath ;
		$fck2->Height='220';
		$fck2->ToolbarSet = preg_replace("/[^a-z]/i", "", "Custom");//null = Default
		$fck2->Value = $msg;
		$fck2->Create() ;
?>
<input type="button" value="SAVE" size="50" onClick="process_form('savecomment2')">
</div>
<br>
<br>

<div id="mytitlebg">CAPAIAN PEMBELAJARAN</div>
<table width="100%" cellspacing="0" cellpadding="2" style="font-size:11px">
	<tr>
    		<td class="mytableheader" style="border-right:none;" width="5%" align="center">No</td>
		    <td class="mytableheader" style="border-right:none;" width="50%" >Deskripsi</td>            
			<td class="mytableheader" style="border-right:none;" width="5%" align="center">Nilai</td>
			<?php if($ON_TK_KOMENTAR){?>
			<td class="mytableheader" style="border-right:none;" width="5%" align="center">Komentar</td>
			<?php }?>
	</tr>
<?php
		$q=0;
 		$sql="select * from type where grp='sub_cate' order by idx";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
      	while($row=mysql_fetch_assoc($res)){
				$cate=$row['prm'];
				$q++;
				$jj=0;
				
?>				
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
					<td id="mytabletitle" style="border-right:none;" align="center"><?php echo $q;?></td>
                    <td id="mytabletitle" style="border-right:none;"><?php echo $cate;?></td>
                    <td id="mytabletitle" style="border-right:none;">&nbsp;</td>
					<?php if($ON_TK_KOMENTAR){?>
					<td id="mytabletitle" style="border-right:none;">&nbsp;</td>
					<?php }?>
     </tr>
     
<?php
		$sql="select distinct(type) from sub_construct where cate='$cate' and isdel=0 order by cate";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
      	while($row2=mysql_fetch_assoc($res2)){
				$type=$row2['type'];
				$id=$row2['type'];
				$jj++;
				$bg="#FAFAFA";
				if($type!=""){
?>				
            <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                            <td id="myborder" style="border-top:none;border-right:none;" align="right"><?php echo "$q-$jj";?></td>
                            <td id="myborder" style="border-top:none;border-right:none;"><?php echo $type;?> <input type="hidden" name="sub_type[]" value="<?php echo "$type";?> "></td>
                            <td id="myborder" style="border-top:none;border-right:none;">&nbsp;</td>
			    <?php 
						if($ON_TK_KOMENTAR){
						
						$sqltkkomentar="select * from sub_stu_summary where sid='$sid' and exam='$exam' and uid='$uid' and year='$year' and cls='$cls' and sub_type='$type'";
						$restkkomentar=mysql_query($sqltkkomentar)or die("$sqltkkomentar query failed:".mysql_error()); 
						$rowtkkomentar=mysql_fetch_assoc($restkkomentar);
						$tkkomentar=$rowtkkomentar['sub_msg'];
						
				?>
			    <td id="myborder" style="border-top:none;border-right:none;">
				<textarea name="tk_komentar[]" cols="35" rows="3"><?php echo $tkkomentar;?></textarea>
			    </td>
							<?php if($ON_TK_KOMENTAR){?>
							<?php }?>
             </tr>
            <?php //show itemized item
		}
				$kk=0;
				$sql="select * from sub_construct where type='$type' and cate='$cate' and sid='$sid' and lvl='$lvl' and exam='$exam' and isdel=0 order by cate, code asc";
				$res3=mysql_query($sql)or die("query failed:".mysql_error());
				while($row3=mysql_fetch_assoc($res3)){
						$xid=$row3['id'];
						$cc=$row3['code'];
						$vv=$row3['val'];
						$item=$row3['item'];
						$grading=$row3['grading'];
						$kk++;
						$totalitem=0;
						$bg="";
						?>
                        <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                                    <td id="myborder" style="border-top:none;border-right:none;" align="right"><?php echo "$q-$jj-$kk";?></td>
                                    <td id="myborder" style="border-top:none;border-right:none;">&nbsp;&nbsp;<?php echo " $item";?></td>
                                    <td id="myborder" style="border-top:none;border-right:none;" align="center">
									<select name="markah[]" id="markah[]">
			
			<?php
						$sql="select * from exam where stu_uid='$uid' and year='$year' and sub_code='$cc' and sch_id='$sid'";
						$res6=mysql_query($sql)or die("$sql failed:".mysql_error());
						$row6=mysql_fetch_assoc($res6);
						$grade=$row6['grade'];
						$point=$row6['point'];
						if($grade=="")
								echo "<option value=\"\">- $lg_select -</option>";
						else
								echo "<option value=\"$point|$grade|$cc\">$grade</option>";
								
						$sql="select * from grading where name='$grading' order by val desc";
						$res4=mysql_query($sql)or die("$sql failed:".mysql_error());
						while($row4=mysql_fetch_assoc($res4)){
							$xp=$row4['point'];
							$xg=$row4['grade'];
							$xd=$row4['des'];
							echo "<option value=\"$xp|$xg|$cc\">$xg</option>";
						}
			?>
								</select>	
									</td>
						
									<td id="myborder" >
									
									</td>
						<?php }?>
                        </tr>
<?php } ?>
<?php } ?> 
<?php } ?>     
 </table>
 <div align="right"><input type="button" value="<?php echo $lg_update;?>" onclick="process_form('save');" style="width:200px"></div>

</div><!-- story -->
</div><!-- content -->
</form>	
</body>
</html>
