<?php
$vdate="110626";
$vmod="v6.1.0";

include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
	$adm = $_SESSION['username'];
$dir_file ="../hwork/file_attach/";	

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
	
	$curryear=date('Y');
	if($curryear==$year)
		$sqlstatuspelajar="and stu.status=6";
		
		
$xedit=$_REQUEST['x'];
		
	
	if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql  failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $schname=$row['name'];
			$sname=$row['sname'];
			$stype=$row['level'];
			$level=$row['clevel'];
			$schlevel=$row['lvl'];
            mysql_free_result($res);	  
	}
	$cls=$_REQUEST['clscode'];
	if($cls!=""){
			$sql="select * from ses_cls where sch_id=$sid and cls_code='$cls'";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $lvl=$row['cls_level'];
			$clsname=stripslashes($row['cls_name']);
	}
	
	$sub=$_REQUEST['subcode'];
	$sql="select * from ses_sub where sub_code='$sub' and cls_level='$lvl' and sch_id=$sid and year='$year' and sch_id=$sid";
    $res=mysql_query($sql)or die("$sql failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$subname=stripslashes($row['sub_name']);
	
	$newbook=$_REQUEST['newbook'];
	$type=$_REQUEST['type'];
	$dt=$_REQUEST['dt'];
	if($dt=="")
		$dt=date('Y-m-d');
	$due=$_REQUEST['due'];
	if($due=="")
		$due=date('Y-m-d');
	$des=$_REQUEST['des'];
	$ms=$_REQUEST['ms'];
	$book=$_REQUEST['book'];
	$xid=$_REQUEST['xid'];
	$status=$_REQUEST['status'];
	$stulist=$_REQUEST['cblist'];
	
	$xxuid=$_REQUEST['xxuid'];
	$mark=$_REQUEST['mark'];
	
	$fn=basename( $_FILES['file']['name']);
		$ext=substr($fn,-4,4);
			$target_path =$dir_file.$fn;
	
	
	
	$op=$_REQUEST['op'];
	if($op=='savebook'){
		$sql="insert into hwork_book(dt,uid,sid,book,subcode,adm,ts)values(now(),'$adm','$sid','$newbook','$sub','$adm',now())";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$book=$newbook;
	}
//here
	if($op=='save'){
            if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		
		if($xid>0){
				
				
				$sql="update hwork set dt='$dt',due='$due',book='$book',des='$des',type='$type',ms='$ms',file='$fn' where id=$xid";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				
				
				for ($i=0; $i<count($stulist); $i++) {
						$xuid=$stulist[$i];
						$sql="update hwork_stu set sta='$status',submitdt=now() where uid='$xuid' and xid='$xid'";
						$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				}
				
				if($xedit=='x')
				{
						
				 //update mark
				   for($m=0;$m<count($xxuid);$m++){
				       $xeuid=$xxuid[$m];
				       $xmark=$mark[$m];
				         if($xmark==''){ $status='0'; }else{ $status='1';}
				       
				       $sql2="update hwork_stu set sta='$status',submitdt=now(),mark='$xmark' where uid='$xeuid' and xid='$xid'";
				       $res2=mysql_query($sql2)or die("$sql2 failed:".mysql_error());
				
				       
				    }
						
				}
				
				
		}else{
				$ss=addslashes($subname);
				$sql="insert into hwork(dt,due,cls,sub,subname,type,uid,sid,book,ms,des,year,adm,ts,file)
								values('$dt','$due','$cls','$sub','$ss','$type','$adm','$sid','$book','$ms','$des','$year','$adm',now(),'$fn')";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				$xid=mysql_insert_id($link);
				
				
				for ($i=0; $i<count($stulist); $i++) {
						$xuid=$stulist[$i];
						 $sql="insert into hwork_stu(dt,uid,sid,xid,adm,ts)values(now(),'$xuid','$sid','$xid','$adm',now())";
						$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				}
		}
	    }else{
		if($xid>0){
				
				
				$sql="update hwork set dt='$dt',due='$due',book='$book',des='$des',type='$type',ms='$ms' where id=$xid";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				
				
				for ($i=0; $i<count($stulist); $i++) {
						$xuid=$stulist[$i];
						$sql="update hwork_stu set sta='$status',submitdt=now() where uid='$xuid' and xid='$xid'";
						$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				}
				
				if($xedit=='x')
				{
						
				 //update mark
				   for($m=0;$m<count($xxuid);$m++){
				       $xeuid=$xxuid[$m];
				       $xmark=$mark[$m];
				         if($xmark==''){ $status='0'; }else{ $status='1';}
				       
				       $sql2="update hwork_stu set sta='$status',submitdt=now(),mark='$xmark' where uid='$xeuid' and xid='$xid'";
				       $res2=mysql_query($sql2)or die("$sql2 failed:".mysql_error());
				
				       
				    }
						
				}
				
				
		}else{
				$ss=addslashes($subname);
				$sql="insert into hwork(dt,due,cls,sub,subname,type,uid,sid,book,ms,des,year,adm,ts)
								values('$dt','$due','$cls','$sub','$ss','$type','$adm','$sid','$book','$ms','$des','$year','$adm',now())";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				$xid=mysql_insert_id($link);
				
				
				for ($i=0; $i<count($stulist); $i++) {
						$xuid=$stulist[$i];
						 $sql="insert into hwork_stu(dt,uid,sid,xid,adm,ts)values(now(),'$xuid','$sid','$xid','$adm',now())";
						$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				}
		}
	    }
		
		$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
		$xedit="";
	}
	
