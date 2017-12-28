<?php 
include_once('../etc/db.php');
include_once('etc/session_sms.php');
include_once('../inc/xgate.php');
verify('ADMIN');

$to=$_POST['to'];
if($to=="")
	$to="STAFF";
	
$sid=$_POST['sid'];
if($sid=="")
	$sid=$_SESSION['sid'];

if($sid>0){
	$sqlsid=" and sch_id='$sid'";
	$sqlsid2=" and stu.sch_id='$sid'";
}
$job=$_POST['job'];
if($job!="")
	$sqljob=" and job='$job'";
	
$div=$_POST['div'];
if($div!=""){
	$xdiv=addslashes($div);
	$sqldiv=" and jobdiv='$xdiv'";
}
$sex=$_POST['sex'];
if($sex!="")
	$sqlsex=" and sex='$sex'";
	
$msg=$_POST['msg'];
$tel=$_POST['tel'];

$schlevel = "Tahap";
if($sid>0){
	$sql="select * from sch where id=$sid order by name";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	if($row=mysql_fetch_assoc($res)){
		$schname=$row['name'];
		$schlevel=$row['clevel'];
	}
	$sqlsid=" and sch_id=$sid";
}

$clslevel=0;
$clslevel=$_POST['clslevel'];
$clscode=$_POST['clscode'];
if($clscode!=""){
	$sqlclscode="and ses_stu.cls_code='$clscode'";
	$sql="select * from cls where sch_id=$sid and code='$clscode'";
	$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $clsname=$row['name'];
	$clslevel=$row['level'];
}
if($clslevel>0)
	$sqlclslevel="and ses_stu.cls_level='$clslevel'";

if($to=="STAFF"){
$totaluser=0;
$sql="select count(*) from usr where status=0 $sqlsid $sqljob $sqldiv $sqlsex order by name";
$res=mysql_query($sql)or die("query failed:".mysql_error());
if($row=mysql_fetch_row($res))
	$totaluser=$row[0];
	
$totalhp=0;
$sql="select count(distinct(hp)) from usr where status=0 $sqlsid $sqljob $sqldiv $sqlsex order by name";
$res=mysql_query($sql)or die("query failed:".mysql_error());
if($row=mysql_fetch_row($res))
	$totalhp=$row[0];
}else{
	$cyear=date('Y');
	if(($clslevel==0)&&($clscode==""))
		$sql="select count(*) from stu where status=6";
	else
		$sql="select count(*) from stu LEFT JOIN ses_stu ON (stu.uid=ses_stu.stu_uid) where status=6 $sqlsid2 $sqlsex $sqlclscode $sqlclslevel and ses_stu.year='$cyear'";
	$totaluser=0;
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	if($row=mysql_fetch_row($res))
		$totaluser=$row[0];

	if(($clslevel==0)&&($clscode==""))
		$sql="select count(distinct(hp)) from stu where status=6";
	else
		$sql="select count(distinct(hp)) from stu LEFT JOIN ses_stu ON (stu.uid=ses_stu.stu_uid) where status=6 $sqlsid2 $sqlsex $sqlclscode $sqlclslevel and ses_stu.year='$cyear'";
	$totalhp=0;
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	if($row=mysql_fetch_row($res))
		$totalhp=$row[0];
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="javascript">
function process_send(){

	if(document.myform.msg.value==""){
    	alert("Please enter the message");
        document.myform.msg.focus();
        return;
    }
	ret = confirm("Send this SMS??");
	if (ret == true){
		document.myform.p.value="sms_bcast_save";
		document.myform.submit();
	}
	return;
}
</script>

<script language="javascript">
	var myWind = ""
	function openchild(table,fname) {
		if (myWind == "" || myWind.closed || myWind.name == undefined) {
				url= "addrbook.php?tbl=" + table
    			myWind = window.open(url,"subWindow","HEIGHT=650,WIDTH=800,scrollbars=yes,status=yes,resizable=yes,left=0,top=0")
	  	} else{
    			myWind.focus();
  		}

	} 
</script>

<script language="JavaScript">
function clearfield()
{
	document.form1.tel.value="";
}

function kira(field,countfield,maxlimit){
        var y=field.value.length+1;
        if(y>=maxlimit){
                field.value=field.value.substring(0,maxlimit);
                alert("140 maximum character..");
                return true;
        }else{
				xx=maxlimit-y;
                countfield.value=xx+" HURUF";
        }
}
</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SSS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>
<body>
 
<div id="panelleft"> 
	<?php include('inc/mymenu.php');?>
</div><!--end pageNav-->

<div id="content2">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="p.php?p=sms_bcast" id="mymenuitem"><img src="../img/new.png"><br>New</a>
		<a href="#" id="mymenuitem" onClick="process_send()"><img src="../img/flash.png"><br>Send</a>
	</div> <!-- end mymenu -->
</div> <!-- end mypanel -->
 
<div id="story">
<?php if(!$ON_XGATE){?>
<div align="center" style="font-size:120%; font-weight:bold; color:#FF0000">ESMS Anda Belum Diaktifkan. Sila hubungi AWFATECH. TQ</div>
<?php } ?>
<div id="mytitle">SMS - GROUP BROADCAST (<?php echo "Total User $totaluser - found $totalhp hp numbers"; ?>)</div>

<form name="myform"  method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="sms_bcast">

        <table width="100%" id="mytable">
			<tr >
				<td width="10%">Community</td>
				<td width="2%">:</td>
				<td>
				 <select name="to" onChange="document.myform.submit();">
                        <?php 
							if($to=="STAFF"){
								echo "<option value=\"STAFF\">STAFF</option>";
								echo "<option value=\"WARIS\">WARIS</option>";
							}
							else{
								echo "<option value=\"WARIS\">WARIS</option>";
								echo "<option value=\"STAFF\">STAFF</option>";
							}
						?>
                      </select>
				<td>
				</tr>
			  <tr>
				<td width="10%">Sekolah </td>
				<td width="2%">:</td>
				<td>
				 <select name="sid" id="sid" onChange="document.myform.clscode[0].value='';document.myform.clslevel[0].value='';document.myform.submit();">
                <?php	
      		if(($sid=="")||($sid==0))
            	echo "<option value=\"\">- Semua -</option>";
			else
                echo "<option value=$sid>$schname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
					$s=$row['name'];
					$t=$row['id'];
					echo "<option value=$t>$s</option>";
				}
				if(($_SESSION['sid']==0)&&($sid>0))
					echo "<option value=\"\">- Semua -</option>";
			}				
            mysql_free_result($res);					  
			
