<?php
        require_once("../etc/conndb.php");
        include_once('../etc/session.php');
        $username = $_SESSION['username'];
		$sort=$_POST['sort'];
        if($sort=="")
                $sort="gw_id";

        $direction=$_POST['direction'];
        if($direction=="")
                $direction="asc";
        $orderby=" order by $sort $direction";

        $sch=$_POST['sch'];

	$curr=$_POST['curr'];
        if($curr=="")
                $curr='0';

        $total=$_POST['total'];
        $max=$_POST['max'];
        if($max=="")
                $max='0';
        $min='0';
        $MAXLINE=$_POST['maxline'];
	if($MAXLINE==0)
		$MAXLINE=30;
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
function process(cur){
        document.form10.curr.value=cur;
        document.form10.submit();
}

function process2(cur){
        document.form11.curr.value=cur;
        document.form11.submit();
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<style type="text/css">
<!--
body {
	background-image: url(../img/bg_body.jpg);
}
.style1 {
	color: #0033CC;
	font-weight: bold;
	font-size: large;
}

-->
</style></head>

<body>
 <?php
	if($total==""){
                $sql="select count(*) from `ulog` order by id desc";
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

        $sql="select * from ulog order by id desc limit $curr,$MAXLINE";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
?>

        <strong> User Activity</strong><br>
          <table width="88%"  border="0" bgcolor="#CCCCFF">
            <tr bgcolor="#9999FF">
              <td width="7%">No</td>
              <td width="22%">Date (<?php echo "$last/$total";?>)</td>
			  <td width="16%">Username</td>
              <td width="12%">Action</td>
			  <td width="43%">Description</td>         
            </tr>
	<?php
		$q=$curr;	
		while($row=mysql_fetch_assoc($res)){
			$user=$row['username'];
			$mdate=$row['mdate'];
			$act=$row['ptype'];
			$info=$row['info'];
	
			if(($q++%2)==0)
				echo "<tr bgcolor=#BBBBBB>";
			else
				echo "<tr bgcolor=#CCCCCC>";
    			echo "<td>  <font face=verdana size=1>&nbsp;$q</td>";
			echo "<td>  <font face=verdana size=1>&nbsp;<font face=verdana size=1>$mdate<font></td>";
			echo "<td>  <font face=verdana size=1>&nbsp;<font face=verdana size=1>$user<font></td>";
			echo "<td>  <font face=verdana size=1>&nbsp;<font face=verdana size=1>$act</font></td>";
	    		echo "<td>  <font face=verdana size=1>&nbsp;<font face=verdana size=1>$info</font></td>";
			//echo "<td>  <font face=verdana size=1>&nbsp;$cmp_userid</td>";
 			//echo "<td>  <font face=verdana size=1>&nbsp;$cmp_ip</td>";
			//echo "<td>  <font face=verdana size=1>&nbsp;$cmp_port</td>";
  			echo "</tr>";
		  }
		  mysql_free_result($res);
		  mysql_close($link);
  ?>
</table>          
	<table width="40%">
    <tr>
      <td width="50%">
	  <?php if($curr>0 ){?>
			<form name="form10" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	      	  <div align="left">
 	  		  <input name="sch" type="hidden" id="sch" value="<?php echo $sch;?>">
			  <input name="dt" type="hidden" id="dt" value="<?php echo $dt;?>">
			  <input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
			  <input name="direction" type="hidden" id="direction" value="<?php echo $direction;?>">
		      	<input name="curr" type="hidden" id="curr" value="<?php echo $curr-$MAXLINE;?>">
                        <input name="total" type="hidden" id="total" value="<?php echo $total;?>">
                        <input type="button" name="button1" value="first" onClick="process(0)">
                        <input type="submit" name="Submit" value="<<">
			</div>
			</form>
		<?php }?>
	  </td>
      <td width="68%">
	  <?php if($last<$total){?>
		<form name="form11" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">   	  
		  <div align="left">
  		    <input name="sch" type="hidden" id="sch" value="<?php echo $sch;?>">
		    <input name="dt" type="hidden" id="dt" value="<?php echo $dt;?>">
			<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
			<input name="direction" type="hidden" id="direction" value="<?php echo $direction;?>">
        	<input name="curr" type="hidden" id="curr" value="<?php echo $curr+$MAXLINE;?>">
<input name="total" type="hidden" id="total" value="<?php echo $total;?>">
<input type="submit" name="Submit" value=">>">
<input type="button" name="button3" value="last" onClick="process2(<?php if(($total%$MAXLINE)==0) echo $total-$MAXLINE; else echo $total-($total%$MAXLINE);?>)">  
	</div>
		</form>
	<?php }?>
	  </td>
    </tr>
  </table>

</body>
</html>
