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
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode: "textareas",
		plugins : "spellchecker",
		theme: "advanced",
		theme_advanced_buttons1: "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink,spellchecker",
		theme_advanced_buttons2: "",
		theme_advanced_buttons3: "",
		theme_advanced_buttons4: "",
		theme_advanced_toolbar_location: "top",
		theme_advanced_toolbar_align: "left"
	});
</script>
</head>
<body>
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<div align="center">
<?php
/**
 * displayTemplates - Displays the templates in
 * a nicely formatted html table.
 */
function displayTemplates() {
    global $database;
    $q = "SELECT name,content FROM email_templates ORDER BY name";
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
    echo "<tr><th>Name</th><th>Content</th></tr>\n";
    for ($i = 0; $i < $num_rows; $i++) {
        $name = mysqli_result($result, $i, "name");
        $content = stripslashes(str_replace('\r\n', ' ', mysqli_result($result, $i, "content")));
        echo "<tr><td valign='top'>$name</td><td valign='top' style='padding:10px'>$content</td></tr>\n";
    }
    echo "</table><br>\n";
}

if (!$session->isAdmin() && !$session->isMaster()) {
    echo "Error: You must be an Administrator/Master to access this page";
} else {
?>
<table align="center" cellspacing="0" cellpadding="5">
<tr><td>

<h3>Email Templates</h3>
<table align="center" cellspacing="0" cellpadding="5">
<tr><td>
<?php displayTemplates(); ?>
</td></tr>
<tr>
<td>

</td></tr>
<tr><td>

<h3>Add Template</h3>
<?php echo $form->error("addtemplate"); ?>
<form action="adminTemplatesHelper.php" method="POST">
<input type="text" name="name" maxlength="30" size="30" value="<?php echo $form->value("name"); ?>" placeholder="Template Name" />
<p />
<textarea name="content" cols="75" rows="10" wrap="virtual"><?php echo $form->value("content"); ?></textarea><br />
<input type="hidden" name="addtemplate" value="1" />
<input class="button" type="submit" value="Add Template"><br />
</form>

</td></tr>
<tr><td>

<h3>Delete Template</h3>
<?php echo $form->error("deltemplate"); ?>
<form action="adminTemplatesHelper.php" method="POST">
<input type="text" name="name" maxlength="30" size="30" value="<?php echo $form->value("name"); ?>" placeholder="Template Name" />
<input type="hidden" name="deltemplate" value="1" />
<input class="button" type="submit" value="Delete Template"><br />
</form>

</td></tr>
</table>

<?php
}
?>
</div>
</div>
</body>
</html>