<?php
//110610 - upgrade gui
//110610 - fixed staff view
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
ISACCESS("asset",1);
$adm=$_SESSION['username'];
$f=$_REQUEST['f'];
$p=$_REQUEST['p'];

		if($_SESSION['sid']>0)
			$sid=$_SESSION['sid'];
		else
			$sid=$_REQUEST['sid'];
	
		if($sid!="")
			$sqlsid=" and sch_id=$sid";
			
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- Tag, Brand, Model -")==0)
			$search="";
		if($search!="")
			$sqlsearch="and (item_tag='$search' or item_brand='$search' or item_model like '%$search%')";
	
		$uid=$_REQUEST['uid'];
		if($uid!=""){
			$sqluid="and uid='$uid'";
			$sql="select * from usr where uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$uidname=stripslashes($row['name']);
		}
			
		$vid=$_REQUEST['vid'];
		if($vid!=""){
			$sqlvid="and vendor_id='$vid'";
			$sql="select * from vendor where uid='$vid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$vidname=stripslashes($row['name']);
		}

		$isdelete=$_REQUEST['isdelete'];
		if($isdelete!=""){
			$sqldelete="and isdelete='1'";
		}else
			$sqldelete="and isdelete='0'";
		$location=$_REQUEST['location'];
		if($location!="")
			$sqllocation="and location='$location'";
			
		$blockname=$_REQUEST['blockname'];
		if($blockname!="")
			$sqlblock="and loc_block='$blockname'";
		
		$level=$_REQUEST['level'];
		if($level!="")
			$sqllevel="and loc_level='$level'";
			
		$category=$_REQUEST['category'];
		if($category!="")
			$sqlcategory="and category='$category'";
			
		$type=$_REQUEST['type'];
		if($type!="")
			$sqltype="and type='$type'";
		
		
		
		$tamat=$_REQUEST['tamat'];
		if($tamat=="")
			$tamat=0;
		$sqltamat="and status=$tamat";
		
		$viewlist=$_REQUEST['viewlist'];
			
		if($sid>0){
			$sql="select * from sch where id=$sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
            mysql_free_result($res);					  
		}
		
/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=25;
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
		$sqlsort="order by $sort $order, id desc";
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>

<script language="JavaScript">
function set_check(sta){
		for (var i=0;i<document.myform.elements.length;i++){
			var e=document.myform.elements[i];
			if ((e.type=='checkbox')&&(e.id=='viewlist'))
				e.checked=sta;
		}
}

</script>

</head>

<body >

 <form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
 <input type="hidden" name="p" value="<?php echo $p;?>">
 

<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
				<a href="../asset/asset_reg.php" target="_blank" class="fbbig" target="_blank" id="mymenuitem" >
                	<img src="<?php echo $MYLIB;?>/img/new.png"><br>New</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br>Print</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br>Refesh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php
                $sql="select * from asset where id>0 $sqluid $sqlvid $sqldelete $sqlstatus $sqlcategory $sqltype $sqlblock $sqllevel $sqllocation $sqlsearch $sqlsort";
                ?>                        
				<a href="../asset/excel.php?sql=<?php echo $sql;?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/excel.png"><br>Export</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="showhide('listview','');hide('mel');hide('sms');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/listview22.png"><br>Options</a>
                		<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		</div> <!-- end mymenu -->

		<div align="right"  >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
</div> <!-- end mypanel -->
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">

		<select name="type"  onChange="document.myform.submit();">
<?php
      		if($type=="")
            	echo "<option value=\"\">- $lg_all $lg_type -</option>";
			else
                echo "<option value=\"$type\">$type</option>";
			$sql="select distinct(type) from asset";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_row($res)){
            	$x=$row[0];
                echo "<option value=\"$x\">$x</option>";
            }
           echo "<option value=\"\">- $lg_all $lg_type -</option>";
?>
		</select>
			<select name="location"  onChange="document.myform.submit();">
<?php
      		if($location=="")
            	echo "<option value=\"\">- Location -</option>";
			else
                echo "<option value=\"$location\">$location</option>";
			$sql="select * from type where grp='building_name'order by idx";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=$row['prm'];
                echo "<option value=\"$x\">$x</option>";
            }
			if($location!="")
           		echo "<option value=\"\">- $lg_all -</option>";
?>
		</select>
		<select name="blockname"  onChange="document.myform.submit();">
