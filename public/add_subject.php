<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php $layout_context = "admin";?>
<?php include "../includes/layouts/header.php";?>

<?php getCurrentPage();?>
<div id = "main">
	<div id = "navigation">
	<?php echo navigation($current_subject, $current_page, false); ?>
	</div>
		<div id = "page">
		<?php echo message(); ?>
		<?php $errors = errors();?>
		<?php echo error_message($errors); ?>
			<h2>Add a New Subject</h2>
			<form action = "new_subject.php" method = "post">
			<p>Subject Name:
				<input type="text" name="menu_name" value="" />
			</p>
			<p>Position:
				<select name="position">
			<?php $subject_set = findAllSubjects(false);?>
			<?php $subject_count = mysqli_num_rows($subject_set);?>
			<?php for ($count = 1; $count <= $subject_count + 1; $count++) {?>
			<?php echo "<option value=\"{$count}\">{$count}</option>"; ?>
			<?php }?>
				</select>
				</p>
			<p>Visible:
				<input type="radio" name="visible" value="0"/>No
				&nbsp;
				<input type="radio" name="visible" value="1"/>Yes
				</p>
			<input type="submit" name="submit" value="Add Subject"/>
			</form>
			<br>
			<a href="manage_content.php">Cancel</a>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>