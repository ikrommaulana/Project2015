<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
$sid=$_REQUEST['sid'];
$ic=$_REQUEST['ic'];

 
require_once("../dompdf/dompdf_config.inc.php");
 
if($ic!=""){
	$get = "select * from stureg where ic='$ic' and isdel=0 order by id desc";
	$res=mysql_query($get) or die("$get query failed:".mysql_error());
		$num=mysql_num_rows($res);
		if($num>0){	
			$row=mysql_fetch_assoc($res);
			$xname=$row['name'];
			$ic=$row['ic'];
			$id=$row['id'];
			$transid=$row['transid'];
			$xic=$row['ic'];
			$xid=$row['id'];
			$name=stripslashes($row['name']);
			$status=$row['status'];
			$job=$row['job'];
			$xdt=$row['cdate'];
			$tanggal=explode(" ",$xdt);
			$tgl=explode("-",$tanggal[0]);
			$img=$row['picture'];
			$att=$row['resume'];
			$schid=$row['sch_id'];
			
			$clssession=$row['clssession'];
			$interview_center=$row['pt'];
			$interview_date=$row['tarikhtemuduga'];
			$letter=$row['letter'];

		
			$sql="select * from type where grp='statusmohon' and val=$status";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$statusmohon=$row['prm'];
			$remark=$row['des'];
			$FOUND=1;
			
			$sql="select * from sch where id='$schid'";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$progname=stripslashes($row['name']); //school name
		}
}
$open="select * from sch where id='$sid'";
    $xres=mysql_query($open)or die("query failed:".mysql_error());
    $xrow=mysql_fetch_assoc($xres);
    $sname=stripslashes($xrow['name']);
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


$html =
  
	'<div align="center" style="padding:5px; font-size:12px;">'.
		'<p><img src='.$simg.' height="70px"></p>'.
		'<p><h1>'.$sname.'</h1></p>'.
		'<p> '.$saddr.' '.$saddr1.' '.$xxp.' '.$xxd.' '.$xxs.' '.$xxc.'</p>'.
		'<p>Tel:'.$stel.' Fax: '.$sfax.' Http://'.$sweb.'</p>'.
	'</div><br><br>'.
	'<table>
		<tr>
			<td>Tanggal Daftar</td>
			<td> : </td>
			<td> '.$tgl[2].'-'.$tgl[1].'-'.$tgl[0].'</td>
		</tr>
		<tr>
			<td>Nama Siswa</td>
			<td> : </td>
			<td> '.$xname.'</td>
		</tr>
		<tr>
			<td>No Akte Lahir/Passport</td>
			<td> : </td>
			<td> '.$xic.'</td>
		</tr>
		<tr>
			<td>Nama Sekolah</td>
			<td> : </td>
			<td> '.$sname.'</td>
		</tr>
		<tr>
			<td>Status</td>
			<td> : </td>
			<td> '.$statusmohon.'</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td> '.$remark.'</td>
		</tr>
	</table><br><br>
	<div align="center">
		TERIMA KASIH
	</div>'.
'</body></html>';
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream('cetak_pendaftaran.pdf');
 
?>