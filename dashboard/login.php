<?php
include_once("include/session.php");
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
            <img src="images/logo.png" />
        </div>
        <div id="content">
            <p />
            <form action="process.php" method="post">
                <fieldset style="width:300px;margin:0 auto;">
                    <legend>Login details</legend>
                    <table align="center" border="0" cellspacing="0" cellpadding="3">
                        <tr>
                            <td colspan="2" align="center">
                                &nbsp;
                                <?php
                                 $default_referer = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                                 $default_referer = str_replace("login", "index",$default_referer);                                 
                                if( !isset($_SESSION["myreferer"]) ){                                    
                                    $_SESSION["myreferer"] = $default_referer;
                                }
                                $myreferer = $_SESSION["myreferer"];
                                if ($session->logged_in) {
                                    echo "<meta http-equiv=\"refresh\" content=\"0;URL=$myreferer\">";
                                } else {
                                    if ($form->num_errors > 0) {
                                        echo "<font size=\"2\" color=\"#ff0000\">" . $form->num_errors . " error(s) found</font>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">Username:</td>
                                <td align="left"><input type="text" name="user" maxlength="30" value="<?php echo $form->value('user'); ?>"></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">&nbsp;<?php echo $form->error("user"); ?></td>
                            </tr>
                            <tr>
                                <td align="right">Password:</td>
                                <td align="left"><input type="password" name="pass" maxlength="30" value="<?php echo $form->value('pass'); ?>"></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">&nbsp;<?php echo $form->error("pass"); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <input type="checkbox" name="remember" <?php if ($form->value("remember") != "") { echo "checked"; } ?>>
                                    <font size="2">Remember me next time &nbsp;
                                    <input type="hidden" name="sublogin" value="1">
                                    <input class="button" type="submit" value="Login">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center"><br /><font size="2">[<a href="forgotpass.php">Forgot Password?</a>]</font></td>
                            </tr>
                        </table>
                    </fieldset>
                </form>
                <?php
            }
            ?>
        </div>
    </body>
</html>