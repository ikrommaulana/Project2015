<?php 
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body>
<form name="myform" method="post">
<div id="content" style="min-height:300px">
<div id="mypanel">
	<div id="mymenu" align="center">
        <a style="cursor:pointer" onClick="document.myform.submit()"id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a style="cursor:pointer" onClick="window.close();parent.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>        
    </div> <!-- end mymenu -->
	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>
</div> <!-- end cpanel -->
<div id="mytitlebg">WHO'S ONLINE</div>
	<div style="background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg)" >
<?php
		$tm=time()-300;//5minit x active
		$sql="select * from usr where seson=1 and sestm>$tm";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
				$na=ucwords(strtolower(stripslashes($row['author'])));
				$m=stripslashes($row['msg']);
				$id=$row['id'];
				$uid=$row['uid'];
				$name=ucwords(strtolower(stripslashes($row['name'])));
				$img=$row['file'];
				if($img!="")
					$img=$dir_image_user.$img;
				else
					$img="$MYLIB/img/defaultuser.png";
					
				if(!file_exists($img))
					$img="$MYLIB/img/defaultuser.png";
			
				$i++;$q++;
				if($i>1){
?>
	<div style="border-top:none; border-bottom:1px solid #DDDDDD"></div>
<?php } ?>
			<div  style="font-size:9px; padding:5px 4px 5px 4px">
				<img src="../adm/imgresize.php?w=30&h=30&img=<?php echo $img;?>" width="30" height="30" style="float:left; ">
					<font face="Verdana" color="#000000"><?php echo "";?></font><br>
					<font face="Verdana" color="#996600"><strong>&nbsp;<?php echo "$name";?></strong><br><br></font>
			</div>
<?php } ?>
	</div>

</div> <!--content -->
</form>
</body>
</html>