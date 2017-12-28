<?php
//only root can edit
$vmod="v6.0.0";
$vdate="121207";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN');
$username = $_SESSION['username'];
	$op=$_REQUEST['op'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	if($sid!=0){
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$stype=$row['level'];
		$level=$row['clevel'];
        mysql_free_result($res);					  
	}
	else
		$level="Level";

$id=$_REQUEST['id'];		
if($id!=""){
			$sql="select * from cls where id='$id'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
  			$row=mysql_fetch_assoc($res);
			$id=$row['id'];
			$name=$row['name'];
			$code=$row['code'];
			$lvl=$row['level'];
			$year=$row['year'];
}
if($op!=""){
	$id=$_POST['id'];
	$clsname=$_POST['clsname'];
	$clslevel=$_POST['clslevel'];
	$code=$_POST['code'];
	$del=$_POST['del'];
	$year=$_POST['year'];

	if($op=="delete"){
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from cls where id=$del[$i]";
		      	mysql_query($sql)or die("$sql query failed:".mysql_error());
			}
		}
		$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
	}		
	else{
		$xname=addslashes($clsname);
		
		if($id>0) {
			$sql="update cls set name='$clsname',level='$clslevel',code='$code',year='$year' where id=$id";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		}
		else {
			$sql="select name from cls where name='$xname' and sch_id=$sid";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$num=mysql_num_rows($res);
			if($num>0){
				echo "Sorry. Class name '$clsname' already exist";
				exit;
			}
			$sql="select name from cls where code='$code' and sch_id=$sid";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$num=mysql_num_rows($res);
			if($num>0){
				echo "Sorry. Class code '$code' already exist";
				exit;
			}	
		$sql="insert into cls(cdate,name,level,sch_id,code,adm,year,ts) values (now(),'$xname','$clslevel',$sid,'$code','$username','$year',now())";
		mysql_query($sql)or die("$sql query failed:".mysql_error());
		}
		$id=mysql_insert_id();
		$f="<font color=blue>&lt;Successfully&gt;</font>";
	}

}
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by level $order";
	else
		$sqlsort="order by $sort $order, name";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include("$MYLIB/inc/myheader_setting.php");?>	
<script language="JavaScript">
<!--
function process_form(op){
	var ret="";
	var cflag=false;
	if(op=='update'){
		if(document.myform.sid.value=="0"){
				alert("<?php echo $lg_please_select;?> <?php echo $lg_school;?>");
				document.myform.sid.focus();
				return;
		}
		if(document.myform.clslevel.value==""){
				alert("<?php echo $lg_please_enter_the_information;?>");
				document.getElementById('clslevelerr').style.display = "inline";
				document.myform.clslevel.focus();
				return;
		}
		if(document.myform.clsname.value==""){
				alert("<?php echo $lg_please_enter_the_information;?>");
				document.getElementById('clsnameerr').style.display = "inline";
				document.myform.clsname.focus();
				return;
		}
		if(document.myform.code.value==""){
				alert("<?php echo $lg_please_enter_the_information;?>");
				document.getElementById('codeerr').style.display = "inline";
				document.myform.code.focus();
				return;
		}
		ret = confirm("<?php echo "Simpan data ?";?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}
	if(op=='delete'){
		for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.type=='checkbox')&&(e.name!='checkall')){
                        if(e.checked==true)
                                cflag=true;
                        else
                                allflag=false;
                }
        }
		if(!cflag){
			alert("<?php echo $lg_check_the_item;?>");
			return;
		}
		ret = confirm("<?php echo "Hapus data?";?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;
	}
}


//-->
</script>
</head>

<body>
<form id="myform" name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
	<input type="hidden" name="op">
	<input type="hidden" name="id" id="id" value="<?php echo $id;?>">
	<input type="hidden" name="xid">
<div id="panelleft"> 
	<?php include('inc/mymenu.php');?>
</div><!--end pageNav-->

<div id="content2">
<div id="mypanel">
	<div id="mymenu" align="center">
<?php if((is_verify('ROOT'))||($CLASS_EDIT_ENABLE)){?>
		<a href="javascript:show('panelform');" onClick="process_form('new')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/new.png"><br><?php echo $lg_new;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="process_form('update')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br><?php echo $lg_save;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="process_form('delete')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/delete.png"><br><?php echo $lg_delete;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<?php } ?>
	<a href="#" onClick="javascript: href='p.php?p=../cls/cls&sid=<?php echo $sid?>'" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
    	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div> <!-- end mymenu -->
	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		<select name="sid" onchange="document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
		//	else
                //echo "<option value=$sid>$sname</option>";
			//if($_SESSION['sid']==0){
				$sql="select * from sch where id>0 order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$s=stripcslashes($s);
							$t=$row['id'];
							if($t==$sid){$selected="selected";}else{$selected="";}
							echo "<option value=$t $selected>$s</option>";
				}
			//}					  
