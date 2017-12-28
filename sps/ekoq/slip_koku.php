<?php
//160910 5.0.0 - update gui.. data kementrian
$vmod="v5.0.0";
$vdate="241210";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
$adm = $_SESSION['username'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
			
	$sql="select * from sch where id='$sid'";
    $res=mysql_query($sql)or die("$sql - failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $sname=$row['name'];

	$dts=$_POST['rdate'];
	if($dts=="")
		$dts=date('Y-m-d');
	$dte=$_POST['edate'];
	$kelab=$_POST['kelab'];
	
	$jawatan=$_POST['jawatan'];
	if($jawatan!="")
		list($posval,$pos)=explode("|",$jawatan);
	else
		$posval=0;
	
	$penglibatan=$_POST['penglibatan'];
	if($penglibatan!="")
		list($parval,$par)=explode("|",$penglibatan);
	else
		$parval=0;
		
	$pencapaian=$_POST['pencapaian'];
	if($pencapaian!="")
		list($achval,$ach)=explode("|",$pencapaian);
	else
		$achval=0;
		
	$status=$_POST['status'];
	$year=$_REQUEST['y'];
	if($year=="")
		    $year=date('Y');
	if($status=="")
		$status=0;
	$op=$_POST['op'];
	$id=$_POST['id'];
	$del=$_POST['del'];
	$uid=$_REQUEST['uid'];
	$attval=$_REQUEST['attval'];
	$attfull=$_REQUEST['attfull'];
	$attstu=$_REQUEST['attstu'];
	$par_des=$_REQUEST['par_des'];
	$ach_des=$_REQUEST['ach_des'];
	
	if($uid!=""){
		$sql="select * from stu where sch_id=$sid and uid='$uid'";
		$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $xname=ucwords(strtolower(stripslashes($row['name'])));
		$xic=$row['ic'];
		$file=$row['file'];
			
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$cname=$row2['cls_name'];
	}
		
//get teacher note
	
	                $sql11="select * from koq_note where uid='$uid' and year='$year'";
					$res11=mysql_query($sql11);
					if(mysql_num_rows($res11) > 0) 
					{   
						$row11=mysql_fetch_assoc($res11);
						$note=$row11['note'];
		            }
		
					else  $note ="";
								
						
						 
				   $sql10="select * from koq_stu where uid='$uid' and koq_type='10' and year='$year' order by total_val  desc LIMIT 0,1";
				   $res10=mysql_query($sql10)or die("query failed:".mysql_error());     
					$row10=mysql_fetch_assoc($res10);
					$bonus=$row10['total_val'];
										
	//get the highest sukan
	$sql9="select * from koq_stu where uid='$uid' and koq_type='3' and year='$year' order by total_val  desc LIMIT 0,1";
					$res9=mysql_query($sql9)or die("query failed:".mysql_error());
					$row9=mysql_fetch_assoc($res9);
					$att3=$row9['att_full'];	
					$pos3=$row9['pos_val'];
					$ach3=$row9['ach_val'];
					$par3=$row9['par_val'];
					$total3=$row9['total_val'];
					$koqname3=$row9['koq_name'];
	//get the highest kelab
	$sql8="select * from koq_stu where uid='$uid' and koq_type='2' and year='$year' order by total_val  desc LIMIT 0,1";
					$res8=mysql_query($sql8)or die("query failed:".mysql_error());
					$row8=mysql_fetch_assoc($res8);
					$att2=$row8['att_full'];	
					$pos2=$row8['pos_val'];
					$ach2=$row8['ach_val'];
					$par2=$row8['par_val'];
					$total2=$row8['total_val'];
					$koqname2=$row8['koq_name'];
					
	//get the highest pakaian beragam
	$sql7="select * from koq_stu where uid='$uid' and koq_type='1' and year='$year' order by total_val  desc LIMIT 0,1";
					$res7=mysql_query($sql7)or die("query failed:".mysql_error());
					$row7=mysql_fetch_assoc($res7);
					$att1=$row7['att_full'];	
					$pos1=$row7['pos_val'];
					$ach1=$row7['ach_val'];
					$par1=$row7['par_val'];
					$total1=$row7['total_val'];
					$koqname1=$row7['koq_name'];
	// get koq group 1
		$sql2="SELECT prm FROM type WHERE grp = 'koq_grp' and val = '1'";
			$res2=mysql_query($sql2)or die("query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$pbb=$row2['prm'];
			$val1=$row2['val'];
			// get koq group 2
			$sql3="SELECT prm FROM type WHERE grp = 'koq_grp' and val = '2'";
			$res3=mysql_query($sql3)or die("query failed:".mysql_error());
			$row3=mysql_fetch_assoc($res3);
			$k=$row3['prm'];
			$val3=$row3['val'];
			
			// get koq group 3
			$sql4="SELECT prm FROM type WHERE grp = 'koq_grp' and val = '3'";
			$res4=mysql_query($sql4)or die("query failed:".mysql_error());
			$row4=mysql_fetch_assoc($res4);
			$s=$row4['prm'];
			$val4=$row4['val'];
			
		
			
		// get highest mark
			/*$sql5="select * from koq_stu where uid='$uid' order by total_val  desc LIMIT 0,1";
				$res5=mysql_query($sql5)or die("query failed:".mysql_error());
				$row5=mysql_fetch_assoc($res5);
				$first=$row5['total_val'];
		
		// get second highest mark
			$sql6="select * from koq_stu  where uid='$uid' order by total_val desc LIMIT 1,1";
					$res6=mysql_query($sql6)or die("query failed:".mysql_error());
					$row6=mysql_fetch_assoc($res6);
					$second=$row6['total_val'];
					$total = ($first + $second)/2*/
		
		
		
?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
            </head>
            <script language="JavaScript">
			function process_form(operation){		
					ret = confirm("Save the info??");
					if (ret == true){
						document.myform.op.value='save';
						document.myform.submit();
					}
			}
        </script>
        
    <body>
    <form name="myform" method="post" action="slip_koku.php">
            <input type="hidden" name="p" value="slip_exam">
	        <input type="hidden" name="id" value="<?php echo $id;?>">
            <input type="hidden" name="sid" value="1">
            <input type="hidden" name="uid" value="<?php echo $uid;?>">
            <input type="hidden" name="clscode" value="">
            <input type="hidden" name="op">
            
    <div id="content">
    
    <div id="mypanel" class="printhidden">
        <div id="mymenu" align="center">
            <a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
            <a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
            <a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
        </div>
        
        <div id="mytable" align="right"><br>
    <a href="#" title="02/07/10">v5.0.0</a>
    </div>
    
    </div><!-- end mypanel --><div id="story" >
    <div id="mytitlebg" class="printhidden" style="color:#CCCCCC" align="right"></div>
    
    <div align="center" style="padding:5px ">

		<?php include ('../inc/school_header.php');?>
	 
    </div>
   <div id="mytitlebg" align="center"> <?php echo strtoupper($lg_laporan_koq)?><?php echo $f;?></div>
    <table  id="myborder" width="100%" >
          <td  width="50%" height="94" valign="top">	
                <table width="100%">
                  <tr >
            
                    <td width="30%" valign="top"><?php echo strtoupper($lg_name);?></td>
                    <td width="1%" valign="top">:</td>
                    <td width="70%"><?php echo strtoupper("$xname");?> </td>
                  </tr>
                  <tr >
                    <td><?php echo strtoupper($lg_ic_number);?></td>
                    <td>:</td>
            
                    <td><?php echo strtoupper("$xic");?></td>
                  </tr>
                  <tr>
                    <td><?php echo strtoupper($lg_class);?></td>
                    <td>:</td>
                    <td><?php echo strtoupper("$cname / $year");?> </td>
                  </tr>
                </table>
            
 </td>
        <td width="50%" valign="top">
                <table width="100%" >
                  <tr>
            
                    <td width="20%" valign="top"><?php echo strtoupper($lg_matric);?></td>
                    <td width="1%" valign="top">:</td>
                    <td width="79%"><?php echo strtoupper("$uid");?></td>
                  </tr>
                  <tr>
                    <td><?php echo strtoupper($lg_school);?></td>
                    <td>:</td>
            
                    <td><?php echo strtoupper("$sname");?></td>
                  </tr>
                  </tr>
                  </table>
        </td>
    </table>
    <table  id="myborder" width="100%" border="0" id="myborder">
      <tr id="mytitlebg">
        <td  id="myborder" align="center" width="261"><?php echo strtoupper($lg_aspect);?></td>
        <td  id="myborder" align="center" width="88"><?php echo strtoupper($lg_agihan);?></td>
        <td  id="myborder" align="center" width="300"><p><table><tr><?php echo strtoupper("I");?></br></tr>
		<tr><p><?php echo strtoupper("$pbb");?></p></br></tr>
          <tr><p><?php echo strtoupper("$koqname1");?></p></tr></table></td>
        <td  id="myborder" align="center" width="250"><table><tr><?php echo strtoupper("II");?></br></tr>
		<tr><p><?php echo strtoupper("$k");?></p></br></tr>
          <tr><p><?php echo strtoupper("$koqname2");?></p></tr></table></td>
        <td  id="myborder" align="center"width="250"><table><tr><?php echo strtoupper("III");?></br></tr>
		<tr><p><?php echo strtoupper("$s");?></p></tr>
         <tr><p><?php echo strtoupper("$koqname3");?></tr></table></td>
        <td id="myborder" width="200" align="center" ><?php echo strtoupper($lg_grade)?></td>
		<td id="myborder" width="200" align="center" ><?php echo strtoupper($lg_mark)?></td>
      </tr>
      <tr  id="myborder" align="center">
        <td id="myborder" align="center"><?php echo strtoupper("$lg_attendance");?></td>
        <td  id="myborder" align="center">50</td>
        <td  id="myborder" align="center"><?php echo "$att1";?></td>
        <td  id="myborder" align="center"><?php echo "$att2";?></td>
        <td  id="myborder" align="center"><?php echo "$att3";?></td>
        <td id="myborder" align="center">A</td>
		<td id="myborder" align="center">80-100</td>
      </tr>
      <tr  id="myborder" align="center">
        <td  id="myborder" align="center"><?php echo strtoupper($lg_position);?></td>
        <td  id="myborder" align="center">10</td>
        <td  id="myborder" align="center"><?php echo "$pos1";?></td>
        <td  id="myborder" align="center"><?php echo "$pos2";?></td>
        <td  id="myborder" align="center"><?php echo "$pos3";?></td>
        <td id="myborder" align="center">B</td>
		<td id="myborder" align="center">60-79</td>
      </tr>
      <tr  id="myborder" align="center">
        <td  id="myborder" align="center"><?php echo strtoupper($lg_participation);?></td>
        <td id="myborder" align="center">20</td>
        <td  id="myborder" align="center"><?php echo "$par1";?></td>
        <td  id="myborder" align="center"><?php echo "$par2";?></td>
        <td  id="myborder" align="center"><?php echo "$par3";?></td>
        <td id="myborder" align="center">C</td>
		<td id="myborder" align="center">40-59</td>
      </tr>
      <tr  id="myborder">
        <td  id="myborder" align="center"><?php echo strtoupper($lg_achievement);?></td>
        <td  id="myborder" align="center">20</td>
        <td  id="myborder" align="center"><?php echo "$ach1";?></td>
        <td id="myborder" align="center"><?php echo "$ach2";?></td>
        <td  id="myborder" align="center"><?php echo "$ach3";?></td>
        <td id="myborder" align="center">D</td>
		<td id="myborder" align="center">20-39</td>
      </tr>
      <tr  id="myborder">
        <td  id="myborder" align="center"><?php echo strtoupper($lg_total_mark);?></td>
        <td  id="myborder" align="center">100</td>
        <td  id="myborder" align="center"><?php echo "$total1";?></td>
        <td  id="myborder" align="center"><?php echo "$total2";?></td>
        <td  id="myborder" align="center"><?php echo "$total3";?></td>
        <td id="myborder" align="center">E</td>
		<td id="myborder" align="center">0-19</td>
      </tr >
</table>

<?php 
$array = array($total1, $total2, $total3);
natsort($array); // Sorts from highest to lowest
$highest = $array[0];
$second_highest = $array[1];
$total = ($highest + $second_highest)/2;
$subtotal = $total + $bonus ;
// get grade
if ( $subtotal<= 100 && $subtotal>= 80)
{
	$gred ='A';
}

if ( $subtotal<= 79 && $subtotal>= 60)
{
	$gred ='B';
}

if ( $subtotal<= 59 && $subtotal>= 40)
{
	$gred ='C';
}

if ( $subtotal<= 39 && $subtotal>= 20)
{
	$gred ='D';
}

if ( $subtotal<= 19)
{
	$gred ='E';
}

   ?>
 <table id="myborder" width="100%">  
 <tr id="myborder" >
        <td id="mytitlebg" align="right" ><?php echo strtoupper($lg_purata_2_terbaik);?> : </td>
        <td id="mytitlebg" width="50%">(<?php echo "$highest";?> + <?php echo "$second_highest";?>)/2 = <?php echo "$total";?> </td>
      </tr>
      <tr>

        <td id="mytitlebg" align="right"><?php echo strtoupper($lg_bonus);?> : </td>
        <td id="mytitlebg"  width="50%"><?php echo "$bonus";?></td>
      </tr>
        <td id="mytitlebg"  align="right"><?php echo strtoupper($lg_total);?> : </td>
        <td id="mytitlebg"  width="50%"><?php echo "$subtotal";?></td>
      </tr>

      <tr>
        <td id="mytitlebg"   align="right"><?php echo strtoupper($lg_grade);?> : </td>
        <td id="mytitlebg"  width="50%"><?php echo "$gred";?></td>
      </tr>
      <tr>
        <td id="mytitlebg" align="right"><?php echo strtoupper($lg_teacher_note);?> : </td>
        <td id="mytitlebg"  width="50%"><?php echo "$note";?></td>
      </tr>
</table>
<table  id="myborder" width="100%">
<tr  id="mytitlebg">
                <td id="myborder" width="33%" valign="top" align="">
                  <p>&nbsp;</p>
                  <p>--------------------------------<br><?php echo strtoupper($lg_signatory);?> <?php echo strtoupper($lg_class_teacher);?><br><br><br><br><br><br>
    </p></td>
                <td id="myborder" width="33%" valign="top"  align="">
                <p>&nbsp;</p>
                  <p>--------------------------------<br><?php echo strtoupper($lg_signatory);?> <?php echo strtoupper($lg_pengetua);?> </p>
                  <p><br>
                  <?php echo strtoupper($lg_date);?>:<br><br></br></br></br></br>---------------------- <br><br><br><br><br>
          </p></td>
                <td id="myborder" width="33%" valign="top"  align="">
                <p>&nbsp;</p>
                  <p>--------------------------------<br><?php echo strtoupper($lg_parent_signatory);?></p>
                  <p><br>
                    <?php echo strtoupper($lg_date);?>:<br><br>---------------------- <br><br><br><br><br>
            </p></td>
        </tr>
    </table>
    <!-- end signing -->
    
    </div> <!-- story -->
    
    
    </div> <!-- content -->
    
    </form>
    
    </body>
    </html>
