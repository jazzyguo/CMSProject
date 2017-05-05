<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php confirmLogin();?>

<?php
//if subject isnt found then redirect
$current_page = findPageByID($_GET['page'], false);
if (!$current_page) {
	$_SESSION["message"] = "Deletion error (Page not found).";
	redirect('manage_content.php');
}

$query = "DELETE FROM pages where id={$current_page['id']} LIMIT 1";
$result = mysqli_query($mysqli, $query);

if ($result && mysqli_affected_rows($mysqli) == 1) {
	//success
	$_SESSION["message"] = "Page deleted.";
	redirect("manage_content.php");
} else {
	//fail
	$_SESSION["message"] = "Page deletion failed.";
	redirect("manage_content.php?subject={$current_page['id']}");
}
?>