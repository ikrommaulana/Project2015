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
$edit=$_REQUEST['edit'];
$grading=$_REQUEST['grading'];


if($selectcate!="")
			$sqlcate="and cate='$selectcate'";
			

$sid=$_REQUEST['sid'];
if($sid==""){
		$sid=$_SESSION['sid'];	
}
$sql="select * from sch where id='$sid'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$sname=stripslashes($row['sname']);
$sqlsid="and sid='$sid'";
		
$lvl=$_REQUEST['lvl'];
$smt=$_REQUEST['smt'];
$exam=$_REQUEST['exam'];
$sqlexam="and exam='$exam'";
$sql="select * from type where grp='exam' and code='$exam'";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$examname=$row['prm'];

if($sid!="0")
	$sqlsid="and sid='$sid'";
					
$op=$_REQUEST['op'];
if($op=="delete"){
		$sql="update sub_construct set isdel=1,delby='$adm',delts=now() where id='$edit'";
		mysql_query($sql)or die("query failed:".mysql_error());		
		$f="<font color=blue>&lt;RECORD DELETED&gt;</font>"; 
		$edit=0; 
}
if($op=="save"){
		$edit=$_REQUEST['edit'];
		$type=$_REQUEST['type'];
		$cate=$_REQUEST['cate'];
		$code=$_REQUEST['code'];
		$item=$_REQUEST['item'];
		$val=$_REQUEST['val'];
		if($val=="")
			$val=0;
		$des=$_REQUEST['des'];
		$rem=$_REQUEST['rem'];
		
		
		if($code==""){
				$sql="select count(*) from sub_construct";
				$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row=mysql_fetch_row($res);
				$c=$row[0]+1;
				$code=sprintf("%04d",$c);
		}
		
		if($edit>0){
			$sql="update sub_construct set sid='$sid',cate='$cate',grading='$grading',type='$type',code='$code',item='$item',exam='$exam',lvl='$lvl', smt='$smt',adm='$adm',ts=now() where id=$edit";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());		
		}else{
			$sql="insert into sub_construct (sid,cate,type,code,item,grading,exam,lvl,smt,adm,ts)values
				('$sid','$cate','$type','$code','$item','$grading','$exam','$lvl','$smt','$adm',now())";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$edit=mysql_insert_id($link);
		}
		$edit="";
		$selectcate=$cate;
		$f="<font color=blue>&lt;SUCCESSFULY SAVE&gt;</font>";		
}
if($edit>0){
			$sql="select * from sub_construct where id='$edit'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
  			$row=mysql_fetch_assoc($res);
			$xid=$row['id'];
			$sid=$row['sid'];
			$lvl=$row['lvl'];
			$cate=$row['cate'];
			$type=$row['type'];
			$code=$row['code'];
			$item=$row['item'];
			$exam=$row['exam'];
			$smt=$row['smt'];
			$rem=$row['rem'];
			$des=$row['des'];
			$grading=$row['grading'];
			
		$sql="select * from type where grp='ujian' and code='$exam'";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$examname=$row['prm'];

}

