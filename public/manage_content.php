<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php include "../includes/layouts/header.php";?>

<?php getCurrentPage();?>
<div id = "main">
	<div id = "navigation">
	<br>
	<a href="admin.php">&laquo; Main Menu</a>
	<?php echo navigation($current_subject, $current_page); ?>
	<br>
	<a href="add_subject.php">+ Add a Subject</a>
	</div>
		<div id = "page">
			<?php echo message(); ?>
			<?php if ($current_subject) {?>
				<h2>Manage Subject</h2>
				Menu Name: <?php echo htmlentities($current_subject["menu_name"]) . "<br>"; ?>
				Position: <?php echo $current_subject["position"] . "<br>"; ?>
				Visible: <?php echo $current_subject["visible"] == 1 ? 'Yes' : 'No' . "<br>"; ?> <br>
				<a href="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Edit Subject</a>
				<br>
				<hr>
				<h3>Pages Associated with this subject: </h3>
				<ul class = "pages">
				<?php $page_set = findPages($current_subject["id"]);?>
				<?php while ($page = mysqli_fetch_assoc($page_set)) {?>
				<li> <?php echo htmlentities($page['menu_name']); ?> </li>
				<?php }?>
				</ul>
				<a href="new_page.php?subject=<?php echo urlencode($current_subject['id']); ?>">+ Add a new Page</a>

			<?php } elseif ($current_page) {?>
				<h2>Manage Page</h2>
				Menu Name: <?php echo htmlentities($current_page["menu_name"]) . "<br>"; ?>
				Position: <?php echo $current_page["position"] . "<br>"; ?>
				Visible: <?php echo $current_page["visible"] == 1 ? 'Yes' : 'No' . "<br>"; ?> <br>
				Content: <br>
				<div class = "view-content">
				<?php echo htmlentities($current_page["content"]); ?>
				</div>
				<a href="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>">Edit Page</a>
			<?php } else {?>
				<br>
				<?php echo "Please select a page.."; ?>
			<?php }?>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>