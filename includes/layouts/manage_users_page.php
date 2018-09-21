<?php

function manage_users_page ($users_table = "") {

	// Проверяем входные данные
	if (!is_string($users_table)) trigger_error("Argument should be a string value.", E_USER_ERROR);

	// Возвращаем разметку содержимого страницы
	return <<< HTML
	<h1>Управление пользователями</h1>
	<a href="/admin/manage_users/add">Добавление нового пользователя</a>
	<table>
		<tr>
			<th>Логин пользователя</th><th>Имя пользователя</th><th>Фамилия пользователя</th><th>Роль пользователя</th><th>Дата регистрации</th>
		</tr>
		<tr>
			{$users_table}
		</tr>
	</table>
HTML;
} // end of function manage_users_page ();
