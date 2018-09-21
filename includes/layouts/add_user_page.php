<?php

function add_user_page ($users_roles) {

	// Проверяем входные данные
	if (!isset($users_roles))
	{
		trigger_error("Argument expected.", E_USER_ERROR);
	} // endif (!isset($users_roles))
	elseif (is_null($users_roles) || !is_array($users_roles))
	{
		trigger_error("Argument must be an array.", E_USER_ERROR);
	} // end of elseif (!is_null($users_roles) || !is_array($users_roles))
	
	// Инициализируем переменные
	$error_msg = "";
	$select_role = "";
	$input_username = "<input type=\"text\" name=\"username\" id=\"username\"";
	$input_password = "<input type=\"password\" name=\"password\" id=\"password\"";
	$input_first_name = "<input type=\"text\" name=\"first_name\" id=\"first_name\"";
	$input_last_name = "<input type=\"text\" name=\"last_name\" id=\"last_name\"";
	$input_email = "<input type=\"email\" name=\"email\" id=\"email\"";
	
	// Переменная $role нужна для хранения атрибута value, а также для временного хранения класса элемента с ошибкой
	$role = "";

	// Если в базе указаны роли, то будет возможность выбора ролей
	if (!empty($users_roles))
	{
		// Подготавливаем разметку для выбора полей
		$select_role = <<< HTML
		<label for="role">Роль пользователя:</label>
		<select name = "role">
HTML;
		foreach ($users_roles as $users_role) {
			
			// Подготавливаем разметку со списком ролей пользователя
			$select_role .= "<option";
			
			// если для элемента применен класс ошибки, то добавляем его в вывод
			if (strstr($role, "class"))
			{
				$select_role .= $role;

				// Очищаем временную переменную. Эта переменная будет нужна для хранения атрибута value
				$role = "";
			}

			$select_role .= ">{$users_role}</option>";	
		} // end of foreach ($users_roles as $users_role)

		$select_role .= "</select><br>";
	} // endif (!empty($users_roles))

	// Закрываем теги
	$input_username .= ">";
	$input_password .= ">";
	$input_first_name .= ">";
	$input_last_name .= ">";
	$input_email .= ">";

	// Возвращаем разметку
	return <<< HTML
	<h1>Добавление нового пользователя</h1>
	<form action="/admin/manage_users/add" name="add_user" method="POST">
		<label for="username">Логин пользователя:</label>{$input_username}<br>
		<label for="password">Пароль пользователя:</label>{$input_password}<br>
		<label for="first_name">Имя пользователя:</label>{$input_first_name}<br>
		<label for="last_name">Фамилия пользователя:</label>{$input_last_name}<br>
		<label for="email">Электронная почта пользователя:</label>{$input_email}<br>
		{$select_role}
		<input type="hidden" name="add_user" value=""/>
		<input type="submit" name="submit" value="Добавить">
		<input type="reset" value="Сбросить значения">
		{$error_msg}
	</form>
HTML;
} // end of function add_user_page ()