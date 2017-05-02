<?
include("include/session.php");
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
<div id="header">
<table width="100%">
<tr>
<td width="200px">
<img src="images/logo.png" />
</td>
<td align="center" valign="bottom">
<h3>SimpleHouseSolutions.com - Dashboard</h3>
</td>
<td width="200px">
&nbsp;
</td>
</tr>
</table>
</div>
<div id="content">
<div align="center">
<?
/**
 * Forgot Password form has been submitted and no errors
 * were found with the form (the username is in the database)
 */
if(isset($_SESSION['forgotpass'])){
   /**
    * New password was generated for user and sent to user's
    * email address.
    */
   if($_SESSION['forgotpass']){
      echo "<h2>New Password Generated</h2>";
      echo "<p />Your new password has been generated and sent to the email associated with your account.";
      echo "<p />Back to the <a href='index.php'>Login</a> page";
   }
   /**
    * Email could not be sent, therefore password was not
    * edited in the database.
    */
   else{
      echo "<h2>New Password Failure</h2>";
      echo "<p />There was an error sending you the email with the new password, so your password has not been changed.";
      echo "<p />Back to the <a href='index.php'>Login</a> page";
   }

   unset($_SESSION['forgotpass']);
} else {

/**
 * Forgot password form is displayed, if error found
 * it is displayed.
 */
?>
<fieldset style="width:300px;margin:0 auto;">
<legend>Forgot Password</legend>
<p />
A new password will be generated for you and sent to the email address associated with your account, all you have to do is enter your username.
<p />
<div align="center">
<form action="process.php" method="post">
<?php echo $form->error("user") . "<br />"; ?>
<label>Username:</label>
<input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>">
<p />
<input type="hidden" name="subforgot" value="1">
<input class="button" type="submit" value="Get New Password">
</form>
</div>
</fieldset>

<?
}
?>
</div>
</div>
</body>
</html>