<?php
      		if($blockname=="")
            	echo "<option value=\"\">- $lg_block -</option>";
			else
                echo "<option value=\"$blockname\">$blockname</option>";
			$sql="select distinct(loc_block) from asset";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_row($res)){
            	$x=$row[0];
                echo "<option value=\"$x\">$lg_block $x</option>";
            }
			if($blockname!="")
           		echo "<option value=\"\">- $lg_all -</option>";
?>
		</select>  	
		 <select name="level"  onChange="document.myform.submit();">
<?php
      		if($level=="")
            	echo "<option value=\"\">- Level -</option>";
			else
                echo "<option value=\"$level\">$level</option>";
				$sql="select * from type where grp='building_level' order by idx";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
                echo "<option value=\"$s\">$lg_block $s</option>";
            }
           echo "<option value=\"\">- $lg_all -</option>";
?>
		</select>
      

            <select name="uid" onChange="document.myform.submit();">
				<?php 
					if($uid==""){
							echo "<option value=\"\">- $lg_staff -</option>";
					}else{
							echo "<option value=\"$uid\">$uidname</option>";
							echo "<option value=\"\">- $lg_all -</option>";
					}
					$sql="select * from usr where uid!='$uid' order by name";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
							$s=$row['uid'];
							$v=stripslashes($row['name']);
							echo "<option value=\"$s\">$v</option>";
					}
				
							
				?>
           </select>

            <select name="vid" onChange="document.myform.submit();">
				<?php 
					if($vid==""){
							echo "<option value=\"\">- $lg_select $lg_vendor -</option>";
					}else{
							echo "<option value=\"$vid\">$vidname</option>";
							echo "<option value=\"\">- $lg_all -</option>";
					}
					$sql="select * from vendor where uid!='$vid' order by name";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
							$s=$row['uid'];
							$v=stripslashes($row['name']);
							echo "<option value=\"$s\">$v</option>";
					}
							
				?>
           </select>
		
        
        			<input name="search" type="text"  id="search" size="25" <?php if($search==""){?>onClick="document.myform.search.value='';"<?php } ?>
					value="<?php if($search=="") echo "- Tag, Brand, Model -"; else echo "$search";?>">                
				
				<div style="display:inline; margin:0px 0px 0px -17px; padding:2px 2px 1px 1px; cursor:pointer" onClick="document.myform.search.value='';document.myform.search.focus();" 
					onMouseOver="showhide2('img6','img5');" onMouseOut="showhide2('img5','img6');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px" id="img5">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img6">
				</div>
				<input type="submit" name="Submit" value="Search"><br>
				<label style="cursor:pointer"> <input type="checkbox" name="isdelete" value="1" onClick="document.myform.submit();" <?php if($isdelete=="1") echo "checked";?> >SHOW DELETE</label>
			
				

</div>
<div id="story">


<?php if($search!=""){?><div id="mytitlebg" style="color:#0066FF; font-size:12px">SEARCH FOR : '<?php echo $search;?>'</div><?php }?>

<div id="mytitle2"><?php echo "$lg_asset_list";?></div>

<div id="listview"  style="display:none">
<div id="mytitlebg">
	<a href="#" title="Close" onClick="hide('listview');">&nbsp;VIEW OPTION</a> &raquo;
	&nbsp;&nbsp;&nbsp;
	<a href="#" onClick="set_check(1)"> CHECK ALL</a> | <a href="#" onClick="set_check(0)"> CLEAR ALL</a>
	&nbsp;&nbsp;&nbsp; &raquo; &nbsp;&nbsp;&nbsp;
	<a href="#" onClick="document.myform.submit()" title="Click to View">VIEW RECORD</a>
</div>
<table width="100%" id="mytitlebg">
<tr><td valign="top" width="20%">
<table width="100%" border="0" >
	  <tr>
			<td colspan="2"  id="mytabletitle"><?php echo "$lg_information";?></td>
	  </tr>
	  <tr>
	  		<td width="1%"><input type="checkbox" name=viewlist[] id=viewlist value="Category|Category" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="Category|Category"){ echo "checked"; break;}}?>>
			<td>Category</td>
	  </tr>
	  <tr>
	  		<td width="1%"><input type="checkbox" name=viewlist[] id=viewlist value="Type|Type" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="Type|Type"){ echo "checked"; break;}}?>>
			<td>Type</td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="item_tag|Item Tag" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="item_tag|Item Tag"){ echo "checked"; break;}}?>>
	  		<td>Item Tag</td>
	  </tr>
	   <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="item_type|Item Type" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="item_type|Item Type"){ echo "checked"; break;}}?>>
	  		<td>Item Type</td>
	  </tr>
	   <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="item_category|Item Category" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="item_category|Item Category"){ echo "checked"; break;}}?>>
	  		<td>Item Category</td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="item_model|Item Model" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="item_model|Item Model"){ echo "checked"; break;}}?>>
	  		<td>Item Model</td>
	  </tr>
	   <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="item_brand|Item Brand" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="item_brand|Item Brand"){ echo "checked"; break;}}?>>
	  		<td>Item Brand</td>
	  </tr>
	   <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="item_system|Item System" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="item_system|Item System"){ echo "checked"; break;}}?>>
	  		<td>Item System</td>
	  </tr>
	 
