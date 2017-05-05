<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php require_once "../includes/validations.php";?>
<?php confirmLogin();?>

<?php
if (isset($_POST["submit"])) {
	//creates new subject page
	$menu_name = mysqli_real_escape_string($mysqli, $_POST["menu_name"]);
	$position = (int) $_POST["position"];
	$visible = (int) $_POST["visible"];

	//validations
	$required_fields = array('menu_name', 'position', 'visible');
	validate_presences($required_fields);
	$fields_with_max_lengths = array('menu_name' => 30);
	validate_max_lengths($fields_with_max_lengths);
	//redirect if error
	if (!empty($errors)) {
		$_SESSION['errors'] = $errors;
		redirect('add_subject.php');
	}

	$query = "INSERT INTO subjects (menu_name, position, visible) VALUES ('{$menu_name}', {$position}, {$visible})";
	$result = mysqli_query($mysqli, $query);

	if ($result) {
		$_SESSION["message"] = "New Subject Created.";
		redirect("manage_content.php");
	} else {
		$_SESSION["message"] = "Subject Creation Failed.";
		redirect("add_subject.php");
	}
}

//close connection
mysqli_close($mysqli);
?>
