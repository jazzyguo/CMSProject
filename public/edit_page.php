<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php require_once "../includes/validations.php";?>

<?php getCurrentPage();?>

<?php
//if page isnt found then redirect
if (!$current_page) {
	redirect('manage_content.php');
}
?>

<?php
if (isset($_POST["submit"])) {
	//process edit form

	//validations
	$required_fields = array('menu_name', 'position', 'visible', 'content');
	validate_presences($required_fields);
	$fields_with_max_lengths = array('menu_name' => 30);
	validate_max_lengths($fields_with_max_lengths);

	//if no errors perform update
	if (empty($errors)) {
		$id = $current_page["id"];
		$menu_name = mysqli_real_escape_string($mysqli, $_POST["menu_name"]);
		$position = (int) $_POST["position"];
		$visible = (int) $_POST["visible"];
		$content = mysqli_real_escape_string($mysqli, $_POST["content"]);

		$query = "UPDATE pages SET menu_name = '{$menu_name}',
									  position = {$position},
									  visible = {$visible},
									  content = '{$content}'
									  WHERE id = {$id} LIMIT 1";
		$result = mysqli_query($mysqli, $query);

		//if edited, or if values are same, resulting in no change
		if ($result && mysqli_affected_rows($mysqli) >= 0) {
			$_SESSION["message"] = "Page edit succesful.";
			redirect("manage_content.php");
		} else {
			//redisplay form
			$_SESSION["message"] = "Page edit failed.";
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
			<h2>Edit Page: <?php echo htmlentities($current_page['menu_name']); ?></h2>
			<form action = "edit_page.php?page=<?php echo urlencode($current_page['id']); ?>" method = "post">
			<p>Subject Name:
				<input type="text" name="menu_name" value="<?php echo htmlentities($current_page['menu_name']); ?>">
			</p>
			<p>Position:
				<select name="position">
			<?php $page_set = findPages($current_page['subject_id'], false);?>
			<?php $page_count = mysqli_num_rows($page_set);?>
			<?php for ($count = 1; $count <= $page_count; $count++) {?>
			<?php echo "<option value=\"{$count}\" "; ?>
				<?php if ($current_page['position'] == $count) {?>
					<?php echo "selected"; ?>
				<?php }?>
				<?php echo ">{$count}</option>"; ?>
				<?php }?>
				</select>
				</p>
			<p>Visible:
				<input type="radio" name="visible" value="0" <?php if ($current_page['visible'] == 0) {echo "checked";}?>>No
				&nbsp;
				<input type="radio" name="visible" value="1" <?php if ($current_page['visible'] == 1) {echo "checked";}?>>Yes
				</p>
			<p>Content:</p>
				<textarea cols="50" rows="5" name="content" >
				<?php echo $current_page['content']; ?>
				</textarea>
				<br>
			<input type="submit" name="submit" value="Edit Page">
			</form>
			<br>
			<a href="manage_content.php" style="text-decoration:none">Cancel</a>
			&nbsp;
			&nbsp;
			<a href="delete_page.php?page=<?php echo urlencode($current_page['id']); ?>" style="text-decoration:none"
			   onClick="return confirm('Are you sure?');">Delete Page</a>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>