</table>
</td>
<td valign="top" width="20%">

<table width="100%" border="0" >
	  <tr>
			<td colspan="2" id="mytabletitle"><?php echo "$lg_information";?></td>
	  </tr>
	   <tr>
	  		<td width="1%"><input type="checkbox" name=viewlist[] id=viewlist value="vendor_name|Vendor/Contractor" 
				<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="vendor_id|Vendor/Contractor"){ echo "checked"; break;}}?>>
			<td>Vendor/Contractor</td>
	  </tr> 
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="date_purchase|Date Purchase" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="date_purchase|Date Purchase"){ echo "checked"; break;}}?>>
			<td>Date Purchase</td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="date_warranty|Date Warranty" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="date_warranty|Date Warranty"){ echo "checked"; break;}}?>>
			<td>Date Warranty</td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="date_install|Date Install" 
				<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="date_install|Date Install"){ echo "checked"; break;}}?>>
			<td>Date Install</td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="date_expiry|Date Expiry" 
				<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="date_expiry|Date Expiry"){ echo "checked"; break;}}?>>
			<td>Date Expiry</td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="loc_block|Block Name" 
				<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="loc_block|Block Name"){ echo "checked"; break;}}?>>
			<td>Block Name</td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="loc_level|Block Level" 
				<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="loc_level|Block Level"){ echo "checked"; break;}}?>>
			<td>Block Level</td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="loc_name|Location" 
				<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="loc_name|Location"){ echo "checked"; break;}}?>>
			<td>Location</td>
	  </tr>
</table>

</td>



<td valign="top" width="20%">

<table width="100%" border="0" >
	  <tr>
			<td colspan="2" id="mytabletitle"><?php echo "$lg_information";?></td>
	  </tr>
	   <tr>
	  		<td width="1%"><input type="checkbox" name=viewlist[] id=viewlist value="Remark|Remark" 
				<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="Remark|Remark"){ echo "checked"; break;}}?>>
			<td>Remark</td>
	  </tr> 
</table>

</td>

<td width="40">&nbsp;</td>

</tr></table>

</div><!-- list view -->


