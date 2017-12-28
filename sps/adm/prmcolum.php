<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN');
$username = $_SESSION['username'];
$grp=$_REQUEST['grp'];	
$id=$_REQUEST['id'];
$op=$_REQUEST['op'];
$xcat=$_REQUEST['xcategory'];

if($op==""){
	if($id!=""){
		$sql="select * from type where id=$id";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$prm=$row['prm'];
		$val=$row['val'];
		$idx=$row['idx'];
		$des=$row['des'];
		$etc=$row['etc'];
		$xcat=$row['code'];
		$grp=$row['grp'];
		$lvl=$row['lvl'];
		$sid=$row['sid'];
	}
	
}
else{
	$del=$_POST['del'];
    $prm=addslashes($_POST['prm']);
	$val=$_POST['val'];
	if($val=="")
		$vak=0;
	$grp=$_POST['grp'];
	$des=$_POST['des'];
	$etc=$_POST['etc'];
	$idx=$_POST['idx'];
	$code=$_POST['code'];
	$idx=$_POST['idx'];
	if($idx=="")
		$idx=0;
	$lvl=$_POST['lvl'];
	if($lvl=="")
		$lvl=0;
	$sid=$_POST['sid'];
	if($sid=="")
		$sid=0;
	$id=$_POST['id'];
	$op=$_POST['op'];
	if($op=='delete'){	
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from type where id=$del[$i]";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
		$id="";
	}
	else{
		if($id!="")
			$sql="update type set prm='$prm',val=$val,idx=$idx,des='$des',etc='$etc',code='$code',lvl=$lvl,sid=$sid,adm='$username',ts=now() where id=$id";
		else
      		$sql="insert into type (prm,val,grp,idx,des,etc,code,lvl,sid,adm,ts) values ('$prm','$val','$grp','$idx','$des','$etc','$code',$lvl,$sid,'$username',now())";
		mysql_query($sql)or die("query failed:".mysql_error());
		$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
		$id="";
	}
}
/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=30;
		$sqlmaxline="limit $curr,$MAXLINE";
	}
	elseif($MAXLINE=="All"){
		$sqlmaxline="";
	}
	else{
		$sqlmaxline="limit $curr,$MAXLINE";
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
			$sqlsort="order by id $order";
	else
			$sqlsort="order by $sort $order,idx asc";


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="javascript">

function process_form(action){
	var ret="";
	var cflag=false;
	if(action=='new'){
		document.myform.prm.readOnly=false;
		document.myform.id.value="";
		document.myform.prm.value="";
		document.myform.val.value="";
		document.myform.idx.value="";
		document.myform.des.value="";
		//document.myform.code.value="";
		document.myform.lvl.value="";
		document.myform.etc.value="";
		document.myform.sid.value="";
		return;
	}
	if(action=='reset'){
		//document.myform.prm.readOnly=false;
		//document.myform.id.value="";
		//document.myform.prm.value="";
		document.myform.val.value="";
		document.myform.idx.value="";
		document.myform.des.value="";
		document.myform.code.value="";
		return;
	}
	if(action=='update'){
		if(document.myform.prm.value==""){
			alert("Sila masukkan sistem parameter");
			document.myform.prm.focus();
			return;
		}
		//ret = confirm("Are you sure want to SAVE??");
		//if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		//}
		return;
	}
	if(action=='delete'){
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
			alert('Please checked the item to delete');
			return;
		}
		ret = confirm("Are you sure want to DELETE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
}

</script>
<title>SPS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>
<body >
<form name=myform method="post" action="">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input name="grp" type="hidden" value="<?php echo $grp;?>">
	<input name="op" type="hidden" value="">
            
            
<div id="content">
<div id="mypanel">
        <div id="mymenu" align="center">
            <a href="#" onClick="process_form('new');show('panelform');" id="mymenuitem"><img src="../img/new.png"><br>New</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>            
            <a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>            
            <a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>            
            <a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>            
            <a href="#" onClick="hide('panelform');process_form('new');document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>            
            <a href="#" onClick="window.close();parent.$.fancybox.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>            
        </div>

        <div align="right">
        <select name="xcategory" onchange="document.myform.submit();">
        <?php	
                    if($xcat==""){
                        echo "<option value=\"\">- Show All -</option>";
                       }else{
                        echo "<option value='$xcat'>$xcat</option>";
                        }
                        
                        $sql="select * from type where grp='asset_cate' and prm!='$xcat' and val='1' order by idx";
                        $res=mysql_query($sql)or die("query failed:".mysql_error());
                        while($row=mysql_fetch_assoc($res)){
                                    $s=$row['prm'];
                                    
                                    echo "<option value='$s'>$s</option>";
        
                         }							  
                    
        ?>
        </select>

		</div>
</div> <!-- end mypanel-->
<div id="story">
<div id="panelform" style="display:<?php if($id=="") echo "none";else echo "block";?>">	
<div id="mytitle">
	<div id="myclick" onClick="hide('panelform');"><img src="../img/icon_minimize.gif" id="mycontrolicon">KONFIGURASI PARAMETER</div>&nbsp;
</div>
	<table width="100%"  border="0" id="mytable">
      <tr>
		
        <td width="12%"><?php if($grp!='sms_setting'){ echo "Parameter";}else{echo "SMS Username";}?></td>
        <td width="1%">:</td>
        <td width="87%"><input name="prm" type="text" id="prm" size="50"  maxlength=128 value="<?php echo $prm;?>"></td>
      </tr>
      <tr>
        <td><?php if($grp!='sms_setting'){ echo "Nilai";}else{echo "Aktif";}?></td>
        <td>:</td>
        <td><input name="val" type="text" id="val"  maxlength=32 value="<?php echo $val;?>" ></td>
      </tr>
	  <tr>
        <td><?php if($grp!='sms_setting'){ echo "Kode";}else{echo "SMS Password";}?></td>
        <td>:</td>
        <td><input name="code" type="text" id="code"   maxlength=32 value="<?php echo $xcat;?>" readonly></td>
      </tr>
	  <tr>
        <td>SID</td>
        <td>:</td>
        <td><input name="sid" type="text" id="sid"   maxlength=32 value="<?php echo $sid;?>"></td>
      </tr>
	  <tr>
        <td><?php if($grp!='sms_setting'){ echo "Level";}else{echo "Max Usage";}?></td>
        <td>:</td>
        <td><input name="lvl" type="text" id="lvl"   maxlength=32 value="<?php echo $lvl;?>"></td>
      </tr>
	   <tr>
        <td><?php if($grp!='sms_setting'){ echo "Etc";}else{echo "Keyword";}?></td>
        <td>:</td>
        <td><input name="etc" type="text" size="50" value="<?php echo $etc;?>"></td>
      </tr>
      <tr>
        <td>Description</td>
        <td>:</td>
        <td><input name="des" type="text"  size="50" value="<?php echo $des;?>" ></td>
      </tr>
      <tr>
        <td>Index </td>
        <td>:</td>
        <td><input name="idx" type="text" id="idx" size="5"  maxlength=3 value="<?php echo $idx;?>" ></td>
      </tr>
	  <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
</div>

	<div id="mytitlebg">PARAMETER SISTEM <?php echo $f;?></div>
	<table width="100%" cellspacing="0">
      <tr>
	    <td id="mytabletitle" width="2%" align="center">BIL</td>
      	<td id="mytabletitle" width="1%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
		<?php if($grp=='classlevel'){?>
		<td id="mytabletitle" width="20%"><a href="#" onClick="formsort('prm','<?php echo "$nextdirection";?>')" title="sort">PARAMETER</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('val','<?php echo "$nextdirection";?>')" title="sort">NILAI</a></td>
		<td id="mytabletitle" width="10%" align="center"><a href="#" onClick="formsort('code','<?php echo "$nextdirection";?>')" title="sort">KODE</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('sid','<?php echo "$nextdirection";?>')" title="sort">SID</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('lvl','<?php echo "$nextdirection";?>')" title="sort">LEVEL</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('idx','<?php echo "$nextdirection";?>')" title="sort">INDEX</a></td>
		<td id="mytabletitle" width="20%"><a href="#" onClick="formsort('etc','<?php echo "$nextdirection";?>')" title="sort">KUOTA</a></td>
		<td id="mytabletitle" width="45%"><a href="#" onClick="formsort('des','<?php echo "$nextdirection";?>')" title="sort">TARIKH CUT-OFF</a></td>
		<?php }else if($grp!='sms_setting'){?>
		<td id="mytabletitle" width="20%"><a href="#" onClick="formsort('prm','<?php echo "$nextdirection";?>')" title="sort">PARAMETER</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('val','<?php echo "$nextdirection";?>')" title="sort">NILAI</a></td>
		<td id="mytabletitle" width="10%" align="center"><a href="#" onClick="formsort('code','<?php echo "$nextdirection";?>')" title="sort">KODE</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('sid','<?php echo "$nextdirection";?>')" title="sort">SID</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('lvl','<?php echo "$nextdirection";?>')" title="sort">LEVEL</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('idx','<?php echo "$nextdirection";?>')" title="sort">INDEX</a></td>
		<td id="mytabletitle" width="20%"><a href="#" onClick="formsort('etc','<?php echo "$nextdirection";?>')" title="sort">ETC</a></td>
		<td id="mytabletitle" width="45%"><a href="#" onClick="formsort('des','<?php echo "$nextdirection";?>')" title="sort">KETERANGAN</a></td>
		<?php }else{?>
		<td id="mytabletitle" width="20%"><a href="#" onClick="formsort('prm','<?php echo "$nextdirection";?>')" title="sort">SMS USERNAME</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('val','<?php echo "$nextdirection";?>')" title="sort">AKTIF</a></td>
		<td id="mytabletitle" width="10%" align="center"><a href="#" onClick="formsort('code','<?php echo "$nextdirection";?>')" title="sort">SMS PASSWORD</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('sid','<?php echo "$nextdirection";?>')" title="sort">SID</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('lvl','<?php echo "$nextdirection";?>')" title="sort">MAX USAGE</a></td>
		<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('idx','<?php echo "$nextdirection";?>')" title="sort">USED</a></td>
		<td id="mytabletitle" width="20%"><a href="#" onClick="formsort('etc','<?php echo "$nextdirection";?>')" title="sort">KEYWORD</a></td>
		<td id="mytabletitle" width="45%"><a href="#" onClick="formsort('des','<?php echo "$nextdirection";?>')" title="sort">KETERANGAN</a></td>
		<?php }?>
		
      </tr>
<?php
/**
	$sql="select count(*) from type where grp='$grp' $sqlsid";
	$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
	$q=$curr;

	$sql="select * from type where grp='$grp'  $sqlsid $sqlsort $sqlmaxline";
**/	
	echo $sql="select * from type where grp='$grp' and code='$xcat'  $sqlsid $sqlsort";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$prm=stripslashes($row['prm']);
		$val=$row['val'];
		$xid=$row['id'];
		$idx=$row['idx'];
		$des=$row['des'];
		$etc=htmlspecialchars($row['etc']);
		$cod=$row['code'];
		$lvl=$row['lvl'];
		$sid=$row['sid'];
		if(($q++%2)==0)
			$bg="";
		else
			$bg="#FAFAFA";
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">

<?php
		echo "<td id=myborder align=\"center\">$q</td>";
		echo "<td id=myborder><input type=checkbox name=del[] value=\"$xid\" onClick=\"check(0)\"></td>";
		echo "<td id=myborder><a href=\"#\" onClick=\"document.myform.id.value=$xid;document.myform.submit();\" title=\"edit\" >$prm</a></td>";
		echo "<td id=myborder align=\"center\">$val</td>";
		echo "<td id=myborder align=\"center\">$cod</td>";
		echo "<td id=myborder align=\"center\">$sid</td>";
		echo "<td id=myborder align=\"center\">$lvl</td>";
		echo "<td id=myborder align=\"center\">$idx</td>";
		echo "<td id=myborder>$etc</td>";
		echo "<td id=myborder>$des</td>";
		echo "</tr>";
	}
?>
      </table>
<?php include("../inc/paging_sort_only.php");?>

</div></div>
</form>
</body>
</html>
