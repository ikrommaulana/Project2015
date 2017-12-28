<?php 
	$sql="select * from type where grp='openexam' and prm='EASRAMA'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['val'];
	if($sta!="1")
		echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";
		
	$ic=$_REQUEST['ic'];
	if(($ic!="")||($ic!=0)){
		$sql="select * from stu where ic='$ic'";
		$res=mysql_query($sql) or die(mysql_error());
		$num=mysql_num_rows($res);
		if($num>0)
			$xstatus=1; //jumpa
		else
			$xstatus=2;//takjumpa
	}	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
function process_form(op){
		if(document.myform.ic.value==""){
			alert("Sila masukkan Kad Pengenalan Pelajar");
			document.myform.ic.focus();
			return;
		}			
		document.myform.submit();
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>
<body>

<table width="100%"  border="0" id="mytitle">
  <tr>
    <td><img src="images/ereport.gif" width="120" height="45"></td>
  </tr>
</table>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onSubmit="process_form();return false;">
<input type="hidden" name="p" value="exam">
<table width="100%"  border="0" id="mytitle">
  <tr>
    <td>Masukkan Kad Pengenalan Pelajar 
      <input name="ic" type="text"><input name="button" type="button" value="Cari" onClick="process_form()">
	  <font size="1" color="#0000FF" style="font-weight:normal ">Eg: 760103075251</font></td>
  </tr>
</table>
</form>

<br>


<?php 
if($xstatus==1){
	include('examv.php');
} else if($xstatus==2) {
?>
<table width="100%"  border="0" id="mytitle">
  <tr>
    <td><font color="#FF0000" size="2">Maaf. KP pelajar '<?php echo $ic;?>' tidak ditemui.</font></td>
  </tr>
</table>
<?php } ?>
</body>
</html>