if($xid>0){
	$sql="select * from hwork where id=$xid";
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$year=$row['year'];
	$sub=$row['sub'];
	$cls=$row['cls'];
	$sid=$row['sid'];
	$type=$row['type'];
	$des=$row['des'];
	$book=$row['book'];
	$ms=$row['ms'];
	$xfile=$row['file'];
	if ($xfile!="")
         $filelink="<img src=\"../img/edit12.png\">&nbsp;$xfile";
	
	$sql="select * from ses_sub where sub_code='$sub' and cls_code='$cls' and sch_id=$sid and year='$year' and sch_id=$sid";
    $res=mysql_query($sql)or die("$sql failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$subname=stripslashes($row['sub_name']);
}
	
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="asc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by sex desc, stu.name";
	else
		$sqlsort="order by $sort $order, name asc";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<SCRIPT LANGUAGE="JavaScript">

function process_myform(op,xid){
	var cflag=false;
	if(document.myform.des.value==""){
    	alert("Please description");
        document.myform.des.focus();
        return;
	}
	if(xid==''){ //add new
		for (var i=0;i<document.myform.elements.length;i++){
					var e=document.myform.elements[i];
					if ((e.id=='cblist')){
							if(e.checked==true)
								   cflag=true;
		
					}
		}
		
		if(!cflag){
				alert('Please checked the student');
				return;
		}
	}
	if(document.myform.file.value!=""){
			fn=document.myform.file.value;
			//len=fn.length;
			//ext=fn.substr(len-4,4);
			var parts = fn.split('.');
			ext=parts[parts.length - 1];
			if((ext.toLowerCase()!="gif")&&(ext.toLowerCase()!="jpg")&&(ext.toLowerCase()!="pdf")&&(ext.toLowerCase()!="docx")&&(ext.toLowerCase()!="ppt")&&(ext.toLowerCase()!="pptx")){
				alert("Invalid file. Only GIF ,JPG , DOCX, PPT or PDF are allowed");
				document.myform.uploadedfile.focus();
				return;
			}
		}
	ret = confirm("Save the record ??");
    if (ret == true){
		document.myform.op.value=op;
        document.myform.submit();
    }
}
function process_edit(x){
	document.myform.x.value=x;
	document.myform.submit();
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php include("$MYOBJ/datepicker/dp.php")?>
<title>ISIS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>


<form name="myform" method="post"  enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
    <input type="hidden" name="op">
	<input name="year" type="hidden" value="<?php echo $year;?>">
	<input name="sid" type="hidden" value="<?php echo $sid;?>">
	<input name="clscode" type="hidden" value="<?php echo $cls;?>">
	<input name="subcode" type="hidden" value="<?php echo $sub;?>">
    <input name="xid" type="hidden" value="<?php echo $xid;?>">
    <input type="hidden" name="x" value="<?php echo $xedit?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
		
	


<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="<?php echo "../hwork/hwork.php?year=$year&sid=$sid&clscode=$cls&subcode=$sub";?>" id="mymenuitem"><img src="../img/goback.png"><br>Back</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>    
		<a href="#" onClick="process_myform('save','<?php echo $xid;?>')" id="mymenuitem"><img src="../img/save.png"><br><?php echo $lg_save;?></a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br><?php echo $lg_print;?></a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br><?php echo $lg_refresh;?></a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="../img/help22.png"><br><?php echo $lg_readme;?></a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.close(); parent.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div> 
	</div>
	<div align=right>
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>
<br>

	 
	</div>
</div>
<div id="story">

	<div id="mytitle2"><?php echo "TUGAS PELAJAR";?> : <?php echo $subname;?> <?php echo $f;?></div>
    
<table width="50%" id="mytitlebg" style="font-size:11px" cellpadding="2" cellspacing="0">
    <tr>
    	<td width="20%"><?php echo "Jenis Tugas";?></td>
        <td width="1%">:</td>
        <td>
					<select name="type">
								<?php 
                                    if($type!="")
										 echo "<option value=\"$type\">$type</option>";
									//else
                                    //	 echo "<option value=\"\">- $lg_select -</option>";
                                    $sql="select * from type where grp='assigment_type' and prm!='$type' order by prm";
                                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                                    while($row=mysql_fetch_assoc($res)){
                                                $item=$row['prm'];
                                                echo "<option value=\"$item\">$item</option>";
                                    }
                                ?>
                     </select>  
                     <?php if(is_verify("ADMIN")){?><input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=assigment_type',0)"><?php } ?>
        </td>
    </tr>
    <tr>
    	<td><?php echo $lg_description;?></td>
        <td>:</td>
        <td><input type="text" name="des" size="100" value="<?php echo $des;?>"></td>
    </tr>    
    <tr>
    	<td><?php echo $lg_date_give;?></td>
        <td>:</td>
        <td><input type="text" name="dt" size="30" readonly onClick="displayDatePicker('dt');" value="<?php echo $dt;?>"></td>
    </tr>
    <tr>
    	<td><?php echo $lg_date_hwork;?></td>
        <td>:</td>
        <td><input type="text" name="due" size="30" readonly onClick="displayDatePicker('due');" value="<?php echo $due;?>"></td>
    </tr>    
    <tr>
    	<td valign="top"><?php echo $lg_book;?></td>
        <td valign="top">:</td>
        <td valign="top">
        
        	<select name="book">
								<?php 
									if($book!="")
										 echo "<option value=\"$book\">$book</option>";
									else
                                    	echo "<option value=\"\">- $lg_select -</option>";
                                    $sql="select * from hwork_book where uid='$adm' and sid='$sid' and subcode='$sub' order by book";
                                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                                    while($row=mysql_fetch_assoc($res)){
                                                $item=$row['book'];
                                                echo "<option value=\"$item\">$item</option>";
                                    }
                                ?>
             </select>  
                     
        	<a href="#" onClick="showhide('create')">Create?</a>
            
            <div id="create" style="display:none;">
            	<?php echo $lg_book_name;?> : <input type="text" name="newbook" size="30">
                <input type="button" value="Save" OnClick="document.myform.op.value='savebook'; document.myform.submit()">
        	</div>    
        </td>
    </tr>
    <tr>
    	<td><?php echo "Halaman";?></td>
        <td>:</td>
        <td><input type="text" name="ms" size="3" value="<?php echo $ms;?>"></td>
    </tr>
    <tr>
    	<td><?php echo "Attachment";?></td>
        <td>:</td>
        <td><a href="<?php echo "$dir_file$xfile"; ?>"><?php echo $filelink; ?></a> <input type="file" name="file"></td>
    </tr>
    
    <?php if ($xid!="") {?>	
	
	<tr>
		<td><?php echo "Status";?></td>
		<td>:</td>
		<td><select name="status">
			<option value="">-Pilih Status-</option>
			<option value="1">Sdh Di Cek</option>
		</select>
		</td>
        </tr>
     <?php } ?>
	
    
<!--    
	<tr>
    	<td>Marks</td>
        <td>:</td>
        <td><input type="text" name="mark" size="30"></td>
    </tr>
-->    
	<tr>
    	<td colspan="3"><?php echo $lg_assign_to;?> : </td>
    </tr>

</table>



<table width="100%" cellspacing="0"style="font-size:10px">
<tr>
<?php //if($xid==""){?>
		<td class="mytableheader" style="border-right:none;" width="1%" class="printhidden"><input type=checkbox name=checkall value="0" onClick="checkbox_checkall(1,'cblist')"></td>
<?php //} ?>        
		<td id="mytabletitle" width="2%" align="center"><?php echo strtoupper($lg_no);?></td>
		<td id="mytabletitle" width="4%" align="center"><a href="#" onClick="formsort('stu.uid','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_matric);?></a></td>
		<td id="mytabletitle" width="4%" align="center"><a href="#" onClick="formsort('stu.sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>	
		<td id="mytabletitle" width="55%"><a href="#" onClick="formsort('stu.name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></strong></td>
		<td id="mytabletitle" width="15%" align="center">STATUS</td>
		<td id="mytabletitle" width="10%" align="center">TANGGAL DIKUMPULKAN</td>
		<td id="mytabletitle" width="20%" align="center"><a href="#" onClick="process_edit('x')" title='Edit'
		<div><img src="../img/edit12.png"></div>POIN</a></td>
		
</tr>

<?php
		$q=0;
		if($xid>0)
			$sql="select hwork_stu.*,stu.uid,stu.sex,stu.name,stu.status from hwork_stu INNER JOIN stu ON hwork_stu.uid=stu.uid where stu.sch_id=$sid and hwork_stu.xid='$xid' $sqlstatuspelajar $sqlsort";
		else
			$sql="select ses_stu.*,stu.uid,stu.sex,stu.name,stu.status from ses_stu INNER JOIN stu ON ses_stu.stu_uid=stu.uid where stu.sch_id=$sid and ses_stu.cls_code='$cls' and ses_stu.year='$year' $sqlstatuspelajar $sqlsort";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$stuname=strtoupper(stripslashes($row['name']));
			$uid=$row['uid'];
			$sex=$row['sex'];
			$sta=$row['sta'];
			$mark=$row['mark'];
			if($sta=='0'){$sta='Blm Di Cek';}else if($sta=='1'){$sta='Sdh Di Cek';}else($sta="");
			$senddt=$row['submitdt'];

			if($q++%2==0)
				$bg="";
			else
				$bg="#FAFAFA";
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
<?php //if($xid==""){?>        
			<td class="myborder" style="border-right:none; border-top:none;" align="center">
            	<input type="checkbox" name="cblist[]" id="cblist" value="<?php echo "$uid";?>" onClick="checkbox_checkall(0,'cblist')"></td>  
<?php //} ?>                      
    		<td id="myborder" align="center"><?php echo "$q";?></td>
			<td id="myborder" align="center"><?php echo "$uid";?></td>
			<td id="myborder" align="center"><?php echo $lg_sexmf[$sex];?></td>
			<td id="myborder"><?php echo "$stuname";?></td>
			<td id="myborder" align="center"><?php echo "$sta";?></td>
			<td id="myborder" align="center"><?php echo "$senddt";?></td>
			<td id="myborder" align="center">
			<?php
				if($xedit){
				    
					echo"<input type='text' align'center' size='5%' name='mark[]' value='$mark'>";
					echo"<input type='hidden' size='5%' name='xxuid[]' value='$uid'>"; 
				}else{
					echo "$mark";
				}
		        ?>
			</td>
			
        </tr>
        
<?php } ?>


</table>

</div> <!-- story -->
</div> <!-- content -->

</form>

</body>
</html>
