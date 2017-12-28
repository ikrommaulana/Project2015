<?php
		if($exam!=""){
			$pp="TT";
			$gg="TT";
			
			$fp=$exam."_m";
			$fg=$exam."_g";
			
			/** check if existing **/
			$sql="select $fp,$fg from hc_sub where uid='$uid' and year='$year' and subcode='$subcode'";
			$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
			if($row3=mysql_fetch_row($res3)){
				$pp=$row3[0];
				$gg=$row3[1];
			}
			/** check if lastyear **/
			if($lastexam!=""){
				$pp="TT";
				$gg="TT";
				$sql="select point,grade from exam where stu_uid='$uid' and year='$lastyear' and sub_code='$subcode' and examtype='$lastexam'";
				$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
				if($row3=mysql_fetch_row($res3)){
					$pp=$row3[0];
					$gg=$row3[1];
				}
			}
			$j++;
	}
	?>
	<td align=center <?php if($exam=="") echo "style=\"display:none\"";?> bgcolor="#FFFFFF" id="myborder">
		<input name="g<?php echo $j;?>" type="text" id="g<?php echo $j;?>" size="1" value="<?php echo $gg;?>" readonly style="background-color:#FFFFFF; border:none; ">
	</td>
	<td align=center <?php if($exam=="") echo "style=\"display:none\"";?> bgcolor="#FFFFFF" id="myborder">
		<select name="markah[]" id="markah[]" onChange="check_grade(this,<?php echo $j;?>)">
				<option value="<?php echo "$pp|$gg|$uid";?>" selected><?php echo $pp;?></option>
				<?php
				for($i=100;$i>=0;$i--){
						$sql="select * from grading where name='$grading' and point<=$i order by val desc limit 1";
						$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$xp=$row3['point'];
						$xg=$row3['grade'];
						echo "<option value=\"$i|$xg|$uid\">$i</option>";
				}
				?>
				<option value="TH|TH|<?php echo $uid;?>">TH</option>
				<option value="TT|TT|<?php echo $uid;?>">TT</option>
		</select>
	</td>