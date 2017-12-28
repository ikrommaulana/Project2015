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
$cttn=$_REQUEST['cttn'];
$arrsub_surah=$_POST['sub_surah'];
$arrmarkah_surah=$_POST['markah_surah'];
$arrsub_baliigho=$_POST['sub_baliigho'];
$arrmarkah_baliigho=$_POST['markah_baliigho'];
$op=$_REQUEST['op'];
$sid=$_REQUEST['sid'];
if($sid=="")
		$sid=$_SESSION['sid'];	

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

if($op=="save_comment"){
	$sqlcekcomment="select * from hafaz_stu_summary where sid='$sid' and uid='$uid' and exam='$exam' and year='$year'";
	$rescekcomment=mysql_query($sqlcekcomment)or die("$sqlcekcomment query failed:".mysql_error());
	$numrowcek=mysql_num_rows($rescekcomment);

	if($numrowcek>0){
		$sqlupdate="update hafaz_stu_summary set hafaz_msg='$cttn' where sid='$sid' and uid='$uid' and exam='$exam' and year='$year'";
	}else{
		$sqlupdate="insert into hafaz_stu_summary(uid,sid,exam,year,cls,adm,ts,hafaz_msg)values('$uid','$sid','$exam','$year','$cls','$adm',now(),'$cttn');";
	}
	$res=mysql_query($sqlupdate)or die("$sqlupdate query failed:".mysql_error());


}
if($op=="save"){
for ($i=0; $i<count($arrmarkah_surah); $i++) {
					$data_surah=$arrmarkah_surah[$i];
					$markah_surah=$data_surah;

					$sub_surah=$arrsub_surah[$i];

					$sql="select * from stu where uid='$uid'";
            		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
            		$row=mysql_fetch_assoc($res);
					$stu_ic=$row['ic'];
					$stu_uid=$row['uid'];
					$stu_name=$row['name'];
					mysql_free_result($res);
				
					$sql="delete from hafaz_exam where stu_uid='$uid' and sub_code='$sub_surah' and cls_level='$lvl' and examtype='$exam' and sch_id=$sid and year='$year'";
					$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
					
					$astu_name=addslashes($stu_name);
					$asubname=addslashes($subname);
					$aclsname=addslashes($clsname);
					$ausrname=addslashes($usrname);
					$val=0;
					if(is_numeric($markah_surah))
						$val=$markah_surah;

					$grading="SET_HAFAZ";	
					$gp=0;
					$isfail=0;
					if($gradingtype==1)
						$sql="select * from grading where name='$grading' and grade='$markah_surah' order by val desc limit 1";
					elseif(!is_numeric($markah))
						$sql="select * from grading where name='$grading' and grade='$markah_surah' order by val desc limit 1";
					else
						$sql="select * from grading where name='$grading' and point<=$val order by val desc limit 1";
					$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
					if($row3=mysql_fetch_assoc($res3)){
						$gp=$row3['gp'];
						$gred=$row3['grade'];
						$isfail=$row3['sta'];
					}
						$credit=0;
						$sql="insert into hafaz_exam(dt,sch_id,year,cls_name,cls_level,cls_code,
								stu_uid,stu_name,usr_uid,sub_code,sub_name,sub_grp,sub_type,grading,gradingtype,
								point,grade,examtype,adm,ts,val,gp,credit,isfail) values 
								(now(),'$sid','$year','$aclsname','$lvl','$cls',
								'$uid','$astu_name','$usruid','$sub_surah','$asubname','$grp','$grptype','$grading','$gradingtype',
								'$markah_surah','$gred','$exam','$username',now(),$val,$gp,$credit,$isfail)";
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());
					//echo $sql."<BR>";
}
for ($i=0; $i<count($arrmarkah_baliigho); $i++) {
					$data=$arrmarkah_baliigho[$i];
					$markah=$data;
					//list($markah,$gred,$sub)=explode("|",$data);
					$sub_baliigho=$arrsub_baliigho[$i];

					$sql="select * from stu where uid='$uid'";
            		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
            		$row=mysql_fetch_assoc($res);
					$stu_ic=$row['ic'];
					$stu_uid=$row['uid'];
					$stu_name=$row['name'];
					mysql_free_result($res);
				
					$sql="delete from hafaz_exam where stu_uid='$uid' and sub_code='$sub_baliigho' and cls_level='$lvl' and examtype='$exam' and sch_id=$sid and year='$year'";
					$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
					
					$astu_name=addslashes($stu_name);
					$asubname=addslashes($subname);
					$aclsname=addslashes($clsname);
					$ausrname=addslashes($usrname);
					$val=0;
					if(is_numeric($markah))
						$val=$markah;

					$grading="SET_HAFAZ";	
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
						$sql="insert into hafaz_exam(dt,sch_id,year,cls_name,cls_level,cls_code,
								stu_uid,stu_name,usr_uid,sub_code,sub_name,sub_grp,sub_type,grading,gradingtype,
								point,grade,examtype,adm,ts,val,gp,credit,isfail) values 
								(now(),'$sid','$year','$aclsname','$lvl','$cls',
								'$uid','$astu_name','$usruid','$sub_baliigho','$asubname','$grp','$grptype','$grading','$gradingtype',
								'$markah','$gred','$exam','$username',now(),$val,$gp,$credit,$isfail)";
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());
					//echo $sql."<BR>";
					$f="<font color=blue>&lt;SUCCESSFULY SAVE&gt;</font>";
	
}//for
				
} // end if( save



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
	
	if(action=='save'){
		document.myform.op.value=action;
		document.myform.submit();		
		return;
	}

	if(action=='save_comment'){
		document.myform.op.value=action;
		document.myform.submit();		
		return;
	}
}

