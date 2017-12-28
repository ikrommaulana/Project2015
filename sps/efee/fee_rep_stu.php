<?php
//$vdate="120227"; //list by year intake
$vdate="120316"; //icon statement and excell
$vdate="120605"; //update sql excel n cek paid
$vmod="v6.1.1";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include_once('../inc/xgate.php');
verify('ADMIN|AKADEMIK|KEUANGAN');

		$adm = $_SESSION['username'];
		$xfee=$_REQUEST['xfee'];
		$xuid=$_REQUEST['xuid'];
		
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		
		$fee=$_REQUEST['fee'];
		if($fee!=""){
			$sqlfee="and fee='$fee'";
			$sql="select * from type where grp='yuran' and prm='$fee'";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           	$row=mysql_fetch_assoc($res);
            $feetype=$row['val'];
		}
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$ssname=stripslashes($row['sname']);
			$simg=$row['img'];
			$namatahap=$row['clevel'];
            $issemester=$row['issemester'];	
			$startsemester=$row['startsemester'];				  
		}

$year=$_POST['year'];
if($year==""){
		$year=date('Y');
		if(($issemester)&&(date('n')<$startsemester))
			$year=$year-1;
		$xx=$year+1;
		$sesyear="$year/$xx";	
	
}else{
		$sesyear="$year";
}
$year=$sesyear;
			
		$sqlyear="and year='$year'";
		
		$op=$_POST['op'];
		$feeid=$_POST['feeid'];
		$feeval=$_POST['feeval'];
		if($op=="save"){
				for ($i=0; $i<count($feeid); $i++) {
					$sql="update feestu set val=$feeval[$i],rm=$feeval[$i],adm='$adm',dtm=now() where id=$feeid[$i]";
					mysql_query($sql)or die("query failed:".mysql_error());
				}
		}
		$checker=$_POST['checker'];
		if($op=="reprocess"){
				for ($i=0; $i<count($checker); $i++){
						$FFUID=$checker[$i];$FFSID=$sid;$FFYEAR=$year;
						include('feeprocess.php');
				}
		}
		if($op=="block"){
			for ($i=0; $i<count($checker); $i++) {
					$sql="select isblock from stu where uid='$checker[$i]'";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$isb=$row['isblock'];
					if($isb){
						$sql="update stu set isblock=0 where uid='$checker[$i]'";
						mysql_query($sql)or die("query failed:".mysql_error());
					}else{
						$sql="update stu set isblock=1 where uid='$checker[$i]'";
						mysql_query($sql)or die("query failed:".mysql_error());
					}
			}
		}
		
		
		$clslevel=$_POST['clslevel'];
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['name']);
			$clslevel=$row['level'];
		}
		if($clslevel!="")
			$sqlclslevel="and ses_stu.cls_level='$clslevel'";

		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name_matrik_ic -")==0)
			$search="";
		if($search!=""){
			$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
			$sqlclscode="";
			$sqlclslevel="";
			$clsname="";
			$clscode="";
			$clslevel="";
		}
			
/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=30;
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
<script language="javascript">
function open_fee(fn){
		document.myform.xfee.value=fn;
		document.myform.submit();
}
function open_uid(fn){
		document.myform.xuid.value=fn;
		document.myform.submit();
}
function clearwin(){
	document.myform.action="";
	document.myform.target="";
}
function process_form(action){
	var ret="";
	var cflag=false;
	
		if(action=='block'){
			for (var i=0;i<document.myform.elements.length;i++){
					var e=document.myform.elements[i];
					if ((e.id=='checker')&&(e.name!='checkall')){
							if(e.checked==true)
								cflag=true;
							else
								allflag=false;
					}
			}
			if(!cflag){
				alert('Please checked student to LOCK/UNLOCK');
				return;
			}
			ret = confirm("Are you sure want to LOCK/UNLOCK??");
			if (ret == true){
				document.myform.op.value=action;
				document.myform.submit();
			}
			return;
		}else if(action=='reprocess'){
			ret = confirm("WARNING!.. This will reculculate all the unpaid record. Reprocess this record??");
			if (ret == true){
				document.myform.op.value=action;
				document.myform.submit();
			}
		}else{
			ret = confirm("Save the record??");
			if (ret == true){
				document.myform.op.value=action;
				document.myform.submit();
			}
		}
		return;
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="op">
	<input type="hidden" name="xfee">
	<input type="hidden" name="xuid">
    <?php 
	$curryear=date('Y');
	if($year<$curryear)
		$sqlstatus="and (stu.status=6 or stu.status=3)";
	else
		$sqlstatus="and stu.status=6";
		
	if(($clslevel=="")&&($year==$curryear)){
    	$sql="select * from stu where sch_id=$sid $sqlstatus and intake<='$year'
		 $sqlisyatim $sqlisstaff $sqliskawasan
			 $sqlishostel $sqlisfakir $sqlsearch";
	}else{
		$sql="select * from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid
		 $sqlstatus and intake<='$year' and year='$year' 
		 $sqlclscode $sqlclslevel  $sqlisyatim $sqlisstaff $sqliskawasan $sqlishostel $sqlisfakir $sqlsearch";
	}
	?>
	<input type="hidden" name="sql" value="<?php echo $sql;?>">    

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="clearwin();window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="clearwin();process_form('save');" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
    <a href="../efee/excel-feeset.php?<?php echo "clslevel=$clslevel&clscode=$clscode";?>&year=<?php echo $year;?>&sid=<?php echo $sid;?>" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/excel.png"><br>Excel</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>    
	<a href="#" onClick="clearwin();process_form('block')" id="mymenuitem"><img src="../img/lock.png"><br>BLOCK</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="clearwin();process_form('reprocess')" id="mymenuitem"><img src="../img/option.png"><br>REPROCESS</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="clearwin();document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>

		<div align="right" >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>   <!-- end right -->            
</div>
<div id="mytabletitle" align="right"  class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">
		   <select name="sid" onchange="clearwin();document.myform.clscode.value='';document.myform.year[0].value='';document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_school -</option>";
			else
                echo "<option value=$sid>$ssname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['sname']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
			}									
