<?php
	if($HEADER_NAME_USE_MASTER)
		$sid=0;
	
	$sql="select * from sch where id='$sid'";
    $xres=mysql_query($sql)or die("query failed:".mysql_error());
    $xrow=mysql_fetch_assoc($xres);
    $sname=$xrow['name'];
	$slevel=$xrow['level'];
	$saddr=$xrow['addr'];
	$saddr1=$xrow['addr1'];
	$xxs=$xrow['state'];
	$xxp=$xrow['poskod'];
	$xxd=$xrow['daerah'];
	$xxc=$xrow['country'];
	$stel=$xrow['tel'];
	$sfax=$xrow['fax'];
	$sweb=$xrow['url'];
	$simg=$xrow['img'];
	
?>
<table width="100%">
	<tr>
        <td width="10%">
    		<img src="<?php echo $simg;?>" style="height:80px">
        </td>    
		<td width="80%" style="font-size:11px; color:#666666; padding:5px;" valign="top">
        	<table width="100%" cellspacing="0">
            	<tr><td width="100#"><font style="font-size:16px;"><?php  echo $sname;?></font></td></tr>
                <tr><td width="100#"><?php echo "$saddr $saddr1";?></td></tr>
                <tr><td width="100#"><?php echo "$xxp $xxd $xxs $xxc";?></td></tr>
                <tr><td width="100#"><?php echo "Tel:$stel&nbsp;&nbsp;Fax:$sfax";?></td></tr>
                <tr><td width="100#"><?php echo "Http://$sweb";?></td></tr>
            </table>
        </td>
    </tr>
</table>