</script>

</script>

</head>

<body >

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
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
		<a href="surah_stu_rep.php?uid=<?php echo $uid;?>&year=<?php echo $year;?>&sid=<?php echo $sid;?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/goback.png"><br><?php echo 'Kembali';?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="process_form('save')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br>Save</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
                <a href="#" onClick="document.myform.submit();"  id="mymenuitem" ><img src="<?php echo $MYLIB;?>/img/reload.png"><br>Refresh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>                   
		</div> <!-- end mymenu -->

		<div align="right"  >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
</div> <!-- end mypanel -->
<div id="story" style="min-height:none;">
<div id="mytitle2">Mata Pelajaran <?php echo $f;?></div>
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
        <td width="30%" valign="top"><?php echo strtoupper($lg_name);?></td>
        <td width="1%" valign="top">:</td>
        <td width="70%"><?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_matric);?></td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_ic_number);?></td>
        <td>:</td>
        <td><?php echo "$ic";?> </td>
      </tr>
       <tr>
        <td><?php echo strtoupper($lg_register_date);?></td>
        <td>:</td>
        <td><?php list($xy,$xm,$xd)=split('[-]',$rdate); echo "$xd-$xm-$xy";?> </td>
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
        <td width="20%" valign="top"><?php echo strtoupper($lg_school);?></td>
        <td width="1%" valign="top">:</td>
        <td width="79%"><?php echo strtoupper("$sname");?></td>
      </tr>
	  <tr>
        <td><?php echo strtoupper($lg_exam);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$examname");?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_class);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$cname");?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper($lg_year);?></td>
        <td>:</td>
        <td><?php echo $year;?> </td>
      </tr>
	  <?php if($CONFIG['SHOW_MENTOR']['val']){?>
	  <tr>
        <td>MENTOR</td>
        <td>:</td>
        <td><?php echo strtoupper("$mentorname");?> </td>
      </tr>
	  <?php } ?>
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


<table width="100%" cellspacing="0" cellpadding="2" style="font-size:11px">
	<tr>
    		<td class="mytableheader" style="border-right:none;" width="5%" align="center">No</td>
		    <td class="mytableheader" style="border-right:none;" width="50%" >Deskripsi</td>            
			<td class="mytableheader" style="border-right:none;" width="5%" align="center">Nilai</td>
	</tr>
