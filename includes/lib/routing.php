<?php
require_once "db_functions.php";

function redirect_to ($new_location, $message = "") {
	header ('Location: ' . $new_location);
	exit($message);
}

/*
	Имеется модули и подмодули. Я назвал их по единому шаблону modules_with_rank_{$scalar_integer}. 
	Основной (главный) модуль имеет ранг 0. То есть имеет название "modules_with_rank_0". У основного модуля таблица имеет такой вид:

	| id | module | 

	У вложенных модулей с рангом n>0, таблица имеет следующий вид:

	| id | module | parent_id |

	Таблица маршрутов имеет такой вид (route_owner_id - либо пост, либо категория)):

	| id | route | module_id | module_rank | route_owner_id |
	


*/

function get_modules_name_pattern () {
	// Шаблон для названия модулей должен указываться только тут
	return "modules_with_rank_";
}

function get_modules_rank_num () {
	
	$pattern = get_modules_name_pattern (); // Получаем шаблон для названий модулей

	// Делаем запрос в базу, чтобы получить список таблиц, названия которых соответствуют заданному шаблону
	// Для каждого модуля опреленного ранга отдельная таблица
	// Количество элементов в списке таблиц — количество рангов 
	$db_respond = db_query("SHOW TABLES LIKE '%{$pattern}%'"); 

	// Если список не пуст, возвращаем количество элементов списка
	if ($db_respond) return count($db_respond);

	return 0; // Функция по умолчанию возвращает 0. То есть если произошла какая-то ошибка, то возвращается 0
}

function module_exists ($module, $module_rank = 0) {

	$modules_name_pattern = get_modules_name_pattern(); // Получаем шаблон для названий модулей
	
	// Проверяем входные данные
	if (!isset($module))
	{
		trigger_error("First argument expected.");
	}
	elseif (gettype($module_rank) != 'integer')
	{
		trigger_error("Second argument must be a scalar integer."); 
	}
	elseif (gettype($module_rank) !== "integer" && $module_rank < 0)
	{
		
	}
	elseif ($module_rank > (get_modules_rank_num() - 1))
	{
		return false;
	}	
	elseif (gettype($module) != 'string')
	{
		trigger_error("First argument must be a string value."); 
	}
	else // Если входные данные валидные, то проверяем существует ли модуль
	{		
		if ($module === "/") {
			// Если в качестве модуля указана косая черта, то заменяем её на "root". Так как в базе корневой модуль указан как "root"
			$module = "root";
		}

		// Делаем запрос в базу, чтобы получить модуль с указанным названием
		if (db_query("SELECT module FROM {$modules_name_pattern}" . $module_rank . " WHERE module = '" . $module . "' LIMIT 1"))
		{
			// Если база данных возвратит хоть что-нибудь, то возвращаем true (Указывая, что такой модуль существует).
			return true;
		}
	}

	return false; // Функция по умолчанию возращает false. То есть если произошла какая-то ошибка, то возвращается false
}

function get_module_id ($module, $module_rank = 0) {

	// Проверяем входные данные
	if (!isset($module))
	{
		trigger_error("First argument expected.");
	}
	elseif (gettype($module) !== "string")
	{
		trigger_error("First argument must be a string value");
	}
	elseif (gettype($module_rank) !== "integer" && $module_rank < 0)
	{
		trigger_error("Second argument must be a positive scalar integer or zero");
	}
	elseif ($module_rank > (get_modules_rank_num() - 1) ) return null; // Если указанный ранг модуля выходит за рамки допустимого значения (слишком большое), то возвращаем null
	else
	{
		// Если входные данные валидные, то получаем шаблон для названий модулей
		$modules_name_pattern = get_modules_name_pattern(); 

		// Получаем id указанного модуля, и возвращаем его.
		$query = "SELECT module_id FROM {$modules_name_pattern}{$module_rank} WHERE module = '" . $module . " LIMIT 1";
		$db_respond = db_query($query);
		// Если база данных возвратила хоть что-то, то возвращаем это как $module_id
		if ($db_respond) return $module_id;
	}

	// Функция по умолчанию возвращает null. То есть если произошла какая-то ошибка, то возвращается null
	return null;
}

function get_submodules_from ($module_id, $module_rank = 0) {	

	// Проверяем входные данные
	if (!isset($module_id))
	{
		trigger_error("First argument expected.");
	}
	elseif (gettype($module_id) != 'integer')
	{
		trigger_error("First argument must be a positive scalar integer.");
	}
	elseif(gettype($module_rank) != 'integer' && $module_rank < 0)
	{
		trigger_error("Second argument must be a positive scalar integer or zero."); 
	}
	elseif ($module_rank > (get_modules_rank_num() - 1))
	{
		// Если указанный ранг модуля выходит за рамки допустимого значения (слишком большое), то возвращаем null
		return null;
	}
	else
	{
		// Если входные данные валидные, то получаем шаблон для названий модулей
		$modules_name_pattern = get_modules_name_pattern();

		// Получаем список подмодулей из базы данных
		$submodules = db_query("SELECT * FROM `{$modules_name_pattern}" . ($module_rank++) . "` WHERE parent_id = " . $module_id);

		// Если база данных возвратила хоть что-нибудь, то возвращаем это как список подмодулей
		if ($submodules) return $submodules;
	}

	// Функция по умолчанию возвращает null. То есть если произошла какая-то ошибка, то возвращается null
	return null;
}

