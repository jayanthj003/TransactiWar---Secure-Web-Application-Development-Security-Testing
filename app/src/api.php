<?php

declare(strict_types=1);

include_once __DIR__ . "/utils/db-utils.php";
include_once __DIR__ . "/utils/input-sanatization-utils.php";

function handle_login(): void
{
	if (!isset($_POST['csrf-token']) || $_POST['csrf-token'] != $_SESSION['csrf-token']) {
		syslog(LOG_INFO, "ERROR: Invalid CSRF Token: " . $_POST['csrf-token'] . " " . $_SESSION['csrf-token']);
		header("Location: " . LOGIN);
		exit();
	}
	if (!isset($_POST['username']) || !isset($_POST['password'])) {
		$_SESSION["login-error"] = "Input fields cannot be empty";
		header("Location: " . LOGIN);
		exit();
	}
	if (!validate_signin_inputs($_POST['username'], $_POST['password'])) {
		$_SESSION["login-error"] = "Invalid Credentials";
		header("Location: " . LOGIN);
		exit();
	}
	$username = $_POST['username'];
	$password = $_POST['password'];
	$password_hash = get_user_password($username);

	if ($password_hash == null or !password_verify($password, $password_hash)) {
		$_SESSION["login-error"] = "Invalid Credentials";
		header("Location: " . LOGIN);
		exit();
	}

	# Create and set cookie, redirect to login page
	$cookie_value = bin2hex(random_bytes(16));
	$_SESSION[$cookie_value] = $username;
	header("Location: " . HOME);
	setcookie(
		"session",
		$cookie_value,
		[
			'path' => '/',
			'secure' => true,
			'httponly' => true,
			'samesite' => 'strict'
		]
	);
	exit();
}

function handle_user_logout(string $session_id)
{
	if (!isset($_POST['csrf-token']) || $_POST['csrf-token'] != $_SESSION['csrf-token']) {
		header("Location: " . HOME);
		exit();
	}
	unset($_SESSION[$session_id]);
	unset($_COOKIE["session"]);
	setcookie("session", "", time() - 3600, path: '/');
	header("Location: " . LOGIN);
	exit();
}


// Create a new user
function handle_signup(): bool
{
	if (!isset($_POST['csrf-token']) || $_POST['csrf-token'] != $_SESSION['csrf-token']) {
		header("Location: " . SIGNUP);
		exit();
	}
	# Check if all input attributes are set
	if (!isset($_POST['username']) && !isset($_POST['password']) && !isset($_POST['confirm-password']) && !isset($_POST['email'])) {
		$_SESSION["signup-error"] = "Input fields cannot be empty";
		header("Location: " . SIGNUP);
		exit();
	}
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm-password'];

	# Redirect to signup on invalid inputs
	if (
		!validate_signup_inputs($username, $password, $email) ||
		strcmp($password, $confirm_password) != 0
	) {
		$_SESSION["signup-error"] = "Invalid credentials";
		header("Location: " . SIGNUP);
		exit();
	}

	if (!create_new_user($username, $email, $password) == 0) {
		$_SESSION["signup-error"] = "Username already exists";
		header("Location: " . SIGNUP);
		exit();
	}

	# User created, redirect to login
	header("Location: " . LOGIN);
	$_SESSION["signup-success"] = $username;
	exit();
}

function handle_view_profile(string $username, bool $is_owner)
{
	# Page to view other user's profile
	if (is_null($data = get_user_profile_info($username))) {
		http_response_code(404);
	} else {
		# To send the profile picture, there's an apache mod_rewrite rule to serve images directly
		# any url starting with /picture/.*\.png will be served from ./data/profile-pictures/
		$data['profile_picture_path'] = GET_PROFILE_PICTURE_HANDLER . $data['profile_picture_path'];
		$data['is_owner'] = $is_owner;
		require __DIR__ . "/../views/profile.php";
	}
}

