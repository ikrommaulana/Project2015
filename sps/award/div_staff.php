<div style="font-size:11px; font-weight:bold; color:#333333; border-bottom:2px solid #666;padding:4px;">		            
            <a href="../award/staff_awd.php" target="_blank" style="color:#444444; text-decoration:none;" class="fbbig" >            	
				<strong><?php echo "Staff Award";?></strong>
            </a>
			<a href="#" title="Superb!!!"><img height="12" src="<?php echo $MYLIB;?>/img/gold_supered.png"></a> 
            <a href="#" title="Excellent!!"><img height="12" src="<?php echo $MYLIB;?>/img/gold_excelent.png"></a>
            <a href="#" title="Wow!"><img height="12" src="<?php echo $MYLIB;?>/img/gold_wow.png"></a>
            <a href="#" title="Notbad..."><img height="12" src="<?php echo $MYLIB;?>/img/gold_notbad.png"></a>	
</div>

	<div style="padding:4px; border-top:none;height:150px; min-height:150px; overflow-y: auto;background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg)">
       
        <?php 
			$yyy=date('Y');
			$mmm=date('m');
		?>

			<table width="100%" cellpadding="2" cellpadding="2">
            
<?php
			$sql="select * from award_staff where year='$yyy' order by id desc";
			$res3=mysql_query($sql)or die("query failed:".mysql_error());
			while ($row3=mysql_fetch_assoc($res3)){
				$xmon=$row3['month'];
				$xnam=ucwords(strtolower(stripslashes($row3['name'])));
				$xno=$row3['award_no'];
				$xdes=$row3['award_des'];
				$xuid=$row3['uid'];
				
				$sql="select * from usr where uid='$xuid'";
				$res2=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$img=$row2['file'];
				if($img!="")
					$img=$dir_image_user.$img;
				else
					$img="$MYLIB/img/defaultuser.png";
					
				if(!file_exists($img))
					$img="$MYLIB/img/defaultuser.png";


?>
			<tr>
						<td width="2%"  valign="top" id="myborderfull" align="center"  title="<?php echo $xdes;?>"></td>
						<td style="font-size:10px;" valign="top" ><?php echo "$xnam";?><br>
                              <div style="font-size:10px">
                                <?php if($xno==4){?>                            
                                <img src="<?php echo $MYLIB;?>/img/gold_supered.png" style="float:left "> <?php echo "$xmon";?> <?php echo $xdes;?>
                                <?php } elseif($xno==3){?>     
                                <img src="<?php echo $MYLIB;?>/img/gold_excelent.png" style="float:left "> <?php echo "$xmon";?> <?php echo $xdes;?>
                                <?php } elseif($xno==2){?>    
                                <img src="<?php echo $MYLIB;?>/img/gold_wow.png" style="float:left "> <?php echo "$xmon";?> <?php echo $xdes;?>
                                <?php } elseif($xno==1){?> 
                                <img src="<?php echo $MYLIB;?>/img/gold_notbad.png" style="float:left "> <?php echo "$xmon";?> <?php echo $xdes;?>
                                <?php } ?>                   
                            </div>                                                                               
						</td>
			</tr>
<?php } ?>
	</table>
     
	</div>