<?php
$vmod="v6.0.0";
$vdate="111203";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
$adm = $_SESSION['username'];
verify("");

		$f=$_REQUEST['f'];
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
			
		if($sid>0){
			$sql="select * from sch where id=$sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            $sqlsid="and sch_id=$sid";		  
		}
	
		$name=addslashes($_POST['name']);
		$month=$_POST['month'];
		$nomonth=$_POST['nomonth'];
		$note=addslashes($_POST['note']);
		$uid=$_POST['uid'];
		$award=$_POST['award'];
		$opx=$_POST['opx'];
		$xid=$_REQUEST['xid'];
		if($opx=='save'){
			$sql="insert into award_staff(dt,tm,uid,name,month,nomonth,year,award_no,award_des,sid,adm,ts)
				values(now(),now(),'$uid','$name','$month','$nomonth','$year','$award','$note',sid,'$adm',now())";
			mysql_query($sql)or die("$sql query failed:".mysql_error());
		}
		if($opx=='delete'){
			$sql="delete from award_staff where id=$xid";
			mysql_query($sql);
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
		$sqlsort="order by id $order";
	else
		$sqlsort="order by $sort $order, id desc";
		


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>ISIS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $MYOBJ;?>/popwin/sample.css" />
<script type="text/javascript" src="<?php echo $MYOBJ;?>/popwin/popup-window.js"></script>

<script language="JavaScript">
function process_save(op)
{
		var cflag=false;
		for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.id=='award')){
						if(e.checked==true)
                               cflag=true;
                }
		}
		if(!cflag){
				alert('Please checked the award');
				return;
		}
		document.myform.opx.value=op;
		document.myform.submit();
}
function process_delete(op)
{
		ret = confirm("Delete this??");
		if (ret == true){
			document.myform.opx.value=op;
			document.myform.submit();
		}
}
function myin(id,sta){
		id.style.backgroundColor='#999999';
		id.style.color='#FFFFFF';
		id.style.cursor='pointer';
}
function myout(id,sta){
		id.style.backgroundColor='';
		id.style.cursor='default';
		id.style.color='#000000';
}

</script>
</head>

<body >

<form name="myform" method="post">
 <input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
 <input name="order" type="hidden" id="order" value="<?php echo $order;?>">
 <input name="opx" type="hidden">
 <input name="xid" type="hidden">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refesh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="window.close();top.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
</div> <!-- end mymenu -->

<div id="viewpanel" align="right" >
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		<select name="year" id="year" onChange="document.myform.submit();">
				<?php
					echo "<option value=$year>$year</option>";
					$sql="select * from type where grp='session' and prm!='$year' order by val desc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						$v=$row['val'];
						echo "<option value=\"$s\">$s</option>";
					}
					mysql_free_result($res);					  
				?>
        </select>
		<select name="sid" id="sid" onchange="document.myform.submit();">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_all -</option>";
			else
                echo "<option value=$sid>$sname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				if($sid!=0){
					echo "<option value=\"0\">- Semua $lg_sekolah -</option>";
				}
			}							  
			
?>
              </select>	  
			
</div><!-- end viewpanel -->


</div> 
<!-- end mypanel -->
<div id="story">
<div id="mytitle2">Staff Award <?php echo $year;?> : <?php echo $sname;?></div>

<table width="100%" cellspacing="0" cellpadding="4">
	<tr>
         	<td class="mytableheader" style="border-right:none;" width="2%"align="center">NO</td>
			<td class="mytableheader" style="border-right:none;" width="5%"align="center">
            <a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort">STAFF ID</a></td>
            <td class="mytableheader" style="border-right:none;" width="20%">
            &nbsp;<a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort">NAME</a></td>
<?php	
	$sql="select * from month order by no";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$name=$row['name'];
?>
			<td class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo $name;?></td>
<?php }?>
	</tr>
	<?php	
	$sql="select * from usr where id>0 $sqlsid $sqljobsta $sqltamat $sqlsearch $sqlsort";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$uid=$row['uid'];
			$name=strtoupper(stripslashes($row['name']));
			
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
			<tr bgcolor="<?php echo $bg;?>"  onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" >
              	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
			  	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$uid";?></td>
              	<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$name";?></td>