function handle_view_home(string $username)
{
	$data = get_home_page_info($username);
	$data["profile_picture_path"] = GET_PROFILE_PICTURE_HANDLER . $data["profile_picture_path"];

	$transactions = [];
	foreach ($data["transactions"] as $transaction) {
		$formatted_transaction_row = $transaction;
		unset($formatted_transaction_row["sender_username"]);
		unset($formatted_transaction_row["receiver_username"]);
		unset($formatted_transaction_row["amount_sent"]);

		$formatted_transaction_row["username"] = $transaction["sender_username"] == $username ? $transaction["receiver_username"] : $transaction["sender_username"];
		$amount_sent = $transaction["sender_username"] == $username ? -1 * $transaction["amount_sent"] : $transaction["amount_sent"];
		$formatted_transaction_row["amount"] = floatval($amount_sent);

		array_push($transactions, $formatted_transaction_row);
	}


	$data["transactions"] = $transactions;
	require __DIR__ . "/../views/home.php";
}

function handle_update_email(string $username)
{
	if (!isset($_POST['csrf-token']) || $_POST['csrf-token'] != $_SESSION['csrf-token']) {
		header("Location: " . MY_PROFILE);
		exit();
	}
	if (!isset($_POST['email']) || $_POST['email'] == '') {
		$_SESSION["update-error"] = "Email cannot be empty";
		header("Location: " . MY_PROFILE);
		exit();
	}
	$email = sanitize_input_string($_POST['email']);
	if (!($email = filter_var($email, FILTER_VALIDATE_EMAIL)) || !update_user_profile($username, "email", $email)) {
		$_SESSION["update-error"] = "Email invalid/unchanged";
		header("Location: " . MY_PROFILE);
		exit();
	}

	unset($_SESSION["update-error"]);
	$_SESSION["update-success"] = "Email updated";
	header("Location: " . MY_PROFILE);
}

function handle_update_description(string $username)
{
	if (!isset($_POST['csrf-token']) || $_POST['csrf-token'] != $_SESSION['csrf-token']) {
		header("Location: " . MY_PROFILE);
		exit();
	}
	if (!isset($_POST['description']) || $_POST['description'] == '') {
		$_SESSION["update-error"] = "Description cannot be empty";
		header("Location: " . MY_PROFILE);
		exit();
	}
	$description = sanitize_input_string($_POST['description']);
	if (!update_user_profile($username, "description", $description)) {
		$_SESSION["update-error"] = "Description unchanged/to long";
		header("Location: " . MY_PROFILE);
		exit();
	}

	unset($_SESSION["update-error"]);
	$_SESSION["update-success"] = "Description updated";
	header("Location: " . MY_PROFILE);
}

function handle_update_password(string $username)
{
	if (!isset($_POST['csrf-token']) || $_POST['csrf-token'] != $_SESSION['csrf-token']) {
		header("Location: " . MY_PROFILE);
		exit();
	}

	# Check if all input attributes are set
	if (!isset($_POST['old-password']) && !isset($_POST['new-password']) && !isset($_POST['confirm-new-password'])) {
		$_SESSION["update-error"] = "Password cannot be empty";
		header("Location: " . MY_PROFILE);
		exit();
	}
	$old_password = $_POST['old-password'];
	$new_password = $_POST['new-password'];
	$confirm_new_password = $_POST['confirm-new-password'];

	# Verify old password
	$password_hash = get_user_password($username);
	if ($password_hash == null or !password_verify($old_password, $password_hash)) {
		$_SESSION["update-error"] = "Incorrect Password";
		header("Location: " . MY_PROFILE);
		exit();
	}

	# Redirect to profile on invalid inputs
	if (
		!validate_password($new_password) ||
		strcmp($new_password, $confirm_new_password) != 0
	) {
		$_SESSION["update-error"] = "Invalid new password";
		header("Location: " . MY_PROFILE);
		exit();
	}

	# Update database
	if (!update_user_password($username, $new_password)) {
		$_SESSION["update-error"] = "Password Unchanged";
		header("Location: " . MY_PROFILE);
		exit();
	}

	unset($_SESSION["update-error"]);
	$_SESSION["update-success"] = "Password changed";
	header("Location: " . MY_PROFILE);
}

