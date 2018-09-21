<?php

function pages_header ($context = "public") {

	// Проверяем входные данные
	if (!is_string($context)) trigger_error("Argument must be a string value.", E_USER_ERROR);

	include ("documents_head.php");
	
	// Подготавливаем разметку
	$output = "<!DOCTYPE html><html lang=\"en\">" . documents_head($context);

	if ($context === "admin")
	{
		// Подготавливаем header для административной области
		$output .= <<<HTML

		<body>
			<header>
				<h1>Admin Area Title</h1>
			</header>
HTML;

	} // endif ($context === "admin")
	else
	{
		// Подготавливаем header для публичной области
		$output .= <<<HTML

		<body>
			<header>
				<h1>Public Area Title</h1>
			</header>
HTML;
	
	} // end of else

	// Возвращаем разметку
	return $output;
} // end of function pages_header ()