<?php	
	$sql="select * from month order by no";
	$res2=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row2=mysql_fetch_assoc($res2)){
			$month=$row2['name'];
			$nomonth=$row2['no'];
			
			$sql="select * from award_staff where uid='$uid' and nomonth='$nomonth' and year='$year'";
			$res3=mysql_query($sql)or die("query failed:".mysql_error());
			$row3=mysql_fetch_assoc($res3);
			$des=$row3['award_des'];
			$no=$row3['award_no'];
			$xid=$row3['id'];
			if($no>3)
				$img="<img src=\"$MYLIB/img/gold_supered.png\" title=\"$des\">";
			else if($no>2)
				$img="<img src=\"$MYLIB/img/gold_excelent.png\" title=\"$des\">";
			else if($no>1)
				$img="<img src=\"$MYLIB/img/gold_wow.png\" title=\"$des\">";
			else if($no>0)
				$img="<img src=\"$MYLIB/img/gold_notbad.png\" title=\"$des\">";
			else
				$img="";
			
?>
			<td class="myborder" style="border-right:none; border-top:none;"
            	align="center" onMouseOver="myin(this,1)" onMouseOut="myout(this,1)"
					<?php if((ISACCESS("award",0))&&($img=="")){?> 
						onclick="document.myform.name.value='<?php echo addslashes($name);?>';
									document.myform.month.value='<?php echo $month;?>';
                                    document.myform.nomonth.value='<?php echo $nomonth;?>';
                                    document.myform.uid.value='<?php echo $uid;?>';
									popup_show('popup', 'popup_drag', 'popup_exit', 'screen-center',0,0);
									document.myform.evtnote.focus();"
					<?php } ?>  
                 >
				<?php echo $img;?>
                <?php if((is_verify('ADMIN'))&&($img!="")){?>
				 		 <a hef="#" onclick="document.myform.xid.value='<?php echo $xid;?>';return process_delete('delete');">
                         <img src="<?php echo $MYLIB;?>/img/close12.png" height="9" title="Delete"></a>
                <?php } ?>
                </td>
<?php }?>
            </tr>
<?php }  ?>
 </table>
 
 <!-- ***** Popup Window **************************************************** -->

<div class="sample_popup"     id="popup" style="display: none;">
	<div class="menu_form_header" id="popup_drag">
        <img class="menu_form_exit"   id="popup_exit" src="<?php echo $MYOBJ;?>/popwin/close.gif" alt="" />
                Set Award :
                <input type="text"   name="name" size="40" style="background-color:#000000; color:#FFFFFF; border:none">
                <input type="hidden" name="month">
                <input type="hidden" name="nomonth">
                <input type="hidden" name="uid">
        </div>
        <div class="menu_form_body">
                <label style="color:#FFF">Description</label>
                <table>
                  <tr>
                    <td>
                        <textarea name="note" rows="4" cols="46"></textarea><br>
                        <label style="cursor:pointer; color:#FFF"><input type="radio" value="4" name="award" id="award">
                        <img src="<?php echo $MYLIB;?>/img/gold_supered.png"> Superb!!!! </label>
                        <label style="cursor:pointer; color:#FFF"><input type="radio" value="3" name="award" id="award">
                        <img src="<?php echo $MYLIB;?>/img/gold_excelent.png"> Excellent!!! </label>
                        <label style="cursor:pointer; color:#FFF"><input type="radio" value="2" name="award" id="award">
                        <img src="<?php echo $MYLIB;?>/img/gold_wow.png"> Wow!! </label>
                        <label style="cursor:pointer; color:#FFF"><input type="radio" value="1" name="award"id="award">
                        <img src="<?php echo $MYLIB;?>/img/gold_notbad.png"> Notbad! </label>
                        <br><br>
                        <input type="button" style="width:50%;"onClick="process_save('save')" value="Save"/>
                    </td>
                  </tr>
                </table>
        </div></div>
</div></div>
</form>
</body>
</html>