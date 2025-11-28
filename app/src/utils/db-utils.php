<?php

declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function get_db_connection(): mysqli|null
{
	$db = null;
	try {
		// getenv is known to cause issues in some OS. getenv() function returns a different value 
		// rather that the value present in .env file. If database access is denied 
		// (PHP error prepare() got null ), try hardcoding the password from the .env file
		$db = new mysqli(getenv('MYSQL_HOSTNAME'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
	} catch (mysqli_sql_exception $e) {
		syslog(LOG_ERR, $e->getMessage() . $e->getTraceAsString());
	}
	return $db;
}

// This function does not perform input validation before creating the user, add the login in the calling function
// Returns the 0 if successful, non zero error code
function create_new_user(string $username, string $email, string $password, int $balance = 100): int
{
	$password_hash = password_hash($password, PASSWORD_BCRYPT);
	$default_description = "No description added";
	$default_icon_name = "default-user-icon.png";
	try {
		$db = get_db_connection();
		$query = $db->prepare(
			"insert into 
			users(username, email, description, profile_picture_path, password, balance) 
			values(?, ?, ?, ?, ?, ?);"
		);
		$query->bind_param("sssssi", $username, $email, $default_description, $default_icon_name, $password_hash, $balance);
		$query->execute();
	} catch (Exception $e) {
		syslog(LOG_ERR, $e->getCode() . " " . $e->getMessage() . " " . $e->getTraceAsString());
		return $query->errno;
	}

	return $query->errno;
}


// Return password hash if user exists or null 
function get_user_password(string $username): string|null
{
	$password_hash = null;
	try {
		$db = get_db_connection();
		$query = $db->prepare("select password from users where username = ?;");
		$query->bind_param("s", $username);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows == 1) {
			$password_hash = $result->fetch_assoc()['password'];
		}
	} catch (Exception $e) {
		syslog(LOG_ERR, $e->getCode() . " " . $e->getMessage() . " " . $e->getTraceAsString());
	}

	return $password_hash;
}

# Return the user profile info as an associative array
# Associative array contains the following keys
#	username, email, description and profile_picture_path 
function get_user_profile_info(string $username): array|null
{
	$user_info = null;
	try {
		$db = get_db_connection();
		$query = $db->prepare("select username, email, description, profile_picture_path from users where username = ?;");
		$query->bind_param("s", $username);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows == 1) {
			$user_info = $result->fetch_assoc();
		}
	} catch (Exception $e) {
		syslog(LOG_ERR, $e->getCode() . " " . $e->getMessage() . " " . $e->getTraceAsString());
	}

	return $user_info;
}


# Home page displays, the username, balance and past transactions
# Return format: associative array
#	Keys - Value: 
#		"username" - username in string format
#		"balance" - balance in int format
#		"profile_picture_path" - profile picture path
#		"transactions" - list of transactions
#	Transactions array entry format:
#		["transaction_time": <timestamp>, 
#		"sender_username": <username>, 
#		"receiver_username": <username>,
#		"transaction_remark": <transaction_message>, 
#		"amount": <+ve int>]
function get_home_page_info(string $username): array|null
{
	$user_info = null;
	try {
		$db = get_db_connection();
		$query = $db->prepare("select username, profile_picture_path, balance from users where username = ?;");
		$query->bind_param("s", $username);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows != 1) {
			return $username;
		}
		$user_balance = $result->fetch_assoc();

		# Get all the transactions for the user
		$query = $db->prepare(
			"select transaction_time, sender_username, receiver_username, transaction_remark, amount_sent
			from transactions where sender_username = ? or receiver_username = ? order by transaction_time desc;"
		);
		$query->bind_param("ss", $username, $username);
		$query->execute();
		$result = $query->get_result();
		if ($query->errno != 0 || !$result) {
			return null;
		}
		$user_info = $user_balance;
		$user_info['transactions'] = $result->fetch_all(MYSQLI_ASSOC);
	} catch (Exception $e) {
		syslog(LOG_ERR, $e->getCode() . " " . $e->getMessage() . " " . $e->getTraceAsString());
	}
	return $user_info;
}

# Returns all the users with like $username_serach_query
function get_all_users(string $username_search_query = ""): array|null
{
	$username_search_query = "%$username_search_query%";
	$usernames = null;
	try {
		$db = get_db_connection();
		$query = $db->prepare("select username from users where username like ? collate utf8mb4_0900_ai_ci;");
		$query->bind_param("s", $username_search_query);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows >= 0) {
			$usernames = array_column($result->fetch_all(MYSQLI_NUM), 0);
		}
	} catch (Exception $e) {
		syslog(LOG_ERR, $e->getCode() . " " . $e->getMessage() . " " . $e->getTraceAsString());
	}

	return $usernames;
}

function username_exists(string $username): bool
{
	$user_exists = false;
	try {
		$db = get_db_connection();
		$query = $db->prepare("select username from users where username = ?;");
		$query->bind_param("s", $username);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows == 1) {
			$user_exists = true;
		}
	} catch (Exception $e) {
		syslog(LOG_ERR, $e->getCode() . " " . $e->getMessage() . " " . $e->getTraceAsString());
	}

	return $user_exists;
}