// Profile picture update
# The following sanitization is performed on the uploaded file 
# 1. Check if image is uploaded using is_uploaded_file()
# 2. Image mime type with Fileinfo
# 3. Check extension using pathinfo and basename
# 4. Use GD to create image 
# 5. Use GD to scale down image
# 6. Generate uuid name with time_stamp for the image
# 7. Store uuid name in sql database
# 8. Move file to /var/www/data/profile-pictures using move_uploaded_image
# 9. Remove exec permission from file 
# The images are stored in /var/www/data/profile-pictures/*.png
const PROFILE_PICTURE_UPLOAD_DIR = "/var/www/data/profile-pictures";
function handle_update_profile_picture(string $username)
{
	if (!isset($_POST['csrf-token']) || $_POST['csrf-token'] != $_SESSION['csrf-token']) {
		header("Location: " . MY_PROFILE);
		exit();
	}
	if (!isset($_FILES['profile-picture']['tmp_name']) || $_FILES['profile-picture']['error'] != 0) {
		$_SESSION["update-error"] = "Empty profile picture/picture size exceeded 2MB";
		header("Location: " . MY_PROFILE);
		exit();
	}
	$allowed_extensions = ['png'];
	$allowed_mime_types = ['image/png'];

	$temp_file_name = $_FILES['profile-picture']['tmp_name'];
	$file_extension = strtolower(pathinfo(basename($_FILES['profile-picture']['name']), PATHINFO_EXTENSION));
	$file_mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $temp_file_name);
	if (
		!in_array($file_mime_type, $allowed_mime_types) ||
		!in_array($file_extension, $allowed_extensions) ||
		!is_uploaded_file($temp_file_name)
	) {
		$_SESSION["update-error"] = "Invalid Image. Only png files are supported";
		header("Location: " . MY_PROFILE);
		exit();
	}

	$image = @imagecreatefrompng($temp_file_name);
	if (!$image) {
		$_SESSION["update-error"] = "Invalid Image. Only png files are supported";
		header("Location: " . MY_PROFILE);
		exit();
	}

	$image = @imagescale($image, width: 250, height: 250);
	if (!$image) {
		$_SESSION["update-error"] = "Invalid Image";
		header("Location: " . MY_PROFILE);
		exit();
	}

	$old_file_name = get_profile_picture_path($username);
	if (is_null($old_file_name)) {
		header("Location: " . MY_PROFILE);
		syslog(LOG_ERR, "ERROR: Old profile picture name is null");
		exit();
	}

	# create new file name
	$new_file_name = bin2hex(random_bytes(12)) . ".png";
	if (
		!update_user_profile($username, "profile_picture_path", $new_file_name) ||
		!imagepng($image, join(DIRECTORY_SEPARATOR, [PROFILE_PICTURE_UPLOAD_DIR, $new_file_name]))
	) {
		$_SESSION["update-error"] = "Invalid Image";
		header("Location: " . MY_PROFILE);
		exit();
	}

	# delete the old file
	if (strcmp($old_file_name, "default-user-icon.png") != 0) {
		$status = unlink(join(DIRECTORY_SEPARATOR, [PROFILE_PICTURE_UPLOAD_DIR, $old_file_name]));
		$status = $status ? "SUCCESSFUL" : "FAILED";
		syslog(LOG_INFO, "WARN: old profile picture delete status: $status");
	}

	unset($_SESSION["update-error"]);
	$_SESSION["update-success"] = "Profile Picture updated";
	header("Location: " . MY_PROFILE);
}


function handle_search_users()
{
	$search_username = array_key_exists('username', $_GET) ? $_GET['username'] : '';
	if (validate_username($search_username) || strcmp($search_username, '') == 0) {
		$data['search-query'] = $search_username;
		if (($users_list = get_all_users($search_username)) != null) {
			$data['users'] = $users_list;
		}
	}
	require __DIR__ . "/../views/search_users.php";
}


function handle_create_transaction(string $current_user)
{
	if (!isset($_POST['csrf-token']) || $_POST['csrf-token'] != $_SESSION['csrf-token']) {
		header("Location: " . TRANSFER);
		exit();
	}
	if (!isset($_POST['receiver-username']) || !isset($_POST['amount']) || !isset($_POST['remark'])) {
		$_SESSION["transfer-error"] = "Transfer Failed: Invalid Username/Amount";
		header("Location: " . TRANSFER);
		exit();
	}
	$receiver_username = $_POST['receiver-username'];
	$amount = $_POST['amount'];
	$remark = sanitize_input_string($_POST['remark']);
	if (!validate_transactions_inputs($receiver_username, $current_user, $amount)) {
		$_SESSION["transfer-error"] = "Transfer Failed: Invalid Username/Amount";
		header("Location: " . TRANSFER);
		exit();
	}

	$errno = create_new_transaction($current_user, $receiver_username, intval($amount), $remark);
	if ($errno == 0) {
		$_SESSION["transfer-success"] = "Transfer success";
	} else {
		$_SESSION["transfer-error"] = "Transfer Failed: " . TRANSACTION_ERRORS[$errno];
	}
	header("Location: " . TRANSFER);
}

