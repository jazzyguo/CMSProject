<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php require_once "../includes/validations.php";?>
<?php $layout_context = "admin";?>
<?php include "../includes/layouts/header.php";?>

<?php
if (isset($_POST["submit"])) {
	//creates new subject page
	$username = mysqli_real_escape_string($mysqli, $_POST["username"]);
	$password = mysqli_real_escape_string($mysqli, $_POST["password"]);

	//validations
	$required_fields = array('username', 'password');
	validate_presences($required_fields);
	$fields_with_max_lengths = array('username' => 15);
	validate_max_lengths($fields_with_max_lengths);

	//redirect if error
	if (!empty($errors)) {
		$_SESSION['errors'] = $errors;
		redirect("new_admin.php");
	}

	$query = "INSERT INTO admins (username, password)
			  VALUES ('{$username}','{$password}')";
	$result = mysqli_query($mysqli, $query);

	if ($result) {
		$_SESSION["message"] = "New Admin Created.";
		redirect("manage_admins.php");
	} else {
		$_SESSION["message"] = "Admin Creation Failed.";
		redirect("new_admin.php");
	}
} else {

}
?>

<div id = "main">
	<div id = "navigation">
	<br>
	<a href="manage_admins.php">&laquo; Manage Admins</a>
	</div>
		<div id = "page">
		<?php echo message(); ?>
		<?php $errors = errors();?>
		<?php echo error_message($errors); ?>
			<h2>Add a New Admin</h2>
			<form action = "new_admin.php" method = "post">
			<p>Username: <input type="text" name="username" value="">
			</p>
			<p>Password:&nbsp;&nbsp;<input type="password" name="password" value="">
			</p>
			<input type="submit" name="submit" value="Add Admin">
			</form>
			<br>
			<a href="manage_admins.php">Cancel</a>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>