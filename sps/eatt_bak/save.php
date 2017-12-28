<?php

include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");


function crd($strDateFrom,$strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
}


function multiexplode ($delimiters,$string) {
    
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}


/*if(isset($_REQUEST['add'])){
	
	$datelike = date('Y-m-%');
	$datelike2 = date("Y-m-%", strtotime("last month")) ;
	
	$lastmonth = date("Y-m-d", strtotime("first day of previous month"));
	$today = date("Y-m-d");
	
	$sqlworkday = "SELECT etc from type WHERE grp='attendance_set' and prm = 'School Day'";
	$resworkday = mysql_query($sqlworkday)or die("$sqlworkday query failed:".mysql_error());
	if(mysql_num_rows($resworkday) > 0){
		$rowworkday = mysql_fetch_row($resworkday);
		$workday = $rowworkday;
		$workdays = array_map('trim',explode(",", $workday[0]));	
	}else{
		//default if not set in system
		$workdays = array('Monday','Tuesday','Wednesday','Thursday','Friday');	
	}
	
	
	$sqlusr="SELECT uid, jobstart FROM usr WHERE status=0";
	$resusr=mysql_query($sqlusr)or die("$sqlusr query failed:".mysql_error());
	while($rowusr=mysql_fetch_array($resusr, MYSQL_NUM)){	
		
		$usr = $rowusr[0];
		$jobstart = $rowusr[1];
		if($lastmonth < $jobstart){
			$lastmonth = $jobstart;
		}
		
		$sqlatt = "SELECT d from stuatt where stu_uid='$usr' and (d like '$datelike' or d like '$datelike2')";
		$resultatt = mysql_query($sqlatt);
		
		
		
		$existeddate=array("");
		while ($row = mysql_fetch_row($resultatt)) {
			$existeddate[] = $row[0];
			//echo "a";				
		}
		
		//echo "<br>". var_dump($existeddate) ."<br>";
			
		$ar=array_diff(crd($lastmonth, $today), $existeddate);
		//echo "<br> ".var_dump($ar). "<br>";
	 
		foreach($ar as $q){
			//echo $q;echo $usr;echo "<br>";
			$dts = explode('-', $q);
			$year= $dts[0];
			
			$day = date("l", strtotime($q));
			if(in_array($day, $workdays)){
				$daytype = "WORKDAY";
			}else{
				$daytype = "RESTDAY";
				$clockin = "";
				$clockout = "";
				$ot = "0.00";
				$short = "0.00";
			}
			
			//echo $q;
			$sqlins="insert into stuatt(
						sch_id,
						stu_uid,year,d,sta,
						daytype,att_in,att_out,
						upd_att,ot,short,
						des,att_break,att_resume,ot_in,ot_out,usr_grp
					)
					values(
						(SELECT sch_id from usr WHERE stu.uid = '$usr'),
						'$usr','$year','$q','1', 
						'$daytype','','',
						now(),'','',
						'','','','','',''
					);"; 
				mysql_query($sqlins) or die(mysql_error());
			
		}
	}
	exit;
}*/

$uid = $_REQUEST['id'];
$datetime = $_REQUEST['time'];

$punchin = date("Y-m-d H:i:s", strtotime($datetime)); 

list($punchdate,$punchtime)=explode(' ',$punchin);
list($year,$month,$date)=explode('-',$punchdate);
list($hour,$minute,$second)=explode(':',$punchtime);

$sqlyear = "SELECT distinct year FROM `ses_stu` where stu_uid = '$uid' group by year desc ORDER BY year desc";
$resyear = mysql_query($sqlyear) or die(mysql_error());
$rowyear = mysql_fetch_row($resyear);
$year = $rowyear[0];


$clock="$hour:$minute";

// preg_split( "/ (.|:) /", $input );
			$sqlclockin = "SELECT etc from type WHERE grp='attendance_set' and prm = 'Clock-in'";
			$resclockin = mysql_query($sqlclockin)or die("$sqlclockin query failed:".mysql_error());
			if(mysql_num_rows($resclockin) > 0){
				$rowclockin = mysql_fetch_row($resclockin);
				$dbclockin = multiexplode(array(",",".","|",":"),$rowclockin[0]);
				$dbclockintime= array_map('trim',$dbclockin);		
				$maxinjam=$dbclockintime[0];
				$maxinmin=$dbclockintime[1];
			}else{
				//default if not set in system
				$maxinjam='09';
				$maxinmin='00';
			}
			
			
			$sqlclockout = "SELECT etc from type WHERE grp='attendance_set' and prm = 'Clock-out'";
			$resclockout = mysql_query($sqlclockout)or die("$sqlclockout query failed:".mysql_error());
			if(mysql_num_rows($resclockout) > 0){
				$rowclockout = mysql_fetch_row($resclockout);
				$dbclockout = multiexplode(array(",",".","|",":"),$rowclockout[0]);
				$dbclockouttime= array_map('trim',$dbclockout);		
				$minoutjam=$dbclockouttime[0];
				$minoutmin=$dbclockouttime[1];
			}else{
				//default if not set in system
				$minoutjam='18';
				$minoutmin='00';
			}
			
			

