<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
$adm = $_SESSION['username'];
$p=$_REQUEST['p'];
$id=$_REQUEST['id'];
if($id=="")
	$id=1;
	
$sid=$_REQUEST['sid'];
if($sid=="")
	$sid=$_SESSION['sid'];

$sql="select * from tutorial_sys where id='$id'";
$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
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
$delid=$_REQUEST['delid'];
$op=$_REQUEST['op'];
if($op==""){
		$sql="update tutorial_sys set hit=hit+1 where id='$id'";
		$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
}
if($op=="save"){
	$msg=addslashes($_REQUEST['msg']);
	$author=addslashes($_SESSION['name']);
	$sql="insert into tutorial_sys(dt,uid,sid,msg,author,adm,ts,app,pid)values(now(),'$adm',$sid,'$msg','$author','$adm',now(),'TUTORSPS_COMMENT','$id')";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
}
if($op=="delete"){
		$sql="delete from tutorial_sys where id=$delid";
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
<!-- DW6 -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<!-- SETTING JQUERY -->
<script src="<?php echo $MYOBJ;?>/jquery/jquery-1.6.4.js"></script>
<script src="<?php echo $MYOBJ;?>/jquery/jquery-ui-1.8.16.custom.min.js"></script>

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>


<script type="text/javascript">
function process_chat(op,id,delid){
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
		//else if(op=='delete'){
		//	ret = confirm("<?php echo $lg_confirm_delete;?>");
		//	if (ret == false)
		//		return;
		//}
	
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("div_comment").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","../tutorsps/ajx_comment.php?id="+id+"&op="+op+"&delid="+delid+"&msg="+msg,true);
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
<body > 



<div id="div_comment">

		<div id="comment"  style="min-height:400px; height:400px; overflow-y:auto;">
<?php
		$i=0;
		$sql="select * from tutorial_sys where app='TUTORSPS_COMMENT' and pid='$id' order by id desc";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
				$na=ucwords(strtolower(stripslashes($row['author'])));
				$m=stripslashes($row['msg']);
				$xid=$row['id'];
				$uid=$row['uid'];
				$dt=$row['dt'];
				$ts=$row['ts'];
				$now=date('Y-m-d');
				$date_parts1=explode("-", $dt);
			    $date_parts2=explode("-", $now);

			
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
           			<font face="Verdana" color="#000000"><?php echo "$m";?></font><br>
					<font face="Verdana" color="#996600"><strong>By&nbsp;<?php echo "$na $new";?></strong><br><?php $dt=ago($ts);echo "$dt";?></font>
					<?php if((is_verify('ADMIN'))||($uid==$_SESSION['username'])){?>
						&lt;<a style="cursor:pointer" title="Remove" onClick="process_chat('delete','<?php echo $id;?>','<?php echo $xid;?>');">
                        <font face="Verdana" color="#FF0000" style="font-size:9px">remove</font></a>&gt;
					<?php } ?>
			</div>
     		
<?php } ?>
		</div> 
</div>

</form>
</body>
</html>
