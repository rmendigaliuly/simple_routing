<?php

require_once 'routing.php';
require_once "db_functions.php";
require_once "user_management.php";

function get_content ($route, $module, $module_rank, $parent_module) {
	
	// для content_inf["content"] по умолчанию ставим пустую строку
	$content_inf = array("content" => "");

	// проверяем входные данные
	if (!isset($route) && !isset($module) && !isset($module_rank) && !isset($parent_module)) {
		trigger_error("Arguments expected.");
	}
	if (gettype($route) !== 'string' && gettype($module) !== "string" && gettype($parent_module) !== "string")
	{
		trigger_error("First and second arguments must be a string value");
	}
	elseif (gettype($module_rank) !== "integer")
	{
		trigger_error("Third argument must be a positive scalar integer");
	}

	// Уровень вложенности подмодулей определяется рангом модуля. Самый высший ранг - нулевой
	if ($module_rank === 0)
	{
		switch ($module) {
		 	case "/":
			{
				if ($route != "/") {
					// Эти строки не должны находиться тут
					// Нужно их переместить в новый файл post_management.php
					$module_id = get_module_id($module, $module_rank);

					$query = "SELECT route_owner_id FROM routes WHERE route={$route} AND module_id={$module_id} AND module_rank={$module_rank} LIMIT 1";
					$post_id = db_query($query);

					$query = "SELECT * FROM posts WHERE id={$post_id}";
					if ($content = db_query($query)) $content_inf["content"] = $content;

					$content_inf["page"] = "post";
					$content_inf["context"] = "public";
				}
				else {
					$content_inf["page"] = "home";
					$content_inf["context"] = "public";
				}
			}
			break;
			case "login":
			{
				$content_inf["page"] = "login";
				$content_inf["context"] = "admin";
			}
			break;
			case "admin":
			{
				$content_inf["page"] = "admin";
				$content_inf["context"] = "admin";
			}
			break;
			default: {
				$content_inf["page"] = "not_found";
				$content_inf["context"] = "public";
			}
		} // endswitch ($module)
	} // endif ($module_rank === 0)
	elseif ($module_rank === 1)
	{
		switch ($parent_module) {
			case "admin": {
				switch ($module) {
					case 'manage_content':
					{
						$content_inf["page"] = "manage_content";
						$content_inf["context"] = "admin";
					}
					break;
					case 'manage_users':
					{
						$content_inf["page"] = "manage_users";
						include "/../layouts/users_table.php";
						$content_inf["content"] = users_table();
						$content_inf["context"] = "admin";
					}
					break;
					default: {
						$content_inf["page"] = "not_found";
						$content_inf["context"] = "public";
					}
				} // end of switch ($module)
			} // end of case "admin"
			break;
			default: {
				$content_inf["page"] = "not_found";
				$content_inf["context"] = "public";
			}
		}	
	} // end of elseif ($module_rank === 1)
	elseif ($module_rank === 2)
	{
		switch ($parent_module) {
			case "manage_users": {
				switch ($module) {
					case 'add':
					{
						$content_inf["page"] = "add_user";
						$content_inf["context"] = "admin";
					}
					break;
					default: 
					{
						$content_inf["page"] = "not_found";
						$content_inf["context"] = "public";
					}
				}
			}
			break;
			default:
			{
				$content_inf["page"] = "not_found";
				$content_inf["context"] = "public";
			}
		}
		
	} // end of elseif ($module_rank === 2)
	
	// Если не имеется информация о контенте, то возвращаем null
	if (empty($content_inf)) return null;
	else return $content_inf; // если имеется информация о контенте, то возвращаем её

	// по умолчанию функция фозвращает null
	return null;
}

function show_page ($page, $context = "public", $content = "") {

	// Проверяем входные данные
	if (!isset($page)) trigger_error("Arguments are expected.", E_USER_ERROR);
	if (gettype($page) !== "string" && gettype($context) !== "string" && gettype($content) !== "string")
	{
		trigger_error("Arguments must be a string value.", E_USER_ERROR);
	} // endif

	include "/../layouts/pages_header.php";
					
	// Выводим header страницы
	print pages_header($context);

	// В зависимости от контекста будет выведено содержимое страницы
	if ($context === "admin")
	{
		switch ($page) {
				case 'admin': {
					include "/../layouts/admin_page.php";				

					print admin_page();
				}
				break;
				case "manage_content": {
					include "/../layouts/manage_content_page.php";
					
					print manage_content_page();
				}
				break;
				case "manage_users": {
					include "/../layouts/manage_users_page.php";
					
					print manage_users_page();
				}
				break;
				case "add_user": {
					include "/../layouts/add_user_page.php";
					
					// Функция get_users_roles() объявлена user_management.php
					print add_user_page(get_users_roles ());
				}
				break;
				default: {
					include "/../layouts/login_page.php";
					
					print login_page();
				}
				break;
			} // end of switch ($page)	
	} // endif ($context === "admin")
	elseif ($context === "public")
	{
		switch ($page) {
			case 'post': {
				include "/../layouts/post_page.php";
				
				print post_page();
			}
			break;
			case 'home': {
				include "/../layouts/home_page.php";
					
				print home_page();
			}
			break;
			case "not_found": {
				include "/../layouts/not_found_page.php";
					
				print not_found_page();
			}
			break;
			case "login": {
				include "/../layouts/login_page.php";
				
				print login_page();
			}
			break;
			default: {
				include "/../layouts/home_page.php";
				
				print home_page();
			}
			break;
		} // end of switch ($page)
	} // end of elseif ($context === "public")

	include "/../layouts/pages_footer.php";
	
	// Выводим footer страницы
	print pages_footer($context);
}