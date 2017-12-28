<?php 
	include_once('../etc/db');
	include_once('../etc//session.php');
	verify_admin();
	$username = $_SESSION['username'];
	$mobileid = $_SESSION['mobileid'];
	$userid = $_SESSION['userid'];
	
	$tel=$HTTP_GET_VARS['tel'];
	if($tel=="")
		$tel=$_POST['tel'];

/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr='0';
    $total=$_POST['total'];
    $MAXLINE=20;
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
<title>Untitled Document</title>

</head>

<body>

<?php
        $sql="select * from msg where tel='$tel' order by id desc";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $num=mysql_num_rows($res);
        $total=$num;
        if(($curr+$MAXLINE)<=$total)
                $last=$curr+$MAXLINE;
        else
                $last=$total;
				
		if($total==""){
                $sql="select count(*) from msg where  tel='$tel'";
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
		$sql="select * from msg where  tel='$tel' $sqlsort limit $curr,$MAXLINE";

        $res=mysql_query($sql)or die("query failed:".mysql_error());
		$q=$curr;

?> 

<table width="100%">
      <tr>
        <td width="100%"><strong>Message <?php echo "$tel ($last/$num)"?></strong></td>
      </tr>
<?php
        while($row=mysql_fetch_assoc($res)){
				$id=$row['id'];
                $cdate=$row['cdate'];
				$rdate=$row['rdate'];
                $msg=$row['msg'];
                $rs=$row['resp'];
				$sta=$row['status'];
                $q++;
                echo "<tr bgcolor=\"#FAFAFA\">";
                echo "<td>$q. $sta <br>Q:</span><font color=blue>$cdate</font><br><a href=\"p.php?p=sms/msgdet&id=$id\"><font color=blue>$msg</font></a></td>";
				echo "<tr>";
                echo "<tr >";
                echo "<td >A: <font color=green>$rdate <br>$rs<br><br></font></td>";

                echo "</tr>";
                if(($q%$MAXLINE)==0)
                       break;


	  }
	  mysql_free_result($res);
  ?>
</table>

<div id="myBorder"></div>
	<table width="100%" background="img/bgtitle.gif">
          <tr >
		  <td width="40%" >
		  <strong>
		  <?php if($total>0) $c=$curr+1; else $c=$curr; echo "$c-$last over $total";?>
		  </strong>
		  </td>
		  	<td align="right">
			<strong>
			<?php if($curr>=$MAXLINE){?>
				<a href="#" onClick="gofirst(0)" title="go first">First</a> |
			<?php } ?>
			<?php if($curr>=$MAXLINE){?>
			    <a href="#" onClick="goprev(<?php echo $curr-$MAXLINE;?>)" title="go previuos">Prev</a> 
			<?php } ?>
			<?php if(($curr>=$MAXLINE)&&(($curr+$MAXLINE)<$total)){?>
			|
			<?php } ?>
			<?php if(($curr+$MAXLINE)<$total){?>
			    <a href="#" onClick="gonext(<?php echo $curr+$MAXLINE;?>)" title="go next">Next</a> |
			<?php } ?>
			<?php if(($curr+$MAXLINE)<$total){?>
			    <a href="#" onClick="golast(<?php if(($total%$MAXLINE)==0) echo $total-$MAXLINE; else echo $total-($total%$MAXLINE);?>)" title="go last">
				Last
				</a>
			<?php } ?>
			</strong>
			</td>
          </tr>
	</table>
  <form name="formpage" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        	<input name="curr" type="hidden" id="curr" value="<?php echo $curr;?>">
            <input name="total" type="hidden" id="total" value="<?php echo $total;?>">
			
			<input name="p" type="hidden" id="p" value="sms/msghis">
			<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
			<input name="order" type="hidden" id="sort" value="<?php echo $order;?>">
			 <input name="tel" type="hidden" id="tel" value="<?php echo $tel;?>">		
        </form>
		
		
	
	
</body>
</html>
