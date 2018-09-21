<?php

function login_page () {

	// Указываем значения по умолчанию
	$input_username = "<input id=\"name\" type=\"text\" name=\"username\">";
	$input_password = "<input id=\"password\" type=\"password\" name=\"password\">";

	// Подготавливаем выводимую разметку
	$output = <<<HTML
	<form name = "login" action="{$_SERVER['PHP_SELF']}">
		<label for="name">Name:</label>{$input_username}<br>
		<label for="password">Password:</label>{$input_password}<br>
		<input type="hidden" name="login" value=""/>
		<input type="submit" value="login" name="submit">
	</form>
HTML;

	return $output;
} // end of function login_page()