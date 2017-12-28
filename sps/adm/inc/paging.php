
	<div id="mytitle">
          <div style="float:left ">
		  		  <?php if($total>0) $c=$curr+1; else $c=$curr; echo "$c-$last OVER $total";?>
		  </div>
		  <div align="right">
		  	
		  	&nbsp;
			<?php if($curr>=$MAXLINE){?>
				<a href="#" onClick="gofirst(0)" title="go first">FIRST</a> |
			<?php } ?>
			<?php if($curr>=$MAXLINE){?>
			    <a href="#" onClick="goprev(<?php echo $curr-$MAXLINE;?>)">PREV</a> 
			<?php } ?>
			<?php if(($curr>=$MAXLINE)&&(($curr+$MAXLINE)<$total)){?>
			|
			<?php } ?>
			<?php if(($curr+$MAXLINE)<$total){?>
			    <a href="#" onClick="gonext(<?php echo $curr+$MAXLINE;?>)">NEXT</a> |
			<?php } ?>
			<?php if(($curr+$MAXLINE)<$total){?>
			    <a href="#" onClick="golast(<?php if(($total%$MAXLINE)==0) echo $total-$MAXLINE; else echo $total-($total%$MAXLINE);?>)" >
				LAST
				</a>
			<?php } ?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<select name="maxline" style="font-size:90%; color:#0000FF " onChange="document.myform.submit()">
				<?php echo "<option value=\"$MAXLINE\">$MAXLINE / Page</option>";?>
				<option value="90">90 / Page</option>
				<option value="80">80 / Page</option>
				<option value="70">70 / Page</option>
				<option value="60">60 / Page</option>
				<option value="50">50 / Page</option>
				<option value="40">40 / Page</option>
				<option value="30">30 / Page</option>
				<option value="20">20 / Page</option>
				<option value="10">10 / Page</option>
			</select>
			</div>
	</div>
