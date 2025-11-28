<?php

declare(strict_types=1);

include_once __DIR__ . "/url-definitions.php";
include_once __DIR__ . "/src/api.php";

$request_uri = $_SERVER['REQUEST_URI'];
# Strip trailing '/' from request uri
if ($request_uri != '/' && substr($request_uri, -1, 1) == '/') {
	$request_uri = substr($request_uri, 0, -1);
}


switch ($request_uri) {
	case HOME:
		$_SESSION['csrf-token'] = bin2hex(random_bytes(32));
		handle_view_home($session_username);
		exit();
	case MY_PROFILE:
		$_SESSION['csrf-token'] = bin2hex(random_bytes(32));
		handle_view_profile($session_username, is_owner: true);
		exit();
	case str_starts_with($request_uri, OTHER_USER_PROFILE):
		$_SESSION['csrf-token'] = bin2hex(random_bytes(32));
		$view_username = substr($request_uri, mb_strlen("/profile/u/"));
		handle_view_profile($view_username, is_owner: false);
		exit();
	case str_starts_with($request_uri, SEARCH_USERS):
		handle_search_users();
		exit();
	case TRANSFER:
		$_SESSION['csrf-token'] = bin2hex(random_bytes(32));
		require __DIR__ . "/views/transfer.php";
		exit();
	case LOGOUT_HANDLER:
		handle_user_logout($session_id);
		exit();
	case PROFILE_PICTURE_UPDATE_HANDLER:
		handle_update_profile_picture($session_username);
		exit();
	case EMAIL_UPDATE_HANDLER:
		handle_update_email($session_username);
		exit();
	case DESCRIPTION_UPDATE_HANDLER:
		handle_update_description($session_username);
		exit();
	case PASSWORD_UPDATE_HANDLER:
		handle_update_password($session_username);
		exit();
	case CREATE_TRANSACTION:
		handle_create_transaction($session_username);
		exit();
	case str_starts_with($request_uri, GET_PROFILE_PICTURE_HANDLER):
		$picture_name = substr($request_uri, mb_strlen(GET_PROFILE_PICTURE_HANDLER));
		handle_get_profile_picture($picture_name);
		exit();
	case in_array($request_uri, UNAUTHENTICATED_URL_LIST):
		header("Location: " . HOME);
		exit();
	default:
		http_response_code(404);
		exit();
}
