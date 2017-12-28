<?php 
include_once('../etc/db.php');
include_once('session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify();

$sid=$_SESSION['sid'];
$user=$_SESSION['username'];
$op=$_REQUEST['op'];
$uid=$_POST['stuuid'];
    if($uid=="")
	$uid=$_SESSION['uid'];
    else
	$_SESSION['uid']=$uid;

if($op=="save")
{
    $pass1=md5($_POST['pass1']);
    $pass2=md5($_POST['pass2']);
    $pass3=md5($_POST['pass3']);
        
    $open=mysql_query("SELECT * FROM stu WHERE sch_id=$sid and uid='$uid' and status=6") or die(mysql_error());
    if (mysql_num_rows($open)==0)
    {
	echo "Data kosong";
    }
    else
    {
	if ($pass2=="" && $pass3=="")
	{
	    $a="Masukkan password baru";
	}
	else if ($pass2<>$pass3)
	{
	    $a="Password baru tak sama";
	}
	else if($user<>$pass1)
	{
            $a= "Password lama salah";
        }
	else
	{
	    $update=mysql_query("UPDATE stu SET password='$pass2' WHERE sch_id='$sid' and uid='$uid' and status=6") or die(mysql_error());
	    if($update)
		$a= "Data berhasil disimpan";
	    else
		$a= "Data gagal";
	}
    }
}
else if($op=="reset")
{
   $read=mysql_query("SELECT * FROM stu WHERE sch_id='$sid' and uid='$uid' and status=6") or die(mysql_error());
    $select=mysql_fetch_array($read);
    $bday=$select['bday'];
    $day=md5($uid);
    
    $reset=mysql_query("UPDATE stu SET password='$day' WHERE sch_id='$sid' and uid='$uid' and status=6") or die(mysql_error());
	if($reset)
		$a= "Password berhasil diriset";
	else 
		$a= "Password gagal diriset";
	
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>
<script language="JavaScript">
function process_form(op)
{
    var ret="";
    var cflag=false;
    if(op=='save')
    {
	if(document.myform.pass1.value=="")
	{
	    alert("masukkan password lama");
	    document.myform.pass1.focus();
	    return;
	}
        else if(document.myform.pass2.value=="")
	{
	    alert("masukkan password baru");
	    document.myform.pass2.focus();
		return;
	}
        else if(document.myform.pass3.value==""){
	    alert("ulangi password baru");
	    document.myform.pass3.focus();
	    return;
        }
	ret = confirm("Simpan password baru?");
	if (ret == true)
	{
	    document.myform.op.value=op;
	    document.myform.submit();
	}
    }
    else if(op=='reset')
    {
	ret = confirm("Anda yakin reset password?");
	if (ret == true)
	{
	    document.myform.op.value=op;
	    document.myform.submit();
	}	
    }  
}
</script>
</head>
<body>
    <form name="myform" method="post" action="" >
    <input type="hidden" name="op" value="">
    <input type="hidden" name="uid" value="<?php echo $uid; ?>">
<div id="panelleft">
    <?php include('inc/lmenu.php');?>
</div>

<div id="content2">
        
    <div id="masthead_title" style="border-right:none; border-top:none;" >
                    <?php echo strtoupper($name);?>
    </div>
    <div style="font-size:11px; font-weight:bold; color:#333333; border-bottom:2px solid #666;"></div>
    <div id="story">
    <div id="mypanel">
        <div id="mymenu" align="center">
            <a href="#" onClick="process_form('save')" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
                <div id="mymenu_seperator"></div>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
	    <a href="#" onClick="process_form('reset')"id="mymenuitem"><img src="../img/reload.png"><br>Reset Password</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
        </div>
    </div>
    <?php echo "<h2> <left><font color=#ebaf17> $a </font></left></h2></br>"; ?>
    <div id="mytitlebg" align="center">UBAH PASSWORD </div>

    <table id="mytable" align="center">
        <tr>
            <td>Password Lama</td>
            <td>:</td>
            <td><input type="text" name="pass1" id="pass1" value="<?php $pass1; ?>"></td>
        </tr>
        <tr>
            <td>Password Baru</td>
            <td>:</td>
            <td><input type="text" name="pass2" id="pass2"></td>
        </tr>
        <tr>
            <td>Ulangi Password</td>
            <td>:</td>
            <td><input type="text" name="pass3" id="pass3"></td>
        </tr>
    </table>
    </form>
    </div>
</div>
</body>
</html>