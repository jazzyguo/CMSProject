<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php require_once "../includes/validations.php";?>

<?php
$admin = findAdminByID($_GET["id"]);
//redirect if admin not found
if (!$admin) {
	redirect("manage_admins.php");
}

if (isset($_POST["submit"])) {
	//process edit form

	//validations
	$required_fields = array('username', 'password');
	validate_presences($required_fields);
	$fields_with_max_lengths = array('username' => 15);
	validate_max_lengths($fields_with_max_lengths);

	//if no errors perform update
	if (empty($errors)) {
		$id = $_GET["id"];
		$username = mysqli_real_escape_string($mysqli, $_POST["username"]);
		$password = mysqli_real_escape_string($mysqli, $_POST["password"]);

		$query = "UPDATE admins SET username = '{$username}',
									password = '{$password}'
									WHERE id = {$id} LIMIT 1";
		$result = mysqli_query($mysqli, $query);

		//if edited, or if values are same, resulting in no change
		if ($result && mysqli_affected_rows($mysqli) >= 0) {
			$_SESSION["message"] = "Admin edit succesful.";
			redirect("manage_admins.php");
		} else {
			//redisplay form
			$_SESSION["message"] = "Admin edit failed.";
		}
	} else {
		//errors
		$_SESSION['errors'] = $errors;
	}
}

?>
<?php $layout_context = "admin";?>
<?php include "../includes/layouts/header.php";?>

<div id = "main">
	<div id = "navigation">
	<br>
	<a href="manage_admins.php">&laquo; Manage Admins</a>
	</div>
		<div id = "page">
		<?php echo message(); ?>
		<?php $errors = errors();?>
		<?php echo error_message($errors); ?>
			<h2>Edit Admin: <?php echo $admin["username"]; ?></h2>
			<form action = "edit_admin.php?id=<?php echo urlencode($_GET["id"]); ?>" method = "post">
			<p>New Username: <input type="text" name="username" value="<?php echo $admin["username"]; ?>">
			</p>
			<p>New Password:&nbsp;&nbsp;<input type="password" name="password" value="">
			</p>
			<input type="submit" name="submit" value="Edit Subject">
			</form>
			<br>
			<a href="manage_admins.php" style="text-decoration:none">Cancel</a>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>