function set_route ($route, $route_owner_id, $module_id, $module_rank = 0) {
	
	// Проверяем входные данные
	if (!isset($route) && !isset($module_id) && !isset($route_owner_id))
	{
		trigger_error("Some argument expected.");
	}
	elseif (gettype($route) != 'string')
	{
		trigger_error("First argument must be a string value.");
	}
	elseif (gettype($module_id) != 'integer' && gettype($route_owner_id) != 'integer')
	{
		trigger_error("Second and third arguments must be a positive scalar integer.");
	}
	elseif (gettype($module_rank) != 'integer' && $module_rank < 0)
	{
		trigger_error("The last argument must be a positive scalar integer or zero");
	}
	elseif ($module_rank > (get_modules_rank_num() - 1))
	{
		// Если указанный ранг модуля выходит за рамки допустимого значения (слишком большое), то возвращаем false
		trigger_error("Specified rank doesn't exist");
		return false;
	}
	else
	{
		// Если входные данные валидные, то подготавливаем $route для внесения в базу данных
		safe_str($route);

		// Если таблица маршрутов не пуста и если такой маршрут не занят
		if ( ($routes = routes($module_rank) !== null) && (array_search($route, $routes) === false) )
		{
			// тогда  сохраняем указанный маршурт в таблицу маршрутов
			$query = "INSERT INTO routes (";
			$query .= "route, module_id, route_owner_id, module_rank";
			$query .= ") VALUES (";
			$query .= "{$route}, {module_id}, {$route_owner_id}, {$module_rank}";
			$query .= ");";
		}
	}

	// Функция по умолчанию возвращает false. То есть если произошла какая-то ошибка, то возвращается false
	return false;
}

function routes ($module_rank = 0) {

	// Проверяем входные данные
	if (gettype($module_rank) != 'integer'&& $module_rank < 0)
	{
		trigger_error("The argument must be a positive scalar integer or zero");
	}
	elseif ($module_rank > (get_modules_rank_num() - 1))
	{
		// Если указанный ранг модуля выходит за рамки допустимого значения (слишком большое), то возвращаем null
		return null;
	}
	else
	{
		// Если входные данные валидные, то делаем запрос в базу данных на извлечение списка маршрутов 
		$routes = db_query("SELECT route FROM routes WHERE module_rank = " . $module_rank);

		// Если список не пуст, возвращаем её
		if ($routes) return $routes;
	}

	// Функция по умолчанию возвращает null. То есть если произошла какая-то ошибка, то возвращается null
	return null;
}

function route_exists ($route, $module_rank = 0) {

	// Проверяем входные данные
	if (!isset($route))
	{
		trigger_error("First argument expected.");
	}
	elseif (gettype($route) !== "string")
	{
		trigger_error("First argument must be a string value");
	}
	elseif (gettype($module_rank) != 'integer'&& $module_rank < 0)
	{
		trigger_error("Second argument must be a positive scalar integer or zero");
	}
	elseif ($module_rank > (get_modules_rank_num() - 1))
	{
		// Если указанный ранг модуля выходит за рамки допустимого значения (слишком большое), то возвращаем false
		return false;
	}
	else 
	{
		// Если входные данные валидные, то делаем запрос в базу данных на извлечение указанного маршрута 
		$query = "SELECT * FROM routes WHERE route = '" . $route . "' AND module_rank = " . $module_rank;

		// Если база данных возвратила хоть что-нибудь, то функция возвратит true
		if (db_query($query)) return true;
	}
	
	// Функция по умолчанию возвращает false. То есть если произошла какая-то ошибка, то возвращается false
	return false;
}

function get_module_of_route ($route, $module_rank = 0) {

	// Проверяем входные данные
	if (!isset($route))
	{
		trigger_error("First argument expected.");
	}
	elseif (gettype($route) !== "string")
	{
		trigger_error("First argument must be a string value");
	}
	elseif (gettype($module_rank) != 'integer'&& $module_rank < 0)
	{
		trigger_error("Second argument must be a positive scalar integer or zero");
	}
	elseif ($module_rank > (get_modules_rank_num() - 1))
	{
		// Если указанный ранг модуля выходит за рамки допустимого значения (слишком большое), то возвращаем null
		return null;
	}
	else
	{
		// Если входные данные валидные, то делаем запрос в базу данных на извлечение id модуля, соответствующего указанному маршруту
		$query = "SELECT module_id FROM routes WHERE route = '" . $route . "' AND module_rank = " . $module_rank . " LIMIT 1";

		$db_respond = db_query($query);

		// Если база данных возвратила хоть что-нибудь, устанавливаем полученное значение как id модуля
		if ($db_respond)  $module_id = array_shift($db_respond);
		else return null;

		// Получаем шаблон для названий модулей
		$modules_name_pattern = get_modules_name_pattern();

		// Делаем запрос в базу данных на извлечение имени модуля с указанными id и рангом
		$query = "SELECT module FROM " . $modules_name_pattern . $module_rank . " WHERE id = " . $module_id . " LIMIT 1";
		$db_respond = db_query($query);

		// Если база данных возвратила хоть что-нибудь, то возвращаем это
		if ($db_respond) return $db_respond;
	}

	// Функция по умолчанию возвращает null. То есть если произошла какая-то ошибка, то возвращается null
	return null;
}

