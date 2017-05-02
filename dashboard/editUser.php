<?php
require_once("include/checklogin.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>SimpleHouseSolutions.com - Dashboard</title>
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
/**
 * User has submitted form without errors and user's
 * account has been edited successfully.
 */
if (isset($_SESSION['useredit'])) {
   unset($_SESSION['useredit']);
   echo "<h1>User Account Edit Success!</h1>";
   echo "<p><b>$session->username</b>, your account has been successfully updated. "
       ."<a href=\"process.php\">Logout</a> for changes to take affect.</p>";
} else {

    /**
     * If user is not logged in, then do not display anything.
     * If user is logged in, then display the form to edit
     * account information, with the current email address
     * already in the field.
     */
    if($session->logged_in) {
        if($form->num_errors > 0) {
           echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></td>";
        }
    }
?>
<p />
<form action="process.php" method="POST">
<table class="input">
<tr><th colspan="2"><h3>Edit User</h3></th></tr>
<tr>
<td align="right">Full Name:</td>
<td><input type="text" name="fullname" size="30" maxlength="70" value="
<?php
    if($form->value("fullname") == "") {
       echo $session->userinfo['fullname'];
    } else {
       echo $form->value("fullname");
    }
?>
">
</tr>
<tr>
<td align="right">Email:</td>
<td><input type="text" name="email" size="30" maxlength="50" value="
<?php
    if ($form->value("email") == "") {
       echo $session->userinfo['email'];
    } else {
       echo $form->value("email");
    }
?>">
</td>
<td><?php echo $form->error("email"); ?></td>
</tr>
<tr>
<td align="right">Current Password:</td>
<td><input type="password" name="curpass" size="30" maxlength="30" value="<?php echo $form->value("curpass"); ?>"></td>
<td><?php echo $form->error("curpass"); ?></td>
</tr>
<tr>
<td align="right">New Password:</td>
<td><input type="password" name="newpass" size="30" maxlength="30" value="<?php echo $form->value("newpass"); ?>"></td>
<td><?php echo $form->error("newpass"); ?></td>
</tr>
<tr><td colspan="2" align="center">
<input type="hidden" name="subedit" value="1">
<input class="button" type="submit" value="Submit"></td></tr>
</table>
</form>

<?php
}
?>

</div>
</div>
</body>
</html>