function handle_get_profile_picture(string $picture_name)
{
	$picture_name = basename($picture_name);
	$picture_path = join(DIRECTORY_SEPARATOR, [PROFILE_PICTURE_UPLOAD_DIR, $picture_name]);

	if (!file_exists($picture_path)) {
		http_response_code(404);
		exit();
	}

	header("Content-Type: image/png");
	# If the readfile fails send a 404 error
	if (!readfile($picture_path)) {
		http_response_code(404);
		exit();
	}
	ob_flush();
}

// NOTE: These functions are not used anymore, since file upload/download is not required

/*const FILE_UPLOAD_DIR = "/var/www/data/files";
function handle_update_file(string $username)
{
	if (!isset($_POST['csrf-token']) || $_POST['csrf-token'] != $_SESSION['csrf-token']) {
		header("Location: " . MY_PROFILE);
		exit();
	}
	if (!isset($_FILES['uploaded_file']['tmp_name']) || $_FILES['uploaded_file']['error'] != 0) {
		$_SESSION["update-error"] = "Empty file/file size exceeded 2MB";
		header("Location: " . MY_PROFILE);
		exit();
	}
	$allowed_extensions = ['txt'];
	$allowed_mime_types = ['text/plain'];

	$temp_file_name = $_FILES['uploaded_file']['tmp_name'];
	$file_extension = strtolower(pathinfo(basename($_FILES['uploaded_file']['name']), PATHINFO_EXTENSION));
	$file_mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $temp_file_name);
	if (
		!in_array($file_mime_type, $allowed_mime_types) ||
		!in_array($file_extension, $allowed_extensions) ||
		!is_uploaded_file($temp_file_name)
	) {
		$_SESSION["update-error"] = "Invalid File. Only plain text files are supported";
		header("Location: " . MY_PROFILE);
		exit();
	}

	$old_file_name = get_uploaded_file_name($username);
	$new_file_name = bin2hex(random_bytes(12)) . ".txt";
	if (
		!update_user_profile($username, "uploaded_file_name", $new_file_name) ||
		!move_uploaded_file($_FILES['uploaded_file']['tmp_name'], join(DIRECTORY_SEPARATOR, [FILE_UPLOAD_DIR, $new_file_name]))
	) {
		$_SESSION["update-error"] = "Invalid File";
		header("Location: " . MY_PROFILE);
		exit();
	}

	# delete the old file
	if (!is_null($old_file_name)) {
		$status = unlink(join(DIRECTORY_SEPARATOR, [FILE_UPLOAD_DIR, $old_file_name]));
		$status = $status ? "SUCCESSFUL" : "FAILED";
		syslog(LOG_INFO, "WARN: old profile picture delete status: $status");
	}

	unset($_SESSION["update-error"]);
	$_SESSION["update-success"] = "File uploaded";
	header("Location: " . MY_PROFILE);
}

function handle_download_file(string $username)
{
	if (!isset($_POST['csrf-token']) || $_POST['csrf-token'] != $_SESSION['csrf-token']) {
		header("Location: " . MY_PROFILE);
		exit();
	}

	# Get the file name
	$file_name = get_uploaded_file_name($username);
	$file_path = join(DIRECTORY_SEPARATOR, [FILE_UPLOAD_DIR, $file_name]);
	if (is_null($file_name) || !file_exists($file_path)) {
		$_SESSION["update-error"] = "No file uploaded";
		header("Location: " . MY_PROFILE);
		exit();
	}

	# Send file 
	header('Content-Description: File Transfer');
	header('Content-Type: text/plain');
	header('Content-Disposition: attachment; filename=' . basename($file_name));
	ob_clean();
	flush();
	readfile($file_path);
}
*/