?>
        </select> 
		  <select name="year" onchange="clearwin();document.myform.year.value='';document.myform.clscode[0].value='';document.myform.submit();">
<?php
            echo "<option value=$year>$lg_session $sesyear</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						echo "<option value=\"$s\">$lg_session $s</option>";
			}				  
?>
          </select>        
		<select name="clslevel" onchange="clearwin();document.myform.clscode[0].value='';document.myform.search.value='';document.myform.submit();">
<?php    
		if($clslevel=="")
            	echo "<option value=\"\">- $lg_all $lg_level -</option>";
		else
			echo "<option value=$clslevel>$namatahap $clslevel</option>";
		$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
        	$s=$row['prm'];
            echo "<option value=$s>$namatahap $s</option>";
        }
		if($clslevel!="")
            	echo "<option value=\"\">- $lg_all $lg_level -</option>";

?>
		</select>
         <select name="clscode" onchange="clearwin();document.myform.search.value='';document.myform.submit();">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- $lg_select $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from cls where sch_id=$sid and code!='$clscode' order by level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=stripslashes($row['name']);
						$a=$row['code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
					if($clscode!="")
            			echo "<option value=\"\">- $lg_all $lg_class -</option>";

			?>
                </select>                

				<input name="search" type="text" id="search" size="30" onMouseDown="clearwin();document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- $lg_name_matrik_ic -"; else echo "$search";?>"> 
				
                <input type="button" name="Submit" value="View" onClick="clearwin();document.myform.submit()" ><br>
</div>   <!-- end right -->     
<div id="story" >

<div id="mytitle2"><?php echo "$lg_student_fee_report";?> - <?php echo "$year";?></div>
<!--<div id="scroll" style="overflow-x: auto;">-->
<?php

			$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran'";
			$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
			$numfeeitem=mysql_num_rows($res2);
?>
<table cellspacing="0" <?php if($numfeeitem>30) echo "width=\"1500px\"";?>>
	<tr>
		<td class="mytableheader printhidden" style="border-right:none;" width="1%" rowspan="2"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
		<td class="mytableheader" style="border-right:none;" width="1%" align="center" rowspan="2"><?php echo strtoupper($lg_no);?></td>
		<td class="mytableheader" style="border-right:none;" width="2%" align="center" rowspan="2">
		<?php echo strtoupper($lg_intake);?></td>
        <td class="mytableheader" style="border-right:none;" width="2%" align="center" rowspan="2">
		<?php echo strtoupper($lg_matric);?></td>
		<td class="mytableheader" style="border-right:none;" width="1%" align="center" rowspan="2">
        	<a href="#" onClick="clearwin();formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>
		<td class="mytableheader" style="border-right:none;" width="10%" align="center" rowspan="2">
        	<a href="#" onClick="clearwin();formsort('name','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_name);?></a></td>
		<td class="mytableheader" style="border-right:none;" width="2%" align="center" rowspan="2"><?php echo strtoupper($lg_class);?></td>
        <td class="mytableheader printhidden" style="border-right:none;" width="2%" align="center" rowspan="2">&nbsp;</td>
<?php 
		$sql="select * from type where grp='feetype' order by val";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xf=$row['prm'];
			$xx=$row['val'];
			$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$xx";
			$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
			$rc=mysql_num_rows($res2);
			if($rc>0){
?>
		<td class="mytableheader" style="border-right:none;" width="20%" align="center" colspan="<?php echo $rc;?>"><?php echo strtoupper("$xf");?></td>
<?php } } ?>
		<td class="mytableheader" width="2%" align="center" colspan="4">TOTAL</td>
	</tr>
	
	<tr>
