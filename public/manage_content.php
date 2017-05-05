<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php confirmLogin();?>
<?php $layout_context = "admin";?>
<?php include "../includes/layouts/header.php";?>

<?php getCurrentPage();?>
<div id = "main">
	<div id = "navigation">
	<br>
	<a href="admin.php">&laquo; Main Menu</a>
	<?php echo navigation($current_subject, $current_page, false); ?>
	<br>
	<a href="add_subject.php">+ Add a Subject</a>
	</div>
		<div id = "page">
			<?php echo message(); ?>
			<?php if ($current_subject) {?>
				<h2>Manage Subject</h2>
				Menu Name: <?php echo htmlentities($current_subject["menu_name"]) . "<br>"; ?>
				Position: <?php echo $current_subject["position"] . "<br>"; ?>
				Visible: <?php echo $current_subject["visible"] == 1 ? 'Yes' : 'No' . "<br>"; ?>
				<br><br>
				<a href="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Edit Subject</a>
				<br>
				<hr>
				<h3>Pages Associated with this subject: </h3>
				<ul class = "pages">
				<?php $page_set = findPages($current_subject["id"], false);?>
				<?php while ($page = mysqli_fetch_assoc($page_set)) {?>
				<li> <a href ="manage_content.php?page=<?php echo $page["id"]; ?>"><?php echo htmlentities($page['menu_name']); ?></a></li>
				<?php }?>
				</ul>
				<a href="new_page.php?subject=<?php echo urlencode($current_subject['id']); ?>" style="text-decoration:none">+ Add a new Page</a>

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
				<h1>Welcome!</h1>
				<p>Please Select a page.</p>
			<?php }?>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>