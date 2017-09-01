<?php
//confirms query success
function confirmQuery($result_set) {
	if (!$result_set) {
		die("Database query failed.");
	}
}

//redirects to a new page
function redirect($new_location) {
	header("Location: " . $new_location);
	exit;
}

//shows error messages
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

//gets all admins
function findAllAdmins() {
	global $mysqli;
	$query = "SELECT * FROM admins";
	$admin_set = mysqli_query($mysqli, $query);
	confirmQuery($admin_set);
	return $admin_set;
}

//finds an admin using the given id
function findAdminByID($admin_id) {
	global $mysqli;
	$admin_id = mysqli_real_escape_string($mysqli, $admin_id);
	$query = "SELECT * FROM admins WHERE id = {$admin_id}";
	$admin_set = mysqli_query($mysqli, $query);
	confirmQuery($admin_set);
	if ($admin = mysqli_fetch_assoc($admin_set)) {
		return $admin;
	} else {
		return null;
	}
}

//finds an admin using the given username
function findAdminByUsername($username) {
	global $mysqli;
	$username = mysqli_real_escape_string($mysqli, $username);
	$query = "SELECT * FROM admins WHERE username = '{$username}' LIMIT 1";
	$admin_set = mysqli_query($mysqli, $query);
	confirmQuery($admin_set);
	if ($admin = mysqli_fetch_assoc($admin_set)) {
		return $admin;
	} else {
		return null;
	}
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

//finds a subject using the given id, default visibility
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

//finds a page using the given id, default visibility
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
			$output .= "</ul></li>";
		}
	}
	//free results
	mysqli_free_result($page_set);
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
//encrypts a salted password using blowfish
function passwordEncrypt($password) {
	$hash_format = "$2y$10$"; //blowfish - run 10 times
	$salt = generateSalt(22); //22 chars or more for blowfish salt length
	$format_and_salt = $hash_format . $salt;
	$hash = crypt($password, $format_and_salt);
	return $hash;
}

//generates a 22 char salt used for blowfish
function generateSalt($length) {
	//MD5 returns 32 characters
	$unique_random_string = md5(uniqid(mt_rand(), true));
	//Valid salt characters [a-zA-Z0-9./] but not +
	$base_64_string = str_replace('+', '.', base64_encode($unique_random_string));
	//return the first 22 chars to use for blowfish salt
	$salt = substr($base_64_string, 0, $length);
	return $salt;
}

//authenticates password using the existing hash
//which consists of the format and salt
function checkPassword($password, $existing_hash) {
	$hash = crypt($password, $existing_hash);
	if ($hash === $existing_hash) {
		return true;
	} else {
		return false;
	}
}

function attemptLogin($username, $password) {
	//find if inputed username returns an admin in database
	$admin = findAdminByUsername($username);
	//if found, check if inputed password matches hashed password
	//in database
	if ($admin) {
		//returns admin if sucessful login
		//returns false is password does not match
		if (checkPassword($password, $admin["password"])) {
			return $admin;
		} else {
			return false;
		}
	} else {
		//admin username not found
		return false;
	}
}

//confirms if user is logged in
function confirmLogin() {
	if (!isset($_SESSION["admin_id"])) {
		redirect("login.php");
	}
}