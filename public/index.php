<?php require_once "../includes/session.php";?>
<?php require_once "../includes/db_connection.php";?>
<?php require_once "../includes/functions.php";?>
<?php include "../includes/layouts/header.php";?>

<?php //shows the current page
getCurrentPage(true);?>

<div id = "main">
	<div id = "navigation">
	<br>
	<a href="login.php">&laquo; Admin Login</a>
	<?php //displays the navigation bar
echo navigation($current_subject, $current_page); ?>

	</div>
		<div id = "page">
			<?php //displays any session message
echo message(); ?>
			<?php if ($current_page) {?>
				<h1><?php //displays the current page
	echo htmlentities($current_page["menu_name"]); ?></h1>
				<?php //new line to break, displays the content of the current page
	echo nl2br(htmlentities($current_page["content"])); ?>
			<?php } else {?>
				<h1>Welcome!</h1>
				<p>Please Select a page.</p>
			<?php }?>
		</div>
	</div>

<?php include "../includes/layouts/footer.php";?>