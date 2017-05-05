<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php include "../includes/layouts/header.php";?>

<?php getCurrentPage(true);?>
<div id = "main">
	<div id = "navigation">
	<br>
	<a href="login.php">&laquo; Admin Login</a>
	<?php echo navigation($current_subject, $current_page); ?>

	</div>
		<div id = "page">
			<?php echo message(); ?>
			<?php if ($current_page) {?>
				<h1><?php echo htmlentities($current_page["menu_name"]); ?></h1>
				<?php echo nl2br(htmlentities($current_page["content"])); ?>
			<?php } else {?>
				<h1>Welcome!</h1>
				<p>Please Select a page.</p>
			<?php }?>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>