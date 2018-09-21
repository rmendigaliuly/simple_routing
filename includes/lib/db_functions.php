<?php

// users_roles

// role varchar(30) | number tinyint(1)

// users
// username varchar(50) | password varchar(255) | first_name varchar(50) | last_name varchar(50) | role tinyint(1) | registration_date datetime

function confirm_db_query ($result, $db_connection) {
	// Проверяем входные данные
	if (!isset ($result) && !isset($db_connection))
	{
		trigger_error("Arguments expected.", E_USER_ERROR);
	} // endif (!isset ($result) && !isset($db_connection))

	// Если нет ответа от базы данных, то генерируем ошибку
	if (!$result) trigger_error("Database query failed: " . mysqli_error($db_connection), E_USER_ERROR);
}

function db_connect () {

	// Создаем подключение в базу данных
	$db_connection = mysqli_connect("localhost", "root", "", "first_procedural_blog");

	// Если произошла ошибка подключения, то генерируем ошибку
  	if(mysqli_connect_errno())
  	{
    	trigger_error("Database connection failed: " . 
        	mysqli_connect_error() . 
        	" (" . mysqli_connect_errno() . ")"
    	, E_USER_ERROR);
  	}
  	else
  	{
  		// Если нет ошибок подключения, то возвращаем точку подключения в базу данных
  		return $db_connection;
  	}	
}

function safe_str ($string) {
	// Проверяем входные данные
	if (!isset($string)) trigger_error("Argument expected.", E_USER_ERROR);

	if (!is_string($string)) trigger_error("Argument must be a string value.", E_USER_ERROR);
	// Функция принимает строку и возвращает экранированную строку
	$db_connection = db_connect();
	return mysqli_real_escape_string ($db_connection, $string);
}

function db_query ($query) {

	if (!isset($query)) trigger_error("Argument expected.", E_USER_ERROR);
	elseif (!is_string($query)) trigger_error("Argument must be a string value.", E_USER_ERROR);

	// Инициализируем возвращаемый массив
	$result = array();

	$db_connection = db_connect();
	$db_result = mysqli_query($db_connection, $query);
	confirm_db_query ($db_result, $db_connection);

	// Заполняем возвращаемый массив
	while ($row = mysqli_fetch_assoc($db_result)) $result[] = array_shift($row);
	
	// Освобождаем ответ базы данных
	mysqli_free_result($db_result);
	
	// Закрываем соединение с базой данных
	mysqli_close ($db_connection);

	// Если есть какой ответ для запроса, то возвращаем массив с ответами
	if (!empty($result)) return $result;
	else return null; // иначе возвращаем null
}