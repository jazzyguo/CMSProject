<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>

<?php
$admin = findAdminByID($_GET["id"]);
//redirect if admin not found
if (!$admin) {
	redirect("manage_admins.php");
}

$id = $_GET["id"];
$query = "DELETE FROM admins where id={$id} LIMIT 1";
$result = mysqli_query($mysqli, $query);

if ($result && mysqli_affected_rows($mysqli) == 1) {
	//success
	$_SESSION["message"] = "Admin deleted.";
	redirect("manage_admins.php");
} else {
	//fail
	$_SESSION["message"] = "Admin deletion failed.";
	redirect("manage_admins.php");
}
?>