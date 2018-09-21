<?php

function documents_head ($context = "public") {
	// Подготавливаем head для публичной области
	$output = <<<HTML

		<head>
			<meta charset="UTF-8">
			<link rel="stylesheet" href="/css/main.css" type="text/css">
			<title>Page Title</title>
		</head>

HTML;

	if ($context === "admin")
	{
		// Тут будет head для административной области
	} // endif($context === "admin")

	// Возвращаем разметку
	return $output;
} // end of function documents_head ()