<?php if(count($viewlist)<1){?>
<table width="100%" cellspacing="0" cellpadding="3">
	<tr>
         	<td class="mytableheader" style="border-right:none;" width="2%"  align="center"><?php echo strtoupper($lg_no);?></td>
		    <td class="mytableheader" style="border-right:none;" width="5%"><a href="#" onClick="formsort('category','<?php echo "$nextdirection";?>')" title="Sort">CATEGORY</a></td>
		    <td class="mytableheader" style="border-right:none;" width="5%"><a href="#" onClick="formsort('type','<?php echo "$nextdirection";?>')" title="Sort">TYPE</a></td>
			<td class="mytableheader" style="border-right:none;" width="7%"><a href="#" onClick="formsort('item_tag','<?php echo "$nextdirection";?>')" title="Sort">TAG NO</a></td>
			<td class="mytableheader" style="border-right:none;" width="7%"><a href="#" onClick="formsort('item_brand','<?php echo "$nextdirection";?>')" title="Sort">BRAND</a></td>
        	<td class="mytableheader" style="border-right:none;" width="7%"><a href="#" onClick="formsort('item_model','<?php echo "$nextdirection";?>')" title="Sort">MODEL</a></td>
            <td class="mytableheader" style="border-right:none;" width="10%">LOCATION</td>
            <td class="mytableheader" style="border-right:none;" width="10%">AREA</td>
            <td class="mytableheader" style="border-right:none;" width="10%">STAFF</td>
            <td class="mytableheader" style="border-right:none;" width="3%" align="center">VALUE</td>
	</tr>
<?php	
		$sql="select count(*) from asset where id>0 $sqluid $sqlvid $sqldelete $sqlstatus $sqlcategory $sqltype $sqlblock $sqllevel $sqllocation $sqlsearch";
        $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
        $row=mysql_fetch_row($res);
        $total=$row[0];
		if(($curr+$MAXLINE)<=$total)
			 $last=$curr+$MAXLINE;
		else
			$last=$total;
	$q=$curr;
	$sql="select * from asset where id>0 $sqluid $sqlvid $sqldelete $sqlstatus $sqlcategory $sqltype $sqlblock $sqllevel $sqllocation $sqlsearch $sqlsort $sqlmaxline";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$xid=$row['id'];
			$category=$row['category'];
			$type=$row['type'];
			$item_id=$row['item_id'];
			$item_tag=$row['item_tag'];
			$item_brand=$row['item_brand'];
			$item_model=$row['item_model'];
			$item_system=$row['item_system'];
			$item_type=$row['item_type'];
			$item_category=$row['item_category'];
			$item_code=$row['item_code'];
			$blok=$row['loc_block'];
			$level=$row['loc_level'];
			$uid=$row['uid'];
			$area=$row['loc_name'];
			$blk=$row['loc_block'];
			$lvl=$row['loc_level'];
			$rm=$row['rm'];
			$totalrm=$totalrm+$rm;
			$location=$row['location'];
			$isdelete=$row['isdelete'];
			
			
			$sql="select * from usr where uid='$uid'";
            $res2=mysql_query($sql)or die("query failed:".mysql_error());
            $row2=mysql_fetch_assoc($res2);
            $uname=ucwords(strtolower(stripslashes($row2['name'])));
								 
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
			if($isdelete)
				$bg=$bglred;
?>
			<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$category";?></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$type";?></td>
					<td class="myborder" style="border-right:none; border-top:none;">
                    	<a href="../asset/asset_reg.php?xid=<?php echo $xid;?>" target="_blank" class="fbbig">
							<?php echo "$item_tag";?></a></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$item_brand";?></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$item_model";?></td>
                    <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$location-$blk-$lvl";?></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$area";?></td>
                    <td class="myborder" style="border-right:none; border-top:none;">
					<a href="../asset/asset_tagreg.php?uid=<?php echo $uid;?>" target="_blank" class="fbbig">
						<?php echo "$uname";?></a>
					</td>
                    <td class="myborder" style="border-right:none; border-top:none;" align="right"><?php echo number_format($rm,2,'.',',');?></td>
            </tr>

<?php }  ?>
 </table>
<div id="mytitle2" align="right"> Total Value (RM): <?php echo number_format($totalrm,2,'.',',');?></div>
 <?php include("../inc/paging.php");?>
 
 
<?php }else{  ?>
<table width="100%" cellspacing="0" cellpadding="2">
	<tr>
         	<td class="mytableheader" style="border-right:none;" width="2%"  align="center"><?php echo $lg_no;?></td>
<?php
		for($i=0;$i<count($viewlist);$i++){
				$dat=explode("|",$viewlist[$i]);
				if($sqlfield!="")
					$sqlfield=$sqlfield.",";
				$sqlfield=$sqlfield.$dat[0];
?>
			  <td class="mytableheader" style="border-right:none;" width="5%"><a href="#" onClick="formsort('<?php echo $dat[0];?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $dat[1];?></a></td>
<?php } ?>
	<?php	
	$q=0;
	$sql="select count(*) from asset where id>0 $sqlstatus $sqlcategory $sqltype $sqlblock $sqlsearch";
        $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
        $row=mysql_fetch_row($res);
        $total=$row[0];
		if(($curr+$MAXLINE)<=$total)
			 $last=$curr+$MAXLINE;
		else
			$last=$total;
	$q=$curr;
	$sql="select id,$sqlfield,vendor_id from asset where id>0 $sqlstatus $sqlcategory $sqltype $sqlblock $sqlsearch $sqlsort $sqlmaxline";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_row($res)){
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
				
?>
			<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
									
<?php 
$xid=$row[0];
for($i=1;$i<=count($viewlist);$i++){
			$str=ucwords(strtolower(stripslashes($row[$i])));
			if($str=="")
				$str="&nbsp; - ";
			if($i==1)
				$str="<a href=\"asset_reg.php?xid=$xid\" target=\"_blank\" class=\"fbbig\">$str</a>";
?>
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo $str;?></td>
<?php }?>
            </tr>

<?php }  ?>
	</tr>
</table>
 <?php include("../inc/paging.php");?>
<?php }  ?>

</div><!-- story -->
</div><!-- content -->
</form>	
</body>
</html>
