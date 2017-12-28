<?php
		$newfile=$_FILES['file1']['tmp_name'];
		
		require_once 'excel_reader2.php';
		$data = new Spreadsheet_Excel_Reader($newfile);
				
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
								$tel=$data->sheets[0]['cells'][$i][1];
								$name=$data->sheets[0]['cells'][$i][2];
								$extrakey=$data->sheets[0]['cells'][$i][3];
		}
 ?> 
