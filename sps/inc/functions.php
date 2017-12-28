<?php
/** culculate remainding days/hr/min/sec
$startdate="2011-04-22 21:38:50"; 
$enddate="2011-04-29 21:38:50"; 

$diff=strtotime($enddate)-strtotime($startdate); 
echo "diff in seconds: $diff<br/>\n<br/>\n"; 

// immediately convert to days 
$temp=$diff/86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day 

// days 
$days=floor($temp); echo "days: $days<br/>\n"; $temp=24*($temp-$days); 
// hours 
$hours=floor($temp); echo "hours: $hours<br/>\n"; $temp=60*($temp-$hours); 
// minutes 
$minutes=floor($temp); echo "minutes: $minutes<br/>\n"; $temp=60*($temp-$minutes); 
// seconds 
$seconds=floor($temp); echo "seconds: $seconds<br/>\n<br/>\n"; 

echo "Result: {$days}d {$hours}h {$minutes}m {$seconds}s<br/>\n"; 
echo "Expected: 7d 0h 0m 0s<br/>\n";
**/
function ago($datefrom,$dateto=-1)
{
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
