<?php

declare(strict_types=1);

include_once __DIR__ . "/url-definitions.php";
include_once __DIR__ . "/src/api.php";


# Get request URL and strip trailing '/' from request URL
$request_uri = $_SERVER['REQUEST_URI'];
if ($request_uri != '/' && substr($request_uri, -1, 1) == '/') {
	$request_uri = substr($request_uri, 0, -1);
}


# Generate a CSRF token only if user requested LOGIN or SIGNUP page
if (in_array($request_uri, [LOGIN, SIGNUP])) {
	$_SESSION['csrf-token'] = bin2hex(random_bytes(32));
}

switch ($request_uri) {
	case LOGIN:
		require __DIR__ . "/views/login.php";
		exit();
	case SIGNUP:
		require __DIR__ . "/views/signup.php";
		exit();
	case LOGIN_HANDLER:
		handle_login();
		exit();
	case SIGNUP_HANDLER:
		handle_signup();
		exit();
	case str_starts_with($request_uri, OTHER_USER_PROFILE):
	case in_array($request_uri, AUTHENTICATED_URL_LIST):
	case '/':
		header("Location: " . LOGIN);
		exit();
	default:
		http_response_code(403);
		exit();
}
