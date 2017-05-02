<?php
require_once("include/checklogin.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SimpleHouseSolutions.com - Dashboard</title>
<meta charset="utf-8">
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" type="text/css" href="css/dashboard.css"/>
<link rel="stylesheet" type="text/css" href="css/dashboard_menu.css"/>
</head>
<body>
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<div align="center">
<?php

function displayUsers() {
    global $database;
    $q = "SELECT username,userlevel,email,timestamp,fullname FROM " . TBL_USERS . " ORDER BY userlevel,username";
    $result = $database->query($q);
    /* Error occurred, return given name by default */
    $num_rows = mysqli_num_rows($result);
    if (!$result || ($num_rows < 0)) {
        echo "Error displaying info";
        return;
    }
    if ($num_rows == 0) {
        echo "Database table empty";
        return;
    }
    /* Display table contents */
    echo "<table class='grid'>\n";
    echo "<tr><th>Fullname</th><th>Username</th><th>Level</th><th>Email</th><th>Last Active</th></tr>\n";
    for ($i = 0; $i < $num_rows; $i++) {
        $uname = mysqli_result($result, $i, "username");
        $ulevel = mysqli_result($result, $i, "userlevel");
        $email = mysqli_result($result, $i, "email");
        $time = mysqli_result($result, $i, "timestamp");
        $fullname = mysqli_result($result, $i, "fullname");
        if ($ulevel == 1) {
            $ulevel = "Asset Manager";
        }
        if ($ulevel == 3) {
            $ulevel = "Affiliate";
        }
        if ($ulevel == 5) {
            $ulevel = "TAT Agent";
        }
        if ($ulevel == 7) {
            $ulevel = "SHS Agent";
        }
        if ($ulevel == 9) {
            $ulevel = "Administrator";
        }
        if ($ulevel == 10) {
            $ulevel = "Master";
        }
        $time = date("Y-m-d", $time);
        echo "<tr><td>$fullname</td><td>$uname</td><td>$ulevel</td><td>$email</td><td>$time</td></tr>\n";
    }
    echo "</table><br>\n";
}

if (!$session->isAdmin() && !$session->isMaster()) {
    echo "Error: You must be an Administrator/Master to access this page";
} else {
?>
<table align="center" cellspacing="0" cellpadding="5">
<tr><td>

<h3>User Administration</h3>
<table align="center" cellspacing="0" cellpadding="5">
<tr><td>
<h3>Users</h3>
<?php displayUsers(); ?>
</td></tr>
<tr>
<td>

</td></tr>
<tr><td>

<h3>Add User</h3>
<?php echo $form->error("user"); ?>
<table>
<form action="adminUsersHelper.php" method="POST">
<td>Full Name:<br /><input type="text" name="fullname" size="30" maxlength="70" value="<?php echo $form->value("fullname"); ?>" autocomplete="off" /></td>
<td>Username:<br /><input type="text" name="user" size="30" maxlength="30" value="<?php echo $form->value("user"); ?>" autocomplete="off" /></td>
<td>Password:<br /><input type="password" name="pass" size="30" maxlength="30" value="<?php echo $form->value("pass"); ?>" autocomplete="off" />
<td>Email:<br /><input type="text" name="email" size="30" maxlength="50" value="<?php echo $form->value("email"); ?>" autocomplete="off" /></td>
<td>Level:<br />
<select name="ulevel">
<option value="1">Asset Manager</option>
<option value="3">Affiliate</option>
<option value="5">TAT Agent</option>
<option value="7">SHS Agent</option>
<option value="9">Administrator</option>
<option value="10">Master</option>
</select>
</td>
<td>
<br />
<input type="hidden" name="subadduser" value="1">
<input class="button" type="submit" value="Add User">
</td></tr>
</form>
</table>

</td></tr>
<tr><td>

<h3>Update User Level</h3>
<?php echo $form->error("upduser"); ?>
<table>
<form action="adminUsersHelper.php" method="POST">
<tr><td>
Username:<br>
<input type="text" name="upduser" maxlength="30" value="<?php echo $form->value("upduser"); ?>">
</td>
<td>
Level:<br>
<select name="updlevel">
<option value="1">Asset Manager</option>
<option value="3">Affiliate</option>
<option value="5">TAT Agent</option>
<option value="7">SHS Agent</option>
<option value="9">Administrator</option>
<option value="10">Master</option>
</select>
</td>
<td>
<br>
<input type="hidden" name="subupdlevel" value="1">
<input class="button" type="submit" value="Update Level">
</td></tr>
</form>
</table>

</td></tr>
<tr><td>

<h3>Change Password</h3>
<form action="adminUsersHelper.php" method="POST">
<table>
<tr>
<td>Username:</td>
<td>New Password:</td>
<td>Re-Enter Password:</td>
<td></td>
</tr>
<tr>
<td><input type="text" name="cpuser" maxlength="30" value="<?php echo $form->value("cpuser"); ?>"></td>
<td><input type="password" name="newpass1" maxlength="30" value="<?php echo $form->value("newpass1"); ?>"></td>
<td><input type="password" name="newpass2" maxlength="30" value="<?php echo $form->value("newpass2"); ?>"></td>
<td>
<input type="hidden" name="subedit" value="1">
<input class="button" type="submit" value="Update Password">
</td>
</tr>
<tr>
<td><?php echo $form->error("cpuser"); ?></td>
<td><?php echo $form->error("newpass1"); ?></td>
<td><?php echo $form->error("newpass2"); ?></td>
<td></td>
</tr>
<tr>
<td colspan="4">
<?php
if(isset($_SESSION['pwupdate'])){
   unset($_SESSION['pwupdate']);
   echo "* Password Changed Successfully";
}
?>
</td>
</tr>
</table>
</form>

</td></tr>
<tr><td>

<?php
if($session->isMaster()) {
?>
<h3>Delete User</h3>
<form action="adminUsersHelper.php" method="POST">
Username:<br>
<input type="text" name="deluser" maxlength="30" value="<?php echo $form->value("deluser"); ?>">
<input type="hidden" name="subdeluser" value="1">
<input class="button" type="submit" value="Delete User"><br />
<?php echo $form->error("deluser"); ?>
</form>
<?php
}
?>

</td></tr>
</table>

<?php
}
?>
</div>
</div>
</body>
</html>