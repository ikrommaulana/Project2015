<?php 
//$vdate="100909";
$vdate="120316";// add comment feastures
$vmod="v6.0.0";
include_once('../etc/db.php');
include_once('../etc/session.php');
//verify("");
	
		$adm = $_SESSION['username'];
		$id=$_REQUEST['id'];
		 $sql="select * from content where id='$id'";
		$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res)){
			$tit=stripslashes($row['tit']);
			$content=stripslashes($row['msg']);
			$cat=$row['cat'];
			$status=$row['sta'];
			$access=$row['access'];
			$sid=$row['sid'];
			$by=$row['uid'];
			$dt=$row['dt'];
			$f1=$row['file1'];list($af1,$xf1)=split('[|]',$f1);
			$f2=$row['file2'];list($af2,$xf2)=split('[|]',$f2);
			$f3=$row['file3'];list($af3,$xf3)=split('[|]',$f3);
			list($u1,$u2,$u3,$u4)=split('[/.|]',$access);
		}
		
$usr=mysql_query("SELECT * FROM usr WHERE uid='$by'") or die (mysql_error());
$usr1=mysql_fetch_array($usr);
$nama=$usr['name'];			
		
$delid=$_REQUEST['delid'];
$op=$_REQUEST['op'];
if($op==""){
		$sql="update content set hit=hit+1 where id='$id'";
		$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
}if($op=="save"){
	$msg=addslashes($_REQUEST['msg']);
	$author=addslashes($_SESSION['name']);
	$sql="insert into content(dt,uid,sid,msg,author,adm,ts,app,pid)values(now(),'$adm',$sid,'$msg','$author','$adm',now(),'NEWS_COMMENT','$id')";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
}
if($op=="delete"){
		$sql="delete from content where id=$delid";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
}

function ago($datefrom,$dateto=-1){
        // Defaults and assume if 0 is passed in that
        // its an error rather than the epoch
        if($datefrom==0) { return "A long time ago"; }
		/**
        if($dateto==-1) { $dateto = time()+28800; } //apai tambah 8 jam 28800
		**/
       if($dateto==-1) { $dateto = time(); }
        // Make the entered date into Unix timestamp from MySQL datetime field

        $datefrom = strtotime($datefrom);
   		
        // Calculate the difference in seconds betweeen
        // the two timestamps

        $difference = $dateto - $datefrom;
		//echo "$dateto-$datefrom($ddd)=$difference<br>";
        // Based on the interval, determine the
        // number of units between the two dates
        // From this point on, you would be hard
        // pushed telling the difference between
        // this function and DateDiff. If the $datediff
        // returned is 1, be sure to return the singular
        // of the unit, e.g. 'day' rather 'days'
   
        switch(true)
        {
            // If difference is less than 60 seconds,
            // seconds is a good interval of choice
            case(strtotime('-1 min', $dateto) < $datefrom):
                $datediff = $difference;
                $res = ($datediff==1) ? $datediff.' second ago' : $datediff.' seconds ago';
                break;
            // If difference is between 60 seconds and
            // 60 minutes, minutes is a good interval
            case(strtotime('-1 hour', $dateto) < $datefrom):
                $datediff = floor($difference / 60);
                $res = ($datediff==1) ? $datediff.' minute ago' : $datediff.' minutes ago';
                break;
            // If difference is between 1 hour and 24 hours
            // hours is a good interval
            case(strtotime('-1 day', $dateto) < $datefrom):
                $datediff = floor($difference / 60 / 60);
                $res = ($datediff==1) ? $datediff.' hour ago' : $datediff.' hours ago';
                break;
            // If difference is between 1 day and 7 days
            // days is a good interval               
            case(strtotime('-1 week', $dateto) < $datefrom):
                $day_difference = 1;
                while (strtotime('-'.$day_difference.' day', $dateto) >= $datefrom)
                {
                    $day_difference++;
                }
               
                $datediff = $day_difference;
                $res = ($datediff==1) ? 'yesterday' : $datediff.' days ago';
                break;
            // If difference is between 1 week and 30 days
            // weeks is a good interval           
            case(strtotime('-1 month', $dateto) < $datefrom):
                $week_difference = 1;
                while (strtotime('-'.$week_difference.' week', $dateto) >= $datefrom)
                {
                    $week_difference++;
                }
               
                $datediff = $week_difference;
                $res = ($datediff==1) ? 'last week' : $datediff.' weeks ago';
                break;           
            // If difference is between 30 days and 365 days
            // months is a good interval, again, the same thing
            // applies, if the 29th February happens to exist
            // between your 2 dates, the function will return
            // the 'incorrect' value for a day
            case(strtotime('-1 year', $dateto) < $datefrom):
                $months_difference = 1;
                while (strtotime('-'.$months_difference.' month', $dateto) >= $datefrom)
                {
                    $months_difference++;
                }
               
                $datediff = $months_difference;
                $res = ($datediff==1) ? $datediff.' month ago' : $datediff.' months ago';

                break;
            // If difference is greater than or equal to 365
            // days, return year. This will be incorrect if
            // for example, you call the function on the 28th April
            // 2008 passing in 29th April 2007. It will return
            // 1 year ago when in actual fact (yawn!) not quite
            // a year has gone by
            case(strtotime('-1 year', $dateto) >= $datefrom):
                $year_difference = 1;
                while (strtotime('-'.$year_difference.' year', $dateto) >= $datefrom)
                {
                    $year_difference++;
                }
               
                $datediff = $year_difference;
                $res = ($datediff==1) ? $datediff.' year ago' : $datediff.' years ago';
                break;
               
        }
        return $res;
}
		
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

