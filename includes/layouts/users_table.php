<?php

function users_table () {

	require_once ("/../lib/user_management.php");

	// Получаем список пользователей
	// Функция get_users() объявлена в файле user_management.php
	$users = get_users();

	// указываем значение по умолчанию для таблицы пользователей
	$table_of_users = "";

	// Если список пользователей не пустой, то генерируем таблицу пользователей
	if (!empty($users))
	{
		$table_of_users = "<tr>";
		foreach ($users as $user) {
			// Получаем список ролей пользователей
			// Функция get_users_role_by_id () объявлена в файле user_management.php
			$users_role = ($users_role = get_users_role_by_id($user["role"])) ? $users_role : "";
			$table_of_users .= "<td>" . $user["username"] . "</td>";
			$table_of_users .= "<td>" . $user["first_name"] . "</td>";
			$table_of_users .= "<td>" . $user["last_name"] . "</td>";
			$table_of_users .= "<td>" . $users_role . "</td>";
			$table_of_users .= "<td>" . $user["registration_date"] . "</td>";
			$table_of_users .= "</tr>";
		} // end of foreach ($users as $user)
	} // endif (!empty($users))

	// возвращаем таблицу пользователей
	return $table_of_users;
} // end of function users_table()