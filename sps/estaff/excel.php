<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
header("Content-Type: application/octet-stream");

# replace excelfile.xls with whatever you want the filename to default to
header("Content-Disposition: attachment; filename=stafflist.xls");
header ("Pragma: no-cache");
header("Expires: 0");
		
	$sql=$_REQUEST['sql'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table width='100%'>
<?php
$sql="select * from usr where id>0 $sqlsid $sqljobdiv $sqljob $sqljobsta $sqltamat $sqlsearch $sqlsort";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_row($res)){
		$q++;
?>
<tr>
<?php for($i=0;$i<count($row);$i++){?>
				<td ><?php echo ucwords(strtolower(stripslashes($row[$i])));?></td>
<?php }?>
</tr>
<?php }?>
</table>





?> 

</body>
</html>