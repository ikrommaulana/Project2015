<?php
//STANDARD EDITION
//120117 - ADD REG_FILE
$vmod="v6.0.1";
$vdate="120117";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php");

ISACCESS("eregister",1);
$sid=$_GET['sid'];
	if($sid==0){
		$sqlsid="";		
	} else {
		$sqlsid="and sch_id='$sid'";
	}
	
	$year=$_GET['year'];
	if($year!="") {
		$sqlyear="and sesyear='$year'";
	} else{
		$sqlyear="";	
	}
	
	
	$op=$_REQUEST['op'];
	if($op=='save'){
		$jum=$_POST['jum'];
		for($i=1; $i<=$jum; $i++)
		{
			$id=$_POST['id'.$i];
			$uid=$_POST['uid'.$i];
			$cek=mysql_query("SELECT * FROM stureg WHERE uid='$uid'");
			$read=mysql_num_rows($cek);
			if($read==0) {
				$cek2=mysql_query("SELECT * FROM stu WHERE uid='$uid'");
				$read2=mysql_num_rows($cek2);
				if($read2==0) {
					$simpan=mysql_query("UPDATE stureg SET uid='$uid' WHERE id='$id'") or die(mysql_error());
					if($simpan){
					$a= "Data telah tersimpan";}
					else {
					$a= "Data gagal disimpan"; }
				} else {
					$a="Maaf, NIS tersebut telah ada";
				}	
			} else {
				$a="Maaf, NIS tersebut telah ada";
			}
		}
	}			
	
	$order=$_POST['order'];	
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	//echo "$sort";
	if($sort=="")
		$sqlsort="order by id $order";
	else
		$sqlsort="order by $sort";
		//$sqlsort="order by $sort $order, name asc";


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

<script language="JavaScript">
var newwin = "";
function process_form(op)
{
	if (op=='save')
	{
		var ret="";
		var cflag=false;
		
		ret = confirm("Are you sure want to save??");
		if (ret == true)
		{
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;
	}
}
</script>
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"  enctype="multipart/form-data">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="op">
	<input type="hidden" name="xcurr" value="<?php echo $curr;?>">
	<?php $sql="select * from stureg where id>0 and isdel=0 $sqlyear $sqlsid $sqladminregonly $sqlstatus $sqlclssession $sqlclslevel $sqlsearch $sqlsort";?>
	<input type="hidden" name="sql" value="<?php echo $sql;?>">
<div id="content">
<div id="mypanel">

<div id="mymenu" align="center">
<a href="#" onClick="process_form('save');"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>

   	<div align="right">
    	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		
		
	</div>
    
</div><!-- end mypanel-->
<div id="story">


</div>

<div id="mytitlebg">PROSES PEMBERIAN NIS<br>

</div>
<?php echo "<h2> <left><font color=#ebaf17> $a </font></left></h2></br>"; ?>
<br>
<table width="75%" id="mytable" cellspacing="0" cellpadding=0>
	<tr>
		<td width="5%" valign="top" align="center" class="mytableheader" style="border-right:none;" align="center" width="1%"><?php echo strtoupper("$lg_no");?></td>
		<td width="10%" valign="top" align="center" class="mytableheader" style="border-right:none;" align="center" width="5%"><a href="#" onClick="clear_newwindow();formsort('id <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_date");?></a></td>
		<td width="25%" valign="top" align="center" class="mytableheader" style="border-right:none;" align="center" width="18%"><a href="#" onClick="clear_newwindow();formsort('name <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_student_name");?></a></td>
		<td width="20%" id="mytabletitle" valign="top" align="center">SEKOLAH</td>
		<td width="15%" id="mytabletitle" valign="top" align="center">NIS</td> 
	</tr>

<?php
	$i=0;
    $select = mysql_query("SELECT * FROM stureg WHERE id>0 AND status=10 and isdel=0 $sqlsid $sqlyear $sqlsort") or die (mysql_error());
    while($read=mysql_fetch_array($select)){
        $nama=$read['name'];
        $uid=$read['uid'];
	$id=$read['id'];
	$sch_id=$read['sch_id'];
	$cdate=explode(" ",$read['cdate']);
	$tgl=explode("-",$cdate[0]);
	$sch=mysql_query("SELECT * FROM sch WHERE id='$sch_id'") or die(mysql_error());
	$sch_name=mysql_fetch_array($sch);
	$name=$sch_name['name'];
	//echo "$sch_name";
     $i++;

echo "<tr bgcolor=\"$bg\" style=\"cursor:default\" onMouseOver=\"this.bgColor=#CCCCFF\" onMouseOut=\"this.bgColor=$bg\">
  	<td id=\"myborder\" align=\"center\">$i</td>
	<td id=\"myborder\"> $tgl[2]-$tgl[1]-$tgl[0]</td>
	<td id=\"myborder\"><input type=\"hidden\" name=\"id$i\" value=\"$id\">$nama</td>
	<td id=\"myborder\">$name</td>
        <td id=\"myborder\" align=\"center\"><input type=\"text\" name=\"uid$i\" value=\"$uid \"></td>
        
  </tr>";
} ?>
<input type="hidden" name="jum" value="<?php echo $i; ?>">
</table>


	<?php include("../inc/paging.php");?>

</div></div> 

</form> <!-- end myform -->
</body>
</html>
<!-- 
v2.7
22/11/2008	: update sesi listing
Author		: razali212@yahoo.com
 -->