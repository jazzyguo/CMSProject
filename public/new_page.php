<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php require_once "../includes/validations.php";?>
<?php confirmLogin();?>
<?php $layout_context = "admin";?>
<?php include "../includes/layouts/header.php";?>

<?php getCurrentPage();?>

<?php
if (isset($_POST["submit"])) {
	//creates new subject page
	$menu_name = mysqli_real_escape_string($mysqli, $_POST["menu_name"]);
	$position = (int) $_POST["position"];
	$visible = (int) $_POST["visible"];
	$content = mysqli_real_escape_string($mysqli, $_POST["content"]);

	//validations
	$required_fields = array('menu_name', 'position', 'visible', 'content');
	validate_presences($required_fields);
	$fields_with_max_lengths = array('menu_name' => 30);
	validate_max_lengths($fields_with_max_lengths);
	//redirect if error

	if (!empty($errors)) {
		$_SESSION['errors'] = $errors;
		redirect("new_page.php?subject=" . urlencode($current_subject['id']));
	}

	$query = "INSERT INTO pages (subject_id, menu_name, position, visible, content)
			  VALUES ({$current_subject['id']},'{$menu_name}', {$position}, {$visible}, '{$content}')";
	$result = mysqli_query($mysqli, $query);

	if ($result) {
		$_SESSION["message"] = "New Page Created.";
		redirect("manage_content.php");
	} else {
		$_SESSION["message"] = "Page Creation Failed.";
		redirect("new_page.php?subject=" . urlencode($current_subject['id']));
	}
} else {

}
?>

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
			<h2>Add a New Page</h2>
			<form action = "new_page.php?subject=<?php echo urlencode($current_subject['id']); ?>" method = "post">
			<p>Page Name:
				<input type="text" name="menu_name" value="" />
			</p>
			<p>Position:
				<select name="position">
			<?php $page_set = findPages($current_subject['id'], true);?>
			<?php $page_count = mysqli_num_rows($page_set);?>
			<?php for ($count = 1; $count <= $page_count + 1; $count++) {?>
			<?php echo "<option value=\"{$count}\">{$count}</option>"; ?>
			<?php }?>
				</select>
				</p>
			<p>Visible:
				<input type="radio" name="visible" value="0"/>No
				&nbsp;
				<input type="radio" name="visible" value="1"/>Yes
				</p>
			<p>Content:</p>
				<textarea cols="50" rows="5" name="content" >
				</textarea>
				<br>
			<input type="submit" name="submit" value="Add Page"/>
			</form>
			<br>
			<a href="manage_content.php">Cancel</a>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>