<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
$p=$_REQUEST['p'];
$id=$_REQUEST['id'];
if($id=="")
	$id=1;

$sql="select * from tutorial_sys where id='$id'";
$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
if($row=mysql_fetch_assoc($res)){
			$title=stripslashes($row['tit']);
			$content=stripslashes($row['msg']);
			$topic=$row['cat'];
			$linkk=$row['link'];
			$status=$row['sta'];
			$access=$row['access'];
			$sid=$row['sid'];
			$by=$row['uid'];
			$dt=$row['dt'];
			$f1=$row['file1'];list($af1,$xf1)=explode("|",$f1);
			$f2=$row['file2'];list($af2,$xf2)=explode("|",$f2);
			$f3=$row['file3'];list($af3,$xf3)=explode("|",$f3);
			list($u1,$u2,$u3,$u4)=split('[/.|]',$access);
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- DW6 -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<!-- SETTING JQUERY -->
<script src="<?php echo $MYOBJ;?>/jquery/jquery-1.6.4.js"></script>
<script src="<?php echo $MYOBJ;?>/jquery/jquery-ui-1.8.16.custom.min.js"></script>

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<!-- SETTING JQUERY -->
<link rel="StyleSheet" href="<?php echo $MYOBJ;?>/dtree/dtree.css" type="text/css" />
<script type="text/javascript" src="<?php echo $MYOBJ;?>/dtree/dtree.js"></script>


</head>
<body bgcolor="#F1F1F1" style=" background:none;background-color:#F1F1F1"> 
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="p" value="<?php echo $p;?>">
<input type="hidden" name="op">
<input type="hidden" name="delid">


<div id="content">

<table width="100%" cellpadding="0" cellspacing="0">
<tr><td width="70%" valign="top" style="padding:4px;" bgcolor="#333333">
<div id="mytitle2" style="background-color:#000000; color:#FFFFFF; border:none; width:632px;"><?php echo $topic;?></div>
 
<div id='player'>This div will be replaced by the JW Player.</div> 
<script type='text/javascript' src='<?php echo $MYOBJ;?>/jwplayer/jwplayer.js'></script> 
<script type='text/javascript'> 
	jwplayer('player').setup({ 
			'flashplayer': '<?php echo $MYOBJ;?>/jwplayer/player.swf', 
			'width': '100%', 
			'height': '438', 
			'backcolor': '0x000000',
			'frontcolor': '0xCCCCCC',
			'lightcolor': '0x557722',
			'controlbar': 'over',//bottom
			'fullscreen': 'true',
    		'stretching': 'fill',
			'file': '<?php echo $linkk;?>'
			//'file': 'http://www.youtube.com/watch?v=723b9yQR6Rk'
			//'file': '<?php echo $MYOBJ;?>/jwplayer/video.mp4' 
	}); 
</script>


</div>

</td>
<td width="30%" valign="top">
			<div id="mytitle2">
            <?php echo $title;?>
            </div>
            <div style="font-size:9px"><?php echo "Date: $dt";?></div>
            <font size="2">
            <?php 
                $content=stripslashes($content);
                echo $content; 
            ?>
            </font>
    
            <div id="mytitle" style="font-size:11px">
                <a name="chatbox" onClick="$('#msgbox').toggle('slow');document.myform.msg.focus();"  style="color:#444444;text-decoration:none;cursor:pointer;">
                                    Write Comment <img src="<?php echo $MYLIB;?>/img/pencil16.png" style="margin:0px 0px -2px 0px ">
                </a>
            </div>
            <div id="msgbox" style="display:none">
                            <table width="99%" cellpadding="0">
                              <tr>
                                <td colspan="2"><textarea name="msg" id="msg" style="width:98%;" onKeyUp="kira(this,this.form.jum,300);" rows="3"></textarea></td>
                              </tr>
                              <tr>
                                <td width="50%" align="left"><input type="text" name="jum" value="300" disabled size="3" style="border:none;"></td>
                                <td width="50%" align="right" style="font-size:10px; font-weight:bold; ">
                                    [ <a id="sss" style="cursor:pointer" onClick="process_chat('save','<?php echo $id;?>');">Submit This Comment</a> ]</td>
                              </tr>
                            </table>
            </div>
            <?php 
                    include('../tutorsps/ajx_comment.php');
            ?>


</td>
</tr>
</table>
</form>
</body>
</html>