?>
  </select>
</div>
</div><!-- end of mypanel -->
<div id="story">
<div id="panelform" form" <?php if($id==""){?>style="display:none;" <?php }?>>
<div id="mytitlebg">
	<div id="myclick" onClick="hide('panelform');"><img src="<?php echo $MYLIB;?>/img/icon_minimize.gif" id="mycontrolicon"><?php echo $lg_configuration;?></div>
</div>
        
<table width="100%" id="mytitle">
		<tr>
              <td width="10%"><?php echo $lg_level;?></td>
			  <td width="1%">:</td>
              <td>
			<select name="clslevel" id="clslevel" >
<?php
	if($level==""){
		echo "<option value=''>Pilih Kelas</a>";
	} else {
		echo "<option value='$lvl'> $level $lvl </option>";
	}
			$sql="select * from type where grp='classlevel' and sid='$sid' order by prm";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=$s>$level $s</option>";
            }
?>
              </select>
			  <img src="<?php echo $MYLIB;?>/img/alert14.png" id="clslevelerr" style="display:none">
			  </td>
      		</tr>
			<tr>
              <td ><?php echo $lg_class;?></td>
			  <td >:</td>
              <td ><input name="clsname" type="text"  id="clsname" value="<?php echo $name; ?>">
                <img src="<?php echo $MYLIB;?>/img/alert14.png" id="clsnameerr" style="display:none">
              </td>
            </tr>
			<tr>
              <td><?php echo $lg_code;?></td>
			  <td>:</td>
              <td>
                <input name="code" type="text" id="code" size="6" value="<?php echo $code; ?>">
				<img src="<?php echo $MYLIB;?>/img/alert14.png" id="codeerr" style="display:none">
			  </td>
            </tr>
		<tr>
			<td>Tahun Ajaran</td>
			<td>:</td>
			<td><select name="year" id="year">
			<?php
			if($year!==""){
 			echo "<option value='$year'>$year</option>";
			} else {
			echo "<option value=''>Pilih Tahun</option>";
			}
			$sql="select * from type where grp='session' and prm!='$sesyear' order by val desc";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			while($row=mysql_fetch_assoc($res)){
				$s=$row['prm'];
				echo "<option value=\"$s\">$s</option>";
			}			  
			?>
			 </select></td>
		</tr>	
    </table>
</div>
<?php $sname=stripcslashes($sname);?>
<div id="mytitle2"><?php echo $lg_class;?> - <?php echo $sname;?> <?php echo $f;?></div>
 <table width="100%" cellspacing="0">
  <tr>
  	<td class="mytableheader" style="border-right:none;" align="center" width="5%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
    <td class="mytableheader" style="border-right:none;" align="center" width="5%"><?php echo strtoupper($lg_no);?></td>
    <td class="mytableheader" style="border-right:none;" align="center" width="15%"><a href="#" onClick="formsort('code','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_code);?></a></td>
	<td class="mytableheader" style="border-right:none;" width="15%" align="center"><a href="#" onClick="formsort('level','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($level);?></a></td>
	<td class="mytableheader" style="border-right:none;" width="40%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_class);?></a></td>
	<td class="mytableheader" style="border-right:none;" width="20%"><?php echo strtoupper($lg_year_session);?></a></td>
  </tr>


<?php 
	$sql="select * from cls where sch_id=$sid $sqlsort";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$id=$row['id'];
    	$name=stripslashes($row['name']);
		$code=$row['code'];
		$lvl=$row['level'];
		$year=$row['year'];
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>

 <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td class="myborder" style="border-right:none; border-top:none;" align="center"><input type=checkbox name=del[] value="<?php echo "$id";?>" onClick="check(0)"></td>
	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$code";?></td>
	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$lvl";?></td>
	<td class="myborder" style="border-right:none; border-top:none;"><a href="#" onClick="document.myform.id.value=<?php echo $id; ?>;document.myform.submit();" title="edit" ><?php echo "$name";?></a></td>
	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$year";?></td>
  </tr>
<?php } ?>
 </table> 
 <?php echo "Silahkan Hubungi Support Untuk Mengubah Data Ini";?>
</div></div>
</form>

</body>
</html>