<?php 
		$sql="select * from type where grp='feetype' order by val";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xf=$row['prm'];
			$xx=$row['val'];
			$sql="select feeset.name,type.etc from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val='$xx' order by type.idx";
			$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
				$xf=$row2['etc'];
				$nm=$row2['name'];
?>
		<td id="mytabletitle" style="border-right:none; border-top:none;" width="2%" align="center" ><a href="#" title="<?php echo strtoupper("$nm");?>"><?php echo strtoupper("$xf");?></a></td>
<?php }}?>
			<td id="mytabletitle" style="border-right:none; border-top:none;" width="2%" align="center">TOTAL</td>
			<td id="mytabletitle" style="border-right:none; border-top:none;" width="2%" align="center">PAID</td>
			<td id="mytabletitle" style="border-right:none; border-top:none;" width="2%" align="center">ADVANCE</td>
			<td id="mytabletitle" style="border-top:none;" width="2%" align="center">BALANCE</td>
	</tr>
	
<?php
	$curryear=date('Y');
	if($year<$curryear)
		$sqlstatus="and (stu.status=6 or stu.status=3)";
	else
		$sqlstatus="and stu.status=6";
		
	if(($clslevel=="")&&($year==$curryear)){
    	$sql="select count(*) from stu where sch_id=$sid $sqlstatus and intake<='$year'
		 $sqlisyatim $sqlisstaff $sqliskawasan
			 $sqlishostel $sqlisfakir $sqlsearch";
	}else{
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid
		 $sqlstatus and intake<='$year' and year='$year' 
		 $sqlclscode $sqlclslevel  $sqlisyatim $sqlisstaff $sqliskawasan $sqlishostel $sqlisfakir $sqlsearch";
	}
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	if(($clslevel=="")&&($year==$curryear))
		$sql="select * from stu where sch_id=$sid  $sqlstatus and intake<='$year' 
		$sqlisyatim $sqlisblock $sqlisstaff $sqliskawasan $sqlisfakir $sqlishostel $sqlsearch $sqlsort $sqlmaxline";
	else
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and
		 (stu.status=6 or stu.status=3) and intake<='$year' and year='$year'  
		$sqlclscode $sqlclslevel $sqlisyatim $sqlisblock $sqlisstaff $sqliskawasan $sqlisfakir 
		$sqlishostel $sqlsearch $sqlsort $sqlmaxline";
	
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$sex=$row['sex'];
		$intake=$row['intake'];
		$sx=$lg_sexmf[$sex];
		$isblock=$row['isblock'];
		$name=ucwords(strtolower(stripslashes($row['name'])));
		$refname=$name;
		//if(strlen($name)>22)
		//	$name=substr($name,0,20)."..";
		
		$sql="select * from ses_stu where stu_uid='$uid' and sch_id=$sid and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$cls=$row2['cls_code'];
			//$clslevel=$row2['cls_level'];
			$ccode=$row2['cls_code'];
		}else
			$cls=$lg_none;
			/**
		if($cls=='Tiada'){
			$sql="update stu set intake='2012' where uid='$uid' and sch_id=$sid";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
		}else{
			
			$sql="update stu set intake='2011' where uid='$uid' and sch_id=$sid";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
		}**/
		
		
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
			
		$bg="$bglyellow";
		
		if($isblock)
			$bgb="$bglred";
		else
			$bgb="";
			
		$totalpaid=0;
		$totalunpaid=0;
		$totalamount=0;
