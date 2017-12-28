<?php
//110610 - upgrade gui
//120315 - upgrade delete and user
//130313 - upgrade gui
$vmod="v6.0.0";
$vdate="130313";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
ISACCESS("asset",1);
$adm=$_SESSION['username'];
$xid=$_REQUEST['xid'];
$op=$_REQUEST['op'];

if($op=="delete"){
		$sql="update asset set isdelete=1, deleteby='$adm', deletets=now() where id=$xid";
      	mysql_query($sql)or die("$sql query failed:".mysql_error());
		$xid="";
		$f="<font color=blue>&lt;SUCCESSFULY DELETE&gt;</font>";
}elseif($op=='deleteuser'){
		$xxid=$_POST['delid'];
		$sql="update asset_history set isdelete=1,deleteby='$adm',deletets=now() where id=$xxid";
		mysql_query($sql)or die($sql.mysql_error());
		$f="<font color=blue>&lt; Deleted &gt;</font>";
}elseif($op!=""){
		$xsid=$_REQUEST['xsid'];
		$category=$_REQUEST['category'];
		$type=$_REQUEST['type'];
		$item_id=$_REQUEST['item_id'];
		$item_tag=$_REQUEST['item_tag'];
		$item_brand=$_REQUEST['item_brand'];
		$item_model=$_REQUEST['item_model'];
		$item_system=$_REQUEST['item_system'];
		$item_type=$_REQUEST['item_type'];
		$item_category=$_REQUEST['item_category'];
		$item_code=$_REQUEST['item_code'];
		$loc_block=$_REQUEST['loc_block'];
		$loc_level=$_REQUEST['loc_level'];
		$loc_name=$_REQUEST['loc_name'];
		$date_purchase=$_REQUEST['date_purchase'];
		$date_install=$_REQUEST['date_install'];
		$date_expiry=$_REQUEST['date_expiry'];  
		$date_warranty=$_REQUEST['date_warranty'];
		$location=$_REQUEST['location'];
		$uid=$_REQUEST['uid'];
		$owner=$_REQUEST['owner'];
		$changeowner=$_REQUEST['changeowner'];
		$dtout=$_REQUEST['dt_out'];
		$dtin=$_REQUEST['dt_in'];
		$rm=$_REQUEST['rm'];
		if($rm=="")
			$rm=0;
		 
		$vid=$_REQUEST['vendor_id'];
		$sql="select * from vendor where uid='$vid'";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$vname=stripslashes($row['name']);
		
		if($xid!=""){
			if($op=='saveas'){
				$sql="insert into asset (type,category,item_id,item_code,item_brand,item_type,item_category,
					item_model,item_system,item_tag,vendor_id,vendor_name,date_purchase,date_install,date_expiry,
					date_warranty,sta,remark,loc_name,loc_block,loc_level,location,
					uid,dt_in,dt_out,rm,
					adm,ts) values 
					('$type','$category','$item_id','$item_code','$item_brand','$item_type','$item_category',
					 '$item_model','$item_system','$item_tag','$vid','$vname','$date_purchase','$date_install','$date_expiry',
					 '$date_warranty','$sta','$remark','$loc_name','$loc_block','$loc_level','$location',
					 '$uid','$dtin','$dtout',$rm,
					 '$adm',now())";
				mysql_query($sql)or die("$sql query failed:".mysql_error());
				$xid=mysql_insert_id();
				$f="<font color=blue>&lt;SUCCESSFULY SAVE&gt;</font>";
				
			}else{
					if($op=='savechange'){
						//$sql="select * into asset_history from asset where id=$xid";
						$sql="insert into asset_history select * from asset where id=$xid";
						mysql_query($sql)or die("$sql query failed:".mysql_error());
						
					}
					$sql="update asset set type='$type',category='$category',item_id='$item_id',item_code='$item_code'
							,item_brand='$item_brand',item_type='$item_type',item_category='$item_category',
							item_model='$item_model',item_system='$item_system',item_tag='$item_tag',
							vendor_id='$vid',vendor_name='$vname',date_purchase='$date_purchase',date_install='$date_install',
							date_expiry='$date_expiry',date_warranty='$date_warranty',sta='$sta',
							remark='$remark',loc_name='$loc_name',loc_block='$loc_block',loc_level='$loc_level',location='$location',
							uid='$uid',dt_out='$dtout',dt_in='$dtin',rm='$rm',
							adm='$username',ts=now() where id=$xid";
							mysql_query($sql)or die("$sql query failed:".mysql_error());
							$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
			}
		}else{
      		$sql="insert into asset (type,category,item_id,item_code,item_brand,item_type,item_category,
					item_model,item_system,item_tag,vendor_id,vendor_name,date_purchase,date_install,date_expiry,
					date_warranty,sta,remark,loc_name,loc_block,loc_level,location,
					uid,dt_in,dt_out,rm,
					adm,ts) values 
					('$type','$category','$item_id','$item_code','$item_brand','$item_type','$item_category',
					 '$item_model','$item_system','$item_tag','$vid','$vname','$date_purchase','$date_install','$date_expiry',
					 '$date_warranty','$sta','$remark','$loc_name','$loc_block','$loc_level','$location',
					 '$uid','$dtin','$dtout',$rm,
					 '$adm',now())";
				mysql_query($sql)or die("$sql query failed:".mysql_error());
				$xid=mysql_insert_id();
				$f="<font color=blue>&lt;SUCCESSFULY SAVE&gt;</font>";
		}
}
if($xid!=""){
			$sql="select * from asset where id=$xid";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
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
			$loc_block=$row['loc_block'];
			$loc_level=$row['loc_level'];
			$loc_name=$row['loc_name'];
			$location=$row['location'];
			$date_purchase=$row['date_purchase'];
			$date_install=$row['date_install'];
			$date_expiry=$row['date_expiry'];  
			$date_warranty=$row['date_warranty']; 
			$vid=$row['vendor_id']; 
			$vname=$row['vendor_name']; 
			
			$dtin=$row['dt_in']; 
			$dtout=$row['dt_out']; 
			$rm=$row['rm']; 
			
			
			$uid=$row['uid']; 
			$sql="select * from usr where uid='$uid'";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$staffname=stripslashes($row['name']);
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

function process_form(action){
	var ret="";
	var cflag=false;
	
	if(action=='save'){
		if(document.myform.item_tag.value==""){
			alert("Please enter tag no");
			document.myform.item_tag.focus();
			return;
		}
		
		if(document.myform.owner.value!=""){
			if(document.myform.owner.value!=document.myform.uid.value){
					ret = confirm("Do you want to assiged this to others person??");
					if (ret == true){
						//document.myform.changeowner.value='1';
						document.myform.op.value='savechange';
						document.myform.submit();
						return;
					}
					else{
						//document.myform.op.value=option;
						document.myform.submit();
						return;
					}
			}
			
		}
		

		ret = confirm("Are you sure want to SAVE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
	if(action=='saveas'){
		if(document.myform.item_tag.value==""){
			alert("Please enter tag no");
			document.myform.item_tag.focus();
			return;
		}
		
		ret = confirm("Are you sure want to CREATE A COPY??");
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


function process_change(){
	var ret="";
	var cflag=false;
	

	
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
function process_delete(op,id)
{
		ret = confirm("Delete this process??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.delid.value=id;
			document.myform.submit();
		}
}
</script>
<script type="text/javascript">
			$(function(){
	
					$('#dt_in,#dt_out,#date_purchase,#date_warranty,#date_install,#date_expiry').datepicker({dateFormat: 'yy-mm-dd'});
					$("#dt_in,#dt_out,#date_purchase,date_warranty,#date_install,#date_expiry" ).datepicker( "option", "showAnim", "show");
			});
</script>
<style type="text/css">
/*demo page css*/

			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: 2px; position: relative; padding: 0px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: 0 0px;}

</style>
<title>SPS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body >
<form name=myform method="post" action="">
	<input type="hidden" name="xid" value="<?php echo $xid;?>">
	<input name="op" type="hidden">
    <input name="delid" type="hidden">
<div id="content">
	<div id="mypanel">
		<div id="mymenu" align="center">
				<a href="#" onClick="document.myform.xid.value='';document.myform.submit()" id="mymenuitem"><img src="../img/new.png"><br>New</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="process_form('save')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>                        
				<a href="#" onClick="process_form('saveas')"id="mymenuitem"><img src="../img/letters.png"><br>SAVE AS</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
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
<div id="mytitlebg">ASSET INFORMATION <?php echo $f;?></div>
<table width="100%" style="font-size:12px">
	<tr>
		<td width="50%">
		
		
	<table width="100%" id="mytitle" cellspacing="0">
      <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
        <td width="20%">Category</td>
        <td width="79%">
		 <select name="category">
				<?php 
					if($category!="")
								echo "<option value=\"$category\">$category</option>";
					else
								echo "<option value=\"\">- $lg_select -</option>";
					$sql="select * from type where grp='asset_cate' and prm!='$category' order by prm";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$s=$row['prm'];
								echo "<option value=\"$s\">$s</option>";
					}
				?>
		</select>
        <?php if(is_verify('ADMIN')){?>
        <input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=asset_cate',0)">
        <?php } ?>
		</td>
      </tr>
	<tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
        <td>Type</td>
        <td>
		 <select name="type">
				<?php 
					if($type!="")
								echo "<option value=\"$type\">$type</option>";
					else
								echo "<option value=\"\">- $lg_select -</option>";
					$sql="select * from type where grp='asset_type' and prm!='$type' order by prm";
					$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$s=$row['prm'];
								echo "<option value=\"$s\">$s</option>";
					}
				?>
		</select>
        <?php if(is_verify('ADMIN')){?>
        <input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=asset_type',0)">
        <?php } ?>
		</td>
      </tr>
	  <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
        <td>Tag No</td>
        <td><input name="item_tag" type="text" size="38"  value="<?php echo $item_tag;?>"></td>
      </tr>
	  <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
        <td>Model</td>
        <td><input name="item_model" type="text" size="38" value="<?php echo $item_model;?>"></td>
      <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
	   <tr>
        <td>Brand</td>
        <td><input name="item_brand" type="text" size="38" value="<?php echo $item_brand;?>" ></td>
      </tr>
     <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
        <td>System</td>
        <td><input name="item_system" type="text" size="38" value="<?php echo $item_system;?>" ></td>
      </tr>
    </table>
		</td>
		<td width="50%" valign="top">
		
	<table width="100%" id="mytitle" cellspacing="0">
        
	  <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
        <td width="20%">Supplier/Contractor</td>
        <td width="79%">
		<select name="vendor_id">
				<?php 
					if($vid!="")
								echo "<option value=\"$vid\">$vid - $vname</option>";
					else
								echo "<option value=\"\">- $lg_select -</option>";
					$sql="select * from vendor where uid!='$vid' order by name";
					$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$s=$row['uid'];
								$v=stripslashes($row['name']);
								echo "<option value=\"$s\">$s - $v</option>";
					}
				?>
		</select></td>
      </tr>
	  <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
        <td>Purchase Date</td>
        <td><input name="date_purchase" type="text"  value="<?php echo $date_purchase;?>" id="date_purchase" readonly></td>
      </tr>
	  <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
        <td>Warranty Expiry</td>
        <td><input name="date_warranty" type="text" value="<?php echo $date_warranty;?>" id="date_warranty" readonly></td>
      </tr>
	  <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
        <td>Install Date</td>
        <td><input name="date_install" type="text" value="<?php echo $date_install;?>"id="date_install" readonly></td>
      </tr>
	  <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
        <td>Item Expiry</td>
        <td><input name="date_expiry" type="text" value="<?php echo $date_expiry;?>" id="date_expiry" readonly></td>
      </tr>
      <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
            <td>Item Cost (RM)</td>
            <td><input name="rm" type="text" value="<?php echo $rm;?>"></td>
      </tr>
    </table>
		
		</td>
	</tr>
</table>


<div id="mytitlebg">LOCATION</div>

	<table width="100%"  style="font-size:12px">
      <tr>
			<td width="33%" id="myborder" valign="top">
            	Location
                <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=building_name',0)">
        		<?php } ?>
        <br>
				<table width="100%" cellpadding="0" cellspacing="0">
				<?php 
						$sql="select * from type where grp='building_name' order by idx";
						$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									if($s==$location)
										$checked="checked";
									else
										$checked="";
					?>
                <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
                <td width="100%">
						<label style="cursor:pointer;">
                        	<input type="radio" name="location" value="<?php echo $s;?>" <?php echo $checked;?>> <?php echo $s;?>
                        </label>
                 </td></tr>
				<?php } ?>
                </table>
			</td>
			<td id="myborder"  width="33%" valign="top">Block
            <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=building_block',0)">
        		<?php } ?>
                <br>
				<table width="100%" cellpadding="0" cellspacing="0">
			<?php 
						$sql="select * from type where grp='building_block' order by idx";
						$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									if($s==$loc_block)
										$checked="checked";
									else
										$checked="";
					?>
                <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
                <td width="100%">
						<label style="cursor:pointer;">
                        	<input type="radio" name="loc_block" value="<?php echo $s;?>" <?php echo $checked;?>> <?php echo $s;?>
                        </label>
                 </td></tr>
				<?php } ?>
                </table>
					
			</td>
			<td id="myborder" width="33%" valign="top">Level
            <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=building_level',0)">
        		<?php } ?>
                
            	<table width="100%" cellpadding="0" cellspacing="0">
				<?php 
					$sql="select * from type where grp='building_level' order by idx";
					$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
									if($s==$loc_level)
										$checked="checked";
									else
										$checked="";
				?>
                <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
                <td width="100%">
						<label style="cursor:pointer;">
                        	<input type="radio" name="loc_level" value="<?php echo $s;?>" <?php echo $checked;?>> <?php echo $s;?>
                        </label>
                 </td></tr>
				<?php } ?>
                </table><br>
                Location/Area<br>
				<input name="loc_name" type="text" size="20"  value="<?php echo $loc_name;?>">
			</td>
    </table>
   

