<?php
include_once('../etc/db.php');
include_once('etc/session_sms.php');

if (isset($_SESSION['username']))
	verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
else if(isset($_POST['xuser']))
	process_login();
else
	$login=$_REQUEST['login'];
	//exit;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- DW6 -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
		
<script language="JavaScript">
function process_form(){
	if(document.form1.xuser.value==""){
			alert("Sila masukkan katanama pengguna");
			document.form1.xuser.focus();
			return;
		}
		if(document.form1.xpass.value==""){
			alert("Sila masukkan katalaluan pengguna");
			document.form1.xpass.focus();
			return;
		}
		document.form1.submit();
}
</script>
</head>
<body > 

<?php include('inc/masthead.php')?>
 
<?php if(!isset($_SESSION['username'])){?>
 <div id="content">  
  <div id="story"  style=" background:none; border:none" >
  
  <br>
<br>
<br>
<br>
<br>

  		<div id="xx" style="margin:2% 30% 2% 35%" >
		<table border="0" cellpadding="0" cellspacing="0" >
          <tr>
            <td><img src="../img/t_11.gif" width="10" height="9" alt="" border="0"></td>
            <td background="../img/t_13.gif"><img src="../img/t_12.gif" width="6" height="9" alt="" border="0"></td>
            <td align="right" background="../img/t_13.gif"><img src="../img/t_14.gif" width="6" height="9" alt="" border="0"></td>
            <td><img src="../img/t_15.gif" width="10" height="9" alt="" border="0"></td>
          </tr>
          <tr valign="top">
            <td background="../img/t_fon_left.gif"><img src="../img/t_21.gif" width="10" height="6" alt="" border="0"></td>
            <td rowspan="2" colspan="2">
			<!-- in -->
					<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table border="0" width="300px">
				  <tr>
				  <td align="center"><img src="../img/logosps.png"><br>
				  <font size="+3" face="Palatino Linotype">E-SMS</font><br>
				  
				  </td>
				  </tr>
				  <tr>
				  <td  align="center" width="100%">
             			 <table  border="0" width="200px">
						 
                          <tr>
                            <td width="10%"><font color="#0000FF">Katanama</font></td>
							<td width="1%">:</td>
                            <td  ><input name="xuser" type="text" size="12"></td>
                          </tr>
                          <tr>
                            <td ><font color="#0000FF">Katalaluan</font></td>
							<td >:</td>
                            <td ><input name="xpass" type="password" size="12"></td>
                          </tr>
                          <tr>
                            <td ></td>
							 <td ></td>
                            <td >
                              <input type="button" name="Submit" value="Masuk" onClick="process_form()">
                            </td>
                          </tr>
                          <tr>
                            <td ></td>
							<td ></td>
                            <td ></td>
                          </tr>
                       
                      </table> 
					 
				
				  </td>
					
				  </tr>
				</table>
				<div align="center">
					<font color="#FF0000">
                              <?php 
								if($login=="0")
									echo "Invalid Login! Authorized personnel only.<br>Contact your administrator. TQ";
								?>
                    </font>
				</div>
					</form>
					
			
                <!-- /in -->
            </td>
            <td background="../img/t_fon_right.gif"><img src="../img/t_23.gif" width="10" height="6" alt="" border="0"></td>
          </tr>
          <tr valign="bottom">
            <td background="../img/t_fon_left.gif"><img src="../img/t_31.gif" width="10" height="7" alt="" border="0"></td>
            <td background="../img/t_fon_right.gif"><img src="../img/t_33.gif" width="10" height="7" alt="" border="0"></td>
          </tr>
          <tr>
            <td><img src="../img/t_41.gif" width="10" height="10" alt="" border="0"></td>
            <td background="../img/t_fon_bot.gif"><img src="../img/t_42.gif" width="6" height="10" alt="" border="0"></td>
            <td background="../img/t_fon_bot.gif" align="right"><img src="../img/t_44.gif" width="6" height="10" alt="" border="0"></td>
            <td ><img src="../img/t_45.gif" width="10" height="10" alt="" border="0"></td>
          </tr>
        </table>
		
		<div id="yy" >
			<font color="#999999" size="1"><strong>Best view with Mozilla Firefox resolution 1280x800 pixels</strong></font>
		</div>
		
		
		</div>
			
			
			
<br>
<br>
<br>
<br>
				
 <?php }else{  ?>
 
   <div id="panelleft">  	
	<?php include('inc/mymenu.php'); ?>
  </div>
  
<div id="content2"> 
<div id="mypanel">

</div>
<div id="story" style="font-size:12px ">
 <div id="mytitle">BULETIN SPS</div>
 
<?php 
	if($total==""){
		//$sql="select count(*) from media where $sqlpid $sqlapp $sqltyp $sqlcat $sqlsearch";
				$sql="select count(*) from media where sta=1";
                $res=mysql_query($sql,$link)or die("query failed:".mysql_error());
                $row=mysql_fetch_row($res);
                $total=$row[0];
                if($total=="")
                        $total=0;
    }
        if(($curr+$MAXLINE)<=$total)
               $last=$curr+$MAXLINE;
        else
               $last=$total;
        //$sql="select * from media where $sqlpid $sqlapp $sqltyp $sqlcat $sqlsearch $sqlsort limit $curr,$MAXLINE";
		if(is_verify("SUPERUSER"))
			$sql="select * from media where sta=1 order by id desc";
		else{
			$xsid=$_SESSION['sid'];
			if($xsid==0)
				$sql="select * from media where sta=1 and access like '%$syslevel%' order by id desc";
			else
				$sql="select * from media where sta=1 and access like '%$syslevel%' and (sid=0 or sid=$xsid) order by id desc";				
		}
        $res=mysql_query($sql)or die("query failed:".mysql_error());
		$q=$curr;
        while($row=mysql_fetch_assoc($res)){
				$id=$row['id'];
				$name=$row['usr'];
				//$img=$row['thumb'];
				$usr=$row['usr'];
				$des=$row['des'];
				$msg=$row['msg'];
				$tit=$row['tit'];
				$xtyp=$row['typ'];
				$dt=$row['dt'];
				$filename=$row['file'];
				$sta=$row['sta'];
				$r=$row['rate'];
				$rate=$r/20;
				$rate=round($rate);
					
				$des = htmlspecialchars($des, ENT_QUOTES);
				
				if($q++%2==0)
					$bgc="bgcolor=#FAFAFA";
				else
					$bgc="";
	?>
<table width="100%" >
            <tr >
             <td width="95%" valign="top">
			 	<table width="100%"  border="0">
                <tr>
                  <td><font color="#003399"><strong><?php echo "$tit";?></strong></font></td>
                </tr>
              </table>
				<div id="myborder_bottom"></div>
				<table width="100%"  border="0">
                <tr>
                  	<td><font size="1"><?php echo "By: $usr";?></font></td>
                  	<td valign="bottom" align="right"><font size="1"><?php echo "$dt";?></font><font size="2" color="#009900"></font>
				  	</td>
                </tr>
              </table>
			  <table width="100%"  border="0">
			  <tr>
				<td><font size="2" color="#009900"><?php echo "$des";?></font><br></td>
			  </tr>
			</table>
			</td>
            </tr>
</table>
	<?php } ?>

 
 



 
 
 <?php } ?>      
    </div> 
	<!--end story--> 
  </div> 
  <!--end content--> 
   <?php include('../inc/site_footer.php');?>
</div> 
<!--end pagecell1--> 
</body>
</html>