function get_profile_picture_path(string $username): string| null
{
	$profile_picture_path = null;
	try {
		$db = get_db_connection();
		$query = $db->prepare("select profile_picture_path from users where username = ?;");
		$query->bind_param("s", $username);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows == 1) {
			$profile_picture_path = $result->fetch_column();
		}
	} catch (Exception $e) {
		syslog(LOG_ERR, $e->getCode() . " " . $e->getMessage() . " " . $e->getTraceAsString());
	}

	return $profile_picture_path;
}

function get_uploaded_file_name(string $username): string| null
{
	$uploaded_file_name = null;
	try {
		$db = get_db_connection();
		$query = $db->prepare("select uploaded_file_name from users where username = ?;");
		$query->bind_param("s", $username);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows == 1) {
			$uploaded_file_name = $result->fetch_column();
		}
	} catch (Exception $e) {
		syslog(LOG_ERR, $e->getCode() . " " . $e->getMessage() . " " . $e->getTraceAsString());
	}

	return $uploaded_file_name;
}


# Update profile, description, and email of a user
#	$change_property valid values: "description, email, profile_picture_path"
function update_user_profile(string $username, string $change_property, string $property_value): bool
{
	if (!in_array($change_property, ["description", "email", "profile_picture_path", "uploaded_file_name"])) {
		syslog(LOG_ERR, "ERROR: using invalid user property in db update utils");
		return false;
	}
	$update_query = "update users set $change_property = ? where username = ?";
	$update_success = false;
	try {
		$db = get_db_connection();
		$query = $db->prepare($update_query);
		$query->bind_param("ss", $property_value, $username);
		$query->execute();
		if ($query->errno == 0 && $query->affected_rows == 1) {
			$update_success = true;
		}
	} catch (Exception $e) {
		syslog(LOG_ERR, $e->getCode() . " " . $e->getMessage() . " " . $e->getTraceAsString());
	}
	return $update_success;
}

function update_user_password(string $username, string $new_password): bool
{
	$password_hash = password_hash($new_password, PASSWORD_BCRYPT);
	$update_query = "update users set password = ? where username = ?";
	$update_success = false;
	try {
		$db = get_db_connection();
		$query = $db->prepare($update_query);
		$query->bind_param("ss", $password_hash, $username);
		$query->execute();
		if ($query->errno == 0 && $query->affected_rows == 1) {
			$update_success = true;
		}
	} catch (Exception $e) {
		syslog(LOG_ERR, $e->getCode() . " " . $e->getMessage() . " " . $e->getTraceAsString());
	}
	return $update_success;
}

const TRANSACTION_ERRORS = [1 => "Invalid Username", 1406 => "Remark too long",  3819 => "Insufficient Balance"];
function create_new_transaction(string $sender_uname, string $receiver_uname, float $amount, string $description): int
{
	try {
		$db = get_db_connection();
		$db->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

		# Lock the sender and receiver records
		$query = $db->prepare("select username, balance from users where username = ? or username = ? for update");
		$query->bind_param("ss", $sender_uname, $receiver_uname);
		$query->execute();
		$result = $query->get_result();
		if ($query->errno != 0 || $result->num_rows != 2) {
			return 1;
		}

		# Store the sender and receiver balance
		$balance = [];
		$row = $result->fetch_assoc();
		$balance[$row['username']] = $row['balance'];
		$row = $result->fetch_assoc();
		$balance[$row['username']] = $row['balance'];

		# Update the balance
		$balance[$sender_uname] -= $amount;
		$balance[$receiver_uname] += $amount;

		# Add record to the transaction table
		$transaction_id = random_bytes(16);
		$query = $db->prepare(
			"insert into transactions
			(transaction_id, sender_username, receiver_username, amount_sent, transaction_remark) values 
			(?, ?, ?, ?, ?)"
		);
		$query->bind_param("sssis", $transaction_id, $sender_uname, $receiver_uname, $amount, $description);
		$query->execute();
		if ($query->errno != 0 || $query->affected_rows != 1) {
			throw new Exception("ERROR: Insert Transaction failed. " . $query->errno);
		}

		# Update the balance
		$query = $db->prepare("update users set balance = ? where username = ?");
		$query->bind_param("is", $balance[$sender_uname], $sender_uname);
		$query->execute();
		if ($query->errno != 0 || $query->affected_rows != 1) {
			throw new Exception("ERROR: Update balance failed. " . $query->errno);
		}
		$query->bind_param("is", $balance[$receiver_uname],  $receiver_uname);
		$query->execute();
		if ($query->errno != 0 || $query->affected_rows != 1) {
			throw new Exception("ERROR: Update balance failed. " . $query->error);
		}

		$db->commit();
	} catch (Exception $e) {
		syslog(LOG_ERR, $e->getCode() . " " . $e->getMessage() . " " . $e->getTraceAsString());
		$db->rollback();
	}
	return $query->errno;
}
