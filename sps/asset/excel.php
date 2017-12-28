<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
header("Content-Type: application/octet-stream");

# replace excelfile.xls with whatever you want the filename to default to
header("Content-Disposition: attachment; filename=report.xls");
header ("Pragma: no-cache");
header("Expires: 0");
		
	$sql=$_REQUEST['sql'];
	$sql=stripslashes($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="3">
	<tr>
         	<td class="mytableheader" style="border-right:none;" width="2%"  align="center"><?php echo strtoupper($lg_no);?></td>
		    <td class="mytableheader" style="border-right:none;" width="5%">CATEGORY</td>
		    <td class="mytableheader" style="border-right:none;" width="5%">TYPE</td>
			<td class="mytableheader" style="border-right:none;" width="7%">TAG NO</td>
			<td class="mytableheader" style="border-right:none;" width="7%">BRAND</td>
        	<td class="mytableheader" style="border-right:none;" width="7%">MODEL</td>
			<td class="mytableheader" style="border-right:none;" width="7%">SYSTEM</td>
			<td class="mytableheader" style="border-right:none;" width="7%">ITEM TYPE</td>
			<td class="mytableheader" style="border-right:none;" width="7%">ITEM CATEGORY</td>          
			<td class="mytableheader" width="6%">LOCATION</td>
            <td class="mytableheader" width="10%">AREA</td>
            <td class="mytableheader" width="10%">STAFF</td>
            <td class="mytableheader" width="3%" align="center">VALUE</td>
	</tr>

<?php	
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
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$item_tag";?></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$item_brand";?></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$item_model";?></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$item_system";?></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$item_type";?></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$item_category";?></td>
                    <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$location-$blk-$lvl";?></td>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$area";?></td>
                    <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$uname";?></td>
                    <td class="myborder" style="border-right:none; border-top:none;" align="right"><?php echo number_format($rm,2,'.',',');?></td>
            </tr>

<?php }  ?>
 </table>         
</body>
</html>