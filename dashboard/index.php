<?php
require_once("include/checklogin.php");
require_once('include/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="refresh" content="600">
        <title>SimpleHouseSolutions.com - Dashboard</title>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="css/dashboard.css"/>
        <link rel="stylesheet" type="text/css" href="css/dashboard_menu.css"/>
        <link rel="stylesheet" type="text/css" href="js/tigra_calendar/calendar.css">
        <script type="text/javascript" src="js/tigra_calendar/calendar_us.js"></script>
        <script type="text/javascript">

			function changeCriteria() {
				var follow_up_date = document.getElementById('follow_up_date').value;
				var owner = document.getElementById('owner').value;
				var search = document.getElementById('search').value;
				window.location.href = 'index.php?follow_up_date=' + follow_up_date + '&owner=' + owner + "&search=" + search;
			}

        </script>
    </head>
    <body>    
        <div id="header"><?php require "header.inc.php"; ?></div>
        <div id="menu"><?php require "menu.inc.php"; ?></div>
        <div id="content">
            <!-- Begin Content-->

            <p />
            <div>
                <input class="button" type="button" value="Open Lead" onclick="window.location = 'editLead.php'" />
            </div>

			<?php
			$username = $_SESSION['username'];
			$fullname = $_SESSION['fullname'];
			if (isset($_GET['owner'])) {
				$username = $_GET['owner'];
			}
			?>

            <p />
            <form method="post" action="dashboardHelper.php">
                <table class="grid">
                    <tr><td colspan="13"><h3>Unassigned Leads</h3></td></tr>
                    <tr>
                        <th></th>
                        <th>Actions</th>
                        <th>Lead Type</th>
                        <th>First Name</th>
                        <th>Last Name</th>
					   <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Home/Office Phone</th>
                        <th>Cell Phone</th>
                        <th>Lead Strength</th>
                        <th>Status</th>
                        <th>Age (Minutes)</th>
                    </tr>
					<?php
					$result = $mysqli->query("SELECT * FROM leads WHERE USERNAME='Not Assigned' ORDER BY date_added ASC") or die(mysql_error());
					if ($result->num_rows == 0) {
						?>
						<tr><td colspan="13" align="center">No unassigned leads</td></tr>
						<?php
					}
					while ($row = mysqli_fetch_array($result)) {
						foreach ($row AS $key => $value) {
							$row[$key] = stripslashes($value);
						}
						?>
						<tr>
							<td class="center"><input name="leads[]" type="checkbox" value="<?= $row['LEAD_ID'] ?>" /></td>
							<td class="center"><a href="editLead.php?lead_id=<?= $row['LEAD_ID'] ?>"><img src='images/edit.png' alt='Edit Lead' title='Edit Lead' /></a></td>
							<td><?= stripslashes($row['LEAD_TYPE']) ?></td>
							<td><?= stripslashes($row['FIRST_NAME']) ?></td>
							<td><?= stripslashes($row['LAST_NAME']) ?></td>
							<?php if (stripslashes($row['LEAD_TYPE']) == "Buyer") { ?>
							<td></td>
							<td><?= stripslashes($row['SEARCH_CITY']) ?></td>
							<td align="center"><?= $row['SEARCH_STATE'] ?></td>
							<?php } else { ?>
							<td><?= stripslashes($row['ADDRESS_1']) ?></td>
							<td><?= stripslashes($row['CITY']) ?></td>
							<td align="center"><?= $row['STATE'] ?></td>
							<?php } ?>
							<td><?= $row['OFFICE_PHONE'] ?></td>
							<td><?= $row['CELL_PHONE'] ?></td>
							<td class="<?= $row['PRIORITY'] ?>"><?php if ($row['PRIORITY'] == "NoContract") {
						echo "No Contract";
					} else {
						echo $row['PRIORITY'];
					} ?></td>
							<td><?= $row['STATUS'] ?></td>
							<td><?php echo intval(((time() - strtotime($row['DATE_ADDED'])) / 60)) ?></td>
						</tr>
	<?php
}
?>
                    <tr>
                        <td colspan="13" style="padding:10px" align="center">
                            <label for="move_to">Move To:</label>
                            <input id="move_to_new" name="move_to" size="10" />
                            <script type="text/javascript">
								var f_cal = new tcal({
									'controlname': 'move_to_new'
								});
                            </script>
                            &nbsp;&nbsp;&nbsp;
                            <label for="assign_to">New Owner:</label>
                            <select name="assign_to">
                                <option value=""></option>
                                <option value="Not Assigned">Not Assigned</option>
								<?php
								if ($session->isAdmin() || $session->isMaster()) {
									$result = $mysqli->query("SELECT * FROM users WHERE fullname!='' AND userlevel > 0 ORDER BY fullname ASC") or die(mysql_error());
									while ($row = mysqli_fetch_array($result)) {
										foreach ($row AS $key => $value) {
											$row[$key] = stripslashes($value);
										}
										?>
										<option value="<?= $row['username'] ?>"><?= $row['fullname'] ?></option>
										<?php
									}
								} else {
									?>
									<option value="<?= $username ?>"><?= $fullname ?></option>
	<?php
}
?>
                            </select>
                            &nbsp;&nbsp;&nbsp;
                            <input class="button" type="submit" name="submit" value="Submit" />
                        </td>
                    </tr>
                </table>
            </form>
            <br />

            <p />
            <table class="grid">
                <tr><td align="center">
                        <label for="follow_up_date">Follow-Up Date:</label>
						<?php
						$follow_up_date = date("m/d/Y");
						if (isset($_GET['follow_up_date']) && $_GET['follow_up_date'] != "") {
							$follow_up_date = date("m/d/Y", strtotime($_GET['follow_up_date']));
						} else if (isset($_GET['follow_up_date']) && $_GET['follow_up_date'] == "") {
							$follow_up_date = "";
						}
						?>
                        <input name="follow_up_date" id="follow_up_date" size="10"  value="<?= $follow_up_date ?>" onChange="changeCriteria()" />
                        <script type="text/javascript">
							var f_cal = new tcal({
								'controlname': 'follow_up_date'
							});
                        </script>
                        &nbsp;&nbsp;&nbsp;
                        <label for="owner">Lead Owner:</label>
                        <select id="owner" name="owner" onChange="changeCriteria()">
							<?php
							if ($session->isAdmin() || $session->isMaster()) {
								$result = $mysqli->query("SELECT * FROM users WHERE fullname!='' AND userlevel > 0 ORDER BY fullname ASC")
									or die(mysql_error());
								while ($row = mysqli_fetch_array($result)) {
									foreach ($row AS $key => $value) {
										$row[$key] = stripslashes($value);
									}
									?>
									<option value="<?= $row['username'] ?>" <?if($row['username']==$username) echo "selected=\"selected\""?>><?= $row['fullname'] ?></option>
									<?php
								}
							} else {
								?>
								<option value="<?= $username ?>"><?= $fullname ?></option>
	<?php
}
?>
                        </select>
                        &nbsp;&nbsp;&nbsp;
                        <label for="search">Search:</label>
                        <input type="text" name="search" id="search" size="30" value="<?php if (isset($_GET['search'])) echo $_GET['search']; ?>" onchange="changeCriteria()" />
                        <input class="button" type="button" name="submit" value="Submit" onlick="changeCriteria()" />
                        <input class="button" type="button" name="reset" value="Reset" onclick="window.location = 'index.php'" />
                        </form>
                    </td></tr>
            </table>

			<?php
			$size = 20;
			$link = "index.php?owner=$username";
			$orderby = "PRIORITY,FOLLOW_UP_DATE";
			if (isset($_GET['orderby'])) {
				$orderby = $_GET['orderby'];
				$link .= "&orderby=" . $orderby;
			}
			$dir = "ASC";
			if (isset($_GET['dir'])) {
				$dir = $_GET['dir'];
				$link .= "&dir=" . $dir;
			}
			if (isset($_GET['search'])) {
				$search = $_GET['search'];
			}
			if ($follow_up_date !== "") {
				if ($follow_up_date == date("m/d/Y")) {
					$date = "AND FOLLOW_UP_DATE<='" . date("Y-m-d", strtotime($follow_up_date)) . "'";
				} else {
					$date = "AND FOLLOW_UP_DATE='" . date("Y-m-d", strtotime($follow_up_date)) . "'";
				}
			} else {
				$date = "";
			}
			$query = "SELECT * FROM leads WHERE STATUS!='Dead' AND
            (COMPANY_NAME LIKE '%" . $search . "%'
                    OR FIRST_NAME LIKE '%" . $search . "%'
                    OR LAST_NAME LIKE '%" . $search . "%'
                    OR CLIENT_EMAIL LIKE '%" . $search . "%'
                    OR OFFICE_PHONE LIKE '%" . $search . "%'
                    OR CELL_PHONE LIKE '%" . $search . "%'
                    OR OTHER_PHONE LIKE '%" . $search . "%'
                    OR FAX LIKE '%" . $search . "%'
                    OR WEBSITE LIKE '%" . $search . "%'
                    OR ADDRESS_1 LIKE '%" . $search . "%'
                    OR ADDRESS_2 LIKE '%" . $search . "%'
                    OR CITY LIKE '%" . $search . "%'
                    OR STATE LIKE '%" . $search . "%'
                    OR ZIP LIKE '%" . $search . "%'
                    OR COUNTRY LIKE '%" . $search . "%'
                    OR EXTRA_FIRST_NAME LIKE '%" . $search . "%'
                    OR EXTRA_LAST_NAME LIKE '%" . $search . "%'
                    OR EXTRA_CLIENT_EMAIL LIKE '%" . $search . "%'
                    OR SEARCH_CITY LIKE '%" . $search . "%'
                    OR SEARCH_STATE LIKE '%" . $search . "%')
            AND USERNAME='$username'
            " . $date . "
            ORDER BY " . $orderby . " " . $dir;
			$result = $mysqli->query($query) or die(mysql_error());
			?>
            <p />
            <div style="float:left; width:89%; margin-bottom: 23px;">
                <form method="post" action="dashboardHelper.php">
                    <table class="grid">
                        <tr>
                            <td colspan="13"><h3>Assigned Leads</h3></td>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Actions</th>
                            <th>Lead Type&nbsp;<a href="index.php?owner=<?= $username ?>&follow_up_date=<?= $follow_up_date ?>&search=<?= $search ?>&orderby=lead_type&dir=ASC">&#9650;</a>&nbsp;<a href="index.php?owner=<?= $username ?>&follow_up_date=<?= $follow_up_date ?>&search=<?= $search ?>&orderby=lead_type&dir=DESC">&#9660;</a></th>
                            <th>First Name</th>
                            <th>Last Name</th>
						   <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Home/Office Phone</th>
                            <th>Follow-Up Date&nbsp;<a href="index.php?owner=<?= $username ?>&follow_up_date=<?= $follow_up_date ?>&search=<?= $search ?>&orderby=follow_up_date&dir=ASC">&#9650;</a>&nbsp;<a href="index.php?owner=<?= $username ?>&follow_up_date=<?= $follow_up_date ?>&search=<?= $search ?>&orderby=follow_up_date&dir=DESC">&#9660;</a></th>
                            <th>Follow-Up Time&nbsp;<a href="index.php?owner=<?= $username ?>&follow_up_time=<?= follow_up_time ?>&search=<?= $search ?>&orderby=follow_up_time&dir=ASC">&#9650;</a>&nbsp;<a href="index.php?owner=<?= $username ?>&follow_up_time=<?= follow_up_time ?>&search=<?= $search ?>&orderby=follow_up_time&dir=DESC">&#9660;</a></th>
                            <th>Lead Strength&nbsp;<a href="index.php?owner=<?= $username ?>&follow_up_date=<?= $follow_up_date ?>&search=<?= $search ?>&orderby=priority&dir=ASC">&#9650;</a>&nbsp;<a href="index.php?owner=<?= $username ?>&follow_up_date=<?= $follow_up_date ?>&search=<?= $search ?>&orderby=priority&dir=DESC">&#9660;</a></th>
                            <th>Status&nbsp;<a href="index.php?owner=<?= $username ?>&follow_up_date=<?= $follow_up_date ?>&search=<?= $search ?>&orderby=status&dir=ASC">&#9650;</a>&nbsp;<a href="index.php?owner=<?= $username ?>&follow_up_date=<?= $follow_up_date ?>&search=<?= $search ?>&orderby=status&dir=DESC">&#9660;</a></th>
                        </tr>
						<?php
						if ($result->num_rows == 0) {
							?>
							<tr><td colspan="13" align="center">No leads found for the selected user/date</td></tr>
							<?php
						}
						$total = 0;
						$hot = 0;
						$strong = 0;
						$mild = 0;
						$weak = 0;
						$no_contract = 0;
						$contact = false;
						$contact_leads = array();
						$now = new DateTime('now');
						//echo var_dump($now) . "<br />";
						while ($row = mysqli_fetch_array($result)) {
							foreach ($row AS $key => $value) {
								$row[$key] = stripslashes($value);
							}
							if ($row['FOLLOW_UP_DATE'] != null && $row['FOLLOW_UP_DATE'] != '0000-00-00' && $row['FOLLOW_UP_TIME'] != null && $row['FOLLOW_UP_TIME'] != '00:00:00') {
								$contact_date = new DateTime($row['FOLLOW_UP_DATE'] . " " . $row['FOLLOW_UP_TIME']);
								//echo var_dump($contact_date) . "<br />";
								$interval = $now->diff($contact_date);
								$minutes = $interval->days * 24 * 60;
								$minutes += $interval->h * 60;
								$minutes += $interval->i;
								//echo $minutes;
								if ($minutes <= 15) {
									$contact = true;
									array_push($contact_leads, stripslashes($row['FIRST_NAME']) . " " . stripslashes($row['LAST_NAME']) . "\n");
								}
							}
							?>
							<tr>
								<td class="center"><input name="leads[]" type="checkbox" value="<?= $row['LEAD_ID'] ?>" /></td>
								<td class="center"><a href="editLead.php?lead_id=<?= $row['LEAD_ID'] ?>"><img src='images/edit.png' alt='Edit Lead' title='Edit Lead' /></a></td>
								<td><?= stripslashes($row['LEAD_TYPE']) ?></td>
								<td><?= stripslashes($row['FIRST_NAME']) ?></td>
								<td><?= stripslashes($row['LAST_NAME']) ?></td>
								<?php if (stripslashes($row['LEAD_TYPE']) == "Buyer") { ?>
								<td></td>
								<td><?= stripslashes($row['SEARCH_CITY']) ?></td>
								<td align="center"><?= $row['SEARCH_STATE'] ?></td>
								<?php } else { ?>
								<td><?= stripslashes($row['ADDRESS_1']) ?></td>
								<td><?= stripslashes($row['CITY']) ?></td>
								<td align="center"><?= $row['STATE'] ?></td>
								<?php } ?>
								<td><?= $row['OFFICE_PHONE'] ?></td>
								<?php if ($row['FOLLOW_UP_DATE'] != null && $row['FOLLOW_UP_DATE'] != '0000-00-00') { ?>
								<td><?= date("m/d/Y", strtotime($row['FOLLOW_UP_DATE'])) ?></td>
								<?php } else { ?>
								<td></td>
								<?php } ?>
								<?php if ($row['FOLLOW_UP_TIME'] != null && $row['FOLLOW_UP_TIME'] != '00:00:00') { ?>
								<td><?= $row['FOLLOW_UP_TIME'] ?></td>
								<?php } else { ?>
								<td></td>
								<?php } ?>
								<td class="<?= $row['PRIORITY'] ?>"><?php if ($row['PRIORITY'] == "NoContract") { echo "No Contract"; } else { echo $row['PRIORITY']; } ?></td>
								<td><?= $row['STATUS'] ?></td>
							</tr>
							<?php
							if ($row['PRIORITY'] == "Hot")
								++$hot;
							if ($row['PRIORITY'] == "Strong")
								++$strong;
							if ($row['PRIORITY'] == "Mild")
								++$mild;
							if ($row['PRIORITY'] == "Weak")
								++$weak;
							if ($row['PRIORITY'] == "NoContact")
								++$no_contract;
							++$total;
						}
						?>
                        <tr>
                            <td colspan="13" style="padding:10px" align="center">
                                <label for="move_to">Move To:</label>
                                <input name="move_to" id="move_to" size="10" />
                                <script type="text/javascript">
									var f_cal = new tcal({
										'controlname': 'move_to'
									});
                                </script>
                                &nbsp;&nbsp;&nbsp;
                                <label for="assign_to">New Owner:</label>
                                <select name="assign_to">
                                    <option value=""></option>
                                    <option value="Not Assigned">Not Assigned</option>
									<?php
									if ($session->isAdmin() || $session->isMaster()) {
										$result = $mysqli->query("SELECT * FROM users WHERE fullname!='' AND userlevel > 0 ORDER BY fullname ASC") or die(mysql_error());
										while ($row = mysqli_fetch_array($result)) {
											foreach ($row AS $key => $value) {
												$row[$key] = stripslashes($value);
											}
											?>
											<option value="<?= $row['username'] ?>"><?= $row['fullname'] ?></option>
											<?php
										}
									} else {
										?>
	                                    <option value="<?= $username ?>"><?= $fullname ?></option>
										<?php
									}
									?>
                                </select>
                                &nbsp;&nbsp;&nbsp;
                                <input class="button" type="submit" name="submit" value="Submit" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div style="float:right; width:10%">
                <table class="grid">
                    <tr><td colspan="2"><h3>Summary</h3></td></tr>
                    <tr><td align="right">Call Back Total:</td><td><?= $total ?></td></tr>
                    <tr class="Hot"><td align="right">Hot:</td><td><?= $hot ?></td></tr>
                    <tr class="Strong"><td align="right">Strong:</td><td><?= $strong ?></td></tr>
                    <tr class="Mild"><td align="right">Mild:</td><td><?= $mild ?></td></tr>
                    <tr class="Weak"><td align="right">Weak:</td><td><?= $weak ?></td></tr>
                    <tr class="NoContact"><td align="right">No Contact:</td><td><?= $no_contract ?></td></tr>
                </table>
            </div>

			<?php
			//echo $contact;
			if ($contact == true) {
			?>
			<script type="text/javascript">
				var contact_leads = <?php echo json_encode(implode("", $contact_leads)); ?>;
				alert("You have one or more leads that require follow-up immediately:\n\n" + contact_leads);
			</script>
			<?php
			}
			?>

            <!-- End Content -->
        </div>
    </body>
</html>