$clockminute = ($hour * 60) + $minute;
$clockinminutemax = ($maxinjam * 60) + $maxinmin;
$clockoutminutemin = ($minoutjam * 60) + $minoutmin;

$ot = "0.00";

//$short calculated in this if else apply for mysql insert only
if($clock < "13:00"){
	$clockin=$clock;
	
	if($clockminute > $clockinminutemax){
		$shortminute = $clockminute - $clockinminutemax;
	}
	$shours = floor($shortminute / 60);
	$sminutes = $shortminute % 60;
	$sminutes = str_pad($sminutes, 2 , "0", STR_PAD_LEFT);
	$short = "$shours.$sminutes";
	$sta = 1;
}else{
	$clockout=$clock;
	
	if($clockminute < $clockoutminutemin){
		$shortminute =  $clockoutminutemin - $clockminute;
		$shours = floor($shortminute / 60);
		$sminutes = $shortminute % 60;
		$sminutes = str_pad($sminutes, 2 , "0", STR_PAD_LEFT);
		$short = "$shours.$sminutes";
	}
	if($clockminute > $clockoutminutemin){
		$otminute = $clockminute - $clockoutminutemin;
		$ohr = floor($otminute / 60);
		$omt = $otminute % 60;
		$omt = str_pad($omt, 2 , "0", STR_PAD_LEFT);
		$ot = "$ohr.$omt";
		$short = '0.00';
	}
	$sta = 1;
}
/*
$clockoutshort = $clockout;
if($clockout>='18:00'){
	$clockoutshort = '18:00';
}*/

