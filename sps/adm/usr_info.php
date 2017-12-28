<?php 
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
$username = $_SESSION['username'];

$id=$_REQUEST['id'];


			

		
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>


<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">ssss
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="location.href='news_info.php?id=<?php echo $id;?>';" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>DDDD
</div> <!-- end mymenu -->
</div> <!-- end cpanel -->
<div id="story" style="padding:10px 20px 20px 50px ">

<br>
<br>
DDDDDDDDDDDDDDDDDDDDDDDDDDDD
sss
<font size="+1"><?php echo $tit;?></font><br>
By: <?php echo "$by&nbsp;&nbsp;&nbsp;&nbsp;Date: $dt"; ?>
<br>
<br>
<font size="2"><?php echo $msg;?></font>
<br>
<br>
<?php if($af1!=""){?> <img src="../img/attach16.png"><a href="<?php echo "../content/news_attachment/$af1";?>"><?php echo $xf1;?></a><br><?php } ?>
<?php if($af2!=""){?> <img src="../img/attach16.png"><a href="<?php echo "../content/news_attachment/$af2";?>"><?php echo $xf2;?></a><br><?php } ?>
<?php if($af3!=""){?> <img src="../img/attach16.png"><a href="<?php echo "../content/news_attachment/$af3";?>"><?php echo $xf3;?></a><br><?php } ?>

</div><!-- story -->
</div><!-- content -->
</body>
</html>
