<?php 
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

$xstate=$_REQUEST['xstate'];
if($xstate!="")
	$bstate=$xstate;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title></title>


</head>
<body>

<select name="daerah" id="daerah" >
                      <?php	
						if($daerah=="")
							echo "<option value=\"\">- Pilih -</option>";
						else
							echo "<option value=\"$daerah\">$daerah</option>";
						$sql="select * from daerah where name!='$daerah' and state='$bstate' order by name";
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['name'];
									echo "<option value=\"$s\">$s</option>";
						}
					?>
</select>  
 
</body>
</html>