<?php
//CREATE SESSION
session_start();

//displays the session message, then clears
function message() {
	if (isset($_SESSION["message"])) {
		$output = "<div class=\"message\">";
		$output .= htmlentities($_SESSION["message"]);
		$output .= "</div>";
		$_SESSION["message"] = null;
		return $output;
	}
}

//displays session errors, then clears
function errors() {
	if (isset($_SESSION["errors"])) {
		$output = ($_SESSION["errors"]);
		$_SESSION["errors"] = null;
		return $output;
	}
}