<div id="mytitlebg">STAFF TAGGING</div>
<input type="hidden" name="owner" value="<?php echo $uid;?>"
<input type="hidden" name="changeowner" value="0">
<table width="100%" id="mytabletitle" style="font-size:11px;">
                <tr>
                    <td width="10%">Assigned To</td>
                    <td width="1%">:</td>
                    <td width="20%">
                    				<select name="uid" onChange="document.myform.dt_out.value='';document.myform.dt_in.value='';">
                                        <?php 
											if($uid!="")
														echo "<option value=\"$uid\">$uid - $staffname</option>";
                                            echo "<option value=\"\">- $lg_select $lg_staff -</option>";
                                            $sql="select * from usr order by name";
                                            $res=mysql_query($sql)or die("query failed:".mysql_error());
                                            while($row=mysql_fetch_assoc($res)){
                                                        $s=$row['uid'];
                                                        $v=stripslashes($row['name']);
                                                        echo "<option value=\"$s\">$s - $v</option>";
                                            }
                                        ?>
                                    </select>
                    </td>
                    <td width="10%" align="right">Assign</td>
                    <td width="1%">:</td>
                    <td width="20%"><input type="text" name="dt_out" id="dt_out" readonly value="<?php echo $dtout;?>"> </td>
                    <td width="10%" align="right">Return</td>
                    <td width="1%">:</td>
                    <td width="20%"><input type="text" name="dt_in" id="dt_in" readonly value="<?php echo $dtin;?>"> </td>
                </tr>
                </table>
                            