function router () {

	// Получаем запрашиваемый путь
	$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

	// Разбиваем путь на части
	$parts = explode('/', trim($path,'/'));

	// Получаем количество частей пути
	$parts_num = count($parts);

	// Устанавливаем значение по умолчанию для возвратимых данных
	$inf = array ("module" => "/", "module_rank" => 0, "route" => "/");

	// Если путь имеет только одну часть, то проверяем следующие условия
	if ($parts_num == 1) {

		if ($parts[0] === "" || $parts[0] === "index.php") // Если путь это пустая строка, то возвращаем данные по умолчанию
		{
			return $inf;
		}
		elseif (module_exists($parts[0]) === false) 
		{
			// Если путь не является маршрутом, то проверяем не является ли путь маршрутом
			// Если путь является маршрутом, то указываем корневой модуль, иначе указываем модуль "not_found"
			$module = route_exists($route = $parts[0]) ? "root" : "not_found";
			$inf["module"] = $module; 
			$inf["route"] = $route; 
			return $inf;
		}
		else
		{
			// Если путь является модулем, то указываем его как модуль
			$inf["module"] = $parts[0]; 
			return $inf;
		}
	}
	else 
	{
		// Если путь имеет несколько частей, то проверяем является ли первая часть пути модулем
		// Если так, то указываем первую часть пути как основной модуль (с рангом 0)
		// Если первая часть не является модулем, то проверяем является ли весь путь маршрутом
		if (module_exists($parts[0])) $module = $parts[0];
		elseif (route_exists($route = implode("/", $parts))) {
			// Если весь путь является маршрутом, то указываем его как маршрут
			$inf["route"] = $route;
			return $inf;
		}
		else
		{
			// Если первая часть пути не является модулем, также весь путь не является маршрутом, то указываем модуль "not_found", а весь путь оставляем как маршрут
			$inf["module"] = "not_found"; 
			$inf["route"] = implode("/", $parts); 
			return $inf;
		}

		// Проверяем каждую часть пути на соответствие следующим условиям
		// Здесь $current_index должен соответствовать рангу модуля
		// Здесь $target_index является временной переменной, для хранения целевого индекса, чтобы определить откуда начинается неизвестная часть пути
		// Целевой индекс - индекс последнего определенного (существующего) модуля
		// Для переменной $target_index устанавливаем значение по умолчанию 1, так как здесь проверяются наличие подмодулей (данный блок выполняется если основной модуль указан корректно)
		$target_index = 1;
		foreach ($parts as $current_index => $part) {

			if (module_exists($part, $current_index))
			{
				// Если часть пути является модулем, то проверяем не является ли она последней частью пути
				if ($current_index < (count($parts) - 1))
				{
					// Если это не последняя часть пути, то указываем целевой индекс
					$target_index = $current_index;
					// Также если это не последняя часть пути, то указываем текущий модуль как родительский для следующего
					$inf["parent_module"] = $part;
				}
				else
				{	
					// Если часть пути является модулем, a также является последней частью пути, то
					// указываем её как модуль, и в качестве маршрута указываем косую черту (косая черта - корневой маршрут конкретного модуля), также указываем текущий индекс как ранг модуля
					$inf["module"] = $part;
					$inf["route"] = "/";
					$inf["module_rank"] = $current_index;

					return $inf;
				}
			}
			else {
				// Если часть пути не является модулем, то в временный массив положим часть пути, лежащее после целевого индекса. То есть определяем неизвестную часть пути
				$temp_array = array();
				foreach ($parts as $key => $value) {
					if ($key > $target_index) $temp_array[] = $value;
				}

				// Для начала устанавливаем неизвестную часть пути как маршрут
				$route = implode("/", $temp_array);

				// Если такой маршрут существует, то указываем в качестве ранга значение целевого индекса, так как маршрут лежит после целевого индекса
				if (route_exists($route, $target_index))
				{
					$rank = $target_index;
					// Здесь мы не проверям получаемые данные из функции get_module_of_route (), так как этот блок выполняется только если модуль известен
					$inf["module"] = get_module_of_route ($route, $rank); 
					$inf["module_rank"] = $rank; 
					$inf["route"] = $route;
					return $inf;
				}
				else
				{
					// Если неизвестная часть пути не является маршрутом, то указываем модуль "not_found", а в качестве маршрута указываем весь путь
					$inf["module"] = "not_found";
					$inf["route"] = implode("/", $parts); 
					return $inf;
				}
			}
		}
	}
}