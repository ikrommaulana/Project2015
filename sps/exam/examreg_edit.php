<?php 
//110412-locking exam
//110626 - patch for attamimi.. sistem still can be use for other

			
if($newfile!=""){// then amik dari upload
				//require_once 'excel_reader2.php';
				//$data = new Spreadsheet_Excel_Reader($newfile);
				//echo "$newfile";
				$xx="";
				for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
										$c1=$data->sheets[0]['cells'][$i][1];
										$c2=$data->sheets[0]['cells'][$i][2];
										$c3=$data->sheets[0]['cells'][$i][3];
										$c1=str_replace("\"","",$c1);
										//echo "$c1-$c2-$c3<br>";
										if($c1==$stuuid){
											$pp=$c2;
											$nn=1;
										}
				}
				
}

			echo "<td id=myborder align=center><input name=\"g$q\" type=\"text\" id=\"g$q\" size=\"2\" value=\"$gg\" readonly></td>";
			
			if($exam=="")
				$disabled="disabled";

			echo "<td  id=myborder align=center>";
			/*echo "<select name=\"markah[]\" id=\"markah[]\" onChange=\"check_grade(this,$q)\" $disabled>";
			if($nn>0)
				echo "<option value=\"$pp|$gg|$stuuid\" selected>$pp</option>";
			else
				echo "<option value=\"TT|TT|$stuuid\" selected>TT</option>";

			if($gradingtype==0){
				$sql="select * from grading where name='$gradingset' order by val desc limit 1";
				$res4=mysql_query($sql)or die("$sql failed:".mysql_error());
				$row4=mysql_fetch_assoc($res4);
				$maxpoint=$row4['val'];
				if($maxpoint=="")
					$maxpoint=-1;
				for($i=$maxpoint;$i>=0;$i--){
						$sql="select * from grading where name='$gradingset' and point<=$i order by val desc limit 1";
						$res4=mysql_query($sql)or die("$sql failed:".mysql_error());
						$row4=mysql_fetch_assoc($res4);
						$xp=$row4['point'];
						$xg=$row4['grade'];
						echo "<option value=\"$i|$xg|$stuuid\">$i</option>";
				}
			
				$sql="select * from grading where name='$gradingset' order by val desc";
				$res4=mysql_query($sql)or die("$sql failed:".mysql_error());
				while($row4=mysql_fetch_assoc($res4)){
							$xp=$row4['point'];
							$xg=$row4['grade'];
							if(!is_numeric($xp)){
								if($xp==$xg)
									echo "<option value=\"$xp|$xg|$stuuid\">$xg</option>";
							}
				}
			}
			if($gradingtype==1){
						$sql="select * from grading where name='$gradingset' order by val desc";
						$res4=mysql_query($sql)or die("$sql failed:".mysql_error());
						while($row4=mysql_fetch_assoc($res4)){
							$xp=$row4['point'];
							$xg=$row4['grade'];
							echo "<option value=\"$xp|$xg|$stuuid\">$xp</option>";
						}
			}
			
			echo "</select>";*/

$sqlmax="select * from grading where name='$gradingset' order by val desc limit 1";
$resmax=mysql_query($sqlmax)or die("$sqlmax query failed:".mysql_error());
$rowmax=mysql_fetch_assoc($resmax);
$max=$rowmax['val'];

//if($sid==2){
//$max=10;
$char=10;
//}elseif($sid==3){
//$max=10;
//$char=10;
//}elseif($sid==1){
//$max=100;
//$char=10;
//}

?>
			<input name="markah[]" type="text" id="markah<?php echo $q;?>" size="5" value="<?php echo $pp;?>" style="font-size:16px; font-weight:bold; color:blue;" onKeyUp="process_mark(<?php echo $q;?>)" maxlength="<?php echo $char;?>">



<input name="max[]" id="max<?php echo $q;?>" type="hidden" value="<?php echo $max;?>">
<input name="stuuid[]" type="hidden" value="<?php echo $stuuid;?>">
			</td>