<!-- SETTING JQUERY -->
<script src="<?php echo $MYOBJ;?>/jquery/jquery-1.6.4.js"></script>
<script src="<?php echo $MYOBJ;?>/jquery/jquery-ui-1.8.16.custom.min.js"></script>

<script type="text/javascript">
function process_chat(op,id){
		document.myform.op.value=op;
		document.myform.delid.value=id;
		
		if(op=='save'){
			if(document.myform.msg.value==""){
				alert("Please enter the comment..");
				document.myform.msg.focus();
				return;
			}
		}
		document.myform.submit();
		return;
}
function kira(field,countfield,maxlimit){
        var y=field.value.length;
        if(y>maxlimit){
                field.value=field.value.substring(0,maxlimit);
                alert(maxlimit+" maximum character..");
				document.myform.msg.focus();
                return true;
        }else{
                countfield.value=maxlimit-y;
        }
}
</script>

</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="op">
<input type="hidden" name="delid">

<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a style="cursor:pointer" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>        
		<a style="cursor:pointer" onClick="location.href='news_info.php?id=<?php echo $id;?>';" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>        
		<a style="cursor:pointer" onClick="window.close();parent.$.fancybox.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>        
	</div> <!-- end mymenu -->
	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>
</div> <!-- end cpanel -->
<div id="story" style="padding:10px 20px 20px 50px ">

<br>
<br>


<font size="+1"><?php echo $tit;?></font><br>
By: <?php echo "$nama&nbsp;&nbsp;&nbsp;&nbsp;Date: $dt"; ?>
<br>
<br>
<font size="2">
<?php 
	$content=stripslashes($content);
	echo $content; 
?>
</font>
<br>
<br>
<div>Attachments: (to download, right click to save link as to the computer, or just clik to open)</div>
<?php if($af1!=""){?> <img src="../img/attach16.png"><a href="<?php echo "../content/news_attachment/$af1";?>" target="_blank"><?php echo $xf1;?></a><br><?php } ?>
<?php if($af2!=""){?> <img src="../img/attach16.png"><a href="<?php echo "../content/news_attachment/$af2";?>" target="_blank"><?php echo $xf2;?></a><br><?php } ?>
<?php if($af3!=""){?> <img src="../img/attach16.png"><a href="<?php echo "../content/news_attachment/$af3";?>" target="_blank"><?php echo $xf3;?></a><br><?php } ?>
<br>
<br>
<? if($cat=='EWARIS'){
echo "<br>";
}
else { ?>
<div id="mytitle2">
<a name="chatbox" onClick="$('#msgbox').toggle('slow');document.myform.msg.focus();"  style="color:#444444;text-decoration:none;cursor:pointer;">
                    Write Comment <img src="<?php echo $MYLIB;?>/img/pencil16.png" style="margin:0px 0px -2px 0px ">
</a>
</div>
<div id="msgbox" style="display:none">
        	<div id="mytabletitle" style="width:99%">
                <table width="99%" cellpadding="0">
                  <tr>
                    <td colspan="2"><textarea name="msg" id="msg" style="width:100%;" onKeyUp="kira(this,this.form.jum,300);" rows="3"></textarea></td>
                  </tr>
                  <tr>
                    <td width="50%" align="left"><input type="text" name="jum" value="300" disabled size="3" style="border:none;"></td>
                    <td width="50%" align="right" style="font-size:10px; font-weight:bold; ">
                    	[ <a id="sss" style="cursor:pointer" onClick="process_chat('save');">Submit This Comment</a> ]</td>
                  </tr>
                </table>
              
        	</div>
</div>
<div style="height:220px; min-height:220px; overflow-y: auto;background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg)" >
<?php
		$i=0;
		$sql="select * from content where app='news_comment' and pid='$id' order by id desc";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
				$na=ucwords(strtolower(stripslashes($row['author'])));
				$m=stripslashes($row['msg']);
				$id=$row['id'];
				$uid=$row['uid'];
				$dt=$row['dt'];
				$ts=$row['ts'];
				$now=date('Y-m-d');
				$date_parts1=explode("-", $dt);
			    $date_parts2=explode("-", $now);
			
				$sql="select * from usr where uid='$uid'";
				$res2=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$img=$row2['file'];
				if($img!="")
					$img=$dir_image_user.$img;
				else
					$img="$MYLIB/img/defaultuser.png";
					
				if(!file_exists($img))
					$img="$MYLIB/img/defaultuser.png";
			
			   	$start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
			   	$end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
			   	$jumhari= $end_date - $start_date;
			   	if($jumhari<2)
			   		$new="<img src=$MYLIB/img/newicon.png>";
				else
					$new="";
				$i++;
				
?>
			<div style="border-top:none; border-bottom:1px solid #DDDDDD"></div>
			<div  style="font-size:9px; padding:5px 4px 5px 4px">
				<img src="../adm/imgresize.php?w=30&h=30&img=<?php echo $img;?>" width="30" height="30" style="float:left; ">
					<font face="Verdana" color="#000000"><?php echo "$m";?></font><br>
					<font face="Verdana" color="#996600"><strong>By&nbsp;<?php echo "$na $new";?></strong><br><?php $dt=ago($ts);echo "$dt";?></font>
					<?php if((is_verify('ADMIN'))||($uid==$_SESSION['username'])){?>
						&lt;<a style="cursor:pointer" title="Remove" onClick="process_chat('delete','<?php echo $id;?>');">
                        <font face="Verdana" color="#FF0000" style="font-size:9px">remove</font></a>&gt;
					<?php } ?>
			</div>
     		
<?php } ?>
	</div>
<?php } ?>
</div><!-- story -->
</div><!-- content -->
</form>
</body>
</html>