?>
              </select>
				</td>
			  </tr>
			  <tr <?php if($to!="STAFF") echo "style=\"display:none \"";?>>
				<td>Kumpulan </td>
				<td width="2%">:</td>
				<td>
<select name="div" onChange="document.myform.submit();">
<?php	
      		if($div=="")
            	echo "<option value=\"\">- Semua -</option>";
			else
                echo "<option value=\"$div\">$div</option>";
			$sql="select * from type where grp='jobdiv' and prm!='$xdiv'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=stripslashes($row['prm']);
                echo "<option value=\"$x\">$x</option>";
            }
			if($div!="")
            	echo "<option value=\"\">- Semua -</option>";  
			
?>
  </select>				
				
				</td>
			  </tr>
		  
			  <tr <?php if($to!="WARIS") echo "style=\"display:none \"";?>>
				<td>Kumpulan </td>
				<td width="2%">:</td>
				<td>
<select name="clslevel" onchange="document.myform.clscode[0].value='';document.myform.submit();">
                  <?php    
					if(($clslevel=="0")||($clslevel==""))
							echo "<option value=\"0\">- Semua $schlevel -</option>";
					else
						echo "<option value=$clslevel>$schlevel $clslevel</option>";
						$sql="select * from type where grp='classlevel' and val='$sid' and prm!='$clslevel' order by prm";
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									echo "<option value=$s>$schlevel $s</option>";
						}
						if($clslevel>0)
							echo "<option value=\"0\">- Semua $schlevel -</option>";
				?>
                </select>
			    <select name="clscode" id="clscode" onchange="document.myform.submit();">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- Semua Kelas -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from cls where sch_id=$sid and code!='$clscode' order by level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['name'];
						$a=$row['code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
					if($clscode!="")
						echo "<option value=\"\">- Semua Kelas -</option>";	
			?>
                </select>
		
				
				</td>
			  </tr>
		  
			  <tr>
				<td></td>
				<td></td>
				<td>
				<input name="sex" type="radio" onChange="document.myform.submit();" value="" <?php if($sex=="") echo "checked";?>> Semua
				<input name="sex" type="radio" onChange="document.myform.submit();" value="Lelaki" <?php if($sex=="Lelaki") echo "checked";?>> Lelaki
				<input name="sex" type="radio" onChange="document.myform.submit();" value="Perempuan" <?php if($sex=="Perempuan") echo "checked";?>> Perempuan</td>
			  </tr>
	
	        <tr >
  				<td></td>
				<td></td>
				<td>
				<strong>Message:</strong><br>
                <textarea name="msg" style="width:70%; font-size:180%; color:#0000FF" rows="5" id="msg" onkeypress="kira(this,this.form.jum,140);"></textarea>
				<br>
                <input type="text" name="jum" value="140 HURUF" size="8" onBlur="kira(this.form.jum,this,140);" disabled>
		</tr>
      </table>

    </form>



</div><!-- end of story -->
</div><!--end of content-->   

</body>
</html>
