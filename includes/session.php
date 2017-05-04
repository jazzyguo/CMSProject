<?php
//CREATE SESSION
session_start();

function message() {
	if (isset($_SESSION["message"])) {
		$output = "<div class=\"message\">";
		$output .= htmlentities($_SESSION["message"]);
		$output .= "</div>";
		$_SESSION["message"] = null;
		return $output;
	}
}

function errors() {
	if (isset($_SESSION["errors"])) {
		$output = ($_SESSION["errors"]);
		$_SESSION["errors"] = null;
		return $output;
	}
}