// $date_time_formatted;
//$date_time_formatted = implode("-",array_reverse(explode("/",$date)))." ".$time ;
	
	//Nak check jika dah ada rekod atau belum. Jika belum, setkan semua tak datang
	$sqlcekrekod2="select * from stuatt where d='$punchdate' and cls_code=(SELECT cls_code FROM ses_stu where stu_uid = '$uid' and year = '$year')";
	$rescekrekod2=mysql_query($sqlcekrekod2)or die("$sqlcekrekod2 query failed:".mysql_error());
	$numcekrekod2=mysql_num_rows($rescekrekod2);
	if($numcekrekod2<1){
	
	
		$sqltakhadir = "Select * from ses_stu where year = '$year' and cls_code = (SELECT cls_code FROM ses_stu where stu_uid = '$uid' and year = '$year') and stu_uid not in(Select stu_uid from stuatt where year = '$year' and cls_code = (SELECT cls_code FROM ses_stu where stu_uid = '$uid' and year = '$year') and d like '$punchdate');";
		$res=mysql_query($sqltakhadir)or die("$sqltakhadir query failed:".mysql_error());
	
		while($row=mysql_fetch_assoc($res)){
			
			$rschid=$row['sch_id'];
			echo $rstuid=$row['stu_uid'];
			$rclscode=$row['cls_code'];
			
			$sql="insert into stuatt(
						sch_id,
						stu_uid,year,d,sta,
						cls_code,
						daytype,
						upd_att
					)
					values(
						$rschid,
						'$rstuid','$year','$punchdate','0', 
						'$rclscode',
						'$daytype',
						now()
					);";
			
			mysql_query($sql)or die(mysql_error());
			//echo $sql . "<br>";
			}
			
		}
	
		$sqlcekrekod="select * from stuatt where d='$punchdate' and stu_uid='$uid'";
		$rescekrekod=mysql_query($sqlcekrekod)or die("$sqlcekrekod query failed:".mysql_error());
		$numcekrekod=mysql_num_rows($rescekrekod);
		if($numcekrekod>0){
			$rekod = mysql_fetch_assoc($rescekrekod);
			$clin  = $rekod['att_in'];
			$clout = $rekod['att_out'];
			
			
			
			//Check if it is clock in or clock out
			if($clockin != ""){
				if($clin!=""){
					list($clinh, $clinm)=explode(":",$clin);
					$clinx = ($clinh * 60) + $clinm;
				}
				if(($clin == "") or ($clockminute < $clinx)){
					if($clout != ""){
						list($h, $m)=explode(":",$clout);
						$mm = ($h * 60) + $m;
						if($mm < $clockoutminutemin){
							$shortm = $clockoutminutemin - $mm;
							//$short = "$shorth:$shortminutes";
						}
					}
					
					$shortminute = $shortminute + $shortm;
					$shours = floor($shortminute / 60);
					$sminutes = $shortminute % 60;
					$sminutes = str_pad($sminutes, 2 , "0",STR_PAD_LEFT);
					$short = "$shours.$sminutes";
					//echo $short;
					/*
					list($h1,$m1)=explode(":", $clockin);
					$mm1 = ($h1 * 60) + $m1;
					if($mm1 > $clockinminutemax){
						$shortm1 = $mm1 - $clockinminutemax;
						
					}
					$shortminutes = ($shortm1 + $shortm);
					$sh = floor($shortminutes / 60);
					$sm = $shortminutes % 60;
					$short = "$sh:$sm";
					*/
				}else{
					$short = $rekod['short'];
				}
			}else{
				if($clout!=""){
					list($clouth, $cloutm)=explode(":",$clout);
					$cloutx = ($clouth * 60) + $cloutm;
				}
				//echo "$cloutx<br>";
				//echo "$clockminute<br>";
				//echo "$clout<br>";
				if(($clout == "") or ($clockminute > $cloutx)){
					if($clin != ""){
						list($h, $m)=explode(":",$clin);
						$mm = ($h * 60) + $m;
						if($mm > $clockinminutemax){
							$shortm = $mm - $clockinminutemax;
							//$short = "$shorth:$shortminutes";
						}
						
					}
					$shortminute = $shortminute + $shortm;
					$shours = floor($shortminute / 60);
					$sminutes = $shortminute % 60;
					$sminutes = str_pad($sminutes, 2 , "0", STR_PAD_LEFT);
					$short = "$shours.$sminutes";
					
					
					
					
					/*
					list($h1,$m1)=explode(":", $clockout);
					echo "$h1:$m1";
					$mm1 = ($h1 * 60) + $m1;
					if($mm1 < $clockoutminutemin){
						$shortm1 = $clockinminutemax - $mm1;
						echo $shortm1;	
						$shortminutes = ($shortm1 + $shortm);
						$sh = floor($shortminutes / 60);
						$sm = $shortminutes % 60;
						$short = "$sh:$sm";
					}
					*/
				}else{
					$short = $rekod['short'];
					
				}
			}
			
			//echo $short;
			
			$sqlworkday = "SELECT etc from type WHERE grp='attendance_set' and prm = 'School Day'";
			$resworkday = mysql_query($sqlworkday)or die("$sqlworkday query failed:".mysql_error());
			if(mysql_num_rows($resworkday) > 0){
				$rowworkday = mysql_fetch_row($resworkday);
				$workday = $rowworkday;
				$workdays = array_map('trim',explode(",", $workday[0]));	
			}else{
				//default if not set in system
				$workdays = array('Monday','Tuesday','Wednesday','Thursday','Friday');	
			}
			
			$day = date("l", strtotime($punchdate));
			if(in_array($day, $workdays)){
				//$daytype = "WORKDAY";
			}else{
				//$daytype = "RESTDAY";
				$clockin = "";
				$clockout = "";
				$ot = "0.00";
				$short = "0.00";
			}
			
			
			$sql="update stuatt set sta='$sta',
				att_in=(case when (att_in > '$clockin' and '$clockin' <> '') then '$clockin' else (case when (att_in = '') then '$clockin' else att_in end) end),
				att_out=(case when (att_out > '$clockout') then att_out else '$clockout' end), upd_att=now(),
				ot=(case when (att_out > '$clockout') then ot else '$ot' end),short='$short', sta='1'
				where year='$year' and stu_uid='$uid' and d='$punchdate';";
			
		}else{
			$sqlworkday = "SELECT etc from type WHERE grp='attendance_set' and prm = 'School Day'";
			$resworkday = mysql_query($sqlworkday)or die("$sqlworkday query failed:".mysql_error());
			if(mysql_num_rows($resworkday) > 0){
				$rowworkday = mysql_fetch_row($resworkday);
				$workday = $rowworkday;
				$workdays = array_map('trim',explode(",", $workday[0]));
				
			}else{
				//default if not set in system
				$workdays = array('Monday','Tuesday','Wednesday','Thursday','Friday');	
			}
			
			$day = date("l", strtotime($punchdate));
			if(in_array($day, $workdays)){
				$daytype = "WORKDAY";
			}else{
				$daytype = "RESTDAY";
				$clockin = "";
				$clockout = "";
				$ot = "0.00";
				$short = "0.00";
			}
			
			$sql="insert into stuatt(
						sch_id,
						stu_uid,year,d,sta,
						cls_code,
						daytype,att_in,att_out,
						upd_att,ot,short
					)
					values(
						(SELECT sch_id from ses_stu WHERE stu_uid = '$uid' and year = '$year'),
						'$uid','$year','$punchdate','$sta', 
						(SELECT cls_code FROM ses_stu where stu_uid = '$uid' and year = '$year'),
						'$daytype','$clockin','$clockout',
						now(),'$ot','$short'
					);";
		}
		
		if(mysql_query($sql)){
			echo '1';
		}
		else{
			echo "x mysql_error();";
		}
?>