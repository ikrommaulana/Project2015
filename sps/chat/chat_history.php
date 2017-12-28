<?php 
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
verify("");


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

/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=10;
		$sqlmaxline="limit $curr,$MAXLINE";
	}
	elseif($MAXLINE=="All"){
		$sqlmaxline="";
	}
	else{
		$sqlmaxline="limit $curr,$MAXLINE";
	}
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by stu.id $order";
	else
		$sqlsort="order by $sort $order, name asc";
	
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
<div id="mytitlebg">MESSAGE HISTORY</div>
	<div style="background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg)" >
<?php
		$i=0;
		if($ONLINE_MSG_GLOBAL){
				$sql2="select count(*) from content where app='community_info'";
				$sql= "select * from content where app='community_info' order by id desc $sqlmaxline";
		}
		else{
				$sql2="select count(*) from content where app='community_info' and (sid=0 or sid=$sid)";
				$sql= "select * from content where app='community_info' and (sid=0 or sid=$sid) order by id desc $sqlmaxline";
		}
		$res=mysql_query($sql2,$link)or die("$sql:query failed:".mysql_error());
    	$row=mysql_fetch_row($res);
    	$total=$row[0];
		if(($curr+$MAXLINE)<=$total)
			$last=$curr+$MAXLINE;
		else
			$last=$total;
		$q=$curr;
	
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
				$i++;$q++;
				if($i>1){
?>
	<div style="border-top:none; border-bottom:1px solid #DDDDDD"></div>
<?php } ?>
			<div  style="font-size:9px; padding:5px 4px 5px 4px">
				<img src="../adm/imgresize.php?w=30&h=30&img=<?php echo $img;?>" width="30" height="30" style="float:left; ">
					<font face="Verdana" color="#000000"><?php echo "$m";?></font><br>
					<font face="Verdana" color="#996600"><strong>By&nbsp;<?php echo "$na $new";?></strong><br><?php $dt=ago($ts);echo "$dt";?></font>
			</div>
<?php } ?>
	</div>
    <?php include("../inc/paging.php");?>

</div> <!--content -->
</form>
</body>
</html>