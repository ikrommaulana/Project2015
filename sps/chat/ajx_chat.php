<?php 
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
$sid=$_REQUEST['sid'];
if($sid=="")
	$sid=$_SESSION['sid'];
		

$id=$_REQUEST['id'];
$op=$_REQUEST['op'];
if($op=="save"){
	$msg=addslashes($_REQUEST['msg']);
	$uid=$_SESSION['username'];
	$sid=$_SESSION['sid'];
	$author=addslashes($_SESSION['name']);
	
	$sql="insert into content(dt,uid,sid,msg,author,adm,ts,app)values(now(),'$uid',$sid,'$msg','$author','$uid',now(),'COMMUNITY_INFO')";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
}
if($op=="delete"){
		$sql="delete from content where id=$id";
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
<title></title>


<script type="text/javascript">
function process_chat(op,id){
		var xmlhttp;
		var msg;
		document.myform.op.value=op;
		document.myform.id.value=id;
		
		if(op=='save'){
			if(document.myform.msg.value==""){
				alert("<?php echo $lg_please_enter_the_information;?>");
				document.myform.msg.focus();
				return;
			}
			msg=document.myform.msg.value;
			document.myform.msg.value='';
			hide('msgbox');
		}	
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("chat_div").innerHTML=xmlhttp.responseText;
				<?php //include_once("$MYLIB/inc/myfancybox.js");?>
				$(".fbmedium").fancybox({
					'width'				: '75%',
					'height'			: '85%',					
					'autoScale'			: false,					
					'type'				: 'iframe',
				});
				$(".fbbig").fancybox({
					'width'				: '90%',
					'height'			: '100%',					
					'autoScale'			: true,					
					'type'				: 'iframe',
				});
			}
		}
		xmlhttp.open("GET","../chat/ajx_chat.php?id="+id+"&op="+op+"&msg="+msg,true);
		xmlhttp.send();

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

<div id="chat_div"> 

        <div id="divchatroom" style="font-size:11px; border:1px solid #ccc; padding:4px;background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg)">	
               <table width="100%" cellspacing="0" cellpadding="0">
               	<tr><td width="70%">
                		<a name="chatbox" onClick="$('#msgbox').toggle('slow');document.myform.msg.focus();"  style="color:#444444;text-decoration:none;cursor:pointer;">
                               <strong><?php echo $lg_online_message;?></strong>&nbsp;<img height="12" src="<?php echo $MYLIB;?>/img/dialog16.png"></a>
                </td>
                <td width="30%" align="right">
                   <?php
							$tm=time()-300;//5minit x active
							$sql="select count(*) from usr where seson=1 and sestm>$tm";
							$res2=mysql_query($sql,$link)or die("query failed:".mysql_error());
							$row2=mysql_fetch_row($res2);
							$num=$row2[0];
                	?>	
                	<a href="../chat/chat_online.php" target="_blank" style="color:#666666; font-size:11px;" class="fbmedium"><?php echo $lg_on_time;?> (<?php echo $num;?>)</a>	
                    </td>
                </tr>
                </table>
        </div> 
        
               
              <div style="border-top:1px solid #FAFAFA;"></div>
                   
                
    
        <div id="msgbox" style="display:none">
        	<div id="mytabletitle" style="width:99%">
                <table width="99%" cellpadding="0">
                  <tr>
                    <td colspan="2"><textarea name="msg" id="msg" style="width:98%;" onKeyUp="kira(this,this.form.jum,300);" rows="6"></textarea></td>
                  </tr>
                  <tr>
                    <td width="50%" align="left"><input type="text" name="jum" value="300" disabled size="3" style="border:none;"></td>
                    <td width="50%" align="right" style="font-size:10px; font-weight:bold; ">
                    	[<a style="cursor:pointer" onClick="process_chat('save');">SUBMIT</a>]</td>
                  </tr>
                </table>
        	</div>
        </div>
        
   

    
	<div  style="height:180px; min-height:180px; overflow-y: auto;background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg)" >
<?php
		$i=0;
		if($ONLINE_MSG_GLOBAL)
				$sql="select * from content where app='community_info' order by id desc limit 10";
		else
				$sql="select * from content where app='community_info' and (sid=0 or sid=$sid) order by id desc limit 10";
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
		if($i>1)
			echo "<div style=\"border-bottom:1px solid #DDDDDD\"></div>";	
?>
	
    
			<div  style="font-size:9px; padding:5px 4px 5px 4px">
				<img src="../adm/imgresize.php?w=30&h=30&img=<?php echo $img;?>" width="30" height="30" style="float:left; ">
					<font face="Verdana" color="#000000"><?php echo "$m";?></font><br>
					<font face="Verdana" color="#996600"><strong>By&nbsp;<?php echo "$na $new";?></strong><br><?php $dt=ago($ts);echo "$dt";?></font>
					<?php if((is_verify('ADMIN'))||($uid==$_SESSION['username'])){?>
						&lt;<a style="cursor:pointer" onClick="process_chat('delete','<?php echo $id;?>');"><font face="Verdana" color="#FF0000" style="font-size:9px"><?php echo $lg_delete;?></font></a>&gt;
					<?php } ?>
			</div>
<?php } ?>

     		<table width="100%" bgcolor="#FAFAFA" style="padding-right:5px;">
                        <tr>
                            <td width="50%" align="right">
                                    <a href="../chat/chat_history.php" target="_blank" style="color:#666666; font-size:11px;" class="fbmedium"><?php echo $lg_archive;?></a>
                            </td>
                        </tr>
            </table>

	</div>
    	

</div>
 
</body>
</html>