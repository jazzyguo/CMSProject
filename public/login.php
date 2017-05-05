<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php require_once "../includes/validations.php";?>
<?php $layout_context = "admin";?>
<?php include "../includes/layouts/header.php";?>

<?php
$username = "";

if (isset($_POST["submit"])) {
	//validations
	$required_fields = array('username', 'password');
	validate_presences($required_fields);

	//Login attempt
	if (empty($errors)) {
		$username = mysqli_real_escape_string($mysqli, $_POST["username"]);
		$password = $_POST["password"];
		$found_admin = attemptLogin($username, $password);

		if ($found_admin) {
			//login success
			//Mark user logged in as admin
			$_SESSION["admin_id"] = $found_admin["id"];
			$_SESSION["username"] = $found_admin["username"];
			redirect("admin.php");
		} else {
			//login failure
			$_SESSION["message"] = "Login Failed. Invalid username or password.";
		}
	} else {
		//display errors
		$_SESSION['errors'] = $errors;
	}
}
?>

<div id = "main">
	<div id = "navigation">
	<br>
	<a href="index.php">&laquo; Back</a>
	</div>
		<div id = "page">
		<?php echo message(); ?>
		<?php $errors = errors();?>
		<?php echo error_message($errors); ?>
			<h2>Admin Login</h2>
			<form action = "login.php" method = "post">
			<p>Username: <input type="text" name="username"
			value="<?php echo htmlentities($username); ?>">
			</p>
			<p>Password:&nbsp;&nbsp;<input type="password" name="password" value="">
			</p>
			<input type="submit" name="submit" value="Submit">
			</form>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>