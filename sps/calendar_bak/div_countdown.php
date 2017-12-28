	<div style="font-size:11px; font-weight:bold; color:#444444; border-bottom:2px solid #666;padding:8px;">
		<img src="../img/alert14.png" height="10px">&nbsp;<?php echo $lg_reminder;?>
    </div>
	<div id="reminder" style="height:280px;min-height:280px; overflow-y: auto;background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg)">
            
    
        				<?php
						$adm=$_SESSION['username'];
                        $i=0;
                        $dt=date('Y-m',mktime(0,0,0,$month,1,$year));
                        $currdt=date('Y-m-d');
                        $sql="select * from calendar_event where dt>='$currdt' and isreminder=1 and (isprivate=0 or adm='$adm') order by dt asc";
                        $res=mysql_query($sql)or die("query failed:".mysql_error());
                        while($row=mysql_fetch_assoc($res)){
                                $evt=stripslashes($row['event']);
								$isprivate=$row['isprivate'];
								$tips=stripslashes($row['event']);
								if(strlen($evt)>30)
									$evt=substr($evt,0,30)."..";
                                $xdt=stripslashes($row['dt']);
								$diff=dateDiff($currdt,$xdt);
								$numweek=sprintf("%d",$diff/7);
								$numday=$diff%7;
                ?>
                	<div style="font-size:11px; color:#666; padding:5px 4px 5px 4px; cursor:pointer" title="<?php echo $tips;?>">
                    		 &bull;<b> 
							<?php if($isprivate){?><img src="<?php echo $MYLIB;?>/img/user12.png" height="10" title="Personal Reminder"><?php } ?>
							<?php echo "$evt";?><br>
							<div style="font-size:10px; color:#666;">&nbsp;&nbsp;<?php echo " $numweek $lg_week$lg_s $numday $lg_day$lg_s";?></div>
                            </b>
                    </div>
					
                <?php } ?>
                    
      </div>