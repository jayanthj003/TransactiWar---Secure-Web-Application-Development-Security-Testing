<?php
function sanitize_input_string(string $input): string
{
    // Trim whitespace, remove HTML tags, and convert special characters to HTML entities
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function validate_username(string $input): bool
{
    //Username must be Alphanumeric and can contain special character _
    return preg_match('/^[a-zA-Z0-9_]+$/', $input);
}

function validate_password(string $password): bool
{
    return strlen($password) > 7;
}

function validate_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validate_signup_inputs(string $username, string $password, string $email): bool
{

    // Sanitize inputs
    $username = sanitize_input_string($username);
    $email = sanitize_input_string($email);

    // Validate inputs
    // Password size should be greater than 2
    // Email format validation
    if (
        validate_username($username) &&
        validate_password($password) &&
        validate_email($email)
    ) {
        return true;
    }
    return false;
}


function validate_signin_inputs(string $username, string $password): bool
{
    // Sanitize inputs
    $username = sanitize_input_string($username);

    // Validate inputs
    // Password size should be greater than 2
    if (
        validate_username($username) &&
        validate_password($password)
    ) {
        return true;
    }
    return false;
}


function validate_transactions_inputs(string $receiver_username, string $sender_username, string $amount): bool
{
    // Validate inputs
    if (
        $receiver_username != $sender_username &&
        validate_username($receiver_username) &&
        validate_username($sender_username) &&
        filter_var($amount, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])
    ) {
        return true;
    } else {
        return false;
    }
}