<?php
 		$sql="select * from type where grp='hafaz_cate' order by idx";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
      	while($row=mysql_fetch_assoc($res)){
				$cate=$row['prm'];
				$cate=stripslashes($cate);
				$code_disable=$row['code'];
				$q++;
				$jj=0;
				
?>				
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
					<td id="mytabletitle" style="border-right:none;" align="center"><?php echo $q;?></td>
                    <td id="mytabletitle" style="border-right:none;"><?php echo $cate;?></td>
                    <td id="mytabletitle" style="border-right:none;">&nbsp;</td>
     </tr>
<?php
		if($code_disable){
		$kk=0;
		$sqlsurahstu="select distinct(surahname) from surah_stu_read where uid='$uid' and sid='$sid' and year='$year'";
		$ressurahstu=mysql_query($sqlsurahstu)or die("$sqlsurahstu query failed:".mysql_error());
		while($rowsurahstu=mysql_fetch_assoc($ressurahstu)){
			$surah=$rowsurahstu['surahname'];
			
			$sqlsurahno="select * from surah_stu_read where uid='$uid' and sid='$sid' and year='$year' and surahname='$surah'";
			$ressurahno=mysql_query($sqlsurahno)or die("$sqlsurahno query failed:".mysql_error());
			$rowsurahno=mysql_fetch_assoc($ressurahno);
			$surahno=$rowsurahno['surahno'];

			$jj++;
		
						$kk++;
						$totalitem=0;
						$bg="";

						$sqlmark="select * from hafaz_exam where sch_id='$sid' and stu_uid='$uid' and year='$year' and examtype='$exam' and sub_code='AQ|$surahno'";
						$resmark=mysql_query($sqlmark)or die("$sqlmark query failed:".mysql_error());
						$rowmark=mysql_fetch_assoc($resmark);
						$mark=$rowmark['point'];
						?>
                        <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                                    <td id="myborder" style="border-top:none;border-right:none;" align="right"></td>
                                    <td id="myborder" style="border-top:none;border-right:none;"><?php echo "$q-$jj-$kk";?>&nbsp;&nbsp;<?php echo "$surahno $surah";?></td>
                                    <td id="myborder" style="border-top:none;border-right:none;" align="center">
									<input type="hidden" name="sub_surah[]" value="<?php echo "AQ|$surahno";?>">
									<input type="text" name="markah_surah[]" size="3" value="<?php echo $mark;?>">
									</td>
									</tr>
<?php
				
		}
		}else{
		$sql="select distinct(type) from hafaz_construct where cate='$cate' and isdel=0 order by cate";
		$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
      	while($row2=mysql_fetch_assoc($res2)){
				$type=$row2['type'];
				$type=stripslashes($type);
				$jj++;
				$bg="#FAFAFA";
				if($type!=""){
?>				
            <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                            <td id="myborder" style="border-top:none;border-right:none;" align="right"><?php echo "$q-$jj";?></td>
                            <td id="myborder" style="border-top:none;border-right:none;"><?php echo $type;?></td>
                            <td id="myborder" style="border-top:none;border-right:none;">&nbsp;</td>
             </tr>
            <?php //show itemized item
		}
				$kk=0;
				$type1=addslashes($type);
				$sql="select * from hafaz_construct where type='$type1' and cate='$cate' and sid='$sid' and lvl='$lvl' and exam='$exam' and isdel=0 order by cate";
				$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
				while($row3=mysql_fetch_assoc($res3)){
						$xid=$row3['id'];
						$cc=$row3['code'];
						$vv=$row3['val'];
						$item=$row3['item'];
						$grading=$row3['grading'];
						$kk++;
						$totalitem=0;
						$bg="";

						$sqlmark2="select * from hafaz_exam where sch_id='$sid' and stu_uid='$uid' and year='$year' and examtype='$exam' and sub_code='BG|$cc'";
						$resmark2=mysql_query($sqlmark2)or die("$sqlmark2 query failed:".mysql_error());
						$rowmark2=mysql_fetch_assoc($resmark2);
						$mark2=$rowmark2['point'];
						?>
                        <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                                    <td id="myborder" style="border-top:none;border-right:none;" align="right"></td>
                                    <td id="myborder" style="border-top:none;border-right:none;"><?php echo "$q-$jj-$kk";?>&nbsp;&nbsp;<?php echo "$cc $item";?></td>
                                    <td id="myborder" style="border-top:none;border-right:none;" align="center">
									<input type="hidden" name="sub_baliigho[]" value="<?php echo "BG|$cc";?>">
									<input type="text" name="markah_baliigho[]" size="3" value="<?php echo $mark2;?>">
									</td>
                        </tr>
<?php }
			} //end if (code_disable?>
<?php } ?> 
<?php } ?>     
 </table>
 <div align="right"><input type="button" value="<?php echo $lg_update;?>" onclick="process_form('save');" style="width:200px"></div>
 <br>
<table width="100%" cellspacing="0" cellpadding="0" border="0"  style="font-size:11px">
<tr><td class="mytableheader" style="border-right:none;" colspan="2">&nbsp;Catatan</td></tr>
<tr>
<?php
			$sqlmsg="select * from hafaz_stu_summary where uid='$uid' and sid='$sid' and year='$year' and exam='$exam'";
			$resmsg=mysql_query($sqlmsg)or die("$sqlmsg query failed:".mysql_error());
			$rowmsg=mysql_fetch_assoc($resmsg);
			$hafaz_msg=$rowmsg['hafaz_msg'];

?>
<td id="myborder" width="82%" height="80" align="center">&nbsp;<textarea rows="4" cols="120" name="cttn"><?php echo $hafaz_msg;?></textarea></td>
<td id="myborder" width="18%" align="right">&nbsp;<input type="button" value="Perbaruan Catatan" onclick="process_form('save_comment')" style="width:200px"></td>
</tr>
</table>
</div><!-- story -->
</div><!-- content -->
</form>	
</body>
</html>