$sql="select * from sch where id='$sid'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$sname=stripslashes($row['sname']);



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
		if(document.myform.sid.value==""){
			alert("Pilih Sekolah");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.lvl.value==""){
			alert("Pilih kelas");
			document.myform.lvl.focus();
			return;
		}
		if(document.myform.item.value==""){
			alert("Masukkan Diskripsi");
			document.myform.item.focus();
			return;
		}
		if(document.myform.exam.value==""){
			alert("Pilih Peperiksaan");
			document.myform.exam.focus();
			return;
		}

		document.myform.op.value=action;
		document.myform.submit();		
		return;
	}
	if(action=='delete'){
		ret = confirm("Are you sure want to DELETE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.edit.value=xid;
			document.myform.submit();
		}
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
	<input type="hidden" name="edit" id="edit" value="<?php echo $edit;?>">

<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
                <a href="#" onClick="document.myform.edit.value='0';document.myform.item.value='';document.myform.code.value='';showhide('itemform')" id="mymenuitem" ><img src="<?php echo $MYLIB;?>/img/new.png"><br>New</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="process_form('save')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br>Save</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
                <a href="#" onClick="document.myform.edit.value='';document.myform.submit();"  id="mymenuitem" ><img src="<?php echo $MYLIB;?>/img/reload.png"><br>Refresh</a>
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

<div id="itemform" <?php if($edit==""){?>style="display:none;" <?php }?>>
				<table width="100%" id="mytitle" cellspacing="0" cellpadding="2px" style="font-size:11px">
                	
                	 <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td width="20%" id="myborderbottom">Category</td>
                        <td width="80%">
                        <select name="cate">
								<?php 
                                    if($cate!="")
                                                echo "<option value=\"$cate\">$cate</option>";
									else
                                                echo "<option value=\"\">- $lg_select -</option>";
                                    $sql="select * from type where grp='sub_cate' order by idx,prm";
                                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                                    while($row=mysql_fetch_assoc($res)){
                                                $s=$row['prm'];
                                                echo "<option value=\"$s\">$s</option>";
                                    }
                                ?>
                        </select>
                        <input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=sub_cate',0)">
                        </td>
				  	</tr>
                	 <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom">Group</td>
                        <td>
                        <select name="type">
								<?php 
                                    if($type!="")
                                                echo "<option value=\"$type\">$type</option>";
                                    else
                                                echo "<option value=\"\">- $lg_select -</option>";
                                    $sql="select * from type where grp='sub_type' and prm!='$type' order by prm";
                                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                                    while($row=mysql_fetch_assoc($res)){
                                                $s=$row['prm'];
                                                echo "<option value=\"$s\">$s</option>";
                                    }
                                ?>
                        </select>
                        <input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=sub_type',0)">
                        </td>
				  </tr>                 
				  <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom">Deskripsi*</td>
                        <td><input name="item" type="text" size="70"  value="<?php echo $item;?>"></td>
				  </tr>                   
				  <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom">Kode</td>
                        <td><input name="code" type="text" size="20" value="<?php echo $code;?>"> (Leave blank for auto code)</td>
				  </tr>   
				  <tr onMouseOver="this.bgColor='#F1F1F1'" onMouseOut="this.bgColor=''">
                        <td id="myborderbottom">Grading</td>
                        <td>
								<select name="grading">
						<?php
								if($grading!="")
                                                echo "<option value=\"$grading\">$grading</option>";
									else
                                                echo "<option value=\"\">- $lg_select -</option>";
								$sql="select * from type where prm!='$grading' and grp='grading' and code='EXAM' and (sid=0 or sid=$sid)";
								$res2=mysql_query($sql)or die("4 failed:".mysql_error());
								while($row2=mysql_fetch_assoc($res2)){
									$x=$row2['prm'];
									echo "<option value=\"$x\">$x</option>";
								}
						?>
							</select>
							  </td>
				  </tr>
		</table>
</div>

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
        <option value="">Pilih Ujian</option>
	<?php	
      		$sql="select * from type where grp='ujian'";
            	$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           	while($row=mysql_fetch_assoc($res)){
			$a=$row['prm'];
			$b=$row['code'];
			if($exam==$b){
				$select="selected";
			} else {
				$select="";
			}
			echo "<option value='$b' $select>$a</option>";
		}
	?>
</select>
<select name="smt" id="smt" onchange="document.myform.submit();">
	<option value="">Pilih Semester</option>
	<option value="1" <?php if($smt=='1') { echo "selected"; } ?>>Semester 1</option>
	<option value="2" <?php if($smt=='2') { echo "selected"; } ?>>Semester 2</option>
</select>  
</div> 


<table width="100%" cellspacing="0" cellpadding="2" style="font-size:11px">

	<tr>
    		<td class="mytableheader" style="border-right:none;" width="5%" align="center">No</td>
		    <td class="mytableheader" style="border-right:none;" width="50%" >Deskripsi</td>            
			<td class="mytableheader" style="border-right:none;" width="5%" align="center">Action</td>
	</tr>
<?php
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
     </tr>
<?php
		$sql="select distinct(prm) from sub_construct a left join type b on (a.type=b.prm) where cate='$cate' and b.grp='sub_type' and isdel=0 order by b.idx";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
      	while($row2=mysql_fetch_assoc($res2)){
				$type=$row2['prm'];
				$jj++;
				$bg="#FAFAFA";
?>				
            <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                            <td id="myborder" style="border-top:none;border-right:none;" align="right"></td>
                            <td id="myborder" style="border-top:none;border-right:none;"><?php echo $type;?> </td>
                            <td id="myborder" style="border-top:none;border-right:none;" align="center"><a href="#" onClick="newwindow('../adm/prm.php?grp=sub_type',0)"><img src="<?php echo $MYLIB;?>/img/edit12.png"></a></td>
             </tr>
            <?php //show itemized item
				$kk=0;
				$sql="select * from sub_construct where type='$type' and cate='$cate' and sid='$sid' and lvl='$lvl' and exam='$exam' and smt='$smt' and isdel=0 order by cate, code asc";
				$res3=mysql_query($sql)or die("query failed:".mysql_error());
				while($row3=mysql_fetch_assoc($res3)){
						$xid=$row3['id'];
						$cc=$row3['code'];
						$vv=$row3['val'];
						$item=$row3['item'];
						$kk++;
						$totalitem=0;
						$bg="";
						?>
                                <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                                    <td id="myborder" style="border-top:none;border-right:none;" align="right"></td>
                                    <td id="myborder" style="border-top:none;border-right:none;">&nbsp;&nbsp;<?php echo " $item";?></td>
                                    <td id="myborder" style="border-top:none;border-right:none;" align="center">
                                    		<a href="../adm/p.php?p=../sub/sub_construct_list&edit=<?php echo $xid;?>"><img src="<?php echo $MYLIB;?>/img/edit12.png"></a>&nbsp;
                    						<a href="#" onClick="process_form('delete',<?php echo $xid;?>)" title="Delete"><img src="../img/delete12.png"></a>
                                    </td>
                           </tr>
<?php } ?>
<?php } ?> 
<?php } ?>     
 </table>
 

</div><!-- story -->
</div><!-- content -->
</form>	
</body>
</html>
