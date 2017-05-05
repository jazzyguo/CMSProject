<?php if (!isset($layout_context)) {
	$layout_context = "public";
}?>

<DOCTYPE HTML>
<html>
<head>
	<title>CMS PROJECT <?php if ($layout_context == "admin") {echo "Admin";}?></title>
<link rel="stylesheet" type="text/css" href="stylesheets/public.css">
</head>
<body>
	<div id = "header">
		<h1>CMS PROJECT <?php if ($layout_context == "admin") {echo "Admin";}?></h1>
	</div>
