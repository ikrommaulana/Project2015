<?php if (!isset($_SESSION['username'])) {?>

		<table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><img src="../img/t_11.gif" width="10" height="9" alt="" border="0"></td>
            <td background="img/t_13.gif"><img src="../img/t_12.gif" width="6" height="9" alt="" border="0"></td>
            <td align="right" background="img/t_13.gif"><img src="../img/t_14.gif" width="6" height="9" alt="" border="0"></td>
            <td><img src="../img/t_15.gif" width="10" height="9" alt="" border="0"></td>
          </tr>
          <tr valign="top">
            <td background="img/t_fon_left.gif"><img src="../img/t_21.gif" width="10" height="6" alt="" border="0"></td>
            <td rowspan="2" colspan="2">
			<!-- in -->
                <table  border="0">
                  <tr>
                    <td align="center" valign="top"><strong>Login Sekolah</strong> <img src="../img/logosps.gif"> </td>
                  </tr>
                  <tr>
                    <td valign="top">
					<div id="myborder"></div><br>
             			 <table  border="0">
                        <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                          <tr>
                            <td width="10%">Username</td>
							<td width="1%">:</td>
                            <td  ><input name="username" type="text" id="username" size="16"></td>
                          </tr>
                          <tr>
                            <td >Password</td>
							<td >:</td>
                            <td ><input name="password" type="password" id="password" size="16"></td>
                          </tr>
                          <tr>
                            <td ></td>
							 <td ></td>
                            <td >
                              <input type="submit" name="Submit" value="Masuk">
                            </td>
                          </tr>
                          <tr>
                            <td ></td>
							<td ></td>
                            <td ><font color="#FF0000">
                              <?php 
								if($login=="0")
									echo "Invalid Login!";
								?>
                             </font><a href="mailto:admin@islah.edu.my"></a></td>
                          </tr>
                        </form>
                      </table> 
					
					  </td>
                  </tr>
                  
                </table>
                <!-- /in -->
            </td>
            <td background="img/t_fon_right.gif"><img src="../img/t_23.gif" width="10" height="6" alt="" border="0"></td>
          </tr>
          <tr valign="bottom">
            <td background="img/t_fon_left.gif"><img src="../img/t_31.gif" width="10" height="7" alt="" border="0"></td>
            <td background="img/t_fon_right.gif"><img src="../img/t_33.gif" width="10" height="7" alt="" border="0"></td>
          </tr>
          <tr>
            <td><img src="../img/t_41.gif" width="10" height="10" alt="" border="0"></td>
            <td background="img/t_fon_bot.gif"><img src="../img/t_42.gif" width="6" height="10" alt="" border="0"></td>
            <td background="img/t_fon_bot.gif" align="right"><img src="../img/t_44.gif" width="6" height="10" alt="" border="0"></td>
            <td ><img src="../img/t_45.gif" width="10" height="10" alt="" border="0"></td>
          </tr>
        </table>

  
 

<?php } ?>