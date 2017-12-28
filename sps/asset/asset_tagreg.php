<?php
//110610 - upgrade gui
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
ISACCESS("asset",1);
$adm=$_SESSION['username'];
$xid=$_REQUEST['xid'];
$item_tag=$_REQUEST['item_tag'];
$transid=$_REQUEST['transid'];
$op=$_REQUEST['op'];
		$uid=$_REQUEST['uid'];
		$sql="select * from usr where uid='$uid'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$name=strtoupper(stripslashes($row['name']));
		$job=ucwords(strtolower(stripslashes($row['job'])));
		$jobsta=ucwords(strtolower(stripslashes($row['jobsta'])));
		$status=$row['status'];
		$jobdiv=ucwords(strtolower(stripslashes($row['jobdiv'])));
			

			


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>

<script language="javascript">

function process_form(action){
	var ret="";
	var cflag=false;
	
	if(action=='save'){
		if(document.myform.item_tag.value==""){
			alert("Please enter tag no");
			document.myform.item_tag.focus();
			return;
		}
		ret = confirm("Are you sure want to SAVE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
	if(action=='delete'){
		ret = confirm("Are you sure want to DELETE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
}

</script>
</head>
<body >
<form name=myform method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input name="op" type="hidden" value="">
<div id="content">
	<div id="mypanel">
		<div id="mymenu" align="center">
       <!--
				<a href="#" onClick="showhide('itemlist')" id="mymenuitem"><img src="../img/new.png"><br>New</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="process_form('save')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
          -->
				<a href="#" onClick="window.print();" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();top.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
		<div align="right">
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
	</div> <!-- end mypanel-->
<div id="story">

<div id="mytitlebg">USER'S ITEM <?php echo $f;?></div>
<table width="100%" id="mytitlebg">		  	
            <tr>
              <td width="6%" ><?php echo $lg_name;?></td>
			  <td width="1%" >:</td>
              <td width="93%"><?php echo "$name";?></td>
            </tr>
			<tr>
              <td><?php echo "$lg_position";?></td>
			  <td>:</td>
              <td><?php echo "$job";?></td>
            </tr>
			<tr>
              <td><?php echo $lg_division;?></td>
			  <td>:</td>
              <td><?php echo "$jobdiv";?></td>
            </tr>
			<tr>
              <td><?php echo $lg_status;?></td>
			  <td>:</td>
              <td><?php echo "$jobsta";?></td>
            </tr>
</table>
<table width="100%" cellspacing="0" cellpadding="4">
	<tr>
         	<td class="mytableheader" style="border-right:none;" width="2%"  align="center"><?php echo strtoupper($lg_no);?></td>
			<td class="mytableheader" style="border-right:none;" width="10%">ITEM</td>
            <td class="mytableheader" style="border-right:none;" width="10%">TYPE</td>
            <td class="mytableheader" style="border-right:none;" width="10%">BRAND</td>
            <td class="mytableheader" style="border-right:none;" width="10%">TAG ID</td>
			<td class="mytableheader" style="border-right:none;" width="10%">DATE RECEIVED</td>
			<td class="mytableheader" style="border-right:none;" width="10%">DATE RETURNED</td>
	</tr>
	<?php	

	$sql="select * from asset where uid='$uid'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$xxid=$row['id'];
			$uid=$row['uid'];
			$category=$row['category'];
			$type=$row['type'];
			$brand=$row['item_brand'];
			$tag=$row['item_tag'];
			$date_out=$row['dt_out'];
			$date_in=$row['dt_in'];

			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default; font-size:12px" onMouseOver="this.bgColor='#CCCCFF';" 
    	onMouseOut="this.bgColor='<?php echo $bg?>';">
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$category";?></td>
                    <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$type";?></td>
                    <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$brand";?></td>
                    <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$tag";?></td>
					<td class="myborder" style="border-right:none; border-top:none;">
                    	<a href="#"><?php echo "$date_out";?></td>
					<td class="myborder" style="border-right:none; border-top:none;">
                    	<a href="#"><?php echo "$date_in";?></td>
	</tr>
<?php }  ?>
 </table>


</form>
</div></div>
</body>
</html>