<table width="100%" cellpadding="4" cellspacing="0" style="font-size:11px">
                <tr>
                    <td class="mytableheader" width="20%">History Records</td>
                    <td class="mytableheader" width="10%">Assigned</td>
                    <td class="mytableheader" width="10%">Returned</td>
                    <td class="mytableheader" width="1%" align="center">Action</td>
                </tr>
<?php
	$sql="select * from asset_history where item_tag='$item_tag'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$id=$row['id'];
			$uid=$row['uid'];
			$dtout=$row['dt_out'];
			$dtin=$row['dt_in'];
			$isdelete=$row['isdelete'];
			
			$sql="select * from usr where uid='$uid'";
            $res2=mysql_query($sql)or die("query failed:".mysql_error());
            $row2=mysql_fetch_assoc($res2);
            $uname=ucwords(strtolower(stripslashes($row2['name'])));
			$bg="";
			if($isdelete)
				$bg="$bglred";
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
                    <td id="myborder"><?php echo "$uid - $uname";?></td>
                    <td id="myborder"><?php echo $dtout;?></td>
                    <td id="myborder"><?php echo $dtin;?></td>
                                  <td id="myborder"align="center">
                	<?php if((is_verify('ADMIN'))||($uid==$adm)){
							if(!$isdelete){
					?>
                 	<a href="#" onClick="process_delete('deleteuser',<?php echo $id;?>)">Del</a>
					<?php }} ?>
              </td>
                </tr>
<?php }?>                
</table>




</form>
</div></div>
</body>
</html>
