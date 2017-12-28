<?php
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|HR');
$username = $_SESSION['username'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="javascript">
	var myWind = ""
	function openchild(table,fname) {
		//if (myWind == "" || myWind.closed || myWind.name == undefined) {
			url= fname + "?grp=" + table;
    		myWind = window.open(url,"subWindow","HEIGHT=700,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar")
	  	//} else{
    			myWind.focus();
  		//}

	} 
</script>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>

<body>
 <div id="panelleft"> 
	<?php include('inc/mymenu.php'); ?>
</div>
<div id="content2">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="javascript: href='p.php?p=prmset'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	</div>
	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>
</div>
<div id="story">

  <table width="100%"  border="0" id="mytable">
<?php if(is_verify('SUPERUSER')){ ?>
    <tr id="mytabletitle">
	  <td width="22%">System Configuration <font color="#FF0000">(Do not change, contact your system administrator)</font> </td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('accesskey','prmsys.php')"><img src="../img/expand.gif" width="9" height="9"> Kunci Akses </a></td>
    </tr>
    <tr>
      <td><a href="#" onClick="openchild('syslevel','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Level Mengakses Sistem </a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('staffid','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Staff ID</a></td>
    </tr>
	<tr><td><a href="#" onClick="openchild('schcat','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Kategori Sekolah </a></td></tr>
	<tr>
      <td><a href="#" onClick="openchild('schtype','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Jenis Sekolah </a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('schlevel','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Level Sekolah </a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('classlevel','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Level Kelas</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('subtype','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Bidang <?php echo $lg_subjek;?></a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('session','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Sesi Persekolahan</a></td>
    </tr>
	<tr>
	  <td><a href="#" onClick="openchild('stusta','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Status Pelajar</a></td>
    </tr>
	<tr>
	  <td><a href="#" onClick="openchild('statusmohon','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Status Pemohonan Pelajar</a></td>
    </tr>
	<tr>
	  <td><a href="#" onClick="openchild('worksta','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Status Mohon Kerja</a></td>
    </tr>
	<tr>
	  <td><a href="#" onClick="openchild('statussubjek','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Status Matapelajaran </a></td>
    </tr>    
	<tr>
      <td><a href="#" onClick="openchild('marital_status','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Status Perkahwinan</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('quran_syaathir','prm.php')"><img src="../img/expand.gif" width="9" height="9"> QURAN - Asy-Syaathir</a></td>
    </tr>
	<tr><td><a href="#" onClick="openchild('sms_setting','prm.php')"><img src="../img/expand.gif" width="9" height="9">SMS SETTING</a></td></tr>
    <tr><td><a href="#" onClick="openchild('sps_external','prm.php')"><img src="../img/expand.gif" width="9" height="9">SPS EXTERNAL</a></td></tr>
    <tr><td><a href="#" onClick="openchild('sps_master','prm.php')"><img src="../img/expand.gif" width="9" height="9">SPS MASTER</a></td></tr>
	<tr id="mytabletitle">
	  <td>Application Configuration <font color="#FF0000">(Expert user only, contact your system administrator)</font></td>
    </tr>
	<tr>
	  <td><a href="#" onClick="openchild('saletype','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Kategori - Penjualan</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('saleitem','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Item  - Penjualan </a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('donation','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Item - Sumbangan </a></td>
    </tr>
	<tr>
	  <td><a href="#" onClick="openchild('feetype','prm.php')"><img src="../img/expand.gif" width="9" height="9"> BIAYA - Jenis</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('yuran','prm.php')"><img src="../img/expand.gif" width="9" height="9"> BIAYA - Parameter</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('yuran','prmfee.php')"><img src="../img/expand.gif" width="9" height="9"> BIAYA - Konfigurasi</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('fee_interface','prm.php')"><img src="../img/expand.gif" width="9" height="9"> BIAYA - Interface</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('fee_outlevel','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Tangga Tunggakan - BIAYA</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('examconf','prm.php')"><img src="../img/expand.gif" width="9" height="9"> UJIAN - Konfigurasi </a></td>
	</tr>
	<tr>
      <td><a href="#" onClick="openchild('exam','prm.php')"><img src="../img/expand.gif" width="9" height="9"> UJIAN  - Nama Ujian</a></td>
	</tr>
	<tr>
      <td><a href="#" onClick="openchild('grading','prm.php')"><img src="../img/expand.gif" width="9" height="9"> UJIAN - Parameter Gred</a></td>
	</tr>
	<tr>
      <td><a href="#" onClick="openchild('grading','prmgrading.php')"><img src="../img/expand.gif" width="9" height="9"> UJIAN - Konfigurasi Gred</a></td>
	</tr>
	<tr>
      <td><a href="#" onClick="openchild('reportcard','prm.php')"><img src="../img/expand.gif" width="9" height="9"> UJIAN - Slip ujian </a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('subgroup','prm.php')"><img src="../img/expand.gif" width="9" height="9">  UJIAN - Kumpulan Matapelajaran</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('headcount','prm.php')"><img src="../img/expand.gif" width="9" height="9">  UJIAN - Setting Headcount</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('asrama','prmhos.php')"><img src="../img/expand.gif" width="9" height="9"> ASRAMA - Blok Asrama</a></td>
	</tr>
	<!--<tr>
      <td><a href="#" onClick="openchild('building','prmbuilding.php')"><img src="../img/expand.gif" width="9" height="9"> BUILDING - Room Setting </a></td>
	</tr>-->
	<tr>
      <td><a href="#" onClick="openchild('examtemuduga','prm.php')"><img src="../img/expand.gif" width="9" height="9"> EDAFTAR - Ujian &amp; Wawancara </a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('edaftarprogram','prm.php')"><img src="../img/expand.gif" width="9" height="9"> EDAFTAR - Program</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('profileexam','prm.php')"><img src="../img/expand.gif" width="9" height="9"> EDAFTAR - Hasil Ujian </a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('grade','prm.php')"><img src="../img/expand.gif" width="9" height="9"> EDAFTAR - Grading</a></td>
    </tr>
	<tr>
      <td><a href="#" onClick="openchild('pusattemuduga','prm.php')"><img src="../img/expand.gif" width="9" height="9"> EDAFTAR - Tempat Wawancara </a></td>
    </tr>
    <tr><td><a href="#" onClick="openchild('job','prm.php')"><img src="../img/expand.gif" width="9" height="9"> RECRUITMENT - Seting Jabatan</a></td></tr>
	<tr><td><a href="#" onClick="openchild('item_expenses','prm_expenses.php')"><img src="../img/expand.gif" width="9" height="9"> EXPENSES - ITEM</a></td></tr>
	<tr><td><a href="#" onClick="openchild('asset_cat','prm.php')"><img src="../img/expand.gif" width="9" height="9"> ASSET - KATEGORI ASSET</a></td></tr>
    <tr><td><a style="cursor:pointer" onClick="openchild('mtn_cat','prm.php')"><img src="../img/expand.gif" width="9" height="9"> MAINTENANCE - KATEGORI</a></td></tr>
    <tr><td><a style="cursor:pointer" onClick="openchild('mtn_subcat','prm.php')"><img src="../img/expand.gif" width="9" height="9"> MAINTENANCE - SUB KATEGORI</a></td></tr>
    <tr><td><a style="cursor:pointer" onClick="openchild('maintenance_sta','prm.php')"><img src="../img/expand.gif" width="9" height="9"> MAINTENANCE - STATUS</a></td></tr>
    <tr><td><a style="cursor:pointer" onClick="openchild('building_name','prm.php')"><img src="../img/expand.gif" width="9" height="9"> BUILDING - NAMA</a></td></tr>
	<tr><td><a style="cursor:pointer" onClick="openchild('building_block','prm.php')"><img src="../img/expand.gif" width="9" height="9"> BUILDING - BLOK</a></td></tr>
	<tr><td><a style="cursor:pointer" onClick="openchild('building_level','prm.php')"><img src="../img/expand.gif" width="9" height="9"> BUILDING - LEVEL </a></td></tr>
	<tr><td><a style="cursor:pointer" onClick="openchild('inventory_cat','prm.php')"><img src="../img/expand.gif" width="9" height="9"> INVENTORY - KATEGORI </a></td></tr>
    <tr><td><a style="cursor:pointer" onClick="openchild('clssession','prm.php')"><img src="../img/expand.gif" width="9" height="9">SESI KELAS</a></td></tr>
	<tr>
    	<td><a href="../adm/letter_config.php?type=feewarn" target="_blank" title="Config Letter">
    	<img src="../img/expand.gif" width="9" height="9"> TEMPLATE - PENGINGAT BIAYA </a></td>
    </tr>
	<tr><td><a href="../adm/letter_config.php?type=student_register" target="_blank" title="Config Letter">
    	<img src="../img/expand.gif" width="9" height="9"> TEMPLATE - REGISTRASI PELAJAR </a></td>
     </tr>

<?php } ?>
 	<tr id="mytabletitle">
	  <td>Application Interface</td>
    </tr>
		<tr>
	  <td><a style="cursor:pointer" onClick="openchild('saletype','prm.php')"><img src="../img/expand.gif" width="9" height="9"> JUALAN - Kategori</a></td>
    </tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('saleitem','prm.php')"><img src="../img/expand.gif" width="9" height="9"> JUALAN - Item</a></td>
    </tr>
    <tr>
      <td><a style="cursor:pointer" onClick="openchild('openexam','prm.php')"><img src="../img/expand.gif" width="9" height="9"> E-Waris</a></td>
    </tr>
	<tr>
      <td><a style="cursor:pointer" onClick="newwindow('../eatt/att_cal_set.php',0)"><img src="../img/expand.gif" width="9" height="9"> Taqwim Sekolah</a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('dis_cat','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Kategori Disiplin</a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('','prm_dis.php')"><img src="../img/expand.gif" width="9" height="9"> Kasus Disiplin</a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('dis_act','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Tindakan Disiplin</a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('koq_grp','prm.php')"><img src="../img/expand.gif" width="9" height="9"> KOQ - BIDANG KUMPULAN</a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('koq','prm_koq.php')"><img src="../img/expand.gif" width="9" height="9"> KOQ - EKSTRA KURIKULER </a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('koq_jawatan','prm.php')"><img src="../img/expand.gif" width="9" height="9"> KOQ - JABATAN PELAJAR</a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('koq_jaw_guru','prm.php')"><img src="../img/expand.gif" width="9" height="9"> KOQ - JABATAN GURU</a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('point_penyertaan','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Nilai Penyartaan</a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('joblevel','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Gred Jabatan</a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('jenis_cuti','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Jenis Cuti</a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('conf_cuti','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Konfigurasi Cuti</a></td>
	</tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('conf_advance','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Konfigurasi Advance</a></td>
	</tr>
	<tr>
	  <td><a style="cursor:pointer" onClick="openchild('salary_item','prm.php')"><img src="../img/expand.gif" width="9" height="9"> GAJI - ITEM</a></td>
    </tr>
	<tr>
	  <td><a style="cursor:pointer" onClick="openchild('bank','prm.php')"><img src="../img/expand.gif" width="9" height="9"> List Bank</a></td>
    </tr>
	<tr>
	  <td><a style="cursor:pointer" onClick="openchild('penyakit','prm.php')"><img src="../img/expand.gif" width="9" height="9"> List Penyakit</a></td>
    </tr>
	<tr>
	  <td><a style="cursor:pointer" onClick="openchild('staff_award','prm.php')"><img src="../img/expand.gif" width="9" height="9"> KARTEGORI - PENGHARGAAN STAFF</a></td>
    </tr>
	
 	<tr id="mytabletitle">
	  <td>Parameter Interface</td>
    </tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('newscategory','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Kategori Buletin</a></td>
	</tr>
    <tr>
	  <td><a style="cursor:pointer" onClick="openchild('religion','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Agama</a></td>
    </tr>
    <tr>
	  <td><a style="cursor:pointer" onClick="openchild('race','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Bangsa</a></td>
    </tr>
	<tr>
      <td><a style="cursor:pointer" onClick="openchild('sex','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Jenis Kelamin</a></td>
    </tr>
    <tr>
	  <td><a style="cursor:pointer" onClick="openchild('parent','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Penjaga</a></td>
    </tr>
	<tr>
	  <td><a style="cursor:pointer" onClick="openchild('transport','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Pengangkutan</a></td>
    </tr>
	<tr>
	  <td><a style="cursor:pointer" onClick="openchild('salary','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Tangga Gaji</a></td>
    </tr>
	<tr>
	  <td><a style="cursor:pointer" onClick="openchild('job','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Jabatan</a></td>
    </tr>
	<tr>
	  <td><a style="cursor:pointer" onClick="openchild('jobdiv','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Bahagian</a></td>
    </tr>
	<tr>
	  <td><a style="cursor:pointer" onClick="openchild('jobsta','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Status Jabatan</a></td>
    </tr>
	<tr>
	  	<td><a style="cursor:pointer" onClick="openchild('kelulusan','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Kelulusan Akademik</a></td>
    </tr>
    <tr>
	  	<td><a style="cursor:pointer" onClick="openchild('daerah','prm.php')"><img src="../img/expand.gif" width="9" height="9"> Daerah</a></td>
    </tr>
  </table>

</body>
</div></div>
</html>
