<?php
//confirms query success
function confirmQuery($result_set) {
	if (!$result_set) {
		die("Database query failed.");
	}
}

function redirect($new_location) {
	header("Location: " . $new_location);
	exit;
}

function error_message($errors) {
	$output = "";
	if (!empty($errors)) {
		$output .= "<div class=/'errors/''>";
		$output .= "<br>Please fix the following errors.";
		$output .= "<ul>";
		foreach ($errors as $key => $value) {
			$output .= "<li>";
			$output .= htmlentities($value);
			$output .= "</li>";
		}
		$output .= "</ul>";
		$output .= "</div>";

	}
	return $output;
}
//Gets all subjects
function findAllSubjects($public = true) {
	global $mysqli;
	$query = "SELECT * FROM subjects ";
	if ($public) {
		$query .= "WHERE visible = 1 ";
	}
	$query .= "ORDER BY position asc";
	$subject_set = mysqli_query($mysqli, $query);
	confirmQuery($subject_set);
	return $subject_set;

}

//Gets pages using subject_id
function findPages($subject_id, $public = true) {
	global $mysqli;
	$subject_id = mysqli_real_escape_string($mysqli, $subject_id);
	$query = "SELECT * FROM pages WHERE subject_id = {$subject_id} ";
	if ($public) {
		$query .= "AND visible = 1 ";
	}
	$query .= "ORDER BY position asc";
	$page_set = mysqli_query($mysqli, $query);
	confirmQuery($page_set);
	return $page_set;
}

function findSubjectByID($subject_id, $public = true) {
	global $mysqli;
	$subject_id = mysqli_real_escape_string($mysqli, $subject_id);
	$query = "SELECT * FROM subjects WHERE id = {$subject_id} ";
	if ($public) {
		$query .= " AND visible = 1";
	}
	$subject_set = mysqli_query($mysqli, $query);
	confirmQuery($subject_set);
	if ($subject = mysqli_fetch_assoc($subject_set)) {
		return $subject;
	} else {
		return null;
	}
}

function findPageByID($page_id, $public = true) {
	global $mysqli;
	$page_id = mysqli_real_escape_string($mysqli, $page_id);
	$query = "SELECT * FROM pages WHERE id = {$page_id}";
	if ($public) {
		$query .= " AND visible = 1";
	}
	$page_set = mysqli_query($mysqli, $query);
	confirmQuery($page_set);
	if ($page = mysqli_fetch_assoc($page_set)) {
		return $page;
	} else {
		return null;
	}
}

//gets a default (first) page based on subject id
function findDefaultPage($subject_id) {
	$page_set = findPages($subject_id);
	if ($page = mysqli_fetch_assoc($page_set)) {
		return $page;
	} else {
		return null;
	}
}
//returns list of subjects with corresponding pages(all if $public = false)
function navigation($selected_subject, $selected_page, $public = true) {
	$output = "<ul class=\"subjects\">";
	$subject_set = findAllSubjects($public);
	//while a subject exists
	while ($subject = mysqli_fetch_assoc($subject_set)) {
		$output .= "<li";
		//highlights selected subject
		if ($selected_subject["id"] == $subject["id"]) {
			$output .= " class=\"selected\"";
		}
		$output .= ">";
		if ($public) {
			$output .= "<a href=\"index.php?subject=";
		} else {
			$output .= "<a href=\"manage_content.php?subject=";
		}
		$output .= urlencode($subject["id"]);
		$output .= "\">";
		$output .= htmlentities($subject["menu_name"]);
		$output .= "</a>";
		$page_set = findPages($subject["id"], $public);
		//if not admin, only show visible pages
		if ($public) {
			if ($selected_subject["id"] == $subject["id"] || $selected_page["subject_id"] == $subject["id"]) {
				$output .= "<ul class=\"pages\">";
				//gets pages of subject
				while ($page = mysqli_fetch_assoc($page_set)) {
					$output .= "<li";
					//highlights selected page
					if ($selected_page["id"] == $page["id"]) {
						$output .= " class=\"selected\"";
					}
					$output .= ">";
					$output .= "<a href=\"index.php?page=";
					$output .= urlencode($page["id"]);
					$output .= "\">";
					$output .= htmlentities($page["menu_name"]);
					$output .= "</a>";
					$output .= "</li>";
				}
				//free results
				mysqli_free_result($page_set);
				$output .= "</ul></li>";
			}
			//else show all pages if admin
		} else {
			$output .= "<ul class=\"pages\">";
			//gets pages of subject
			while ($page = mysqli_fetch_assoc($page_set)) {
				$output .= "<li";
				//highlights selected page
				if ($selected_page["id"] == $page["id"]) {
					$output .= " class=\"selected\"";
				}
				$output .= ">";
				$output .= "<a href=\"manage_content.php?page=";
				$output .= urlencode($page["id"]);
				$output .= "\">";
				$output .= htmlentities($page["menu_name"]);
				$output .= "</a>";
				$output .= "</li>";
			}
			//free results
			mysqli_free_result($page_set);
			$output .= "</ul></li>";
		}
	}
	mysqli_free_result($subject_set);
	$output .= "</ul>";
	return $output;
}

//Gets the current page the user is on
function getCurrentPage($public = false) {
	global $current_subject;
	global $current_page;
	//if subject is set and public, set default page to first page
	if (isset($_GET["subject"])) {
		$current_subject = findSubjectByID($_GET["subject"], $public);
		//if current subject is set
		if ($current_subject && $public) {
			$current_page = findDefaultPage($current_subject["id"]);
		} else {
			$current_page = null;
		}
		//gets page for admin
	} elseif (isset($_GET["page"])) {
		$current_subject = null;
		$current_page = findPageByID($_GET["page"], $public);
	} else {
		$current_subject = null;
		$current_page = null;
	}
}
