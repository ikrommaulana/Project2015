	
	<input type="hidden" name="curr" value="<?php echo $curr;?>">
	<input name="sort"	 type="hidden" value="<?php echo $sort;?>">
	<input name="order"  type="hidden" value="<?php echo $order;?>">
	
	<div id="mytitle">
          <div style="float:left ">
		  		  <?php if($total>0) $c=$curr+1; else $c=$curr; echo "$c-$q $lg_over $total";?>
		  </div>
		  <div align="right">
		  	
		  	&nbsp;
			<?php if($MAXLINE>0){ ?>
				<?php if($curr>=$MAXLINE){?>
					<a style="cursor:pointer" onClick="clear_newwindow();gofirst(0)" title="go first">FIRST</a> |
				<?php } ?>
				<?php if($curr>=$MAXLINE){?>
					<a style="cursor:pointer" onClick="clear_newwindow();goprev(<?php echo $curr-$MAXLINE;?>)">PREV</a> 
				<?php } ?>
				<?php if(($curr>=$MAXLINE)&&(($curr+$MAXLINE)<$total)){?>
				|
				<?php } ?>
				<?php if(($curr+$MAXLINE)<$total){?>
					<a style="cursor:pointer" onClick="clear_newwindow();gonext(<?php echo $curr+$MAXLINE;?>)">NEXT</a> |
				<?php } ?>
				<?php if(($curr+$MAXLINE)<$total){?>
					<a style="cursor:pointer" onClick="clear_newwindow();golast(<?php if(($total%$MAXLINE)==0) echo $total-$MAXLINE; else echo $total-($total%$MAXLINE);?>)" >
					LAST
					</a>
				<?php } ?>
			<?php } ?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<select name="maxline" style="font-size:10px; color:#666; font-weight:bold; " onChange="clear_newwindow();document.myform.submit()">
				<?php echo "<option value=\"$MAXLINE\">$MAXLINE / $lg_page</option>";?>
				<option value="10">10 / <?php echo $lg_page;?></option>
				<option value="20">20 / <?php echo $lg_page;?></option>
				<option value="30">30 / <?php echo $lg_page;?></option>
				<option value="40">40 / <?php echo $lg_page;?></option>
				<option value="50">50 / <?php echo $lg_page;?></option>
				<option value="60">60 / <?php echo $lg_page;?></option>
				<option value="70">70 / <?php echo $lg_page;?></option>
				<option value="80">80 / <?php echo $lg_page;?></option>
				<option value="90">90 / <?php echo $lg_page;?></option>
				<option value="All"><?php echo $lg_all;?></option>
			</select>
			</div>
	</div>