?>

	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
				<td id=myborder style="border-right:none; border-top:none" class="printhidden" ><input id="checker" type=checkbox name=checker[] value="<?php echo "$uid";?>" onClick="check(0)" ></td>
              	<td id=myborder style="border-right:none; border-top:none" align="center"><?php echo "$q";?></td>
                <td id=myborder style="border-right:none; border-top:none" align="center"><?php echo "$year";?></td>
              	<td id=myborder style="border-right:none; border-top:none" align="center" bgcolor="<?php echo "$bgb";?>"> 
                	<a href="../efee/<?php echo "$FN_FEECFG.php?uid=$uid&sid=$sid&year=$year&year$year";?>" target="_blank" title="Config">
					<?php echo "$uid";?></a></td>
			  	<td id=myborder style="border-right:none; border-top:none" align="center"><?php echo "$sx";?></td>
              	<td id=myborder style="border-right:none; border-top:none">
					<?php echo "<a href=\"../efee/$FN_FEEPAY.php?uid=$uid&year=$year&sid=$sid\" title=\"$refname\"\ target=_blank>$name</a>";?></td>
				<td id=myborder style="border-right:none; border-top:none" align="center"><?php echo "$cls";?></td>
                <td id="myborder" style="border-right:none; border-top:none;" align="center" class="printhidden">
                	<a  href="../efee/feestu_statement.php?uid=<?php echo "$uid&sid=$sid&year=$year";?>" title="Statement" target="_blank">
                    <img src="../img/printer12.png"></a></td>
                
<?php
		$sql="select * from type where grp='feetype' order by val";
		$res5=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row5=mysql_fetch_assoc($res5)){
			$xf=$row5['prm'];
			$xx=$row5['val'];
			$sql="select feeset.name,type.etc from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val='$xx' order by type.idx";
			$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
				$fee=$row2['name'];
				$feesta="";
				$sql="select * from feestu where uid='$uid' and fee='$fee' and ses='$year'";
				$res8=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				if($row8=mysql_fetch_assoc($res8)){
					$feeid=$row8['id'];
					$feeval=$row8['val'];
					$feesta=$row8['sta'];
					if(($feesta==0)&&($feeval>0)){
						//make second cek;
						$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$fee' and isdelete=0";
						$res9=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$foundpaid=mysql_num_rows($res9);
						if($foundpaid==0){
							$bgl=$bglred;//red
							$totalunpaid=$totalunpaid+$feeval;
						}else{
							$totalpaid=$totalpaid+$feeval;
							$feesta=1;
						}
					}
					elseif($feesta==1)
						$totalpaid=$totalpaid+$feeval;
				}else{
						//$feeval="?";
						//make second cek;
						$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$fee' and isdelete=0";
						$res9=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$foundpaid=mysql_num_rows($res9);
						if($foundpaid==0){
							$feeval="?";
						}else{
							$totalpaid=$totalpaid+$feeval;
							$feesta=1;
						}  
				}
				if($feesta!=1){
					if($fee==$xfee){
						if($feeval=="?")
							$feeval=-1;
						echo "<td id=myborder style=\"border-right:none; border-top:none\" align=\"center\" bgcolor=\"$bgl\"><input type=text value=\"$feeval\" name=\"feeval[]\" size=1></td>";
						echo "<input type=\"hidden\" name=\"feeid[]\" value=\"$feeid\">";
					}
					elseif($uid==$xuid){
						if($feeval=="?")
							$feeval=-1;
						echo "<td id=myborder style=\"border-right:none; border-top:none\" align=\"center\" bgcolor=\"$bgl\"><input type=text value=\"$feeval\" name=\"feeval[]\" size=1></td>";
						echo "<input type=\"hidden\" name=\"feeid[]\" value=\"$feeid\">";
					}
					else
						echo "<td id=myborder style=\"border-right:none; border-top:none\" align=\"center\" bgcolor=\"$bgl\">$feeval</td>";
				}else{
						echo "<td id=myborder style=\"border-right:none; border-top:none\" align=\"center\" bgcolor=\"$bgl\">$feeval</td>";
				}
				$bgl="";
				$xxx="";

	}  }
		$totalamount=$totalpaid+$totalunpaid;
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='ADVANCE' and isdelete=0";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$advance=$row2[0];
		else
			$advance=0;
		
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='RECLAIM' and isdelete=0";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$reclaim=$row2[0];
		else
			$reclaim=0;
		$totaladvance=$advance+$reclaim; //reclaim negative so kena +
		$totalbalance=$totalamount-$totaladvance-$totalpaid;
?>
		<td id=myborder style="border-right:none; border-top:none" align="right"><?php echo number_format($totalamount,2,'.',',');?></td>
		<td id=myborder style="border-right:none; border-top:none" align="right"><?php echo number_format($totalpaid,2,'.',',');?></td>
		<td id=myborder style="border-right:none; border-top:none" align="right"><?php echo number_format($totaladvance,2,'.',',');?></td>
		<td id=myborder style="border-right:none; border-top:none" align="right"><?php echo number_format($totalbalance,2,'.',',');?></td>

	
</tr>
 
<?php  

} //if(($tahap!="")&&(clscode!=""))
?>
        
</table>

<!--</div><!-- scroll -->
<?php include("../inc/paging.php");?>
</div></div>

</form>	


</body>
</html>
