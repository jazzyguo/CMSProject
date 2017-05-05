<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php require_once "../includes/validations.php";?>
<?php confirmLogin();?>
<?php getCurrentPage();?>

<?php
//if subject isnt found then redirect
if (!$current_subject) {
	redirect('manage_content.php');
}
?>

<?php
if (isset($_POST["submit"])) {
	//process edit form

	//validations
	$required_fields = array('menu_name', 'position', 'visible');
	validate_presences($required_fields);
	$fields_with_max_lengths = array('menu_name' => 30);
	validate_max_lengths($fields_with_max_lengths);

	//if no errors perform update
	if (empty($errors)) {
		$id = $current_subject['id'];
		$menu_name = mysqli_real_escape_string($mysqli, $_POST["menu_name"]);
		$position = (int) $_POST["position"];
		$visible = (int) $_POST["visible"];

		$query = "UPDATE subjects SET menu_name = '{$menu_name}',
									  position = {$position},
									  visible = {$visible}
									  WHERE id = {$id} LIMIT 1";
		$result = mysqli_query($mysqli, $query);

		//if edited, or if values are same, resulting in no change
		if ($result && mysqli_affected_rows($mysqli) >= 0) {
			$_SESSION["message"] = "Subject edit succesful.";
			redirect("manage_content.php");
		} else {
			//redisplay form
			$_SESSION["message"] = "Subject edit failed.";
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
	<?php echo navigation($current_subject, $current_page, false); ?>
	<br>
	<a href="add_subject.php">+ Add a Subject</a>
	</div>
		<div id = "page">
		<?php echo message(); ?>
		<?php $errors = errors();?>
		<?php echo error_message($errors); ?>
			<h2>Edit Subject: <?php echo htmlentities($current_subject['menu_name']); ?></h2>
			<form action = "edit_subject.php?subject=<?php echo urlencode($current_subject['id']); ?>" method = "post">
			<p>Subject Name:
				<input type="text" name="menu_name" value="<?php echo htmlentities($current_subject['menu_name']); ?>">
			</p>
			<p>Position:
				<select name="position">
			<?php $subject_set = findAllSubjects(false);?>
			<?php $subject_count = mysqli_num_rows($subject_set);?>
			<?php for ($count = 1; $count <= $subject_count; $count++) {?>
				<?php echo "<option value=\"{$count}\" "; ?>
				<?php if ($current_subject['position'] == $count) {?>
					<?php echo "selected"; ?>
				<?php }?>
				<?php echo ">{$count}</option>"; ?>
			<?php }?>
				</select>
				</p>
			<p>Visible:
				<input type="radio" name="visible" value="0" <?php if ($current_subject['visible'] == 0) {echo "checked";}?>>No
				&nbsp;
				<input type="radio" name="visible" value="1" <?php if ($current_subject['visible'] == 1) {echo "checked";}?>>Yes
				</p>
			<input type="submit" name="submit" value="Edit Subject">
			</form>
			<br>
			<a href="manage_content.php" style="text-decoration:none">Cancel</a>
			&nbsp;
			&nbsp;
			<a href="delete_subject.php?subject=<?php echo urlencode($current_subject['id']); ?>" style="text-decoration:none"
			   onClick="return confirm('Are you sure?');">Delete Subject</a>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>