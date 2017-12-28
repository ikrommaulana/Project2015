<?php
//110610 - upgrade gui
//120315 - upgrade delete and user
//130313 - upgrade gui
$vmod="v6.0.0";
$vdate="130313";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
ISACCESS("sarpras",1);
$adm=$_SESSION['username'];
$xid=$_REQUEST['xid'];
$op=$_REQUEST['op'];
$year=$_REQUEST['year'];
$category=$_REQUEST['cate'];

if($xid!=""){
   $sql2="select * from asset_indo where id='$xid'";
    $res2=mysql_query($sql2) or die(mysql_error());
    $row2=mysql_fetch_assoc($res2);
	$category=$row2['category'];
	$subcategory=$row2['subcategory'];
	$name=$row2['item'];
	$q1=$row2['q1'];
	$q2=$row2['q2'];
	$q3=$row2['q3'];
	$q4=$row2['q4'];
	$q5=$row2['q5'];
	$q6=$row2['q6'];
	$q7=$row2['q7'];
	$q8=$row2['q8'];
	$q9=$row2['q9'];
	$q10=$row2['q10'];
	$q11=$row2['q11'];
	$q12=$row2['q12'];
		
}

if($op=='save'){
    $subcat=$_REQUEST['subcategory'];
    $name=$_REQUEST['name'];
    $q1=$_REQUEST['q1'];
    $q2=$_REQUEST['q2'];
    $q3=$_REQUEST['q3'];
    $q4=$_REQUEST['q4'];
    $q5=$_REQUEST['q5'];
    $q6=$_REQUEST['q6'];
    $q7=$_REQUEST['q7'];
    $q8=$_REQUEST['q8'];
    $q9=$_REQUEST['q9'];
    $q10=$_REQUEST['q10'];
    $q11=$_REQUEST['q11'];
    $q12=$_REQUEST['q12'];
    if($xid!=""){
	
	$sqledit="update asset_indo set category='$category',subcategory='$subcat',item='$name',
	q1='$q1',q2='$q2',q3='$q3',q4='$q4',q5='$q5',q6='$q6',q7='$q7',q8='$q8',q9='$q9',q10='$q10',q11='$q11',q12='$q12',
	adm='$adm',ts=now() where id='$xid'";
	mysql_query($sqledit)or die("$sqledit query failed:".mysql_error());
	
	$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
	?><script language="javascript">top.document.myform.submit();window.close();top.$.fancybox.close();</script><?php
	
    }else{
        $sqlin="insert into asset_indo(year,regdt,category,subcategory,item,
	q1,q2,q3,q4,q5,q6,q7,q8,q9,q10,q11,q12,
	adm,ts)values('$year',now(),'$category','$subcat','$name',
	'$q1','$q2','$q3','$q4','$q5','$q6','$q7','$q8','$q9','$q10','$q11','$q12',
	'$adm',now())";
	mysql_query($sqlin)or die("$sqlin query failed:".mysql_error());
	
	$f="<font color=blue>&lt;SUCCESSFULY SAVE&gt;</font>";
	?><script language="javascript">top.document.myform.submit();window.close();top.$.fancybox.close();</script><?php
    }
    
}
if($op=='delete'){
    $sqldel="delete from asset_indo where id='$xid'";
    mysql_query($sqldel)or die("$sqldel query failed:".mysql_error());
	
	$f="<font color=blue>&lt;SUCCESSFULY DELETE&gt;</font>";
	?><script language="javascript">top.document.myform.submit();window.close();top.$.fancybox.close();</script><?php
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>

<title>SPS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    
   <script language="javascript">

function process_form(action){
	var ret="";
	var cflag=false;
	
	if(action=='save'){
		
                if(document.myform.name.value==""){
			alert("Please insert name");
			document.myform.name.focus();
			return;
		}
                
		ret = confirm("Are you sure want to SAVE??");
		if(ret==true){
			document.myform.op.value=action;
			document.myform.submit();
		
		
                }
                return;
	}
	if(action=='delete'){
		ret = confirm("Are you sure want to DELETE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
}
</script> 
</head>

<body >
<form name=myform method="post" action="">
	<input type="hidden" name="xid" value="<?php echo $xid;?>">
	<input name="op" type="hidden" value="">
    <input name="delid" type="hidden">
<div id="content">
	<div id="mypanel">
		<div id="mymenu" align="center">
				<!--<a href="#" onClick="document.myform.xid.value='';document.myform.submit()" id="mymenuitem"><img src="../img/new.png"><br>New</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>-->
				<a href="#" onClick="process_form('save')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php if($xid!=""){?>
				<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<?php }?>
				<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();top.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
		<div align="right">
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
	</div> <!-- end mypanel-->
<div id="story">
<div id="mytitlebg">ASSET INFORMATION <?php echo $f;?></div>
<table width="100%" style="font-size:12px">
	<tr>
		<td>
		
		
	<table width="100%" id="mytitle" cellspacing="0">
      <tr style="cursor:default" onMouseOver="this.bgColor='#FAFAFA';" onMouseOut="this.bgColor='';">
        <td width="40%">Kategori</td>
        <td width="60%">
            <input type="text" name="category" size='45%' value="<?php echo $category;?>" readonly>
		 
        </td>
      </tr>
      <tr>
        <td width="40%">Sub Kategori</td>
        <td width="60%">
		 <select name="subcategory">
				<?php 
					if($subcategory!="")
								echo "<option value=\"$subcategory\">$subcategory</option>";
					else
								echo "<option value=\"\">- $lg_select -</option>";
                                                                
					$sql="select * from type where grp='assest_sub' and prm!='$subcategory' and code='$category' and val='1' order by idx asc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$s=$row['prm'];
								echo "<option value=\"$s\">$s</option>";
					}
				?>
		</select>
                <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="+" onClick="newwindow('../adm/prmcolum.php?grp=assest_sub',0)">
        	<?php } ?>
        </td>
      </tr>
      <tr>
        <td width="40%">Nama</td>
        <td width="60%"><input type="text" size=45%' name="name" value="<?php echo $name;?>"></td>
      </tr>
      
      
        <?php
        $n=0;
            $sql="select * from type where grp='asset_col' and code='$category' and val='1' and idx!='1' order by idx asc";
            $res=mysql_query($sql) or die(mysql_error());
            while($row=mysql_fetch_assoc($res)){
                $list=$row['prm'];
                $etc=$row['etc'];
                if($etc=="2")
		          $xdes="(Kebutuhan)";
                $n++;
		$q="q$n";
                ?>
                <tr>
                <td width="40%"><?php echo "$list $xdes";?></td>
                <td width="6%"><input type="text" name="<?php echo $q;?>"
		value="<?php if($q=='q1'){echo $q1;}
		if($q=='q2'){echo $q2;}
		if($q=='q3'){echo $q3;}
		if($q=='q4'){echo $q4;}
		if($q=='q5'){echo $q5;}
		if($q=='q6'){echo $q6;}
		if($q=='q7'){echo $q7;}
		if($q=='q8'){echo $q8;}
		if($q=='q9'){echo $q9;}
		if($q=='q10'){echo $q10;}
		if($q=='q11'){echo $q11;}
		if($q=='q12'){echo $q12;}?>"></td>
                </tr>
            <?php }
        
        ?>
      
</table>
</div>
</form>
</body>
</html>