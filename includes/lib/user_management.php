<?php
require_once "db_functions.php";

/*
==================== User management functions =====================
*/

function get_users_roles () {
	$query = "SELECT role FROM users_roles";
	$roles = db_query($query);
	if (!empty($roles)) return $roles;
	else return null;
}

function get_users_role_by_id ($id) {
	if (!isset($id)) trigger_error("Argument expected.", E_USER_ERROR);
	if (!is_int($id) && $id < 1) trigger_error("Argument must be a scalar positive integer.", E_USER_ERROR);

	$query = "SELECT role FROM users_roles WHERE id={$id} LIMIT 1";

	if ($db_result = db_query($query)) return $db_result;

	return null;
}

function users_role_exists ($users_role) {
	if (!isset($users_role)) trigger_error("Argument expected.", E_USER_ERROR);
	if (!is_string($users_role)) trigger_error("Argument must be a string value.", E_USER_ERROR);

	$users_role = safe_str(trim($users_role));
	$query = "SELECT role FROM users_roles WHERE role='" . $users_role . "' LIMIT 1";
	$db_result = db_query($query);

	if ($db_result) return true;

	return false;
}

function get_users_role_id ($users_role) {
	if (!isset($users_role)) trigger_error("Argument expected.", E_USER_ERROR);
	if (!is_string($users_role)) trigger_error("Argument must be a string value.", E_USER_ERROR);

	$users_role = safe_str(trim($users_role));
	$query = "SELECT id FROM users_roles WHERE role='" . $users_role . "' LIMIT 1";
	$db_result = db_query($query);

	if (!emty($db_result)) return $db_result;

	return null;	
}

function get_users () {
	$query = "SELECT * FROM users";
	$users = db_query($query);
	if (!empty($users)) return $users;
	else return null;
}

function user_exists ($username) {
	if (!isset($username)) trigger_error("Argument expected.", E_USER_ERROR);
	if (!is_string($username)) trigger_error("Argument must be a string value.", E_USER_ERROR);
	
	$user = safe_str(trim($username));
	$query = "SELECT * FROM users WHERE username='" . $username . "' LIMIT 1";
	$db_result = db_query($query);
	if ($db_result) return true;

	return false;
}

function set_user ($username, $password, $first_name, $last_name, $role) {
	
	if (!isset($username) && !isset($password) && !isset($first_name) && !isset($last_name) && !isset($role))
	{
		trigger_error("Arguments expected.", E_USER_ERROR);
	}

	if (!is_string($username) && !is_string($password) && !is_string($first_name) && !is_string($last_name) && !is_string($role))
	{
		trigger_error("Arguments must be a string values.", E_USER_ERROR);
	}

	//hashing password
	$password = password_hash(trim($password), PASSWORD_DEFAULT);

	// make safe string values
	$username = safe_str(trim($username));
	$first_name = safe_str(trim($first_name));
	$last_name = safe_str(trim($last_name));
	$password  = safe_str(trim($password));
	$role = safe_str(trim($role));	

	if (!empty(get_users_roles()) && users_role_exists($role))
	{
		// get users roles id
		$role = get_users_role_id ($role);

		$query = "INSERT INTO users (";
		$query .= "username, password, first_name, last_name, role, registration_date";
		$query .= ") VALUES (";
		$query .= "{$username}, {$password}, {$first_name}, {$last_name}, {$role}, NOW()";
		$query .= ")";

		if (db_query($query)) return true;
	}
	else
	{
		trigger_error("Incorrect third argument.", E_USER_ERROR);
	}

	return false;
}