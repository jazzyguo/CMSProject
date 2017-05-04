<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>

<?php
//if subject isnt found then redirect
$current_subject = findSubjectByID($_GET['subject']);
if (!$current_subject) {
	$_SESSION["message"] = "Deletion error (Subject not found).";
	redirect('manage_content.php');
}

$pages_set = findPages($current_subject['id']);
if (mysqli_num_rows($pages_set) > 0) {
	$_SESSION["message"] = "Please remove all pages first before deleting a subject.";
	redirect("manage_content.php?subject={$current_subject['id']}");

}
$query = "DELETE FROM subjects where id={$current_subject['id']} LIMIT 1";
$result = mysqli_query($mysqli, $query);

if ($result && mysqli_affected_rows($mysqli) == 1) {
	//success
	$_SESSION["message"] = "Subject deleted.";
	redirect("manage_content.php");
} else {
	//fail
	$_SESSION["message"] = "Subject deletion failed.";
	redirect("manage_content.php?subject={$current_subject['id']}");
}
?>