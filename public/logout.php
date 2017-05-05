<?php require_once "../includes/session.php";?>
<?php require_once "../includes/functions.php";?>


<?php
//remove session admin id and username
$_SESSION["admin_id"] = null;
$_SESSION["username"] = null;
redirect("login.php");
?>