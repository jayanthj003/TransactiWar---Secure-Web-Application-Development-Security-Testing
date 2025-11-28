<?php

declare(strict_types=1);

openlog("app-log", LOG_PID | LOG_PERROR, LOG_LOCAL0);

include_once __DIR__ . "/src/utils/db-utils.php";

syslog(LOG_INFO, "INFO: User request: " . $_SERVER['REMOTE_ADDR'] . " " . $_SERVER['REQUEST_URI'] . " " . $_SERVER['REQUEST_METHOD']);

// Authentication code.
$is_valid_cookie = false;
if (isset($_COOKIE['session'])) {
	$session_cookie = $_COOKIE['session'];
	$is_valid_cookie = isset($_SESSION[$session_cookie]) && username_exists($_SESSION[$session_cookie]);
}

if (!$is_valid_cookie) {
	# Reset the session cookie if the user set it manually
	setcookie("session", "", time() - 3600, path: '/');
	require __DIR__ . "/unauthenticated-url-handlers.php";
	exit();
} else {
	# To make the username of the user accessing the session set this variable
	$session_username = $_SESSION[$_COOKIE['session']];
	$session_id = $_COOKIE['session'];
	require __DIR__ . "/authenticated-url-handlers.php";
	exit();
}
