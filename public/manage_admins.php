<?php require_once "../includes/session.php";?>
<?php require_once "../includes/functions.php";?>
<?php confirmLogin();?>
<?php require_once "../includes/db_connection.php";?>
<?php $layout_context = "admin";?>
<?php include "../includes/layouts/header.php";?>

	</div>
	<div id = "main">
		<div id = "navigation">
		<br>
	<a href="admin.php">&laquo; Main Menu</a>
	&nbsp;
	</div>
		<div id = "page">
			<?php echo message(); ?>
			<?php $errors = errors();?>
			<?php echo error_message($errors); ?>
			<h2>Manage Admins</h2>
			<table>
				<tr>
					<th style="text-align:left; width:200px">Username</th>
					<th colspan="2" style="text-align:left">Actions</th>
				</tr>
				<?php $admin_set = findAllAdmins();?>
				<?php while ($admin = mysqli_fetch_assoc($admin_set)) {?>
				<tr>
					<td><?php echo htmlentities($admin["username"]); ?></td>
					<td><a href = "edit_admin.php?id=<?php echo urlencode($admin["id"]); ?>" style="text-decoration:none">Edit</a></td>
					<td><a href = "delete_admin.php?id=<?php echo urlencode($admin["id"]); ?>" style="text-decoration:none"
					   onclick="return confirm('Are you sure?');">Delete</a></td>
				</tr>
				<?php }?>
			</table>
			<br>
			<a href="new_admin.php" style="text-decoration:none">+ Add a New Admin</a>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>
