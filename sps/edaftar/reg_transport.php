<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>
<body>
<table width="100%">
  <tr><td width="50%" id="myborder" valign="top">
		<div id="mytitlebg" style="background-color:#FFFF00 ">F. <?php echo strtoupper($lg_self_transport);?></div>
		<table width="100%"  cellspacing="0">
          	<tr>
              <td id=myborder width="30%">&nbsp;<?php echo $lg_sender_name;?></td>
              <td  width="70%"><input name="sendername" type="text" id="sendername" value="<?php echo $sendername;?>" size="38"></td>
  			</tr>
            <tr>
              <td id=myborder >&nbsp;&nbsp;-&nbsp;<?php echo $lg_vehicle_type;?></td>
              <td><input name="cartype" type="text" id="cartype" value="<?php echo $cartype;?>" size="38"></td>
            </tr>
            <tr>
              	<td id=myborder>&nbsp;&nbsp;-&nbsp;<?php echo $lg_vehicle_registration_no;?> </td>
				<td><input name="carno" type="text" id="carno" value="<?php echo $carno;?>" size="38"></td>
            </tr>
            <tr>
              	<td id=myborder>&nbsp;<?php echo $lg_collector_name;?> </td>
				<td><input name="collectorname" type="text" id="collectorname" value="<?php echo $collectorname;?>" size="38"></td>
            </tr>
			<tr>
              	<td id=myborder>&nbsp;&nbsp;-&nbsp;<?php echo $lg_vehicle_type;?> </td>
				<td><input name="cartype2" type="text" id="cartype2" value="<?php echo $cartype2;?>" size="38"></td>
            </tr>
			<tr>
              	<td id=myborder>&nbsp;&nbsp;-&nbsp;<?php echo $lg_vehicle_registration_no;?> </td>
				<td><input name="carno2" type="text" id="carno2" value="<?php echo $carno2;?>" size="38"></td>
            </tr>
</table>
	
	</td>
    <td width="50%" id="myborder" valign="top">
	
		<div id="mytitlebg" style="background-color:#FFFF00 ">G. <?php echo strtoupper($lg_school_transport);?></div>
        <table width="100%" cellspacing="0">
			<tr>
              <td id=myborder width="30%" colspan="2">
			  <input name="istransport" type="checkbox" value="1" <?php if($istransport)echo "checked";?>>
			  <?php echo $lg_i_want_to_use_transport_ficilities;?>
				<br>
				<input type="radio" name="twoway" value="<?php echo $lg_two_way;?>"> <?php echo $lg_two_way;?>
				<input type="radio" name="twoway" value="<?php echo $lg_fetch_only;?>"> <?php echo $lg_fetch_only;?>
				<input type="radio" name="twoway" value="<?php echo $lg_send_only;?>"> <?php echo $lg_send_only;?>
			  </td>
  			</tr>
          	<tr>
              <td width="30%" id="myborder">&nbsp;<?php echo $lg_fetch_from_address;?> </td>
              <td width="70%"><textarea name="faddr" cols="29" rows="3" id="faddr"><?php echo $faddr;?></textarea></td>
            </tr>
			<tr>
              <td width="30%" id="myborder">&nbsp;<?php echo $lg_send_to_address;?> </td>
              <td width="70%"><textarea name="saddr" cols="29" rows="3" id="saddr"><?php echo $saddr;?></textarea></td>
            </tr>
		</table>
	
	</td>
  </tr>
</table>




		  

		  
</body>
</html>
